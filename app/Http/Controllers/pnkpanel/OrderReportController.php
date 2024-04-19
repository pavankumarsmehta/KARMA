<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use DataTables;
use Carbon\Carbon;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class OrderReportController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Order::class;
    }
    
	public function list(Request $request) {

        if ($request->ajax()) {
			$resultArray = [];
			// dd($request->start_date);
			$start_date = $request->has('start_date') && $request->start_date != null ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->subDays(7)->format('Y-m-d');
			$end_date = $request->has('start_date') && $request->end_date != null ? Carbon::parse($request->end_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

			// dd($start_date, $end_date);

			// $start_date = '2021-10-02';
			// $end_date = '2021-10-08';
			$db_collect_in_process = Order::select('order_id', 'sub_total', 'tax', 'shipping_amt', 'order_total')
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->where('status', '=', 'In Process')
							->get();
			$total_in_process_sub_total = 0;
			$total_in_process_tax = 0;
			$total_in_process_shipping = 0;
			$total_in_process_amount = 0;
			$total_in_process_order = count($db_collect_in_process);
			if($total_in_process_order > 0) {
				foreach($db_collect_in_process as $key => $value) {
					$total_in_process_sub_total = $total_in_process_sub_total + $value['sub_total'];
					$total_in_process_tax = $total_in_process_tax + $value['tax'];
					$total_in_process_shipping = $total_in_process_shipping + $value['shipping_amt'];
					$total_in_process_amount = $total_in_process_amount + $value['order_total'];
				}
			}

			$resultArray[8]['status'] = '<a href="'.url('pnkpanel/order/list?filterStatus=In Process&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y')).'" target="_blank">In Process</a>';
			$resultArray[8]['order'] = $total_in_process_order;
			$resultArray[8]['sub_total'] = '$'.number_format($total_in_process_sub_total, 2, '.', '');
			$resultArray[8]['tax'] = '$'.number_format($total_in_process_tax, 2, '.', '');
			$resultArray[8]['shipping'] = '$'.number_format($total_in_process_shipping, 2, '.', '');
			$resultArray[8]['total_amount'] = '$'.number_format($total_in_process_amount, 2, '.', '');

			$db_collect_completed = Order::select('order_id', 'sub_total', 'tax', 'shipping_amt', 'order_total')
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->where('status', '=', 'Completed')
							->get();
			$total_collect_sub_total = 0;
			$total_collect_tax = 0;
			$total_collect_shipping = 0;
			$total_collect_amount = 0;
			$total_collect_order = count($db_collect_completed);
			if($total_collect_order > 0) {
				foreach($db_collect_completed as $key => $value) {
					$total_collect_sub_total = $total_collect_sub_total + $value['sub_total'];
					$total_collect_tax = $total_collect_tax + $value['tax'];
					$total_collect_shipping = $total_collect_shipping + $value['shipping_amt'];
					$total_collect_amount = $total_collect_amount + $value['order_total'];
				}
			}

			$resultArray[0]['status'] = '<a href="'.url('pnkpanel/order/list?filterStatus=Completed&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y')).'" target="_blank">Completed</a>';
			$resultArray[0]['order'] = $total_collect_order;
			$resultArray[0]['sub_total'] = '$'.number_format($total_collect_sub_total, 2, '.', '');
			$resultArray[0]['tax'] = '$'.number_format($total_collect_tax, 2, '.', '');
			$resultArray[0]['shipping'] = '$'.number_format($total_collect_shipping, 2, '.', '');
			$resultArray[0]['total_amount'] = '$'.number_format($total_collect_amount, 2, '.', '');

			$db_collect_pending = Order::select('order_id', 'sub_total', 'tax', 'shipping_amt', 'order_total')
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->where('status', '=', 'Pending')
							->get();
			$total_pending_sub_total = 0;
			$total_pending_tax = 0;
			$total_pending_shipping = 0;
			$total_pending_amount = 0;
			$total_pending_order = count($db_collect_pending);
			if($total_pending_order > 0) {
				foreach($db_collect_pending as $key => $value) {
					$total_pending_sub_total = $total_pending_sub_total + $value['sub_total'];
					$total_pending_tax = $total_pending_tax + $value['tax'];
					$total_pending_shipping = $total_pending_shipping + $value['shipping_amt'];
					$total_pending_amount = $total_pending_amount + $value['order_total'];
				}
			}

			$resultArray[1]['status'] = '<a href="'.url('pnkpanel/order/list?filterStatus=Pending&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y')).'" target="_blank">Pending</a>';
			$resultArray[1]['order'] = $total_pending_order;
			$resultArray[1]['sub_total'] = '$'.number_format($total_pending_sub_total, 2, '.', '');
			$resultArray[1]['tax'] = '$'.number_format($total_pending_tax, 2, '.', '');
			$resultArray[1]['shipping'] = '$'.number_format($total_pending_shipping, 2, '.', '');
			$resultArray[1]['total_amount'] = '$'.number_format($total_pending_amount, 2, '.', '');
			
			$db_collect_cancel = Order::select('order_id', 'sub_total', 'tax', 'shipping_amt', 'order_total')
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->where('status', '=', 'Canceled')
							->get();
			$total_cancel_sub_total = 0;
			$total_cancel_tax = 0;
			$total_cancel_shipping = 0;
			$total_cancel_amount = 0;
			$total_cancel_order = count($db_collect_cancel);
			if($total_cancel_order > 0) {
				foreach($db_collect_cancel as $key => $value) {
					$total_cancel_sub_total = $total_cancel_sub_total + $value['sub_total'];
					$total_cancel_tax = $total_cancel_tax + $value['tax'];
					$total_cancel_shipping = $total_cancel_shipping + $value['shipping_amt'];
					$total_cancel_amount = $total_cancel_amount + $value['order_total'];
				}
			}

			$resultArray[2]['status'] = '<a href="'.url('pnkpanel/order/list?filterStatus=Canceled&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y')).'" target="_blank">Canceled</a>';
			$resultArray[2]['order'] = $total_cancel_order;
			$resultArray[2]['sub_total'] = '$'.number_format($total_cancel_sub_total, 2, '.', '');
			$resultArray[2]['tax'] = '$'.number_format($total_cancel_tax, 2, '.', '');
			$resultArray[2]['shipping'] = '$'.number_format($total_cancel_shipping, 2, '.', '');
			$resultArray[2]['total_amount'] = '$'.number_format($total_cancel_amount, 2, '.', '');

			$db_collect_decline = Order::select('order_id', 'sub_total', 'tax', 'shipping_amt', 'order_total')
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->where('status', '=', 'Declined')
							->get();
			$total_decline_sub_total = 0;
			$total_decline_tax = 0;
			$total_decline_shipping = 0;
			$total_decline_amount = 0;
			$total_decline_order = count($db_collect_decline);
			if($total_decline_order > 0) {
				foreach($db_collect_decline as $key => $value) {
					$total_decline_sub_total = $total_decline_sub_total + $value['sub_total'];
					$total_decline_tax = $total_decline_tax + $value['tax'];
					$total_decline_shipping = $total_decline_shipping + $value['shipping_amt'];
					$total_decline_amount = $total_decline_amount + $value['order_total'];
				}
			}

			$resultArray[3]['status'] = '<a href="'.url('pnkpanel/order/list?filterStatus=Declined&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y')).'" target="_blank">Declined</a>';
			$resultArray[3]['order'] = $total_decline_order;
			$resultArray[3]['sub_total'] = '$'.number_format($total_decline_sub_total, 2, '.', '');
			$resultArray[3]['tax'] = '$'.number_format($total_decline_tax, 2, '.', '');
			$resultArray[3]['shipping'] = '$'.number_format($total_decline_shipping, 2, '.', '');
			$resultArray[3]['total_amount'] = '$'.number_format($total_decline_amount, 2, '.', '');

			$db_collect_refunded = Order::select('order_id', 'sub_total', 'tax', 'shipping_amt', 'order_total')
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->where('status', '=', 'Refunded')
							->get();
			$total_refunded_sub_total = 0;
			$total_refunded_tax = 0;
			$total_refunded_shipping = 0;
			$total_refunded_amount = 0;
			$total_refunded_order = count($db_collect_refunded);
			if($total_refunded_order > 0) {
				foreach($db_collect_refunded as $key => $value) {
					$total_refunded_sub_total = $total_refunded_sub_total + $value['sub_total'];
					$total_refunded_tax = $total_refunded_tax + $value['tax'];
					$total_refunded_shipping = $total_refunded_shipping + $value['shipping_amt'];
					$total_refunded_amount = $total_refunded_amount + $value['order_total'];
				}
			}

			$resultArray[4]['status'] = '<a href="'.url('pnkpanel/order/list?filterStatus=Refunded&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y')).'" target="_blank">Refunded</a>';
			$resultArray[4]['order'] = $total_refunded_order;
			$resultArray[4]['sub_total'] = '$'.number_format($total_refunded_sub_total, 2, '.', '');
			$resultArray[4]['tax'] = '$'.number_format($total_refunded_tax, 2, '.', '');
			$resultArray[4]['shipping'] = '$'.number_format($total_refunded_shipping, 2, '.', '');
			$resultArray[4]['total_amount'] = '$'.number_format($total_refunded_amount, 2, '.', '');


			$db_collect_partial_refund = Order::select('order_id', 'sub_total', 'tax', 'shipping_amt', 'order_total')
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->where('status', '=', 'Partial Refund')
							->get();
			$total_partial_refund_sub_total = 0;
			$total_partial_refund_tax = 0;
			$total_partial_refund_shipping = 0;
			$total_partial_refund_amount = 0;
			$total_partial_refund_order = count($db_collect_partial_refund);
			if($total_partial_refund_order > 0) {
				foreach($db_collect_partial_refund as $key => $value) {
					$total_partial_refund_sub_total = $total_partial_refund_sub_total + $value['sub_total'];
					$total_partial_refund_tax = $total_partial_refund_tax + $value['tax'];
					$total_partial_refund_shipping = $total_partial_refund_shipping + $value['shipping_amt'];
					$total_partial_refund_amount = $total_partial_refund_amount + $value['order_total'];
				}
			}

			$resultArray[5]['status'] = '<a href="'.url('pnkpanel/order/list?filterStatus=Partial Refund&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y')).'" target="_blank">Partial Refund</a>';
			$resultArray[5]['order'] = $total_partial_refund_order;
			$resultArray[5]['sub_total'] = '$'.number_format($total_partial_refund_sub_total, 2, '.', '');
			$resultArray[5]['tax'] = '$'.number_format($total_partial_refund_tax, 2, '.', '');
			$resultArray[5]['shipping'] = '$'.number_format($total_partial_refund_shipping, 2, '.', '');
			$resultArray[5]['total_amount'] = '$'.number_format($total_partial_refund_amount, 2, '.', '');



			$db_collect_admin_review = Order::select('order_id', 'sub_total', 'tax', 'shipping_amt', 'order_total')
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->where('status', '=', 'Admin Review')
							->get();
			$total_admin_review_sub_total = 0;
			$total_admin_review_tax = 0;
			$total_admin_review_shipping = 0;
			$total_admin_review_amount = 0;
			$total_admin_review_order = count($db_collect_admin_review);
			if($total_partial_refund_order > 0) {
				foreach($db_collect_admin_review as $key => $value) {
					$total_admin_review_sub_total = $total_admin_review_sub_total + $value['sub_total'];
					$total_admin_review_tax = $total_admin_review_tax + $value['tax'];
					$total_admin_review_shipping = $total_admin_review_shipping + $value['shipping_amt'];
					$total_admin_review_amount = $total_admin_review_amount + $value['order_total'];
				}
			}

			$resultArray[6]['status'] = '<a href="'.url('pnkpanel/order/list?filterStatus=Admin Review&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y')).'" target="_blank">Admin Review</a>';
			$resultArray[6]['order'] = $total_admin_review_order;
			$resultArray[6]['sub_total'] = '$'.number_format($total_admin_review_sub_total, 2, '.', '');
			$resultArray[6]['tax'] = '$'.number_format($total_admin_review_tax, 2, '.', '');
			$resultArray[6]['shipping'] = '$'.number_format($total_admin_review_shipping, 2, '.', '');
			$resultArray[6]['total_amount'] = '$'.number_format($total_admin_review_amount, 2, '.', '');

			
			$resultArray[7]['status'] = 'Total';
			$resultArray[7]['order'] 	 = 	$total_in_process_order+$total_pending_order+$total_collect_order+$total_cancel_order+$total_decline_order+$total_refunded_order+$total_partial_refund_order+$total_admin_review_order;
			$resultArray[7]['sub_total'] = 	'$'.number_format($total_in_process_sub_total+$total_collect_sub_total+$total_pending_sub_total+$total_cancel_sub_total+$total_decline_sub_total+$total_refunded_sub_total+$total_partial_refund_sub_total+$total_admin_review_sub_total, 2, '.', '');
			$resultArray[7]['tax'] 	 	 = 	'$'.number_format($total_in_process_tax+$total_collect_tax+$total_pending_tax+$total_cancel_tax+$total_decline_tax+$total_refunded_tax+$total_partial_refund_tax+$total_admin_review_tax, 2, '.', '');
			$resultArray[7]['shipping']	 = 	'$'.number_format($total_in_process_shipping+$total_collect_shipping+$total_pending_shipping+$total_cancel_shipping+$total_decline_shipping+$total_refunded_shipping+$total_partial_refund_shipping+$total_admin_review_shipping, 2, '.', '');
			$resultArray[7]['total_amount'] 	 = 	'$'.number_format($total_in_process_amount+$total_collect_amount+$total_pending_amount+$total_cancel_amount+$total_decline_amount+$total_refunded_amount+$total_partial_refund_amount+$total_admin_review_amount, 2, '.', '');

			return Datatables::collection($resultArray)->rawColumns(['status'])->toJson();
        }
		$pageData['page_title'] = 'Order Reports';
		$pageData['meta_title'] = 'Order Reports';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Order Reports',
				 'url' =>route('pnkpanel.order-report.list')
			 ]
		];
		
        return view('pnkpanel.reports.order_report.list')->with($pageData);;
    }
	
}
