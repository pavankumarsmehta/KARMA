<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthorizeFundLog;
use Hash;
use DB;
use Session;
use Cache;
use App\Models\MetaInfo;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Traits\generalTrait;
#use App\Http\Controllers\Traits\productListingTrait;





class SearchController extends Controller
{
	#use productListingTrait;
	#use generalTrait;
	use generalTrait;
	public $PageData;

	public function __construct()
	{
	}
	public function index(Request $request)
	{
	  try { 
		$keyword = $_REQUEST["query"];
		$PAGE_LIMIT = $this->product_listing_page_limit();
		//$page = '1';
		
		// new Filters 
		$flagGetCategoryOnLoad = false;
		$flagprice = false;
		if(isset($request['product_type']) && $request['product_type'] != ""){
			$select_arr['product_type'] = explode(",", $request['product_type']);	
			
		}else{
			$select_arr['product_type']  = [];
		}
		if(isset($request['categories']) && $request['categories'] != ""){
			$select_arr['categories'] = explode(",", $request['categories']);	
			
		}else{
			$select_arr['categories']  = [];
		}

		if(isset($request['brand']) && $request['brand'] != ""){
			$select_arr['brand'] = explode(",", $request['brand']);	
			
		}else{
			$select_arr['brand']  = [];
		}

		if(isset($request['gender']) && $request['gender'] != ""){
			$select_arr['gender']  = explode(",", $request['gender']);	
		
		}else{
			$select_arr['gender'] = [];
		}

		if(isset($request['size']) && $request['size'] != ""){
			$select_arr['size'] = explode(",", $request['size']);	
			
		}else{
			$select_arr['size'] = [];
		}
		if(isset($request['flavour']) && $request['flavour'] != ""){
			$select_arr['flavour'] = explode(",", $request['flavour']);	
			
		}else{
			$select_arr['size'] = [];
		}

		if(isset($request['price_range']) && $request['price_range'] != ""){
			$select_arr['price_range'] = explode(",", $request['price_range']);	
			
			
		}else{
			$select_arr['price_range'] = [];
		}
		if(isset($request['sortby']) && $request['sortby'] != ""){
			$select_arr['sortby'] = $request['sortby'];
			$sortby = $request['sortby'];
		
		}else{
			$select_arr['sortby'] = '';
			$sortby = "";
		}
		
		if(isset($request['page']) && $request['page'] != ""){
			$request['page'] = $request['page'];
			$page = $request['page'];
		
		}else{
			$request['page'] = '1';
			$page = 1;
		}

		if(isset($request['itemperpage']) && $request['itemperpage'] != ""){
			$PAGE_LIMIT = $request['itemperpage'];	
			$request['itemperpage'] = $PAGE_LIMIT;
			
		}else{
			//$PAGE_LIMIT = '48';
			$request['itemperpage'] = $PAGE_LIMIT;
		}
		$getfilter = $this->getSearchProductFilter($select_arr);
		
		$request['filter'] = $getfilter;
		
		$this->PageData['CSSFILES'] = ['search.css','listing.css'];
		$this->PageData['JSFILES'] = ['search.js','listing.js'];	
		$this->PageData['meta_title'] = "Search Page - ". config('const.SITE_NAME');
		$this->PageData['meta_description'] = "Search Page - ". config('const.SITE_NAME');
		$this->PageData['meta_keywords'] = "Search Page - ". config('const.SITE_NAME');
		$resultArr = $this->getDataDoofinderApi($request);
		
		if(!$resultArr){
			report('doofinder api not call');
			return abort(404);		
		}

		$ProductsDetails['Products'] = array();
		if(isset($resultArr['results']) && !empty($resultArr['results'])){
			$ProductsDetails1['LeftFilters'] = array();
			$filter = array();
			$filterFacetsCount = 0;
			foreach($resultArr['facets'] as $facets_key => $facetsvalue){
				
					if(isset($facetsvalue['terms'])){
						$filter[$filterFacetsCount][$facets_key]['Attr'] = array('title' => $facetsvalue['label'], "id" => $facets_key,"filterval" => "key");
						
						$filter[$filterFacetsCount][$facets_key]['Selected'] = (isset($select_arr[$facets_key])) ? $select_arr[$facets_key] : [];
						$filter[$filterFacetsCount][$facets_key]['Order'] = $filterFacetsCount;
						
						if(isset($facetsvalue['terms']['buckets']) && !empty($facetsvalue['terms']['buckets'])){
							$facetsvalue['terms']['buckets'] =  array_sort($facetsvalue['terms']['buckets'], 'key', SORT_ASC);
							foreach($facetsvalue['terms']['buckets'] as $term_buckets_key => $term_buckets_value){
								$filter[$filterFacetsCount][$facets_key]['Data'][$term_buckets_value['key']] = $term_buckets_value['key'];
							}
						}else{
							$filter[$filterFacetsCount][$facets_key]['Data'] = [];
						}
					}
					$filterFacetsCount++;
				
				}
			
			
			if(!empty($filter))	{
				$ProductsDetails['LeftFilters'] = $filter; 
			
			}

			if(isset($resultArr['results']) && !empty($resultArr['results'])){
				foreach($resultArr['results'] as $resultArrKey => $resultArrValue){
					$ProductsDetails['Products'][$resultArrKey]['product_name'] = $resultArrValue['title'];	
					$ProductsDetails['Products'][$resultArrKey]['sku'] = ltrim($resultArrValue['id'],"H-");
					$ProductsDetails['Products'][$resultArrKey]['product_url'] = ($resultArrValue['link'])? $resultArrValue['link'] : '';
					$ProductsDetails['Products'][$resultArrKey]['product_id'] = $resultArrValue['product_id'];
					
					$arr_image_info = pathinfo($resultArrValue['image_link']);
					 
					 $extension = (isset($arr_image_info['extension'])) ? strtolower($arr_image_info['extension']) : '';
					$new_file_name = basename($arr_image_info['basename']);
					$new_image_name = $new_file_name;
					$resultArrValue['image_link'] = Get_Product_Image_URL($new_image_name, 'THUMB');
					$ProductsDetails['Products'][$resultArrKey]['image_url'] = $resultArrValue['image_link'];
					$resultArrValue['our_price'] = $resultArrValue['best_price'];
					$allDealOFWeekArr = get_deal_of_week_by_sku();
					if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
						
						if(isset($allDealOFWeekArr[$resultArrValue['id']]) && !empty($resultArrValue['id'])){
							$resultArrValue['our_price'] = $allDealOFWeekArr[$resultArrValue['id']]->deal_price;
							$resultArrValue['sale_price'] = $allDealOFWeekArr[$resultArrValue['id']]->deal_price;
							$resultArrValue['deal_description'] = $allDealOFWeekArr[$resultArrValue['id']]->description;
						}	
					}
					$ProductsDetails['Products'][$resultArrKey]['price_arr'] = $this->Get_Price_Val($resultArrValue);
				}
			}
		}else{
			
			$request['query_name'] = 'fuzzy';
			$resultArr = $this->getDataDoofinderApi($request);
			if(!$resultArr){
				report('doofinder api not call');
				return abort(404);		
			}


			$ProductsDetails1['LeftFilters'] = array();
			$filter = array();
			$filterFacetsCount = 0;
			foreach($resultArr['facets'] as $facets_key => $facetsvalue){
				
					if(isset($facetsvalue['terms'])){
						$filter[$filterFacetsCount][$facets_key]['Attr'] = array('title' => $facetsvalue['label'], "id" => $facets_key,"filterval" => "key");
						
						$filter[$filterFacetsCount][$facets_key]['Selected'] = $select_arr[$facets_key];
						$filter[$filterFacetsCount][$facets_key]['Order'] = $filterFacetsCount;
						
						if(isset($facetsvalue['terms']['buckets']) && !empty($facetsvalue['terms']['buckets'])){
							foreach($facetsvalue['terms']['buckets'] as $term_buckets_key => $term_buckets_value){
								$filter[$filterFacetsCount][$facets_key]['Data'][$term_buckets_value['key']] = $term_buckets_value['key'];
							}
						}else{
							$filter[$filterFacetsCount][$facets_key]['Data'] = [];
						}
					}
					$filterFacetsCount++;
				
				}
			
			
			if(!empty($filter))	{
				$ProductsDetails['LeftFilters'] = $filter; 
			
			}

			if(isset($resultArr['results']) && !empty($resultArr['results'])){
				foreach($resultArr['results'] as $resultArrKey => $resultArrValue){
					$ProductsDetails['Products'][$resultArrKey]['product_name'] = $resultArrValue['title'];	
					$ProductsDetails['Products'][$resultArrKey]['sku'] = ltrim($resultArrValue['id'],"H-");
					$ProductsDetails['Products'][$resultArrKey]['product_url'] = ($resultArrValue['link'])? $resultArrValue['link'] : '';
					$ProductsDetails['Products'][$resultArrKey]['product_id'] = $resultArrValue['product_id'];
					
					$arr_image_info = pathinfo($resultArrValue['image_link']);
					 
					 $extension = (isset($arr_image_info['extension'])) ? strtolower($arr_image_info['extension']) : '';
					$new_file_name = basename($arr_image_info['basename']);
					$new_image_name = $new_file_name;
					$resultArrValue['image_link'] = Get_Product_Image_URL($new_image_name, 'THUMB');
					$ProductsDetails['Products'][$resultArrKey]['image_url'] = $resultArrValue['image_link'];
					$resultArrValue['our_price'] = $resultArrValue['best_price'];
					$allDealOFWeekArr = get_deal_of_week_by_sku();
					if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
						
						if(isset($allDealOFWeekArr[$resultArrValue['id']]) && !empty($resultArrValue['id'])){
							$resultArrValue['our_price'] = $allDealOFWeekArr[$resultArrValue['id']]->deal_price;
							$resultArrValue['sale_price'] = $allDealOFWeekArr[$resultArrValue['id']]->deal_price;
							$resultArrValue['deal_description'] = $allDealOFWeekArr[$resultArrValue['id']]->description;
						}	
					}
					$ProductsDetails['Products'][$resultArrKey]['price_arr'] = $this->Get_Price_Val($resultArrValue);
				}
			}
		}
		
