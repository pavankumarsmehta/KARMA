<?php

namespace App\Http\Controllers;

use Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\HomeImage;
use App\Models\YoutubeVideos;
use App\Models\HomeProducts;
use App\Models\Product;
use App\Models\Brand;
use App\Models\DealWeek;
use App\Models\Testimonial;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\productTrait;
use App\Helpers\InstagramApp;
use DB;
use Cache;
use App\Models\MetaInfo;
use App\Models\InstagramFeed;
use App\Models\InstagramSettings;
Use \Carbon\Carbon;
use Illuminate\Support\Str;

class HomeController extends Controller
{
	use generalTrait;
	use productTrait;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Request $request)
	{
		$this->prefix = 'hba_';
		//$this->middleware('auth');
		
		
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */

	public function index(Request $request)
	{
		if($request->input('testmode') && $request->input('testmode') == 'yes'){
            Session::put('testmode', 'yes');
			session()->save();
        }else if($request->input('testmode') && $request->input('testmode') == 'no'){
            Session::forget('testmode');
        }

		$this->PageData['CSSFILES'] = ['slick.css','home.css','custom.js'];
		$PageType = 'HO';
		if (Cache::has('homevars_metainfo_cache')) {
			$MetaInfo = Cache::get('homevars_metainfo_cache');
		}
		else {
			$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();
			Cache::put('homevars_metainfo_cache', $MetaInfo);
		}
		
		//dd($MetaInfo);
		if (count($MetaInfo->toArray()) > 0) {
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
			
		######## Main Slider Section Start ########
		$HomePageBanner_Main = [];
		$HomePageBanner_Middle = [];
		$HomePageBanner_Middle_Wholesaler = [];
		$HomePageBanner_Bottom = [];
		$HomePage_instagram = [];

		if (Cache::has('instasettingsvars_cache')) {
			$InstaSettings = Cache::get('instasettingsvars_cache');
		} else {
			$InstagramSettings = InstagramSettings::selectRaw('var_name,setting')->get();
			
			$InstaSettings = array();
			foreach ($InstagramSettings as $InstaSetting) {
				$InstaSettings[$InstaSetting->var_name] = $InstaSetting->setting;
			}
			Cache::put('instasettingsvars_cache', $InstaSettings);
		}
		
		if($InstaSettings['INSTAGRAM_STATUS'] == 'Enable' && $InstaSettings['IS_SHOW_HOME_PAGE'] == 'Yes')
		{
			if (Cache::has('homevars_HomePageBanner_instagram_cache')) {
				$HomePage_instagram = Cache::get('homevars_HomePageBanner_instagram_cache');
			}
			else{
				$Home_instagram_feeds = InstagramFeed::select('*')
					->where('status', '=', '1')
					->orderByDesc('instagram_feed_id')
					->limit($InstaSettings['NO_IMAGES_HOME_PAGE']);
				$Home_instaRes = $Home_instagram_feeds->get();
				
				$HomeInstaCount = count($Home_instaRes->toArray());
				if ($HomeInstaCount > 0) {
					foreach ($Home_instaRes as $HomeInstaValue) {
						$HomePage_instagram[] = array(
							'instagram_post_id'		=> $HomeInstaValue->instagram_post_id,
							'media_type'			=> $HomeInstaValue->media_type,
							'media_url'				=> $HomeInstaValue->media_url,
							'permalink'				=> $HomeInstaValue->permalink
						);
					}
				}
				Cache::put('homevars_HomePageBanner_instagram_cache', $HomePage_instagram);
			}
		}
		
		
		if (Cache::has('homevars_HomePageBanner_Main_cache') || Cache::has('homevars_HomePageBanner_Promotion_cache') || Cache::has('homevars_HomePageBanner_Wholesaler_cache') || Cache::has('homevars_HomePageBanner_Bottom_cache')) {
			$HomePageBanner_Main = Cache::get('homevars_HomePageBanner_Main_cache');
			$HomePageBanner_Middle = Cache::get('homevars_HomePageBanner_Promotion_cache');
			$HomePageBanner_Middle_Wholesaler = Cache::get('homevars_HomePageBanner_Wholesaler_cache');
			$HomePageBanner_Bottom = Cache::get('homevars_HomePageBanner_Bottom_cache');
		}
		else 
		{			
			$HomeImage = HomeImage::select(['link', 'title', 'home_image', 'banner_position', 'image_alt_text', 'banner_text', 'home_image_mobile', 'display_position','video_url','video_url_mobile','added_date'])
				->whereIn('banner_position', ['HOME_MAIN', 'HOME_MIDDLE', 'HOME_MIDDLE_WHOLESALER', 'HOME_BOTTOM'])
				->where('status', '=', '1')
				->orderBy('position');
				$HomeImageRes = $HomeImage->get();
			$HomeImageCount = count($HomeImageRes->toArray());
			
			
			if ($HomeImageCount > 0) {
				$thumb_image = $thumb_image_mobile = '';
				foreach ($HomeImageRes as $HomeImageValue) {
					
					if ($HomeImageValue->banner_position == "HOME_MAIN") {
						//echo $HomeImageValue->home_image;
						if(isset($HomeImageValue->home_image) && $HomeImageValue->home_image != "")
						{
							if (file_exists(config('const.HOME_IMAGE_PATH') . $HomeImageValue->home_image) and !empty($HomeImageValue->home_image))
								$thumb_image = config('const.HOME_IMAGE_URL') . $HomeImageValue->home_image;
							else
								continue;

							if (file_exists(config('const.HOME_IMAGE_PATH') . $HomeImageValue->home_image_mobile) and !empty($HomeImageValue->home_image_mobile))
								$thumb_image_mobile	= config('const.HOME_IMAGE_URL') . $HomeImageValue->home_image_mobile;
							else if ($thumb_image != '')
								$thumb_image_mobile	= $thumb_image;
							else
								continue;
							
							$HomePageBanner_Main[] = array(
								'title'				=> $HomeImageValue->title,
								'banner_text'		=> $HomeImageValue->banner_text,
								'more_link'			=> $HomeImageValue->link,
								'thumb_image'		=> $thumb_image,
								'thumb_image_mobile' => $thumb_image_mobile,
								'image_alt_text'	=> $HomeImageValue->image_alt_text,
								'banner_position'	=> $HomeImageValue->banner_position,
								'display_position'	=> strtolower($HomeImageValue->display_position),
								'video_url'			=> $HomeImageValue->video_url,
								'video_url_mobile'	=> $HomeImageValue->video_url_mobile
							);
						}
						
						
						//echo "<pre>"; print_r($HomePageBanner_Main); exit;
					
					} 
					else if ($HomeImageValue->banner_position == "HOME_MIDDLE") {
						//echo $HomeImageValue->banner_position; //exit;
						if (file_exists(config('const.HOME_IMAGE_PATH') . $HomeImageValue->home_image) and !empty($HomeImageValue->home_image))
							$thumb_image = config('const.HOME_IMAGE_URL') . $HomeImageValue->home_image;
						else
							continue;
						
						//echo $HomeImageValue->home_image_mobile; exit;
						if (file_exists(config('const.HOME_IMAGE_PATH') . $HomeImageValue->home_image_mobile) and !empty($HomeImageValue->home_image_mobile))
							$thumb_image_mobile = config('const.HOME_IMAGE_URL') . $HomeImageValue->home_image_mobile;
						else
							continue;

						$HomePageBanner_Middle[] = array(
							'title'				=> $HomeImageValue->title,
							'more_link'			=> $HomeImageValue->link,
							'thumb_image'		=> $thumb_image,
							'thumb_image_mobile'	=> $thumb_image_mobile,
							'banner_position'	=> $HomeImageValue->banner_position,
							'banner_text'	=> $HomeImageValue->banner_text,
							'image_alt_text'	=> $HomeImageValue->image_alt_text
						);
					}
					else if ($HomeImageValue->banner_position == "HOME_MIDDLE_WHOLESALER") {
						//echo $HomeImageValue->banner_position; //exit;
						if (file_exists(config('const.HOME_IMAGE_PATH') . $HomeImageValue->home_image) and !empty($HomeImageValue->home_image))
							$thumb_image = config('const.HOME_IMAGE_URL') . $HomeImageValue->home_image;
						else
							continue;
						
						//echo $HomeImageValue->home_image_mobile; exit;
						if (file_exists(config('const.HOME_IMAGE_PATH') . $HomeImageValue->home_image_mobile) and !empty($HomeImageValue->home_image_mobile))
							$thumb_image_mobile = config('const.HOME_IMAGE_URL') . $HomeImageValue->home_image_mobile;
						else
							continue;

						$HomePageBanner_Middle_Wholesaler[] = array(
							'title'				=> $HomeImageValue->title,
							'more_link'			=> $HomeImageValue->link,
							'thumb_image'		=> $thumb_image,
							'thumb_image_mobile'	=> $thumb_image_mobile,
							'banner_position'	=> $HomeImageValue->banner_position,
							'banner_text'	=> $HomeImageValue->banner_text,
							'image_alt_text'	=> $HomeImageValue->image_alt_text
						);
					}
					
					else if ($HomeImageValue->banner_position == "HOME_BOTTOM") {
						if (file_exists(config('const.HOME_IMAGE_PATH') . $HomeImageValue->home_image) and !empty($HomeImageValue->home_image))
							$thumb_image = config('const.HOME_IMAGE_URL') . $HomeImageValue->home_image;
						else
							continue;

						$HomePageBanner_Bottom[] = array(
							'title'				=> $HomeImageValue->title,
							'more_link'			=> $HomeImageValue->link,
							'thumb_image'		=> $thumb_image,
							'banner_position'	=> $HomeImageValue->banner_position,
							'added_date'		=> date('d M y', strtotime($HomeImageValue->added_date)),
							'image_alt_text'	=> $HomeImageValue->image_alt_text
						);
					}
				}
			}
			//dd($HomePageBanner_Bottom);
			//dd($HomePageBanner_Middle_Wholesaler);
			Cache::put('homevars_HomePageBanner_Main_cache', $HomePageBanner_Main);
			Cache::put('homevars_HomePageBanner_Promotion_cache', $HomePageBanner_Middle);
			Cache::put('homevars_HomePageBanner_Wholesaler_cache', $HomePageBanner_Middle_Wholesaler);
			Cache::put('homevars_HomePageBanner_Bottom_cache', $HomePageBanner_Bottom);
		}
		//dd($HomePageBanner_Bottom);
		//dd($HomePageBanner_Middle);
		######## Main Slider Section End ########
		
		/*if (Cache::has('homevars_freeshipping_cache')) {
			$FreeInfo = Cache::get('homevars_freeshipping_cache');
		}
		else {
			$FreeInfo = HomeProducts::where('home_flag', '=', 'FREESHIPPING')->get();
			Cache::put('homevars_freeshipping_cache', $FreeInfo);
		}
		dd($FreeInfo);*/
		
		if (count($MetaInfo->toArray()) > 0) {
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		
		$this->PageData['JSFILES'] = ['slick.js','moment.min.js','home.js'];

		######## Start Deal of weeek ########
			$dealOfWeekProduct = $this->HomeDealOfWeek();
	    ######## End Deal of weeek ########
		
		######## Start new arrival ########
			if (Cache::has('homenewarrivals_cache')) 
			{
				$newArrivals = Cache::get('homenewarrivals_cache');
			} else {
				$newArrivals = $this->ProductSlider('NEW_ARRIVALS','Home');
				Cache::put('homenewarrivals_cache', $newArrivals);
			}	
			$this->PageData['newArrivals'] = $newArrivals;
	    ######## End new arrival ########
		
		######## Start seasonal specials ########
			if (Cache::has('homeseasonalSpecials_cache')) 
			{
				$seasonalSpecials = Cache::get('homeseasonalSpecials_cache');
			} else {
				$seasonalSpecials = $this->ProductSlider('SEOSONAL_SPECIALS','Home');
				Cache::put('homeseasonalSpecials_cache', $seasonalSpecials);
			}
			$this->PageData['seasonalSpecials'] = $seasonalSpecials;
		######## End seasonal specials ########

		######## Start popular brand ########
			$popularBrand = $this->HomePopularBrandList();
			
			$this->PageData['popularBrand'] = $popularBrand;
		######## End easonal specials ########
		######## Get main category list ########
		    $CAT_ADS_IMAGE_PATH = config('const.CAT_IMAGE_PATH');
			$CAT_IMAGE_URL = config('const.CAT_IMAGE_URL');
			
			//$CategoryObj = $this->MainCategoryList('HomePage');
			
			
			if (Cache::has('homeMainCategoryList_cache')) 
			{
				$CategoryObj = Cache::get('homeMainCategoryList_cache');
			} else {
				$CategoryObj = $this->MainCategoryList('HomePage');
				Cache::put('homeMainCategoryList_cache', $CategoryObj);
			}
			$CategoryArr = json_decode(json_encode($CategoryObj),true);  
			$this->PageData['Category'] = $CategoryArr;
		######## Get main category list ########
		 	
		###################### ORGANIZATION SCHEMA START ####################
		$organizationSchemaData = getOrganizationSchema($MetaInfo);
		if ($organizationSchemaData != false) {
			$this->PageData['organization_schema'] = $organizationSchemaData;
		}
		###################### ORGANIZATION SCHEMA END ####################
		//$HomePageBanner_Main = array();
		//$HomePageBanner_Bottom = array();
		$final_home = array();
		return view('home.Home', compact('HomePageBanner_Main', 'HomePageBanner_Middle', 'HomePageBanner_Middle_Wholesaler','HomePageBanner_Bottom','dealOfWeekProduct','final_home','HomePage_instagram'))->with($this->PageData);
	}

	public function home_new(Request $request)
	{
		echo "ssssss"; exit;
		$test = '';
		$this->PageData['test'] = '';
		return view('home_test', compact('test'))->with($this->PageData);
	}
	public function homePageProductData(Request $request, $section = null)
	{
	}

	public function homeLazyloadSectionAjax(Request $request)
	{
		if (request()->ajax()) {
			$section = $request->section;
			if ($section = "home_instagram_posts") {
				$instagramApp = new InstagramApp();
				return $instagramApp->getHomePagePosts();
			}
		}
	}
	public function NewArriaval(Request $request)
	{
		//$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['CSSFILES'] = ['newarriavallist.css'];
		$PageType = 'BR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		$this->PageData['JSFILES'] = ['newarriavallist.js'];		
		return view('home.NewArriaval-List')->with($this->PageData);
	}
	public function SeasonSpecial(Request $request)
	{
		//$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['CSSFILES'] = ['seasonlist.css'];
		$PageType = 'BR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		$this->PageData['JSFILES'] = ['seasonlist.js'];		
		return view('home.Season-List')->with($this->PageData);
	}
	public function Promotions(Request $request)
	{
		//$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['CSSFILES'] = ['promotions.css'];
		$PageType = 'BR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		$this->PageData['JSFILES'] = ['promotions.js'];		
		return view('home.Promotions')->with($this->PageData);
	}
	public function Dealofweek(Request $request)
	{
		//$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['CSSFILES'] = ['deallist.css'];
		$PageType = 'BR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		$this->PageData['JSFILES'] = ['deallist.js'];		
		return view('home.Deal-List')->with($this->PageData);
	}
	public function HomePopularBrandList()
	{
		if (Cache::has('homePopularBrands_cache')) {
			$brandArr = Cache::get('homePopularBrands_cache');
		} else {
			$product_limit = config('const.LIMIT_SHOW_PRODUCT_SLIDER');
			$brand_logo_limit = config('const.LIMIT_SHOW_BRAND_LOGO_HOME');
			
			
			$brandArr = Brand::select('brand_id','brand_name','brand_logo_image')->where('status','=','1')->where('display_on_home','=','Yes')->with(['product' => function($query) use($product_limit) {
				$query->select('brand_id','product_id','parent_category_id','category','product_name','sku','product_description','image_name','product_url','our_price','retail_price','on_sale','sale_price','current_stock')->where('status','=','1')->orderBy('added_datetime', 'desc');
			}])->orderBy('display_position')->take($brand_logo_limit)->get()->map(function($brand) use($product_limit){
				$brand->setRelation('product', $brand->product->take($product_limit));
				return $brand;
			})->toarray();
			
			if($brandArr && count($brandArr) > 0)
			{
				foreach ($brandArr as $brandArrKey => $brand){ 
					$brandArr[$brandArrKey]['brand_product_count'] = 0;
					$brandArr[$brandArrKey]['brand_logo_image_url'] = Get_Brand_Image_URL($brand['brand_logo_image']);
					if(!empty(count($brand['product']) > 0)){
						
						foreach($brand['product'] as $brandProductKey => $brandProduct){

							$brandArr[$brandArrKey]['product'][$brandProductKey]['image_url'] = Get_Product_Image_URL($brandProduct['image_name'],'THUMB');
							$brandArr[$brandArrKey]['product'][$brandProductKey] = $this->get_whishlist($brandArr[$brandArrKey]['product'][$brandProductKey]);
							if(empty($brandProduct['product_url'])){
								$brandArr[$brandArrKey]['product'][$brandProductKey]['product_url'] = Get_Product_URL($brandProduct['product_id'], $brandProduct['product_name'],'',$brandProduct['parent_category_id'],$brandProduct['category'],$brandProduct['sku'],'');
							}
							
							$brandArr[$brandArrKey]['product'][$brandProductKey]['price_arr'] = $this->Get_Price_Val($brandProduct);
						}
						$brandArr[$brandArrKey]['brand_product_count'] = count($brand['product']);	
					}
				}
			}
			Cache::put('homePopularBrands_cache', $brandArr);
		}
		return $brandArr;
	}
		
	public function HomeDealOfWeek()
	{
		$product_limit = config('const.LIMIT_SHOW_PRODUCT_SLIDER');
		if (Cache::has('homedealofweeks_cache')) 
		{
			return $AllUniqueProductDealOfWeeks = Cache::get('homedealofweeks_cache');
		} else {
			
		$table_prefix = env('DB_PREFIX', '');
		$DealDetails =[];
		$currentDate = getDateTimeByTimezone('Y-m-d');
		$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
		$dealofWeekEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
		$dealofWeekdiffInMinutes = diffInMinutes($currentDatetime,$dealofWeekEndDatetime);

		$DealQuery = DB::table($table_prefix.'dealofweek as dw')
						->select('dw.dealofweek_id','dw.description','dw.product_sku','dw.deal_price','p.retail_price','p.current_stock','p.our_price','p.product_name','p.image_name','p.product_id','p.product_url','p.product_description')
						->join($table_prefix.'products as p','dw.product_sku','=','p.sku')
						->join($table_prefix.'products_category as pc','p.product_id','=','pc.products_id')
						->join($table_prefix.'category as c','pc.category_id','=','c.category_id')
						->where('dw.status','=','1')
						->Where('dw.display_on_home','Yes')
						->where('dw.start_date','<=',$currentDate)->where('dw.end_date','>=',$currentDate)
						->where('dw.deal_type','=','Weekly')
						->where('p.status','=','1')
						->where('dw.deal_price','>',0)
						->groupBy('dw.product_sku');

		$DealOfWeeks = $DealQuery->orderBy('dw.display_rank')->take($product_limit)->get()->toarray();
		
		$DealOfWeeks = json_decode(json_encode($DealOfWeeks), true);
		/* $ProductDealQuery = DB::table($table_prefix.'products as p')
						->join($table_prefix.'products_category as pc','p.product_id','=','pc.products_id')
						->join($table_prefix.'category as c','pc.category_id','=','c.category_id')
						->select('p.product_description','p.retail_price','p.our_price','p.product_name','p.image_name','p.product_id','p.product_url')
						->where('p.status','=','1')
						->where('p.current_stock', '>', '0')
						->where('p.display_deal_of_week','Yes'); */

		// $ProductDealOfWeeks = $ProductDealQuery->orderBy('p.display_rank')->get()->toarray();
		// $ProductDealOfWeeks = json_decode(json_encode($ProductDealOfWeeks), true);
		$ProductDealOfWeeks = 	array();
		if(count($DealOfWeeks) > 0){
			if(count($ProductDealOfWeeks) > 0){
				$AllProductDealOfWeeks = array_merge(json_decode(json_encode($DealOfWeeks), true),json_decode(json_encode($ProductDealOfWeeks), true));
			}else{
				$AllProductDealOfWeeks = $DealOfWeeks;
			}
		}else{
			$AllProductDealOfWeeks =  $ProductDealOfWeeks;
		}

		if(count($AllProductDealOfWeeks) > 0){
			$AllUniqueProductDealOfWeeks = uniqueArray($AllProductDealOfWeeks, 'product_id');
			$AllUniqueProductDealOfWeeks = json_decode(json_encode($AllUniqueProductDealOfWeeks));
		}else{
			$AllUniqueProductDealOfWeeks = $AllProductDealOfWeeks;
		}

		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		
		if($AllUniqueProductDealOfWeeks && count($AllUniqueProductDealOfWeeks) > 0)
		{
			foreach ($AllUniqueProductDealOfWeeks as $dealofWeekKey => $DealOfWeek){ 
			
				$AllUniqueProductDealOfWeeks[$dealofWeekKey]->prod_image = Get_Product_Image_URL($DealOfWeek->image_name,'THUMB');

				if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
					$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
					$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
					$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
					$DealOfWeek->our_price = $DealOfWeek->our_price*$curencyvalue;
					$DealOfWeek->retail_price = $DealOfWeek->retail_price*$curencyvalue;
					$DealOfWeek->deal_price = $DealOfWeek->deal_price*$curencyvalue;
				}else{
					$curencySymbol = '';
				}

				//price
				if(!isset($DealOfWeek->deal_price)){
					$AllUniqueProductDealOfWeeks[$dealofWeekKey]->deal_price = $DealOfWeek->our_price;
				}
				
				if(isset($DealOfWeek->our_price) &&  !empty($DealOfWeek->our_price)){
					$AllUniqueProductDealOfWeeks[$dealofWeekKey]->our_price = Make_Price($DealOfWeek->our_price,true,false,$curencySymbol);
				}
				if(isset($DealOfWeek->retail_price) &&  !empty($DealOfWeek->retail_price)){
					$AllUniqueProductDealOfWeeks[$dealofWeekKey]->retail_price = Make_Price($DealOfWeek->retail_price,true,false,$curencySymbol);
				}
				if(isset($DealOfWeek->deal_price) && !empty($DealOfWeek->deal_price)){
					$AllUniqueProductDealOfWeeks[$dealofWeekKey]->deal_price = Make_Price($DealOfWeek->deal_price,true,false,$curencySymbol);
				}

				//description
				if(!isset($DealOfWeek->description) || empty($DealOfWeek->description)){
					$AllUniqueProductDealOfWeeks[$dealofWeekKey]->description = Str::limit(strip_tags($DealOfWeek->product_description),100);
				}
				
			}
			
		}
		Cache::put('homedealofweeks_cache', $AllUniqueProductDealOfWeeks,$dealofWeekdiffInMinutes);
			return $AllUniqueProductDealOfWeeks;
	   }				
	}
}
