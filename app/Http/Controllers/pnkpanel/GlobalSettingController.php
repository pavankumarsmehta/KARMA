<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use DataTables;

class GlobalSettingController extends Controller
{
    
	public function index(Request $request) {
		$global_setting = SiteSetting::select('site_settings_id', 'title', 'var_name', 'setting', 'description', 'html_element', 'html_element_value')->orderBy('display_order')->get();
		
		$pageData['page_title'] = 'Site Global Settings';
		$pageData['meta_title'] = 'Site Global Settings';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Site Global Settings',
				 'url' =>route('pnkpanel.global-setting.index')
			 ]/*,
			 [
				 'title' => 'Global Settings',
				 'url' =>route('pnkpanel.global-setting.index')
			 ]*/
		];
		
        return view('pnkpanel.global-setting.edit', compact('global_setting'))->with($pageData);;
    }
	
	public function update(Request $request) {
		
		$global_setting = SiteSetting::select('site_settings_id')->orderBy('display_order')->get();
		if(count($global_setting) > 0) {
			foreach($global_setting as $gs_key => $gs_value) {
				$updateArray['setting'] = $request[$gs_value["site_settings_id"]];
				$update = SiteSetting::where('site_settings_id', '=', $gs_value['site_settings_id'])->update($updateArray);
			}
		}
        session()->flash('site_common_msg', config('messages.msg_update')); 
		return redirect()->route('pnkpanel.global-setting.index');

	}

	
}
