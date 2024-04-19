<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Representative;
use DataTables;
use Carbon\Carbon;
use DB;

class OrderSummaryController extends Controller
{
	public function list(Request $request) {
		$filterCustomer = $request->filterCustomer;
		$filterStartDate = $request->filterStartDate;
		$filterEndDate = $request->filterEndDate;
		
		if(!isset($filterStartDate) ) {
			$filterStartDate 	= date('m/d/Y', mktime(0, 0, 0, date('m'), date('d')-15, date('Y')));
		}
		if(!isset($filterEndDate) ) {
			$filterEndDate 	= date('m/d/Y', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
		}
		
		if(request()->ajax()) {
			$model = Order::select([
				DB::raw("DATE_FORMAT( order_datetime, '%Y-%m-%d' ) as orderDate"),
				DB::raw("COUNT(order_id) as orderCount"),
				DB::raw("SUM(order_total) as totalAmount"),
				DB::raw("SUM(status='Pending') as pendingCount"),
				DB::raw("SUM(status='Completed') as closedCount"),
				DB::raw("SUM(status='Canceled') as cancelCount"),
				DB::raw("SUM(status='Declined') as declinedCount")
			])->groupBy('orderDate');
			
			if(isset($filterCustomer) && !empty($filterCustomer)) {
				$model->where('customer_id', $filterCustomer);
			}
			
			if($filterStartDate != '') {
				$model->where(DB::raw("(DATE_FORMAT(order_datetime, '%Y-%m-%d'))"), '>=', date("Y-m-d",strtotime($filterStartDate)));
			}
			if($filterEndDate != '') {
				$model->where(DB::raw("(DATE_FORMAT(order_datetime, '%Y-%m-%d'))"), '<=', date("Y-m-d",strtotime($filterEndDate)));
			}

			$table = DataTables::eloquent($model);
			$table->addIndexColumn();

			$table->editColumn('orderDate', function($row) use ($filterCustomer) {
				$date = Carbon::parse($row->orderDate)->format('m/d/Y');
				return "<a href='".route('pnkpanel.order.list')."?filterStartDate=".$date."&filterEndDate=".$date."&filterCustomer=".$filterCustomer."'>".$date."</a>";
			});
			$table->editColumn('pendingCount', function($row) use ($filterCustomer) {
				$date = Carbon::parse($row->orderDate)->format('m/d/Y');
				$result = Order::select([
					DB::raw("SUM(order_total) as PPAmount")
				])->where([['status', '=', 'Pending'],[DB::raw("DATE_FORMAT(order_datetime, '%Y-%m-%d')"), '=', $row->orderDate]])->groupBy(DB::raw("DATE_FORMAT( order_datetime, '%Y-%m-%d' )"))->first();
				$PPAmount = $result->PPAmount ?? 0;
				return ($row->pendingCount ? "<a href='".route('pnkpanel.order.list')."?filterStartDate=".$date."&filterEndDate=".$date."&filterStatus=Pending&filterCustomer=".$filterCustomer."'> <font class='admin-text2'>" : "") . $row->pendingCount . "<font class='admin-text2'>~</font>$".number_format($PPAmount,2,'.','') . ($row->pendingCount ? "</font></a>" : "");
			});
			$table->editColumn('closedCount', function($row) use ($filterCustomer) {
				$date = Carbon::parse($row->orderDate)->format('m/d/Y');
				$result = Order::select([
					DB::raw("SUM(order_total) as PCAmount")
				])->where([['status', '=', 'Completed'],[DB::raw("DATE_FORMAT(order_datetime, '%Y-%m-%d')"), '=', $row->orderDate]])->groupBy(DB::raw("DATE_FORMAT( order_datetime, '%Y-%m-%d' )"))->first();
				$PCAmount = $result->PCAmount ?? 0;
				return ($row->pendingCount ? "<a href='".route('pnkpanel.order.list')."?filterStartDate=".$date."&filterEndDate=".$date."&filterStatus=Pending&filterCustomer=".$filterCustomer."'> <font class='admin-text2'>" : "") . $row->pendingCount . "<font class='admin-text2'>~</font>$".number_format($PCAmount,2,'.','') . ($row->pendingCount ? "</font></a>" : "");
			});
			$table->editColumn('cancelCount', function($row) use ($filterCustomer) {
				$date = Carbon::parse($row->orderDate)->format('m/d/Y');
				$result = Order::select([
					DB::raw("SUM(order_total) as PClAmount")
				])->where([['status', '=', 'Canceled'],[DB::raw("DATE_FORMAT(order_datetime, '%Y-%m-%d')"), '=', $row->orderDate]])->groupBy(DB::raw("DATE_FORMAT( order_datetime, '%Y-%m-%d' )"))->first();
				$PClAmount = $result->PClAmount ?? 0;
				return ($row->pendingCount ? "<a href='".route('pnkpanel.order.list')."?filterStartDate=".$date."&filterEndDate=".$date."&filterStatus=Pending&filterCustomer=".$filterCustomer."'> <font class='admin-text2'>" : "") . $row->pendingCount . "<font class='admin-text2'>~</font>$".number_format($PClAmount,2,'.','') . ($row->pendingCount ? "</font></a>" : "");
			});
			$table->editColumn('declinedCount', function($row) use ($filterCustomer) {
				$date = Carbon::parse($row->orderDate)->format('m/d/Y');
				$result = Order::select([
					DB::raw("SUM(order_total) as PDCAmount")
				])->where([['status', '=', 'Declined'],[DB::raw("DATE_FORMAT(order_datetime, '%Y-%m-%d')"), '=', $row->orderDate]])->groupBy(DB::raw("DATE_FORMAT( order_datetime, '%Y-%m-%d' )"))->first();
				$PDCAmount = $result->PDCAmount ?? 0;
				return ($row->pendingCount ? "<a href='".route('pnkpanel.order.list')."?filterStartDate=".$date."&filterEndDate=".$date."&filterStatus=Pending&filterCustomer=".$filterCustomer."'> <font class='admin-text2'>" : "") . $row->pendingCount . "<font class='admin-text2'>~</font>$".number_format($PDCAmount,2,'.','') . ($row->pendingCount ? "</font></a>" : "");
			});
			$table->addColumn('action', function($row) {
				$date = Carbon::parse($row->orderDate)->format('m/d/Y');
				return "<a href='".route('pnkpanel.order.order_slip')."?filterStartDate=".urlencode($date)."' onClick=\"window.open('','xwin','toolbar=0,scrollbars=1,location=0,status=0,menubars=0,resizable=0, width=800,height=600,top=0,left=0,maximize=0')\" target=\"xwin\">Print Order(s)</a>";
			});
			
			$table->rawColumns(['orderDate', 'pendingCount', 'closedCount', 'cancelCount', 'declinedCount', 'action']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "Order Summary";
		$pageData['meta_title'] = "Order Summary";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Order Summary',
				 'url' =>route('pnkpanel.order-summary')
			 ]
		];
		
		$customer_name = '';
		$customer = Customer::select('customer_id', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))->where('customer_id', $filterCustomer)->first();
		if($customer) {
			$customer_name = $customer->full_name;
		}
		
		return view('pnkpanel.order_summary.list', compact('customer_name', 'filterStartDate', 'filterEndDate'))->with($pageData);
	}
}
