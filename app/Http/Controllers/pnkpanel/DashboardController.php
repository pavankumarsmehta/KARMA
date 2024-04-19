<?php

namespace App\Http\Controllers\pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\SampleRequest;
use App\Models\Customer;
use App\Models\Order;
//use Session;

class DashboardController extends Controller
{
    public function index() {

    	//$pageData['tot_quote_request'] = Quote::whereDate('reg_datetime',date('Y-m-d'))->count();
    	//$pageData['tot_sample_request'] = SampleRequest::whereDate('reg_datetime',date('Y-m-d'))->count();
    	$pageData['tot_customer'] = Customer::whereDate('reg_datetime',date('Y-m-d'))->count();
    	$pageData['tot_order'] = Order::whereDate('order_datetime',date('Y-m-d'))->count();
    	$pageData['tot_order_amt'] = Order::whereDate('order_datetime',date('Y-m-d'))->sum('order_total');
    	
		$pageData['page_title'] = "Dashboard";
		$pageData['meta_title'] = "Dashboard";

		$pageData['breadcrumbs'] = [];
		
        return view('pnkpanel.dashboard.dashboard')->with($pageData);
    }
}
