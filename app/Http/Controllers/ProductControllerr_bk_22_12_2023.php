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
use App\Http\Controllers\Traits\PaginationTrait;
use App\Http\Controllers\Traits\productListingTrait;
use App\Models\Category;

class ProductController extends Controller
{
	use generalTrait;
	use PaginationTrait;
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
	public function Dealofweek(Request $request)
	{
	  try { 
			$PAGE_LIMIT = $this->product_listing_page_limit();
			$table_prefix = env('DB_PREFIX', '');
			$DealDetails =[];
			$currentDate = getDateTimeByTimezone('Y-m-d');
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$dealofWeekEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$dealofWeekdiffInMinutes = diffInMinutes($currentDatetime,$dealofWeekEndDatetime);
			//$this->PageData['BrandsList'] = BrandsList();
			$this->PageData['CSSFILES'] = ['listing.css'];
			
			$this->PageData['meta_title'] = "Dealofweek Page - ". config('const.SITE_NAME');
			$this->PageData['meta_description'] = "Dealofweek Page - ". config('const.SITE_NAME');
			$this->PageData['meta_keywords'] = "Dealofweek Page - ". config('const.SITE_NAME');
			$MetaInfo[0] = (object)[];
			$MetaInfo[0]->meta_title = 	"Dealofweek Page - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_description = 	"Dealofweek Page - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_keywords = 	"Dealofweek Page - ". config('const.SITE_NAME');

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
			
			$this->PageData['SelCat'] = '';
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$dealofWeekEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$dealofWeekdiffInMinutes = diffInMinutes($currentDatetime,$dealofWeekEndDatetime);
			
			if (Cache::has('listingdealofweek_cache')) 
			{
				$ProductsDetails = Cache::get('listingdealofweek_cache');
			} else {
				$ProductsDetails = $this->GetProducts('DealofweekPage','',$PAGE_LIMIT,$SetFilters);
				Cache::put('listingdealofweek_cache', $ProductsDetails,$dealofWeekdiffInMinutes);
			}	
			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$CatArray =  $ProductsDetails['Categories'];
			$this->PageData['Products'] = json_decode(json_encode($Products),true);
			$this->PageData['TotalProducts'] = $TotalProducts;
			$this->PageData['Categories'] = $CatArray;
			$this->PageData['Filters'] = $ProductsDetails['LeftFilters'];	
			$this->PageData['DealofWeekCount'] = 0; 
			$this->PageData['DealofWeekProducts'] = array();
			$Bredcrum = $this->Bredcrum($request,'DealofweekPage');
			$BredcrumObj = $this->BredcrumObj($request,'DealofweekPage');

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
			$this->PageData['Bredcrum'] = $Bredcrum['BredLink'];
			$this->PageData['PageTitle'] = $Bredcrum['PageTitle'];
			$this->PageData['ProductListingType']= 'DealofweekPage';
			$this->PageData['Pagelimit']=$PAGE_LIMIT;
			$this->PageData['page']=1;
			$this->PageData['NEW_PAGE_LIMIT']=72;
			$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
			return view('product.listing')->with($this->PageData);
		} catch(\Exception $e){
			report($e);
			return abort(404);		
		} 	
	}
	public function NewArriaval(Request $request)
	{
	 	try { 
			$PAGE_LIMIT = $this->product_listing_page_limit();
			$table_prefix = env('DB_PREFIX', '');
			$DealDetails =[];
			$currentDate = getDateTimeByTimezone('Y-m-d');
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$dealofWeekEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$dealofWeekdiffInMinutes = diffInMinutes($currentDatetime,$dealofWeekEndDatetime);
			//$this->PageData['BrandsList'] = BrandsList();
			$this->PageData['CSSFILES'] = ['listing.css'];
			
			
			$this->PageData['meta_title'] = "New Arrival Page - ". config('const.SITE_NAME');
			$this->PageData['meta_description'] = "New Arrival Page - ". config('const.SITE_NAME');
			$this->PageData['meta_keywords'] = "New Arrival Page - ". config('const.SITE_NAME');
			
			$MetaInfo[0] = (object)[];
			$MetaInfo[0]->meta_title = 	"New Arrival Page - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_description = 	"New Arrival Page - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_keywords = 	"New Arrival Page - ". config('const.SITE_NAME');

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
			
			$this->PageData['SelCat'] = '';
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$newArrivalEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$newArrivaldiffInMinutes = diffInMinutes($currentDatetime,$newArrivalEndDatetime);

			if (Cache::has('listingnewarriaval_cache')) 
			{
				$ProductsDetails = Cache::get('listingnewarriaval_cache');
			} else {
				$ProductsDetails = $this->GetProducts('NewArrivalPage','',$PAGE_LIMIT,$SetFilters);
				Cache::put('listingnewarriaval_cache', $ProductsDetails,$newArrivaldiffInMinutes);
			}	
			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$CatArray =  $ProductsDetails['Categories'];
			$this->PageData['Products'] = json_decode(json_encode($Products),true);
			$this->PageData['TotalProducts'] = $TotalProducts;
			$this->PageData['Categories'] = $CatArray;
			$this->PageData['Filters'] = $ProductsDetails['LeftFilters'];	
			$this->PageData['DealofWeekCount'] = 0; 
			$Bredcrum = $this->Bredcrum($request,'NewArrivalPage');
			$BredcrumObj = $this->BredcrumObj($request,'NewArrivalPage');

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
			$this->PageData['DealofWeekProducts'] = array();
			$this->PageData['Bredcrum'] = $Bredcrum['BredLink'];
			$this->PageData['PageTitle'] = $Bredcrum['PageTitle'];
			$this->PageData['ProductListingType']= 'NewArrivalPage';
			$this->PageData['Pagelimit']=$PAGE_LIMIT;
			$this->PageData['page']=1;
			$this->PageData['NEW_PAGE_LIMIT']=72;
			$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
			return view('product.listing')->with($this->PageData);
		} catch(\Exception $e){
			report($e);
			return abort(404);		
		}	
	}
	public function SeasonSpecial(Request $request)
	{
		try { 
			$PAGE_LIMIT = $this->product_listing_page_limit();
			$table_prefix = env('DB_PREFIX', '');
			$DealDetails =[];
			$currentDate = getDateTimeByTimezone('Y-m-d');
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$dealofWeekEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$dealofWeekdiffInMinutes = diffInMinutes($currentDatetime,$dealofWeekEndDatetime);
			//$this->PageData['BrandsList'] = BrandsList();
			$this->PageData['CSSFILES'] = ['listing.css'];
			
			$this->PageData['meta_title'] = "Season special Page - ". config('const.SITE_NAME');
			$this->PageData['meta_description'] = "Season special Page - ". config('const.SITE_NAME');
			$this->PageData['meta_keywords'] = "Season special Page - ". config('const.SITE_NAME');

			$MetaInfo[0] = (object)[];
			$MetaInfo[0]->meta_title = 	"Season special Page - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_description = 	"Season special Page - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_keywords = 	"Season special Page - ". config('const.SITE_NAME');
			
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
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$seasonspecialEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$seasonspecialdiffInMinutes = diffInMinutes($currentDatetime,$seasonspecialEndDatetime);

			if (Cache::has('listingseasonspecial_cache')) 
			{
				$ProductsDetails = Cache::get('listingseasonspecial_cache');
			} else {
				$ProductsDetails = $this->GetProducts('SeosonalSpecialsPage','',$PAGE_LIMIT,$SetFilters);
				Cache::put('listingseasonspecial_cache', $ProductsDetails,$seasonspecialdiffInMinutes);
			}	
			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$CatArray =  $ProductsDetails['Categories'];
			$this->PageData['Products'] = json_decode(json_encode($Products),true);
			$this->PageData['TotalProducts'] = $TotalProducts;
			$this->PageData['Categories'] = $CatArray;
			$this->PageData['Filters'] = $ProductsDetails['LeftFilters'];	

			
			
			$this->PageData['DealofWeekCount'] = 0; 
			$this->PageData['DealofWeekProducts'] = array();

			$Bredcrum = $this->Bredcrum($request,'SeosonalSpecialsPage');
			$BredcrumObj = $this->BredcrumObj($request,'SeosonalSpecialsPage');

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
			$this->PageData['Bredcrum'] = $Bredcrum['BredLink'];
			$this->PageData['PageTitle'] = $Bredcrum['PageTitle'];
			$this->PageData['ProductListingType']= 'SeosonalSpecialsPage';
			$this->PageData['Pagelimit']=$PAGE_LIMIT;
			$this->PageData['page']=1;
			$this->PageData['NEW_PAGE_LIMIT']=72;
			$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
			return view('product.listing')->with($this->PageData);
		} catch(\Exception $e){
			report($e);
			return abort(404);		
		}	
	}
	public function ProductSale(Request $request)
	{
		try { 
			$PAGE_LIMIT = $this->product_listing_page_limit();
			$table_prefix = env('DB_PREFIX', '');
			
			$this->PageData['CSSFILES'] = ['listing.css'];
			
			$this->PageData['meta_title'] = "Sale - ". config('const.SITE_NAME');
			$this->PageData['meta_description'] = "Sale - ". config('const.SITE_NAME');
			$this->PageData['meta_keywords'] = "Sale - ". config('const.SITE_NAME');

			$MetaInfo[0] = (object)[];
			$MetaInfo[0]->meta_title = 	"Sale - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_description = 	"Sale - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_keywords = 	"Sale - ". config('const.SITE_NAME');
			
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
			
			$this->PageData['SelCat'] = '';
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$saleEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$salediffInMinutes = diffInMinutes($currentDatetime,$saleEndDatetime);

			
			if (Cache::has('listingsale_cache')) 
			{
				$ProductsDetails = Cache::get('listingsale_cache');
			} else {
				$ProductsDetails = $this->GetProducts('SalePage','',$PAGE_LIMIT,$SetFilters);
				Cache::put('listingsale_cache', $ProductsDetails,$salediffInMinutes);
			}
			//$ProductsDetails = array('Products'=> array(),'TotalProducts'=> 10,'Categories'=> array(),'LeftFilters'=> array());	

			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$CatArray =  $ProductsDetails['Categories'];
			$this->PageData['Products'] = json_decode(json_encode($Products),true);
			$this->PageData['TotalProducts'] = $TotalProducts;
			$this->PageData['Categories'] = $CatArray;
			$this->PageData['Filters'] = $ProductsDetails['LeftFilters'];	
			
			$Bredcrum = $this->Bredcrum($request,'SalePage');
			$this->PageData['Bredcrum'] = $Bredcrum['BredLink'];
			$this->PageData['PageTitle'] = $Bredcrum['PageTitle'];
			$BredcrumObj = $this->BredcrumObj($request,'SalePage');

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
			//dd($BredcrumObj);
			//$this->PageData['PageDescription'] = strip_tags(html_entity_decode($BrandData[0]->brand_description));

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

			$this->PageData['ProductListingType']= 'SalePage';
			$this->PageData['Pagelimit']=$PAGE_LIMIT;
			$this->PageData['page']=1;
			$this->PageData['NEW_PAGE_LIMIT']=72;
			$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
			return view('product.listing')->with($this->PageData);	
		} catch(\Exception $e){
			report($e);
			return abort(404);		
		}	
	}
	public function ProductBestseller(Request $request, $category_id = null)
	{  
		try {  
			$PAGE_LIMIT = $this->product_listing_page_limit();
			$table_prefix = env('DB_PREFIX', '');
			
			$this->PageData['CSSFILES'] = ['listing.css'];
			
			$this->PageData['meta_title'] = "Best seller - ". config('const.SITE_NAME');
			$this->PageData['meta_description'] = "Best seller - ". config('const.SITE_NAME');
			$this->PageData['meta_keywords'] = "Best seller - ". config('const.SITE_NAME');

			$MetaInfo[0] = (object)[];
			$MetaInfo[0]->meta_title = 	"Best seller - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_description = 	"Best seller - ". config('const.SITE_NAME');
			$MetaInfo[0]->meta_keywords = 	"Best seller - ". config('const.SITE_NAME');
			if($category_id != "" && !is_numeric($category_id)){
				$find_category = Category::where('url_name', $category_id)->first();
				if($find_category){
					$request['category_id'] = $find_category->category_id;
				}else{
					$request['category_id'] = "";
				} 
			}else{
				$find_category = Category::where('category_id', $category_id)->first();
			}
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
			
			$this->PageData['SelCat'] = '';

			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$bestSellerEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$bestSellerdiffInMinutes = diffInMinutes($currentDatetime,$bestSellerEndDatetime);

			
			// if (Cache::has('listingsale_cache')) 
			// {
			// 	$ProductsDetails = Cache::get('listingsale_cache');
			// } else {
			// 	$ProductsDetails = $this->GetProducts('BestsellerPage','',$PAGE_LIMIT,$SetFilters);
			// 	Cache::put('listingsale_cache', $ProductsDetails,$bestSellerdiffInMinutes);
			// }
			
			if (Cache::has('listingsale_'.(int)$request['category_id'].'_cache')) 
			{
				$ProductsDetails = Cache::get('listingsale_'.(int)$request['category_id'].'_cache');
			} else {
				$ProductsDetails = $this->GetProducts('BestsellerPage','',$PAGE_LIMIT,$SetFilters);
				Cache::put('listingsale_'.(int)$request['category_id'].'_cache', $ProductsDetails,$bestSellerdiffInMinutes);
			}		
			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$CatArray =  $ProductsDetails['Categories'];
			$this->PageData['Products'] = json_decode(json_encode($Products),true);
			$this->PageData['TotalProducts'] = $TotalProducts;
			$this->PageData['Categories'] = $CatArray;
			$this->PageData['Filters'] = $ProductsDetails['LeftFilters'];	
			
			$Bredcrum = $this->Bredcrum($request,'BestsellerPage');
			$BredcrumObj = $this->BredcrumObj($request,'BestsellerPage');

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
			$this->PageData['Bredcrum'] = $Bredcrum['BredLink'];
			$this->PageData['PageTitle'] = $Bredcrum['PageTitle'].' - '.$find_category->category_name;
			//$this->PageData['PageDescription'] = strip_tags(html_entity_decode($BrandData[0]->brand_description));
			$this->PageData['ProductListingType']= 'BestsellerPage';
			$this->PageData['CategoryId']= (int)$request['category_id'];
			$this->PageData['Pagelimit']=$PAGE_LIMIT;
			$this->PageData['page']=1;
			$this->PageData['NEW_PAGE_LIMIT']=72;
			$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
			return view('product.listing')->with($this->PageData);
		} catch(\Exception $e){
			report($e);
			return abort(404);		
		}			
	}
	public function GetCategory(Request $request)
	{ 
		try {  
			$CategoryId = $request->category_id;
			$categoryIdArr[] = $CategoryId;
			$CatArray = $this->GetCatTree();
			if(!isset($CatArray['CatForProd'][$CategoryId])){
				return abort(404);
			}
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
			$found_key1 = array_search($CategoryId, array_column($categoryArr->toArray(), 'category_id'));
			if(!$found_key1){
				return abort(404);
			}
			$CategoryData[0] = json_decode(json_encode($categoryArr[$found_key1]));
			//dd($CategoryData[0]);
			
				if(!$CategoryData || count($CategoryData) == 0)
					return redirect('/');

			$PAGE_LIMIT = $this->product_listing_page_limit();
			$table_prefix = env('DB_PREFIX', '');
			
			$this->PageData['CSSFILES'] = ['listing.css'];
			
			$PageType = 'CT';
			if (Cache::has('metainfo_pagetype_ct')) {
				$MetaInfo = Cache::get('metainfo_pagetype_ct');
			} else {
				$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
				Cache::put('metainfo_pagetype_ct', $MetaInfo);
			}
			
			
			
			if($MetaInfo->count() > 0 )
			{
				$found_key = array_search($CategoryId, array_column($categoryArr->toArray(), 'category_id'));
				$this->PageData['meta_title'] = str_replace('{$category_name}',ucfirst(strtolower($categoryArr[$found_key]->category_name)),$MetaInfo[0]->meta_title);
				$this->PageData['meta_description'] = str_replace('{$category_description}',$categoryArr[$found_key]->category_description,$MetaInfo[0]->meta_description);
				$this->PageData['meta_keywords'] = str_replace('{$category_description}',$categoryArr[$found_key]->category_description,$MetaInfo[0]->meta_keywords);
			}
			if ($CategoryData[0]->meta_title != '')
					$this->PageData['meta_title'] = ucfirst(strtolower($CategoryData[0]->meta_title));
			if ($CategoryData[0]->meta_description != '')
					$this->PageData['meta_description'] = $CategoryData[0]->meta_description;
			if ($CategoryData[0]->meta_keywords != '')
					$this->PageData['meta_keywords'] = $CategoryData[0]->meta_keywords;
				
			$SetFilters = $this->SetFilters($request);
			//dd($SetFilters);
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
			if(isset($request['product_type']) && $request['product_type'] != ""){
				$product_type = explode(",", $request['product_type']);	
			}else{
				$product_type = [];
			}

			if(isset($request['bid']) && $request['bid'] != ""){
				$bid = explode(",", $request['bid']);	
			}else{
				$bid = [];
			}

			if(isset($request['gender']) && $request['gender'] != ""){
				$gender = explode(",", $request['gender']);	
			}else{
				$gender = [];
			}

			if(isset($request['size']) && $request['size'] != ""){
				$size = explode(",", $request['size']);	
			}else{
				$size = [];
			}

			if(isset($request['price']) && $request['price'] != ""){
				$price = explode(",", $request['price']);	
			}else{
				$price = [];
			}

			if(isset($request['page']) && $request['page'] != ""){
				$page = $request['page'];	
			}else{
				$page = '1';
			}

			if(isset($request['itemperpage']) && $request['itemperpage'] != ""){
				$PAGE_LIMIT = $request['itemperpage'];	
			}else{
				$PAGE_LIMIT = '48';
			}
			$NewFilters['categories'] = [$CategoryId];
			$NewFilters['product_type'] = $product_type;
			$NewFilters['brands'] = $bid;
			$NewFilters['gender'] = $gender;
			$NewFilters['size'] = $size;
			$NewFilters['price'] = $price;
			$NewFilters['page'] = $page;
			
			$this->PageData['SelCat'] = '';
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$categoryEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$categorydiffInMinutes = diffInMinutes($currentDatetime,$categoryEndDatetime);

			if (Cache::has('listingcategory_'.$CategoryId.'_cache')) 
			{
				$ProductsDetails = Cache::get('listingcategory_'.$CategoryId.'_cache');
			} else {
				$ProductsDetails = $this->GetProducts('CategoryList',$CategoryId,$PAGE_LIMIT,$NewFilters, $type = 'on_load');
				//Cache::put('listingcategory_'.$CategoryId.'_cache', $ProductsDetails,$categorydiffInMinutes);
			}
			
			// $ProductsDetails = $this->GetProducts('CategoryList',$CategoryId,$PAGE_LIMIT,$SetFilters);
			//$ProductsDetails = $this->GetProducts('CategoryList','',$PAGE_LIMIT,$SetFilters);

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
			
			if(($PAGE_LIMIT * $page) >= $TotalProducts) {
				$NEW_PAGE_LIMIT = $TotalProducts;
			}else{
				$NEW_PAGE_LIMIT = $PAGE_LIMIT;
			}
			$this->PageData['PageDescription'] = strip_tags(html_entity_decode($CategoryData[0]->category_description));
			$this->PageData['CategoryId']= $request->category_id;
			$this->PageData['ProductListingType']= 'CategoryList';
			$this->PageData['Pagelimit']=$PAGE_LIMIT;
			$this->PageData['page']=$page;
			$this->PageData['NEW_PAGE_LIMIT']=$NEW_PAGE_LIMIT;
			
			$this->PageData['advertisementCategoryArr']=(isset($advertisementCategoryArr) && !empty($advertisementCategoryArr)) ? array_values($advertisementCategoryArr) : [] ;
			$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
			return view('product.listing')->with($this->PageData);	
		} catch(\Exception $e){
			report($e);
			return abort(404);		
		}
		
	}	
	
