<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AutoDiscount;
use Carbon\Carbon;
use DataTables;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class AutoDiscountController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return AutoDiscount::class;
    }
    
    public function list() {
		if(request()->ajax()) {
			$model = AutoDiscount::select([
				'auto_discount_id',
				'order_amount',
				'auto_discount_amount',
				'type',
				'start_date',
				'end_date',
				'status'
			]);
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->auto_discount_id.'" />';
			});
			$table->editColumn('order_amount', function($row) {
				return '$'.number_format($row->order_amount,2,'.','');
			});
			$table->editColumn('auto_discount_amount', function($row) {
				if($row->type=='1')	{
					$type_per	=	'%';
					$type_us	=	"";
				} else {
					$type_per	=	"";
					$type_us	=	"$";
				}

				return $type_us.number_format($row->auto_discount_amount,2,'.','').$type_per;
			});
			// $start_date = '';
			$table->editColumn('start_date', function($row) {
				$start_date = Carbon::parse($row->start_date)->format('m/d/Y');
				$end_date = Carbon::parse($row->end_date)->format('m/d/Y');
				return $start_date.' to '.$end_date;
			});
			$table->editColumn('status', function($row) {
				return ($row->status ? 'Active':'Inactive');
			});
			// $table->addColumn('action', 'pnkpanel.component.datatable_action');
			$table->addColumn('action', function($row) {
				return '<a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->auto_discount_id.'" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a><a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->auto_discount_id.'" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>';
			});

			$table->rawColumns(['checkbox', 'order_amount', 'auto_discount_amount', 'start_date', 'action']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "Auto Discount List";
		$pageData['meta_title'] = "Auto Discount List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Auto Discount List',
				 'url' =>route('pnkpanel.autodiscount.list')
			 ]
		];
		
		return view('pnkpanel.promotion.autodiscount.list')->with($pageData);
	}
	
	public function edit($id = 0) {
        if($id > 0) {
			$autodiscount = AutoDiscount::findOrFail($id);
		} else {
			$autodiscount =  new AutoDiscount;
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Auto Discount';
		$pageData['meta_title'] = $prefix.' Auto Discount';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Auto Discount List',
				 'url' =>route('pnkpanel.autodiscount.list')
			 ],
			 [
				 'title' => $prefix.' Auto Discount',
				 'url' =>route('pnkpanel.autodiscount.edit', $id)
			 ]
		];
		
        return view('pnkpanel.promotion.autodiscount.edit', compact('autodiscount'))->with($pageData);;
    }
	
    public function update(Request $request) {
    	// dd($request);
		$actType = $request->actType;
		$id = $request->id;
		$autodiscount = AutoDiscount::findOrNew($id);
		
		$this->validate($request, [
			'order_amount'			=> 'required|numeric',
            'auto_discount_amount' 	=> 'required|numeric',
            'start_date' 			=> 'required|date',
            'end_date' 				=> 'required|date|after_or_equal:start_date',
			'status'				=> 'required|numeric'
		],
		[
        	'order_amount.required' => 'Please Enter Order Amount',
        	'order_amount.numeric' => 'Please enter only positive numeric value',
        	'auto_discount_amount.required' => 'Please Enter Discount Amount',
        	'auto_discount_amount.numeric' => 'Please enter only positive numeric value',
        	'start_date.required' => 'Please select the Discount Start Date',
        	'end_date.required' => 'Please select the Discount End Date',
        	'end_date.after_or_equal' => 'Start Date should be smaller than End Date',
        	'status.required' => 'Please select Status',
		]);

		if($request->type == '1' && ($request->auto_discount_amount > 100 || $request->auto_discount_amount <= 0))
		{
			if($actType == 'add') {
				$betweenErrorMessage = 'Did not added, Auto Discount Percent value must be between 0 and 100.';
			} else {
				$betweenErrorMessage = 'Did not updated, Auto Discount Percent value must be between 0 and 100.';
			}
			return redirect()->back()
			->withInput()
			->withErrors([
				'auto_discount_between' => $betweenErrorMessage,
			]);
		}

		$start_date = Carbon::parse($request['start_date']);
		// $start_date = $start_date->format('Y-m-d');
		$start_date = $start_date->format('m/d/Y');
		$end_date = Carbon::parse($request['end_date']);
		// $end_date = $end_date->format('Y-m-d');
		$end_date = $end_date->format('m/d/Y');

		$autodiscount->order_amount = isset($request->order_amount) ? $request->order_amount : '';
		$autodiscount->auto_discount_amount = isset($request->auto_discount_amount) ? $request->auto_discount_amount : '';
		$autodiscount->start_date = $start_date;
		$autodiscount->end_date = $end_date;
		$autodiscount->type = isset($request->type) ? $request->type : '';
		$autodiscount->detail = isset($request->detail) ? $request->detail : '';
		$autodiscount->status 	=  $request->status;
		if($autodiscount->save()) {
			if($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update')); 
			}
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.autodiscount.edit', $autodiscount->auto_discount_id);
	}
	
	public function delete($id){
		if(AutoDiscount::findOrFail($id)->delete()) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.autodiscount.list');
	}
	
}
