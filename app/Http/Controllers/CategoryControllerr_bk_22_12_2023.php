<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use App\Models\MetaInfo;
use Cache;
use DB;
use Session;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\productListingTrait;

class CategoryController extends Controller
{
	use generalTrait;
	use productListingTrait;
	public function __construct()
	{
		$this->prefix = config('const.DB_TABLE_PREFIX');
		$PageType = 'NR';

		if (Cache::has('metainfo_pagetype_nr')) {
			$MetaInfo = Cache::get('metainfo_pagetype_nr');
		} else {
			$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();
			Cache::put('metainfo_pagetype_nr', $MetaInfo);
		}

		if ($MetaInfo->count() > 0) {
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
	}

	public function index(Request $request){
	 try {  
		$CurrentRoute = $request->category_name;
		$categoriesArr = $this->get_all_categories();
		$categoryArr =  $categoriesArr->filter(function ($category, $key) use($CurrentRoute) {	

			if(strtolower($category->url_name) == strtolower($CurrentRoute) && $category->parent_id==0){
				//dd($category->url_name);
				return  true;
			}else{
				
				if(strtolower(title($category->category_name)) == strtolower($CurrentRoute) && $category->parent_id==0){
					return  true;
				}else{
					return  false;
				}
			}
			
		})->toArray();
		//dd($categoryArr);
		if(empty($categoryArr)){
			return abort(404);
		}
		$categoryArr = json_decode(json_encode($categoryArr),true);
		$Categoryobj=array_values($categoryArr)[0];
		$request->category_id=$Categoryobj['category_id'];
        $CategoryId=$Categoryobj['category_id'];
        $category_name=$Categoryobj['category_name'];
        
        $getcategory_key = array_search($CategoryId, array_column($categoriesArr->toArray(), 'category_id'));
        $CategoryData[0] = json_decode(json_encode($categoryArr[$getcategory_key]));
        //Meta information category wise
        $PageType = 'CT';
			if (Cache::has('metainfo_pagetype_ct')) {
				$MetaInfo = Cache::get('metainfo_pagetype_ct');
			} else {
				$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
				Cache::put('metainfo_pagetype_ct', $MetaInfo);
		 	}
        if($MetaInfo->count() > 0 )
			{
				$found_key = array_search($CategoryId, array_column($categoriesArr->toArray(), 'category_id'));
				$this->PageData['meta_title'] = str_replace('{$category_name}',$categoriesArr[$found_key]->category_name,$MetaInfo[0]->meta_title);
				$this->PageData['meta_description'] = str_replace('{$category_description}',$categoriesArr[$found_key]->category_description,$MetaInfo[0]->meta_description);
				$this->PageData['meta_keywords'] = str_replace('{$category_description}',$categoriesArr[$found_key]->category_description,$MetaInfo[0]->meta_keywords);
			}
			if ($CategoryData[0]->meta_title != '')
					$this->PageData['meta_title'] = $CategoryData[0]->meta_title;
			if ($CategoryData[0]->meta_description != '')
					$this->PageData['meta_description'] = $CategoryData[0]->meta_description;
			if ($CategoryData[0]->meta_keywords != '')
					$this->PageData['meta_keywords'] = $CategoryData[0]->meta_keywords;
				

		$this->PageData['CSSFILES'] = ['slick.css','category.css'];
		$this->PageData['JSFILES'] = ['slick.js','category.js'];	
		

		if($Categoryobj['template_page']=='product_list'){
		    
			$CAT_ADS_IMAGE_PATH = config('const.CAT_ADS_IMAGE_PATH');
			$CAT_ADS_IMAGE_URL = config('const.CAT_ADS_IMAGE_URL');
			$categoryArr = $this->get_all_categories();
			$advertisementCategoryArr =  $categoryArr->filter(function ($category, $key) use($CategoryId, $CAT_ADS_IMAGE_PATH,$CAT_ADS_IMAGE_URL) {

				if(((isset($category->advertisement_image1) && !empty($category->advertisement_image1)) || (isset($category->advertisement_image2) && !empty($category->advertisement_image2))) && $category->category_id == $CategoryId &&  (file_exists($CAT_ADS_IMAGE_PATH.$category->advertisement_image1) || file_exists($CAT_ADS_IMAGE_PATH.$category->advertisement_image2))){

					if(file_exists($CAT_ADS_IMAGE_PATH.$category->advertisement_image1)){
						$category->advertisement_image1 = $CAT_ADS_IMAGE_URL.$category->advertisement_image1;
					}
					if(file_exists($CAT_ADS_IMAGE_PATH.$category->advertisement_image2)){
						$category->advertisement_image2 = $CAT_ADS_IMAGE_URL.$category->advertisement_image2;
					}

					return $category;
				}else{
					return false;
				}
			})->toArray();
			$advertisementCategoryArr = json_decode(json_encode($advertisementCategoryArr),true);
			$CategoryData[0] = json_decode(json_encode($categoryArr[$CategoryId]));
			
				if(!$CategoryData || count($CategoryData) == 0)
					return redirect('/');

			$PAGE_LIMIT = $this->product_listing_page_limit();
			$table_prefix = env('DB_PREFIX', '');
			
			$this->PageData['CSSFILES'] = ['listing.css'];

			
			
		    $SetFilters = $this->SetFilters($request);
		
		$ProdCat='';

		if(isset($SetFilters['categories']) && count($SetFilters['categories']) > 0){
			$ProdCat = $SetFilters['categories'];
			$this->PageData['SelectedCat'] = $ProdCat;
			$this->PageData['SelCat'] = implode(',',$ProdCat);
		}
		
		$this->PageData['SelCat'] = '';

		if (Cache::has('listingcategory_'.$CategoryId.'_cache')) 
		 {
			$ProductsDetails = Cache::get('listingcategory_'.$CategoryId.'_cache');
		 } else {
		 	$ProductsDetails = $this->GetProducts('CategoryList','',$PAGE_LIMIT,$SetFilters);
		 	Cache::put('listingcategory_'.$CategoryId.'_cache', $ProductsDetails);
		 }	
		$Products = $ProductsDetails['Products'];
		$TotalProducts = $ProductsDetails['TotalProducts'];
		$CatArray =  $ProductsDetails['Categories'];
		$productArr = json_decode(json_encode($Products),true);
		//dd($productArr);
		$this->PageData['Products'] = $productArr;
		$this->PageData['TotalProducts'] = $TotalProducts;
		$this->PageData['Categories'] = $CatArray;
		$this->PageData['Filters'] = $ProductsDetails['LeftFilters'];	


		$Bredcrum = $this->Bredcrum($request);
		$BredcrumObj = $this->BredcrumObj($request);
		###################### BREADCRUMBLIST SCHEMA START ####################
		$organizationSchemaData = getOrganizationSchema($MetaInfo);
		if ($organizationSchemaData != false) {
			$this->PageData['organization_schema'] = $organizationSchemaData;
		}
	
		$breadcrumbListSchemaData = getBLSchemaForProductListing($BredcrumObj);
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################
		//dd($Bredcrum['BredLink']);
		$this->PageData['Bredcrum'] = $Bredcrum['BredLink'];
		$this->PageData['PageTitle'] = $Bredcrum['PageTitle'];
		//$this->PageData['Breadcrumb'] = ucwords($breadcrumb);
		//$this->PageData['PageTitle'] = ucwords($request->sub_childcategory);
		
		$this->PageData['PageDescription'] = strip_tags(html_entity_decode($CategoryData[0]->category_description));
		$this->PageData['CategoryId']= $CategoryId;
		$this->PageData['ProductListingType']= 'CategoryList';
		$this->PageData['Pagelimit']=$PAGE_LIMIT;
		
		$this->PageData['advertisementCategoryArr']=(isset($advertisementCategoryArr) && !empty($advertisementCategoryArr)) ? array_values($advertisementCategoryArr) : [] ;
		$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];

		return view('product.listing')->with($this->PageData);	
		
		}else if($Categoryobj['template_page']=='category_list'){

			 $product_limit = config('const.LIMIT_SHOW_PRODUCT_SLIDER') ;
			 $SetFilters = $this->SetFilters($request);
			 //$ProductsObj = $this->GetProducts('CategoryPage','',$product_limit,$SetFilters);
			
			//best seller
			 if (Cache::has('categorylanding_'.$CategoryId.'_cache')) 
			 {
				$ProductsObj = Cache::get('categorylanding_'.$CategoryId.'_cache');
			 } else {
			 	$ProductsObj = $this->GetProducts('CategoryPage','',$product_limit,$SetFilters);
			 	Cache::put('categorylanding_'.$CategoryId.'_cache', $ProductsObj);
			 }
			 
			
			$productArr = json_decode(json_encode($ProductsObj),true);
			$Products = $productArr['Products'];
			
			//Get main category list
			$CAT_ADS_IMAGE_PATH = config('const.CAT_IMAGE_PATH');
			$CAT_IMAGE_URL = config('const.CAT_IMAGE_URL');
			
			
			
			if (Cache::has('categorylanding_categorylist_cache')) 
			{
			   $CategoryObj = Cache::get('categorylanding_categorylist_cache');
			} else {
				$CategoryObj = $this->MainCategoryList('CategoryPage');
				Cache::put('categorylanding_categorylist_cache', $CategoryObj);
			}
			if(isset($CategoryObj) && !empty($CategoryObj)){
				$CategoryArr = json_decode(json_encode($CategoryObj),true);
			}else{
				$CategoryArr = array();
			}
			$this->PageData['Category'] = $CategoryArr;

			//popular brand
			$popularBrand = $this->CategoryPopularBrandList();
			$categoryBanner = $this->CategoryBanner($CategoryId);
			$CategoryPromotionBanner = $this->CategoryPromotionBanner($CategoryId);
			$CategoryBeauty = $this->CategoryBeauty($CategoryId);
			$this->PageData['categoryBanner'] = $categoryBanner;
			$this->PageData['popularBrand'] = $popularBrand;
			$this->PageData['categoryPromotion'] = $CategoryPromotionBanner;
			$this->PageData['categoryBeauty'] = $CategoryBeauty;
			$this->PageData['Products'] = $Products;
			$this->PageData['Category_detail'] = $CategoryData[0];
			if($MetaInfo->count() > 0 )
			{
				$MetaInfo[0]->meta_title = str_replace('{$category_name}',$categoriesArr[$found_key]->category_name,$MetaInfo[0]->meta_title);
				$MetaInfo[0]->meta_description = str_replace('{$category_description}',$categoriesArr[$found_key]->category_description,$MetaInfo[0]->meta_description);
				$MetaInfo[0]->meta_keywords = str_replace('{$category_description}',$categoriesArr[$found_key]->category_description,$MetaInfo[0]->meta_keywords);
			}
			$organizationSchemaData = getOrganizationSchema($MetaInfo);
			if ($organizationSchemaData != false) {
				$this->PageData['organization_schema'] = $organizationSchemaData;
			}
			###################### ORGANIZATION SCHEMA END ####################
			return view('category.index')->with($this->PageData);
		}
	} catch(\Exception $e){
			report($e);
			return abort(404);		
		}
		
	}
	public function CategoryPopularBrandList($limit=15)
	{
		$brand_logo_limit = config('const.LIMIT_SHOW_BRAND_LOGO_HOME');

		if (Cache::has('categoryPopularBrands_cache')) {
			$brandArr = Cache::get('categoryPopularBrands_cache');
		} else {
			$getAllBrandArr = array_values($this->get_all_brands());
			$brandArr = array_filter($getAllBrandArr, function ($brand) {
				return (($brand['display_on_category'] == 'Yes') && !empty($brand['brand_logo_image']));
			});
			if($brandArr && count($brandArr) > 0)
			{
				foreach ($brandArr as $brandArrKey => $brand){ 
					$brandArr[$brandArrKey]['brand_logo_image_url'] = Get_Brand_Image_URL($brand['brand_logo_image']);
					$brandArr[$brandArrKey]['brand_url'] = config('const.SITE_URL').'/brand/'.title($brand['brand_name']).'/brid/'.$brand['brand_id']; 
				}
			}
			Cache::put('categoryPopularBrands_cache', $brandArr);
		}
		if(isset($brandArr) && !empty($brandArr)){ 
			$brandArr = array_sort($brandArr, 'display_position', SORT_ASC);
			$brandArr1 = array_slice($brandArr,0,$limit);
		}else{
			$brandArr1 = array();
		}
		return $brandArr1;
	}
	public function CategoryBanner($category_id, $limit=1){
		if (Cache::has('categoryBanner_'.$category_id.'_cache')) {
			return $categoryBanner = Cache::get('categoryBanner_'.$category_id.'_cache');
		} else {
			$CAT_BANNER_PATH = config('const.CAT_BANNER_PATH');
			$CAT_BANNER_URL = config('const.CAT_BANNER_URL');

			$categoryArr = $this->get_all_categories();
			$getAllCategoryBannerArr =  $categoryArr->filter(function ($category, $key) use($category_id, $CAT_BANNER_PATH,$CAT_BANNER_URL) {

				if((isset($category->banner_image) && !empty($category->banner_image)) && $category->category_id == $category_id){

					if(file_exists($CAT_BANNER_PATH.$category->banner_image)){
						$category->banner_image = $CAT_BANNER_URL.$category->banner_image;
					}else{
						return false;
					}
					if (trim(strip_tags($category->category_description))) {
					  $category->category_description = strip_tags($category->category_description);

					}
					return $category;
				}else{
					return false;
				}
			})->toArray();
			if(isset($getAllCategoryBannerArr) && !empty($getAllCategoryBannerArr)){
				$getAllCategoryBannerArr = json_decode(json_encode($getAllCategoryBannerArr),true);
				$getAllCategoryBannerArr = array_sort($getAllCategoryBannerArr, 'display_position', SORT_ASC);
				$getAllCategoryBannerArr = array_slice($getAllCategoryBannerArr,0,$limit);
				Cache::put('categoryBanner_'.$category_id.'_cache', $getAllCategoryBannerArr);
			}else{
				$getAllCategoryBannerArr = array();
			}
			return $getAllCategoryBannerArr;
		}
	}
	public function CategoryPromotionBanner($category_id,$limit=1){
		if (Cache::has('CategoryPromotionBanner_'.$category_id.'_cache')) {
			return $categoryBanner = Cache::get('CategoryPromotionBanner_'.$category_id.'_cache');
		} else {
			$CAT_PROMOTION_PATH = config('const.CAT_PROMOTION_PATH');
			$CAT_PROMOTION_URL = config('const.CAT_PROMOTION_URL');

			$categoryArr = $this->get_all_categories();
			$getAllCategPromotionArr =  $categoryArr->filter(function ($category, $key) use($category_id, $CAT_PROMOTION_PATH,$CAT_PROMOTION_URL) {

				if((isset($category->promotion_banner_image) && !empty($category->promotion_banner_image)) && (isset($category->promotion_title) && !empty($category->promotion_title)) && $category->category_id == $category_id){

					if(file_exists($CAT_PROMOTION_PATH.$category->promotion_banner_image)){
						$category->promotion_banner_image = $CAT_PROMOTION_URL.$category->promotion_banner_image;
					}
					return $category;
				}else{
					return false;
				}
			})->toArray();

			if(isset($getAllCategPromotionArr) && !empty($getAllCategPromotionArr)){
				$getAllCategPromotionArr = json_decode(json_encode($getAllCategPromotionArr),true);
				$getAllCategPromotionArr = array_values(array_sort($getAllCategPromotionArr, 'display_position', SORT_ASC));
				$getAllCategPromotionArr = array_slice($getAllCategPromotionArr,0,$limit);
				Cache::put('CategoryPromotionBanner_'.$category_id.'_cache', $getAllCategPromotionArr);
			}else{
				$getAllCategPromotionArr = array();
			}
			return $getAllCategPromotionArr;
		}
	}
	public function CategoryBeauty($category_id){
		if (Cache::has('CategoryBeauty_'.$category_id.'_cache')) {
			return $categoryBanner = Cache::get('CategoryBeauty_'.$category_id.'_cache');
		} else {
			$CAT_BEAUTY_IMAGE_PATH = config('const.CAT_BEAUTY_IMAGE_PATH');
			$CAT_BEAUTY_IMAGE_URL = config('const.CAT_BEAUTY_IMAGE_URL');

			$categoryArr = $this->get_all_categories();
			$getAllCategBannerArr =  $categoryArr->filter(function ($category, $key) use($category_id, $CAT_BEAUTY_IMAGE_PATH,$CAT_BEAUTY_IMAGE_URL) {

				if((isset($category->category_beauty_json) && !empty($category->category_beauty_json)) && $category->category_id == $category_id){
					$categorybeautyArr = json_decode($category->category_beauty_json,true);
					if(isset($categorybeautyArr) && !empty($categorybeautyArr)){
						$category->category_beauty_json = $categorybeautyArr;
						$flagCategoryShow = 0; 
						foreach($categorybeautyArr as $categorybeautyArrKey => $categorybeautyObj){
							if($categorybeautyObj['category_status'] == 1){
								$flagCategoryShow = 1;
							}
							if(file_exists($CAT_BEAUTY_IMAGE_PATH.$categorybeautyObj['category_beauty_image'])){
								$categorybeautyObj['category_beauty_image'] = $CAT_BEAUTY_IMAGE_URL.$categorybeautyObj['category_beauty_image'];
							}
							$category->category_beauty_json[$categorybeautyArrKey] = $categorybeautyObj;

						}
						
					}
					$category->category_beauty_json = array_sort($category->category_beauty_json, 'display_position', SORT_ASC);
					if($flagCategoryShow){
						return $category;
					}else{
						return false;
					}
				}else{
					return false;
				}
			})->toArray();
			if(isset($getAllCategBannerArr) && !empty($getAllCategBannerArr)){
				$getAllCategBannerArr = json_decode(json_encode($getAllCategBannerArr),true);
				$getAllCategBannerArr = array_values(array_sort($getAllCategBannerArr, 'display_position', SORT_ASC));
				//$getAllCategBannerArr = array_slice($getAllCategBannerArr,0,$limit);
				Cache::put('CategoryBeauty_'.$category_id.'_cache', $getAllCategBannerArr);
			}else{
				$getAllCategBannerArr = array();
			}
			return $getAllCategBannerArr;
		}
	}
}