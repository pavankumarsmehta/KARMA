<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Order;
use App\Models\SparsOrder;
use App\Models\OrderDetail;
use DataTables;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SampleOrderExports;
use Session;
use GlobalHelper;
use Illuminate\Support\Facades\URL;
use Str;
use App\Http\Controllers\Traits\generalTrait;
use Mail;

class ReturnOrderController extends Controller
{
	use generalTrait;
	public function __construct()
    {
        $this->current_url = URL::full();
        $this->prefix = config('const.DB_TABLE_PREFIX');
    }
	//-----------return order---------------
	public function returnOrderList(Request $request)
	{
		$filterStatus = $request->filterStatus;
		$filterCustomer = $request->filterCustomer;
		$filterStartDate = $request->filterStartDate;
		$filterEndDate = $request->filterEndDate;

		if (!isset($filterStartDate)) {
			$filterStartDate 	= date('m/d/Y', mktime(0, 0, 0, date('m'), date('d') - 15, date('Y')));
		}
		if (!isset($filterEndDate)) {
			$filterEndDate 	= date('m/d/Y', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
		}
		if (request()->ajax()) {

			$model = Order::select([
				'order_id',
				'customer_id',
				'bill_first_name',
				'bill_last_name',
				'bill_email',
				'order_datetime',
				'sub_total',
				'shipping_amt',
				'tax',
				'order_total',
				'status',
				'return_request_read_unread'
			]);

			if (isset($filterCustomer) && !empty($filterCustomer)) {
				$model->where('customer_id', $filterCustomer);
			}

			// if ($filterStartDate != '') {
			// 	$model->where(DB::raw("(DATE_FORMAT(order_datetime, '%Y-%m-%d'))"), '>=', date("Y-m-d", strtotime($filterStartDate)));
			// }
			// if ($filterEndDate != '') {
			// 	$model->where(DB::raw("(DATE_FORMAT(order_datetime, '%Y-%m-%d'))"), '<=', date("Y-m-d", strtotime($filterEndDate)));
			// }

			$model->whereIn('status',['Return Request', 'Return Request Accepted', 'Return Request Rejected', 'Partially Accepted/Rejected']);

			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function ($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->order_id . '" />';
			});

			$table->editColumn('order_id', function ($row) {
				return "<a href=" . route('pnkpanel.order.return_order.details', $row->order_id) . ">" . $row->order_id . "</a>";
			});

			$table->editColumn('spars_order_id', function ($row) {
				if ($row->spars_order_id) {
					$sparslink = $row->spars_order_id;
				} else {
					$sparslink = "-";
				}

				return $sparslink;
			});

			$table->editColumn('order_datetime', function ($row) {
				return Carbon::parse($row->order_datetime)->format('m/d/Y H:i:s');
			});

			$table->addColumn('customer', function ($row) {
				return $row->bill_first_name . ' ' . $row->bill_last_name;
			});
			$table->editColumn('bill_email', function ($row) {
				return $row->bill_email;
			});
			$table->editColumn('sub_total', function ($row) {
				return '$' . number_format($row->sub_total, 2, '.', '');
			});
			$table->editColumn('tax', function ($row) {
				return '$' . number_format($row->tax, 2, '.', '');
			});
			$table->editColumn('shipping_amt', function ($row) {
				return '$' . number_format($row->shipping_amt, 2, '.', '');
			});
			$table->editColumn('order_total', function ($row) {
				return '$' . number_format($row->order_total, 2, '.', '');
			});
			
			$table->rawColumns(['checkbox', 'order_id']);
			return $table->make(true);
		}

		$pageData['page_title'] = "Return Order List";
		$pageData['meta_title'] = "Return Order List";
		$pageData['breadcrumbs'] = [
			[
				'title' => 'Return Order List',
				'url' => route('pnkpanel.order.return_order')
			]
		];

		$customer_name = '';
		$customer = Customer::select('customer_id', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))->where('customer_id', $filterCustomer)->first();
		if ($customer) {
			$customer_name = $customer->full_name;
		}
		return view('pnkpanel.return_order.list', compact('customer_name', 'filterStartDate', 'filterEndDate'))->with($pageData);
	}

	public function bulkDelete(Request $request)
	{
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;
		if ($actType == 'delete') {
			$id_str = $request->ids;
			if (empty($id_str)) {
				$success = false;
				$errors = ["message" => ["Please select record(s) to Delete."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				Order::destroy(explode(",", $id_str));
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_delete")]];
				$response_http_code = 200;
			}
		}
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

	public function returnOrderDetails($order_id)
	{
		$order = Order::find($order_id);
		if (is_null($order)) {
			session()->flash('site_common_msg_err', 'Wrong order selected, please choose a order from list to view detail.');
			return redirect()->route('pnkpanel.order.list');
		}
		$order->return_request_read_unread = '1';
		$order->save();
		$customerOtherOrders = Order::select('order_id')->where('customer_id', $order->customer_id)->where('order_id', '<>', $order_id)->orderBy('order_id', 'desc')->get();
		
		// $SparsOrderLog = SparsOrder::select('*')->where('order_id', $order_id)->orderBy('log_id', 'desc')->get();

		$pageData['page_title'] = 'Order Details # ' . $order_id;
		$pageData['meta_title'] = 'Order Details # ' . $order_id;

		if($order->returnAcceptedOrderDetails->sum('total_price') > 0){
			$order->total_accepted_returned_amount = $order->returnAcceptedOrderDetails->sum('total_price');
		}else{
			$order->total_accepted_returned_amount = 0;
		}

		if($order->returnRejectedOrderDetails->sum('total_price') > 0){
			$order->total_rejected_returned_amount = $order->returnRejectedOrderDetails->sum('total_price');
		}else{
			$order->total_rejected_returned_amount = 0;
		}
		
		$pageData['breadcrumbs'] = [
			[
				'title' => 'Order List',
				'url' => route('pnkpanel.order.list')
			],
			[
				'title' => 'Order Details # ' . $order_id,
				'url' => route('pnkpanel.order.details', $order_id)
			]
		];
		return view('pnkpanel.return_order.details', compact('order', 'customerOtherOrders'))->with($pageData);
	}

	public function acceptRejectReturnOrder(Request $request)
	{
		if(count($request->selectedValues) > 0){
			$orderItemIds = [];
			$order_id = $request['order_id'];
			$order = Order::find($order_id);
			if (is_null($order)) {
				session()->flash('site_common_msg_err', 'Wrong order selected, please choose a order from list to view detail.');
				return redirect()->route('admin.order.return_order');
			}
			$customerID = (int)Session::get('customer_id');
			foreach($request->selectedValues as $key => $value){
				
				$orderDetail = OrderDetail::find($value);
				if($orderDetail){
					if($request->type == 'accept' || $request->type == 'bulk_accept'){
						$orderDetail->return_request_accept_reject = '1';
					}elseif($request->type == 'reject' || $request->type == 'bulk_reject'){
						$orderDetail->return_request_accept_reject = '0';
					}
					
					if($request->reason != ''){
						$orderDetail->return_request_accept_reject_reason = $request->reason;
					}
					$orderDetail->save();
					$success = true;
					$errors = [];
					$messages = ["message" => ["Return request status updated successfully."]];
					$response_http_code = 200;
					$orderItemIds[] = $value;
				}else{
					$success = false;
					$errors = ["message" => ["Invalid request."]];
					$messages = [];
					$response_http_code = 400;
				}
			}

			$orderItems = OrderDetail::where('order_id', $order_id)->where('is_return_request', '1')->get();
			$acceptedCount = $orderItems->where('return_request_accept_reject', '1')->count();
			$rejectedCount = $orderItems->where('return_request_accept_reject', '0')->count();

			if ($acceptedCount == $orderItems->count()) {
				$order->status = 'Return Request Accepted';
			} elseif ($rejectedCount == $orderItems->count()) {
				$order->status = 'Return Request Rejected';
			} else {
				$order->status = 'Partially Accepted/Rejected';
			}
			$order->save();

			if(count($orderItemIds) > 0){
				$customerID = $order->customer_id;
				$this->sendReturnRequestStatusEmail($order_id, $customerID, $orderItemIds, 'return_request_status');
			}
		}

		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}


	public function sendReturnRequestStatusEmail($orderID, $customerID,$orderItemIds, $type = 'return_request_status')
	{
		$SITE_URL 		= config('const.SITE_URL');
	
		$OrdersInfo 	= $this->getOrderData($orderID, $customerID);
		$OrderDetails 	= $this->getReturnOrderDetails($orderID, $customerID,$orderItemIds);
		
		if($type == 'return_request_status'){
			## Billing Detils Start
			##---------------------
			$billing_details	= '';
			$billing_address	= '';
			
			if(trim($OrdersInfo->bill_address1) != "") { $billing_address .= $OrdersInfo->bill_address1; }
			if(trim($OrdersInfo->bill_address2) != "") { $billing_address .= '<br>'.$OrdersInfo->bill_address2; }
			
			$billing_details .='<table border="0" class="fullbox spacing_smallnone" cellpadding="0" cellspacing="0" style="width:100%; padding:0 20px;">
				<tr class="flex" align="left">
					<td class="" style="color:rgba(51, 51, 51);font-size:20px;font-family:\'Lato\', sans-serif;padding:0px 10px;font-weight:700; text-transform:uppercase;">Billing Address</td>
				</tr>
				<tr><td class="flex" height="20"></td></tr>
				<tr class="flex" align="left">
					<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">'.$OrdersInfo->bill_first_name.' '.$OrdersInfo->bill_last_name.'<br clear="hide">'.$billing_address.',<br clear="hide">'.$OrdersInfo->bill_city.', '.$OrdersInfo->bill_state.' - '.$OrdersInfo->bill_zip.', '.$OrdersInfo->bill_country.'</td>
				</tr>
				<tr class="flex" align="left">
					<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">Phone: <a href="tel:+'.$OrdersInfo->bill_phone.'" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->bill_phone.'</a></td>
				</tr>
				<tr class="flex" align="left">
					<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">Email: <a href="#" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->ship_email.'</a></td>
				</tr>
			</table>';
			## Billing Detils End
			##---------------------
			
			## Shipping Details Start
			##-----------------------
			$shipping_details	= '';
			$shipping_address	= '';
			
			if(trim($OrdersInfo->ship_address1) != "") { $shipping_address .= $OrdersInfo->ship_address1; }
			if(trim($OrdersInfo->ship_address2) != "") { $shipping_address .= '<br>'.$OrdersInfo->ship_address2; }
			
			$shipping_details .='<table border="0" class="fullbox spacing_smallnone" cellpadding="0" cellspacing="0" style="width:100%; padding:0 20px;">
				<tr class="flex" align="left">
					<td class="" style="color:rgba(51, 51, 51);font-size:20px;font-family:\'Lato\', sans-serif;padding:0px 10px;font-weight:700; text-transform:uppercase;">Shipping Address</td>
				</tr>
				<tr><td class="flex" height="20"></td></tr>
				<tr class="flex" align="left">
					<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">'.$OrdersInfo->ship_first_name.' '.$OrdersInfo->ship_last_name.'<br clear="hide">'.$shipping_address.',<br clear="hide"> '.$OrdersInfo->ship_city.' - '.$OrdersInfo->ship_state.' - '.$OrdersInfo->ship_zip.', '.$OrdersInfo->ship_country.'</td>
				</tr>
				<tr class="flex" align="left">
					<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">Phone: <a href="tel:+'.$OrdersInfo->ship_phone.'" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->ship_phone.'</a></td>
				</tr>
				<tr class="flex" align="left">
					<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">Email: <a href="#" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->ship_email.'</a></td>
				</tr>
			</table>';
			## Shipping Details End
			##-----------------------
		}
		
		//Item Detail Start
			
			$STR_EMAIL_ITEMS ='
			<tr>
				<td>
					<table style="width:100%; font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;" cellpadding="0" cellspacing="0" align="center">
						<tbody>
							<tr style="margin:0px; padding:0px;" align="center">
								<td style="margin:0px; padding:10px 5px 10px 0px; border-bottom:1px solid #e2e6ea;" align="left"><strong>Item&nbsp;Description</strong></td>
								<td style="margin:0px; padding:10px 5px; border-bottom:1px solid #e2e6ea;"><strong>Status</strong></td>
								<td style="margin:0px; padding:10px 5px; border-bottom:1px solid #e2e6ea;"><strong>Reason</strong></td>
							</tr>';
											
							//For Loop Start
							$OrderDetails = json_decode(json_encode($OrderDetails), true);
							for($od=0;$od<count($OrderDetails);$od++)
							{
							if($OrderDetails[$od]['return_request_accept_reject'] == '1'){
								$acceptRejectTopMessage = 'Your Return Request has been approved, we will refund you the amount and it will credited in original payment mode within 48 hours.';
								$acceptRejectStatus = "Accepted";
							}else{
								$acceptRejectTopMessage = 'Unfortunately as mentioned in our return policy, we are not able to accept your returns. For more information, you can read the full return policy <a href="https://www.hbastore.com/pages/return-and-refund">here</a>.';
								$acceptRejectStatus = "Rejected";
							}
							$OrderDetails[$od]['return_request_accept_reject'] = ($OrderDetails[$od]['return_request_accept_reject'] == '1') ? 'Accepted' : 'Rejected';
							$STR_EMAIL_ITEMS .='<tr style="margin:0px; padding:0px;" align="center">
								<td style="margin:0px; padding:10px 5px 10px 0px;" align="left">
									<table cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td style="width:55px;">
													<img src="'.$OrderDetails[$od]['Image'].'" alt="'.$OrderDetails[$od]['product_name'].'" style="width:50px; height:50px;">
												</td>
												<td style="font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">
													<strong><a href="'.$OrderDetails[$od]['product_url'].'" style="text-decoration:none;"><font color="#000000">'.$OrderDetails[$od]['product_name'].'</font></a></strong>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="margin:0px; padding:10px 5px; font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">'.$OrderDetails[$od]['return_request_accept_reject'].'</td>
								<td style="margin:0px; padding:10px 5px;">'.$OrderDetails[$od]['return_request_accept_reject_reason'].'</td>
							</tr>';
							//For Loop End
							}
							$STR_EMAIL_ITEMS .='
						</tbody>
					</table>
				</td>
			</tr>';
		
		##Send Email TO Customer
		##---------------------
		$res_mail = GetMailTemplate("RETURN_REQUEST_STATUS");
		$ToEmail 	= $OrdersInfo->ship_email;
		$FromEmail 	= config('const.CONTACT_MAIL');
		$Subject	= $res_mail[0]->subject. " ". $acceptRejectStatus ." - Order# ". $OrdersInfo->order_id;
		
		$EmailBody = $res_mail[0]->mail_body;
		
		$EmailBody = str_replace('{$SITE_URL}',  config('app.url'), $EmailBody);
		$EmailBody = str_replace('{$first_name}',  $OrdersInfo->bill_first_name, $EmailBody);
		$EmailBody = str_replace('{$last_name}',  $OrdersInfo->bill_last_name, $EmailBody);
		$EmailBody = str_replace('{$order_no}',  $OrdersInfo->order_id, $EmailBody);
		if($type == 'return_request_status'){
			// $EmailBody = str_replace('{$billing_address}', $billing_details, $EmailBody);
			// $EmailBody = str_replace('{$shipping_address}', $shipping_details, $EmailBody);
			$EmailBody = str_replace('{$acceptRejectTopMessage}', $acceptRejectTopMessage, $EmailBody);
		}
		$EmailBody = str_replace('{$ordered_items}', $STR_EMAIL_ITEMS, $EmailBody);
		$EmailBody = str_replace('{CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);
		$EmailBody = str_replace('{TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
		$EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);
		$EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
		$EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
		//echo $EmailBody; exit;
		$From = config('Settings.CONTACT_MAIL');
		## Send Email TO Customer
		##------------------------
		$a = $this->sendSMTPMail($ToEmail, $Subject, $EmailBody, $From );
		$b = $this->sendSMTPMail('sachin.qualdev@gmail.com', $Subject, $EmailBody, $From );
		
	}

	public function getOrderData($orderID, $customerID)
	{
		$SITE_URL 		= config('const.SITE_URL');
		$OrdersInfo = DB::table($this->prefix.'orders')
									->select('*')
									->where('order_id','=',$orderID)
									->where('customer_id','=',$customerID)->first();
		
		$OrdersInfo->merchandise_subtotal = '0.00';
		$OrdersInfo->merchandise_subtotal = $OrdersInfo->sub_total - ( $OrdersInfo->auto_discount + $OrdersInfo->quantity_discount + $OrdersInfo->coupon_amount);
		
		return $OrdersInfo;						
	}
	
	public function getReturnOrderDetails($orderID, $customerID, $orderItemIds=array())
	{
		$OrderDetails = DB::table($this->prefix.'order_detail')
			->select('*')
			->where('order_id','=',$orderID)
			->whereIn('order_detail_id', $orderItemIds)
			->orderBy('order_detail_id')->get();
		
		return $OrderDetails;						
	}
	
}