		$TotalProducts = $resultArr['total_found'];
		if(($PAGE_LIMIT * $page) >= $TotalProducts) {
			$NEW_PAGE_LIMIT = $TotalProducts;
		}else{
			$NEW_PAGE_LIMIT = $PAGE_LIMIT;
		}
		
		$Products = $ProductsDetails['Products'];
		$this->PageData['PageTitle'] = 'Search : '.ucwords(strtolower($keyword));
		$this->PageData['Products'] = $Products;
		$this->PageData['ProductListingType']= 'SearchProductListing';
		$this->PageData['Filters'] = $ProductsDetails['LeftFilters'];
		$this->PageData['page']=1;
		$this->PageData['Bredcrum'] = '';
		$this->PageData['ProductListingType']= 'SearchProductPage';
		$this->PageData['Pagelimit']=$PAGE_LIMIT;
		
		$this->PageData['resultArr'] = $resultArr;	
		$this->PageData['Pagelimit']=$PAGE_LIMIT;
		$this->PageData['SelSort']= $sortby;
		$this->PageData['page']=$page;
		$this->PageData['NEW_PAGE_LIMIT']=$NEW_PAGE_LIMIT;
		$this->PageData['TotalProducts'] = $TotalProducts;
		return view('search.productlist')->with($this->PageData);
	} catch(\Exception $e){
		$keyword = $_REQUEST["query"];
	
		// report($e);
		// return abort(404);	
		$this->PageData['PageTitle'] = 'Search : '.ucwords(strtolower($keyword));
		$this->PageData['Products'] = [];
		$this->PageData['ProductListingType']= 'SearchProductListing';
		$this->PageData['Filters'] = [];
		$this->PageData['page']=1;
		$this->PageData['Bredcrum'] = '';
		$this->PageData['ProductListingType']= 'SearchProductPage';
		$this->PageData['Pagelimit']=0;
		
		$this->PageData['resultArr'] = [];	
		$this->PageData['Pagelimit']=0;
		$this->PageData['SelSort']= '';
		$this->PageData['page']=1;
		$this->PageData['NEW_PAGE_LIMIT']=0;
		$this->PageData['TotalProducts'] = 0;
		return view('search.productlist')->with($this->PageData);	
	 } 		
 }
 public function getSearchProductListingAjax(Request $request){
	try {

		$productListingType =  $request->product_listing_type;
		$Filters = json_decode($request->filters,true);
		$getfilter = $this->getSearchProductFilter($Filters);
		
		$request['filter'] = $getfilter;
		$searchparam = $request->searchparam;
		$request['query'] = $searchparam;

		$Filters['page'] = $request->page;
		$page = $request->page;
		$itemperpage =  $request->itemperpage;
		$PAGE_LIMIT = $this->product_listing_page_limit($itemperpage);
		$request['itemperpage'] = $itemperpage;
		$resultArr = $this->getDataDoofinderApi($request);
	
		
		if(isset($resultArr['results']) && !empty($resultArr['results'])){
			foreach($resultArr['results'] as $resultArrKey => $resultArrValue){
			
				$ProductsDetails['Products'][$resultArrKey]['product_name'] = $resultArrValue['title'];	
				$ProductsDetails['Products'][$resultArrKey]['sku'] = ltrim($resultArrValue['id'],"H-");
				$ProductsDetails['Products'][$resultArrKey]['product_url'] = ($resultArrValue['link'])? $resultArrValue['link'] : '';
				$ProductsDetails['Products'][$resultArrKey]['product_id'] = $resultArrValue['product_id'];
				
				$arr_image_info = pathinfo($resultArrValue['image_link']);
				 
				 $extension = (isset($arr_image_info['extension'])) ? strtolower($arr_image_info['extension']) : '';
				$new_file_name = basename($arr_image_info['basename']);
				$new_image_name = $new_file_name;
				$resultArrValue['image_link'] = Get_Product_Image_URL($new_image_name, 'THUMB');
				$ProductsDetails['Products'][$resultArrKey]['image_url'] = $resultArrValue['image_link'];
				$resultArrValue['our_price'] = $resultArrValue['best_price'];
				$allDealOFWeekArr = get_deal_of_week_by_sku();
				if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
					
					if(isset($allDealOFWeekArr[$resultArrValue['id']]) && !empty($resultArrValue['id'])){
						$resultArrValue['our_price'] = $allDealOFWeekArr[$resultArrValue['id']]->deal_price;
						$resultArrValue['sale_price'] = $allDealOFWeekArr[$resultArrValue['id']]->deal_price;
						$resultArrValue['deal_description'] = $allDealOFWeekArr[$resultArrValue['id']]->description;
					}	
				}
				$ProductsDetails['Products'][$resultArrKey]['price_arr'] = $this->Get_Price_Val($resultArrValue);
			}
		}else{
			$request['query_name'] = 'fuzzy';
			$resultArr = $this->getDataDoofinderApi($request);
			foreach($resultArr['results'] as $resultArrKey => $resultArrValue){
				$ProductsDetails['Products'][$resultArrKey]['product_name'] = $resultArrValue['title'];	
				$ProductsDetails['Products'][$resultArrKey]['sku'] = $resultArrValue['id'];
				$ProductsDetails['Products'][$resultArrKey]['product_url'] = ($resultArrValue['link'])? $resultArrValue['link'] : '';
				$ProductsDetails['Products'][$resultArrKey]['product_id'] = $resultArrValue['product_id'];
				
				$arr_image_info = pathinfo($resultArrValue['image_link']);
				 
				 $extension = (isset($arr_image_info['extension'])) ? strtolower($arr_image_info['extension']) : '';
				$new_file_name = basename($arr_image_info['basename']);
				$new_image_name = $new_file_name;
				$resultArrValue['image_link'] = Get_Product_Image_URL($new_image_name, 'THUMB');
				$ProductsDetails['Products'][$resultArrKey]['image_url'] = $resultArrValue['image_link'];
				$resultArrValue['our_price'] = $resultArrValue['best_price'];
				$allDealOFWeekArr = get_deal_of_week_by_sku();
				if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
					
					if(isset($allDealOFWeekArr[$resultArrValue['id']]) && !empty($resultArrValue['id'])){
						$resultArrValue['our_price'] = $allDealOFWeekArr[$resultArrValue['id']]->deal_price;
						$resultArrValue['sale_price'] = $allDealOFWeekArr[$resultArrValue['id']]->deal_price;
						$resultArrValue['deal_description'] = $allDealOFWeekArr[$resultArrValue['id']]->description;
					}	
				}
				$ProductsDetails['Products'][$resultArrKey]['price_arr'] = $this->Get_Price_Val($resultArrValue);
			}
		}
		$TotalProducts = $resultArr['total_found'];
		if(($PAGE_LIMIT * $page) >= $TotalProducts) {
			$NEW_PAGE_LIMIT = $TotalProducts;
		}else{
			$NEW_PAGE_LIMIT = $PAGE_LIMIT;
		}

		$Products = $ProductsDetails['Products'];
		
		$this->PageData['Products'] = $Products;
		$this->PageData['ProductListingType'] = $productListingType;
		$ProductHTML = view('search.productlistajax')->with($this->PageData)->render();
		//return view('product.list')->with($this->PageData);
		return response()->json(array('TotalProducts' => $TotalProducts, 'ProductHTML'=>$ProductHTML));

	} catch(\Exception $e){
		return false;	
 	} 		
 }
 public function getSearchProductFilter($filter){
	$joinfilter = '';
	foreach($filter as $filterKey => $filterValue){
		
		if(in_array($filterKey,['categories','brand','size','gender','product_type','price_range','flavour'])){
			foreach($filterValue as $filterValueKey => $filterValueValue){
				if(!empty($joinfilter)){
					$joinfilter.='&'.rawurlencode("filter[$filterKey][]=$filterValueValue");
				}else{
					$joinfilter.=rawurlencode("filter[$filterKey][]=$filterValueValue");
				}
			}
		}elseif($filterKey=='sortby'){
			if($filterValue=='PLTH'){
				if(!empty($joinfilter)){
					$joinfilter.='&'.rawurlencode("sort[][best_price]=asc");
				}else{
					$joinfilter.=rawurlencode("sort[][best_price]=asc");
				}	
			}elseif($filterValue=='PHTL'){
				if(!empty($joinfilter)){
					$joinfilter.='&'.rawurlencode("sort[][best_price]=desc");
				}else{
					$joinfilter.=rawurlencode("sort[][best_price]=desc");
				}	

			}elseif($filterValue=='AZ'){
				if(!empty($joinfilter)){
					$joinfilter.='&'.rawurlencode("sort[][title]=asc");
				}else{
					$joinfilter.=rawurlencode("sort[][title]=asc");
				}	

			}elseif($filterValue=='ZA'){
				if(!empty($joinfilter)){
					$joinfilter.='&'.rawurlencode("sort[][title]=desc");
				}else{
					$joinfilter.=rawurlencode("sort[][title]=desc");
				}	

			}elseif($filterValue=='newest'){
				if(!empty($joinfilter)){
					$joinfilter.='&'.rawurlencode("sort[][product_id]=desc");
				}else{
					$joinfilter.=rawurlencode("sort[][product_id]=desc");
				}	
			}
		}
	}
	return $joinfilter;
 }
 public function getDataDoofinderApi(Request $request){
	try {

		$doofinder_api_url =   env('DOOFINDER_API_URL');
		$doofinder_api_key =   env('DOOFINDER_API_KEY');
		$doofinder_hashid =   env('DOOFINDER_HASHID_ID');
		 $itemperpage = $request['itemperpage'];
	
		if(isset($request['query']) && !empty($request['query'])){
			$keyword = $request['query'];
		}else{
			return false;
		}
		if(isset($request['filter']) && !empty($request['filter'])){
			$filter = '&'.$request['filter'];
			//$query_name = '&query_name=fuzzy';
			$query_name = '&query_name=match_and';
			$sort = '';
			$page = $request['page'];
		}else{
			$filter = '';
			$query_name = '';
			//$query_name = '&query_name=match_and';
			$query_name = '';
			//$sort = '&'.rawurlencode('sort[0][best_price]=asc');
			$sort = '';
			$page =  $request['page'];
		}
		if(isset($request['filter']) && isset($request['query_name']) && !empty($request['query_name'])){
			$query_name = '&query_name='.$request['query_name'];
		}
		$ch = curl_init(); 
		//$doofinder_api_url = $doofinder_api_url."?hashid=$doofinder_hashid&transformer=&rpp=60&query=".rawurlencode($keyword)."&query_counter=21&page=1&$filter";
		$id = session_create_id();
		$doofinder_api_url = $doofinder_api_url."?hashid=$doofinder_hashid&page=$page&rpp=$itemperpage$filter$sort&session_id=$id$query_name&query=".rawurlencode($keyword)."";
	
		

		
		curl_setopt($ch, CURLOPT_URL,$doofinder_api_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("authorization: Token $doofinder_api_key"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		
		if (curl_errno($ch)) {
			$error_msg = curl_error($ch);
	
		}
		if (isset($error_msg)) {
		
			return false;
		}
		$resultArr = json_decode($server_output, true);
		//dd($resultArr);
		return $resultArr;
	} catch(\Exception $e){

		return false;	
	 } 		
}
 		
}

