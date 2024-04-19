<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use App\Models\MetaInfo;
use App\Models\Wishlist;
use Cache;
use DB;
use Session;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\productTrait;

class ProductDetailController extends Controller
{
	use generalTrait;
	use productTrait;
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

	public function index(Request $request)
	{
		$this->PageData['CSSFILES'] = ['detail.css','listing.css','slick.css',];


		$SITE_URL = config('const.SITE_URL');

		$ProductsObj = $this->get_all_productWithCategory();
		// $ProductsObj = DB::table($table_prefix.'products as p')
		// 	->join($table_prefix.'products_category as pc','p.product_id','=','pc.products_id')
		// 	->join($table_prefix.'category as c','pc.category_id','=','c.category_id')
		// 	->select('p.*','c.*','c.status as category_status')->get();
		$product_sku = trim($request->product_sku);
		$status_val = '1';
		// if ($request->preview == 1) {
		// 	$status_val = '0';
		// }
		if (Session::has('testmode')) {

			$status_val = '1';
		}
		
		$ProductResult = $ProductsObj->filter(function ($product, $key) use($product_sku,$status_val) {
			
			
			
			if($product->sku == $product_sku && $product->status==$status_val  && $product->category_status==1){
				return true;
			}else{
				return false;
			}
		})->unique('product_id');
		
		$ProductResult = json_decode(json_encode($ProductResult), true);
			$ProductResult = array_values($ProductResult);
			$ProductResult = uniqueArray($ProductResult,'product_id');
		if (isset($ProductResult) && empty($ProductResult)) {
			//return abort(404);
			return redirect('/');


		}

		$Product = $ProductResult[0];
		$product_id = $Product['product_id'];

		//echo $product_sku; exit;
		if ($product_sku == '' || empty($product_sku)) {
			//return abort(404);
			return redirect('/');
		}

	

		//echo $product_sku; exit;
		$allURLforCrowal = array();
		
		if (Cache::has('productdetails' . $product_id . $status_val))
		{
			$Product = Cache::get('productdetails' . $product_id . $status_val);
		} else {
			
			//DB::enableQueryLog();
			// $ProductResult = Product::from('hba_products as p')
			// 	->select('p.*','c.category_name','c.category_id')
			// 	->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
			// 	->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
			// 	->where('p.sku', '=', $product_sku)
			// 	->where('p.status', '=', $status_val)
			// 	->where('p.current_stock', '>', '0');

			$ProductResult = $ProductsObj->filter(function ($product, $key) use($product_sku,$status_val) {
				if($product->sku == $product_sku && $product->status==$status_val  && $product->category_status==1){
					return true;
				}else{
					return false;
				}
			})->unique('product_id');
			//dd($ProductResult);
			$ProductResult = json_decode(json_encode($ProductResult), true);
			$ProductResult = array_values($ProductResult);
			$ProductResult = uniqueArray($ProductResult,'product_id');

			/*if (Session::get('is_from_sale') == 'YES') {
				$ProductResult->where('p.is_sale', '>', 0)
					->where('p.is_sale', '!=', 999999);
			}*/

			// $ProductResult->where('c.status', '=', '1');
			// $ProductResult->groupBy('p.product_id');
			// //dd(DB::getQueryLog());
			// $Product = $ProductResult->get();
			$Product = $ProductResult;

			//dd($Product);
			if($_SERVER['REMOTE_ADDR'] == '49.36.91.121')
			{
				//dd($Product);
			}
			Cache::put('productdetails' . $product_id . $status_val, $Product);
		}
		$Product = $Product[0];
		//dd($Product);
		$Product = json_decode(json_encode($Product), true);
		$product_group_code = $Product['product_group_code'];
		if(!isset($Product['product_group_code']) || $Product['product_group_code'] == '' || $Product['product_group_code'] == NULL)
		{
			$product_group_code = $Product['sku'];
		}
		// $SizeWiseProducts = Product::from('hba_products as p')
		// 	->select('p.product_id', 'p.sku', 'p.product_name', 'p.product_description', 'p.size',  'p.badge', 'p.retail_price', 'p.our_price', 'p.sale_price','p.parent_sku', 'p.product_group_code', 'p.image_name', 'p.extra_images', 'p.video_url', 'p.meta_title', 'p.meta_keyword', 'p.meta_description', 'p.shipping_text',  'p.product_weight', 'p.quantity','p.current_stock', 'p.parent_category_id','p.product_url')
		// 	->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
		// 	->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
		// 	->where('p.status', '=', $status_val)
		// 	->where('p.product_group_code', '=', $product_group_code)
		// 	->where('c.status', '=', '1')
		// 	->groupBy('p.size')
		// 	->orderBy('p.display_rank', 'ASC')->get();

		$product_size = $Product['size'];
		$product_pack_size = $Product['pack_size'];
		$product_flavour = $Product['flavour'];

		$PackSizeWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$product_size) {
			if($product->pack_size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1  && strtolower($product->size) == strtolower($product_size)){
				return true;
			}else{	
				return false;
			}
		})->unique('pack_size');

		$SizeWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$product_flavour) {
			if($product->size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1 && strtolower($product->flavour) == strtolower($product_flavour)){
				return true;
			}else{
				return false;
			}
		})->unique('size');
		
		$SizeWiseProductsSelected = json_decode(json_encode($SizeWiseProductsSelected), true);
		$SizeWiseProductsSelected = array_values($SizeWiseProductsSelected);
		$SizeWiseProductsSelected = uniqueArray($SizeWiseProductsSelected,'size');
		$SizeWiseProductsSelected = array_sort($SizeWiseProductsSelected, 'display_rank', SORT_ASC);

		$SizeWiseProductsSelected_arr = [];
		foreach ($SizeWiseProductsSelected as $key => $SizeWiseProduct)
		{
			$SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['product_id'] = $SizeWiseProduct['product_id'];
			$SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['product_name'] = $SizeWiseProduct['product_name'];
			$SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['pack_size'] = $SizeWiseProduct['size'];
		}
	
		//dd($SizeWiseProductsSelected_arr);
		$SizeWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
			if($product->size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
				return true;
			}else{
				return false;
			}
		})->unique('size');
		$SizeWiseProducts = json_decode(json_encode($SizeWiseProducts), true);
		$SizeWiseProducts = array_values($SizeWiseProducts);
		$SizeWiseProducts = uniqueArray($SizeWiseProducts,'size');
		$SizeWiseProducts = array_sort($SizeWiseProducts, 'display_rank', SORT_ASC);
		

		$SizeWiseProducts_arr = [];
		foreach ($SizeWiseProducts as $key => $SizeWiseProduct)
		{
			$SizeWiseProducts_arr[$key]['product_id'] = $SizeWiseProduct['product_id'];
			$SizeWiseProducts_arr[$key]['sku'] = $SizeWiseProduct['sku'];
			$SizeWiseProducts_arr[$key]['product_name'] = $SizeWiseProduct['product_name'];
			$SizeWiseProducts_arr[$key]['product_url'] = $SizeWiseProduct['product_url'];
			$SizeWiseProducts_arr[$key]['size'] = $SizeWiseProduct['size'];
			$SizeWiseProducts_arr[$key]['product_description'] = $SizeWiseProduct['product_description'];
			$SizeWiseProducts_arr[$key]['category_id'] = $SizeWiseProduct['category_id'];
			$SizeWiseProducts_arr[$key]['retail_price'] = $SizeWiseProduct['retail_price'];
			$SizeWiseProducts_arr[$key]['our_price'] = $SizeWiseProduct['our_price'];
			$SizeWiseProducts_arr[$key]['sale_price'] = $SizeWiseProduct['sale_price'];
			$SizeWiseProducts_arr[$key]['image_name'] = $SizeWiseProduct['image_name'];
			$SizeWiseProducts_arr[$key]['extra_images'] = $SizeWiseProduct['extra_images'];

			$SizeWiseProductPrice = $this->Get_Price_Val($SizeWiseProduct);

			$SizeWiseProducts_arr[$key]['retail_price'] = $SizeWiseProductPrice['retail_price'];
			$SizeWiseProducts_arr[$key]['our_price'] = $SizeWiseProductPrice['our_price'];
			$SizeWiseProducts_arr[$key]['sale_price'] = $SizeWiseProductPrice['sale_price'];
			$SizeWiseProducts_arr[$key]['retail_price_disp'] = $SizeWiseProductPrice['retail_price_disp'];
			$SizeWiseProducts_arr[$key]['our_price_disp'] = $SizeWiseProductPrice['our_price_disp'];
			$SizeWiseProducts_arr[$key]['sale_price_disp'] = $SizeWiseProductPrice['sale_price_disp'];

			//if(isset($SizeWiseProducts_arr[$key]['image_name']) && $SizeWiseProducts_arr[$key]['image_name'] != "")
			//{

			$SizeWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'ZOOM');
			$SizeWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'LARGE');
			$SizeWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'MEDIUM');
			$SizeWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'THUMB');
			$SizeWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'SMALL');
			//}
		}
	
		//dd($SizeWiseProducts_arr);
		// $PrdWishRes = Wishlist::from('hba_wishlist')->select('customer_id','products_id','sku')
		// ->where('products_id', '=', $Product['product_id'])
		// ->where('customer_id', '=', Session::get('customer_id'))->limit(1)->get();
		
		//pack size array
	
		$PackSizeWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$product_flavour,$product_size) {
			if($product->pack_size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1  && strtolower($product->size) == strtolower($product_size) && strtolower($product->flavour) == strtolower($product_flavour)){
				return true;
			}else{	
				return false;
			}
		})->unique('pack_size');
		
		$PackSizeWiseProductsSelected = json_decode(json_encode($PackSizeWiseProductsSelected), true);
		$PackSizeWiseProductsSelected = array_values($PackSizeWiseProductsSelected);
		$PackSizeWiseProductsSelected = uniqueArray($PackSizeWiseProductsSelected,'pack_size');
		$PackSizeWiseProductsSelected = array_sort($PackSizeWiseProductsSelected, 'display_rank', SORT_ASC);

		$PackWiseProductsSelected_arr = [];
		foreach ($PackSizeWiseProductsSelected as $key => $PackSizeWiseProduct)
		{
			$PackWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['product_id'] = $PackSizeWiseProduct['product_id'];
			$PackWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['product_name'] = $PackSizeWiseProduct['product_name'];
			$PackWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['pack_size'] = $PackSizeWiseProduct['pack_size'];
		}
	
		$PackSizeWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
			if($product->pack_size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
				return true;
			}else{	
				return false;
			}
		})->unique('pack_size');
		$PackSizeWiseProducts = json_decode(json_encode($PackSizeWiseProducts), true);
		$PackSizeWiseProducts = array_values($PackSizeWiseProducts);
		$PackSizeWiseProducts = uniqueArray($PackSizeWiseProducts,'pack_size');
		$PackSizeWiseProducts = array_sort($PackSizeWiseProducts, 'display_rank', SORT_ASC);

		
		$PackWiseProducts_arr = [];
		foreach ($PackSizeWiseProducts as $key => $PackSizeWiseProduct)
		{
			$PackWiseProducts_arr[$key]['product_id'] = $PackSizeWiseProduct['product_id'];
			$PackWiseProducts_arr[$key]['sku'] = $PackSizeWiseProduct['sku'];
			$PackWiseProducts_arr[$key]['product_name'] = $PackSizeWiseProduct['product_name'];
			$PackWiseProducts_arr[$key]['product_url'] = $PackSizeWiseProduct['product_url'];
			$PackWiseProducts_arr[$key]['pack_size'] = $PackSizeWiseProduct['pack_size'];
			$PackWiseProducts_arr[$key]['product_description'] = $PackSizeWiseProduct['product_description'];
			$PackWiseProducts_arr[$key]['category_id'] = $PackSizeWiseProduct['category_id'];
			$PackWiseProducts_arr[$key]['retail_price'] = $PackSizeWiseProduct['retail_price'];
			$PackWiseProducts_arr[$key]['our_price'] = $PackSizeWiseProduct['our_price'];
			$PackWiseProducts_arr[$key]['sale_price'] = $PackSizeWiseProduct['sale_price'];
			$PackWiseProducts_arr[$key]['image_name'] = $PackSizeWiseProduct['image_name'];
			$PackWiseProducts_arr[$key]['extra_images'] = $PackSizeWiseProduct['extra_images'];
			$PackWiseProducts_arr[$key]['display_rank'] = $PackSizeWiseProduct['display_rank'];
			$PackSizeWiseProduct = $this->Get_Price_Val($PackSizeWiseProduct);

			$PackWiseProducts_arr[$key]['retail_price'] = $PackSizeWiseProduct['retail_price'];
			$PackWiseProducts_arr[$key]['our_price'] = $PackSizeWiseProduct['our_price'];
			$PackWiseProducts_arr[$key]['sale_price'] = $PackSizeWiseProduct['sale_price'];
			$PackWiseProducts_arr[$key]['retail_price_disp'] = $PackSizeWiseProduct['retail_price_disp'];
			$PackWiseProducts_arr[$key]['our_price_disp'] = $PackSizeWiseProduct['our_price_disp'];
			$PackWiseProducts_arr[$key]['sale_price_disp'] = $PackSizeWiseProduct['sale_price_disp'];
			

			//if(isset($SizeWiseProducts_arr[$key]['image_name']) && $SizeWiseProducts_arr[$key]['image_name'] != "")
			//{

			$PackWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'ZOOM');
			$PackWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'LARGE');
			$PackWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'MEDIUM');
			$PackWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'THUMB');
			$PackWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'SMALL');
			//}
		}
		
		// $FlavourWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$product_size,$product_pack_size) {
		// 	if($product->flavour != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1 && strtolower($product->size) == strtolower($product_size) && strtolower($product->pack_size) == strtolower($product_pack_size)){
		// 		return true;
		// 	}else{	
		// 		return false;
		// 	}
		// })->unique('flavour');
		// $FlavourWiseProductsSelected = json_decode(json_encode($FlavourWiseProductsSelected), true);
		// $FlavourWiseProductsSelected = array_values($FlavourWiseProductsSelected);
		// $FlavourWiseProductsSelected = uniqueArray($FlavourWiseProductsSelected,'flavour');
		// $FlavourWiseProductsSelected = array_sort($FlavourWiseProductsSelected, 'display_rank', SORT_ASC);

			
		// $FlavourWiseProductsSelected_arr = [];
		// foreach ($FlavourWiseProductsSelected as $key => $FlavourWiseProduct)
		// {
		// 	$FlavourWiseProductsSelected_arr[strtolower(title($FlavourWiseProduct['flavour']))]['product_id'] = $FlavourWiseProduct['product_id'];
		// 	$FlavourWiseProductsSelected_arr[strtolower(title($FlavourWiseProduct['flavour']))]['product_name'] = $FlavourWiseProduct['product_name'];
		// 	$FlavourWiseProductsSelected_arr[strtolower(title($FlavourWiseProduct['flavour']))]['flavour'] = $FlavourWiseProduct['pack_size'];
		// }
		if($_SERVER['REMOTE_ADDR'] == '122.167.69.240')
			{
			//	dd($FlavourWiseProductsSelected_arr);
			}
		
		//flavour attribute array
		$FlavourWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
			if($product->flavour != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
				return true;
			}else{	
				return false;
			}
		})->unique('flavour');
		$FlavourWiseProducts = json_decode(json_encode($FlavourWiseProducts), true);
		$FlavourWiseProducts = array_values($FlavourWiseProducts);
		$FlavourWiseProducts = uniqueArray($FlavourWiseProducts,'flavour');
		$FlavourWiseProducts = array_sort($FlavourWiseProducts, 'display_rank', SORT_ASC);

		$FlavourWiseProducts_arr = [];
		foreach ($FlavourWiseProducts as $key => $FlavourWiseProduct)
		{
			$FlavourWiseProducts_arr[$key]['product_id'] = $FlavourWiseProduct['product_id'];
			$FlavourWiseProducts_arr[$key]['sku'] = $FlavourWiseProduct['sku'];
			$FlavourWiseProducts_arr[$key]['product_name'] = $FlavourWiseProduct['product_name'];
			$FlavourWiseProducts_arr[$key]['product_url'] = $FlavourWiseProduct['product_url'];
			$FlavourWiseProducts_arr[$key]['flavour'] = $FlavourWiseProduct['flavour'];
			$FlavourWiseProducts_arr[$key]['product_description'] = $FlavourWiseProduct['product_description'];
			$FlavourWiseProducts_arr[$key]['category_id'] = $FlavourWiseProduct['category_id'];
			$FlavourWiseProducts_arr[$key]['retail_price'] = $FlavourWiseProduct['retail_price'];
			$FlavourWiseProducts_arr[$key]['our_price'] = $FlavourWiseProduct['our_price'];
			$FlavourWiseProducts_arr[$key]['sale_price'] = $FlavourWiseProduct['sale_price'];
			$FlavourWiseProducts_arr[$key]['image_name'] = $FlavourWiseProduct['image_name'];
			$FlavourWiseProducts_arr[$key]['extra_images'] = $FlavourWiseProduct['extra_images'];

			$FlavourWiseProduct = $this->Get_Price_Val($FlavourWiseProduct);

			$FlavourWiseProducts_arr[$key]['retail_price'] = $FlavourWiseProduct['retail_price'];
			$FlavourWiseProducts_arr[$key]['our_price'] = $FlavourWiseProduct['our_price'];
			$FlavourWiseProducts_arr[$key]['sale_price'] = $FlavourWiseProduct['sale_price'];
			$FlavourWiseProducts_arr[$key]['retail_price_disp'] = $FlavourWiseProduct['retail_price_disp'];
			$FlavourWiseProducts_arr[$key]['our_price_disp'] = $FlavourWiseProduct['our_price_disp'];
			$FlavourWiseProducts_arr[$key]['sale_price_disp'] = $FlavourWiseProduct['sale_price_disp'];

			//if(isset($SizeWiseProducts_arr[$key]['image_name']) && $SizeWiseProducts_arr[$key]['image_name'] != "")
			//{

			$FlavourWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'ZOOM');
			$FlavourWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'LARGE');
			$FlavourWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'MEDIUM');
			$FlavourWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'THUMB');
			$FlavourWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'SMALL');
			//}
		}


		if($_SERVER['REMOTE_ADDR'] == '122.167.69.240')
		{
		//dd($FlavourWiseProducts_arr);
		}
		$PrdWishRes = $this->get_all_wishlist();
		if(isset($PrdWishRes[$Product['product_id']]) && !empty($PrdWishRes[$Product['product_id']])){
			$PrdWishRes = $PrdWishRes[$Product['product_id']];
		}
		//dd($PrdWishRes[0]['products_id']);
		$SizeWiseArray = $SizeWiseProducts_arr;
		$PackSizeWiseArray = $PackWiseProducts_arr;
		$FlavourWiseArray = $FlavourWiseProducts_arr;
		$PackSizeSelectedWiseArray = $PackWiseProductsSelected_arr;
		$SizeSelectedWiseArray = $SizeWiseProductsSelected_arr;
		//dd($SizeWiseArray);
		//dd($Product);
		$category_id = $Product['category_id'];
		$category_name = $Product['category_name'];

		if (Cache::has('SimilarProducts' . $product_sku . $status_val))
		{
			$SimilarProducts = Cache::get('SimilarProducts' . $product_sku . $status_val);
		} else {
			// $SimilarProductResult = Product::from('hba_products as p')
			// ->select('p.product_id','p.sku','p.product_name','p.product_url','p.product_description','c.category_id','p.retail_price','p.our_price','p.sale_price','p.image_name','p.extra_images')
			// ->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
			// ->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
			// ->where('p.status', '=', $status_val)
			// ->where('c.category_id', '=', $category_id)
			// ->where('p.current_stock', '>', '0')
			// ->where('c.status', '=', '1')
			// ->groupBy('p.product_id')
			// ->limit(10)->get();

			$SimilarProductResult = $ProductsObj->filter(function ($product, $key) use($category_id,$status_val) {
				if($product->category_id == $category_id && $product->status==$status_val && $product->category_status==1 ){
					return true;
				}else{
					return false;
				}
			})->unique('product_id')->slice(0, 10);
			$SimilarProductResult = json_decode(json_encode($SimilarProductResult), true);
			$SimilarProductResult = array_values($SimilarProductResult);
			$SimilarProductResult = uniqueArray($SimilarProductResult,'product_id');
			$SimilarProductResult = array_sort($SimilarProductResult, 'display_rank', SORT_ASC);
			// $SimilarProductResult->where('c.status', '=', '1');
			// $SimilarProductResult->groupBy('p.product_id');
			 $SimilarProducts = $SimilarProductResult;

			Cache::put('SimilarProducts' . $product_sku . $status_val, $SimilarProducts);
		}

		//$SimilarProducts = [];


		$SimilarProducts_arr = [];
		foreach ($SimilarProducts as $key => $SimilarProduct)
		{
			$SimilarProducts_arr[$key]['product_id'] = $SimilarProduct['product_id'];
			$SimilarProducts_arr[$key]['sku'] = $SimilarProduct['sku'];
			$SimilarProducts_arr[$key]['product_name'] = $SimilarProduct['product_name'];
			$SimilarProducts_arr[$key]['product_url'] = $SimilarProduct['product_url'];
			$SimilarProducts_arr[$key]['product_description'] = $SimilarProduct['product_description'];
			$SimilarProducts_arr[$key]['category_id'] = $SimilarProduct['category_id'];
			$SimilarProducts_arr[$key]['retail_price'] = $SimilarProduct['retail_price'];
			$SimilarProducts_arr[$key]['our_price'] = $SimilarProduct['our_price'];
			$SimilarProducts_arr[$key]['sale_price'] = $SimilarProduct['sale_price'];
			$SimilarProducts_arr[$key]['image_name'] = $SimilarProduct['image_name'];
			$SimilarProducts_arr[$key]['extra_images'] = $SimilarProduct['extra_images'];

			$SimilarproductPrice = $this->Get_Price_Val($SimilarProduct);
			//dd('ssds',$SimilarproductPrice);
			$SimilarProducts_arr[$key]['retail_price'] = $SimilarproductPrice['retail_price'];
			$SimilarProducts_arr[$key]['our_price'] = $SimilarproductPrice['our_price'];
			$SimilarProducts_arr[$key]['sale_price'] = $SimilarproductPrice['sale_price'];
			$SimilarProducts_arr[$key]['retail_price_disp'] = $SimilarproductPrice['retail_price_disp'];
			$SimilarProducts_arr[$key]['our_price_disp'] = $SimilarproductPrice['our_price_disp'];
			$SimilarProducts_arr[$key]['sale_price_disp'] = $SimilarproductPrice['sale_price_disp'];

			//if(isset($SimilarProducts_arr[$key]['image_name']) && $SimilarProducts_arr[$key]['image_name'] != "")
			//{
				$SimilarProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'ZOOM');
				$SimilarProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'MEDIUM');
				$SimilarProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'LARGE');
				$SimilarProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'THUMB');
				$SimilarProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'SMALL');
			//}
		}
		//dd($SimilarProducts_arr);
		//dd($SimilarProducts);

		$Product['product_zoom_image'] = Get_Product_Image_URL($Product['image_name'], 'ZOOM');
		$Product['product_medium_image'] = Get_Product_Image_URL($Product['image_name'], 'MEDIUM');
        $Product['product_large_image'] = Get_Product_Image_URL($Product['image_name'], 'LARGE');
        $Product['product_thumb_image'] = Get_Product_Image_URL($Product['image_name'], 'THUMB');
        $Product['product_small_image'] = Get_Product_Image_URL($Product['image_name'], 'SMALL');
		//echo "<pre>"; print_r($Product); exit;
		if(isset($Product['shipping_text']) && $Product['shipping_text'] != "")
		{
			$Product['shipping_text'] = $Product['shipping_text'];
		}
		else
		{
			$today = date('Y-m-d');
			$tomorrow_date = date('M. d', strtotime($today . ' + 1 days'));
			$future_date = date('M. d', strtotime($today . ' + 7 days'));
			//echo $future_date; exit;
			$shipping_text = "Get it as soon as ".$tomorrow_date." - ".$future_date."";
			$Product['shipping_text'] = $shipping_text;
		}
		//echo $Product['shipping_text']; exit;
		$arr_extra_image = array();

		if (trim($Product['product_large_image']) != "") {
			$arr_extra_image[] = array(
				'extra_image_name' => $Product['product_large_image'],
				'extra_zoom_url' => $Product['product_zoom_image'],
				'extra_large_url' => $Product['product_large_image'],
				'extra_medium_url' => $Product['product_medium_image'],
				'extra_thumb_url' => $Product['product_thumb_image'],
				'extra_small_url' => $Product['product_small_image'],
				'video_image_type' => 'image',
			);
		}

		$extra_images = array();
        if (trim($Product['extra_images']) != '') {
            $extra_images = explode('#', $Product['extra_images']);

            if (count($extra_images) > 0) {
                for ($k = 0; $k < count($extra_images); $k++) {
                    $extra_zoom_url = Get_Product_Image_URL($extra_images[$k], 'ZOOM');
                    $extra_large_url = Get_Product_Image_URL($extra_images[$k], 'LARGE');
                    $extra_medium_url = Get_Product_Image_URL($extra_images[$k], 'MEDIUM');
                    $extra_thumb_url = Get_Product_Image_URL($extra_images[$k], 'THUMB');
                    $extra_small_url = Get_Product_Image_URL($extra_images[$k], 'SMALL');

                    if (!preg_match("/noimage/i", $extra_thumb_url) or !preg_match("/noimage/i", $extra_large_url)) {
                        $arr_extra_image[] = array(
                            'extra_image_name' => $extra_images[$k],
                            'extra_zoom_url' => $extra_zoom_url,
                            'extra_large_url' => $extra_large_url,
                            'extra_medium_url' => $extra_medium_url, //show large image
                            'extra_thumb_url' => $extra_thumb_url,
                            'extra_small_url' => $extra_small_url,
                            'video_image_type' => "image",
                        );
                    }

                }
            }
        }
		//echo $Product['video_url']; exit;
		if ($Product['video_url'] != "") {
			if (strpos($Product['video_url'], 'you') !== false) {
				preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $Product['video_url'], $matches);
				if (isset($matches[1]) && strlen($matches[1]) > 0) {
					$arr_extra_image[] = array(
						'extra_image_name' => $matches[1],
						'extra_zoom_url' => $matches[1],
						'extra_large_url' => $matches[1],
						'extra_medium_url' => $matches[1],
						'extra_thumb_url' => '<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
							<use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
						</svg>',
						'video_image_type' => 'mp4',
						'extra_small_url' => $matches[1],
					);

				}
			} else {
				$arr_extra_image[] = array(
					'extra_image_name' => $Product['video_url'],
					'extra_zoom_url' => $Product['video_url'],
					'extra_large_url' => $Product['video_url'],
					'extra_medium_url' => $Product['video_url'],
					'extra_small_url' => $Product['video_url'],
					'extra_thumb_url' => '<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
						<use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
					</svg>',
					'video_image_type' => 'mp4',
				);
			}
		}

		//dd($arr_extra_image);
		$allDealOFWeekArr = get_deal_of_week_by_sku();
		if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
			
			if(isset($allDealOFWeekArr[$Product['sku']]) && !empty($allDealOFWeekArr[$Product['sku']])){
				$Product['our_price'] = $allDealOFWeekArr[$Product['sku']]->deal_price;
				$Product['sale_price'] = $allDealOFWeekArr[$Product['sku']]->deal_price;
				$Product['deal_description'] = $allDealOFWeekArr[$Product['sku']]->description;
			}	
		}
		$productPrice = $this->Get_Price_Val($Product);
		
		$Product['retail_price'] = $productPrice['retail_price'];
		$Product['our_price'] = $productPrice['our_price'];
		$Product['sale_price'] = $productPrice['sale_price'];
		$Product['retail_price_disp'] = $productPrice['retail_price_disp'];
		$Product['our_price_disp'] = $productPrice['our_price_disp'];
		$Product['sale_price_disp'] = $productPrice['sale_price_disp'];
		//echo config('const.PDF_PATH').$Product->ingredients_pdf;
		//echo config('const.PDF_PATH').$Product->ingredients_pdf; exit;
		$ingredients_pdf = '';
		if (isset($Product->ingredients_pdf) && file_exists(config('const.PDF_PATH').$Product->ingredients_pdf))
		{

			$ingredients_pdf =  stripslashes(config('const.PDF_URL').$Product->ingredients_pdf);
		}
		$Product['ingredients_pdf'] = $ingredients_pdf;
		//echo $Product['ingredients_pdf']; exit;
		###################### BREADCRUMB START ####################
		$bredCrumb_Detail = detailBreadcrumb($Product['product_id'], $Product['category_id'], $Product['parent_category_id'],$Product);
		###################### BREADCRUMB END ####################


		###################### METADATA START ####################
		$PageType = 'PD';
		if (Cache::has('metainfo_pagetype_pd')) {
			$MetaInfo = Cache::get('metainfo_pagetype_pd');
		} else {
			$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();
			Cache::put('metainfo_pagetype_pd', $MetaInfo);
		}

		if ($Product['product_description'] != '') {
			$Product_description = strip_tags($Product['product_description']);
		} else {
			$Product_description = $Product['product_name'];
		}



		if (!empty($Product->meta_title))
		{
			$this->PageData['meta_title'] =  stripslashes($Product->meta_title);
		}
		elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_title))
		{
			$meta_title = str_replace(array('{$product_name}', '{$category_name}'), array($Product['product_name'], $Product['category_name']), $MetaInfo[0]->meta_title);
			$this->PageData['meta_title'] = stripslashes($meta_title);
		}

		if (!empty($Product->meta_keywords))
		{
			$this->PageData['meta_keywords'] =  stripslashes($Product->meta_keywords);
		}
		elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_keywords))
		{
			$meta_keywords = str_replace(array('{$product_description}', '{$category_description}', '{$category_name}'), array(strip_tags($Product['product_description']), strip_tags($Product['category_description']), $Product['product_description']), $MetaInfo[0]->meta_keywords);
			$this->PageData['meta_keywords'] = $meta_keywords;
		}

		if (!empty($Product->meta_description))
		{
			$this->PageData['meta_description'] =  stripslashes($Product->meta_description);
		}
		elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_keywords))
		{
			$meta_description = str_replace(array('{$product_description}', '{$category_description}', '{$category_name}'), array(strip_tags($Product['product_description']), strip_tags($Product['category_description']), $Product['category_name']), $MetaInfo[0]->meta_description);
			$this->PageData['meta_description'] = $meta_description;
		}

		###################### METADATA END ####################


		################ PRODUCT RELATED ITEMS START ###############
        $arr_related_item = array();
        $arr_related_item = $this->getProduct_SimilarItems(trim($Product['related_sku']));
		################ PRODUCT RELATED ITEMS END ###############

		################ PRODUCT RECENT ITEMS START ###############
        $arr_recent_item = array();
        $arr_recent_item = $this->getRecent_ViewedItems($product_sku,$ProductsObj);
		//dd($arr_recent_item);
		################ PRODUCT RECENT ITEMS END ###############
		// 	###################### BREADCRUMB START ####################
		$ProductObj = json_decode(json_encode($Product));
		$breadcrumbListSchemaData = getBreadcrumbListSchema($Product['category_id'], $Product);
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		$productSchemaData = getProductSchema($ProductObj);
		if ($productSchemaData != false) {
			$this->PageData['product_schema'] = $productSchemaData;
		}
	// 	###################### BREADCRUMB END ####################
		$getAllCurrencyObj = getCurrencyArray();
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
		}else{
			$curencySymbol = '$';
		}
		
		//dd($Product);
		$PageBanner_Main = '';
		$this->PageData['JSFILES'] = ['slick.js','detail.js'];
		$this->PageData['Product'] = $Product;
		$this->PageData['product_sku'] = $product_sku;
		$this->PageData['ActiveSize'] =  $Product['size'];
		$this->PageData['ActivePackSize'] =  $Product['pack_size'];
		$this->PageData['ActiveFlavour'] =  $Product['flavour'];
		$Wishproducts_id = 0;
		if(isset($PrdWishRes[0]['products_id']) && $PrdWishRes[0]['products_id'] !="")
		{
			$Wishproducts_id = $PrdWishRes[0]['products_id'];
		}
		$this->PageData['Wishproducts_id'] =  $Wishproducts_id;
		$this->PageData['SimilarProducts'] = $SimilarProducts_arr;
		$this->PageData['arr_extra_image'] = $arr_extra_image;
		$this->PageData['SizeWiseArray'] = $SizeWiseArray;
		$this->PageData['PackSizeWiseArray'] = $PackSizeWiseArray;
		$this->PageData['FlavourWiseArray'] = $FlavourWiseArray;
		$this->PageData['PackSizeSelectedWiseArray'] = $PackSizeSelectedWiseArray;
		$this->PageData['SizeSelectedWiseArray'] = $SizeSelectedWiseArray;
		$this->PageData['related_item'] = $arr_related_item;
		$this->PageData['recent_item'] = $arr_recent_item;
		$this->PageData['bredCrumb_Detail'] = $bredCrumb_Detail;
		$this->PageData['CurencySymbol'] = $curencySymbol;
		$final_home = array();
		return view('product_detail.productdetail', compact('PageBanner_Main','final_home'))->with($this->PageData);
	}

	/*public function index(Request $request)
	{
		// echo url()->current();
		// exit;

		$this->PageData['CSSFILES'] = ['detail.css','listing.css','slick.css',];


		$SITE_URL = config('const.SITE_URL');
		$product_sku = trim($request->product_sku);
		
		//echo $product_sku; exit;
		if ($product_sku == '' || empty($product_sku)) {
			return abort(404);
		}

		$status_val = '1';
		if ($request->preview == 1) {
			$status_val = '0';
		}
		$ProductObjResult = Product::from('hba_products as p')
				->select('p.product_id')
				->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
				->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
				->where('p.sku', '=', $product_sku)
				->where('p.status', '=', $status_val)
				->where('p.current_stock', '>', '0')
				->where('c.status', '=', '1')
				->groupBy('p.product_id')
				->first();
		if(isset($ProductObjResult) && !empty($ProductObjResult)){
			$product_id = $ProductObjResult->product_id; 
		}		
		if($_SERVER['REMOTE_ADDR'] == '182.69.28.20')
		{

		}
		//echo $product_sku; exit;
		$allURLforCrowal = array();

		if (Cache::has('productdetails' . $product_id . $status_val))
		{
			$Product = Cache::get('productdetails' . $product_id . $status_val);
		} else {

			//DB::enableQueryLog();
			$ProductResult = Product::from('hba_products as p')
				->select('*')
				->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
				->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
				->where('p.sku', '=', $product_sku)
				->where('p.status', '=', $status_val)
				->where('p.current_stock', '>', '0');

			$ProductResult->where('c.status', '=', '1');
			$ProductResult->groupBy('p.product_id');
			//dd(DB::getQueryLog());
			$Product = $ProductResult->get();
			//dd($Product);

			Cache::put('productdetails' . $product_id . $status_val, $Product);
		}
		$Product = $Product[0];
		//dd($Product);
		$product_group_code = $Product['product_group_code'];
		if(!isset($Product['product_group_code']) || $Product['product_group_code'] == '' || $Product['product_group_code'] == NULL)
		{
			$product_group_code = $Product['sku'];
		}
		$SizeWiseProducts = Product::from('hba_products as p')
			->select('p.product_id', 'p.sku', 'p.product_name', 'p.product_description', 'p.size',  'p.badge', 'p.retail_price', 'p.our_price', 'p.sale_price','p.parent_sku', 'p.product_group_code', 'p.image_name', 'p.extra_images', 'p.video_url', 'p.meta_title', 'p.meta_keyword', 'p.meta_description', 'p.shipping_text',  'p.product_weight', 'p.quantity','p.current_stock', 'p.parent_category_id','p.product_url')
			->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
			->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
			->where('p.status', '=', $status_val)
			->where('p.product_group_code', '=', $product_group_code)
			->where('c.status', '=', '1')
			->groupBy('p.size')
			->orderBy('p.display_rank', 'ASC')->get();

		$SizeWiseProducts_arr = [];
		foreach ($SizeWiseProducts as $key => $SizeWiseProduct)
		{
			$SizeWiseProducts_arr[$key]['product_id'] = $SizeWiseProduct['product_id'];
			$SizeWiseProducts_arr[$key]['sku'] = $SizeWiseProduct['sku'];
			$SizeWiseProducts_arr[$key]['product_name'] = $SizeWiseProduct['product_name'];
			$SizeWiseProducts_arr[$key]['product_url'] = $SizeWiseProduct['product_url'];
			$SizeWiseProducts_arr[$key]['size'] = $SizeWiseProduct['size'];
			$SizeWiseProducts_arr[$key]['product_description'] = $SizeWiseProduct['product_description'];
			$SizeWiseProducts_arr[$key]['category_id'] = $SizeWiseProduct['category_id'];
			$SizeWiseProducts_arr[$key]['retail_price'] = $SizeWiseProduct['retail_price'];
			$SizeWiseProducts_arr[$key]['our_price'] = $SizeWiseProduct['our_price'];
			$SizeWiseProducts_arr[$key]['sale_price'] = $SizeWiseProduct['sale_price'];
			$SizeWiseProducts_arr[$key]['image_name'] = $SizeWiseProduct['image_name'];
			$SizeWiseProducts_arr[$key]['extra_images'] = $SizeWiseProduct['extra_images'];

			$SizeWiseProductPrice = $this->Get_Price_Val($SizeWiseProduct);

			$SizeWiseProducts_arr[$key]['retail_price'] = $SizeWiseProductPrice['retail_price'];
			$SizeWiseProducts_arr[$key]['our_price'] = $SizeWiseProductPrice['our_price'];
			$SizeWiseProducts_arr[$key]['sale_price'] = $SizeWiseProductPrice['sale_price'];
			$SizeWiseProducts_arr[$key]['retail_price_disp'] = $SizeWiseProductPrice['retail_price_disp'];
			$SizeWiseProducts_arr[$key]['our_price_disp'] = $SizeWiseProductPrice['our_price_disp'];
			$SizeWiseProducts_arr[$key]['sale_price_disp'] = $SizeWiseProductPrice['sale_price_disp'];

			//if(isset($SizeWiseProducts_arr[$key]['image_name']) && $SizeWiseProducts_arr[$key]['image_name'] != "")
			//{

			$SizeWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'ZOOM');
			$SizeWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'LARGE');
			$SizeWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'MEDIUM');
			$SizeWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'THUMB');
			$SizeWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'SMALL');
			//}
		}
		//dd($SizeWiseProducts_arr);
		$PrdWishRes = Wishlist::from('hba_wishlist')->select('customer_id','products_id','sku')
		->where('products_id', '=', $Product['product_id'])
		->where('customer_id', '=', Session::get('customer_id'))->limit(1)->get();



		//dd($PrdWishRes[0]['products_id']);
		$SizeWiseArray = $SizeWiseProducts_arr;
		//dd($SizeWiseArray);
		//dd($Product);
		$category_id = $Product['category_id'];
		$category_name = $Product['category_name'];

		if (Cache::has('SimilarProducts' . $product_id . $status_val))
		{
			$SimilarProducts = Cache::get('SimilarProducts' . $product_id . $status_val);
		} else {
			$SimilarProductResult = Product::from('hba_products as p')
			->select('p.product_id','p.sku','p.product_name','p.product_url','p.product_description','c.category_id','p.retail_price','p.our_price','p.sale_price','p.image_name','p.extra_images')
			->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
			->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
			->where('p.status', '=', $status_val)
			->where('c.category_id', '=', $category_id)
			->where('p.current_stock', '>', '0');
			$SimilarProductResult->where('c.status', '=', '1');
			$SimilarProductResult->groupBy('p.product_id');
			$SimilarProducts = $SimilarProductResult->limit(10)->get();

			Cache::put('SimilarProducts' . $product_id . $status_val, $SimilarProducts);
		}




		$SimilarProducts_arr = [];
		foreach ($SimilarProducts as $key => $SimilarProduct)
		{
			$SimilarProducts_arr[$key]['product_id'] = $SimilarProduct['product_id'];
			$SimilarProducts_arr[$key]['sku'] = $SimilarProduct['sku'];
			$SimilarProducts_arr[$key]['product_name'] = $SimilarProduct['product_name'];
			$SimilarProducts_arr[$key]['product_url'] = $SimilarProduct['product_url'];
			$SimilarProducts_arr[$key]['product_description'] = $SimilarProduct['product_description'];
			$SimilarProducts_arr[$key]['category_id'] = $SimilarProduct['category_id'];
			$SimilarProducts_arr[$key]['retail_price'] = $SimilarProduct['retail_price'];
			$SimilarProducts_arr[$key]['our_price'] = $SimilarProduct['our_price'];
			$SimilarProducts_arr[$key]['sale_price'] = $SimilarProduct['sale_price'];
			$SimilarProducts_arr[$key]['image_name'] = $SimilarProduct['image_name'];
			$SimilarProducts_arr[$key]['extra_images'] = $SimilarProduct['extra_images'];

			$SimilarproductPrice = $this->Get_Price_Val($SimilarProduct);
			//dd('ssds',$SimilarproductPrice);
			$SimilarProducts_arr[$key]['retail_price'] = $SimilarproductPrice['retail_price'];
			$SimilarProducts_arr[$key]['our_price'] = $SimilarproductPrice['our_price'];
			$SimilarProducts_arr[$key]['sale_price'] = $SimilarproductPrice['sale_price'];
			$SimilarProducts_arr[$key]['retail_price_disp'] = $SimilarproductPrice['retail_price_disp'];
			$SimilarProducts_arr[$key]['our_price_disp'] = $SimilarproductPrice['our_price_disp'];
			$SimilarProducts_arr[$key]['sale_price_disp'] = $SimilarproductPrice['sale_price_disp'];

			//if(isset($SimilarProducts_arr[$key]['image_name']) && $SimilarProducts_arr[$key]['image_name'] != "")
			//{
				$SimilarProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'ZOOM');
				$SimilarProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'MEDIUM');
				$SimilarProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'LARGE');
				$SimilarProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'THUMB');
				$SimilarProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($SimilarProducts_arr[$key]['image_name'], 'SMALL');
			//}
		}
		//dd($SimilarProducts_arr);
		//dd($SimilarProducts);

		$Product['product_zoom_image'] = Get_Product_Image_URL($Product['image_name'], 'ZOOM');
		$Product['product_medium_image'] = Get_Product_Image_URL($Product['image_name'], 'MEDIUM');
        $Product['product_large_image'] = Get_Product_Image_URL($Product['image_name'], 'LARGE');
        $Product['product_thumb_image'] = Get_Product_Image_URL($Product['image_name'], 'THUMB');
        $Product['product_small_image'] = Get_Product_Image_URL($Product['image_name'], 'SMALL');
		//echo "<pre>"; print_r($Product); exit;
		if(isset($Product['shipping_text']) && $Product['shipping_text'] != "")
		{
			$Product['shipping_text'] = $Product['shipping_text'];
		}
		else
		{
			$today = date('Y-m-d');
			$tomorrow_date = date('M. d', strtotime($today . ' + 1 days'));
			$future_date = date('M. d', strtotime($today . ' + 7 days'));
			//echo $future_date; exit;
			$shipping_text = "Get it as soon as ".$tomorrow_date." - ".$future_date."";
			$Product['shipping_text'] = $shipping_text;
		}
		//echo $Product['shipping_text']; exit;
		$arr_extra_image = array();

		if (trim($Product['product_large_image']) != "") {
			$arr_extra_image[] = array(
				'extra_image_name' => $Product['product_large_image'],
				'extra_zoom_url' => $Product['product_zoom_image'],
				'extra_large_url' => $Product['product_large_image'],
				'extra_medium_url' => $Product['product_medium_image'],
				'extra_thumb_url' => $Product['product_thumb_image'],
				'extra_small_url' => $Product['product_small_image'],
				'video_image_type' => 'image',
			);
		}

		$extra_images = array();
        if (trim($Product['extra_images']) != '') {
            $extra_images = explode('#', $Product['extra_images']);

            if (count($extra_images) > 0) {
                for ($k = 0; $k < count($extra_images); $k++) {
                    $extra_zoom_url = Get_Product_Image_URL($extra_images[$k], 'ZOOM');
                    $extra_large_url = Get_Product_Image_URL($extra_images[$k], 'LARGE');
                    $extra_medium_url = Get_Product_Image_URL($extra_images[$k], 'MEDIUM');
                    $extra_thumb_url = Get_Product_Image_URL($extra_images[$k], 'THUMB');
                    $extra_small_url = Get_Product_Image_URL($extra_images[$k], 'SMALL');

                    if (!preg_match("/noimage/i", $extra_thumb_url) or !preg_match("/noimage/i", $extra_large_url)) {
                        $arr_extra_image[] = array(
                            'extra_image_name' => $extra_images[$k],
                            'extra_zoom_url' => $extra_zoom_url,
                            'extra_large_url' => $extra_large_url,
                            'extra_medium_url' => $extra_medium_url, //show large image
                            'extra_thumb_url' => $extra_thumb_url,
                            'extra_small_url' => $extra_small_url,
                            'video_image_type' => "image",
                        );
                    }

                }
            }
        }
		//echo $Product['video_url']; exit;
		if ($Product['video_url'] != "") {
			if (strpos($Product['video_url'], 'you') !== false) {
				preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $Product['video_url'], $matches);
				if (isset($matches[1]) && strlen($matches[1]) > 0) {
					$arr_extra_image[] = array(
						'extra_image_name' => $matches[1],
						'extra_zoom_url' => $matches[1],
						'extra_large_url' => $matches[1],
						'extra_medium_url' => $matches[1],
						'extra_thumb_url' => '<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
							<use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
						</svg>',
						'video_image_type' => 'mp4',
						'extra_small_url' => $matches[1],
					);

				}
			} else {
				$arr_extra_image[] = array(
					'extra_image_name' => $Product['video_url'],
					'extra_zoom_url' => $Product['video_url'],
					'extra_large_url' => $Product['video_url'],
					'extra_medium_url' => $Product['video_url'],
					'extra_small_url' => $Product['video_url'],
					'extra_thumb_url' => '<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
						<use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
					</svg>',
					'video_image_type' => 'mp4',
				);
			}
		}
		$allDealOFWeekArr = get_deal_of_week_by_sku();
		if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
			
			if(isset($allDealOFWeekArr[$Product['sku']]) && !empty($allDealOFWeekArr[$Product['sku']])){
				$Product['our_price'] = $allDealOFWeekArr[$Product['sku']]->deal_price;
				$Product['sale_price'] = $allDealOFWeekArr[$Product['sku']]->deal_price;
				$Product['deal_description'] = $allDealOFWeekArr[$Product['sku']]->description;
			}	
		}
		//dd($arr_extra_image);
		$productPrice = $this->Get_Price_Val($Product);

		$Product['retail_price'] = $productPrice['retail_price'];
		$Product['our_price'] = $productPrice['our_price'];
		$Product['sale_price'] = $productPrice['sale_price'];
		$Product['retail_price_disp'] = $productPrice['retail_price_disp'];
		$Product['our_price_disp'] = $productPrice['our_price_disp'];
		$Product['sale_price_disp'] = $productPrice['sale_price_disp'];

		//echo config('const.PDF_PATH').$Product->ingredients_pdf;
		//echo config('const.PDF_PATH').$Product->ingredients_pdf; exit;
		$ingredients_pdf = '';
		if (isset($Product->ingredients_pdf) && file_exists(config('const.PDF_PATH').$Product->ingredients_pdf))
		{

			$ingredients_pdf =  stripslashes(config('const.PDF_URL').$Product->ingredients_pdf);
		}
		$Product['ingredients_pdf'] = $ingredients_pdf;
		//echo $Product['ingredients_pdf']; exit;
		###################### BREADCRUMB START ####################
		if (Cache::has('detailBreadcrumb' . $Product['product_id']) && !str_contains(url()->previous(), 'clearance.html')) {
			$bredCrumb_Detail = Cache::get('detailBreadcrumb' . $Product['product_id']);
		} else {
			$bredCrumb_Detail = detailBreadcrumb($Product['product_id'], $Product['category_id'], $Product->parent_category_id);

			Cache::put('detailBreadcrumb' . $Product['product_id'], $bredCrumb_Detail);

			$bredCrumb_Detail = Cache::get('detailBreadcrumb' . $Product['product_id']);
		}
		$productSchemaData = getProductSchema($Product);
		if ($productSchemaData != false) {
			$this->PageData['product_schema'] = $productSchemaData;
		}
		###################### BREADCRUMB END ####################


		###################### METADATA START ####################
		$PageType = 'PD';
		if (Cache::has('metainfo_pagetype_pd')) {
			$MetaInfo = Cache::get('metainfo_pagetype_pd');
		} else {
			$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();
			Cache::put('metainfo_pagetype_pd', $MetaInfo);
		}

		if ($Product->description != '') {
			$Product_description = strip_tags($Product->description);
		} else {
			$Product_description = $Product->product_name;
		}



		if (!empty($Product->meta_title))
		{
			$this->PageData['meta_title'] =  stripslashes($Product->meta_title);
		}
		elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_title))
		{
			$meta_title = str_replace(array('{$product_name}', '{$category_name}'), array($Product->product_name, $Product->category_name), $MetaInfo[0]->meta_title);
			$this->PageData['meta_title'] = stripslashes($meta_title);
		}

		if (!empty($Product->meta_keywords))
		{
			$this->PageData['meta_keywords'] =  stripslashes($Product->meta_keywords);
		}
		elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_keywords))
		{
			$meta_keywords = str_replace(array('{$product_description}', '{$category_description}', '{$category_name}'), array(strip_tags($Product->description), strip_tags($Product->category_description), $Product->category_name), $MetaInfo[0]->meta_keywords);
			$this->PageData['meta_keywords'] = $meta_keywords;
		}

		if (!empty($Product->meta_description))
		{
			$this->PageData['meta_description'] =  stripslashes($Product->meta_description);
		}
		elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_keywords))
		{
			$meta_description = str_replace(array('{$product_description}', '{$category_description}', '{$category_name}'), array(strip_tags($Product->description), strip_tags($Product->category_description), $Product->category_name), $MetaInfo[0]->meta_description);
			$this->PageData['meta_description'] = $meta_description;
		}

		###################### METADATA END ####################


		################ PRODUCT RELATED ITEMS START ###############
        $arr_related_item = array();
        $arr_related_item = $this->getProduct_SimilarItems(trim($Product['related_sku']));
		################ PRODUCT RELATED ITEMS END ###############

		################ PRODUCT RECENT ITEMS START ###############
        $arr_recent_item = array();
        $arr_recent_item = $this->getRecent_ViewedItems($product_sku);
		//dd($arr_recent_item);
		################ PRODUCT RECENT ITEMS END ###############
		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBreadcrumbListSchema($Product['category_id'], $Product);
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################

		$PageBanner_Main = '';
		$this->PageData['JSFILES'] = ['slick.js','detail.js'];
		$this->PageData['Product'] = $Product;
		$this->PageData['product_sku'] = $product_sku;
		$this->PageData['ActiveSize'] =  $Product->size;
		$Wishproducts_id = 0;
		if(isset($PrdWishRes[0]['products_id']) && $PrdWishRes[0]['products_id'] !="")
		{
			$Wishproducts_id = $PrdWishRes[0]['products_id'];
		}
		$this->PageData['Wishproducts_id'] =  $Wishproducts_id;
		$this->PageData['SimilarProducts'] = $SimilarProducts_arr;
		$this->PageData['arr_extra_image'] = $arr_extra_image;
		$this->PageData['SizeWiseArray'] = $SizeWiseArray;
		$this->PageData['related_item'] = $arr_related_item;
		$this->PageData['recent_item'] = $arr_recent_item;
		$this->PageData['bredCrumb_Detail'] = $bredCrumb_Detail;
		$final_home = array();
		return view('product_detail.productdetail', compact('PageBanner_Main','final_home'))->with($this->PageData);
	}*/
	
	public function GetProductDetailView(Request $request)
	{
		//dd('test');
		$status_val = 1;
		if (Session::has('testmode')) {
			$status_val = '1';
		}
		
		$variantAcitveSizeValue = trim($request->variant_acitve_size_Value);
		$variantAcitvePackSizeValue = trim($request->variant_acitve_pack_size_Value);
		$variantAcitveFlavourValue = trim($request->variant_acitve_flavour_Value);


		$CheckProductVariantAcitveSizeValue = trim($request->variant_acitve_size_Value);
		$CheckProductVariantAcitvePackSizeValue = trim($request->variant_acitve_pack_size_Value);
		$CheckProductVariantAcitveFlavourValue = trim($request->variant_acitve_flavour_Value);

		$checkProdutVariantAcitveSizeValue = trim($request->variant_acitve_size_Value);
		$checkProdutVariantAcitvePackSizeValue = trim($request->variant_acitve_pack_size_Value);
		$checkProdutVariantAcitveFlavourValue = trim($request->variant_acitve_flavour_Value);

		$currentSelectedVariantName = trim($request->current_variant_name);
		$currentSelectedValue = trim($request->current_variant_value);

		$product_group_code = trim($request->product_group_code);	

		$flagShowHideVarinat = trim($request->flag_show_hide_varinat);

		$size_check = false;

		$pack_size_check = false; 
		$flavour_check = false; 

		if($currentSelectedVariantName == 'size'){
			$size_check = true;
			$checkProdutVariantAcitveSizeValue = trim($currentSelectedValue);
			$CheckProductVariantAcitveSizeValue = trim($currentSelectedValue);
		}else if($currentSelectedVariantName == 'pack_size'){
			$pack_size_check = true; 
			$checkProdutVariantAcitvePackSizeValue = trim($currentSelectedValue);
			$CheckProductVariantAcitvePackSizeValue = trim($currentSelectedValue);
		}else if($currentSelectedVariantName == 'flavour'){
			$flavour_check = true; 	
			$checkProdutVariantAcitveFlavourValue = trim($currentSelectedValue);
			$CheckProductVariantAcitveFlavourValue = trim($currentSelectedValue);
		}
		$ProductsObj = $this->get_all_productWithCategory();
		
		//Get Product
		
		$getCurrentProductObj = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue) {
			if($product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1 && strtolower($product->size) == strtolower($CheckProductVariantAcitveSizeValue) && strtolower($product->pack_size) == strtolower($CheckProductVariantAcitvePackSizeValue) && strtolower($product->flavour) == strtolower($CheckProductVariantAcitveFlavourValue)){
				return true;
			}else{
				return false;
			}
		});

		//Flavour variant
		$FlavourWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$currentSelectedVariantName,$currentSelectedValue,$flavour_check,$CheckProductVariantAcitveSizeValue, $CheckProductVariantAcitvePackSizeValue, $CheckProductVariantAcitveFlavourValue) {
			if($product->flavour != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
				return true;
			}else{
				return false;
			}
		});
		
		//dd($FlavourWiseProductsSelected);
		if(isset($FlavourWiseProductsSelected) && !empty($FlavourWiseProductsSelected)){
			$FlavourWiseProductsSelected = json_decode(json_encode($FlavourWiseProductsSelected), true);
			$FlavourWiseProductsSelected = array_values($FlavourWiseProductsSelected);
			$FlavourWiseProductsSelectedNotUnique = array_sort($FlavourWiseProductsSelected, 'display_rank', SORT_ASC);
			$FlavourWiseProductsSelected = uniqueArray($FlavourWiseProductsSelectedNotUnique,'flavour');
			
			
			$FlavourWiseProductsSelected_arr = [];
			foreach ($FlavourWiseProductsSelected as $key => $FlavourWiseProduct)
			{
				$FlavourWiseProductsSelected_arr[strtolower(title($FlavourWiseProduct['flavour']))]['product_id'] = $FlavourWiseProduct['product_id'];
				$FlavourWiseProductsSelected_arr[strtolower(title($FlavourWiseProduct['flavour']))]['product_name'] = $FlavourWiseProduct['product_name'];
				$FlavourWiseProductsSelected_arr[strtolower(title($FlavourWiseProduct['flavour']))]['flavour'] = $FlavourWiseProduct['flavour'];
			}
		}
		//dd($FlavourWiseProductsSelected_arr);
		$FlavourWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
			if($product->flavour != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
				return true;
			}else{
				return false;
			}
		})->unique('flavour');
		
		$FlavourWiseProducts = json_decode(json_encode($FlavourWiseProducts), true);
		$FlavourWiseProducts = array_values($FlavourWiseProducts);
	
		$FlavourWiseProducts = uniqueArray($FlavourWiseProducts,'flavour');
		//dd($FlavourWiseProducts);
		$FlavourWiseProducts = array_sort($FlavourWiseProducts, 'display_rank', SORT_ASC);
		
		$FlavourWiseProducts_arr = [];
		foreach ($FlavourWiseProducts as $key => $FlavourWiseProduct)
		{
			$FlavourWiseProducts_arr[$key]['product_id'] = $FlavourWiseProduct['product_id'];
			$FlavourWiseProducts_arr[$key]['sku'] = $FlavourWiseProduct['sku'];
			$FlavourWiseProducts_arr[$key]['product_name'] = $FlavourWiseProduct['product_name'];
			$FlavourWiseProducts_arr[$key]['product_url'] = $FlavourWiseProduct['product_url'];
			$FlavourWiseProducts_arr[$key]['flavour'] = trim($FlavourWiseProduct['flavour']);
			$FlavourWiseProducts_arr[$key]['display_rank'] = $FlavourWiseProduct['display_rank'];
		}
		$FlavourWiseProducts_arr = uniqueArray($FlavourWiseProducts_arr,'flavour');

		/*if(empty($getCurrentProductArr)){

			if($flavour_check == false){
				$FlavourWiseProductsSelectedKey = array_search($CheckProductVariantAcitveFlavourValue, array_column($FlavourWiseProductsSelected, 'flavour'));
				if(empty($FlavourWiseProductsSelectedKey)){
		
					if(count($FlavourWiseProductsSelected)==1){
						$CheckProductVariantAcitveFlavourValue = $FlavourWiseProductsSelectedNotUnique[0]['flavour'];
					}
				}
			}
		
			if($flavour_check == false){
				$FlavourWiseProductsSelectedKey = array_search($CheckProductVariantAcitveFlavourValue, array_column($FlavourWiseProductsSelected, 'flavour'));
				if(!$FlavourWiseProductsSelectedKey){
					if(count($FlavourWiseProductsSelected) > 1){
					
						$FlavourWiseProductsSelectedobj =  json_decode(json_encode($FlavourWiseProductsSelectedNotUnique), false);
						$FlavourWiseProductsSelectedobj = collect($FlavourWiseProductsSelectedobj);
						$checkFlavourWiseProductsSelectedobj = $FlavourWiseProductsSelectedobj->filter(function ($product, $key) use($CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue) {
							if($product->size == $CheckProductVariantAcitveSizeValue && $product->pack_size == $CheckProductVariantAcitvePackSizeValue){
								return true;
							}else{
								return false;
							}
						});
						$checkFlavourWiseProductsSelectedobjArr = json_decode(json_encode($checkFlavourWiseProductsSelectedobj), true);
						if(empty($checkFlavourWiseProductsSelectedobjArr)){
							$checkFlavourWiseProductsSelectedobj = $FlavourWiseProductsSelectedobj;
						}
		
						$checkFlavourWiseProductsSelectedArr = json_decode(json_encode($checkFlavourWiseProductsSelectedobj), true);
						$checkFlavourWiseProductsSelectedArr = array_values($checkFlavourWiseProductsSelectedArr);
						
						$CheckProductVariantAcitveFlavourValue = $checkFlavourWiseProductsSelectedArr[0]['flavour'];
					}
				}
			}
		}*/
		

		//size variant

		$SizeWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$currentSelectedVariantName,$currentSelectedValue,$size_check,$CheckProductVariantAcitveSizeValue, $CheckProductVariantAcitvePackSizeValue, $CheckProductVariantAcitveFlavourValue) {
			if($product->size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1 && strtolower($product->flavour) == strtolower($CheckProductVariantAcitveFlavourValue)){
				return true;
			}else{
				return false;
			}
		})->unique('size');
		// dd($SizeWiseProductsSelected);
		// exit;	
	
		if(isset($SizeWiseProductsSelected) && !empty($SizeWiseProductsSelected)){
			$SizeWiseProductsSelected = json_decode(json_encode($SizeWiseProductsSelected), true);
			$SizeWiseProductsSelected = array_values($SizeWiseProductsSelected);
			$SizeWiseProductsNotUnique = uniqueArray($SizeWiseProductsSelected,'size');
			$SizeWiseProductsSelected = array_sort($SizeWiseProductsNotUnique, 'display_rank', SORT_ASC);
			
			$SizeWiseProductsSelected_arr = [];
			foreach ($SizeWiseProductsSelected as $key => $SizeWiseProduct)
			{
				$SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['product_id'] = $SizeWiseProduct['product_id'];
				$SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['product_name'] = $SizeWiseProduct['product_name'];
				$SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['size'] = $SizeWiseProduct['size'];
			}
		}

		$SizeWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
			if($product->size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
				return true;
			}else{
				return false;
			}
		});
		$SizeWiseProducts = json_decode(json_encode($SizeWiseProducts), true);
		$SizeWiseProducts = array_values($SizeWiseProducts);
		$SizeWiseProducts = array_sort($SizeWiseProducts, 'display_rank', SORT_ASC);
		$SizeWiseProducts = uniqueArray($SizeWiseProducts,'size');
		
		
		$SizeWiseProducts_arr = [];
		foreach ($SizeWiseProducts as $key => $SizeWiseProduct)
		{
			$SizeWiseProducts_arr[$key]['product_id'] = $SizeWiseProduct['product_id'];
			$SizeWiseProducts_arr[$key]['sku'] = $SizeWiseProduct['sku'];
			$SizeWiseProducts_arr[$key]['product_name'] = $SizeWiseProduct['product_name'];
			$SizeWiseProducts_arr[$key]['product_url'] = $SizeWiseProduct['product_url'];
			$SizeWiseProducts_arr[$key]['size'] = $SizeWiseProduct['size'];
			$SizeWiseProducts_arr[$key]['display_rank'] = $SizeWiseProduct['display_rank'];

			$SizeWiseProducts_arr[$key]['image_name'] = $SizeWiseProduct['image_name'];
			$SizeWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'ZOOM');
			$SizeWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'LARGE');
			$SizeWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'MEDIUM');
			$SizeWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'THUMB');
			$SizeWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'SMALL');
		}
		$getCurrentProductArr  = json_decode(json_encode($getCurrentProductObj), true);

		 if(empty($getCurrentProductArr)){

			if($size_check == false){
				$SizeWiseProductsSelectedKey = array_search($CheckProductVariantAcitveSizeValue, array_column($SizeWiseProductsSelected, 'size'));
				if(empty($SizeWiseProductsSelectedKey)){
		
					if(count($SizeWiseProductsSelected)==1){
						$CheckProductVariantAcitveSizeValue = $SizeWiseProductsNotUnique[0]['size'];
					}
				}
		
			}
		
			if($size_check == false){
				$SizeWiseProductsSelectedKey = array_search($CheckProductVariantAcitveSizeValue, array_column($SizeWiseProductsSelected, 'size'));
				if(empty($SizeWiseProductsSelectedKey)){
				
					if(count($SizeWiseProductsSelected)>1){
						$SizeWiseProductsSelectedobj =  json_decode(json_encode($SizeWiseProductsNotUnique), false);
						//dd($SizeWiseProductsSelectedobj);
						$SizeWiseProductsSelectedobj = collect($SizeWiseProductsSelectedobj);
						$checkSizeWiseProductsSelectedobj = $SizeWiseProductsSelectedobj->filter(function ($product, $key) use($CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue) {
							if($product->pack_size == $CheckProductVariantAcitvePackSizeValue && $product->flavour == $CheckProductVariantAcitveFlavourValue){
								return true;
							}else{
								return false;
							}
						});
						//dd($SizeWiseProductsSelectedobj);
						$checkSizeWiseProductsSelectedobjArr = json_decode(json_encode($SizeWiseProductsSelectedobj), true);
					
						if(empty($checkSizeWiseProductsSelectedobjArr)){
							
							$checkSizeWiseProductsSelectedobj = $SizeWiseProductsSelectedobj;
						}
		
						$checkSizeWiseProductsSelectedArr = json_decode(json_encode($SizeWiseProductsSelectedobj), true);
						$checkSizeWiseProductsSelectedArr = array_values	($checkSizeWiseProductsSelectedArr);
						$CheckProductVariantAcitveSizeValue = $checkSizeWiseProductsSelectedArr[0]['size'];
							
					}
				}
		
			}
		} 
		//dd($CheckProductVariantAcitveSizeValue);

		//packsize variant

		$PackSizeWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$currentSelectedVariantName,$currentSelectedValue,$pack_size_check,$CheckProductVariantAcitveSizeValue, $CheckProductVariantAcitvePackSizeValue, $CheckProductVariantAcitveFlavourValue) {
			if($product->pack_size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1 && strtolower($product->size) == strtolower($CheckProductVariantAcitveSizeValue) && strtolower($product->flavour) == strtolower($CheckProductVariantAcitveFlavourValue)   ){
				return true;
			}else{
				return false;
			}
		});
		
	
		if(isset($PackSizeWiseProductsSelected) && !empty($PackSizeWiseProductsSelected)){
			$PackSizeWiseProductsSelected = json_decode(json_encode($PackSizeWiseProductsSelected), true);
			$PackSizeWiseProductsSelected = array_values($PackSizeWiseProductsSelected);
			$PackSizeWiseProductsSelectedNotUnique = array_sort($PackSizeWiseProductsSelected, 'display_rank', SORT_ASC);
			$PackSizeWiseProductsSelected = uniqueArray($PackSizeWiseProductsSelectedNotUnique,'pack_size');
			
			
			$PackSizeWiseProductsSelected_arr = [];
			foreach ($PackSizeWiseProductsSelected as $key => $PackSizeWiseProduct)
			{
				$PackSizeWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['product_id'] = $PackSizeWiseProduct['product_id'];
				$PackSizeWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['product_name'] = $PackSizeWiseProduct['product_name'];
				$PackSizeWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['pack_size'] = $PackSizeWiseProduct['pack_size'];
			}
		}
		//dd($PackSizeWiseProductsSelected);
		$PackSizeWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
			if($product->pack_size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
				return true;
			}else{
				return false;
			}
		})->unique('pack_size');
	
		$PackSizeWiseProducts = json_decode(json_encode($PackSizeWiseProducts), true);
		$PackSizeWiseProducts = array_values($PackSizeWiseProducts);
		$PackSizeWiseProducts = uniqueArray($PackSizeWiseProducts,'pack_size');
		$PackSizeWiseProducts = array_sort($PackSizeWiseProducts, 'display_rank', SORT_ASC);
		
		$PackSizeWiseProducts_arr = [];
		foreach ($PackSizeWiseProducts as $key => $PackSizeWiseProduct)
		{
			$PackSizeWiseProducts_arr[$key]['product_id'] = $PackSizeWiseProduct['product_id'];
			$PackSizeWiseProducts_arr[$key]['sku'] = $PackSizeWiseProduct['sku'];
			$PackSizeWiseProducts_arr[$key]['product_name'] = $PackSizeWiseProduct['product_name'];
			$PackSizeWiseProducts_arr[$key]['product_url'] = $PackSizeWiseProduct['product_url'];
			$PackSizeWiseProducts_arr[$key]['pack_size'] = $PackSizeWiseProduct['pack_size'];
			$PackSizeWiseProducts_arr[$key]['display_rank'] = $PackSizeWiseProduct['display_rank'];

			$PackSizeWiseProducts_arr[$key]['image_name'] = $PackSizeWiseProduct['image_name'];
            $PackSizeWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($PackSizeWiseProducts_arr[$key]['image_name'], 'ZOOM');
			$PackSizeWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($PackSizeWiseProducts_arr[$key]['image_name'], 'LARGE');
			$PackSizeWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($PackSizeWiseProducts_arr[$key]['image_name'], 'MEDIUM');
			$PackSizeWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($PackSizeWiseProducts_arr[$key]['image_name'], 'THUMB');
			$PackSizeWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($PackSizeWiseProducts_arr[$key]['image_name'], 'SMALL');
			
		}

		if(empty($getCurrentProductArr)){

			if($pack_size_check == false){
				$PackSizeWiseProductsSelectedKey = array_search($CheckProductVariantAcitvePackSizeValue, array_column($PackSizeWiseProductsSelected, 'pack_size'));
				if(empty($SizeWiseProductsSelectedKey)){
		
					if(count($PackSizeWiseProductsSelected)==1){
						$CheckProductVariantAcitvePackSizeValue = $PackSizeWiseProductsSelectedNotUnique[0]['pack_size'];
					}
				}
			}
		
			if($pack_size_check == false){
				$PackSizeWiseProductsSelectedKey = array_search($CheckProductVariantAcitvePackSizeValue, array_column($PackSizeWiseProductsSelected, 'pack_size'));
				
				if(!$PackSizeWiseProductsSelectedKey){
					//dd($PackSizeWiseProductsSelectedKey);
					if(count($PackSizeWiseProductsSelected) > 1){
						$PackSizeWiseProductsSelectedobj =  json_decode(json_encode($PackSizeWiseProductsSelectedNotUnique), false);
						$PackSizeWiseProductsSelectedobj = collect($PackSizeWiseProductsSelectedobj);
						
		
						$checkPackSizeWiseProductsSelectedobj = $PackSizeWiseProductsSelectedobj->filter(function ($product, $key) use($CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue) {
							if($product->size == $CheckProductVariantAcitveSizeValue && $product->flavour == $CheckProductVariantAcitveFlavourValue){
								return true;
							}else{
								return false;
							}
						});
						$checkPackSizeWiseProductsSelectedobjArr = json_decode(json_encode($checkPackSizeWiseProductsSelectedobj), true);
						if(empty($checkPackSizeWiseProductsSelectedobjArr)){
							$checkPackSizeWiseProductsSelectedobj = $PackSizeWiseProductsSelectedobj;
						}
		
						$checkPackSizeWiseProductsSelectedArr = json_decode(json_encode($checkPackSizeWiseProductsSelectedobj), true);
						$checkPackSizeWiseProductsSelectedArr = array_values($checkPackSizeWiseProductsSelectedArr);
						$CheckProductVariantAcitvePackSizeValue = $checkPackSizeWiseProductsSelectedArr[0]['pack_size'];
					}
				}
			}
		}  
		//dd($CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue);    
		

		
		$getCurrentProductArr  = json_decode(json_encode($getCurrentProductObj), true);
		if(empty($getCurrentProductArr)){
			// if($size_check == false){
			// 	$SizeWiseProductsSelectedKey = array_search($CheckProductVariantAcitveSizeValue, array_column($SizeWiseProductsSelected, 'size'));
			// 	if(empty($SizeWiseProductsSelectedKey)){

			// 		if(count($SizeWiseProductsSelected)==1){
			// 			$CheckProductVariantAcitveSizeValue = $SizeWiseProductsNotUnique[0]['size'];
			// 		}
			// 	}

			// }
			
			// if($pack_size_check == false){
			// 	$PackSizeWiseProductsSelectedKey = array_search($CheckProductVariantAcitvePackSizeValue, array_column($PackSizeWiseProductsSelected, 'pack_size'));
			// 	if(empty($SizeWiseProductsSelectedKey)){

			// 		if(count($PackSizeWiseProductsSelected)==1){
			// 			$CheckProductVariantAcitvePackSizeValue = $PackSizeWiseProductsSelectedNotUnique[0]['pack_size'];
			// 		}
			// 	}

			// }

			// if($flavour_check == false){
			// 	$FlavourWiseProductsSelectedKey = array_search($CheckProductVariantAcitveFlavourValue, array_column($FlavourWiseProductsSelected, 'flavour'));
			// 	if(empty($FlavourWiseProductsSelectedKey)){

			// 		if(count($FlavourWiseProductsSelected)==1){
			// 			$CheckProductVariantAcitveFlavourValue = $FlavourWiseProductsSelectedNotUnique[0]['flavour'];
			// 		}
			// 	}
			// }

			// if($size_check == false){
			// 	$SizeWiseProductsSelectedKey = array_search($CheckProductVariantAcitveSizeValue, array_column($SizeWiseProductsSelected, 'size'));
			// 	if(empty($SizeWiseProductsSelectedKey)){

			// 		if(count($SizeWiseProductsSelected)>1){
			// 			$SizeWiseProductsSelectedobj =  json_decode(json_encode($SizeWiseProductsNotUnique), false);
			// 			$SizeWiseProductsSelectedobj = collect($SizeWiseProductsSelectedobj);
			// 			$checkSizeWiseProductsSelectedobj = $SizeWiseProductsSelectedobj->filter(function ($product, $key) use($CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue) {
			// 				if($product->pack_size == $CheckProductVariantAcitvePackSizeValue && $product->flavour == $CheckProductVariantAcitveFlavourValue){
			// 					return true;
			// 				}else{
			// 					return false;
			// 				}
			// 			});
			// 			$checkSizeWiseProductsSelectedobjArr = json_decode(json_encode($SizeWiseProductsSelectedobj), true);
			// 			if(empty($checkSizeWiseProductsSelectedobjArr)){
			// 				$checkSizeWiseProductsSelectedobj = $SizeWiseProductsSelectedobj;
			// 			}

			// 			$checkSizeWiseProductsSelectedArr = json_decode(json_encode($checkSizeWiseProductsSelectedobj), true);
			// 			$checkSizeWiseProductsSelectedArr = array_values	($checkSizeWiseProductsSelectedArr);
			// 			$CheckProductVariantAcitveSizeValue = $checkSizeWiseProductsSelectedArr[0]['size'];
							
			// 		}
			// 	}

			// }

			// if($pack_size_check == false){
			// 	$PackSizeWiseProductsSelectedKey = array_search($CheckProductVariantAcitvePackSizeValue, array_column($PackSizeWiseProductsSelected, 'pack_size'));
			// 	if(empty($PackSizeWiseProductsSelectedKey) && $PackSizeWiseProductsSelectedKey!=0){
				
			// 		if(count($PackSizeWiseProductsSelected) > 1){
			// 			$PackSizeWiseProductsSelectedobj =  json_decode(json_encode($PackSizeWiseProductsSelectedNotUnique), false);
			// 			$PackSizeWiseProductsSelectedobj = collect($PackSizeWiseProductsSelectedobj);
						

			// 			$checkPackSizeWiseProductsSelectedobj = $PackSizeWiseProductsSelectedobj->filter(function ($product, $key) use($CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue) {
			// 				if($product->size == $CheckProductVariantAcitveSizeValue && $product->flavour == $CheckProductVariantAcitveFlavourValue){
			// 					return true;
			// 				}else{
			// 					return false;
			// 				}
			// 			});
			// 			$checkPackSizeWiseProductsSelectedobjArr = json_decode(json_encode($checkPackSizeWiseProductsSelectedobj), true);
			// 			if(empty($checkPackSizeWiseProductsSelectedobjArr)){
			// 				$checkPackSizeWiseProductsSelectedobj = $PackSizeWiseProductsSelectedobj;
			// 			}

			// 			$checkPackSizeWiseProductsSelectedArr = json_decode(json_encode($checkPackSizeWiseProductsSelectedobj), true);
			// 			$checkPackSizeWiseProductsSelectedArr = array_values($checkPackSizeWiseProductsSelectedArr);
			// 			$CheckProductVariantAcitvePackSizeValue = $checkPackSizeWiseProductsSelectedArr[0]['pack_size'];
			// 		}
			// 	}

			// }

			// if($flavour_check == false){
				
			// 	$FlavourWiseProductsSelectedKey = array_search($CheckProductVariantAcitveFlavourValue, array_column($FlavourWiseProductsSelected, 'flavour'));
			// 	if(empty($FlavourWiseProductsSelectedKey)){
			// 		dd($FlavourWiseProductsSelected);
			// 		if(count($FlavourWiseProductsSelected) > 1){
					
			// 			$FlavourWiseProductsSelectedobj =  json_decode(json_encode($FlavourWiseProductsSelectedNotUnique), false);
			// 			$FlavourWiseProductsSelectedobj = collect($FlavourWiseProductsSelectedobj);
			// 			//dd($FlavourWiseProductsSelectedobj);
			// 			$checkFlavourWiseProductsSelectedobj = $FlavourWiseProductsSelectedobj->filter(function ($product, $key) use($CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue) {
			// 				// echo $CheckProductVariantAcitvePackSizeValue.'<br/>';
			// 				// echo ($product->size.'=='.$CheckProductVariantAcitveSizeValue.'!!!!'.$product->pack_size.'=='.
			// 				// $CheckProductVariantAcitvePackSizeValue);
			// 				// echo '<br/>';
			// 				if($product->size == $CheckProductVariantAcitveSizeValue && $product->pack_size == $CheckProductVariantAcitvePackSizeValue){
			// 					return true;
			// 				}else{
			// 					return false;
			// 				}
			// 			});
			// 			$checkFlavourWiseProductsSelectedobjArr = json_decode(json_encode($checkFlavourWiseProductsSelectedobj), true);
			// 			if(empty($checkFlavourWiseProductsSelectedobjArr)){
			// 				$checkFlavourWiseProductsSelectedobj = $FlavourWiseProductsSelectedobj;
			// 			}

			// 			$checkFlavourWiseProductsSelectedArr = json_decode(json_encode($checkFlavourWiseProductsSelectedobj), true);
			// 			$checkFlavourWiseProductsSelectedArr = array_values($checkFlavourWiseProductsSelectedArr);
						
			// 			$CheckProductVariantAcitveFlavourValue = $checkFlavourWiseProductsSelectedArr[0]['flavour'];
			// 		}
			// 	}
			// }

		//dd($getCurrentProductObj,$CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue);
			$getCurrentProductObj = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$CheckProductVariantAcitveSizeValue,$CheckProductVariantAcitvePackSizeValue,$CheckProductVariantAcitveFlavourValue) {
				if($product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1 && strtolower($product->size) == strtolower($CheckProductVariantAcitveSizeValue) && strtolower($product->pack_size) == strtolower($CheckProductVariantAcitvePackSizeValue) && strtolower($product->flavour) == strtolower($CheckProductVariantAcitveFlavourValue)){
					return true;
				}else{
					return false;
				}
			});


		}
		$getCurrentProductArr =  json_decode(json_encode($getCurrentProductObj), true);
		$getCurrentProductArr = array_values($getCurrentProductArr);
		//$getCurrentProductObj =  json_decode(json_encode($getCurrentProductArr), false);

		//dd($getCurrentProductArr);
		$Product = $getCurrentProductArr[0];
		
		$SizeWiseArray = $SizeWiseProducts_arr;
		$PackSizeWiseArray = $PackSizeWiseProducts_arr;
		$FlavourWiseArray = $FlavourWiseProducts_arr;
		$SizeSelectedWiseArray = array();
		//if(!$size_check){
			$SizeSelectedWiseArray = $SizeWiseProductsSelected_arr;
		//}
		$PackSizeSelectedWiseArray = array();
		//if(!$pack_size_check){
			$PackSizeSelectedWiseArray = $PackSizeWiseProductsSelected_arr;
		//}	
		$FlavourSelectedWiseArray = array();
		//if(!$flavour_check){
			$FlavourSelectedWiseArray = $FlavourWiseProductsSelected_arr;
		//}

		$productPrice = $this->Get_Price_Val($Product);
		
		$Product['retail_price'] = $productPrice['retail_price'];
		$Product['our_price'] = $productPrice['our_price'];
		$Product['sale_price'] = $productPrice['sale_price'];
		$Product['retail_price_disp'] = $productPrice['retail_price_disp'];
		$Product['our_price_disp'] = $productPrice['our_price_disp'];
		$Product['sale_price_disp'] = $productPrice['sale_price_disp'];

		$Product['product_zoom_image'] = Get_Product_Image_URL($Product['image_name'], 'ZOOM');
		$Product['product_medium_image'] = Get_Product_Image_URL($Product['image_name'], 'MEDIUM');
        $Product['product_large_image'] = Get_Product_Image_URL($Product['image_name'], 'LARGE');
        $Product['product_thumb_image'] = Get_Product_Image_URL($Product['image_name'], 'THUMB');
        $Product['product_small_image'] = Get_Product_Image_URL($Product['image_name'], 'SMALL');

		if(empty($Product['product_url'])){
			$Product['product_url'] = Get_Product_URL($Product['product_id'], $Product['product_name'],'',$Product['parent_category_id'],$Product['category'],$Product['sku'],'');
		}

		if(isset($Product['shipping_text']) && $Product['shipping_text'] != "")
		{
			$Product['shipping_text'] = $Product['shipping_text'];
		}
		else
		{
			$today = date('Y-m-d');
			$tomorrow_date = date('M. d', strtotime($today . ' + 1 days'));
			$future_date = date('M. d', strtotime($today . ' + 7 days'));
			//echo $future_date; exit;
			$shipping_text = "Get it as soon as ".$tomorrow_date." - ".$future_date."";
			$Product['shipping_text'] = $shipping_text;
		}

		$arr_extra_image = array();

		if (trim($Product['product_large_image']) != "") {
			$arr_extra_image[] = array(
				'extra_image_name' => $Product['product_large_image'],
				'extra_zoom_url' => $Product['product_zoom_image'],
				'extra_large_url' => $Product['product_large_image'],
				'extra_medium_url' => $Product['product_medium_image'],
				'extra_thumb_url' => $Product['product_thumb_image'],
				'extra_small_url' => $Product['product_small_image'],
				'video_image_type' => 'image',
			);
		}
		
		
		$extra_images = array();
		if (trim($Product['extra_images']) != '') {
            $extra_images = explode('#', $Product['extra_images']);

            if (count($extra_images) > 0) {
                for ($k = 0; $k < count($extra_images); $k++) {
                    $extra_zoom_url = Get_Product_Image_URL($extra_images[$k], 'ZOOM');
                    $extra_large_url = Get_Product_Image_URL($extra_images[$k], 'LARGE');
                    $extra_medium_url = Get_Product_Image_URL($extra_images[$k], 'MEDIUM');
                    $extra_thumb_url = Get_Product_Image_URL($extra_images[$k], 'THUMB');
                    $extra_small_url = Get_Product_Image_URL($extra_images[$k], 'SMALL');

                    if (!preg_match("/noimage/i", $extra_thumb_url) or !preg_match("/noimage/i", $extra_large_url)) {
                        $arr_extra_image[] = array(
                            'extra_image_name' => $extra_images[$k],
                            'extra_zoom_url' => $extra_zoom_url,
                            'extra_large_url' => $extra_large_url,
                            'extra_medium_url' => $extra_medium_url, //show large image
                            'extra_thumb_url' => $extra_thumb_url,
                            'extra_small_url' => $extra_small_url,
                            'video_image_type' => "image",
                        );
                    }

                }
            }
        }

		if ($Product['video_url'] != "") 
		{
			if (strpos($Product['video_url'], 'you') !== false){
					preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $Product['video_url'], $matches);
					if (isset($matches[1]) && strlen($matches[1]) > 0) {
						$arr_extra_image[] = array(
							'extra_image_name' => $matches[1],
							'extra_zoom_url' => $matches[1],
							'extra_large_url' => $matches[1],
							'extra_medium_url' => $matches[1],
							'extra_thumb_url' => '<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
								<use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
							</svg>',
							'video_image_type' => 'mp4',
							'extra_small_url' => $matches[1],
						);

					}
			} else {
				$arr_extra_image[] = array(
					'extra_image_name' => $Product['video_url'],
					'extra_zoom_url' => $Product['video_url'],
					'extra_large_url' => $Product['video_url'],
					'extra_medium_url' => $Product['video_url'],
					'extra_small_url' => $Product['video_url'],
					'extra_thumb_url' => '<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
						<use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
					</svg>',
					'video_image_type' => 'mp4',
				);
			}
		}
	
		$allDealOFWeekArr = get_deal_of_week_by_sku();
		if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
			
			if(isset($allDealOFWeekArr[$Product['sku']]) && !empty($allDealOFWeekArr[$Product['sku']])){
				$Product['our_price'] = $allDealOFWeekArr[$Product['sku']]->deal_price;
				$Product['sale_price'] = $allDealOFWeekArr[$Product['sku']]->deal_price;
				$Product['deal_description'] = $allDealOFWeekArr[$Product['sku']]->description;
			}	
		}

		if(empty($Product['product_url'])){
			$Product['product_url'] = Get_Product_URL($Product['product_id'], $Product['product_name'],'',$Product['parent_category_id'],$Product['category'],$Product['sku'],'');
		}

		$ingredients_pdf = '';
		if (isset($Product->ingredients_pdf) && file_exists(config('const.PDF_PATH').$Product->ingredients_pdf))
		{

			$ingredients_pdf =  stripslashes(config('const.PDF_URL').$Product['ingredients_pdf']);
		}
		$Product['ingredients_pdf'] = $ingredients_pdf;
		
		###################### BREADCRUMB START ####################
		$bredCrumb_Detail = detailBreadcrumb($Product['product_id'], $Product['category_id'], $Product['parent_category_id'],$Product);
		###################### BREADCRUMB END ####################



			###################### METADATA START ####################
			$PageType = 'PD';
			if (Cache::has('metainfo_pagetype_pd')) {
				$MetaInfo = Cache::get('metainfo_pagetype_pd');
			} else {
				$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();
				Cache::put('metainfo_pagetype_pd', $MetaInfo);
			}
	
			if ($Product['product_description'] != '') {
				$Product_description = strip_tags($Product['product_description']);
			} else {
				$Product_description = $Product['product_name'];
			}
	
	
	
			if (!empty($Product->meta_title))
			{
				$this->PageData['meta_title'] =  stripslashes($Product->meta_title);
			}
			elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_title))
			{
				$meta_title = str_replace(array('{$product_name}', '{$category_name}'), array($Product['product_name'], $Product['category_name']), $MetaInfo[0]->meta_title);
				$this->PageData['meta_title'] = stripslashes($meta_title);
			}
	
			if (!empty($Product->meta_keywords))
			{
				$this->PageData['meta_keywords'] =  stripslashes($Product->meta_keywords);
			}
			elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_keywords))
			{
				$meta_keywords = str_replace(array('{$product_description}', '{$category_description}', '{$category_name}'), array(strip_tags($Product['product_description']), strip_tags($Product['category_description']), $Product['product_description']), $MetaInfo[0]->meta_keywords);
				$this->PageData['meta_keywords'] = $meta_keywords;
			}
	
			if (!empty($Product->meta_description))
			{
				$this->PageData['meta_description'] =  stripslashes($Product->meta_description);
			}
			elseif ($MetaInfo->count() > 0 && !empty($MetaInfo[0]->meta_keywords))
			{
				$meta_description = str_replace(array('{$product_description}', '{$category_description}', '{$category_name}'), array(strip_tags($Product['product_description']), strip_tags($Product['category_description']), $Product['category_name']), $MetaInfo[0]->meta_description);
				$this->PageData['meta_description'] = $meta_description;
			}
	
			###################### METADATA END ####################
		
		if(isset($flagShowHideVarinat) && !empty($flagShowHideVarinat)){
			if($flagShowHideVarinat=='true'){
				$checkShowMoreDynamicClass = 'showhide-variant-box-js hidden-lg-down';
				$flagShowHideVarinat  = 'showhide-variant-box-js hidden-lg-down';
			}elseif($flagShowHideVarinat=='false'){
				$checkShowMoreDynamicClass = 'showhide-variant-box-js';
				$flagShowHideVarinat  = '';
			}else{
				$checkShowMoreDynamicClass = 'showhide-variant-box-js hidden-lg-down';
				$flagShowHideVarinat  = 'showhide-variant-box-js hidden-lg-down';
			}
		}else{
			$checkShowMoreDynamicClass = 'showhide-variant-box-js hidden-lg-down';
			$flagShowHideVarinat  = 'showhide-variant-box-js hidden-lg-down';
		}

		$getAllCurrencyObj = getCurrencyArray();
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
		}else{
			$curencySymbol = '$';
		}
		$this->PageData['CurencySymbol'] = $curencySymbol;
		$this->PageData['Product'] = $Product;
		$this->PageData['arr_extra_image'] = $arr_extra_image;
		$this->PageData['ActiveSize'] =  $Product['size'];
		$this->PageData['ActivePackSize'] =  $Product['pack_size'];
		$this->PageData['ActiveFlavour'] =  $Product['flavour'];
		$this->PageData['SizeWiseArray'] = $SizeWiseArray;
		$this->PageData['PackSizeWiseArray'] = $PackSizeWiseArray;
		$this->PageData['FlavourWiseArray'] = $FlavourWiseArray;
		$this->PageData['SizeSelectedWiseArray'] = $SizeSelectedWiseArray;
		$this->PageData['PackSizeSelectedWiseArray'] = $PackSizeSelectedWiseArray;
		$this->PageData['FlavourSelectedWiseArray'] = $FlavourSelectedWiseArray;
		$this->PageData['checkShowMoreDynamicClass'] = $checkShowMoreDynamicClass;
		$this->PageData['flagShowHideVarinat'] = $flagShowHideVarinat;
		$this->PageData['bredCrumb_Detail'] = $bredCrumb_Detail;
		$Wishproducts_id = 0;
		if(isset($PrdWishRes[0]['products_id']) && $PrdWishRes[0]['products_id'] !="")
		{
			$Wishproducts_id = $PrdWishRes[0]['products_id'];
		}
		$this->PageData['Wishproducts_id'] =  $Wishproducts_id;
		$productdetail_right_variant_size =  view('product_detail.details.productdetail_right_variant_size')->with($this->PageData)->render();
		$productdetail_right_variant_pack_size =  view('product_detail.details.productdetail_right_variant_pack_size')->with($this->PageData)->render();
		$productdetail_right_variant_flavour =  view('product_detail.details.productdetail_right_variant_flavour')->with($this->PageData)->render();
		$productdetail_right_desc_section =  view('product_detail.details.productdetail_right_desc')->with($this->PageData)->render();
		$product_left_image_section =   view('product_detail.details.productdetail_leftimage')->with($this->PageData)->render();
		$product_review_section =  view('product_detail.details.productdetail_review')->with($this->PageData)->render();
		$quickview_left_image_section =   view('popup.quickview.quickview_left_image')->with($this->PageData)->render();
		$quickview_right_image_section =   view('popup.quickview.quickview_right_desc')->with($this->PageData)->render();
		

		$array = array(
			'productdetail_right_variant_flavour' => mb_convert_encoding($productdetail_right_variant_flavour, 'UTF-8', 'UTF-8'),
			'productdetail_right_variant_pack_size' => mb_convert_encoding($productdetail_right_variant_pack_size, 'UTF-8', 'UTF-8'),
			'productdetail_right_variant_size' => mb_convert_encoding($productdetail_right_variant_size, 'UTF-8', 'UTF-8'),
			'productdetail_right_desc_section' => mb_convert_encoding($productdetail_right_desc_section, 'UTF-8', 'UTF-8'),
			'product_left_image_section' => mb_convert_encoding($product_left_image_section, 'UTF-8', 'UTF-8'),
			'quickview_right_image_section' => mb_convert_encoding($quickview_right_image_section, 'UTF-8', 'UTF-8'),
			'quickview_left_image_section' => mb_convert_encoding($quickview_left_image_section, 'UTF-8', 'UTF-8'),
			'product_review_section' => mb_convert_encoding($product_review_section, 'UTF-8', 'UTF-8'),
			'product_url' => mb_convert_encoding($Product['product_url'], 'UTF-8', 'UTF-8'),
			'meta_title' => mb_convert_encoding($this->PageData['meta_title'], 'UTF-8', 'UTF-8'),
			'meta_description' => mb_convert_encoding($this->PageData['meta_description'], 'UTF-8', 'UTF-8'),
			'meta_keywords' => mb_convert_encoding($this->PageData['meta_keywords'], 'UTF-8', 'UTF-8'),
			'bredCrumb_Detail' => $bredCrumb_Detail,
		);
		return response()->json($array);
	}
}
