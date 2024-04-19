<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BottomHtml;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use File;

class BottomHtmlController extends Controller
{
    //
    use CrudControllerTrait;

    public function model()
    {
        return BottomHtml::class;
    }
    
	public function index(Request $request) {
		$bottom = BottomHtml::where('section', '=', 'Bottom')->first();
		$top = BottomHtml::where('section', '=', 'Top')->first();
		$diy = BottomHtml::where('section', '=', 'DIY')->first();
		$pageData['page_title'] = 'Site HTML';
		$pageData['meta_title'] = 'Site HTML';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Site HTML',
				 'url' =>route('pnkpanel.bottom-html.index')
			 ]
		];

        return view('pnkpanel.bottom-html.edit', compact('bottom', 'top', 'diy'))->with($pageData);;
    }
	
    public function update(Request $request) {
    	// dd($request);
		## Bottom HTML
		$home_html_text  = $request['home_html_text'];
		$home_html_text  = str_replace(config('const.SITE_URL'),'{$Site_URL}',$home_html_text );
		$home_html_text = str_replace(array('\r', '\n'), '', $home_html_text);
		// $home_html_text  = str_replace($SECURED_PATH,'{$Site_URL}',$home_html_text );
		
		$arrUpdate = array('home_html_text' => $home_html_text);
		$Bottom = BottomHtml::where('section', '=', 'Bottom')->update($arrUpdate);
		
		## Top HTML
		$home_top_html_text  = $request['home_top_html_text'];
		$home_top_html_text  = str_replace(config('const.SITE_URL'),'{$Site_URL}',$home_top_html_text );
		$home_top_html_text = str_replace(array('\r', '\n'), '', $home_top_html_text);
		// $home_top_html_text  = str_replace($SECURED_PATH,'{$Site_URL}',$home_top_html_text );
		
		$arrUpdate = array('home_html_text' => $home_top_html_text);
		$se2 = BottomHtml::where('section', '=', 'Top')->update($arrUpdate);

		## DIY HTML
		$diy_page_html_text  = $request['diy_page_html_text'];
		$diy_page_html_text  = str_replace(config('const.SITE_URL'),'{$Site_URL}',$diy_page_html_text );
		$diy_page_html_text = str_replace(array('\r', '\n'), '', $diy_page_html_text);
		// $home_top_html_text  = str_replace($SECURED_PATH,'{$Site_URL}',$home_top_html_text );
		
		$arrUpdate = array('home_html_text' => $diy_page_html_text);
		$se2 = BottomHtml::where('section', '=', 'DIY')->update($arrUpdate);
		
		session()->flash('site_common_msg', config('messages.msg_update')); 
		return redirect()->route('pnkpanel.bottom-html.index');
	}

}

