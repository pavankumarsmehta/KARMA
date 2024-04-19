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
		$this->PageData['BrandsList'] = BrandsList();
		$this->PageData['CSSFILES'] = ['static.css','brandlist.css'];
		
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
			
			$this->PageData['SelCat'] = '';
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$brandEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$branddiffInMinutes = diffInMinutes($currentDatetime,$brandEndDatetime);
			if (Cache::has('listingbrand_'.$BrandID.'_cache')) 
			{
				$ProductsDetails = Cache::get('listingbrand_'.$BrandID.'_cache');
			} else {
				$ProductsDetails = $this->GetProducts('BrandsList','',$PAGE_LIMIT,$SetFilters);
				Cache::put('listingbrand_'.$BrandID.'_cache', $ProductsDetails,$branddiffInMinutes);
			}	
			$Products = $ProductsDetails['Products'];
			$TotalProducts = $ProductsDetails['TotalProducts'];
			$CatArray =  $ProductsDetails['Categories'];
			$this->PageData['Products'] = json_decode(json_encode($Products),true);
			$this->PageData['TotalProducts'] = $TotalProducts;
			$this->PageData['Categories'] = $CatArray;
			$this->PageData['Filters'] = $ProductsDetails['LeftFilters'];	

			
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
			$this->PageData['PageDescription'] = strip_tags(html_entity_decode($BrandData[0]->brand_description));
			$this->PageData['ProductListingType']= 'BrandsList';
			$this->PageData['Pagelimit']=$PAGE_LIMIT;
			$this->PageData['page']=1;
			$this->PageData['NEW_PAGE_LIMIT']=72;
			$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
			return view('product.listing')->with($this->PageData);
		} catch(\Exception $e){
			//dd($e);
			report($e);
			return abort(404);		
		}		
	}	
	
}