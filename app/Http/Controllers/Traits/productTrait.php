<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductsReview;
use App\Models\Category;
use App\Http\Controllers\Traits\generalTrait;
use App\Models\CategoryImage;
use DB;
use Cache;
use Session;
use Illuminate\Support\Str; 

trait productTrait
{
	use generalTrait;
	function getProductGeneralInfo($general_info)
	{
		$arr_prod_infomation = array();
		if (trim($general_info) != '') {
			$arr_prod_infomation = explode("#", trim($general_info));
		}
		$arr_general_infomation = array();
		if (count($arr_prod_infomation) > 0) {
			$cnt = 0;
			for ($k = 0; $k < count($arr_prod_infomation); $k++) {
				if (trim($arr_prod_infomation[$k]) == '') {
					continue;
				}

				$arr_temp_1 = explode(":", trim($arr_prod_infomation[$k]));

				if (trim($arr_temp_1[1]) == '' and trim($arr_temp_1[0]) != '' and count($arr_temp_1) > 0) {
					$arr_temp_1[1]	= $arr_temp_1[0];
					$arr_temp_1[0] = '';
				}


				$arr_general_infomation[$cnt] = array("HEADER" => $arr_temp_1[0]);

				if (count($arr_temp_1) > 0) {
					if (trim($arr_temp_1[1]) != '') {
						$arr_temp_2 = explode("@", trim($arr_temp_1[1]));


						for ($p = 0; $p < count($arr_temp_2); $p++) {
							if (trim($arr_temp_2[$p]) == '') {
								continue;
							}


							$arr_temp_3 = explode("~", trim($arr_temp_2[$p]));

							$arr_general_infomation[$cnt][] = array(
								'lable' =>  $arr_temp_3[0],
								'value' => $arr_temp_3[1]
							);
						}
					}
				}

				$cnt = $cnt + 1;
			}
		}

		return $arr_general_infomation;
	}
	
	public function getProduct_SimilarItems($similar_sku_str) 
	{
		//dd(Session::all());

		$arr_similar_sku =  array();
        if(trim($similar_sku_str)!='')
		{
            $arr_similar_sku = explode("#",$similar_sku_str);
            $arr_similar_sku  = array_unique(array_map('trim',$arr_similar_sku));
            $arr_similar_sku  = array_filter($arr_similar_sku, 'strlen'); // Remove NULL, FALSE and empty strings only
        }
		
        $arr_related_item = array();
        if(count($arr_similar_sku) == 0) 
		{
            return $arr_related_item;
        }
		
		$final_smililar_sku_string = implode("','",$arr_similar_sku);
        
		$arr_related_item = array();
		
		
		$arr_related_item = DB::table($this->prefix . 'products as p')
		->select('p.product_id','p.sku','p.product_name','p.parent_category_id','p.parent_sku','p.image_name','p.product_url','p.our_price','p.sale_price','p.retail_price','p.badge','p.product_description')
		->join($this->prefix . 'products_category as pc', 'p.product_id', '=', 'pc.products_id')
		->join($this->prefix . 'category as c', 'pc.category_id', '=', 'c.category_id')
		->where('c.status','=','1')
		->where('p.status','=','1')
		->whereIn('p.sku', $arr_similar_sku)
		->groupBy('p.product_id')
		->get();
		
		$tot_items = count($arr_related_item);
	
		$arr_related_item = json_decode(json_encode($arr_related_item), true);
	
	
		if($tot_items == 0) { $arr_related_item = array(); return $arr_related_item; }
		if($tot_items > 0) 
		{
			foreach($arr_related_item as $kPR => $vPR) 
			{
				
				$arr_related_item[$kPR]['product_zoom_image'] = Get_Product_Image_URL($arr_related_item[$kPR]['image_name'], 'ZOOM');
				$arr_related_item[$kPR]['product_large_image'] = Get_Product_Image_URL($arr_related_item[$kPR]['image_name'], 'LARGE');
				$arr_related_item[$kPR]['product_medium_image'] = Get_Product_Image_URL($arr_related_item[$kPR]['image_name'], 'MEDIUM');
				$arr_related_item[$kPR]['product_thumb_image'] = Get_Product_Image_URL($arr_related_item[$kPR]['image_name'], 'THUMB');
				$arr_related_item[$kPR]['product_small_image'] = Get_Product_Image_URL($arr_related_item[$kPR]['image_name'], 'SMALL');
				$arr_related_item[$kPR]['image_name'] = Get_Product_Image_URL($arr_related_item[$kPR]['image_name'], 'THUMB');
				
				
				$productPrice = $this->Get_Price_Val($arr_related_item[$kPR]);
						
				$arr_related_item[$kPR]['retail_price_disp'] = $productPrice['retail_price_disp'];
				$arr_related_item[$kPR]['our_price_disp'] = $productPrice['our_price_disp'];
				$arr_related_item[$kPR]['sale_price_disp'] = $productPrice['sale_price_disp'];

				$arr_related_item[$kPR]['our_price'] = $productPrice['our_price'];
				$arr_related_item[$kPR]['sale_price'] = $productPrice['sale_price_disp'];  
				$arr_related_item[$kPR]['retail_price'] = $productPrice['retail_price'];
				
				$arr_related_item[$kPR]['badge'] = $arr_related_item[$kPR]['badge'];
				$arr_related_item[$kPR]['product_url'] = $arr_related_item[$kPR]['product_url'];
				$arr_related_item[$kPR]['product_name'] = Str::limit($arr_related_item[$kPR]['product_name'], 50);
				$arr_related_item[$kPR]['product_name_hover'] = $arr_related_item[$kPR]['product_name'];
				$arr_related_item[$kPR]['product_description'] = Str::limit($arr_related_item[$kPR]['product_description'], 50);

				if ($arr_related_item[$kPR]['product_url'] == "") {
					$arr_related_item[$kPR]['product_url'] = Get_Product_URL($arr_related_item[$kPR]['product_id'], $arr_related_item[$kPR]['product_name'], "No", $arr_related_item[$kPR]['category_id'], $arr_related_item[$kPR]['sku']);
				}
				//dd($recentProduct);
			}
		}
		return $arr_related_item;
    }
  
  
  
