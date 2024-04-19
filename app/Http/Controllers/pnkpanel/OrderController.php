<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pnkpanel;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\EmailTemplates;
use App\Models\SiteSetting;
use DataTables;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SampleOrderExports;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class OrderController extends Controller
{
	use CrudControllerTrait;
	public function model()
    {
        return Category::class;
    }
	public function list(Request $request)
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
				'status'
			]);

			if (isset($filterCustomer) && !empty($filterCustomer)) {
				$model->where('customer_id', $filterCustomer);
			}
			if (isset($filterStatus) && !empty($filterStatus)) {
				$model->where('status', $filterStatus);
			}

			if ($filterStartDate != '') {
				$model->where(DB::raw("(DATE_FORMAT(order_datetime, '%Y-%m-%d'))"), '>=', date("Y-m-d", strtotime($filterStartDate)));
			}
			if ($filterEndDate != '') {
				$model->where(DB::raw("(DATE_FORMAT(order_datetime, '%Y-%m-%d'))"), '<=', date("Y-m-d", strtotime($filterEndDate)));
			}


			//~ if(!request()->get('order') && $request->clone == 1) {
			//~ $model->orderBy('products.added_datetime', 'desc');
			//~ } elseif (!request()->get('order')) {+
			//~ $model->orderBy('products.order_id', 'desc');
			//~ }

			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function ($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->order_id . '" />';
			});
			$table->editColumn('order_id', function ($row) {
				return "<a href=" . route('pnkpanel.order.details', $row->order_id) . ">" . $row->order_id . "</a>";
			});

			
			$table->editColumn('order_datetime', function ($row) {
				return Carbon::parse($row->order_datetime)->format('m/d/Y H:i:s');
			});
			/*$table->addColumn('order_date', function($row) {
				return Carbon::parse($row->order_datetime)->format('m/d/Y');
			});
			$table->addColumn('order_time', function($row) {
				return Carbon::parse($row->order_datetime)->format('H:i:s');
			});*/
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
			//~ $table->editColumn('rank', function($row) {
			//~ return "<input type='text' id='display_position_".$row->products_id."' value='".$row->rank."' class='form-control input-sm' size='8'>";
			//~ });
			//~ $table->addColumn('action', function($row) {
			//~ return (string)view('pnkpanel.component.datatable_action', ['id' => $row->products_id]);
			//~ });
			$table->rawColumns(['checkbox', 'order_id']);
			return $table->make(true);
		}

		$pageData['page_title'] = "Order List";
		$pageData['meta_title'] = "Order List";
		$pageData['breadcrumbs'] = [
			[
				'title' => 'Order List',
				'url' => route('pnkpanel.order.list')
			]
		];

		$customer_name = '';
		$customer = Customer::select('customer_id', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))->where('customer_id', $filterCustomer)->first();
		if ($customer) {
			$customer_name = $customer->full_name;
		}
		return view('pnkpanel.order.list', compact('customer_name', 'filterStatus', 'filterStartDate', 'filterEndDate'))->with($pageData);
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
				//$this->model()::whereIn("id",explode(",",$id_str))->delete();
				Order::destroy(explode(",", $id_str));
				//OrderDetail::whereIn('order_id', explode(",",$id_str));
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_delete")]];
				$response_http_code = 200;
			}
		}
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

	public function details($order_id)
	{
		/*
		$result = Order::findOrFail($order_id);
		dump($result->customer);
		echo '<br/><br/>';
		//echo $result->customer->first_name;
		
		$result = Order::findOrFail($order_id);
		foreach($result->orderItems as $orderItem) {
			dump($orderItem); echo '<br/>';
			//echo '<br/>'.$orderItem->orders_detail_id. '<br/>';
		}
		echo '<br/><br/>';
		
		$result = Order::findOrFail($order_id);
		//dd($result->products);
		foreach($result->products as $product) {
			dump($product); echo '<br/>';
			//echo '<br/>'.$product->products_id. '<br/>';
			//echo '<br/>'.$product->product_description. '<br/>';
		}
		echo '<br/><br/>';
		*/

		$order = Order::find($order_id);

		
		if (is_null($order)) {
			session()->flash('site_common_msg_err', 'Wrong order selected, please choose a order from list to view detail.');
			return redirect()->route('pnkpanel.order.list');
		}
		//dump($order->previous()->order_id); exit;
		//dump($order->customer->orders); exit;
		$customerOtherOrders = Order::select('order_id')->where('customer_id', $order->customer_id)->where('order_id', '<>', $order_id)->orderBy('order_id', 'desc')->get();
		
		

		$pageData['page_title'] = 'Order Details # ' . $order_id;
		$pageData['meta_title'] = 'Order Details # ' . $order_id;
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

		return view('pnkpanel.order.details', compact('order', 'customerOtherOrders'))->with($pageData);
	}

	public function update(Request $request)
	{
		$actType = $request->actType;
		$order_id = (int) $request->order_id;
		switch ($actType) {
			case 'UpdateShippingAddress':
				$this->updateShippingAddress($order_id, $request);
				break;
			case 'RefundOrder':
				$this->refundOrder($order_id, $request);
				break;
			case 'UpdateOrder':
				$this->updateOrder($order_id, $request);
				break;
			case 'SendMail':
				$this->sendMailToCustomer($order_id, $request);
				break;
			case 'SendStatusMail':
					$this->sendStatusMailToCustomer($order_id, $request);
					break;	
			default:
				//code to be executed
		}
		return redirect()->route('pnkpanel.order.details', $order_id);
	}

	private function updateShippingAddress($order_id, Request $request)
	{
		$validationRules = [
			'ship_first_name'	=> 'required|string',
			'ship_last_name'	=> 'required|string',
			'ship_address1'		=> 'required|string',
			'ship_city'				=> 'required|string',
			'ship_zip'				=> 'required|string',
			'ship_country' 		=> 'required|string',
		];
		$validationMessages = [
			'ship_first_name.required' => 'Please fill customer first name',
			'ship_last_name.required' => 'Please fill customer last name',
			'ship_address1.required' => 'Please fill address 1',
			'ship_city.required' => 'Please fill city',
			'ship_zip.required' => 'Please fill zip code',
			'ship_country.required' => 'Please fill country',
		];
		if ($request->ship_country == 'US') {
			$validationRules['ship_state'] = 'required|string';
			$validationMessages['ship_state.required'] = 'Please select state';
		}
		if ($request->ship_country != 'US') {
			$validationRules['ship_state_other'] = 'required|string';
			$validationMessages['ship_state_other.required'] = 'Please fill state';
		}
		$this->validate($request, $validationRules, $validationMessages);
		$order = Order::find($order_id);
		if (is_null($order)) {
			session()->flash('site_common_msg_err', 'Wrong order selected, please choose a order from list to view detail.');
			return redirect()->route('pnkpanel.order.list');
		}

		$order->ship_first_name = $request->ship_first_name;
		$order->ship_last_name = $request->ship_last_name;
		$order->ship_address1 = $request->ship_address1;
		$order->ship_address2 = $request->ship_address2;
		$order->ship_city = $request->ship_city;
		$order->ship_zip = $request->ship_zip;
		$order->ship_state = ($request->ship_country != 'US' ? $request->ship_state_other : $request->ship_state);
		$order->ship_country = $request->ship_country;
		$order->ship_phone = $request->ship_phone;
		$order->ship_email = $request->ship_email;

		if ($order->save()) {
			session()->flash('site_common_msg', 'Shipping address updated successfully.');
		} else {
			session()->flash('site_common_msg_err', 'Cannot Update Shipping address, Please Try Again.');
		}
	}

	private function refundOrder($order_id, Request $request)
	{
		$this->validate($request, [
			'refund_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
		], [
			'refund_amount.required' => 'Please enter refund amount value.',
			'refund_amount.regex' => 'Please enter only numeric value for refund amount.',
		]);
		//$data = Admin::user()->admin_id;
		//echo Admin::user()->admin_id;
		//print_r($data);
		//exit;
		//$order_res = Order::select('payment_type', 'total_refund_amount', 'refund_transaction_response', 'refund_comment')->where('order_id', $order_id)->first();
		$order_res = Order::find($order_id);

		if (is_null($order_res)) {
			session()->flash('site_common_msg_err', 'Wrong order selected, please choose a order from list to view detail.');
			return redirect()->route('pnkpanel.order.list');
		}
		$refund_amount = number_format($request->refund_amount, 2, '.', '');
		$refund_comment = $request->refund_comment;
		if ($refund_amount <= 0) {
			session()->flash('site_common_msg_err', 'Your Order can not been refund.<br>For refund order, Refund Amount must be greater than zero.');
			return redirect()->route('pnkpanel.order.details', $order_id);
		}
		if ($order_res['payment_type'] == 'PAYMENT_AUTHORIZENETCC') {
			require_once(PHYSICAL_PATH . "authorize_checkout/refund_authorize.php");
			$p_msg = process_Refund_Payment_Authorize($order_id, $refund_amount, $refund_comment);
			$err_msg = rawurlencode($p_msg);
			session()->flash('site_common_msg_err', $err_msg);
		} elseif ($order_res['payment_type'] == 'PAYMENT_PAYPALEC') {
			require_once(PHYSICAL_PATH . "paypal_checkout/refund_paypal.php");
			$p_msg = process_Refund_Payment_Paypal($order_id, $refund_amount, $refund_comment);
			$err_msg = rawurlencode($p_msg);
			session()->flash('site_common_msg_err', $err_msg);
		} else {
			$new_refund_comment  = $order_res->refund_comment;
			$new_refund_comment  .= $refund_comment . "<br><br> ";
			//$new_refund_comment  .= "Refund processed by : <b>" . Admin::user()->email . "</b> on date " . Carbon::now()->format('m/d/Y H:i:s') . "<br><br> ";
			$new_refund_comment  .= "Refund processed by : <b></b> on date " . Carbon::now()->format('m/d/Y H:i:s') . "<br><br> ";
			$total_refund_amount  = $order_res->total_refund_amount + $refund_amount;

			$order_res->total_refund_amount = number_format($total_refund_amount, 2, '.', '');
			$order_res->refund_transaction_response = '';
			$order_res->refund_comment = $new_refund_comment;
			if ($order_res->save()) {
				session()->flash('site_common_msg', 'Refund processed successfully.');
			} else {
				session()->flash('site_common_msg_err', 'Cannot Update Refund Details.');
			}
		}
	}

	private function updateOrder($order_id, Request $request)
	{
		//~ dd($request);
		############ Update order detail start hare#################
		$total_items = (int)$request->total_items;
		for ($i = 0; $i < $total_items; $i++) {
			$orderDetail = OrderDetail::find((int)$request->{'orders_detail_id' . $i});
			if (is_null($orderDetail)) {
				session()->flash('site_common_msg_err', 'Invalid Order Detail ID.');
				return redirect()->route('pnkpanel.order.details', $order_id);
			}
			$orderDetail->quantity = $request->{'quantity' . $i};
			$orderDetail->unit_price = $request->{'unit_price' . $i};
			$orderDetail->total_price = $request->{'total_price' . $i};
			$orderDetail->save();
		}
		############ Update order detail end hare#################

		################# Order update start here ################
		$order = Order::find($order_id);
		if (is_null($order)) {
			session()->flash('site_common_msg_err', 'Wrong order selected, please choose a order from list to view detail.');
			return redirect()->route('pnkpanel.order.list');
		}

		$order->sub_total = number_format($request->sub_total, 2, '.', '');
		$order->shipping_amt = number_format($request->shipping_amt, 2, '.', '');
		$order->tax = number_format($request->tax, 2, '.', '');
		//$order->gift_charge = 0;
		//$order->wire_discount = 0;
		$order->auto_discount = number_format($request->auto_discount, 2, '.', '');
		$order->quantity_discount = number_format($request->quantity_discount, 2, '.', '');
		$order->coupon_amount = number_format($request->coupon_amount, 2, '.', '');
		//$order->gc_amount = 0;
		$order->order_total = number_format($request->order_total, 2, '.', '');
		$order->status = $request->order_status;
		$order->pay_status = $request->pay_status;
		$order->order_comment = $request->order_comment;
		$order->admin_remark = $request->admin_remark;
		
		if($request->ship_status=="Shipped" && isset($request->ship_method) && $request->ship_method !='' && isset($request->tracking_no) && $request->tracking_no != '' && $request->is_shipmail=='0')
		{   
			$sendordershipkmail=$this->sendordershipkmail($order_id, $request, $request->ship_method, $request->tracking_no);
			
			if($sendordershipkmail==true)
			{
				$global_setting = SiteSetting::select('setting')->where('var_name','=','RETURN_DAYS')->get();
				if($global_setting[0]['setting']!='')
				{	
					$date = Carbon::now();
		  		    $date->addDays($global_setting[0]['setting']);
					$return_order_date=$date->format('Y-m-d');
					
					$UpdateOrderdate = [];
		            $UpdateOrderdate["return_order_last_date"]= $return_order_date;
		            $result1 = Order::where('order_id', '=', $order_id)
		                                            ->update($UpdateOrderdate);
					$order->is_shipmail='1';
					$order->ship_status = $request->ship_status;
		            $order->ship_method = $request->ship_method;
					$order->tracking_no = $request->tracking_no;
				}
			}
			
		}
		//Sales Order [117295265] has been created successfully.
		//$order->representative_id = $request->representative_id;

		if ($order->save()) {
			session()->flash('site_common_msg', 'Order details updated successfully.');
		} else {
			session()->flash('site_common_msg_err', 'Cannot Update Order details, Please Try Again.');
		}
		################# Order update end here ################
	}

	private function sendMailToCustomer($order_id, Request $request)
	{
	

		include_once("functions/utilities_function.php"); ## For email and thumb image

		$order = Order::find($order_id);
		if (is_null($order)) {
			session()->flash('site_common_msg_err', 'Wrong order selected, please choose a order from list to view detail.');
			return redirect()->route('pnkpanel.order.list');
		}

		## Billing Address Start Here
		$billing_address  = $order->bill_first_name . " " . $order->bill_last_name . "<br>";
		$billing_address .= $order->bill_address1 . ",<br>";
		if (isset($order->bill_address2) && $order->bill_address2 != '') {
			$billing_address .= $order->bill_address2 . "<br>";
		}
		$billing_address .= $order->bill_city . " - " . $order->bill_zip . "<br>";
		$billing_address .= $order->bill_state . " , " . $order->bill_country . "<br>";
		$billing_address .= "Phone : " . $order->bill_phone . "<br>";
		## Single Shipping Address Start Here	

		$shipping_address = '';
		$shipping_address = '<strong>Shipping Address :</strong><br>';
		$shipping_address .= $order->ship_first_name . " " . $order->ship_last_name . "<br>";
		$shipping_address .= $order->ship_address1 . ",<br>";
		if (isset($order->ship_address2) && $order->ship_address2 != '') {
			$shipping_address .= $order->ship_address2 . "<br>";
		}

		$shipping_address .= $order->ship_city . " - " . $order->ship_zip . "<br>";
		$shipping_address .= $order->ship_state . " , " . $order->ship_country . "<br>";
		$shipping_address .= "Phone : " . $order->ship_phone . "<br>";
		$ship_status = $order->ship_status;
		$ship_method = $order->ship_method;
		$tracking_no = $order->tracking_no;
		$str_ship = '<strong>Shipment Status : </strong>' . $ship_status . '<br>';
		if ($tracking_no == "") {
			$str_ship .= '<strong>Tracking Number :</strong> Not Available';
		} else {
			if ($ship_method == 'FedEx') {
				$str_ship .= '<strong>Shipping Via : </strong>' . $ship_method . '<br>';
				$str_ship .= "<strong>Tracking Number :</strong> <a href=\"http://www.fedex.com/Tracking?tracknumbers=" . $tracking_no . "\" target=\"_blank\">" . $tracking_no . "</a>";
			} elseif ($ship_method == 'USPS') {
				$str_ship .= '<strong>Shipping Via : </strong>' . $ship_method . '<br>';
				$str_ship .= "<strong>Tracking Number :</strong> <a href=\"http://www.usps.com/shipping/trackandconfirm.htm?from=home&page=0035trackandconfirm\" target=\"_blank\">" . $tracking_no . "</a>";
			} elseif ($ship_method == 'UPS') {
				$str_ship .= '<strong>Shipping Via : </strong>' . $ship_method . '<br>';
				$str_ship .= "<strong>Tracking Number :</strong> <a href=\"https://www.ups.com/mobile/track?trackingNumber=" . $tracking_no . "\" target=\"_blank\">" . $tracking_no . "</a>";
			} 
		}
		$shipping_address .= $str_ship;
		## FOR THE ORDERED ITEMS START HERE

		$orderItems = OrderDetail::where('order_id', $order_id)->get();
		$ordered_items = '<table style="width: 100%; font-family: Arial; font-size: 12px;" border="0" cellspacing="1" cellpadding="5" bgcolor="#dddddd">
                  <tbody>';
		$ordered_items .= '<tr style="background:#7c7875; color:#ffffff; padding:5px; font-weight:bold;">
						  <td width="150" align="center">Image</td>
						  <td height="25" align="center">Item Description</td>
						  <td width="10%" align="center">Total Price</td>
						</tr>';

		foreach ($orderItems as $orderItem) {
			$products_id = $orderItem->products_id;
			$product_sku = trim($orderItem->product_sku);
			$product_name = trim($orderItem->product_name);
			$thumb_image = getMixItemThumb($product_sku);
			## here items row start ##########
			$ordered_items .= '<tr align="center" bgcolor="#ffffff">';
			$ordered_items .= '<td width="150" align="center" valign="top">' . $thumb_image . '</td>';
			$ordered_items .= '<td valign="top"><table style="width: 100%; font-family:Arial; font-size:12px;" border="0" cellspacing="10" cellpadding="0">';
			if ($product_sku != '') {
				$ordered_items .= '<tr>
													<td><strong>Product SKU : </strong>' . $product_sku . '</td>
												</tr>';
			}
			if ($product_name != '') {
				$ordered_items .= '<tr>
												<td><strong>Product Name : </strong>' . stripslashes($product_name) . '</td>
											  </tr>';
			}
			if ($orderItem["attribute_info"] != '') {
				$ordered_items .= '<tr>
												<td>' . stripslashes($orderItem["attribute_info"]) . '</td>
											 </tr>';
			}

			$ordered_items .= '</table></td>';
			$ordered_items .= '<td width="10%" align="center">$' . $orderItem["total_price"] . '</td>';
			$ordered_items .= '</tr>';
			#### here items row end ###########
		}

		$ordered_items .= '<tr bgcolor="#f5f5f5"><td colspan="2" align="right"><b>Order Subtotal : </b></td>
						<td colspan="2"><b>$' . $order->sub_total . '</b></td></tr>';
		if ($order_res["shipping_amt"] > 0) {
			$ordered_items .= '<tr bgcolor="#f5f5f5"><td colspan="2" align="right">Shipping Charge : </td><td colspan="2">$' . $order->shipping_amt . '</td></tr>';
		}

		if ($order_res["tax"] > 0) {
			$ordered_items .= '<tr bgcolor="#f5f5f5"><td colspan="2" align="right">Sales Tax : </td><td colspan="2">$' . $order->tax . '</td></tr>';
		}

		if ($order_res["auto_discount"] > 0) {
			$ordered_items .= '<tr bgcolor="#f5f5f5"><td colspan="2" align="right">Auto Discount : </td><td colspan="2">$' . $order->auto_discount . '</td></tr>';
		}

		if ($order_res["quantity_discount"] > 0) {
			$ordered_items .= '<tr bgcolor="#f5f5f5"><td colspan="2" align="right">Quantity Discount : </td><td colspan="2">$' . $order->quantity_discount . '</td></tr>';
		}

		if ($order_res["coupon_amount"] > 0) {
			$ordered_items .= '<tr bgcolor="#f5f5f5"><td colspan="2" align="right">Coupon Discount : </td><td colspan="2">$' . $order->coupon_amount . '</td></tr>';
		}
		$ordered_items .= '<tr bgcolor="#f5f5f5"><td colspan="2" align="right"><b>Order Total : </b></td><td colspan="2"><b>$' . $order->order_total . '</b></td></tr>';
		$ordered_items .= ' </tbody> </table>';

		$mres = Get_Mail_Template("ORDER_STATUS");
		$mail_subject = stripslashes($mres[0]["subject"]);
		$mail_content = stripslashes($mres[0]["mail_body"]);
		$mail_subject = str_replace('{$SITE_NAME}', SITE_TITLE, $mail_subject);
		$mail_content = str_replace('{$first_name}', $order->bill_first_name, $mail_content);
		$mail_content = str_replace('{$last_name}', $order->bill_last_name, $mail_content);
		$mail_content = str_replace('{$order_no}', $order->order_id, $mail_content);
		$mail_content = str_replace('{$order_status}', $order->status, $mail_content);
		$mail_content = str_replace('{$payment_status}', $order->pay_status, $mail_content);
		$mail_content = str_replace('{$billing_address}', $billing_address, $mail_content);
		$mail_content = str_replace('{$shipping_address}', $shipping_address, $mail_content);
		$mail_content = str_replace('{$ordered_items}', $ordered_items, $mail_content);
		$mail_content = str_replace('{$TOLL_FREE_NO}', TOLL_FREE_NO, $mail_content);
		$mail_content = str_replace('{$Site_URL}', $Site_URL, $mail_content);

		//$vtoemail = $order->customer()->email;
		$onesendstat = SMTP_Mail_Send($order->bill_email, $mail_subject, $mail_content, CONTACT_MAIL);
		// Send the Mail to the Customer about Order Status End Here

		session()->flash('site_common_msg', 'Order Status Mail Sent Successfully!');
	}

	private function sendStatusMailToCustomer($order_id, Request $request)
	{

		//include_once("functions/utilities_function.php"); ## For email and thumb image

		$order = Order::find($order_id);
		if (is_null($order)) {
			session()->flash('site_common_msg_err', 'Wrong order selected, please choose a order from list to view detail.');
			return redirect()->route('pnkpanel.order.list');
		}

		$mres = GetMailTemplate("ORDER_STATUS");
		$mail_subject = stripslashes($mres[0]["subject"]);
		$mail_content = stripslashes($mres[0]["mail_body"]);
		$mail_subject = str_replace('{$order_status}', $order->status, $mail_subject);
		$mail_subject = str_replace('{$order_no}', $order->order_id, $mail_subject);
		$mail_content = str_replace('{$first_name}', ucwords($order->bill_first_name), $mail_content);
		$mail_content = str_replace('{$last_name}', ucwords($order->bill_last_name), $mail_content);
		$mail_content = str_replace('{$order_no}', $order->order_id, $mail_content);
		$mail_content = str_replace('{$order_status}', $order->status, $mail_content);
		$mail_content = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $mail_content);
		$mail_content = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $mail_content);	
		$mail_content = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $mail_content);	
		$EmailBody = ''; 
		$EmailBody = $mail_content;
	
		
		$EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
		$to = $order->bill_email;
		$from = config('Settings.CONTACT_MAIL');
		$sendMailStatus = $this->sendSMTPMail($to, $mail_subject, $EmailBody, $from );
		

		session()->flash('site_common_msg', 'Order Status Mail Sent Successfully!');
	}

	public function orderSlip(Request $request)
	{
		//~ $request_method = $request->method();
		//~ if ($request->isMethod('post')) {
		//~ } elseif($request->isMethod('get')) {
		//~ }

		$filterStartDate = $request->filterStartDate;
		//$filterEndDate = $request->filterEndDate;

		//$d_start_date = $request->d_start_date;
		//$d_end_date = $request->d_end_date;
		$start_id = $request->start_id;
		$end_id = $request->end_id;

		if (isset($filterStartDate)) {
			$allOrders = Order::where(DB::raw("DATE_FORMAT( order_datetime, '%Y-%m-%d' )"), date("Y-m-d", strtotime($filterStartDate)))->orderBy('order_id', 'asc')->get();
		} else {
			$allOrders = Order::where([['order_id', '>=', $start_id], ['order_id', '<=', $end_id]])->orderBy('order_id', 'asc')->get();
		}

		$pageData['page_title'] = 'Order Slip';
		$pageData['meta_title'] = 'Order Slip';
		$pageData['breadcrumbs'] = [];

		return view('pnkpanel.order.order_slip', compact('allOrders'))->with($pageData);
	}

	public function packingSlip(Request $request)
	{
		//~ $request_method = $request->method();
		//~ if ($request->isMethod('post')) {
		//~ } elseif($request->isMethod('get')) {
		//~ }

		$start_id = $request->start_id;
		$end_id = $request->end_id;

		$allOrders = Order::where([['order_id', '>=', $start_id], ['order_id', '<=', $end_id]])->orderBy('order_id', 'asc')->get();

		$pageData['page_title'] = 'Order Slip';
		$pageData['meta_title'] = 'Order Slip';
		$pageData['breadcrumbs'] = [];

		return view('pnkpanel.order.packing_slip', compact('allOrders'))->with($pageData);
	}
	 public function Get_Mail_Template($mail_name) 
    {
        $aEmailTemplate = EmailTemplates::select('subject', 'mail_body')->where('template_var_name', '=', $mail_name)->where('status', '=', '1')->first();

        if( $aEmailTemplate && $aEmailTemplate->count() > 0 ) 
        {
            return $aEmailTemplate;
        }
        else 
        {
            return false;
        }
    }
	private function sendordershipkmail($order_id, Request $request, $ship_method, $tracking_no)
	{

		$order = Order::find($order_id);
		if (is_null($order)) {
			return false;
		}

		## Billing Address Start Here
		$billing_address  = $order->bill_first_name . " " . $order->bill_last_name . "<br>";
		$billing_address .= $order->bill_address1 . ",<br>";
		if (isset($order->bill_address2) && $order->bill_address2 != '') {
			$billing_address .= $order->bill_address2 . "<br>";
		}
		$billing_address .= $order->bill_city . " - " . $order->bill_zip . "<br>";
		$billing_address .= $order->bill_state . " , " . $order->bill_country . "<br>";
		$billing_address .= "Phone : " . $order->bill_phone . "<br>";
		## Single Shipping Address Start Here	

		$shipping_address = '';
		$shipping_address .= $order->ship_first_name . " " . $order->ship_last_name . "<br>";
		$shipping_address .= $order->ship_address1 . ",<br>";
		if (isset($order->ship_address2) && $order->ship_address2 != '') {
			$shipping_address .= $order->ship_address2 . "<br>";
		}

		$shipping_address .= $order->ship_city . " - " . $order->ship_zip . "<br>";
		$shipping_address .= $order->ship_state . " , " . $order->ship_country . "<br>";
		$shipping_address .= "Phone : " . $order->ship_phone . "<br>";
		
		$ship_method = $ship_method;

		if ($ship_method == 'FedEx') {
			$tracking_no = "<a href=\"http://www.fedex.com/Tracking?tracknumbers=" . $tracking_no . "\" target=\"_blank\">" . $tracking_no . "</a>";
		} elseif ($ship_method == 'USPS') {
			$tracking_no = "<a href=\"http://www.usps.com/shipping/trackandconfirm.htm?from=home&page=0035trackandconfirm\" target=\"_blank\">" . $tracking_no . "</a>";
		} elseif ($ship_method == 'UPS') {
			$tracking_no = "<a href=\"https://www.ups.com/mobile/track?trackingNumber=" . $tracking_no . "\" target=\"_blank\">" . $tracking_no . "</a>";
		}

		//$tracking_no = '<a href="https://www.fedex.com/wtrk/track/?trknbr='.$tracking_no.'">'.$tracking_no.'</a>';
		
		## FOR THE ORDERED ITEMS START HERE

		$orderItems = OrderDetail::where('order_id', $order_id)->get();
		$ordered_items = '<table style="width: 100%; font-family: Arial; font-size: 12px;padding:0px 20px 0px 20px" border="0" cellspacing="1" cellpadding="5" bgcolor="#f5f4f4">
                  <tbody>';
		$ordered_items .= ' <tr>
            <td>
                <table style="width:100%; font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;" cellpadding="0" cellspacing="0" align="center">
                    <tbody>
                        <tr style="margin:0px; padding:0px;" align="center">
                            <td style="margin:0px; padding:10px 5px 10px 0px; border-bottom:1px solid #e2e6ea;" align="left"><strong>Item Description</strong></td>
                            <td style="margin:0px; padding:10px 5px; border-bottom:1px solid #e2e6ea;"><strong>Unit Price</strong></td>
                            <td style="margin:0px; padding:10px 5px; border-bottom:1px solid #e2e6ea;"><strong>Quantity</strong></td>
                            <td style="margin:0px; padding:10px 0px 10px 5px; border-bottom:1px solid #e2e6ea;" align="right"><strong>Total Price</strong></td>
                        </tr>';

		foreach ($orderItems as $orderItem) {
			$products_id = $orderItem->products_id;
			$product_sku = trim($orderItem->product_sku);
			$product_name = trim($orderItem->product_name);
			//$thumb_image = getMixItemThumb($product_sku);
			## here items row start ##########
			$ordered_items.='<tr style="margin:0px; padding:0px;" align="center">
                            <td style="margin:0px; padding:10px 5px 10px 0px;" align="left">
                                <table cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td style="width:55px;">
                                                <img src="'.config('const.SITE_URL').'/images/productimages/thumb/'.$product_sku.'.jpg" alt="'.$product_name.'" style="width:50px; height:50px;">
                                            </td>
                                            <td style="font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">
                                                <strong><a href="'.config('const.SITE_URL').'/'.$orderItem->product_url.'" style="text-decoration:none;"><font color="#000000">'.$product_name.'</font></a></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="margin:0px; padding:10px 5px; font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">$'.$orderItem->unit_price.'</td>
                            <td style="margin:0px; padding:10px 5px;">'.$orderItem->quantity.'</td>
                            <td style="margin:0px; padding:10px 0px 10px 5px;" align="right">$'.$orderItem->total_price.'</td>
                        </tr>';
			#### here items row end ###########
		}

		$ordered_items .= '
		<tr style="margin:0px; padding:0px;" align="right">
            <td colspan="4" style="margin:0px; padding:0px;">
                <table style="margin:0px; padding:0px;font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">
                    <tbody>
                        <tr style="margin:0px; padding:0px;" align="right">
                            <td style="margin:0px; padding:5px 2px 1px 5px;">Subtotal:</td>
                            <td style="margin:0px; padding:5px 0px 1px 2px;">$'.$order->sub_total.'</td>
                        </tr>';
                        if($order->tax > 0)
                        {
                        $ordered_items .='
                        <tr style="margin:0px; padding:0px;" align="right">
                            <td style="margin:0px; padding:1px 2px 1px 5px;">Sales Tax:</td>
                            <td style="margin:0px; padding:1px 0px 1px 2px;">$'.$order->tax .'</td>
                        </tr>';
                        }
                        if($order->auto_discount > 0)
                        {
                        $ordered_items .='<tr style="margin:0px; padding:0px;" align="right">
                            <td style="margin:0px; padding:1px 2px 1px 5px;">Auto Discount:</td>
                            <td style="margin:0px; padding:1px 5px 1px 2px;">-$'.$order->auto_discount.'</td>
                        </tr>';
                        }
                        if($order->quantity_discount > 0)
                        {
                        $ordered_items .='<tr style="margin:0px; padding:0px;" align="right">
                            <td style="margin:0px; padding:1px 2px 1px 5px;">Quantity Discount:</td>
                            <td style="margin:0px; padding:1px 5px 1px 2px;">-$'.$order->quantity_discount.'</td>
                        </tr>';
                        }
                        if($order->coupon_amount > 0)
                        {
                        $ordered_items .='<tr style="margin:0px; padding:0px;" align="right">
                            <td style="margin:0px; padding:1px 2px 1px 5px;">Coupon Discount:</td>
                            <td style="margin:0px; padding:1px 0px 1px 2px;">-$'.$order->coupon_amount.'</td>
                        </tr>';
                        }
                        $ordered_items .='<tr style="margin:0px; padding:0px;" align="right">
                            <td style="margin:0px; padding:6px 2px 0px 5px;"><strong>Total Amount:</strong></td>
                            <td style="margin:0px; padding:6px 0px 0px 2px;"><strong>$'.$order->order_total.'</strong></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>';
        $ordered_items .= ' </tbody> </table>';

		
		$mres =  Self::Get_Mail_Template("ORDER_SHIPPED");
		$Subject = stripslashes($mres->subject);
		$Subject = str_replace('{$order_no}', $order->order_id, $Subject);
        $EmailBody = stripslashes($mres->mail_body);
        $EmailBody = str_replace('{$order_no}', $order->order_id, $EmailBody);
		$EmailBody = str_replace('{$tracking_no}', $tracking_no, $EmailBody);
		$EmailBody = str_replace('{$ship_method}', $ship_method, $EmailBody);
		$EmailBody = str_replace('{$billing_address}', $billing_address, $EmailBody);
		$EmailBody = str_replace('{$shipping_address}', $shipping_address, $EmailBody);
		$EmailBody = str_replace('{$ordered_items}', $ordered_items, $EmailBody);
		$EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
		$EmailBody = str_replace('{$Site_URL}', config('const.SITE_URL'), $EmailBody);
        $EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
		
      //  print_r($EmailBody);
        $From = config('Settings.CONTACT_MAIL');
        $onesendstat = $this->sendSMTPMail($order->bill_email, $Subject, $EmailBody, $From );            	 
        $onesendstat = $this->sendSMTPMail('sachin.qualdev@gmail.com', $Subject, $EmailBody, $From );            	 
		
		if($onesendstat)
		{
			return true;
		}
		else
		{
			return false;
		} 
		
		//session()->flash('site_common_msg', 'Order Status Mail Sent Successfully!');
		
	}
     //-----------order export csv file---------------
	public function sampleOrderExport(Request $request){

		   $export_file_name = "order_export_" . date('M_d_Y') . ".Csv";
		   //$header_row = ["Order Id", "Purchase Date", "Tracking Number", "Shipping Cost", "Username","Email Address","Company Name","Bill Address","Bill Address 2","Bill City","Bill Region","Bill Country","Bill Postal Code","Bill Phone","Ship Name","Ship Address","Ship Address 2","Ship City","Ship Region","Ship Country","Ship Postal Code","Ship Phone","Total Items","Order Amount","Item Sku","Item Type","Item Quantity","Item Price","Ship Method","Ground Shipping Cost","Freight Shipping Cost","Payment Info"];

		//    $header_row = ["Order Id", "Purchase Date", "Tracking Number", "Shipping Cost", "Username","Email Address","Bill Address","Bill Address 2","Bill City","Bill Region","Bill Country","Bill Postal Code","Bill Phone","Ship Name","Ship Address","Ship Address 2","Ship City","Ship Region","Ship Country","Ship Postal Code","Ship Phone","Total Items","Order Amount","Item Sku","Item Type","Item Quantity","Item Price","Ship Method","Ground Shipping Cost","Freight Shipping Cost","Payment Info"];
		$header_row = ["Order Id", "Purchase Date", "Tracking Number", "Username","Email Address","Bill Address","Bill Address 2","Bill City","Bill Region","Bill Country","Bill Postal Code","Bill Phone","Ship Name","Ship Address","Ship Address 2","Ship City","Ship Region","Ship Country","Ship Postal Code","Ship Phone","Order Amount","Item Sku","Item Type","Item Quantity","Item Price","Ship Method","Shipping Cost","Payment Info"];

         $ids = explode(",",$request->ids);
         	if(isset($request->ids)){
			$orders_data = Order::Join('hba_customer', 'hba_customer.customer_id', '=', 'hba_orders.customer_id')->leftJoin('hba_order_detail', 'hba_order_detail.order_id', '=', 'hba_orders.order_id')->leftJoin('hba_products', 'hba_products.product_id', '=', 'hba_order_detail.products_id')->leftJoin('hba_category', 'hba_category.category_id', '=', 'hba_products.parent_category_id')->select('hba_orders.*','hba_customer.email', 'hba_order_detail.product_sku','hba_order_detail.unit_price', 'hba_order_detail.quantity','hba_order_detail.products_id','hba_order_detail.item_price','hba_products.category','hba_category.category_name')->whereIn('hba_orders.order_id',$ids)->where('bill_email', 'not like', '%qualdev%')->orderBy('hba_orders.order_id','desc')->get();
		  }else{
		  	
		  	$orders_data = Order::Join('hba_customer', 'hba_customer.customer_id', '=', 'hba_orders.customer_id')->leftJoin('hba_order_detail', 'hba_order_detail.order_id', '=', 'hba_orders.order_id')->leftJoin('hba_products', 'hba_products.product_id', '=', 'hba_order_detail.products_id')->leftJoin('hba_category', 'hba_category.category_id', '=', 'hba_products.parent_category_id')->select('hba_orders.*','hba_customer.email', 'hba_order_detail.product_sku','hba_order_detail.unit_price', 'hba_order_detail.quantity','hba_order_detail.products_id','hba_order_detail.item_price','hba_products.category','hba_category.category_name')->where('bill_email', 'not like', '%qualdev%')->orderBy('hba_orders.order_id','desc')->get();

              }
		
	    $csv_data = [];
		 if(count($orders_data) > 0) {
			foreach($orders_data as $order_key => $order_value) {
				$csv_data[$order_key]['order_id'] = $order_value->order_id;
				$csv_data[$order_key]['order_datetime'] = Carbon::parse($order_value->order_datetime)->format('m/d/Y,H:i:s');

				$csv_data[$order_key]['tracking_no'] = $order_value->tracking_no;
				//$csv_data[$order_key]['shipping_amt'] = '$' . number_format($order_value->shipping_amt, 2, '.', '');;
				$csv_data[$order_key]['username'] = $order_value->email;
				$csv_data[$order_key]['email'] = $order_value->email;
				//$csv_data[$order_key]['company_name'] = $order_value->company_name;
				$csv_data[$order_key]['bill_address1'] = $order_value->bill_address1;
				$csv_data[$order_key]['bill_address2'] = $order_value->bill_address2;
				$csv_data[$order_key]['bill_city'] = $order_value->bill_city;
				$csv_data[$order_key]['bill_state'] = $order_value->bill_state;
				$csv_data[$order_key]['bill_country'] = $order_value->bill_country;
				$csv_data[$order_key]['bill_zip'] = $order_value->bill_zip;
				$csv_data[$order_key]['bill_phone'] = $order_value->bill_phone;

				$csv_data[$order_key]['ship_name'] = $order_value->ship_first_name . " " . $order_value->ship_last_name;
				$csv_data[$order_key]['ship_address1'] = $order_value->ship_address1;
				$csv_data[$order_key]['ship_address2'] = $order_value->ship_address2;
				$csv_data[$order_key]['ship_city'] = $order_value->ship_city;
				$csv_data[$order_key]['ship_state'] = $order_value->ship_state;
				$csv_data[$order_key]['ship_country'] = $order_value->ship_country;
				$csv_data[$order_key]['ship_zip'] = $order_value->ship_zip;
				$csv_data[$order_key]['ship_phone'] = $order_value->ship_phone;
				//$csv_data[$order_key]['total_item'] = '';
				$csv_data[$order_key]['order_amount'] = '$' . number_format($order_value->order_total, 2, '.', '');
			    $csv_data[$order_key]['item_sku'] = $order_value->product_sku;
                $csv_data[$order_key]['item_type'] = $order_value->category_name;
                $csv_data[$order_key]['quantity'] = $order_value->quantity;
                /*if(!empty($order_value->item_price)){
                	$csv_data[$order_key]['item_price'] = '$'.$order_value->item_price;
                }else{
                	$csv_data[$order_key]['item_price'] = '';
                }*/
				if(!empty($order_value->unit_price)){
                	$csv_data[$order_key]['item_price'] = '$'.$order_value->unit_price;
                }else{
                	$csv_data[$order_key]['item_price'] = '';
                }
                //$csv_data[$order_key]['ship_method'] = $order_value->ship_method;
				$csv_data[$order_key]['ship_method'] = $order_value->shipping_information;
                // $csv_data[$order_key]['ground_ship_cost'] = '$' . number_format(0, 2, '.', '');
                // $csv_data[$order_key]['freight_cost'] = '$' . number_format(0, 2, '.', '');
				$csv_data[$order_key]['ship_cost'] = '$' . number_format($order_value->shipping_amt, 2, '.', '');
                $csv_data[$order_key]['payment_info'] = $order_value->payment_method;

                
               }
			   $myFile =  Excel::raw(new SampleOrderExports($csv_data, $header_row), 'Csv');
               $response =  array(
			        'name' => $export_file_name,
			        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($myFile)
                    );
             return response()->json($response);
			//return Excel::download(new SampleOrderExports($csv_data, $header_row), $export_file_name);
		} else {
			session()->flash('site_common_msg_err', 'Something went wrong. Please try again later.'); 
		}

     }
}
