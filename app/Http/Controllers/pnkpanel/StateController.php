<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use DataTables;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class StateController extends Controller
{
    use CrudControllerTrait;

    public function model()
    {
        return State::class;
    }

    public function list(Request $request) {
		if(request()->ajax()) {
			// dd($request);
			$model = State::select('state_id','name','code','status');
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->state_id.'" />';
			});
			$table->editColumn('status', function($row) {
				return ($row->status ? 'Active':'Inactive');
			});

			$table->rawColumns(['checkbox','name','code']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "State List";
		$pageData['meta_title'] = "State List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'State List',
				 'url' =>route('pnkpanel.state.list')
			 ]
		];
		
		return view('pnkpanel.state.list')->with($pageData);
	}
}
