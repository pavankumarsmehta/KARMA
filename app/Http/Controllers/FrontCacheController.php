<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Cache;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Traits\generalTrait;
class FrontCacheController extends Controller
{
	use generalTrait;
	public function ClearFrontCache(Request $request)
	{
		$cacheArray = array('menu_array_common','menu_array');
		if (in_array($request->cachevarialbe, $cacheArray)) {
			Cache::forget($request->cachevarialbe);
			$array = array(
				'message' => $request->cachevarialbe . ' cache clear sucessfully',
			);

			return response()->json($array);
		}
	}

	
	public function ClearCacheByParentSku(Request $request)
	{
		Cache::forget('ColorWiseArray' . $request->product_id);
		Cache::forget('productdetails' . $request->product_id . '1');
		$ProductsObj = $this->get_all_productWithCategory();
		$product_id  = $request->product_id;
		$ProductResult = $ProductsObj->filter(function ($product, $key) use($product_id) {
			
			if($product->product_id == $product_id){
				return true;
			}else{
				return false;
			}
		})->unique('category_id');
			$ProductResult = json_decode(json_encode($ProductResult), true);
			$ProductResult = array_values($ProductResult);
			if(isset($ProductResult) && !empty($ProductResult)){
				foreach($ProductResult as $ProductResultKey => $Product){
					$category_id = $Product['category_id'];
					$brand_id = $Product['brand_id'];
					Cache::forget('listingcategory_'.$category_id.'_cache');
					Cache::forget('listingbestseller_'.$category_id.'_cache');
					Cache::forget('listingcatnewarrival_'.$category_id.'_cache');
					Cache::forget('listingcatfeatureditems_'.$category_id.'_cache');
					Cache::forget('categorylanding_featured_'.$category_id.'_cache');
					Cache::forget('categorylanding_newarrival_'.$category_id.'_cache');
					Cache::forget('categorylanding_'.$category_id.'_cache');
					Cache::forget('listingbrand_'.$brand_id.'_cache');
					Cache::forget('listingbrand_'.$category_id.'_'.$brand_id.'_cache');
					Cache::forget('categoryPopularBrands_'.$category_id.'_cache');

				}
			}
		Cache::forget('categoryPopularBrands_cache');	
        Cache::forget('homedealofweeks_cache');
		Cache::forget('dealofweekBySku_cache');
		Cache::forget('getAllBrandProductWise_cache');
		Cache::forget('listingdealofweek_cache');
		Cache::forget('homenewarrivals_cache');
		Cache::forget('listingnewarriaval_cache');
		Cache::forget('listingseasonspecial_cache');
		Cache::forget('listingsale_cache');
		Cache::forget('homeseasonalSpecials_cache');
		Cache::forget('homePopularBrands_cache');
		Cache::forget('getAllProductWithCategory_cache');
		Cache::forget('SimilarProducts');
		$array = array(
			'message' => "" . ' cache clear sucessfully by parent sku',
		);

		return response()->json($array);
	}
	//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 Start
	public function ClearCacheGlobalSetting(Request $request)
	{
		Cache::forget('settingvars_cache');
		$array = array(
			'message' => "" . ' cache clear sucessfully global settings',
		);

		return response()->json($array);
	}
	//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 End

	//Added as per 'Instagram Settings' basecamp point as on 12-03-2024 Start
	public function ClearCacheInstagramSetting(Request $request)
	{
		Cache::forget('instasettingsvars_cache');
		Cache::forget('homevars_HomePageBanner_instagram_cache');
		$array = array(
			'message' => "" . ' cache clear sucessfully instagram settings',
		);

		return response()->json($array);
	}
	//Added as per 'Instagram Settings' basecamp point as on 12-03-2024 End

	//Meta info cache clear - which is set from admin/meta-info/edit/-  START
	public function ClearCacheMetaInfo(Request $request)
	{
		Cache::forget('homevars_metainfo_cache');
		Cache::forget('metainfo_pagetype_nr');
		Cache::forget('metainfo_pagetype_pd');
		Cache::forget('metainfo_pagetype_ct');
		Cache::forget('metainfo_pagetype_sr');
		Cache::forget('metainfo_pagetype_br');
		Cache::forget('metainfo_pagetype_bs');
		
		$array = array(
			'message' => "" . ' cache clear sucessfully meta info',
		);

		return response()->json($array);
	}
	public function ClearCacheAllCategory(Request $request)
	{
		Cache::forget('getAllBrands__cache');
		Cache::forget('getAllProductWithCategory_cache');
		Cache::forget('SimilarProducts');
		$array = array(
			'message' => "" . ' cache clear sucessfully category',
		);

		return response()->json($array);
	}
	//Meta info cache clear - which is set from admin/meta-info/edit/-  END

