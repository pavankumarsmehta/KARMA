<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthorizeFundLog;
use App\Models\Brand;
use Hash;
use DB;
use Session;
use Cache;
use App\Models\MetaInfo;
use App\Models\Category;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\productListingTrait;
//use App\Http\Controllers\Order;




class BrandController extends Controller
{
	use productListingTrait;
	use generalTrait;
	public $PageData;

	public function __construct()
	{
	}
	public function BrandPerfume(Request $request)
	{
		$category_id = '';
		$category_name = '';
		$CategoryName = $request->main_catgory;
		
		// if(!empty($CategoryName)){
		// $find_category = Category::where('url_name', $CategoryName)->first();
		// dd($find_category);
		// 	if(isset($find_category) && !empty($find_category)){
		// 		$category_id = $find_category->category_id;
		// 	}else{

		// 	}
		// } 

		if(isset($CategoryName) && !empty($CategoryName) && strtolower($CategoryName) !='all'){
			//$getAllCategory = $this->get_all_productWithCategory();
			$getAllCategory = $this->get_all_categories();
			$getAllCategory = $getAllCategory->unique('category_id')->filter(function ($categoryObj, $key) use ($CategoryName) {
				if (strtolower(title($categoryObj->category_name)) == strtolower($CategoryName) && $categoryObj->parent_id==0) {
					return true;
				}else{
					return false;
				}

		})->sortBy('category_name')
		->values()
		->toArray();
		

		$getAllCategory = json_decode(json_encode($getAllCategory), true);	
		
		// if(!$getAllCategory || count($getAllCategory) == 0)
		// 		return redirect('/');

		if(isset($getAllCategory) && !empty($getAllCategory)){
			$category_id = $getAllCategory[0]['category_id'];
			$category_name = $getAllCategory[0]['category_name'];
			$this->PageData['CategoryId']= $category_id;
			$NewFilters['brandcategories'] = [$category_id];
		}else{
			$category_id = -1;
			$category_name = title($CategoryName);
		}
		}
			

		$this->PageData['BrandsList'] = BrandsList('',$category_id,$category_name);
		$this->PageData['CSSFILES'] = ['static.css','brandlist.css'];
		$request->category_id = $category_id;

		$Bredcrum = $this->Bredcrum($request,'BrandsPage','');
		$BredcrumObj = $this->BredcrumObj($request,'BrandsPage','');
		###################### BREADCRUMBLIST SCHEMA START ####################
		// $organizationSchemaData = getOrganizationSchema($MetaInfo);
		// if ($organizationSchemaData != false) {
		// 	$this->PageData['organization_schema'] = $organizationSchemaData;
		// }
		$breadcrumbListSchemaData = getBLSchemaForProductListing($BredcrumObj);
		if ($breadcrumbListSchemaData != false) {
		
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################
		/*$PageType = 'BR';
		if (Cache::has('metainfo_pagetype_br')) {
			$MetaInfo = Cache::get('metainfo_pagetype_br');
		} else {
			$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
			Cache::put('metainfo_pagetype_br', $MetaInfo);
		}
		if($MetaInfo->count() > 0 )
		{*/
			$this->PageData['meta_title'] = "Brand Page - ". config('const.SITE_NAME');
			$this->PageData['meta_description'] = "Brand Page - ". config('const.SITE_NAME');
			$this->PageData['meta_keywords'] = "Brand Page - ". config('const.SITE_NAME');
		/*}*/
		
		$this->PageData['JSFILES'] = ['brandlist.js'];	
		return view('brand.brandperfume')->with($this->PageData);		
	}
	public function BrandListing(Request $request)
	{
		//$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['CSSFILES'] = ['brandlist.css'];
		$PageType = 'BR';
		if (Cache::has('metainfo_pagetype_br')) {
			$MetaInfo = Cache::get('metainfo_pagetype_br');
		} else {
			$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
			Cache::put('metainfo_pagetype_br', $MetaInfo);
		}
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		$this->PageData['JSFILES'] = ['brandlist.js'];		
		return view('brand.brand_Listing')->with($this->PageData);		
	}	
	public function BrandProductListing(Request $request)
	{ 
		try {  
			$BrandID = $request->brand_id;
			$category_url = $request->category_name;

			if(isset($category_url) && !empty($category_url)){
				$getAllCategory = $this->get_all_productWithCategory();
				$getAllCategory = $getAllCategory->unique('category_id')->filter(function ($categoryObj, $key) use ($category_url) {
					if (strtolower($categoryObj->url_name) == strtolower($category_url) && $categoryObj->parent_id==0) {
						return true;
					}else{
						return false;
					}

			})->sortBy('category_name')
			->values()
			->toArray();

			$getAllCategory = json_decode(json_encode($getAllCategory), true);	
			// if(!$getAllCategory || count($getAllCategory) == 0)
			// 		return redirect('/');

			}

			//$BrandData = Brand::where('status','=','1')->where('brand_id','=',$BrandID)->get();
			$brandArr = $this->get_all_brands();
			if(empty($brandArr[$BrandID]))
				return redirect('/');
			$BrandData[0] = json_decode(json_encode($brandArr[$BrandID]));

				if(!$BrandData || count($BrandData) == 0)
					return redirect('/');

			/* $PAGE_LIMIT = '48';
			if(config('const.PRODUCT_LISTING_PAGE_LIMIT') > 0 )
			{
			$PAGE_LIMIT = config('const.PRODUCT_LISTING_PAGE_LIMIT');
			}*/
			$PAGE_LIMIT = $this->product_listing_page_limit();

			$table_prefix = env('DB_PREFIX', '');
			
			$this->PageData['CSSFILES'] = ['listing.css'];
			
			
			$PageType = 'BR';
			if (Cache::has('metainfo_pagetype_br')) {
				$MetaInfo = Cache::get('metainfo_pagetype_br');
			} else {
				$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
				Cache::put('metainfo_pagetype_br', $MetaInfo);
			}
			
			if($MetaInfo->count() > 0 )
			{
				$this->PageData['meta_title'] = str_replace('{$brand_name}',ucfirst($BrandData[0]->brand_name),$MetaInfo[0]->meta_title);
				$this->PageData['meta_description'] = str_replace('{$brand_description}',strip_tags(html_entity_decode($BrandData[0]->brand_description)),$MetaInfo[0]->meta_description); 
				$this->PageData['meta_keywords'] = str_replace('{$brand_keyword}',strip_tags(html_entity_decode($BrandData[0]->brand_description)),$MetaInfo[0]->meta_keywords); 
			}
			if ($BrandData[0]->meta_title != '')
					$this->PageData['meta_title'] = $BrandData[0]->meta_title;
			if ($BrandData[0]->meta_description != '')
					$this->PageData['meta_description'] = $BrandData[0]->meta_description;
				if ($BrandData[0]->meta_keywords != '')
					$this->PageData['meta_keywords'] = $BrandData[0]->meta_keywords;
					

			$SetFilters = $this->SetFilters($request);
			$this->PageData['SelBrand'] = '';
			if(isset($SetFilters['brands']) && count($SetFilters['brands']) > 0){
				$this->PageData['SelBrand'] = $SetFilters['brands'][0];	
			}
			
			$this->PageData['SelSize'] = '';
			if(isset($SetFilters['size']) && count($SetFilters['size']) > 0){
				$this->PageData['SelSize'] = implode(",",$SetFilters['size']);
			}

			$this->PageData['SelCat'] = '';
			$this->PageData['SelectedCat'] = [];
			$ProdCat='';

			if(isset($SetFilters['categories']) && count($SetFilters['categories']) > 0){
				$ProdCat = $SetFilters['categories'];
				$this->PageData['SelectedCat'] = $ProdCat;
				$this->PageData['SelCat'] = implode(',',$ProdCat);
			}

			// new Filters 
			$flagWithouFilterOnLoad = false;
			if(isset($request['product_type']) && $request['product_type'] != ""){
				$product_type = explode(",", $request['product_type']);
				$flagWithouFilterOnLoad = true;	
			}else{
				$product_type = [];
			}

			if(isset($request['bid']) && $request['bid'] != ""){
				$bid = explode(",", $request['bid']);	
				$flagWithouFilterOnLoad = true;
			}else{
				$bid = [];
			}

			if(isset($request['gender']) && $request['gender'] != ""){
				$gender = explode(",", $request['gender']);	
				$flagWithouFilterOnLoad = true;
			}else{
				$gender = [];
			}

			if(isset($request['size']) && $request['size'] != ""){
				$size = explode(",", $request['size']);	
				$flagWithouFilterOnLoad = true;
			}else{
				$size = [];
			}

			if(isset($request['price']) && $request['price'] != ""){
				$price = explode(",", $request['price']);	
				$flagWithouFilterOnLoad = true;
			}else{
				$price = [];
			}
			if(isset($request['sortby']) && $request['sortby'] != ""){
				$sortby = $request['sortby'];
				$flagWithouFilterOnLoad = true;	
			}else{
				$sortby = [];
			}

			if(isset($request['page']) && $request['page'] != ""){
				$page = $request['page'];
				$flagWithouFilterOnLoad = true;	
			}else{
				$page = '1';
			}

			if(isset($request['itemperpage']) && $request['itemperpage'] != ""){
				$PAGE_LIMIT = $request['itemperpage'];	
				$flagWithouFilterOnLoad = true;
			}else{
				//$PAGE_LIMIT = '48';
			}
			$NewFilters['product_type'] = $product_type;
			$NewFilters['brands'] = [$BrandID];
			$category_id = '';
			if(isset($getAllCategory) && !empty($getAllCategory)){
				$category_id = $getAllCategory[0]['category_id'];
				$this->PageData['CategoryId']= $category_id;
				$NewFilters['brandcategories'] = [$category_id];
				$request->category_id = $category_id;
			}
			$NewFilters['gender'] = $gender;
			$NewFilters['size'] = $size;
			$NewFilters['price'] = $price;
			$NewFilters['page'] = $page;
			$NewFilters['sortby'] = $sortby;


			
			$this->PageData['SelCat'] = '';
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$brandEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$branddiffInMinutes = diffInMinutes($currentDatetime,$brandEndDatetime);
			$cachewithoutParam = '';
			if($flagWithouFilterOnLoad){
				$cachewithoutParam = 'withoutparam_';
			}
			if(!empty($category_id)){
				$category_idcache = $category_id.'_'; 
			}else{
				$category_idcache = '';
			}
			if (Cache::has('listingbrand_'.$cachewithoutParam.$category_idcache.$BrandID.'_cache')) 
			{
				$ProductsDetails = Cache::get('listingbrand_'.$cachewithoutParam.$category_idcache.$BrandID.'_cache');
			} else {
				$ProductsDetails = $this->GetProducts('BrandsList','',$PAGE_LIMIT,$NewFilters, $type = 'on_load');
				Cache::put('listingbrand_'.$cachewithoutParam.$category_idcache.$BrandID.'_cache', $ProductsDetails,$branddiffInMinutes);
			}	
			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$CatArray =  $ProductsDetails['Categories'];

			if($flagWithouFilterOnLoad){
				if(Cache::has('listingbrand_'.$BrandID.'_cache')){
					$ProductsDetails1 = Cache::get('listingbrand_'.$BrandID.'_cache');
				
					foreach($ProductsDetails['LeftFilters'] as $filterArr => $filtervalue){
						foreach($filtervalue as $subfilterArr => $subfiltervalue){
							$ProductsDetails1['LeftFilters'][$filterArr][$subfilterArr]['Selected'] = $subfiltervalue['Selected'];
						}	
					}
					//dd($ProductsDetails);
				}else{
					$ProductsDetails1 = $ProductsDetails;
				}
			 }else{
				$ProductsDetails1 = $ProductsDetails;
			 }
	
			// dd($ProductsDetails1['LeftFilters']);
			$this->PageData['Products'] = json_decode(json_encode($Products),true);
			$this->PageData['TotalProducts'] = $TotalProducts;
			$this->PageData['Categories'] = $CatArray;
			$this->PageData['Filters'] = $ProductsDetails1['LeftFilters'];
			$this->PageData['flagWithouFilterOnLoad'] = $flagWithouFilterOnLoad;	

			
			$BrandList = [];
			$SizeList = [];
			$GenderList = [];

			if(count($ProductsDetails['LeftFilters']) > 0)
			{
				foreach($ProductsDetails['LeftFilters'] as $DealFilter)
				{
					if(array_key_exists('Categories',$DealFilter)){
						$BrandList = $DealFilter['Categories']['Data'];
					}
					if(array_key_exists('Size',$DealFilter)){
						$SizeList = $DealFilter['Size']['Data'];
					}
					if(array_key_exists('Gender',$DealFilter)){
						$GenderList = $DealFilter['Gender']['Data'];
					}
				}
			}

			
			// $this->PageData['DealofWeekCount'] = count($AllUniqueProductDealOfWeeks);
			// $this->PageData['DealofWeekProducts'] = $AllUniqueProductDealOfWeeks;
			$Bredcrum = $this->Bredcrum($request,'BrandsList',$BrandData[0]->brand_name);
			$BredcrumObj = $this->BredcrumObj($request,'BrandsList',$BrandData[0]->brand_name);
			###################### BREADCRUMBLIST SCHEMA START ####################
			// $organizationSchemaData = getOrganizationSchema($MetaInfo);
			// if ($organizationSchemaData != false) {
			// 	$this->PageData['organization_schema'] = $organizationSchemaData;
			// }
		
			$breadcrumbListSchemaData = getBLSchemaForProductListing($BredcrumObj);
			if ($breadcrumbListSchemaData != false) {
				$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
			}
			###################### BREADCRUMBLIST SCHEMA END ####################

			if(($PAGE_LIMIT * $page) >= $TotalProducts) {
				$NEW_PAGE_LIMIT = $TotalProducts;
			}else{
				$NEW_PAGE_LIMIT = $PAGE_LIMIT;
			}

			$this->PageData['Bredcrum'] = $Bredcrum['BredLink'];
			$this->PageData['PageTitle'] = $Bredcrum['PageTitle'];
			$this->PageData['PageDescription'] = strip_tags(html_entity_decode($BrandData[0]->brand_description));
			$this->PageData['ProductListingType']= 'BrandsList';
			$this->PageData['Pagelimit']=$PAGE_LIMIT;
			$this->PageData['page']=$page;
			$this->PageData['NEW_PAGE_LIMIT']=$NEW_PAGE_LIMIT;
			$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
			return view('product.listing')->with($this->PageData);
		} catch(\Exception $e){
			// report($e);
			// return abort(404);
			return redirect('/');		
		}		
	}	
	
}