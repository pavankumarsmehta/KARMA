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
		$ProductResult = $ProductsObj->filter(function ($product, $key) use($product_sku,$status_val) {
			
			if($product->sku == $product_sku && $product->status==$status_val && $product->current_stock > 0 && $product->category_status==1){
				return true;
			}else{
				return false;
			}
		})->unique('product_id');
		$ProductResult = json_decode(json_encode($ProductResult), true);
			$ProductResult = array_values($ProductResult);
			$ProductResult = uniqueArray($ProductResult,'product_id');
		if (isset($ProductResult) && empty($ProductResult)) {
			return abort(404);
		}

		$Product = $ProductResult[0];
		$product_id = $Product['product_id'];

		//echo $product_sku; exit;
		if ($product_sku == '' || empty($product_sku)) {
			return abort(404);
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
				if($product->sku == $product_sku && $product->status==$status_val && $product->current_stock > 0 && $product->category_status==1){
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


			$SizeWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
				if($product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
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

		$PrdWishRes = $this->get_all_wishlist();
		if(isset($PrdWishRes[$Product['product_id']]) && !empty($PrdWishRes[$Product['product_id']])){
			$PrdWishRes = $PrdWishRes[$Product['product_id']];
		}
		//dd($PrdWishRes[0]['products_id']);
		$SizeWiseArray = $SizeWiseProducts_arr;
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
				if($product->category_id == $category_id && $product->status==$status_val && $product->category_status==1 && $product->current_stock > 0){
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
		//dd($Product);
		$PageBanner_Main = '';
		$this->PageData['JSFILES'] = ['slick.js','detail.js'];
		$this->PageData['Product'] = $Product;
		$this->PageData['product_sku'] = $product_sku;
		$this->PageData['ActiveSize'] =  $Product['size'];
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
}