	public function getProductListingAjax(Request $request)
	{
		$CAT_ADS_IMAGE_PATH = config('const.CAT_ADS_IMAGE_PATH');
		$CAT_ADS_IMAGE_URL = config('const.CAT_ADS_IMAGE_URL');
		$Filters = json_decode($request->filters,true);
		$productListingType =  $request->product_listing_type;
		$Filters['page'] = $request->page;
		$itemperpage =  $request->itemperpage;
		if($productListingType=='CategoryList' && $request->page==1){
			$categoryArr = $this->get_all_categories();
			$CategoryId = $Filters['categories'][0];
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
		}
		$PAGE_LIMIT = $this->product_listing_page_limit($itemperpage);
		$ProductsDetails = $this->GetProducts($productListingType,'',$PAGE_LIMIT,$Filters);
	//dd($ProductsDetails);
	$Products = $ProductsDetails['Products'];
	$TotalProducts = $ProductsDetails['TotalProducts'];
	$CatArray =  $ProductsDetails['Categories'];
	$this->PageData['Products'] = json_decode(json_encode($Products),true);
	$this->PageData['TotalProducts'] = $TotalProducts;
	$this->PageData['Categories'] = $CatArray;
	$this->PageData['ProductListingType'] = $productListingType;
	$this->PageData['advertisementCategoryArr']=(isset($advertisementCategoryArr) && !empty($advertisementCategoryArr)) ? array_values($advertisementCategoryArr) : [] ;

		$ProductHTML = view('product.productlist')->with($this->PageData)->render();
		//return view('product.list')->with($this->PageData);
		return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML'=>$ProductHTML));
	}

