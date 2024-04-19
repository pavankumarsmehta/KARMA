<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\QuantityDiscount;
use Carbon\Carbon;
use DataTables;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class QuantityDiscountController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return QuantityDiscount::class;
    }
    
    public function list() {
		if(request()->ajax()) {
			$model = QuantityDiscount::select([
				'quantity_discount_id',
				'quantity',
				'quantity_discount_amount',
				'type',
				'start_date',
				'end_date',
				'status'
			]);
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->quantity_discount_id.'" />';
			});
			/*$table->editColumn('quantity', function($row) {
				return '$'.number_format($row->quantity,2,'.','');
			});*/
			$table->editColumn('quantity_discount_amount', function($row) {
				if($row->type=='1')	{
					$type_per	=	'%';
					$type_us	=	"";
				} else {
					$type_per	=	"";
					$type_us	=	"$";
				}

				return $type_us.number_format($row->quantity_discount_amount,2,'.','').$type_per;
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
				return '<a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->quantity_discount_id.'" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a><a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->quantity_discount_id.'" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>';
			});

			$table->rawColumns(['checkbox', 'quantity', 'quantity_discount_amount', 'start_date', 'action']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "Quantity Discount List";
		$pageData['meta_title'] = "Quantity Discount List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Quantity Discount List',
				 'url' =>route('pnkpanel.quantitydiscount.list')
			 ]
		];
		
		return view('pnkpanel.promotion.quantitydiscount.list')->with($pageData);
	}
	
	public function edit($id = 0) {
        if($id > 0) {
			$quantitydiscount = QuantityDiscount::findOrFail($id);
		} else {
			$quantitydiscount =  new QuantityDiscount;
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Quantity Discount';
		$pageData['meta_title'] = $prefix.' Quantity Discount';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Quantity Discount List',
				 'url' =>route('pnkpanel.quantitydiscount.list')
			 ],
			 [
				 'title' => $prefix.' Quantity Discount',
				 'url' =>route('pnkpanel.quantitydiscount.edit', $id)
			 ]
		];
		
        return view('pnkpanel.promotion.quantitydiscount.edit', compact('quantitydiscount'))->with($pageData);;
    }
	
    public function update(Request $request) {
    	// dd($request);
		$actType = $request->actType;
		$id = $request->id;
		$quantitydiscount = QuantityDiscount::findOrNew($id);
		
		$this->validate($request, [
			'quantity'			=> 'required|numeric',
            'quantity_discount_amount' 	=> 'required|numeric',
            'start_date' 			=> 'required|date',
            'end_date' 				=> 'required|date|after_or_equal:start_date',
			'status'				=> 'required|numeric'
		],
		[
        	'quantity.required' => 'Please Enter Quantity',
        	'quantity.numeric' => 'Please enter only positive numeric value',
        	'quantity_discount_amount.required' => 'Please Enter Discount Amount',
        	'quantity_discount_amount.numeric' => 'Please enter only positive numeric value',
        	'start_date.required' => 'Please select the Discount Start Date',
        	'end_date.required' => 'Please select the Discount End Date',
        	'end_date.after_or_equal' => 'Start Date should be smaller than End Date',
        	'status.required' => 'Please select Status',
		]);

		if($request->type == '1' && ($request->quantity_discount_amount > 100 || $request->quantity_discount_amount <= 0))
		{
			// dd($request);
			if($actType == 'add') {
				$betweenErrorMessage = 'Did not added, Quantity Discount Percent value must be between 0 and 100.';
			} else {
				$betweenErrorMessage = 'Did not updated, Quantity Discount Percent value must be between 0 and 100.';
			}
			return redirect()->back()
			->withInput()
			->withErrors([
				'quantity_discount_between' => $betweenErrorMessage,
			]);
		}

		$start_date = Carbon::parse($request['start_date']);
		// $start_date = $start_date->format('Y-m-d');
		$start_date = $start_date->format('m/d/Y');
		$end_date = Carbon::parse($request['end_date']);
		// $end_date = $end_date->format('Y-m-d');
		$end_date = $end_date->format('m/d/Y');

		$quantitydiscount->quantity = isset($request->quantity) ? $request->quantity : '';
		$quantitydiscount->quantity_discount_amount = isset($request->quantity_discount_amount) ? $request->quantity_discount_amount : '';
		$quantitydiscount->start_date = $start_date;
		$quantitydiscount->end_date = $end_date;
		$quantitydiscount->type = isset($request->type) ? $request->type : '';
		$quantitydiscount->detail = isset($request->detail) ? $request->detail : '';
		$quantitydiscount->status 	=  $request->status;
		
		if($quantitydiscount->save()) {
			if($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update')); 
			}
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.quantitydiscount.edit', $quantitydiscount->quantity_discount_id);
	}
	
	public function delete($id){
		if(QuantityDiscount::findOrFail($id)->delete()) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.quantitydiscount.list');
	}
	
}
