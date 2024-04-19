<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealWeek;
use App\Models\AutoDiscount;
use DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NewsLetterExports;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class DealWeekController extends Controller
{
    use CrudControllerTrait;
	
	public function __construct()
	{
		$this->prefix = env('DB_PREFIX', '');
		//$this->middleware('auth');
		
	}
    public function model()
    {
        return DealWeek::class;
    }

	public function list(Request $request) {
		if(request()->ajax()) {
			$model = DealWeek::select([
				'dealofweek_id',
				'product_sku',
				'start_date',
				'end_date',
				'display_rank',
				'deal_price',
				'status'				
			]);
			if (!request()->get('order')) {
				$model->orderBy('dealofweek_id', 'asc');
			}
			
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->dealofweek_id.'" />';
			});
			$table->editColumn('product_sku', function($row) {				
				return $row->product_sku;
			});
			$table->editColumn('start_date', function($row) {				
				return Carbon::parse($row->start_date)->format('m/d/Y');
			});
			$table->editColumn('end_date', function($row) {				
				return Carbon::parse($row->end_date)->format('m/d/Y');
			});
			$table->editColumn('display_rank', function($row) {				
				return $row->display_rank;
			});
			$table->editColumn('deal_price', function($row) {
				return $row->deal_price;
			});
			$table->editColumn('status', function($row) {
				return ($row->status ? 'Active':'Inactive');
			});
			
			$table->addColumn('action', function($row) {
				return '<a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->dealofweek_id.'" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a><a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->dealofweek_id.'" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>';
			});
			$table->rawColumns(['checkbox', 'product_sku', 'start_date', 'end_date', 'display_rank','deal_price','status','action']);
			return $table->make(true);
		}
		$pageData['page_title'] = "Deal of the week List";
		$pageData['meta_title'] = "Deal of the week List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Deal of the week List',
				 'url' =>route('pnkpanel.dealweek.list')
			 ]
		];
		return view('pnkpanel.dealweek.list')->with($pageData);
	}
    
	public function edit($id = 0) {
        if($id > 0) {
			$dealweek = DealWeek::findOrFail($id);
		} else {
			$dealweek =  new DealWeek;
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Deal Week';
		$pageData['meta_title'] = $prefix.' Deal Week';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Deal Week List',
				 'url' =>route('pnkpanel.dealweek.list')
			 ],
			 [
				 'title' => $prefix.' Deal Week',
				 'url' =>route('pnkpanel.dealweek.edit', $id)
			 ]
		];
		
        return view('pnkpanel.dealweek.edit', compact('dealweek'))->with($pageData);;
    }
	
    public function update(Request $request) {
		$actType = $request->actType;
		$id = $request->id;

		$prodctTableName =  $this->prefix.'products';
		$dealOFWeekTableName =  $this->prefix.'dealofweek';
		$this->validate($request, [
            'product_sku' 			=> 'required|exists:'.$prodctTableName.',sku|unique:'.$dealOFWeekTableName.',product_sku,'.$id.',dealofweek_id',
			'deal_price' 			=> 'required|numeric',
            'start_date' 			=> 'required|date',
			'end_date' 				=> 'required|date|after_or_equal:start_date',
			'display_rank' 			=> 'required|numeric',
			'display_on_home'		=> 'required|in:Yes,No',
			'deal_type'				=> 'required|in:0,1',
			'status'				=> 'required|in:0,1',
		],
		[
			'product_sku.required' => 'Please enter product sku in product section',
        	'product_sku.exists'   => 'The enter product sku  is invalid.',
			'deal_price.required' => 'Please enter discount amount',
        	'deal_price.numeric' => 'Please enter only positive numeric value',
        	'start_date.required' => 'Please select the Discount Start Date',
        	'end_date.required' => 'Please select the Discount End Date',
        	'end_date.after_or_equal' => 'Start Date should be smaller than End Date',
			'display_on_home.required' => 'Please select dipsplay on home',
			'deal_type.required' => 'Please select deal type',
        	'status.required' => 'Please select Status',
		]);

		$dealOFWeek = DealWeek::findOrNew($id);

		$start_date = Carbon::parse($request['start_date']);
		$start_date = $start_date->format('Y-m-d');
		$end_date = Carbon::parse($request['end_date']);
		$end_date = $end_date->format('Y-m-d');

			$dealOFWeek->product_sku    	= $request->product_sku;
			$dealOFWeek->start_date     	= $start_date;
			$dealOFWeek->end_date       	= $end_date;
			$dealOFWeek->description    	= isset($request->description) ? $request->description : '';
			$dealOFWeek->deal_price   		= $request->deal_price;
			$dealOFWeek->status 			=  $request->status;	
			$dealOFWeek->display_rank 		= $request->display_rank;
			$dealOFWeek->display_on_home 	=  $request->display_on_home;
			$dealOFWeek->deal_type 	    	= $request->deal_type;
		
		if($dealOFWeek->save()) {
			if($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update')); 
			}
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.dealweek.edit', $dealOFWeek->dealofweek_id);
	}

	public function delete($id){
		if(DealWeek::findOrFail($id)->delete()) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.dealweek.list');
	}
}