	//Add on date 25-09-2023 start
	public function ProductListPage(Request $request)
	{
		
		$Filters = json_decode($request->filters,true);
		$Filters['page'] = $request->page;
		
		if($request->keyword != ''){
			return $this->ThirdPartyListPage($request);
		}
		else{
			$ProductsDetails = $this->GetProducts('ProductListPage',$request->category_id,24,$Filters);
			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$BredcrumHTML = $this->BredcrumAjax($request);
			$this->PageData['Products'] = $Products;
			$this->PageData['TotalProducts'] = $TotalProducts;
			$ProductHTML = view('product.listing')->with($this->PageData)->render();
			//return view('product.list')->with($this->PageData);
			return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML'=>$ProductHTML, 'BredcrumHTML' => $BredcrumHTML));
		}
	}
	public function ThirdPartyListPage(Request $request)
		{
			
			$Filters = json_decode($request->filters,true);
			$Filters['page'] = $request->page;
			
			//if($request->keyword != ''){
				
				$searchKeyword = $request->keyword;
				$searchKeyword = str_replace("andd","&",$searchKeyword);
				$searchKeyword = str_replace("backslash","/",$searchKeyword);
				$searchKeyword = str_replace(" ","-",$searchKeyword);
				
				$perPage = 24;
				$begin = $Filters['page'];
				$extraSearchQuery = '';
				
				
				if($Filters['sortby'] != ''){
					if($Filters['sortby'] == 'priceLH'){
						$extraSearchQuery .= '&sort.price=asc';
					}
					else if($Filters['sortby'] == 'priceHL'){
						$extraSearchQuery .= '&sort.price=desc';
					}
					else if($Filters['sortby'] == 'ATZ'){
						$extraSearchQuery .= '&sort.name=asc';
					}
					else if($Filters['sortby'] == 'ZTA'){
						$extraSearchQuery .= '&sort.name=desc';
					}
				}
				
				if(!empty($Filters['brands'])){
					for($b=0;$b<count($Filters['brands']);$b++){
						$selBrands = $Filters['brands'][$b];
						$selBrands = str_replace("doubledot",":",$selBrands);
						$selBrands = str_replace("dot",".",$selBrands);
						$selBrands = str_replace("andd","&",$selBrands);
						$selBrands = str_replace("dash","-",$selBrands);
						$selBrands = str_replace("singlecomma","'",$selBrands);
						$selBrands = str_replace("_"," ",$selBrands);
						
						$extraSearchQuery .= '&filter.brand='.rawurlencode($selBrands);
					}
				}
				
				if(!empty($Filters['categories'])){
					for($c=0;$c<count($Filters['categories']);$c++){
						$selCategories = $Filters['categories'][$c];
						$selCategories = str_replace("doubledot",":",$selCategories);
						$selCategories = str_replace("dot",".",$selCategories);
						$selCategories = str_replace("andd","&",$selCategories);
						$selCategories = str_replace("dash","-",$selCategories);
						$selCategories = str_replace("singlecomma","'",$selCategories);
						$selCategories = str_replace("_"," ",$selCategories);
						
						$extraSearchQuery .= '&filter.category='.rawurlencode($selCategories);
					}
				}
				
				if(!empty($Filters['size'])){
					for($s=0;$s<count($Filters['size']);$s++){
						$selSizes = $Filters['size'][$s];
						$selSizes = str_replace("doubledot",":",$selSizes);
						$selSizes = str_replace("dot",".",$selSizes);
						$selSizes = str_replace("andd","&",$selSizes);
						$selSizes = str_replace("dash","-",$selSizes);
						$selSizes = str_replace("singlecomma","'",$selSizes);
						$selSizes = str_replace("_"," ",$selSizes);
						
						$extraSearchQuery .= '&filter.size='.rawurlencode($selSizes);
					}
				}
				
				if(!empty($Filters['special'])){
					for($sp=0;$sp<count($Filters['special']);$sp++){
						$selSpecials = $Filters['special'][$sp];
						$selSpecials = str_replace("doubledot",":",$selSpecials);
						$selSpecials = str_replace("dot",".",$selSpecials);
						$selSpecials = str_replace("andd","&",$selSpecials);
						$selSpecials = str_replace("dash","-",$selSpecials);
						$selSpecials = str_replace("singlecomma","'",$selSpecials);
						$selSpecials = str_replace("_"," ",$selSpecials);
						
						$extraSearchQuery .= '&filter.badges='.rawurlencode($selSpecials);
					}
				}
				
				if(!empty($Filters['formulation'])){
					for($f=0;$f<count($Filters['formulation']);$f++){
						$selFormulations = $Filters['formulation'][$f];
						$selFormulations = str_replace("doubledot",":",$selFormulations);
						$selFormulations = str_replace("dot",".",$selFormulations);
						$selFormulations = str_replace("andd","&",$selFormulations);
						$selFormulations = str_replace("dash","-",$selFormulations);
						$selFormulations = str_replace("singlecomma","'",$selFormulations);
						$selFormulations = str_replace("_"," ",$selFormulations);
						
						$extraSearchQuery .= '&filter.formulation='.rawurlencode($selFormulations);
					}
				}
				
				if(!empty($Filters['features'])){
					for($f=0;$f<count($Filters['features']);$f++){
						$selFeatures = $Filters['features'][$f];
						$selFeatures = str_replace("doubledot",":",$selFeatures);
						$selFeatures = str_replace("dot",".",$selFeatures);
						$selFeatures = str_replace("andd","&",$selFeatures);
						$selFeatures = str_replace("dash","-",$selFeatures);
						$selFeatures = str_replace("singlecomma","'",$selFeatures);
						$selFeatures = str_replace("_"," ",$selFeatures);
						
						$extraSearchQuery .= '&filter.by_feature='.rawurlencode($selFeatures);
					}
				}
				
				if(!empty($Filters['fragrance_family'])){
					for($f=0;$f<count($Filters['fragrance_family']);$f++){
						$selFragrances = $Filters['fragrance_family'][$f];
						$selFragrances = str_replace("doubledot",":",$selFragrances);
						$selFragrances = str_replace("dot",".",$selFragrances);
						$selFragrances = str_replace("andd","&",$selFragrances);
						$selFragrances = str_replace("dash","-",$selFragrances);
						$selFragrances = str_replace("singlecomma","'",$selFragrances);
						$selFragrances = str_replace("_"," ",$selFragrances);
						
						$extraSearchQuery .= '&filter.fragrance_family='.rawurlencode($selFragrances);
					}
				}
				
				if(!empty($Filters['vtype'])){
					for($f=0;$f<count($Filters['vtype']);$f++){
						$selTypes = $Filters['vtype'][$f];
						$selTypes = str_replace("doubledot",":",$selTypes);
						$selTypes = str_replace("dot",".",$selTypes);
						$selTypes = str_replace("andd","&",$selTypes);
						$selTypes = str_replace("dash","-",$selTypes);
						$selTypes = str_replace("singlecomma","'",$selTypes);
						$selTypes = str_replace("_"," ",$selTypes);
						
						$extraSearchQuery .= '&filter.type='.rawurlencode($selTypes);
					}
				}
				
				if(!empty($Filters['coverage'])){
					for($f=0;$f<count($Filters['coverage']);$f++){
						$selCoverage = $Filters['coverage'][$f];
						$selCoverage = str_replace("doubledot",":",$selCoverage);
						$selCoverage = str_replace("dot",".",$selCoverage);
						$selCoverage = str_replace("andd","&",$selCoverage);
						$selCoverage = str_replace("dash","-",$selCoverage);
						$selCoverage = str_replace("singlecomma","'",$selCoverage);
						$selCoverage = str_replace("_"," ",$selCoverage);
						
						$extraSearchQuery .= '&filter.coverage='.rawurlencode($selCoverage);
					}
				}
				
				if(!empty($Filters['finish'])){
					for($f=0;$f<count($Filters['finish']);$f++){
						$selFinish = $Filters['finish'][$f];
						$selFinish = str_replace("doubledot",":",$selFinish);
						$selFinish = str_replace("dot",".",$selFinish);
						$selFinish = str_replace("andd","&",$selFinish);
						$selFinish = str_replace("dash","-",$selFinish);
						$selFinish = str_replace("singlecomma","'",$selFinish);
						$selFinish = str_replace("_"," ",$selFinish);
						
						$extraSearchQuery .= '&filter.finish='.rawurlencode($selFinish);
					}
				}
				
				if(!empty($Filters['skin_type'])){
					for($f=0;$f<count($Filters['skin_type']);$f++){
						$selSkinTypes = $Filters['skin_type'][$f];
						$selSkinTypes = str_replace("doubledot",":",$selSkinTypes);
						$selSkinTypes = str_replace("dot",".",$selSkinTypes);
						$selSkinTypes = str_replace("andd","&",$selSkinTypes);
						$selSkinTypes = str_replace("dash","-",$selSkinTypes);
						$selSkinTypes = str_replace("singlecomma","'",$selSkinTypes);
						$selSkinTypes = str_replace("_"," ",$selSkinTypes);
						
						$extraSearchQuery .= '&filter.skin_type='.rawurlencode($selSkinTypes);
					}
				}
				
				if(!empty($Filters['fragrance_seasons'])){
					for($f=0;$f<count($Filters['fragrance_seasons']);$f++){
						$selSeasons = $Filters['fragrance_seasons'][$f];
						$selSeasons = str_replace("doubledot",":",$selSeasons);
						$selSeasons = str_replace("dot",".",$selSeasons);
						$selSeasons = str_replace("andd","&",$selSeasons);
						$selSeasons = str_replace("dash","-",$selSeasons);
						$selSeasons = str_replace("singlecomma","'",$selSeasons);
						$selSeasons = str_replace("_"," ",$selSeasons);
						
						$extraSearchQuery .= '&filter.seasons='.rawurlencode($selSeasons);
					}
				}
				
				if(!empty($Filters['fragrance_occasion'])){
					for($f=0;$f<count($Filters['fragrance_occasion']);$f++){
						$selOccasion = $Filters['fragrance_occasion'][$f];
						$selOccasion = str_replace("doubledot",":",$selOccasion);
						$selOccasion = str_replace("dot",".",$selOccasion);
						$selOccasion = str_replace("andd","&",$selOccasion);
						$selOccasion = str_replace("dash","-",$selOccasion);
						$selOccasion = str_replace("singlecomma","'",$selOccasion);
						$selOccasion = str_replace("_"," ",$selOccasion);
						
						$extraSearchQuery .= '&filter.occasion='.rawurlencode($selOccasion);
					}
				}
				
				if(!empty($Filters['fragrance_personality'])){
					for($f=0;$f<count($Filters['fragrance_personality']);$f++){
						$selPersonality = $Filters['fragrance_personality'][$f];
						$selPersonality = str_replace("doubledot",":",$selPersonality);
						$selPersonality = str_replace("dot",".",$selPersonality);
						$selPersonality = str_replace("andd","&",$selPersonality);
						$selPersonality = str_replace("dash","-",$selPersonality);
						$selPersonality = str_replace("singlecomma","'",$selPersonality);
						$selPersonality = str_replace("_"," ",$selPersonality);
						
						$extraSearchQuery .= '&filter.personality='.rawurlencode($selPersonality);
					}
				}
				
				if($Filters['ochangeprice'] == '1' && $Filters['minprice'] != '' && $Filters['maxprice'] != ''){
					$extraSearchQuery .= '&filter.price.low='.rawurlencode($Filters['minprice']).'&filter.price.high='.rawurlencode($Filters['maxprice']);
				}
				
				$curl = new \GuzzleHttp\Client();
				$initialRequest = "https://faltym.a.searchspring.io/api/suggest/query?disableSpellCorrect=true&lang=en&pubId=faltym&query=".$searchKeyword;
				$response = $curl->request('GET', $initialRequest);
				$Value = $response->getBody()->getContents();
				$jsonArrayResponse = json_decode($Value);
				
				
				if(!empty($jsonArrayResponse->suggested->text)){
					$suggestedQry = $jsonArrayResponse->suggested->text;
				}else{
					$suggestedQry = $searchKeyword;
				}
				
				$suggestedQry1 = rawurlencode($searchKeyword);
				$suggestedQry1 = str_replace("-","+",$suggestedQry1);
				
				
				$curl1 = new \GuzzleHttp\Client();
				//$initialRequest1 = "https://api.searchspring.net/api/search/search?siteId=faltym&resultsFormat=native&resultsPerPage=".$perPage."&page=".$begin."&q=".$suggestedQry1.$extraSearchQuery;
				$initialRequest1 = "https://api.searchspring.net/api/search/search?siteId=faltym&resultsFormat=native&resultsPerPage=".$perPage."&page=".$begin."&q=".$searchKeyword.$extraSearchQuery;
				$response1 = $curl->request('GET', $initialRequest1);
				$Value1 = $response1->getBody()->getContents();
				$jsonArrayResponse1 = json_decode($Value1);
				
				
				$Products = json_decode(json_encode($jsonArrayResponse1->results));
				$TotalProducts = $jsonArrayResponse1->pagination->totalResults;
				
				
				$filtersAll = json_decode(json_encode($jsonArrayResponse1->facets), true);
				
				$TotalProducts = $jsonArrayResponse1->pagination->totalResults;
				
				$this->PageData['Products'] = $Products;
				$this->PageData['TotalProducts'] = $TotalProducts;
				
				$MinPrice = $MaxPrice = 0;
				
				$allFilters = array();
				
				$setMinPrice = $setMaxPrice = '';
				
				for($i=0;$i<count($filtersAll);$i++){
					
					$filtersAll[$i]['Attr'] =  $filtersAll[$i]['Selected'] =  $filtersAll[$i]['Data'] = array();
					
					if($filtersAll[$i]['field'] == 'price'){
						/*$this->PageData['MinPrice'] = $filtersAll[$i]['range'][0];
						$this->PageData['MaxPrice'] = $filtersAll[$i]['range'][1];*/
						$setMinPrice = $filtersAll[$i]['range'][0];
						$setMaxPrice = $filtersAll[$i]['range'][1];
					}
					else{
						$filtersAll[$i]['Attr']['title'] = $filtersAll[$i]['label'];
						$filtersAll[$i]['Attr']['id'] = $filtersAll[$i]['field'];
						$filtersAll[$i]['Attr']['filterval'] = 'key';
						//$filtersAll[$i]['Attr']['status'] = $filtersAll[$i]['active'];
						
						if($filtersAll[$i]['field'] == 'brand'){
							$filtersAll[$i]['Attr']['name'] = 'mid';
							$filtersAll[$i]['Selected'] = $Filters['brands'];
						}else if($filtersAll[$i]['field'] == 'category'){
							$filtersAll[$i]['Attr']['name'] = 'cid';
							$filtersAll[$i]['Selected'] = $Filters['categories'];
						}else if($filtersAll[$i]['field'] == 'size'){
							$filtersAll[$i]['Attr']['name'] = 'size';
							$filtersAll[$i]['Selected'] = $Filters['size'];
						}else if($filtersAll[$i]['field'] == 'badges'){
							$filtersAll[$i]['Attr']['name'] = 'special';
							$filtersAll[$i]['Selected'] = $Filters['special'];
						}else if($filtersAll[$i]['field'] == 'formulation'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['formulation'];
						}else if($filtersAll[$i]['field'] == 'by_feature'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['features'];
						}else if($filtersAll[$i]['field'] == 'fragrance_family'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['fragrance_family'];
						}else if($filtersAll[$i]['field'] == 'type'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['vtype'];
						}else if($filtersAll[$i]['field'] == 'coverage'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['coverage'];
						}else if($filtersAll[$i]['field'] == 'finish'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['finish'];
						}else if($filtersAll[$i]['field'] == 'skin_type'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['skin_type'];
						}else if($filtersAll[$i]['field'] == 'seasons'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['fragrance_seasons'];
						}else if($filtersAll[$i]['field'] == 'occasion'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['fragrance_occasion'];
						}else if($filtersAll[$i]['field'] == 'personality'){
							$filtersAll[$i]['Attr']['name'] = '';
							$filtersAll[$i]['Selected'] = $Filters['fragrance_personality'];
						}
						
						$valuesArr = $filtersAll[$i]['values'];
						
						$ItemsData = [];
						foreach ($valuesArr as $item) {
							
							if(isset($item['value']) && $item['value'] != ''){
								
								$item['value'] = str_replace(":","doubledot",$item['value']);
								$item['value'] = str_replace(".","dot",$item['value']);
								$item['value'] = str_replace("-","dash",$item['value']);
								$item['value'] = str_replace("&","andd",$item['value']);
								$item['value'] = str_replace("'","singlecomma",$item['value']);
								$item['value'] = str_replace(" ","_",$item['value']);
								
								$ItemsData[$item['value']] = $item['label'];
							}
						}
						$filtersAll[$i]['Data'] = $ItemsData;
						
						$allFilters[][$filtersAll[$i]['label']] = $filtersAll[$i];
					}
				}
				
				$this->PageData['Filters'] = $allFilters;
				//$this->PageData['OMinPrice'] = $MinPrice;
				//$this->PageData['OMaxPrice'] = $MaxPrice;
				
				$MinPrice = $Filters['minprice'];
				$MaxPrice = $Filters['maxprice'];
				$this->PageData['MinPrice'] = $MinPrice;
				$this->PageData['MaxPrice'] = $MaxPrice;
				
				$this->PageData['OMinPrice'] = $Filters['ominprice'];
				$this->PageData['OMaxPrice'] = $Filters['omaxprice'];
				$this->PageData['OChangePrice'] = $Filters['ochangeprice'];
				
				if($Filters['ochangeprice'] == ''){
					$this->PageData['OChangePrice'] = 0;
				}
				
				
				if($MinPrice == ''){
					$MinPrice = $setMinPrice;
					$this->PageData['MinPrice'] = $MinPrice;
					
					$Filters['ominprice'] = $setMinPrice;
					$this->PageData['OMinPrice'] = $setMinPrice;
				}
				
				if($MinPrice == '' && $setMinPrice == ''){
					$MinPrice = $Filters['ominprice'];
					$this->PageData['MinPrice'] = $MinPrice;
				}
				
				if($MaxPrice == ''){
					$MaxPrice = $setMaxPrice;
					$this->PageData['MaxPrice'] = $MaxPrice;
					
					$Filters['omaxprice'] = $setMaxPrice;
					$this->PageData['OMaxPrice'] = $setMaxPrice;
				}
				
				if($MaxPrice == '' && $setMaxPrice == ''){
					$MaxPrice = $Filters['omaxprice'];
					$this->PageData['MaxPrice'] = $MaxPrice;
				}
				
				$BredcrumHTML = $this->BredcrumAjax($request);
				$ProductHTML = view('product.list_search')->with($this->PageData)->render();
				$allFilters = view('product.filter_search')->with($this->PageData)->render();
				
				
				//return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML'=>$ProductHTML, 'Filters'=>$allFilters, 'OMinPrice'=>$MinPrice, 'OMaxPrice'=>$MaxPrice, 'MinPrice'=>$Filters['minprice'], 'MaxPrice'=>$Filters['maxprice'], 'BredcrumHTML' => $BredcrumHTML));
				return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML'=>$ProductHTML, 'Filters'=>$allFilters, 'OMinPrice'=>$Filters['ominprice'], 'OMaxPrice'=>$Filters['omaxprice'], 'MinPrice'=>$MinPrice, 'MaxPrice'=>$MaxPrice, 'BredcrumHTML' => $BredcrumHTML));
			//}
		}

		public function ProductListAjax(Request $request) {
			echo "test";
			exit;
		}

		//Add on date 25-09-2023 end
}
