<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use DataTables;
use Carbon\Carbon;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class SalesTaxReportController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Order::class;
    }
    
	public function list(Request $request) {

        if ($request->ajax()) {
			// dd($request->start_date);
			$start_date = $request->has('start_date') && $request->start_date != null ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->subDays(7)->format('Y-m-d');
			$end_date = $request->has('start_date') && $request->end_date != null ? Carbon::parse($request->end_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

			// dd($start_date, $end_date);

			// $start_date = '2014-11-11';
			// $end_date = '2021-10-08';

			$resultArray = Order::selectRaw('CONCAT(hba_customer.first_name, " ", hba_customer.last_name, ", ", hba_customer.country) AS customer_name, COUNT(hba_orders.order_id) AS cnt_order, CONCAT("$", SUM(hba_orders.tax)) AS cnt, hba_orders.customer_id')
							->join('hba_customer', 'hba_customer.customer_id', '=', 'hba_orders.customer_id')
							// ->whereBetween('order_datetime', [$start_date, $end_date])
							->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->groupBy('hba_orders.customer_id')
							->get()->toArray();
			if(count($resultArray) > 0) {
				foreach($resultArray as $key => $value) {
					$link = url('pnkpanel/order/list?filterCustomer='.$value['customer_id'].'&filterStartDate='.Carbon::parse($start_date)->format('m/d/Y').'&filterEndDate='.Carbon::parse($end_date)->format('m/d/Y'));
					$resultArray[$key]['customer_name'] = '<a href="'.$link.'" target="_blank">'.$value['customer_name'].'</a>';
				}
			} else {
				$resultArray = [];
			}
			return Datatables::collection($resultArray)->rawColumns(['customer_name'])->toJson();
        }
		$pageData['page_title'] = 'Sales Tax';
		$pageData['meta_title'] = 'Sales Tax';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Sales Tax',
				 'url' =>route('pnkpanel.salestax-report.list')
			 ]
		];
		
        return view('pnkpanel.reports.salestax_report.list')->with($pageData);;
    }
	
}