	function getRecent_ViewedItems($current_product_sku = '',$ProductsObj=array())
	{
		$arr_recent_product_id = array();
		
        if(session::has('RECENT_VIEWED_ITEMS')) 
		{
            $arr_recent_product_id = Session::get('RECENT_VIEWED_ITEMS');
            if(count($arr_recent_product_id) > 0)
			{
                $arr_recent_product_id  = array_reverse($arr_recent_product_id);
                $arr_recent_product_id  = array_unique(array_map('trim',$arr_recent_product_id));
                $arr_recent_product_id  = array_filter($arr_recent_product_id, 'strlen');
            }
        }
		
        if( session::has('RECENT_VIEWED_ITEMS') == false || empty(Session::get('RECENT_VIEWED_ITEMS')) ) 
		{
            Session::put('RECENT_VIEWED_ITEMS', array());
            Session::save();
        }
		if($current_product_sku != '') 
		{
			$arr_session_recent_product_id = Session::get('RECENT_VIEWED_ITEMS');
            if(!in_array(trim($current_product_sku), $arr_session_recent_product_id)) 
			{   
            	$arr_session_recent_product_id[] = $current_product_sku;
                Session::put('RECENT_VIEWED_ITEMS', $arr_session_recent_product_id);
                Session::save();
            }
		}
		$arr_recent_item = array();
        if(count($arr_recent_product_id) == 0) 
		{
            return $arr_recent_item;
        }
		
		// $arr_recent_item = DB::table($this->prefix . 'products as p')
		// 	->select('p.product_id','p.sku','p.product_name','p.parent_category_id','p.parent_sku','p.image_name','p.product_url','p.our_price','p.sale_price','p.retail_price','p.badge','p.product_description')
		// 	->join($this->prefix . 'products_category as pc', 'p.product_id', '=', 'pc.products_id')
		// 	->join($this->prefix . 'category as c', 'pc.category_id', '=', 'c.category_id')
		// 	->where('p.status', '=', '1')
		// 	->where('c.status', '=', '1')
		// 	->whereIn('p.sku', $arr_recent_product_id)
		// 	->groupBy('p.product_id')
		// 	->get();
		if(empty($ProductsObj)){
			$ProductsObj = $this->get_all_productWithCategory();
		}


			$arr_recent_item = $ProductsObj->filter(function ($product, $key) use($arr_recent_product_id) {
				if($product->status == 1 && $product->category_status==1 && in_array($product->sku,$arr_recent_product_id)){
					return true;
				}else{
					return false;
				}
			})->unique('product_id');

			$arr_recent_item = json_decode(json_encode($arr_recent_item), true);
			$arr_recent_item = array_values($arr_recent_item);
			$arr_recent_item = uniqueArray($arr_recent_item,'product_id');
			$arr_recent_item = array_sort($arr_recent_item, 'display_rank', SORT_ASC);
			
		$tot_items = count($arr_recent_item);
		$arr_recent_item = json_decode(json_encode($arr_recent_item), true);
		
		if($tot_items > 0)
		{
			foreach($arr_recent_item as $kPR => $vPR) 
			{
				
				$arr_recent_item[$kPR]['product_zoom_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'ZOOM');
				$arr_recent_item[$kPR]['product_large_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'LARGE');
				$arr_recent_item[$kPR]['product_medium_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'MEDIUM');
				$arr_recent_item[$kPR]['product_thumb_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'THUMB');
				$arr_recent_item[$kPR]['product_small_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'SMALL');
				$arr_recent_item[$kPR]['image_name'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'THUMB');
				
				
				$productPrice = $this->Get_Price_Val($arr_recent_item[$kPR]);
						
				$arr_recent_item[$kPR]['retail_price_disp'] = $productPrice['retail_price_disp'];
				$arr_recent_item[$kPR]['our_price_disp'] = $productPrice['our_price_disp'];
				$arr_recent_item[$kPR]['sale_price_disp'] = $productPrice['sale_price_disp'];

				$arr_recent_item[$kPR]['our_price'] = $productPrice['our_price'];
				$arr_recent_item[$kPR]['sale_price'] = $productPrice['sale_price_disp'];  
				$arr_recent_item[$kPR]['retail_price'] = $productPrice['retail_price'];
				
				$arr_recent_item[$kPR]['badge'] = $arr_recent_item[$kPR]['badge'];
				$arr_recent_item[$kPR]['product_url'] = $arr_recent_item[$kPR]['product_url'];
				$arr_recent_item[$kPR]['product_name'] = Str::limit($arr_recent_item[$kPR]['product_name'], 50);
				$arr_recent_item[$kPR]['product_name_hover'] = $arr_recent_item[$kPR]['product_name'];
				$arr_recent_item[$kPR]['product_description'] = Str::limit($arr_recent_item[$kPR]['product_description'], 50);
				//echo $arr_recent_item[$kPR]['category_id']; exit;
				if ($arr_recent_item[$kPR]['product_url'] == "") {
					$arr_recent_item[$kPR]['product_url'] = Get_Product_URL($arr_recent_item[$kPR]['product_id'], $arr_recent_item[$kPR]['product_name'], "No", $arr_recent_item[$kPR]['category_id'], $arr_recent_item[$kPR]['sku']);
				}
				//dd($recentProduct);
			}
		}
		return $arr_recent_item;
	}
	function bk_getRecent_ViewedItems($current_product_sku = '')
	{
		$arr_recent_product_id = array();
		
        if(session::has('RECENT_VIEWED_ITEMS')) 
		{
            $arr_recent_product_id = Session::get('RECENT_VIEWED_ITEMS');
            if(count($arr_recent_product_id) > 0)
			{
                $arr_recent_product_id  = array_reverse($arr_recent_product_id);
                $arr_recent_product_id  = array_unique(array_map('trim',$arr_recent_product_id));
                $arr_recent_product_id  = array_filter($arr_recent_product_id, 'strlen');
            }
        }
		
        if( session::has('RECENT_VIEWED_ITEMS') == false || empty(Session::get('RECENT_VIEWED_ITEMS')) ) 
		{
            Session::put('RECENT_VIEWED_ITEMS', array());
            Session::save();
        }
		if($current_product_sku != '') 
		{
			$arr_session_recent_product_id = Session::get('RECENT_VIEWED_ITEMS');
            if(!in_array(trim($current_product_sku), $arr_session_recent_product_id)) 
			{   
            	$arr_session_recent_product_id[] = $current_product_sku;
                Session::put('RECENT_VIEWED_ITEMS', $arr_session_recent_product_id);
                Session::save();
            }
		}
		$arr_recent_item = array();
        if(count($arr_recent_product_id) == 0) 
		{
            return $arr_recent_item;
        }
		
		$arr_recent_item = DB::table($this->prefix . 'products as p')
			->select('p.product_id','p.sku','p.product_name','p.parent_category_id','p.parent_sku','p.image_name','p.product_url','p.our_price','p.sale_price','p.retail_price','p.badge','p.product_description')
			->join($this->prefix . 'products_category as pc', 'p.product_id', '=', 'pc.products_id')
			->join($this->prefix . 'category as c', 'pc.category_id', '=', 'c.category_id')
			->where('p.status', '=', '1')
			->where('c.status', '=', '1')
			->whereIn('p.sku', $arr_recent_product_id)
			->groupBy('p.product_id')
			->get();
			
		$tot_items = count($arr_recent_item);
		$arr_recent_item = json_decode(json_encode($arr_recent_item), true);
		
		if($tot_items > 0)
		{
			foreach($arr_recent_item as $kPR => $vPR) 
			{
				
				$arr_recent_item[$kPR]['product_zoom_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'ZOOM');
				$arr_recent_item[$kPR]['product_large_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'LARGE');
				$arr_recent_item[$kPR]['product_medium_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'MEDIUM');
				$arr_recent_item[$kPR]['product_thumb_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'THUMB');
				$arr_recent_item[$kPR]['product_small_image'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'SMALL');
				$arr_recent_item[$kPR]['image_name'] = Get_Product_Image_URL($arr_recent_item[$kPR]['image_name'], 'THUMB');
				
				
				$productPrice = $this->Get_Price_Val($arr_recent_item[$kPR]);
						
				$arr_recent_item[$kPR]['retail_price_disp'] = $productPrice['retail_price_disp'];
				$arr_recent_item[$kPR]['our_price_disp'] = $productPrice['our_price_disp'];
				$arr_recent_item[$kPR]['sale_price_disp'] = $productPrice['sale_price_disp'];

				$arr_recent_item[$kPR]['our_price'] = $productPrice['our_price'];
				$arr_recent_item[$kPR]['sale_price'] = $productPrice['sale_price_disp'];  
				$arr_recent_item[$kPR]['retail_price'] = $productPrice['retail_price'];
				
				$arr_recent_item[$kPR]['badge'] = $arr_recent_item[$kPR]['badge'];
				$arr_recent_item[$kPR]['product_url'] = $arr_recent_item[$kPR]['product_url'];
				$arr_recent_item[$kPR]['product_name'] = Str::limit($arr_recent_item[$kPR]['product_name'], 50);
				$arr_recent_item[$kPR]['product_name_hover'] = $arr_recent_item[$kPR]['product_name'];
				$arr_recent_item[$kPR]['product_description'] = Str::limit($arr_recent_item[$kPR]['product_description'], 50);
				//echo $arr_recent_item[$kPR]['category_id']; exit;
				if ($arr_recent_item[$kPR]['product_url'] == "") {
					$arr_recent_item[$kPR]['product_url'] = Get_Product_URL($arr_recent_item[$kPR]['product_id'], $arr_recent_item[$kPR]['product_name'], "No", $arr_recent_item[$kPR]['category_id'], $arr_recent_item[$kPR]['sku']);
				}
				//dd($recentProduct);
			}
		}
		return $arr_recent_item;
	}
	function getRecent_ViewedItems_old($products_id = null)
	{
		$recentPidArr = [];
		//dd(Session::get('RECENT_VIEWED_ITEMS'));
		if (Session::has('RECENT_VIEWED_ITEMS') and count(Session::get('RECENT_VIEWED_ITEMS')) > 0)
			$recentPidArr = array_reverse(Session::get('RECENT_VIEWED_ITEMS'));

		if (!Session::has('RECENT_VIEWED_ITEMS') or empty(Session::get('RECENT_VIEWED_ITEMS'))) {
			Session::put('RECENT_VIEWED_ITEMS', []);
			Session::save();
		}

		if (!in_array((int)$products_id, Session::get('RECENT_VIEWED_ITEMS'))) {
			Session::push('RECENT_VIEWED_ITEMS', (int)$products_id);
			Session::save();
		}

		$recentProductRes = [];
		if (count($recentPidArr) <= 0 || empty($recentPidArr))
			return $recentProductRes;
		//dd(Session::get('RECENT_VIEWED_ITEMS'));

		$recentProductRes = Product::from($this->prefix . 'products as p')
			->select(
				'p.product_id',
				'p.sku',
				'p.product_name',
				'p.parent_category_id',
				'p.parent_sku',
				'p.image_name',
				'p.product_url',
				'p.our_price',
				'p.sale_price',
				'p.retail_price'
			)
			->join($this->prefix . 'products_category as pc', 'p.product_id', '=', 'pc.products_id')
			->join($this->prefix . 'category as c', 'pc.category_id', '=', 'c.category_id')
			->where('p.status', '=', '1')
			->where('c.status', '=', '1')
			->whereIn('p.sku', Session::get('RECENT_VIEWED_ITEMS'))
			->groupBy('p.product_id')
			->get();
		//dd($recentProductRes);		
		if (count($recentProductRes) > 0) {
			foreach ($recentProductRes as $recentProduct) {
				$image_name = Get_Product_Image_URL($recentProduct->image_name, 'MEDIUM');
				
				
				//echo "<pre>"; print_r($image_name); 
				$productPrice = $this->Get_Price_Val($recentProduct);
				
				$recentProductRes->retail_price_disp = $productPrice['retail_price_disp'];
				$recentProductRes->our_price_disp = $productPrice['our_price_disp'];
				$recentProductRes->sale_price_disp = $productPrice['sale_price_disp'];

				$recentProductRes->our_price = $productPrice['our_price'];
				$recentProductRes->is_sale_price = $recentProduct->sale_price;
				$recentProductRes->sale_price = $productPrice['sale_price_disp'];  
				$recentProductRes->retail_price = $productPrice['retail_price'];
				$recentProductRes->badge = $recentProduct->badge;
				$recentProductRes->product_url = $recentProduct->product_url;

				if ($recentProductRes->product_url == "") {
					$recentProductRes->product_url = Get_Product_URL($recentProduct->product_id, $recentProductRes->product_name, "No", $recentProduct->category_id, $recentProductRes->sku);
				}
				$recentProductRes->top_seller =  $recentProduct->is_topseller;
				//dd($recentProduct);

			}
		}
		dd($recentProductRes);
		//exit;
		return $recentProductRes;
	}
	public function sortByHTML($request = array())
	{
		$sort_by_html 	= '';
		$sort_by_array 	= $this->getSortBy();
		$selected_sort_by_label = (isset($request['sort_by']) && $request['sort_by'] != '') ? $sort_by_array[$request['sort_by']] : $sort_by_array['BEST'];
		$selected_sort_by 	= (isset($request['sort_by']) && $request['sort_by'] != '') ? $request['sort_by'] : 'BEST';

		$sort_by_html 		.= '<select class="form-select sort_by_div" id="itemperpage" aria-label="Floating label select example">';
		foreach ($sort_by_array as $kSBA => $vSBA) {
			//$class_active_sort_by = ($kSBA == $selected_sort_by) ? 'class="active"' : '';
			$sort_by_html .= '<option value="' . $kSBA . '">' . $vSBA['disp_val'] . '</option>';
		}
		$sort_by_html 		.= '</select>';
		return $sort_by_html;
	}
	/* Sort By Array */
	public function getSortBy()
	{
		$sort_field_arr = array(
			"NEW" 	=> array("disp_val" => "Newest"),
			"BEST" 	=> array("disp_val" => "Best Sellers"),
			"PLTH" 	=> array("disp_val" => "Price: Low to High"),
			"PHTL" 	=> array("disp_val" => "Price: High to Low")
		);
		return $sort_field_arr;
	}

	public function perPageHTML($request = array())
	{
		/* Per Page Dropdown Code Starts Here */
		$per_page_html = '';
		$per_page = (isset($request->per_page) && $request->per_page != '') ? $request->per_page : config('global.PRODUCT_PER_PAGE');
		$per_page_array = $this->getPerPage();
		$per_page_html .= '
			<select class="form-select" id="itemperpage" aria-label="Floating label select example">';
		foreach ($per_page_array as $kPPA => $vPPA) {
			$per_page_html .= '<option value="' . $vPPA . '">' . $vPPA . '</option>';
		}
		$per_page_html .= '</select>
			<label for="itemperpage">Item Per Page</label>';
		return $per_page_html;
		/* Per Page Dropdown Code Ends Here */
	}
	/* Per Page Array */
	public function getPerPage()
	{
		$per_page_arr = array('12', '24', '36', '48');
		return $per_page_arr;
	}
	function getColorGroupItems($products_id, $product_group)
	{
		$productrRes = (object)[];
		if (trim($product_group) == '' or $products_id <= 0) {
			return $productrRes;
		}

		$productrRes = Product::from('products as p')
			->select('p.products_id', 'p.product_name', 'p.product_sku', 'p.color', 'p.image_name', 'c.category_id')
			->join('products_category as pc', 'p.products_id', '=', 'pc.products_id')
			->join('category as c', 'pc.category_id', '=', 'c.category_id')
			->where('p.product_group', '=', trim(addslashes($product_group)))
			->where('c.status', '=', '1')
			->where('p.is_for_brand_price_list', '=', '0')
			->where('p.color', '!=', '')
			->groupBy('p.color')
			->orderBy('p.product_sku', 'ASC')->get();

		if ($productrRes) {
			foreach ($productrRes as $prod) {

				$prod->Product_URL   = $this->getProductRewriteURL($prod->products_id, $prod->product_name, $prod->category_id);
				$prod->Product_Img   = $this->getImageURL($prod->image_name, 'THUMB');
			}
		}

		return $productrRes;
	}

	function getColorULHTML($groupedColorRes)
	{
		$colorHtml = '';
		if ($groupedColorRes) {
			$max = 5;
			if ($groupedColorRes->count() < 5)
				$max = $groupedColorRes->count();

			$colorHtml = '<div class="prdt-color">
										<ul>';
			for ($c = 0; $c < $max; $c++) {
				$colorHtml .= '<li>
					<a href="' . $groupedColorRes[$c]->Product_URL . '" class="pc-box" title="' . $groupedColorRes[$c]->color . '"><img src="' . $groupedColorRes[$c]->Product_Img . '" width="20" height="20" alt=""></a>
				</li>';
			}
			if (($groupedColorRes->count() - $max) > 0) {
				$colorHtml .= '<li>
					<a href="javascript:void(0);" class="pc-link" title="">+ <span class="d-none d-lg-inline-block">' . ($groupedColorRes->count() - $max) . ' More</span></a>
				</li>';
			}

			$colorHtml .= '</ul>
						</div>';
		}

		return $colorHtml;
	}

	function getProductInfo($productRes)
	{
		$prodInfoHtml = '';
		if ($productRes) {
			$topCatId = $this->getTopParent($productRes->category_id);


			$prodInfoHtml = '<div class="prdt-slide-dsc">
									<ul class="unorder-list black-color">';



			if ($topCatId == '65') {
				$exp_fiber = array();
				if ($productRes->fiber != '') {
					$exp_fiber = explode("#", $productRes->fiber);

					$tmp_ounce = '';
					if ($productRes->species != '') {
						$tmp_ounce = $productRes->species;
					}
					$prodInfoHtml .= '<li>' . ($tmp_ounce != '' ? $productRes->species . 'oz ' : '') . $exp_fiber[0] . '</li>';
				}

				$cat_sub_cat = '';
				$cat_sub_cat = $this->getCategoryNavigationPrdInfo($productRes->category_id);

				if ($cat_sub_cat != '') {
					$cat_sub_cat_ary_str = '';
					$cat_sub_cat_ary = array();
					$cat_sub_cat_ary = explode("||", $cat_sub_cat);

					unset($cat_sub_cat_ary[0]);
					$cat_sub_cat_ary_str = implode(" ", $cat_sub_cat_ary);
					if ($cat_sub_cat_ary_str != '') {
						$prodInfoHtml .= '<li>' . $cat_sub_cat_ary_str . '</li>';
					}
				}


				$groupProduts = Product::select(DB::raw('distinct(width)'))
					->where('product_group', '=', trim(addslashes($productRes->product_group)))
					->where('status', '=', '1')
					//->where('products_id', '!=', $productRes->products_id)
					->where('color', '!=', '')
					->groupBy('color')
					->get()->toArray();

				$temp_width = array();
				$widthArr = array_column($groupProduts, 'width');
				if (count($widthArr) > 1) {
					$productRes->width = implode(" & ", $widthArr);
				}

				if (strtoupper($productRes->width) == 'RANDOM') {
					$prodInfoHtml .= '<li>Random Widths</li></li>';
				} elseif ($productRes->width != '') {
					$prodInfoHtml .= '<li>' . $productRes->width . ' ' . $productRes->width_unit_measure . ' Wide</li>';
				}
			} else if ($topCatId == '69') {
				if ($productRes->species != '') {
					$species_name = $productRes->species;
					$species_name = explode("#", $species_name);
					$prodInfoHtml .= '<li>' . $productRes->width . ' ' . $species_name[0] . '</li>';
				}

				$cat_sub_cat = '';
				$cat_sub_cat = $this->getCategoryNavigationPrdInfo($productRes->category_id);

				if ($cat_sub_cat != '') {
					$cat_sub_cat_ary_str = '';
					$cat_sub_cat_ary = array();
					$cat_sub_cat_ary = explode("||", $cat_sub_cat);

					unset($cat_sub_cat_ary[0]);
					$cat_sub_cat_ary_str = implode(" ", $cat_sub_cat_ary);
					if ($cat_sub_cat_ary_str != '') {
						$prodInfoHtml .= '<li>' . $cat_sub_cat_ary_str . '</li>';
					}
				}

				if ($productRes->hrd_lm_lv_cat_id_match == 1) {
					$groupProduts = Product::select(DB::raw('distinct(width)'))
						->where('product_group', '=', trim(addslashes($productRes->product_group)))
						->where('status', '=', '1')
						//->where('products_id', '!=', $productRes->products_id)
						->where('color', '!=', '')
						->groupBy('color')
						->get()->toArray();


					$temp_width = array();
					$widthArr = array_column($groupProduts, 'width');
					if (count($widthArr) > 1) {
						$productRes->width = implode(" & ", $widthArr);
					}

					if (strtoupper($productRes->width) == 'RANDOM') {
						$prodInfoHtml .= '<li>Random Widths</li></li>';
					} elseif ($productRes->width != '') {
						$prodInfoHtml .= '<li>' . $productRes->width . ' ' . $productRes->width_unit_measure . ' Wide</li>';
					}
				}
			} else if ($topCatId == '79' || $topCatId == '73') {
				if ($productRes->product_dimensions != '') {
					$prodInfoHtml .= '<li>' . stripcslashes($productRes->product_dimensions) . '</li>';
				}
			} else if ($topCatId == '76') {

				if ($productRes->hrd_lm_lv_cat_id_match == 1) {

					$groupProduts = Product::select(DB::raw('distinct(width)'))
						->where('product_group', '=', trim(addslashes($productRes->product_group)))
						->where('status', '=', '1')
						//->where('products_id', '!=', $productRes->products_id)
						->where('color', '!=', '')
						->groupBy('color')
						->get()->toArray();


					$temp_width = array();
					$widthArr = array_column($groupProduts, 'width');
					if (count($widthArr) > 1) {
						$productRes->width = implode(" & ", $widthArr);
					}

					if (strtoupper($productRes->width) == 'RANDOM') {
						$prodInfoHtml .= '<li>Random Widths</li></li>';
					} elseif ($productRes->width != '') {
						$prodInfoHtml .= '<li>' . $productRes->width . ' ' . $productRes->width_unit_measure . ' Wide</li>';
					}
				}
			} else {
				if ($productRes->hrd_lm_lv_cat_id_match == '1') {
					if (strtoupper($productRes->width) == "RANDOM")
						$prodInfoHtml .= '<li>Random Widths</li>';
					else {
						if ($productRes->width != '' && $productRes->thickness != '') {
							$prodInfoHtml .= '<li>' . $productRes->width . " X " . $productRes->thickness . $productRes->thickness_unit_measure . '</li>';
						}
					}
				}
			}
			$prodInfoHtml .= '</ul>
								</div>';
		}

		$priceInfo = $this->getPriceInfo($productRes, $topCatId);

		$prodInfo['prodInfoHtml'] 	= $prodInfoHtml;
		$prodInfo['prodPriceHTML']	= $priceInfo['prodPriceHTML'];
		$prodInfo['requestFlag'] 	= $priceInfo['requestFlag'];

		return $prodInfo;
	}

	function getPriceInfo($productRes, $topCatId = null)
	{
		$retail_price_ary = $this->calculatePrice($productRes->retail_price, $productRes->unit_measure);
		$retail_price_sf = $retail_price_ary['sf'];
		$retail_price_sy = $retail_price_ary['sy'];
		$retail_price_lf = $retail_price_ary['lf'];
		$retail_price_pp = $retail_price_ary['pp'];
		$retail_price_f = $retail_price_ary['f'];

		$prodPriceHTML = $prefix = $displaySalePrice = $displayRetailPrice = $unitMeasure = $requestFlag = '';

		$sale_price = $productRes->sale_price;
		if ($productRes->sale_price == 0 && $productRes->our_price > 0) {
			$sale_price = $productRes->our_price;
		}
		$price_ary = $this->calculatePrice($sale_price, $productRes->unit_measure);
		$sale_price_sf = $price_ary['sf'];
		$sale_price_sy = $price_ary['sy'];
		$sale_price_lf = $price_ary['lf'];
		$sale_price_pp = $price_ary['pp'];
		$sale_price_f = $price_ary['f'];

		if ($productRes->diy == '1') {
			$sale_price_sf = 0;
			$sale_price_sy = 0;
			$sale_price_lf = 0;
			$sale_price_pp = 0;
			$sale_price_f 	= 0;
		}

		if ($sale_price_sf > 0  ||  $sale_price_pp > 0 ||  $sale_price_f > 0) {
			if ($productRes->manufacture_id == '101'  || $productRes->manufacture_id == '78'  || $productRes->manufacture_id == '77'  || $productRes->manufacture_id == '45'  || $productRes->manufacture_id == '40' || $productRes->manufacture_id == '213') {
				if ($sale_price_sf > 0  ||  $sale_price_pp > 0 ||  $sale_price_f > 0)
					$prefix = 'MAP ';
			}
			if ($sale_price_sf > 0) {
				$displaySalePrice = Make_Price($sale_price_sf, true);
				$unitMeasure = ' per sq ft';
			}
			if ($sale_price_sy > 0 && strtolower($productRes->item_comes_in) != 'box' && !in_array($topCatId, array('65', '69', '79', '73', '76'))) {
				$displaySalePrice =	'| ' . $sale_price_sy;
			}
			if ($sale_price_pp > 0) {
				$displaySalePrice = Make_Price($sale_price_pp, true);
				$unitMeasure = ' per piece';
			}
			if ($sale_price_f > 0) {
				$displaySalePrice = Make_Price($sale_price_f, true);
				$unitMeasure = ' per ft';
			}

			if ($retail_price_sf > 0) {
				$displayRetailPrice = Make_Price($retail_price_sf, true);
			}
			if ($retail_price_pp > 0) {
				$displayRetailPrice = Make_Price($retail_price_pp, true);
			}
			if ($retail_price_f > 0) {
				$displayRetailPrice = Make_Price($retail_price_f, true);
			}

			$prodPriceHTML = '<div class="prdt-price">
									<div class="prdt-mprice"><span>' . $prefix . $displaySalePrice . '</span> ' . $unitMeasure . '</div>
									<div class="prdt-dprice"><span>' . $prefix . $displayRetailPrice . '</span> ' . $unitMeasure . '</div>
								</div>';
		} else {
			if (in_array($productRes->manufacture_id, config('const.req_qt_restricted_manu_id_arr'))) {
				$requestFlag = 'checkStock';
			} else {
				if ($productRes->manufacture_id == 101 || $productRes->manufacture_id == 78 || $productRes->manufacture_id == 77 || $productRes->manufacture_id == 45 || $productRes->manufacture_id == 40 || $productRes->manufacture_id == 213)
					$requestFlag = 'requestAQuote';
				else
					$requestFlag = 'requestSalePrice';
			}
		}

		return array(
			'prodPriceHTML' => $prodPriceHTML,
			'requestFlag'	=> $requestFlag,
		);
	}

	public function getProductReview($sku)
	{
		$result =  ProductsReview::select(
			'review_id',
			'products_id',
			'sku',
			'star_rate',
			'first_name',
			'city',
			'state',
			'country',
			'user_review',
			'customer_id',
			'date',
			'approved',
			'ip_address',
			'email'
		)->where('approved', '=', 'Yes')
			->where('star_rate', '!=', '0')
			->where('sku', '=', $sku)
			->orderBy('review_id', 'DESC')
			->offset(0)->limit(8)->get();
		if (count($result) > 0) {
			for ($p = 0; $p < count($result); $p++) {
				$average_rate = ceil($result[$p]->star_rate);
				if ($average_rate > 5)
					$average_rate = 5;
				$result[$p]['date'] = date('F d,Y ', strtotime($result[$p]->date));
				$result[$p]['user_review'] = $this->clean_new($result[$p]->user_review);
				$result[$p]['average_rate'] = $average_rate;
			}
		}
		return $result;
	}

	public function getRugsCategoryBoxes($category_id = 1)
	{

		$category = Category::select(['category_id', 'category_name', 'category_description', 'landing_main_image', 'landing_mobile_image', 'landing_banner_links', 'landing_skus', 'landing_amazing_text', 'category_tile_json', 'landing_four_boxes', 'landing_how_to_html', 'landing_bottom_html', 'meta_title', 'meta_keywords', 'meta_description'])->where('category_id', $category_id)->where('status', '1')->orderBy('category_name')->get();

		$final_data = [];
		$final_new_data = [];
		if ($category->count() > 0 && !empty($category[0]->category_tile_json)) {
			$CategoryImage = CategoryImage::where('category_id', $category[0]->category_id)->where('status', '=', '1')->get();
			$data = $CategoryImage->count();
			$final_data = [];
			$final_new_data = [];
			$category_image = $category_image_mobile = $category_image2 = $category_image_mobile2 = '';

			$categoryTileJson = $this->getDisplayRanking(json_decode($category[0]->category_tile_json));

			$final_data = array(
				'landing_main_image'	=>	$category[0]->landing_main_image != '' ? config('const.SITE_URL') . "/" . $category[0]->landing_main_image : config('const.NO_IMAGE_1250_450'),
				'landing_mobile_image'	=>	$category[0]->landing_mobile_image != '' ? config('const.SITE_URL') . "/" . $category[0]->landing_mobile_image : config('const.NO_IMAGE_767_400'),
				'landing_amazing_text'	=>	str_replace('{$SITE_URL}', config('const.SITE_URL'), $category[0]->landing_amazing_text),
				'landing_four_boxes'	=>	str_replace('{$SITE_URL}', config('const.SITE_URL'), $category[0]->landing_four_boxes),
				'landing_how_to_html'	=>	str_replace('{$SITE_URL}', config('const.SITE_URL'), $category[0]->landing_how_to_html),
				'landing_bottom_html'	=>	$category[0]->landing_bottom_html,
				'landing_skus'			=>	$category[0]->landing_skus,
				'category_tile_json'    =>  $categoryTileJson
			);
		} else {
			$CategoryData = Category::where('category_id', 1)->where('status', '=', '1')->get();

			$categoryTileJson = $this->getDisplayRanking(json_decode($CategoryData[0]->category_tile_json));
			$final_data = array(
				'landing_four_boxes'	=>	str_replace('{$SITE_URL}', config('const.SITE_URL'), $CategoryData[0]->landing_four_boxes),
				'category_tile_json'    =>  $categoryTileJson
			);
		}

		$this->PageData['final_data'] = $final_data;

		if ($category->count() > 0) {
			if (!empty($category[0]->meta_title))
				$this->PageData['meta_title'] = $category[0]->meta_title;
			if (!empty($category[0]->meta_description))
				$this->PageData['meta_description'] = $category[0]->meta_description;
			if (!empty($category[0]->meta_keywords))
				$this->PageData['meta_keywords'] = $category[0]->meta_keywords;
		}
		return $final_data;
	}
}
