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
class SaleController extends Controller
{	

	use productListingTrait;
	use generalTrait;
	use PaginationTrait;
	
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
	public function ProductSale(Request $request)
	{
		$PAGE_LIMIT = '48';
		if(config('const.PRODUCT_LISTING_PAGE_LIMIT') > 0 )
		{
		 $PAGE_LIMIT = config('const.PRODUCT_LISTING_PAGE_LIMIT');
		}
		$table_prefix = env('DB_PREFIX', '');
		
		$this->PageData['CSSFILES'] = ['listing.css'];
		
		
		$PageType = 'SR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
		/*if ($BrandData[0]->meta_title != '')
				$this->PageData['meta_title'] = $BrandData[0]->meta_title;
		if ($BrandData[0]->meta_description != '')
				$this->PageData['meta_description'] = $BrandData[0]->meta_description;
			if ($BrandData[0]->meta_keywords != '')
				$this->PageData['meta_keywords'] = $BrandData[0]->meta_keywords;
				
*/
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
		
		$ProductsDetails = $this->GetProducts('SalePage','',$PAGE_LIMIT,$SetFilters);
		
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
		
		$this->PageData['PageTitle'] = "Sale";
		//$this->PageData['PageDescription'] = strip_tags(html_entity_decode($BrandData[0]->brand_description));
	    $this->PageData['ProductListingType']= 'BrandsList';
		$this->PageData['Pagelimit']=$PAGE_LIMIT;
		$this->PageData['JSFILES'] = ['listing.js','productlisting.js','common_listing.js'];			
		return view('product.listing')->with($this->PageData);	
	}
	
	
}
