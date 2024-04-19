<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetaInfo;
use DataTables;
use Carbon\Carbon;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class MetaInfoController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return MetaInfo::class;
    }

	public function edit($type = '') {
        if($type == '') {
        	$type = 'HO';
		}
		$metainfo = MetaInfo::where('type', '=', $type)->first();
		$prefix = ($type != '' ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Site Meta Information';
		$pageData['meta_title'] = $prefix.' Site Meta Information';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Site Meta Information',
				 'url' =>route('pnkpanel.meta-info.edit')
			 ]/*,
			 [
				 'title' => $prefix.' Site Meta Information',
				 'url' =>route('pnkpanel.meta-info.edit', $type)
			 ]*/
		];

        return view('pnkpanel.meta-info.edit', compact('metainfo'))->with($pageData);;
    }
	
    public function update(Request $request) {
		// dd($request->all());
		
		$actType = $request->actType;
		$type = trim($request->type);
				
		if($actType == 'add') {

			$check_entry = MetaInfo::where('type', '=', $type)->first();
			if($get_data->count() <= 0) {

				$metainfo = new MetaInfo;
				//$category = Category::findOrNew($category_id);
				$metainfo->type						=  $request->type;
				$metainfo->meta_title				=  $request->meta_title;
				$metainfo->meta_keywords 			=  $request->meta_keywords;
				$metainfo->meta_description 		=  $request->meta_description;
				if($metainfo->save()) {
					session()->flash('site_common_msg', config('messages.msg_add'));
				} else {
					session()->flash('site_common_msg_err', config('messages.msg_add_err'));
				}
			}
		}

		if($actType == 'update') {
			// $metainfo = new MetaInfo;

			//$category = Category::findOrNew($category_id);
			$metainfo['meta_title']				=  $request->meta_title;
			$metainfo['meta_keywords'] 				=  $request->meta_keywords;
			$metainfo['meta_description'] 		=  $request->meta_description;

			$updateData = MetaInfo::where('type', $type)
		      				->update($metainfo);
		    
			session()->flash('site_common_msg', config('messages.msg_update')); 

		}

		return redirect()->route('pnkpanel.meta-info.edit', $type);
	}

	public function getHtml(Request $request) {
		$type = ($request->type != '') ? $request->type : 'HO';
		$html = '';
		$get_data = MetaInfo::where('type', '=', $type)->first();
		if($get_data && $get_data->count() > 0) {
			$success = true;
			$response_http_code = 200;
			$errors = [];
			$messages = ['messages' => 'Success'];
			$html = view('pnkpanel.meta-info.html', compact('get_data'))->render();
		} else {
			$success = false;
			$response_http_code = 400;
			$messages = [];
			$errors = 'Something went wrong. Please try again later.';
		}

        return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages, 'html' => $html), $response_http_code);
	}
	
}
