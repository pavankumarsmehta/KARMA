<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Category;
use DataTables;
use Carbon\Carbon;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class CustomerOrderReportController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Order::class;
    }
    
	public function list(Request $request) {
		$filterByAmountSpent = $request->has('filterByAmountSpent') ? $request->filterByAmountSpent : '';
		$filterByNoOrders = $request->has('filterByNoOrders') ? $request->filterByNoOrders : '';
		$filterByCountry = $request->has('filterByCountry') ? $request->filterByCountry : 'US';
		$filterByState = $request->has('filterByState') ? $request->filterByState : '';
		$filterByCategory = $request->has('filterByCategory') ? $request->filterByCategory : 0;

		$categories = Category::where('parent_id', '=', '0')->orderBy('category_name', 'asc')->with('childrenRecursive')->get();
		$drawCategoryTreeDropdownOption = drawCategoryTreeDropdownOption($categories, $filterByCategory);

		$start_date = $request->has('start_date') && $request->start_date != null ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->subDays(7)->format('Y-m-d');
		$end_date = $request->has('start_date') && $request->end_date != null ? Carbon::parse($request->end_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        if ($request->ajax()) {
			$resultArray = Customer::selectRaw('CONCAT(hba_customer.first_name, " ", hba_customer.last_name, ", ", hba_customer.country) AS customer_name, COUNT(hba_orders.order_id) AS total_order, CONCAT("$", SUM(hba_orders.order_total)) AS total_amount, hba_customer.customer_id')
							->join('hba_orders', 'hba_orders.customer_id', '=', 'hba_customer.customer_id');
			
			if($request->filterByCountry != null) {
				$resultArray = $resultArray->where('hba_customer.country', '=', $request->filterByCountry);
			}
			if($request->filterByState != null) {
				$resultArray = $resultArray->where('hba_customer.state', '=', $request->filterByState);
			}
			if($request->filterByCategory != 0) {
				$categories = Category::where('category_id', '=', $request->filterByCategory)->with('childrenRecursive')->get();
				$category_ids = getSubCategories($categories);

				$resultArray = $resultArray->join('hba_order_detail', 'hba_order_detail.order_id', '=', 'hba_orders.order_id')
								->join('hba_products', 'hba_products.product_id', '=', 'hba_order_detail.products_id')
								->join('hba_products_category', 'hba_products_category.products_id', '=', 'hba_products.product_id')
								->whereIn('hba_products_category.category_id', $category_ids);
			}
			if($request->filterByAmountSpent != null) {
				$arr_price = explode("-", $request->filterByAmountSpent);
				$price_1 = $arr_price[0];
				$price_2 = $arr_price[1];
				if($price_2=='0') {
					$resultArray = $resultArray->havingRaw("SUM(hba_orders.order_total) >= '".$price_1."'");
				} else {
					$resultArray = $resultArray->havingRaw("SUM(hba_orders.order_total) >= '".$price_1."' AND SUM(hba_orders.order_total) <= '".$price_2."'");
				}
			}
			if($request->filterByNoOrders != null) {
				if($filterByNoOrders==4) {
					$resultArray = $resultArray->havingRaw("COUNT(hba_orders.order_id) >= '".$filterByNoOrders."'");
				} else {
					$resultArray = $resultArray->havingRaw("COUNT(hba_orders.order_id) = '".$filterByNoOrders."'");
				}
			} else {
				$resultArray = $resultArray->havingRaw("COUNT(hba_orders.order_id) >= '1'");
			}
			$resultArray = $resultArray->whereRaw("DATE_FORMAT(order_datetime,'%Y-%m-%d') >='".$start_date."' AND DATE_FORMAT(order_datetime,'%Y-%m-%d') <= '".$end_date."'")
							->groupBy('hba_customer.customer_id')
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

        $countryArray = getCountryBoxArray();
        $stateArray = getStateBoxArray();
		$pageData['page_title'] = 'Customer Orders';
		$pageData['meta_title'] = 'Customer Orders';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Customer Orders',
				 'url' =>route('pnkpanel.customerorder-report.list')
			 ]
		];
		
        return view('pnkpanel.reports.customerorder_report.list', compact('filterByAmountSpent', 'filterByNoOrders', 'filterByCountry', 'filterByState', 'filterByCategory', 'countryArray', 'stateArray', 'drawCategoryTreeDropdownOption'))->with($pageData);;
    }
	
}
