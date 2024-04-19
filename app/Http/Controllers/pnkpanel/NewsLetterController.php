<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsLetter;
use DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NewsLetterExports;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class NewsLetterController extends Controller
{
    use CrudControllerTrait;

    public function model()
    {
        return NewsLetter::class;
    }

	public function list(Request $request) {
		if(request()->ajax()) {
			$model = NewsLetter::select([
				'news_letter_id',
				'email',
				'first_name',
				'last_name',
				'insert_datetime',
				'status'				
			]);
			if (!request()->get('order')) {
				$model->orderBy('news_letter_id', 'asc');
			}
			
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->news_letter_id.'" />';
			});
			$table->editColumn('email', function($row) {				
				return $row->email;
			});
			$table->editColumn('first_name', function($row) {				
				return $row->first_name;
			});
			$table->editColumn('last_name', function($row) {				
				return $row->first_name;
			});
			$table->editColumn('insert_datetime', function($row) {				
				return $row->insert_datetime;
			});
			$table->editColumn('status', function($row) {
				return ($row->status ? 'Active':'Inactive');
			});
			$table->rawColumns(['checkbox', 'email', 'first_name', 'last_name', 'insert_datetime','status']);
			return $table->make(true);
		}
		$pageData['page_title'] = "Newsletter List";
		$pageData['meta_title'] = "Newsletter List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Newsletter List',
				 'url' =>route('pnkpanel.newsletter.list')
			 ]
		];
		return view('pnkpanel.newsletter.list')->with($pageData);
	}
    
    /*public function list(Request $request) {
		if(request()->ajax()) {
	 		$model = NewsLetter::select('news_letter_id', 'email', 'first_name', 'last_name', 'insert_datetime', 'status');
	 		if (!request()->get('order')) {
	 			$model->orderBy('news_letter_id', 'asc');
	 		}
	 		$table = DataTables::eloquent($model);
	 		$table->addIndexColumn();
	 		$table->addColumn('checkbox', function($row) {
	 			return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->news_letter_id.'" />';
	 		});

	 		$table->editColumn('email', function($row) {                                
                 return $row->email;
             });
	 		$table->editColumn('first_name', function($row) {                                
                 return $row->first_name;
             });

	 		$table->editColumn('last_name', function($row) {                                
                 return $row->last_name;
             });
			
	 		$table->editColumn('insert_datetime', function($row) {
	 			return $insert_datetime = Carbon::parse($row->insert_datetime)->format('m/d/Y');
	 		});
	 		$table->editColumn('status', function($row) {
	 			return ($row->status ? 'Active':'Inactive');
	 		});
	 		$table->rawColumns(['checkbox']);
	 		return $table->make(true);
	 	}
		
	 	$pageData['page_title'] = "Newsletter List";
	 	$pageData['meta_title'] = "Newsletter List";
	 	$pageData['breadcrumbs'] = [
	 		 [
	 			 'title' => 'Newsletter List',
	 			 'url' =>route('pnkpanel.newsletter.list')
	 		 ]
	 	];
		
	 	return view('pnkpanel.newsletter.list')->with($pageData);
	 }*/

    public function newsLetterExport(){
    	$export_file_name = 'newsletter.csv';
		$header_row = ["Email", "First Name", "Last Name", "Date", "Status"];
		$newsletter_data = NewsLetter::all();
		$csv_data = [];
		if(count($newsletter_data) > 0) {
			foreach($newsletter_data as $newsletter_key => $newsletter_value) {
				$csv_data[$newsletter_key]['email'] = $newsletter_value['email'];
				$csv_data[$newsletter_key]['first_name'] = $newsletter_value['first_name'];
				$csv_data[$newsletter_key]['last_name'] = $newsletter_value['last_name'];
				$csv_data[$newsletter_key]['insert_datetime'] = Carbon::parse($newsletter_value['insert_datetime'])->format('m/d/Y');
				$csv_data[$newsletter_key]['status'] = ($newsletter_value['status'] == '0') ? 'InActive' : 'Active';
			}
			return Excel::download(new NewsLetterExports($csv_data, $header_row), $export_file_name);
		} else {
			session()->flash('site_common_msg_err', 'Something went wrong. Please try again later.'); 
		}

        // return Excel::download(new NewsLetterExports, 'newsletter.csv');
    }

}