	//HomePageBanner cache clear - which is set from admin/home-page-banner/edit/-  START
	public function ClearCacheHomePageBanner(Request $request)
	{
		Cache::forget('homevars_HomePageBanner_Main_cache');
		Cache::forget('homevars_HomePageBanner_Bottom_cache');
		Cache::forget('homevars_HomePageBanner_Promotion_cache');
		Cache::forget('homevars_HomePageBanner_Wholesaler_cache');
		
		Cache::forget('homevars_product_cache');
		Cache::forget('homevars_cache');
		
		$array = array(
			'message' => "" . ' cache clear sucessfully homepagebanner',
		);

		return response()->json($array);
	}
	//Front Main menu
	public function ClearCacheFrontMenu(Request $request)
	{
		Cache::forget('menu_array');
		
		$array = array(
			'message' => "" . ' cache clear sucessfully frontmenu',
		);

		return response()->json($array);
	}
	public function ClearCacheDealOfWeeks(Request $request)
	{
		Cache::forget('homedealofweeks_cache');
		Cache::forget('listingdealofweek_cache');
		Cache::forget('dealofweekBySku_cache');
		$array = array(
			'message' => "" . ' cache clear sucessfully homedealofweeks',
		);

		return response()->json($array);
	}
	//Front Brand menu
	public function ClearCacheFrontBrandMenu(Request $request)
	{
		$brand_id = $request->brand_id;
		$brandArr = $this->search_brand_category_ids($brand_id);
		if(isset($brandArr) && !empty($brandArr)){
			foreach($brandArr as $brandArrkey => $brandArrValue){
				$brandArr_brand_id = $brandArrValue['brand_id'];
				$brandArr_category_id = $brandArrValue['category_id'];
				Cache::forget('listingbrand_'.$brandArr_category_id.'_'.$brandArr_brand_id.'_cache');
				Cache::forget('categoryPopularBrands_'.$brandArr_category_id.'_cache');

			}
		}
		if(isset($brand_id) && !empty($brand_id)){
			Cache::forget('listingbrand_'.$brand_id.'_cache');
		}
		Cache::forget('getAllBrandProductWise_cache');
		Cache::forget('getAllBrands_cache');
		Cache::forget('brandlist');
		Cache::forget('popular_brands');
		Cache::forget('categoryPopularBrands_cache');
		Cache::forget('homePopularBrands_cache');

		$array = array(
			'message' => "" . ' cache clear sucessfully front brandmenu',
		);

		return response()->json($array);
	}
	
	public function ClearCacheFrontBrandList (Request $request){
		$brand_ids = $request->brand_ids;
		
		if(isset($brand_ids) && !empty($brand_ids)){
			$brand_id_arr = explode(",",$brand_ids);
			if(isset($brand_id_arr) && !empty($brand_id_arr)){
				foreach($brand_id_arr as $key => $brand_id){
					$brandArr = $this->search_brand_category_ids($brand_id);
					if(isset($brandArr) && !empty($brandArr)){
						foreach($brandArr as $brandArrkey => $brandArrValue){
							$brandArr_brand_id = $brandArrValue['brand_id'];
							$brandArr_category_id = $brandArrValue['category_id'];
							Cache::forget('listingbrand_'.$brandArr_category_id.'_'.$brandArr_brand_id.'_cache');
							Cache::forget('categoryPopularBrands_'.$brandArr_category_id.'_cache');

						}
					}
					Cache::forget('listingbrand_'.$brand_id.'_cache');
				}
			}
		}
		Cache::forget('getAllBrands_cache');
		Cache::forget('brandlist');
		Cache::forget('getAllBrandProductWise_cache');
		Cache::forget('categoryPopularBrands_cache');
		Cache::forget('popular_brands');
		Cache::forget('homePopularBrands_cache');

		$array = array(
			'message' => "" . ' cache clear sucessfully front brandlist',
		);
	}
	
