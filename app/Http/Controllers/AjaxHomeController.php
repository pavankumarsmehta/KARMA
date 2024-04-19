<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;
use App\Models\Newsletter;
use App\Models\HomeProducts;
use Cache;

class AjaxHomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //use GeneralTrait;
    public $successStatus = 200;
    
    public function __construct()
    {
		$this->prefix = 'hba_';
        //$this->middleware('cart');
    }

    public function Deals(Request $request)
	{
		$sectionType = $_POST["sectionType"];
		if($sectionType == "hdeals")
		{
			$rsDeals = 1;
			/*if(count($rsDeals) > 0)
			{*/
				$Deals = array('test','test1');
				$result = array(
					'success'	=>	true,
					'Deals'	=>	implode("",$Deals));
				return response()->json( $result );
			//}
		}
	}
	
	public function SeasonSpecial(Request $request)
	{
		$sectionType = $_POST["sectionType"];
		if($sectionType == "hdeals")
		{
			
		}
	}
	
	public function Category(Request $request)
	{
		$sectionType = $_POST["sectionType"];
		if($sectionType == "hdeals")
		{
			
		}
	}
	
	public function Brands(Request $request)
	{
		$sectionType = $_POST["sectionType"];
		if($sectionType == "hdeals")
		{
			
		}
	}
	
	public function NewArrival(Request $request)
	{
		$sectionType = $_POST["sectionType"];
		if($sectionType == "hdeals")
		{
			
		}
	}
	
	public function AboutUs(Request $request)
	{
		$sectionType = $_POST["sectionType"];
		if($sectionType == "haboutus")
		{
			$rsAboutus = DB::table($this->prefix.'home_products')->where('home_flag','=','ABOUT')->get();
			//dd($rsAboutus,config('const.SITE_IMAGES_PATH').'homeimg/'.$rsAboutus[0]->image_name);
			$AboutImage = "https://via.placeholder.com/750x600?text=Coming%20Soon";
			if(file_exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$rsAboutus[0]->image_name) && $rsAboutus[0]->image_name != "")
			{
				$AboutImage = config('const.SITE_IMAGES_URL').'homeimg/'.$rsAboutus[0]->image_name;
			}
			$AboutUS=array();
			$AboutUS[]='<div class="homabo-about">
				<div class="homabo-thumb hidden-md-down">
					<img src="'.$AboutImage.'" alt="" width="623" height="762" loading="lazy"/>
				</div>
				<h5>'.$rsAboutus[0]->title.'</h5>
				<p>'.$rsAboutus[0]->text.'</p>
				<div><a href="'.$rsAboutus[0]->link.'" class="linksbb"><strong>'.$rsAboutus[0]->button_name.'</strong></a></div>
			</div>';
			
			if(count($rsAboutus) > 0)
			{
				$result = array(
					'success'	=>	true,
					'AboutUS'	=>	implode("",$AboutUS));
				return response()->json( $result );
			}
		}
	}
	
	
}
