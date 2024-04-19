<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPages;
use App\Models\Quotation;
use Carbon\Carbon;
use DataTables;
use File;
use DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class QuotationController extends Controller
{
    use CrudControllerTrait;

    public function model()
    {
        $action_method = explode('/', url()->full());
        if (in_array("manage-quotations", $action_method)) {
            return Quotation::class;
        }
    }

    public function manage_quotations_list()
    {
        if (request()->ajax()) {
            $model = Quotation::select('*');
            $table = DataTables::eloquent($model);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->quotation_id . '" />';
            });
            $table->editColumn('status', function ($row) {
                return ($row->status ? 'Active' : 'Inactive');
            });
            $table->editColumn('action', function ($row) {
                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->quotation_id]);
                return $action;
            });
            $table->rawColumns(['checkbox', 'action']);
            return $table->make(true);
        }

        $pageData['page_title'] = "Manage Quotation";
        $pageData['meta_title'] = "Manage Quotation";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manage Quotation',
                'url' => route('pnkpanel.manage-quotations.list')
            ]
        ];
        return view('pnkpanel.quotations.quotations_list')->with($pageData);
    }

    public function manage_quotations_edit($id = 0)
    {
        if ($id > 0) {
            $quotations = Quotation::findOrFail($id);
        } else {
            $quotations =  new Quotation;
        }
        $prefix = ($id > 0 ? 'Edit' : 'Add New');
        $pageData['page_title'] = $prefix . ' Quotations';
        $pageData['meta_title'] = $prefix . ' Quotations';
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manage Quotation',
                'url' => route('pnkpanel.manage-quotations.list')
            ],
            [
                'title' => $prefix . ' Quotation',
                'url' => route('pnkpanel.manage-quotations.edit', $id)
            ]
        ];
        return view('pnkpanel.quotations.quotations_edit', compact('quotations'))->with($pageData);
    }

    public function manage_quotations_update(Request $request)
    {   
        $actType = $request->actType;
        $id = $request->id;
        $is_delete = $request->is_delete;
        $del_chk = $request->del_chk;
        $del_chk_arr = explode(",",$del_chk);
        $quotations = Quotation::findOrNew($id);
        $this->validate($request, [
            'sourcing_product' => 'required|string',
            'quantity' => 'required',
            'unit' => 'required',
        ],
        [
            'sourcing_product.required' => 'Sorcing product is required',
            'quantity.required' => 'Quantity is required',
            'unit.required' => 'Unit is required',
        ]);

        $quotations->sourcing_product     =  $request->sourcing_product;
        $quotations->quantity =  $request->quantity;
        $quotations->unit     =  $request->unit;
        $quotations->last_updated     =  date("Y-m-d H:i:s", time());
        $quotations->status     =  $request->status;
        if ($quotations->save()) {
            
            if ($actType == 'add') {
                session()->flash('site_common_msg', config('messages.msg_add'));
                return redirect()->route('pnkpanel.manage-quotations.list', $quotations->quotation_id);
            } else {
                session()->flash('site_common_msg', config('messages.msg_update'));
            }
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_add_err'));
        }
        return redirect()->route('pnkpanel.manage-quotations.list', $quotations->quotation_id);
    }

    public function manage_quotations_delete($id)
    {
        if (Quotation::findOrFail($id)->delete()) {
            session()->flash('site_common_msg', config('messages.msg_delete'));
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_delete_err'));
        }
        return redirect()->route('pnkpanel.manage-quotations.list');
    }
}