	public function ClearCacheFrontCategory(Request $request)
	{
		$category_id = $request->category_id;
		$brandArr = $this->search_brand_category_ids('',$category_id);
		if(isset($brandArr) && !empty($brandArr)){
			foreach($brandArr as $brandArrkey => $brandArrValue){
				$brandArr_brand_id = $brandArrValue['brand_id'];
				$brandArr_category_id = $brandArrValue['category_id'];
				Cache::forget('listingbrand_'.$brandArr_category_id.'_'.$brandArr_brand_id.'_cache');
			}
		}
		if(isset($category_id) && !empty($category_id)){
			Cache::forget('listingcategory_'.$category_id.'_cache');
			Cache::forget('categoryBanner_'.$category_id.'_cache');
			Cache::forget('CategoryPromotionBanner_'.$category_id.'_cache');
			Cache::forget('CategoryBeauty_'.$category_id.'_cache');
			Cache::forget('listingcatnewarrival_'.$category_id.'_cache');
			Cache::forget('listingcatfeatureditems_'.$category_id.'_cache');

		}
		Cache::forget('getAllProductWithCategory_cache');
		Cache::forget('categorylanding_categorylist_cache');
		Cache::forget('SimilarProducts');
		Cache::forget('homeMainCategoryList_cache');
		Cache::forget('otherMainCategoryList_cache');
		Cache::forget('getAllCategories_cache');
		Cache::forget('otherCategories_menu_cache');
		
		$array = array(
			'message' => "" . ' cache clear sucessfully front category',
		);

		return response()->json($array);
	}
	public function ClearCacheFrontCategoryList(Request $request)
	{
		$category_ids = $request->category_ids;
		if(isset($category_ids) && !empty($category_ids)){
			$category_ids_arr = explode(",",$category_ids);
			if(isset($category_ids_arr) && !empty($category_ids_arr)){
				foreach($category_ids_arr as $key => $category_id){
					$brandArr = $this->search_brand_category_ids('',$category_id);
					if(isset($brandArr) && !empty($brandArr)){
						foreach($brandArr as $brandArrkey => $brandArrValue){
							$brandArr_brand_id = $brandArrValue['brand_id'];
							$brandArr_category_id = $brandArrValue['category_id'];
							Cache::forget('listingbrand_'.$brandArr_category_id.'_'.$brandArr_brand_id.'_cache');
						}
					}
					Cache::forget('listingcategory_'.$category_id.'_cache');
					Cache::forget('categoryBanner_'.$category_id.'_cache');
					Cache::forget('CategoryPromotionBanner_'.$category_id.'_cache');
					Cache::forget('CategoryBeauty_'.$category_id.'_cache');
					Cache::forget('listingcatnewarrival_'.$category_id.'_cache');
					Cache::forget('listingcatfeatureditems_'.$category_id.'_cache');
				}
			}
		}
		Cache::forget('categorylanding_categorylist_cache');
		Cache::forget('getAllProductWithCategory_cache');
		Cache::forget('SimilarProducts');
		Cache::forget('homeMainCategoryList_cache');
		Cache::forget('otherMainCategoryList_cache');
		Cache::forget('getAllCategories_cache');
		Cache::forget('getAllBrands__cache');
		Cache::forget('otherCategories_menu_cache');
		$array = array(
			'message' => "" . ' cache clear sucessfully front category list',
		);

		return response()->json($array);
	}
	
	public function ClearfrontCurrencyCache(Request $request){
		Cache::forget('getAllCurrencyArr_cache');
		$array = array(
			'message' => "" . ' cache clear sucessfully front currency',
		);

		return response()->json($array);
	}

	public function ClearCacheFrontProductList(Request $request)
	{
		$product_ids = $request->product_ids;
		if(isset($product_ids) && !empty($product_ids)){
			$product_ids_arr = explode(",",$product_ids);
			if(isset($product_ids_arr) && !empty($product_ids_arr)){
				foreach($product_ids_arr as $key => $product_id){
					Cache::forget('ColorWiseArray' . $product_id);
				Cache::forget('productdetails' . $product_id . '1');
				}
			}
		}
		Cache::forget('getAllProductWithCategory_cache');
		Cache::forget('SimilarProducts');
		Cache::forget('listingdealofweek_cache');
		Cache::forget('getAllBrandProductWise_cache');
		Cache::forget('homedealofweeks_cache');
		Cache::forget('dealofweekBySku_cache');
		Cache::forget('homenewarrivals_cache');
		Cache::forget('listingnewarriaval_cache');
		Cache::forget('listingseasonspecial_cache');
		Cache::forget('listingsale_cache');
		Cache::forget('homeseasonalSpecials_cache');
		Cache::forget('homePopularBrands_cache');
		$array = array(
			'message' => "" . ' cache clear sucessfully front product list',
		);

		return response()->json($array);
	}
	//HomePageBanner cache clear - which is set from admin/home-page-banner/edit/-  END
	
}
