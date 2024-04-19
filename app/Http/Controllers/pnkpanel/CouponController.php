<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\ShippingMode;
use App\Models\Category;
use Carbon\Carbon;
use DataTables;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

//use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Coupon::class;
    }
    
    public function list() {
		if(request()->ajax()) {
			$model = Coupon::select([
				'coupon_id',
				'coupon_title',
				'coupon_number',
				'orders',
				'order_amount',
				'discount',
				'type',
				'start_date',
				'end_date',
				'status'
			]);
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->coupon_id.'" />';
			});
			$table->editColumn('coupon_title', function($row) {
				return $row->coupon_title;
			});
			$table->editColumn('coupon_number', function($row) {
				return "<a href=".route('pnkpanel.coupon.coupon_order_list', $row->coupon_id).">".$row->coupon_number."</a>";
			});
			$table->editColumn('orders', function($row) {
				return $this->couponFor($row->orders);
			});
			$table->editColumn('order_amount', function($row) {
				return '$'.number_format($row->order_amount,2,'.','');
			});
			$table->editColumn('discount', function($row) {
				if($row->type=='1')	{
					$type_per	=	'%';
					$type_us	=	"";
				} else {
					$type_per	=	"";
					$type_us	=	"$";
				}

				return $type_us.number_format($row->discount,2,'.','').$type_per;
			});
			// $start_date = '';
			$table->editColumn('start_date', function($row) {
				$start_date = Carbon::parse($row->start_date)->format('m/d/Y');
				$end_date = Carbon::parse($row->end_date)->format('m/d/Y');
				return $start_date.' to '.$end_date;
			});
			$table->addColumn('total_sales', function($row) {
				$order_total = Order::where('coupon_code', $row->coupon_number)->sum('order_total');
				return '$'.number_format($order_total,2,'.','');
			});
			$table->addColumn('total_discount', function($row) {
				$coupon_amount = Order::where('coupon_code', $row->coupon_number)->sum('coupon_amount');
				return '$'.number_format($coupon_amount,2,'.','');
			});
			$table->editColumn('status', function($row) {
				return ($row->status ? 'Active':'Inactive');
			});
			// $table->addColumn('action', 'pnkpanel.component.datatable_action');
			$table->addColumn('action', function($row) {
				return '<a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->coupon_id.'" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a><a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->coupon_id.'" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>';
			});

			$table->rawColumns(['checkbox', 'email', 'action','coupon_number','coupon_title']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "Discount Coupons List";
		$pageData['meta_title'] = "Discount Coupons List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Discount Coupons List',
				 'url' =>route('pnkpanel.coupon.list')
			 ]
		];
		
		return view('pnkpanel.promotion.coupon.list')->with($pageData);
	}
	
	public function coupon_order_list(Request $request,$coupon_id=0) {
		$filterCoupon_id = $request->coupon_id;

		$coupan_name = Coupon::select('coupon_number')->where('coupon_id', $coupon_id)->get();

        if(request()->ajax()) {
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

			if(isset($filterCoupon_id) && !empty($filterCoupon_id)) {
				$model->where('coupon_id', $filterCoupon_id);
			}
			
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->editColumn('order_id', function($row) {
				return "<a href=".route('pnkpanel.order.details', $row->order_id).">".$row->order_id."</a>";
			});
			$table->editColumn('order_datetime', function($row) {
				return Carbon::parse($row->order_datetime)->format('m/d/Y H:i:s');
			});
			$table->addColumn('customer', function($row) {
				return $row->bill_first_name.' '.$row->bill_last_name;
			});
			$table->editColumn('sub_total', function($row) {
				return '$' . number_format($row->sub_total, 2, '.', '');
			});
			$table->editColumn('tax', function($row) {
				return '$' . number_format($row->tax, 2, '.', '');
			});
			$table->editColumn('shipping_amt', function($row) {
				return '$' . number_format($row->shipping_amt, 2, '.', '');
			});
			$table->editColumn('order_total', function($row) {
				return '$' . number_format($row->order_total, 2, '.', '');
			});
			$table->rawColumns(['order_id']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "Discount Coupons Order List";
		$pageData['meta_title'] = "Discount Coupons Order List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Discount Coupons Order List',
				 'url' =>route('pnkpanel.coupon.coupon_order_list')
			 ]
		];
		
		return view('pnkpanel.promotion.coupon.coupon_order_list',compact('coupon_id','coupan_name'))->with($pageData);
    }
	
	public function edit($id = 0) {
        if($id > 0) {
			$coupon = Coupon::findOrFail($id);
		} else {
			$coupon =  new Coupon;
		}
		
		$pageData['shippingMethods'] = ShippingMode::select('shipping_mode_id', 'shipping_title')->where('status', '=', '1')->orderBy('shipping_mode_id');
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Coupon';
		$pageData['meta_title'] = $prefix.' Coupon';
		$pageData['categories'] = $this->getCategoryList();
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Discount Coupons List',
				 'url' =>route('pnkpanel.coupon.list')
			 ],
			 [
				 'title' => $prefix.' Discount Coupon',
				 'url' =>route('pnkpanel.coupon.edit', $id)
			 ]
		];
		
        return view('pnkpanel.promotion.coupon.edit', compact('coupon'))->with($pageData);;
    }

	public function getCategoryList(){
		//$select = "category_id, category_name";
		//$categories = Category::select('sf_category as c')->whereIn('status','1')->select(DB::raw($select))->get();
		$categories = Category::select('category_id','category_name')->where('status','1')->get();
		$categories = json_decode(json_encode($categories), true);		
		return $categories;
	}
	
    public function update(Request $request) {
		$actType = $request->actType;
		$id = $request->id;
		$coupon = Coupon::findOrNew($id);
		
		$this->validate($request, [
			'coupon_number'	 	=> 'required',
			'order_amount'		=> 'required_if:orders,0|numeric',
            'category_id'		=> 'array|required_if:orders,3',
            'brand_id' 			=> 'array|required_if:orders,5',
            'sku' 				=> 'required_if:orders,1',
            'shipping_mode_id' 	=> 'required_if:orders,4',
            'discount' 			=> 'required_unless:orders,4|numeric',
            'start_date' 		=> 'required|date',
            'end_date' 			=> 'required|date|after_or_equal:start_date',
			'status'			=> 'required|numeric'
		],
		[
        	'coupon_number.required' => 'Please Enter Coupon Code',
        	'order_amount.required_if' => 'Please Enter Order Amount',
        	'order_amount.numeric' => 'Please enter only positive numeric value',
        	'category_id.required_if' => 'Please Select Category',
        	'brand_id.required_if' => 'Please Select Brand',
        	'sku.required_if' => 'Please Enter Product SKU',
        	'shipping_mode_id.required_if' => 'Please select shipping method',
        	'start_date.required' => 'Please select the Coupon Start Date',
        	'end_date.required' => 'Please select the Coupon End Date',
        	'end_date.after_or_equal' => 'Start Date should be smaller than the End Date',
        	'status.required' => 'Please select Status',
        	'discount.required_unless' => 'Please Enter Coupon Discount',
        	'discount.numeric' => 'Please Enter Coupon Discount',
		]);

		// dd($request);
		$orders 				  = $request->orders;
		$sku 					  = $request->sku;
		$category_id 			  = $request->category_id;
		$brand_id	 			  = $request->brand_id;
		$shipping_mode_id 		  = $request->shipping_mode_id;
		
		if($orders=="1") {
			$val=$sku;
		} elseif($orders=="3") {
			$val=implode(",",$category_id);
		} elseif($orders=="4") {
			$val=$shipping_mode_id;
		} elseif($orders=="5") {
			$val=implode(",",$brand_id);
		} else {
		    $val="";
		}
		
		$start_date = Carbon::parse($request['start_date']);
		// $start_date = $start_date->format('Y-M-d');
		$start_date = $start_date->format('m/d/Y');
		$end_date = Carbon::parse($request['end_date']);
		// $end_date = $end_date->format('Y-m-d');
		$end_date = $end_date->format('m/d/Y');

		$coupon->coupon_title = isset($request->coupon_title) ? $request->coupon_title : '';
		$coupon->coupon_number = isset($request->coupon_number) ? $request->coupon_number : '';
		$coupon->start_date = $start_date;
		$coupon->end_date = $end_date;
		$coupon->type = isset($request->type) ? $request->type : '';
		if($request->orders == '0') {
			$coupon->order_amount = isset($request->order_amount) ? $request->order_amount : '';
		}
		$coupon->sku = $val;
		$coupon->orders = isset($request->orders) ? $request->orders : '';
		
		if($request->orders != '4') {
			$coupon->discount = isset($request->discount) ? $request->discount : 0.00;
		} else {
			$coupon->discount = 0.00;
		}
		// $coupon->discount = isset($request->discount) ? $request->discount : '';
		$coupon->detail = isset($request->detail) ? $request->detail : '';
		$coupon->is_once = isset($request->is_once) ? $request->is_once : '';
		$coupon->status 	=  $request->status;
		$coupon->is_used 	=  'No';
		$coupon->remark 	=  '';
		
		// dd($coupon);

		if($coupon->save()) {
			if($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update')); 
			}
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.coupon.edit', $coupon->coupon_id);
	}
	
	public function delete($id){
		if(Coupon::findOrFail($id)->delete()) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.coupon.list');
	}

	public function couponFor($value) {
		if($value == "0") { 
			return "Order Amount";
		} elseif($value == "1") { 
			return "Product";
		} elseif($value == "3") { 
			return "Category";
		} elseif($value == "4") { 
			return "Free Shipping";
		} elseif($value == "5") { 
			return "Manufacturer";
		} else {
			return '';
		}
	}
	
}
