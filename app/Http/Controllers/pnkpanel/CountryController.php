<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use DataTables;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class CountryController extends Controller
{
    use CrudControllerTrait;

    public function model()
    {
        return Country::class;
    }
    
    public function list(Request $request) {
		if(request()->ajax()) {
			// dd($request);
			$model = Country::select('countries_id','countries_name','countries_iso_code_2','status');
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->countries_id.'" />';
			});
			$table->editColumn('status', function($row) {
				return ($row->status ? 'Active':'Inactive');
			});

			$table->rawColumns(['checkbox', 'countries_name','countries_iso_code_2']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "Country List";
		$pageData['meta_title'] = "Country List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Country List',
				 'url' =>route('pnkpanel.country.list')
			 ]
		];
		
		return view('pnkpanel.country.list')->with($pageData);
	}

}
