<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPages;
use App\Models\Currency;
use Carbon\Carbon;
use DataTables;
use File;
use DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class CurrencyController extends Controller
{
    use CrudControllerTrait;

    public function model()
    {
        $action_method = explode('/', url()->full());
        if (in_array("manage-currency", $action_method)) {
            return Currency::class;
        }
    }

    public function manage_currency_list()
    {
        if (request()->ajax()) {
            $model = Currency::select('*');
            $table = DataTables::eloquent($model);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->currencies_id . '" />';
            });
            $table->editColumn('status', function ($row) {
                return ($row->status ? 'Active' : 'Inactive');
            });
            $table->editColumn('action', function ($row) {
                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->currencies_id]);
                return $action;
            });
            $table->rawColumns(['checkbox', 'action']);
            return $table->make(true);
        }

        $pageData['page_title'] = "Manage Currency";
        $pageData['meta_title'] = "Manage Currency";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manage Currency',
                'url' => route('pnkpanel.manage-currency.list')
            ]
        ];
        return view('pnkpanel.currency.currency_list')->with($pageData);
    }

    public function manage_currency_edit($id = 0)
    {
        if ($id > 0) {
            $currency = Currency::findOrFail($id);
        } else {
            $currency =  new Currency;
        }
        $prefix = ($id > 0 ? 'Edit' : 'Add New');
        $pageData['page_title'] = $prefix . ' Currency';
        $pageData['meta_title'] = $prefix . ' Currency';
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manage Currency',
                'url' => route('pnkpanel.manage-currency.list')
            ],
            [
                'title' => $prefix . ' Currency',
                'url' => route('pnkpanel.manage-currency.edit', $id)
            ]
        ];
        return view('pnkpanel.currency.currency_edit', compact('currency'))->with($pageData);;
    }

    public function manage_currency_update(Request $request)
    {   
        $actType = $request->actType;
        $id = $request->id;
        $is_delete = $request->is_delete;
        $del_chk = $request->del_chk;
        $del_chk_arr = explode(",",$del_chk);
        $currency = Currency::findOrNew($id);
        $this->validate($request, [
            'title' => 'required|string',
            'code' => 'required',
            'decimal_point' => 'required',
            'value' => 'required',
        ],
        [
            'title.required' => 'Currency Title is required',
            'code.required' => 'Currency Code is required',
            'decimal_point.required' => 'Decimal point is required',
            'value.required' => 'Currency Value is required',
        ]);

        $currency->title     =  $request->title;
        $currency->code =  $request->code;
        $currency->symbol_left     =  $request->symbol_left ? $request->symbol_left :'';
        $currency->symbol_right     =  $request->symbol_right ? $request->symbol_right :'';
        $currency->decimal_point     =  $request->decimal_point ? $request->decimal_point :'';
        $currency->thousands_point     =  $request->thousands_point ? $request->thousands_point :'';
        $currency->decimal_places     =  $request->decimal_places ? $request->decimal_places :'';
        $currency->value     =  $request->value;
        $currency->last_updated     =  date("Y-m-d H:i:s", time());
        $currency->status     =  $request->status;
        if ($currency->save()) {

            
            if ($actType == 'add') {
                session()->flash('site_common_msg', config('messages.msg_add'));
                return redirect()->route('pnkpanel.manage-currency.list', $currency->currencies_id);
            } else {
                session()->flash('site_common_msg', config('messages.msg_update'));
            }
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_add_err'));
        }
        return redirect()->route('pnkpanel.manage-currency.list', $currency->currencies_id);
    }

    public function manage_currency_delete($id)
    {
        if (Currency::findOrFail($id)->delete()) {
            session()->flash('site_common_msg', config('messages.msg_delete'));
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_delete_err'));
        }
        return redirect()->route('pnkpanel.manage-currency.list');
    }
}
