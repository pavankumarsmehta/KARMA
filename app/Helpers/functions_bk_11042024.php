<?php

use App\Http\Controllers\RequestAQuoteController;
use Illuminate\Container\Container;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Manufacturer;
use App\Models\Country;
use App\Models\State;
use App\Models\ProductsCategory;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Currency;
use App\Models\StaticPages;
use Carbon\Carbon;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;


	if (!function_exists('getControllerName')) {
		function getControllerName()
		{
			$action = request()->route()->getAction();
			$controller = class_basename($action['controller']);
			list($controller_name, $action_name) = explode('@', $controller);
			return $controller_name;
		}
	}

	if (!function_exists('NumberFormat')) {
		function NumberFormat($val)
		{
			if ($val == '')
				$val = 0;
			$val = (float)$val;
			return number_format($val, 2, '.', '');
		}
	}
	if (!function_exists('getActionName')) {
		function getActionName()
		{
			$action = request()->route()->getAction();
			$controller = class_basename($action['controller']);
			list($controller_name, $action_name) = explode('@', $controller);
			return $action_name;
		}
	}

	if (!function_exists('site_slug')) {
		function site_slug($value = null)
		{
			//use Illuminate\Support\Str;
			return Illuminate\Support\Str::slug($value, '_');
		}
	}

	if (!function_exists('clearSpecialCharacters')) {
		function clearSpecialCharacters($value = null)
		{
			$value = str_replace("#", " ", $value);
			$value = str_replace("@", " ", $value);
			$value = str_replace(":", " ", $value);
			$value = str_replace("~", " ", $value);
			$value = str_replace(".", " ", $value);
			$value = str_replace("\\", " ", $value);
			return $value;
		}
	}

	/*
	 * Function will create/get breadcrumblist schema for static and landing pages.
	 * */
	if (!function_exists('getBLSchemaForStaticPages')) {
		function getBLSchemaForStaticPages($pageName)
		{
			try {
				$schemaContent = '<script type="application/ld+json">';
				$schemaContent .= '{';
				$schemaContent .= '"@context": "http://schema.org",';
				$schemaContent .= '"@type": "BreadcrumbList",';
				$schemaContent .= '"itemListElement":[';
				$schemaContent .= '{ "@type": "ListItem", "position": 1, "item": "' . config('const.SITE_URL') . '", "name": "Home" } ,';
				$schemaContent .= '{ "@type": "ListItem", "position": 2, "name": "' . ucwords($pageName) . '" }';
				$schemaContent .= ']';
				$schemaContent .= '}';
				$schemaContent .= '</script>';

				return $schemaContent;
			} catch (\Exception $exception) {
				return false;
			}
		}
	}

	/*
	 * Function will create/get breadcrumblist schema for static and landing pages.
	 * */
	if (!function_exists('getBLSchemaForMyAccountPages')) {
		function getBLSchemaForMyAccountPages($pageName='')
		{
			try {
				$schemaContent = '<script type="application/ld+json">';
				$schemaContent .= '{';
				$schemaContent .= '"@context": "http://schema.org",';
				$schemaContent .= '"@type": "BreadcrumbList",';
				$schemaContent .= '"itemListElement":[';
				$schemaContent .= '{ "@type": "ListItem", "position": 1, "item": "' . config('const.SITE_URL') . '", "name": "Home" } ,';
				if(empty($pageName)){
					$schemaContent .= '{ "@type": "ListItem", "position": 2, "name": "' . ucwords('My Account') . '" }';
				}else{
					$schemaContent .= '{ "@type": "ListItem", "position": 2, "item": "' . config('const.SITE_URL') . '/myaccount.html", "name": "'.ucwords('My Account').'" } ,';
				}
				if(isset($pageName) && !empty($pageName)){
					$schemaContent .= '{ "@type": "ListItem", "position": 3, "name": "' . ucwords($pageName) . '" }';
				}
				$schemaContent .= ']';
				$schemaContent .= '}';
				$schemaContent .= '</script>';

				return $schemaContent;
			} catch (\Exception $exception) {
				return false;
			}
		}
	}


	/*
	 * Function will create/get breadcrumblist schema for sale page.
	 * */
	 
	if (!function_exists('getSubCategories')) {
		function getSubCategories($records = null, $columnName = 'category_name', $columnSortOrder = 'asc')
		{
			$categories_arr = array();
			if ($columnSortOrder == 'asc') {
				$records = $records->sortBy($columnName);
			} else {
				$records = $records->sortByDesc($columnName);
			}
			foreach ($records as $record) {
				$categories_arr[] = $record->category_id;
				if ($record->childrenRecursive && count($record->childrenRecursive) > 0) {
					$categories_arr = array_merge($categories_arr, getSubCategories($record->childrenRecursive,  $columnName, $columnSortOrder));;
				}
			}
			return $categories_arr;
		}
	}

	if (!function_exists('getCountryBoxArray')) {
		function getCountryBoxArray()
		{
			$CountryRS = Country::select('countries_name', 'countries_iso_code_2')->where('status', '=', '1')->orderBy('countries_name', 'ASC')->get();
			// Return US if no country found start
			$ArrTemp = array();
			if (count($CountryRS) <= 0) {
				$ArrTemp = array("US" => "US United States");
				return $ArrTemp;
			}
			// Return US if no country found end

			$ArrTemp        = array();
			$arrCountryCode = array();
			$arrCountryName = array();

			for ($p = 0; $p < count($CountryRS); $p++) {
				$arrCountryCode[] = $CountryRS[$p]['countries_iso_code_2'];
				$arrCountryName[] = $CountryRS[$p]['countries_iso_code_2'] . " " . $CountryRS[$p]['countries_name'];
			}

			$ArrTemp = array_combine($arrCountryCode, $arrCountryName);

			return $ArrTemp;
		}
	}

	if (!function_exists('getCategoryBoxArray')) {
		function getCategoryBoxArray()
		{
			$CategoryRS = Category::select('category_id', 'category_name')->where('parent_id', '=', '0')->where('status', '=', '1')->orderBy('category_name', 'ASC')->get();

			$ArrTemp        = array();

			for ($p = 0; $p < count($CategoryRS); $p++) {
				$ArrTemp[$p]['category_id'] = $CategoryRS[$p]['category_id'];
				$ArrTemp[$p]['category_name'] = $CategoryRS[$p]['category_name'];
			}
			return $ArrTemp;
		}
	}


	if (!function_exists('getStateBoxArray')) {
		function getStateBoxArray()
		{
			$StateRS = State::select('name', 'code')->where('status', '=', '1')->where('countries_id', '=', '223')->orderBy('name', 'ASC')->get();

			// Return NY if no state found start
			$ArrTemp = array();
			if (count($StateRS) <= 0) {
				$ArrTemp = array("NY" => "New York");
				return $ArrTemp;
			}
			// Return NY if no state found end

			$ArrTemp        = array();
			$arrStateCode = array();
			$arrStateName = array();

			for ($p = 0; $p < count($StateRS); $p++) {
				$arrStateCode[] = $StateRS[$p]['code'];
				$arrStateName[] = $StateRS[$p]['name'];
			}

			$ArrTemp = array_combine($arrStateCode, $arrStateName);

			$selectstate = array("" => "Select State");
			$ArrTemp = array_merge($selectstate, $ArrTemp);

			return $ArrTemp;
		}
	}

	if (!function_exists('displaycountry')) {
		function displaycountry($selcountry, $countryArray = null)
		{
			if (!isset($countryArray)) {
				$countryArray = getCountryBoxArray();
			}

			if (!isset($selcountry) || $selcountry == "")
				$selcountry = "US";
			$countrycombo = '';
			foreach ($countryArray as $entry_key => $entry_value) {

				if (trim($selcountry) == trim($entry_key)) {
					$countrycombo .= "<option value=" . $entry_key . " selected>" . $entry_value . "</option>";
				} else {
					$countrycombo .= "<option value=" . $entry_key . ">" . $entry_value . "</option>";
				}
			}
			return $countrycombo;
		}
	}

	if (!function_exists('displaystate')) {
		function displaystate($selstate, $stateArray = null)
		{
			if (!isset($stateArray)) {
				$stateArray = getStateBoxArray();
			}

			$statecombo = "";

			foreach ($stateArray as $entry_key => $entry_value) {

				if (trim($selstate) == trim($entry_key)) {
					if ($entry_value == 'Select State') {
						$statecombo .= '<option value="">' . $entry_value . '</option>';
					} else {
						$statecombo .= "<option value=" . $entry_key . " selected>" . $entry_value . "</option>";
					}
				} else {
					$statecombo .= "<option value=" . $entry_key . ">" . $entry_value . "</option>";
				}
			}
			return $statecombo;
		}
	}
	/*if (!function_exists('displaycategory')) {
		function displaycategory($selcategory, $categoryArray = null)
		{
			if(!isset($categoryArray)) {
				$categoryArray = getCategoryBoxArray();
			}
			//$category_image = CategoryImage::findOrFail($id);
			$categorycombo = "";
			dd($request->image_id);
			foreach ($categoryArray as $entry_key => $entry_value) {

				if (trim($selcategory) == trim($entry_key)) {
					$categorycombo .= "<option value=" . $entry_key . " selected>" . $entry_value . "</option>";
				} else {
					$categorycombo .= "<option value=" . $entry_key . ">" . $entry_value . "</option>";
				}
			}
			return $categorycombo;
		}
	}*/

	if (!function_exists('Get_Product_Additional_Image_URL')) {
		function Get_Product_Additional_Image_URL($sku)
		{
			$PRD_IMG_PATH  = config('const.PRD_IMG_PATH');
			$PRD_IMG_URL   = config('const.PRD_IMG_URL');
			$additional_images = array();
			for ($extimg = 1; $extimg <= 5; $extimg++) {
				if (file_exists($PRD_IMG_PATH . "large/" . $sku . "_" . $extimg . ".jpg") &&  file_exists($PRD_IMG_PATH . "thumb/" . $sku . "_" . $extimg . ".jpg")) {
					if (file_exists($PRD_IMG_PATH . "zoom/" . $sku . "_" . $extimg . ".jpg"))
						$vextZOOM_img = $PRD_IMG_URL . "zoom/" . $sku . "_" . $extimg . ".jpg";
					else if (file_exists($PRD_IMG_PATH . "large/" . $sku . "_" . $extimg . ".jpg"))
						$vextZOOM_img = $PRD_IMG_URL . "large/" . $sku . "_" . $extimg . ".jpg";
					else
						$vextZOOM_img = config('const.NO_IMAGE');

					//Large img code Start
					if (file_exists($PRD_IMG_PATH . "large/" . $sku . "_" . $extimg . ".jpg"))
						$vextLarge_img = $PRD_IMG_URL . "large/" . $sku . "_" . $extimg . ".jpg";
					else
						$vextLarge_img = config('const.NO_IMAGE');
					//Large img code End

					//Medium img code Start
					if (file_exists($PRD_IMG_PATH . "/medium/" . $sku . "_" . $extimg . ".jpg"))
						$vextMedium_img = $PRD_IMG_URL . "medium/" . $sku . "_" . $extimg . ".jpg";
					else
						$vextMedium_img = config('const.NO_IMAGE');
					//Medium img code End

					//Thumb img code Start
					if (file_exists($PRD_IMG_PATH . "thumb/" . $sku . "_" . $extimg . ".jpg"))
						$vextThumb_img = $PRD_IMG_URL . "thumb/" . $sku . "_" . $extimg . ".jpg";
					else
						$vextThumb_img = config('const.NO_IMAGE');
					//Thumb img code End


					$additional_images['extraImgsThumb'][] = $vextThumb_img;
					$additional_images['extraImgsMedium'][] = $vextMedium_img;
					$additional_images['extraImgsLarge'][] = $vextLarge_img;
					$additional_images['extraImgsMagnifier'][] = $vextZOOM_img;
				}
			}
			if (file_exists($PRD_IMG_PATH . "swatch/" . $sku . ".jpg")) {
				$additional_images['swatchImgs'] = $PRD_IMG_URL . "swatch/" . $sku . ".jpg";
			}
			//dd($additional_images);
			return $additional_images;
		}
	}
	//parent category url
	if (!function_exists("Get_Parent_Category_URL")) {
		function Get_Parent_Category_URL($catid, $replace_flg = 0)
		{
			global $SITE_URL;
			$new_vcat_name = '';
			$cRes = Category::select('category_id', 'parent_id', 'category_name')->where('category_id', $catid)->where('status', '1')->get()->toArray();
			if (count($cRes) > 0) {
				$new_iparent_id = $cRes[0]["parent_id"];
				$new_icat_id = $cRes[0]["category_id"];
				$new_vcat_name = remove_special_chars(trim($cRes[0]["category_name"]));

				while ($new_iparent_id != 0) {
					$newcres = Category::select('category_id', 'parent_id', 'category_name')->where('category_id', $new_iparent_id)->where('status', '1')->get()->toArray();
					$new_iparent_id = $newcres[0]["parent_id"];
					$new_icat_id = $newcres[0]["category_id"];
					$new_vcat_name = remove_special_chars(trim($newcres[0]["category_name"])) . "/" . $new_vcat_name;
				}
			}
			return $new_vcat_name;
		}
	}
	//product detail url
	if (!function_exists("Get_Product_URL")) {
		function Get_Product_URL($productid = '', $product_name = '', $is_build = 'No', $icatn = '', $sku = '', $catName = '', $url = '')
		{
			$SITE_URL = config('app.url') . "/";

			if ($productid == '')
				return false;

			$product_name = $product_name;
			if (trim($product_name) == '' || trim($sku) == '') {
				$productnameres = Product::select('product_name', 'product_name', 'sku')->where('product_id', $productid)->get()->toArray();
				if (count($productnameres) > 0) {
					$product_name = $productnameres[0]['product_name']; //."-".$productnameres[0]['sku'];
					$sku = remove_special_chars($productnameres[0]['sku']);
				} else {
					return false;
				}
			}

			$product_name = remove_special_chars($product_name);
			$catName = remove_special_chars($catName);

			//$check_cat_res = Category::select('category_id', 'parent_id')->where('category_id', $icatn)->where('status', '1')->get()->toArray();
			//if ($icatn == '' || count($check_cat_res) == 0) {
				if ($icatn == '') {	
				//$prod_sql_res = 
				$prod_sql_res = DB::table('hba_products_category as pcr')
					->join('hba_category as c', 'pcr.category_id', '=', 'c.category_id')
					->where('c.status', '=', '1')
					->where('pcr.products_id', '=', $productid)
					->select('pcr.products_id', 'c.category_id', 'c.category_name')
					->orderBy('c.display_position', 'ASC')
					->orderBy('c.category_name', 'ASC')
					->skip(0)->take(1)->get()->toArray();
				//$prod_sql_res = json_decode(json_encode($prod_sql_res), true);						
				//print_r($prod_sql_res);	

				//$prod_sql_res = $prod_query->get()->toArray(); //$this->dbobj->select($prod_query);	
				$icatn = (isset($prod_sql_res[0]->category_id) ? $prod_sql_res[0]->category_id : "");
				if ($catName == "" && isset($prod_sql_res[0]->category_name)) {
					$catName = remove_special_chars($prod_sql_res[0]->category_name);
				}
			}
			//$category_url = Get_Parent_Category_URL($icatn, 1);
			$product_url = $SITE_URL . $catName . '/product/' . $product_name . "/" . strtolower($sku) . ".html";
			if ($productid > 0) {
				// dd($productid);
				DB::table('hba_products')->where('product_id', $productid)->where('product_url', NULL)->update(array('product_url' => $product_url));
			}
			return $product_url;
			/*if($product_name==''){
				return $SITE_URL.$catName.'/product/'.$product_name."/".strtolower($sku).".html";
			} else {
				return $SITE_URL.$catName.'/product/'.$product_name."/".strtolower($sku).".html";
			}*/
		}
	}

	if (!function_exists('Get_Product_Image_URL')) {
		function Get_Product_Image_URL($image_name='',$image_type='THUMB')
		{
			
			$image_name = trim($image_name);
			
			$original_image_name = $image_name;
			
			$arr_image_info = pathinfo($image_name);
			$new_image_name = $arr_image_info['filename'];
			
			$arr_prefix_info = explode('-',$new_image_name);
			
			
					
			$PRD_IMG_PATH  = config('const.PRD_IMG_PATH');
			$PRD_IMG_URL   = config('const.PRD_IMG_URL');
			
			$DEFAULT_IMAGE_PATH = $PRD_IMG_PATH.'medium/';
			$DEFAULT_IMAGE_URL  = $PRD_IMG_URL.'medium/';
			$DEFAULT_NO_IMAGE 	= config('const.NO_IMAGE_PRD_MEDIUM');
			
			/*$DEFAULT_IMAGE_PATH = $PRD_IMG_PATH.'thumb/';
			$DEFAULT_IMAGE_URL  = $PRD_IMG_URL.'thumb/';
			$DEFAULT_NO_IMAGE 	= config('const.NO_IMAGE_PRD_THUMB');*/
			
			if($image_type == 'SMALL')
			{
				/*$DEFAULT_IMAGE_PATH = $PRD_IMG_PATH.'small/';
				$DEFAULT_IMAGE_URL  = $PRD_IMG_URL.'small/';
				$DEFAULT_NO_IMAGE 	= config('const.NO_IMAGE_PRD_SMALL');*/
				
				$DEFAULT_IMAGE_PATH = $PRD_IMG_PATH.'thumb/';
				$DEFAULT_IMAGE_URL  = $PRD_IMG_URL.'thumb/';
				$DEFAULT_NO_IMAGE 	= config('const.NO_IMAGE_PRD_THUMB');
			}
			else if($image_type == 'MEDIUM')
			{
				$DEFAULT_IMAGE_PATH = $PRD_IMG_PATH.'medium/';
				$DEFAULT_IMAGE_URL  = $PRD_IMG_URL.'medium/';
				$DEFAULT_NO_IMAGE 	= config('const.NO_IMAGE_PRD_MEDIUM');
			}
			else if($image_type == 'LARGE')
			{
				$DEFAULT_IMAGE_PATH = $PRD_IMG_PATH.'large/';
				$DEFAULT_IMAGE_URL  = $PRD_IMG_URL.'large/';
				$DEFAULT_NO_IMAGE 	= config('const.NO_IMAGE_PRD_LARGE');
			}
			else if($image_type == 'ZOOM')
			{
				$DEFAULT_IMAGE_PATH = $PRD_IMG_PATH.'zoom/';
				$DEFAULT_IMAGE_URL  = $PRD_IMG_URL.'zoom/';
				$DEFAULT_NO_IMAGE 	= config('const.NO_IMAGE_PRD_ZOOM');
			}			
			
			if(file_exists($DEFAULT_IMAGE_PATH.$image_name) && $image_name != '')
			{
				return $DEFAULT_IMAGE_URL.$image_name;
			}
			else
			{
				return $DEFAULT_NO_IMAGE;
			}	
		}
	}

	if (!function_exists('generatePassword')) {
		function generatePassword($length = 10)
		{
			$consonants = 'abcdefghijklmnopqrstuvwxyz';
			$consonants .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$consonants .= '123456789';
			##$consonants .= '@#$%^()!';
			$password = '';
			$alt = time() % 2;
			for ($i = 0; $i < $length; $i++) {
				$password .= $consonants[(rand() % strlen($consonants))];
			}
			return $password;
		}
	}

	if (!function_exists('encrypt')) {
		function encrypt($str1)
		{
			$private_key = "CEXENCRYPTKEY";
			$len = strlen($str1);

			$encrypt_str = "";

			for ($i = 0; $i < $len; $i++) {
				$char = substr($str1, $i, 1);

				$keychar = substr($private_key, ($i % strlen($private_key)) - 1, 1);

				$char = chr((ord($char) + ord($keychar)) + ord('&'));

				$encrypt_str .= $char;
			}

			$encrypt_str = $this->encoding($encrypt_str);

			return $encrypt_str;
		}
	}

	if (!function_exists('decrypt')) {
		function decrypt($string)
		{
			$private_key = "CEXENCRYPTKEY";
			$string = $this->decoding($string);

			$len = strlen($string);

			$decrypt_str = "";

			for ($i = 0; $i < $len; $i++) {
				$char = substr($string, $i, 1);

				$keychar = substr($private_key, ($i % strlen($private_key)) - 1, 1);

				$char = chr((ord($char) - ord($keychar)) - ord('&'));

				$decrypt_str .= $char;
			}

			return $decrypt_str;
		}
	}

	function encoding($str)
	{
		return base64_encode(gzdeflate($str));
	}

	function decoding($str)
	{
		return gzinflate(base64_decode($str));
	}

	if (!function_exists('GetSubCategory')) {
		function GetSubCategory($category_id, $category_array)
		{
			global $obj, $str_id;
			$result = Category::select('category_id', 'parent_id', 'category_name')->where('parent_id', $category_id)->get()->toArray();
			if (count($result) > 0) {
				$newcat = array();
				for ($i = 0; $i < count($result); $i++) {
					$newcat[] = $result[$i]["category_id"];
				}

				for ($i = 0; $i < count($category_array); $i++) {
					if (in_array($category_array[$i], $newcat)) {

						return NULL;
						break;
					}
				}

				for ($p = 0; $p < count($result); $p++) {
					$str_id .= "," . $result[$p]['category_id'];
					$result_sub = Category::select('category_id', 'parent_id', 'category_name')->where('parent_id',)->get($result[$p]['category_id'])->toArray();
					if (count($result_sub) > 0) {
						GetSubCategory($result[$p]['category_id'], $category_array);
					}
				}
			}
			return $str_id;
		}
	}


	if (!function_exists('Get_Product_Category')) {
		## Retriving Category Details Start Here ################
		function Get_Product_Category($products_id)
		{
			$catres = ProductsCategory::select('category_id')->where('products_id', $products_id)->orderBy('category_id')->get()->toArray();
			if ($catres)
				return $catres;
			else
				return false;
		}
		## Retriving Category Details End Here ############

	}

	if (!function_exists('Get_Category_Structure')) {
		## Retriving Category Structure Start Here ##########
		function Get_Category_Structure($category_id)
		{
			global $obj;

			if (!empty($category_id) or $category_id != '') {
				$vcategory = "";
				$db_sql = Category::select('category_id', 'parent_id', 'category_name')->where('category_id', $category_id)->get()->toArray();

				if (count($db_sql) > 0) {
					$parent_id = $db_sql[0]["parent_id"];
					$vcategory = trim($db_sql[0]["category_name"]);
				}

				if (count($db_sql) > 0) {
					while ($parent_id != '0') {
						$db_sql         = Get_Parent_Category($parent_id);
						$parent_id         = $db_sql[0]["parent_id"];
						$parentcategory = trim($db_sql[0]["category_name"]);
						$vcategory         = $parentcategory . ":" . $vcategory;
					}
				}
				return $vcategory;
			}
		}
		## Retriving Category Structure End Here ##########
	}

	if (!function_exists('Get_Brand_Name')) {
		function Get_Brand_Name($brand_id)
		{
			global $obj;

			$brand_name = "";

			$res_brand = Brand::select('brand_name')->where('brand_id', (int)$brand_id)->get()->toArray();

			if (count($res_brand) > 0) {
				$brand_name =  $res_brand[0]['brand_name'];
			}

			return $brand_name;
		}
	}

	if (!function_exists('Get_Color_Name')) {
		function Get_Color_Name($color_id)
		{
			global $obj;

			$color_name = "";

			$res_color = Color::select('color_name')->where('color_id', (int)$color_id)->get()->toArray();

			if (count($res_color) > 0) {
				$color_name =  $res_color[0]['color_name'];
			}

			return $color_name;
		}
	}

	if (!function_exists('Get_Style_Name')) {
		function Get_Style_Name($style_id)
		{
			global $obj;

			$style_name = "";

			$res_style = Style::select('style_name')->where('style_id', (int)$style_id)->get()->toArray();

			if (count($res_style) > 0) {
				$style_name =  $res_style[0]['style_name'];
			}

			return $style_name;
		}
	}

	if (!function_exists('Get_Style_Names')) {
		function Get_Style_Names($style_id)
		{
			global $obj;

			$style_name = "";

			$res_style = Style::select('style_name')->whereIn('style_id', $style_id)->get()->toArray();

			if (count($res_style) > 0) {
				$style_name =  $res_style;
			}

			return $res_style;
		}
	}

	if (!function_exists('Get_Shape_Name')) {
		function Get_Shape_Name($shape_id)
		{
			global $obj;

			$shape_name = "";

			$res_shape = Shape::select('shape_name')->where('shape_id', (int)$shape_id)->get()->toArray();

			if (count($res_shape) > 0) {
				$shape_name =  $res_shape[0]['shape_name'];
			}

			return $shape_name;
		}
	}

	if (!function_exists('Get_Manufacture_Name')) {
		function Get_Manufacture_Name($manufacture_id)
		{
			global $manufacture_id;
			$manufacture_name = "";
			$manufcature = Manufacturer::select('manufacturer_name')->where('manufacturer_id', (int)$manufacture_id)->get()->toArray();
			if (count($manufcature) > 0) {
				$manufacture_name =  $manufcature[0]['manufacturer_name'];
			}
			return $manufacture_name;
		}
	}

	/*if (!function_exists('Check_Brand_Exist')) {
		function Check_Brand_Exist($brand_name)
		{
			global $obj, $manufacture_id;
			$brand_name = strtolower(trim($brand_name));
			$brand_name = stripslashes($brand_name);
			$res_brand = Brand::select('brand_id')->where('brand_name', $brand_name)->get()->toArray();

			if (count($res_brand) > 0) {
				$brand_name =  $res_brand[0]['brand_id'];
			}

			return $brand_name;
		}
	}*/

	if (!function_exists('Get_Parent_Category')) {
		function Get_Parent_Category($category_id)
		{
			$db_sql = Category::select('category_id', 'parent_id', 'category_name')->where('category_id', $category_id)->get()->toArray();
			return $db_sql;
		}
	}


	if (!function_exists('config')) {
		/**
		 * Get / set the specified configuration value.
		 *
		 * If an array is passed as the key, we will assume you want to set an array of values.
		 *
		 * @param  array|string|null  $key
		 * @param  mixed  $default
		 * @return mixed|\Illuminate\Config\Repository
		 */
		function config($key = null, $default = null)
		{
			if (is_null($key)) {
				return app('config');
			}

			if (is_array($key)) {
				return app('config')->set($key);
			}

			return app('config')->get($key, $default);
		}
	}

	if (!function_exists('app')) {
		/**
		 * Get the available container instance.
		 *
		 * @param  string|null  $abstract
		 * @param  array  $parameters
		 * @return mixed|\Illuminate\Contracts\Foundation\Application
		 */
		function app($abstract = null, array $parameters = [])
		{
			if (is_null($abstract)) {
				return Container::getInstance();
			}

			return Container::getInstance()->make($abstract, $parameters);
		}
	}

	if (!function_exists('map_array')) {
		function map_array($str)
		{
			global $gen_csv_fields_arr, $field_list;
			$str = trim($str);

			if (array_key_exists($str, $gen_csv_fields_arr)) {
				$field_list .=  "`" . $gen_csv_fields_arr[$str]['import_field'] . '`,';
			}
			return trim($str);
		}
	}

	if (!function_exists('Check_Manufacture_Exist')) {
		function Check_Manufacture_Exist($manufacture_name)
		{
			$manufacture_name  = strtolower(trim($manufacture_name));
			$manufacture_name  = stripslashes($manufacture_name);
			if (trim($manufacture_name) != "" and !empty($manufacture_name)) {
				$result = Manufacturer::firstOrCreate(
					['manufacturer_name' => $manufacture_name],
					['display_position' => 99999, 'status' => 1]
				);
				return $result->manufacturer_id;
			} else {
				return 0;
			}
		}
	}

	if (!function_exists('Check_Brand_Exist')) {
		function Check_Brand_Exist($brand_name)
		{
			global $manufacture_id;
			$brand_name  = strtolower(trim($brand_name));
			$brand_name  = stripslashes($brand_name);
			if (trim($brand_name) != "" and !empty($brand_name)) {
				$result = Brand::firstOrCreate(
					['brand_name' => $brand_name],
					['display_position' => 99999, 'status' => 1]
				);
				return $result->brand_id;
			} else {
				return 0;
			}
		}
	}


	if (!function_exists('Check_Color_Exist')) {
		function Check_Color_Exist($color_name)
		{
			$color_name  = strtolower(trim($color_name));
			$color_name  = stripslashes($color_name);
			$color_name = str_replace('   /   ', '/', $color_name);
			$color_name = str_replace('  /  ', '/', $color_name);
			$color_name = str_replace(' / ', '/', $color_name);
			$color_name = str_replace('/  ', '/', $color_name);
			$color_name = str_replace('  /', '/', $color_name);
			$color_name = str_replace(' /', '/', $color_name);
			$color_name = str_replace('/ ', '/', $color_name);
			if (trim($color_name) != "" and !empty($color_name)) {

				$result = Color::where('color_name', '=', $color_name)->where('color_parent_family_id', '!=', '0')->where('status', '=', '1')->first();

				if (empty($result) || $result == null) {
					$color = new Color;
					$color->color_name	= $color_name;
					$color->color_parent_family_id	= 99999;
					$color->status = '1';
					$color->save();

					return $color->color_id;
				} else {
					return $result->color_id;
				}
			} else {
				return 0;
			}
		}
	}

	if (!function_exists('Check_ColorFamily_Exist')) {
		function Check_ColorFamily_Exist($color_name)
		{
			$color_name  = strtolower(trim($color_name));
			$color_name  = stripslashes($color_name);
			$color_name = str_replace('   /   ', '/', $color_name);
			$color_name = str_replace('  /  ', '/', $color_name);
			$color_name = str_replace(' / ', '/', $color_name);
			$color_name = str_replace('/  ', '/', $color_name);
			$color_name = str_replace('  /', '/', $color_name);
			$color_name = str_replace(' /', '/', $color_name);
			$color_name = str_replace('/ ', '/', $color_name);

			if (trim($color_name) != "" || !empty($color_name)) {
				$result = Color::where('color_name', '=', $color_name)->where('color_parent_family_id', '!=', '0')->where('status', '=', '1')->first();

				if ($result) {
					return $result->color_parent_family_id;
				} else {
					return '99999';
				}
			} else {
				return 0;
			}
		}
	}

	if (!function_exists('Check_Shape_Exist')) {
		function Check_Shape_Exist($shape_name)
		{
			$shape_name  = strtolower(trim($shape_name));
			$shape_name  = stripslashes($shape_name);
			if (trim($shape_name) != "" and !empty($shape_name)) {
				$result = Shape::firstOrCreate(
					['shape_name' => $shape_name],
					['status' => 1]
				);
				return $result->shape_id;
			} else {
				return 0;
			}
		}
	}

	if (!function_exists('Check_Style_Exist')) {
		function Check_Style_Exist($style_name)
		{
			$style_name  = strtolower(trim($style_name));
			$style_name  = stripslashes($style_name);
			if (trim($style_name) != "" and !empty($style_name)) {
				$result = Style::firstOrCreate(
					['style_name' => $style_name],
					['status' => 1]
				);
				return $result->style_id;
			} else {
				return 0;
			}
		}
	}

	if (!function_exists('Insert_In_Prod_Style')) {
		function Insert_In_Prod_Style($style_id, $product_id)
		{
			ProductsStyle::create(
				[
					'products_id' => $product_id,
					'style_id' => $style_id,
				]
			);
			return true;
		}
	}


	if (!function_exists('Check_Material_Exists')) {
		function Check_Material_Exists($material_name)
		{
			$material_name  = strtolower(trim($material_name));
			// $material_name  = stripslashes($material_name);
			if (trim($material_name) != "" and !empty($material_name)) {
				$result = Material::firstOrCreate(
					['material_name' => $material_name],
					['status' => 1]
				);
				return $result->material_id;
			} else {
				return 0;
			}
		}
	}

	if (!function_exists('Insert_In_Prod_Material')) {
		function Insert_In_Prod_Material($material_id, $product_id)
		{
			ProductsMaterial::create(
				[
					'products_id' =>  $product_id,
					'material_id' => $material_id,
				]
			);
			return true;
		}
	}

	if (!function_exists('drawCategoryTreeDropdownOption')) {
		function drawCategoryTreeDropdownOption($records = null, $level = 0, $defaultSelectedCategoryId = '')
		{
			$html = array();
			foreach ($records as $record) {
				if ($record->parent_id == 0) {
					$level = 0;
				}
				$levelString = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level) . ($level ? "|" . $level . "|&nbsp;&nbsp;" : "&raquo;&nbsp;");

				$html[] = "<option value=\"" . $record->category_id . "\" " . ($record->category_id == $defaultSelectedCategoryId ? " selected" : "") . ">" . $levelString . $record->category_name . "</option>";

				if ($record->childrenRecursive && count($record->childrenRecursive) > 0) {
					$html = array_merge($html, drawCategoryTreeDropdownOption($record->childrenRecursive, $level + 1, $defaultSelectedCategoryId));;
				}
			}
			return $html;
		}
	}

	if (!function_exists('Check_Product_Category')) {
		function Check_Product_Category($path)
		{ {
				global $parent_id;
				$path = str_replace('"', "", $path);
				//$path = str_replace(' ', "", $path);

				##$cat_name = explode(":", $path);

				## Use Bellow Cat array For Not Inster Blank Category In Table
				$cat_name = array();

				$arr_cat_name = explode(":", $path);

				for ($pp = 0; $pp < count($arr_cat_name); $pp++) {
					if (trim($arr_cat_name[$pp]) != '') {
						$cat_name[] = trim($arr_cat_name[$pp]);
					}
				}
				#######################################################

				$no = count($cat_name);
				//$parent_id = '';
				for ($o = 0; $o < $no; $o++) {
					if ($o == 0) {
						$db_sql = DB::table('hba_category')->select('category_id', 'parent_id', 'category_name')->where('category_name', strtolower(trim($cat_name[$o])))->where('parent_id', 0)->get()->toArray();
						$db_sql = json_decode(json_encode($db_sql), true);
						//echo $db_sql[0]["category_id"]."dfdf"; exit;
						if (count($db_sql) > 0) {
							$parent_id = $db_sql[0]["category_id"];
							$catId = $db_sql[0]["category_id"];
							$parent_product_id = $db_sql[0]["category_id"];
						} else {
							$InsertCategoryTable = array(
								'category_id' 	=> '',
								'parent_id' 	=> '0',
								'url_name' 		=> str_replace(' ', '-', strtolower(trim($cat_name[$o]))),
								'category_name' => trim($cat_name[$o]),
								'template_page'	=> 'category_list',
								'status' 		=> '1'
							);

							$insert_category = DB::table('hba_category')->insertGetId($InsertCategoryTable);
							$parent_id  = $insert_category;
							$parent_product_id = $insert_category;
							$catId = $insert_category;
						}
					} else {
						//echo $parent_id."======".$cat_name[$o]."<br>----------<br>";
						$db_sql = Category::select('category_id', 'parent_id', 'category_name')->where('category_name', strtolower(trim($cat_name[$o])))->where('parent_id', $parent_id)->get()->toArray();
						//dd($db_sql);
						$db_sql = json_decode(json_encode($db_sql), true);
						//dd($cat_name[$o]);
						//echo $db_sql[0]["category_id"].'sdsdsds'; exit;
						if (count($db_sql) > 0) {
							$parent_id = $db_sql[0]["category_id"];
							$catId = $db_sql[0]["category_id"];
						} else {
							$InsertCategoryTable = array(
								'category_id' 	=> '',
								'parent_id' 	=> $parent_id,
								'url_name' 		=> str_replace(' ', '-', strtolower(trim($cat_name[$o]))),
								'category_name' => trim($cat_name[$o]),
								'template_page'	=> 'product_list',
								'status' 		=> '1'
							);
							//dd($cat_name[$o]);					
							$insert_category = DB::table('hba_category')->insertGetId($InsertCategoryTable); //Category::create($InsertCategoryTable);
							$parent_id = $parent_id;
							$catId = $insert_category;
						}
					}
				}
				//echo "<pre>"; print_r($parent_id); 
				//exit;
				return array('parentId' => $parent_id, 'categoryId' => $catId, 'parent_product_id' => $parent_product_id);
				// return $parent_id; // its category id
			}
		}
	}

	if (!function_exists('Insert_In_Prod_Cat')) {
		## insert into product cat relation table
		function Insert_In_Prod_Cat($category_id, $products_id)
		{
			$db_sql = ProductsCategory::select('*')->where('category_id', $category_id)->where('products_id', $products_id)->get()->toArray();
			$db_sql = json_decode(json_encode($db_sql), true);

			if (count($db_sql) <= 0) {
				$InsertPrCategoryTable = array(
					'category_id' => $category_id,
					'products_id' => $products_id
				);
				$db_sql = DB::table('hba_products_category')->insert($InsertPrCategoryTable);
			}

			if ($db_sql)
				return $db_sql;
			else
				return false;
		}
		## End for inserting in product cat relation table
	}

	if (!function_exists('GetMailTemplate')) {
		## featch email template from database
		function GetMailTemplate($Template)
		{
			if ($Template != "") {
				$TemplateDetails = \App\Models\EmailTemplates::select('subject', 'mail_body')
					->where('template_var_name', '=', $Template)
					->where('status', '=', '1')
					->get();

				if ($TemplateDetails && $TemplateDetails->count() > 0)
					return $TemplateDetails;
				else
					return false;
			} else {
				return false;
			}
		}
		## end featch email template from database
	}

	######### Generate Customer Password Function Start #########
	if (!function_exists('generateCustPassword')) {
		function generateCustPassword($length = 10)
		{
			$consonants = 'abcdefghijklmnopqrstuvwxyz';
			$consonants .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$consonants .= '123456789';

			$password = '';
			$alt = time() % 2;
			for ($i = 0; $i < $length; $i++) {
				$password .= $consonants[(rand() % strlen($consonants))];
			}
			return $password;
		}
	}

	######### Generate Customer Password Function End #########
	if (!function_exists('Make_Price')) {
		function Make_Price($text, $currency_symbol = false, $round = false,$currency_symbol_icon='')
		{
			
		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(empty($currency_symbol_icon) && $currency_symbol){
			if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode]) && empty($currency_symbol_icon)){
				$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
				$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
				$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
				$currency_symbol_icon  = $curencySymbol;
				$text = $text*$curencyvalue; 
			}else{
				$currency_symbol_icon = '';
			}
		}

			$text1	 = $text;

			if (preg_match("/admin/i", Route::current()->uri)) {
				$text1 = $text;
				return number_format($text1, 2, '.', '');
			}

			if ($currency_symbol == true) {
				if ($round == true) {
					return config('const.SITE_CURRENCY_SYMBOL') . number_format(round($text1), 0, '', ',');
				} else {
					if(empty($currency_symbol_icon)){
						return config('const.SITE_CURRENCY_SYMBOL') . number_format((float)$text1, 2, '.', ',');
					}else{
						return $currency_symbol_icon.' ' . number_format((float)$text1, 2, '.', ',');
					}
				}
			} else {
				return number_format($text1, 2, '.', '');
			}
		}
	}

	######### Get Sales Representative By customer Start #########
	if (!function_exists('GetCustomer')) {
		function GetCustomer($customerId)
		{
			return \App\Models\Customer::select('first_name as sfirstname', 'email as semail')
				->where('customer_id', $customerId)
				->where('status', '1')->get();
		}
	}
	######### Get Sales Representative By customer End #########


	######### Get Sales Representative By customer Start #########
	if (!function_exists('get_rand_sales_representative')) {
		function get_rand_sales_representative()
		{
			$cur_date = date('Y-m-d');

			$sales_person_res =  \App\Models\SalesRepresentative::select('inquiry_count_date', 'representative_id')
				->where('inquiry_count_date', '!=', $cur_date)
				->where('status', '1')->get();

			foreach ($sales_person_res as $key => $value) {

				$InsertAry =	array(
					'inquiry_count_date'	=> $cur_date,
					'per_day_inquiry_count'	=> 0
				);

				\App\Models\SalesRepresentative::where('representative_id', '=', $value->representative_id)->update($InsertAry);
			}

			$sales_person_res1 =  \App\Models\SalesRepresentative::select('representative_id')
				->where('inquiry_count_date', '=', $cur_date)
				->where('per_day_inquiry_count', '<', 'per_day_inquiry')
				->where('status', '1')->orderBy('rank', 'ASC')->get();

			if (count($sales_person_res1) > 0) {

				$InsertAry1 = array('per_day_inquiry_count'	=> $cur_date);
				\App\Models\SalesRepresentative::where('representative_id', '=', $sales_person_res1[0]->representative_id)->update($InsertAry1);

				return $sales_person_res1[0]->representative_id;
			}

			/*******************************************************************************/

			$sales_person_res2 =  \App\Models\SalesRepresentative::select('representative_id')
				->where('representative_id', '>', 'last_ins_id')->where('status', '1')->orderBy('representative_id', 'ASC')->limit(1)->get();

			if (count($sales_person_res2)) {
				$where_cond = " 1 = 1 ";
				DB::statement("UPDATE cx_sales_representative SET last_ins_id = '.$sales_person_res2[0]->representative_id.' WHERE '.$where_cond.'");

				return $sales_person_res2[0]->representative_id;
			} else {

				$sales_person_res3 =  \App\Models\SalesRepresentative::select('representative_id')->where('status', '1')->orderBy('representative_id', 'ASC')->limit(1)->get();

				if (count($sales_person_res3) > 0) {
					$InsertAry2 = array('last_ins_id'	=> $sales_person_res3[0]->representative_id);
					\App\Models\SalesRepresentative::where(' 1 ', '=', ' 1 ')->update($InsertAry2);

					return $sales_person_res3[0]->representative_id;
				}
			}

			return false;
		}
	}
	######### Get Sales Representative By customer End #########

	######### Get Sales Representative By customer Start #########
	/*if (!function_exists('get_cust_sales_representative')) {
		function get_cust_sales_representative($customerId)
		{
			$exe_rec =  \App\Models\Customer::select('representative_id')->where('customer.customer_id', $customerId)->get();

			if (count($exe_rec) > 0) {
				$sales_person_res =  \App\Models\SalesRepresentative::select('representative_id')
					->where('representative_id', $exe_rec[0]->representative_id)
					->where('status', '1')->get();

				if(count($sales_person_res)>0 && $sales_person_res[0]->representative_id){
					return $sales_person_res[0]->representative_id;
				}else{
					return get_rand_sales_representative();
				}
			} else {
				return get_rand_sales_representative();
			}
		}
	}*/
	######### Get Sales Representative By customer End #########



	if (!function_exists(('getAllCategories'))) {
		function getAllCategories($records = null, $level = 0, $defaultSelectedCategoryId = '')
		{
			$html = array();
			foreach ($records as $record) {
				if ($record->parent_id == 0) {
					$level = 0;
				} else {
					$html[$record->parent_id][] = $record->category_id;
				}
				if ($record->childrenRecursive && count($record->childrenRecursive) > 0) {
					$html[] = getAllCategories($record->childrenRecursive, $level + 1, $defaultSelectedCategoryId);;
				}
			}
			return $html;
		}
	}


	if (!function_exists('remove_special_chars')) {
		function remove_special_chars($str)
		{
			$str = preg_replace("/[,^!<>@\/()\"&#$*~`{}'?:;.?%]*/", "", trim($str));
			$str = str_replace("  ", " ", strtolower($str));
			$str = str_replace(" ", "-", strtolower($str));
			$str = str_replace("--", "-", strtolower($str));
			$str = str_replace("--", "-", strtolower($str));
			return $str;
		}
	}


	/*
	 * Function will return breadcrumb data for product details page.
	 * */
	if (!function_exists('detailBreadcrumb')) {
		function detailBreadcrumb($productId, $categoryId, $parentCategoryId,$productObj=array())
		{
			$productCategoryId = $categoryId;

			$categoryHierarchy = getCategoryHierarchy($productCategoryId);
			if($_SERVER["REMOTE_ADDR"]=='49.36.91.121'){
				//dd($categoryHierarchy);
			}
			$breadcrumbContent = '';

			if ($categoryHierarchy != false) {
				if(isset($productObj) && !empty($productObj)){
					$productData = $productObj;
				}else{
					$productData = Product::select('product_name')->find($productId);
				}
				$breadcrumbContent .= '<div class="breadcrumb">';

				$breadcrumbContent .= '<a href="' . config("const.SITE_URL") . '">Home';
				$breadcrumbContent .= '<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">';
				$breadcrumbContent .= '<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>';
				$breadcrumbContent .= '</svg>';
				$breadcrumbContent .= '</a>';

				if (str_contains(url()->previous(), 'clearance.html')) {
					$breadcrumbContent .= '<a href="' . config("const.SITE_URL") . '/clearance.html' . '">Clearance';
					$breadcrumbContent .= '<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">';
					$breadcrumbContent .= '<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>';
					$breadcrumbContent .= '</svg>';
					$breadcrumbContent .= '</a>';
				} else {
					foreach ($categoryHierarchy as $key => $categoryData) {
						//$categoryData = Category::find($catId);
						if($categoryData->parent_id==0 && $categoryData->template_page=='category_list'){
							$breadcrumbContent .= '<a href="' . config("const.SITE_URL") . '/'.$categoryData->url_name . '.html">' . ucwords($categoryData->category_name);
							
						}else{
							$breadcrumbContent .= '<a href="' . config("const.SITE_URL") . '/' . $categoryData->url_name . '/cid/' . $categoryData->category_id . '">' . ucwords($categoryData->category_name);
						}
						
						$breadcrumbContent .= '<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">';
						$breadcrumbContent .= '<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>';
						$breadcrumbContent .= '</svg>';
						$breadcrumbContent .= '</a>';
					}
				}
				
				$breadcrumbContent .= '<span class="active">' . ucwords($productData['product_name']) . '</span>';

				$breadcrumbContent .= '</div>';
			}

			return $breadcrumbContent;
		}
	}

	/*
	 * Function will return category hierarchy.
	 * */
	if (!function_exists('getCategoryHierarchy')) {
		function getCategoryHierarchy($categoryId)
		{
			$productCategoryId = $categoryId;
			$categoryHierarchyArray = [];
			$parentCategoryFlag = 0;
		try {	
			$getAllCategoryRes = get_all_categories();
			//dd($getAllCategoryRes);
			if(isset($getAllCategoryRes) && !empty($getAllCategoryRes)){
				while ($parentCategoryFlag == 0) {
					
					//replace code for optimization category id wise
					/*$categoryRes = Category::select('category_id', 'parent_id')
						/*->where('status', '=', '1')*
						->where('category_id', '=', $productCategoryId)
						->first();*/
					$categoryRes = $getAllCategoryRes->filter(function ($category, $key) use($productCategoryId) {
						if($category->category_id == $productCategoryId){
							return true;
						}else{
							return false;
						}
					})->first();
					if (!empty($categoryRes) && $categoryRes->parent_id != '0') {
						array_push($categoryHierarchyArray, $categoryRes);
						$productCategoryId = $categoryRes->parent_id;
					} elseif (!empty($categoryRes) && $categoryRes->parent_id == '0') {
						array_push($categoryHierarchyArray, $categoryRes);
						$parentCategoryFlag = 1;
					} else {
						return false;
					}
				}
				krsort($categoryHierarchyArray);
				return $categoryHierarchyArray;
			 }
			} catch (Throwable $e) {
				report($e);
				return $categoryHierarchyArray;
			}
			
		}
	}

	/*
	 * Function will get canonical url.
	 * */
	if (!function_exists('getCanonicalURL')) {
		function getCanonicalURL($productId)
		{
			try {
				$productData = Product::find($productId);
				$productCategoryData = ProductsCategory::where('products_id', $productData->product_id)->first();

				if (!empty($productCategoryData)) {
					$categoryId = $productCategoryData->category_id;
					$categoryData = Category::find($categoryId);
					$productName = trim($productData->product_name);
					$productName = remove_special_chars($productName);
					$productSKU = strtolower($productData->sku);

					$canonicalURL = route('product-detail', ['category_name1' => $categoryData->url_name, 'product_name' => $productName, 'product_sku' => $productSKU]);
					return $canonicalURL;
				} else {
					return false;
				}
			} catch (\Exception $exception) {
				return false;
			}
		}
	}

	if (!function_exists('getDateTimeByTimezone')) {
		function getDateTimeByTimezone($formate,$dateTime=''){
			date_default_timezone_set('US/Eastern');
			if(isset($dateTime) && empty($dateTime)){
				
				$dateTime = date('Y-m-d h:i:s');
			}
			$dt = new DateTime($dateTime);
			$tz = new DateTimeZone('US/Eastern'); 
			$dt->setTimezone($tz);
			$newDateTime  = $dt->format($formate);
			date_default_timezone_set('UTC');
			return $newDateTime;
		}
	}

	if (!function_exists('GetFrontMegaMenu')) {
		function GetFrontMegaMenu()
		{
			//$menu_array = Cache::remember('menu_array', 3600, function() {
				$parentCategories =  DB::table('hba_menu_front')
									->select('menu_title', 'menu_id', 'menu_link', 'rank', 'status','parent_id')
									->where('parent_id', '=', 0)
									->where('status', '=', '1')
									->orderBy('rank', 'ASC')
									->get()->toArray();
				
				$mainArray = [];
				$level = 1;
				if(count($parentCategories) > 0) {
					foreach($parentCategories as $pcKey => $pcValue) {
						$mainArray[$pcKey]['menu_id'] = $pcValue->menu_id;
						$mainArray[$pcKey]['menu_title'] = $pcValue->menu_title;
						$mainArray[$pcKey]['menu_link'] = $pcValue->menu_link;
						$mainArray[$pcKey]['rank'] = $pcValue->rank;
						$mainArray[$pcKey]['status'] = $pcValue->status;
						$mainArray[$pcKey]['parent_id'] = $pcValue->parent_id;
						$parentCategories[$pcKey]->level = $level;
						$labels =  DB::table('hba_menu_front')
									->select('menu_title', 'menu_id', 'menu_link', 'rank', 'status','parent_id')
									->where('parent_id', '=', $pcValue->menu_id)
									->where('is_label', '=', '1')
									->where('status', '=', '1')
									->orderBy('rank', 'ASC')
									->get()->toArray();
						$cat_labels_count =  DB::table('hba_menu_front')
									->select('menu_title', 'menu_id', 'menu_link', 'rank', 'status')
									->where('parent_id', '=', $pcValue->menu_id)
									->where('is_label', '=', '1')
									->where('menu_title', '!=', 'Custom Tag Link - Banner Section')
									->where('status', '=', '1')
									->count();
						$parentCategories[$pcKey]->label_count = count($labels);
						$mainArray[$pcKey]['label_count'] = count($labels);
						$total_columns = 5;
						$display_banners_count = $total_columns - $cat_labels_count;
						$mainArray[$pcKey]['display_banners_count'] = $display_banners_count;
						$labelArray = [];
						if(count($labels) > 0) {
							foreach($labels as $labelKey => $labelVaue) {
								$labelArray[$labelKey]['menu_id'] = $labelVaue->menu_id;
								$labelArray[$labelKey]['menu_title'] = $labelVaue->menu_title;
								$labelArray[$labelKey]['menu_link'] = $labelVaue->menu_link;
								// $labelArray[$labelKey]['is_below'] = $labelVaue->is_below;
								$labelArray[$labelKey]['rank'] = $labelVaue->rank;
								$labelArray[$labelKey]['status'] = $labelVaue->status;
								$labelArray[$labelKey]['parent_id'] = $labelVaue->parent_id;
								$labelArray[$labelKey]['childs'] = array();
								getSubCats($labelVaue->menu_id, $labelArray[$labelKey]['childs'],$level+1);
							}
						}
						$mainArray[$pcKey]['label'] = $labelArray;
					}
				}
				
				$menu_array = $mainArray;
				return $menu_array;
			//});
		}
	}
	if (!function_exists('getSubCats')) {
		function getSubCats($parent_id = 0, &$categoriesArray = array(),$level=0) {
			$allSubCategories =  DB::table('hba_menu_front')
					->select('menu_title', 'menu_id', 'menu_link', 'rank', 'status', 'menu_image', 'menu_image1', 'menu_image2', 'menu_label', 'menu_label1', 'menu_label2', 'menu_custom_link', 'menu_custom_link1', 'menu_custom_link2')
					->where('parent_id', '=', (int)$parent_id)
					->where('is_label', '=', '0')
					->where('status', '=', '1')
					->orderBy('rank', 'ASC')
					->get()->toArray();

			foreach($allSubCategories as $k => $category) {
				$categoriesArray[$k]['menu_id'] = $category->menu_id;
				$categoriesArray[$k]['menu_title'] = $category->menu_title;
				$categoriesArray[$k]['menu_link'] = $category->menu_link;

				if (file_exists(config('const.MENUIMAGE_PATH') . $category->menu_image) && $category->menu_image != '') {
					$newimageVal = config('const.MENUIMAGE_PATH')  . stripslashes($category->menu_image);
					$verP = filemtime($newimageVal);
					$categoriesArray[$k]['menu_image'] = config('const.MENUIMAGE_URL') . $category->menu_image . "?ver=" . $verP;
				}else{
					$categoriesArray[$k]['menu_image'] = $category->menu_image;
				}

				if (file_exists(config('const.MENUIMAGE_PATH') . $category->menu_image1) && $category->menu_image1 != '') {
					$newimageVal = config('const.MENUIMAGE_PATH')  . stripslashes($category->menu_image1);
					$verP = filemtime($newimageVal);
					$categoriesArray[$k]['menu_image1'] = config('const.MENUIMAGE_URL') . $category->menu_image1 . "?ver=" . $verP;
				}else{
					$categoriesArray[$k]['menu_image1'] = $category->menu_image1;
				}

				if (file_exists(config('const.MENUIMAGE_PATH') . $category->menu_image2) && $category->menu_image2 != '') {
					$newimageVal = config('const.MENUIMAGE_PATH')  . stripslashes($category->menu_image2);
					$verP = filemtime($newimageVal);
					$categoriesArray[$k]['menu_image2']  = config('const.MENUIMAGE_URL') . $category->menu_image2 . "?ver=" . $verP;
				}else{
					$categoriesArray[$k]['menu_image2'] = $category->menu_image2;
				}
				
				$categoriesArray[$k]['menu_label'] = $category->menu_label;
				$categoriesArray[$k]['menu_label1'] = $category->menu_label1;
				$categoriesArray[$k]['menu_label2'] = $category->menu_label2;
				$categoriesArray[$k]['menu_custom_link'] = $category->menu_custom_link;
				$categoriesArray[$k]['menu_custom_link1'] = $category->menu_custom_link1;
				$categoriesArray[$k]['menu_custom_link2'] = $category->menu_custom_link2;
				
				$categoriesArray[$k]['rank'] = $category->rank;
				$categoriesArray[$k]['status'] = $category->status;
				$categoriesArray[$k]['level'] = $level;
				$categoriesArray[$k]['childs'] = array();
				getSubCats($category->menu_id,$categoriesArray[$k]['childs'],$level+1);
			}
		}
	}
	if (!function_exists('getCategoriesHTML')) {
		function getCategoriesHTML($category) 
		{
			
			$html = "";
			foreach ($category as $cat_id)
			{	
				if (count($cat_id['childs']) > 0) {
					// $html .='<li><a href="'.$cat_id['menu_link'].'" class="mm-sub-link">'.$cat_id['menu_title'].'</a></li>';
					$html .= getCategoriesHTML($cat_id['childs']);
				} else {
						$menu_title=$cat_id['menu_title'];
						$menu_title=title($menu_title);
						if(!empty($cat_id['menu_link']))
						{
							$menu_link=$cat_id['menu_link'];
							$on_click = '';
						}
						else
						{
							//$menu_link='javascript:void(0)';
							$menu_link=config('const.SITE_URL').'/#'.title($cat_id['menu_title']);
							$on_click = 'onClick="return false;"';
						}
						$html .='<li><a href="'.$menu_link.'" '.$on_click.' title="'.$cat_id['menu_title'].'" aria-label="'.$cat_id['menu_title'].'" >'.$cat_id['menu_title'].'</a></li>';
					
				}
			}

			return $html;
		}
	}
	if (!function_exists('getCategoriesHTMLMob')) {
		function getCategoriesHTMLMob($category) 
		{
	
			$html = "";
			foreach ($category as $cat_id)
			{	
				if (count($cat_id['childs']) > 0) {
					// $html .='<li><a href="'.$cat_id['menu_link'].'" class="mm-sub-link">'.$cat_id['menu_title'].'</a></li>';
					$html .= getCategoriesHTMLMob($cat_id['childs']);
				} else {
						$menu_title=$cat_id['menu_title'];
						$menu_title=title($menu_title);
						if(!empty($cat_id['menu_link']))
						{
							$menu_link=$cat_id['menu_link'];
							$on_click = '';
						}
						else
						{
							//$menu_link='javascript:void(0)';
							$menu_link=config('const.SITE_URL').'/#'.title($cat_id['menu_title']);
							$on_click = 'onClick="return false;"';
						}
						$html .='<a href="'.$menu_link.'" '.$on_click.'  title="'.$cat_id['menu_title'].'" aria-label="'.$cat_id['menu_title'].'">'.$cat_id['menu_title'].'</a>';
					
				}
			}

			return $html;
		}
	}
	if (!function_exists('title')) {
	function title($menu_title)
	{
		$menu_title=str_replace(" " ,"-",(strtolower($menu_title)));
		return $menu_title;					
	}
	}
	if (!function_exists('diffInMinutes')) {
		function diffInMinutes($firstdatetime, $seconddatetime)
		{  
			$to = Carbon::createFromFormat('Y-m-d H:i:s', $firstdatetime);
			$from = Carbon::createFromFormat('Y-m-d H:i:s', $seconddatetime);
			return $diffInMinutes = $to->diffInMinutes($from);
		}
	}
	if (!function_exists('uniqueArray')) {
		function uniqueArray($array, $column_name)
		{  
			//print_r(array_map('strtolower', array_column($array, $column_name)));
			$array_unique_column_array = array_unique(array_map('strtolower',array_column($array, $column_name)));
			return $uniqueArray = array_values(array_intersect_key($array, $array_unique_column_array));
		}
	} 
	/*if (!function_exists('BrandsList')) {
	
		function BrandsList($BrandChar='')
		{
			
			$BrandList = [];
			$brandObj = DB::table('hba_products as po')
			->join('hba_brand as b','po.brand_id','=','b.brand_id')
			->select('po.product_id','po.product_name','b.brand_id','b.brand_name','b.brand_description','b.brand_logo_image')
			->where('po.status','=','1')
			->where('b.status','=','1');
			if($BrandChar != '')
			{
				$BrandQry = $brandObj;
				if($BrandChar == '#')
					$BrandQry->where('b.brand_name','regexp','^[0-9]+');
				else
					$BrandQry->where('b.brand_name','like',$BrandChar.'%');
				$Brands = $BrandQry->groupBy('b.brand_id')->orderBy('b.brand_name')->get();			
				
				if($Brands && $Brands->count() > 0)
				{
					foreach($Brands as $Brand)
					{
						$Name = remove_special_chars($Brand->brand_name);
						$Brand_name= ucwords(strtolower($Brand->brand_name));
						$BrandList[]=[
							'Name' => $Brand_name,
							'Link' => config('const.SITE_URL').'/brand/'.$Name.'/brid/'.$Brand->brand_id, 
						];
					}
				}
				return $BrandList;
			}else{

			}
			
			foreach (range('A', 'Z') as $char){
				$Brands = DB::table('hba_products as po')
							->join('hba_brand as b','po.brand_id','=','b.brand_id')
							->select('po.product_id','po.product_name','b.brand_id','b.brand_name','b.brand_description','b.brand_logo_image')
							->where('po.status','=','1')
							->where('b.status','=','1')
							->where('b.brand_name','like',$char.'%')
							->groupBy('b.brand_id')
							->orderBy('b.brand_name')
							->get();			
				
				if($Brands && $Brands->count() > 0)
				{
					foreach($Brands as $Brand)
					{
						$Name = remove_special_chars($Brand->brand_name);
						$Brand_name= ucwords(strtolower($Brand->brand_name));
						$BrandList[$char][]=[
							'Name' => $Brand_name,
							'Link' => config('const.SITE_URL').'/brand/'.$Name.'/brid/'.$Brand->brand_id, 
						];
					}
				}
			}
			
			$Brands = DB::table('hba_products as po')
							->join('hba_brand as b','po.brand_id','=','b.brand_id')
							->select('po.product_id','po.product_name','b.brand_id','b.brand_name','b.brand_description','b.brand_logo_image')
							->where('po.status','=','1')
							->where('b.status','=','1')
							->where('b.brand_name','regexp','^[0-9]+')
							->groupBy('b.brand_id')
							->orderBy('b.brand_name')
							->get();

			foreach($Brands as $Brand)
			{
				$Name = remove_special_chars($Brand->brand_name);
				$Brand_name= ucwords(strtolower($Brand->brand_name));
				$BrandList['#'][]=[
					'Name' => $Brand_name,
					'Link' => config('const.SITE_URL').'/brand/'.$Name.'/brid/'.$Brand->brand_id, 
				];
			}
			return $BrandList;	


		
		}
	}*/
	if (!function_exists('BrandsList')) {
	
		function BrandsList($BrandChar='',$category_id='',$category_name='')
		{
			
			$BrandList = [];
			$brandObj = getAllBrand(); 
			if($BrandChar != '')
			{
				$BrandQry = $brandObj;
				if(isset($brandObj) && !empty($brandObj)){
					
					$filterBrandArr = $brandObj->filter(function ($brand, $key) use($BrandChar,$category_id) {
					if($BrandChar == '#'){
							if(!empty($category_id)){
								if($brand->category_id==$category_id){
								return 1 === preg_match('/^[0-9]+/', strtolower($brand->brand_name));
							}else{
								return false;
							}
						}else{
							return 1 === preg_match('/^[0-9]+/', strtolower($brand->brand_name));
						}					
					}else{
						$char1 = $BrandChar.'%';
						if(!empty($category_id)){
							if($brand->category_id==$category_id){
								return 1 === preg_match(sprintf('/^%s$/i', preg_replace('/(^%)|(%$)/', '.*', strtolower($char1))), strtolower($brand->brand_name));
							}else{
								return false;
							}
						}else{
							$char1 = $BrandChar.'%';
							return 1 === preg_match(sprintf('/^%s$/i', preg_replace('/(^%)|(%$)/', '.*', strtolower($char1))), strtolower($brand->brand_name));
						}
					}
				})->toArray();
					
				}	
				$uniqueBrandArr = array();
				if(isset($filterBrandArr) && !empty($filterBrandArr)){
					$filterBrandArr = json_decode(json_encode($filterBrandArr), true);
					$uniqueBrandArr = array_values($filterBrandArr);

					$uniqueBrandArr = uniqueArray($uniqueBrandArr,'brand_name');
					foreach($uniqueBrandArr as $Brand)
					{
						$Name = remove_special_chars($Brand['brand_name']);
						$Brand_name= ucwords(strtolower($Brand['brand_name']));
						$Brand_id= $Brand['brand_id'];
						if(!empty($category_name)){
							$category_url = title($category_name).'/';
						}else{
							$category_url = 'brand'.'/';
						}
						$BrandList[]=[
							'Name' => $Brand_name,
							'Brand_id' => $Brand_id,
							'Link' => config('const.SITE_URL').'/'.$category_url.$Name.'/brid/'.$Brand['brand_id'], 
						];
					}
				}
				return $BrandList;
			}else{

			}
			
			foreach (range('A', 'Z') as $char){

				$filterBrandArr = array();
				if(isset($brandObj) && !empty($brandObj)){
					$filterBrandArr = $brandObj->filter(function ($brand, $key) use($char,$category_id) {
						if(!empty($category_id)){
							if($brand->category_id==$category_id){
								$char1 = $char.'%';
								return 1 === preg_match(sprintf('/^%s$/i', preg_replace('/(^%)|(%$)/', '.*', strtolower($char1))), strtolower($brand->brand_name));
							}else{
								return false;
							}
						}else{
							$char1 = $char.'%';
							return 1 === preg_match(sprintf('/^%s$/i', preg_replace('/(^%)|(%$)/', '.*', strtolower($char1))), strtolower($brand->brand_name));
						}	
					
					})->toArray();
				}	
				$uniqueBrandArr = array();
				if(isset($filterBrandArr) && !empty($filterBrandArr)){
					$filterBrandArr = json_decode(json_encode($filterBrandArr), true);
					$uniqueBrandArr = array_values($filterBrandArr);
					$uniqueBrandArr = uniqueArray($uniqueBrandArr,'brand_name');
					foreach($uniqueBrandArr as $Brand)
					{
						$Name = remove_special_chars($Brand['brand_name']);
						$Brand_name= ucwords(strtolower($Brand['brand_name']));
						if(!empty($category_name)){
							$category_url = title($category_name).'/';
						}else{
							$category_url = 'brand'.'/';
						}
						$Brand_id= $Brand['brand_id'];
						$BrandList[$char][]=[
							'Name' => $Brand_name,
							'Brand_id' => $Brand_id,
							'Link' => config('const.SITE_URL').'/'.$category_url.$Name.'/brid/'.$Brand['brand_id'], 
						];
					}
				}
			}
			$filterBrandArr = array();
			if(isset($brandObj) && !empty($brandObj)){
					$numberFilterBrandArr = $brandObj->filter(function ($brand, $key) use($category_id)  {
						if(!empty($category_id)){
							if($brand->category_id==$category_id){
								return 1 === preg_match('/^[0-9]+/', strtolower($brand->brand_name));
							}else{
								return false;
							}
						}else{
							return 1 === preg_match('/^[0-9]+/', strtolower($brand->brand_name));
						}	
				})->toArray();
			}	
			$uniqueBrandArr = array();
			if(isset($numberFilterBrandArr) && !empty($numberFilterBrandArr)){
				$numberFilterBrandArr = json_decode(json_encode($numberFilterBrandArr), true);
				$uniquenumberBrandArr = array_values($numberFilterBrandArr);
				$uniqueNumberBrandArr = uniqueArray($uniquenumberBrandArr,'brand_name');
				foreach($uniqueNumberBrandArr as $Brand)
				{
					$Name = remove_special_chars($Brand['brand_name']);
					$Brand_name= ucwords(strtolower($Brand['brand_name']));
					$Brand_id= $Brand['brand_id'];
					if(!empty($category_name)){
						$category_url = title($category_name).'/';
					}else{
						$category_url = 'brand'.'/';
					}
					$BrandList['#'][]=[
						'Name' => $Brand_name,
						'Brand_id' => $Brand_id,
						'Link' => config('const.SITE_URL').'/'.$category_url.$Name.'/brid/'.$Brand['brand_id'], 
					];
				}
			}
			return $BrandList;
		}
	}
	/*if (!function_exists('getAllBrand')) {
		function getAllBrand() {
			try {
				if (Cache::has('getAllBrandProductWise_cache')){
					$brandObj = Cache::get('getAllBrandProductWise_cache');
				} else {
					$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
					$brandEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
					$branddiffInMinutes = diffInMinutes($currentDatetime,$brandEndDatetime);
					$brandObj = DB::table('hba_products as po')
					->join('hba_brand as b','po.brand_id','=','b.brand_id')
					->select('po.product_id','po.product_name','b.brand_id','b.brand_name','b.brand_description','b.brand_logo_image','po.parent_category_id')
					->where('po.status','=','1')
					->where('b.status','=','1')
					->orderBy('b.brand_name')->get();
					Cache::put('getAllBrandProductWise_cache', $brandObj,$branddiffInMinutes);
				}	
				return $brandObj;
			} catch (Throwable $e) {
				report($e);
				return  $brandObj = [];		
			}
		}
	}*/
	if (!function_exists('getAllBrand')) {
		function getAllBrand() {
			try {
				if (Cache::has('getAllBrandProductWise_cache')){
					$brandObj = Cache::get('getAllBrandProductWise_cache');
				} else {
					
					$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
					$brandEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
					$branddiffInMinutes = diffInMinutes($currentDatetime,$brandEndDatetime);
					
					$brandObj = DB::table('hba_brand as b')
					->join('hba_brand_category as bc','b.brand_id','=','bc.brand_id')
					->join('hba_products as po','b.brand_id','=','po.brand_id')
					->select('po.product_id','po.product_name','b.brand_id','b.brand_name','b.brand_description','b.brand_logo_image','bc.category_id')
					->where('po.status','=','1')
					->where('b.status','=','1')
					->orderBy('b.brand_name')->get();
					

					Cache::put('getAllBrandProductWise_cache', $brandObj,$branddiffInMinutes);
				}	
				return $brandObj;
			} catch (Throwable $e) {
				report($e);
				return  $brandObj = [];		
			}
		}
	}
	if (!function_exists('remove_special_chars')) {
		function remove_special_chars($str) {
		$str = preg_replace("/[,^!<>@\/()\"&#$*~`{}'?:;.?%]*/", "", trim($str));
		$str = str_replace("  ", " ", strtolower($str));
		$str = str_replace(" ", "-", strtolower($str));
		$str = str_replace("--", "-", strtolower($str));
		$str = str_replace("--", "-", strtolower($str));
		return $str;
	}
	}
	if (!function_exists('getPopularBrands')) {
		function getPopularBrands()
		{
			
				$popularBrands = [];	
				
				$BrandList = DB::table('hba_products as po')
							->join('hba_brand as b','po.brand_id','=','b.brand_id')
							->select('po.product_id','po.product_name','b.brand_id','b.brand_name','b.brand_description','b.brand_logo_image')
							->where('po.status','=','1')
							->where('b.is_popular','=','Yes')
							->where('b.status','=','1')
							->groupBy('b.brand_id')
							->orderBy('b.brand_name', 'ASC')
							->limit(18)
							->get();

				if($BrandList && $BrandList->count() > 0)
				{
					foreach($BrandList as $Brand)
					{
						$Name = remove_special_chars($Brand->brand_name);
						$Brand_name= ucwords(strtolower($Brand->brand_name));
						if(file_exists(config('const.BRAND_IMAGE_PATH').$Brand->brand_logo_image) && $Brand->brand_logo_image !='')
						{
							$brand_logo_image = config('const.BRAND_IMAGE_URL').$Brand->brand_logo_image;
						} else {
							$brand_logo_image = config('const.BRAND_DEFAULT_IMG');
						}
						$popularBrands[]=[
							'Name' => $Brand_name,
							'ImageLogo' => $brand_logo_image,
							'Link' => config('const.SITE_URL').'/brand/'.$Name.'/brid/'.$Brand->brand_id, 
						];
					}
				}

				$popular_brands = $popularBrands;
				return $popular_brands;
			
			
		}
	}
	if (!function_exists('Get_Brand_Image_URL')) {
		function Get_Brand_Image_URL($brand_logo_image)
		{
			if(file_exists(config('const.BRAND_IMAGE_PATH').$brand_logo_image) && $brand_logo_image !='')
			{
				$brand_logo_image = config('const.BRAND_IMAGE_URL').$brand_logo_image;
			} else {
				$brand_logo_image = config('const.BRAND_DEFAULT_IMG');
			}
			return $brand_logo_image;
		}		
	}
	if (!function_exists('Get_Main_Cats_Tree')) {
		function Get_Main_Cats_Tree($CatArray)
		{
			$Categories = Category::where('status','=','1')->orderBy('category_id')->get();
			$AllCats = New_Cat_Tree($Categories);
			$SubCatsTree=[];$key=0;$SubCats=[];
			foreach($AllCats as $MainCat)
			{
				$SubCatsTree[$key][]=['category_id' => $MainCat->category_id, 'category_name' => $MainCat->category_name, 'link' => config('global.SITE_URL').remove_special_chars($MainCat->category_name).'/cid/'.$MainCat->category_id,'Level' => 0];
				if(in_array($MainCat->category_id,$CatArray) || $CatArray[0] == 0)
				{
					$SubCats[]=['category_id' => $MainCat->category_id, 'category_name' => $MainCat->category_name, 'link' => config('global.SITE_URL').remove_special_chars($MainCat->category_name).'/cid/'.$MainCat->category_id];
					if(isset($MainCat->childs) && count($MainCat->childs) > 0 ){
						$SubCatsTree[$key][]=['category_id' => $MainCat->category_id, 'category_name' => $MainCat->category_name, 'link' => config('global.SITE_URL').remove_special_chars($MainCat->category_name).'/cid/'.$MainCat->category_id, 'Level' => 0];
						
						foreach($MainCat->childs as $SubLevel1){
							$SubCats[]=['category_id' => $SubLevel1->category_id, 'category_name' => $SubLevel1->category_name, 'link' => config('global.SITE_URL').remove_special_chars($SubLevel1->category_name).'/scid/'.$SubLevel1->category_id];
							$SubAllCats = isset($SubLevel1->childs)?$SubLevel1->childs:[];
							$SubCatsTree[$key][]=['category_id' => $SubLevel1->category_id, 'category_name' => $SubLevel1->category_name,'hasChild' => ($SubAllCats != null && count($SubAllCats) > 0) ? 'Yes':'No', 'link' => config('global.SITE_URL').remove_special_chars($SubLevel1->category_name).'/scid/'.$SubLevel1->category_id,'Level' => 1];
							
							if($SubAllCats){
								foreach($SubAllCats as $SubLevel2){
									$SubCats[]=['category_id' => $SubLevel2->category_id, 'category_name' => $SubLevel2->category_name, 'link' => config('global.SITE_URL').remove_special_chars($SubLevel2->category_name).'/scid/'.$SubLevel2->category_id];	
									$SubCatsTree[$key][]=['category_id' => $SubLevel2->category_id, 'category_name' => $SubLevel2->category_name,  'link' => config('global.SITE_URL').remove_special_chars($SubLevel2->category_name).'/scid/'.$SubLevel2->category_id, 'Level' => 2];
									$key++;
								}
							}
							$key++;		
						}
					} 				
				}
			}
			return ['CatList' => $SubCats, 'CatTree' => $SubCatsTree];
		}	
	}
	if (!function_exists('New_Cat_Tree')) {
		function New_Cat_Tree($Cats)
		{
			$childs = array();
			foreach($Cats as $item){
				$childs[$item->parent_id][] = $item;
				unset($item);
			}
			foreach($Cats as $item){
				if (isset($childs[$item->category_id])){
					$item['childs'] = $childs[$item->category_id];
				}
			}
			return $childs[0];
		}
	}
	if (!function_exists('get_category_url')) {
		function get_category_url($category)
		{
				$data = new ProductController();
				$getCategoryObj = $data->GetCatTree();
				$category_id=$category->category_id;

				$getCategoryProd = $getCategoryObj['CatForProd'][$category_id];
				
			return $getCategoryProd['category_url'];
		}
	}
	if (!function_exists('array_sort')) {
		function array_sort($array, $on, $order=SORT_ASC){

			$new_array = array();
			$sortable_array = array();
		
			if (count($array) > 0) {
				foreach ($array as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $k2 => $v2) {
							if ($k2 == $on) {
								$sortable_array[$k] = $v2;
							}
						}
					} else {
						$sortable_array[$k] = $v;
					}
				}
		
				switch ($order) {
					case SORT_ASC:
						asort($sortable_array);
						break;
					case SORT_DESC:
						arsort($sortable_array);
						break;
				}
		
				foreach ($sortable_array as $k => $v) {
					$new_array[$k] = $array[$k];
				}
			}
		
			return $new_array;
		}
	}
	if (!function_exists('get_deal_of_week_by_sku')) {

		function get_deal_of_week_by_sku(){
			if (Cache::has('dealofweekBySku_cache')) 
			{
				$DealOfWeeksArr = Cache::get('dealofweekBySku_cache');
			} else {
				$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
				$dealofWeekEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
				$dealofWeekdiffInMinutes = diffInMinutes($currentDatetime,$dealofWeekEndDatetime);
				$table_prefix = env('DB_PREFIX', '');
				$currentDate = getDateTimeByTimezone('Y-m-d');
				$DealQuery = DB::table($table_prefix.'dealofweek as dw')
				->select('dw.dealofweek_id','dw.description','dw.product_sku','dw.deal_price')
				->join($table_prefix.'products as p','dw.product_sku','=','p.sku')
				->where('dw.status','=','1')
				->where('dw.start_date','<=',$currentDate)->where('dw.end_date','>=',$currentDate)
				->where('dw.deal_type','=','Weekly')
				->where('p.status','=','1');
				$DealOfWeeks = $DealQuery->orderBy('dw.display_rank')->get();
				
				$DealOfWeeksArr = $DealOfWeeks->mapWithKeys(function ($DealOfWeek, $key) {
					return [$DealOfWeek->product_sku => $DealOfWeek];
				})->toarray();
				Cache::put('dealofweekBySku_cache', $DealOfWeeksArr,$dealofWeekdiffInMinutes);
			}	
			if(isset($DealOfWeeksArr) && !empty($DealOfWeeksArr)){
				return $DealOfWeeksArr;
			}else{
				return array();
			}
		}
	}
	if (!function_exists('show_other_page_categoryList')) {
		function show_other_page_categoryList(){
			$data = new ProductController();
			$CategoryObj = $data->MainCategoryList('HomePage');
			return $CategoryObj;
		}
	}
	if (!function_exists('show_other_page_PopularBrandList')) {
		function show_other_page_PopularBrandList(){
			$data = new CategoryController();
			$brandArr = $data->CategoryPopularBrandList();
			return $brandArr;
		}
	}
	if (!function_exists('GetCatTree')) {
		function GetCatTree(){
			$data = new CategoryController();
			$GetCatTree = $data->GetCatTree();
			return $GetCatTree;
		}
	}
	if (!function_exists('get_all_categories')) {
		function get_all_categories()
		{
			try{
				$data = new ProductController();
				$getAllCategoryObj = $data->get_all_categories();
				return $getAllCategoryObj;
			} catch (Throwable $e) {
				report($e);
				return  [];		
			}
		}
	}
	/*
 * Function will create/get organization schema.
 * */
if (!function_exists('getOrganizationSchema')) {
	function getOrganizationSchema($metaInfo)
	{	
		
		try {
			$logo = config('const.LOGO');
			$same = config('const.SAMEAS');
			$address_street_address = config('const.ADDRESSSTREETADRESS');
			$address_locality = config('const.ADDRESSLOCALITY');
			$address_region = config('const.ADDRESSREGION');
			$address_postcode = config('const.ADDRESSPOSTCODE');
			$address_country = config('const.ADDRESSCOUNTRY');
			$telephone = config('const.TELEPHONE');
			$site_url = config('const.SITE_URL'); 
			if (count($metaInfo) > 0) {
				$meta_title = $metaInfo[0]->meta_title;
				$meta_description = strip_tags($metaInfo[0]->meta_description);
				$meta_keywords = strip_tags($metaInfo[0]->meta_keywords);
			}else{
				$meta_title = '';
				$meta_description = '';
				$meta_keywords = '';
			}
			$schemaContent = '<script type="application/ld+json">';
			$schemaContent .= '{';
			$schemaContent .= '"@context" : "http://schema.org",';
			$schemaContent .= '"@type" : "Organization",';
			$schemaContent .= '"legalName" : "' . $meta_title . '",';
			$schemaContent .= '"logo" : "'.$logo.'",';
			$schemaContent .= '"name" : "'.$meta_title.'",';
			$schemaContent .= '"image" : "'.$logo.'",';
			$schemaContent .= '"description": "' . $meta_description . '",';
			$schemaContent .= '"telephone" : "' . $telephone . '",';
			$schemaContent .= '"sameAs" :"'.$same.'",';
			$schemaContent .= '"url" : "' .$site_url. '"';
			if(!empty($address_street_address)){
			$schemaContent .= ',"address" : {
				"@type": "PostalAddress",
				"streetAddress": "'.$address_street_address.'",
				"addressLocality": "'.$address_locality.'",
				"addressRegion": "'.$address_region.'",
				"postalCode": "'.$address_postcode.'",
				"addressCountry": "'.$address_country.'"
			}';
			}
			$schemaContent .= '}';
			//dd($schemaContent);
			$schemaContent .= '</script>';
			return $schemaContent;
		} catch (\Exception $exception) {
			dd($exception);
			return false;
		}
	}
}

/*
 * Function will create/get breadcrumblist schema for Product Listing Page.
 * */
if (!function_exists('getBLSchemaForProductListing')) {
	function getBLSchemaForProductListing($bredcrumObj)
	{
		try {
			$schemaContent = '<script type="application/ld+json">';
			$schemaContent .= '{';
			$schemaContent .= '"@context": "http://schema.org",';
			$schemaContent .= '"@type": "BreadcrumbList",';
			$schemaContent .= '"itemListElement":[';
     
			if (isset($bredcrumObj) && !empty($bredcrumObj)) {
				foreach($bredcrumObj as $bredcrumObjKey => $bredcrumObjValue){
					$position = $bredcrumObjKey+1;
					$breadcrumbURL = $bredcrumObjValue['link'];
					$breadcrumbName = $bredcrumObjValue['title'];
					if($bredcrumObjKey  == (count($bredcrumObj)-1)){
						$schemaContent .= '{ "@type": "ListItem", "position": ' . $position . ', "name": "' . ucwords($breadcrumbName) . '" }';
					}else{
						$schemaContent .= '{ "@type": "ListItem", "position": ' . $position . ', "item": "' . $breadcrumbURL . '", "name": "'.ucwords($breadcrumbName).'" } ,';
					}
				}
				
				
			}
			$schemaContent .= ']';
			$schemaContent .= '}';
			$schemaContent .= '</script>';

			return $schemaContent;
		} catch (\Exception $exception) {
			return false;
		}
	}
}

/*
 * Function will create/get breadcrumblist schema.
 * */
if (!function_exists('getBreadcrumbListSchema')) {
	function getBreadcrumbListSchema($categoryId, $productObj)
	{

		try {
			$categoryHierarchy = getCategoryHierarchy($categoryId);
			$productData = '';
			if (isset($productObj) && !empty($productObj)) {
				$productData = $productObj;
			}

			if ($categoryHierarchy != false) {
				$position = 1;
				$schemaContent = '<script type="application/ld+json">';
				$schemaContent .= '{';
				$schemaContent .= '"@context": "http://schema.org",';
				$schemaContent .= '"@type": "BreadcrumbList",';
				$schemaContent .= '"itemListElement":[';
				$schemaContent .= '{ "@type": "ListItem", "position": ' . $position . ', "item": "' . config("const.SITE_URL") . '", "name": "Home" } ,';
				$position++;

				foreach ($categoryHierarchy as $key => $catId) {
					
					//dd($catId);
					//$categoryData = Category::find($catId->category_id);

					if (next($categoryHierarchy)) {
						$schemaContent .= '{ "@type": "ListItem", "position": ' . $position . ', "item": "' . config("const.SITE_URL") . '/' . $catId->url_name . '/cid/' . $catId->category_id . '", "name": "' . ucwords($catId->category_name) . '" } ,';
					} else {
						$schemaContent .= '{ "@type": "ListItem", "position": ' . $position . ', "item": "' . config("const.SITE_URL") . '/' . $catId->url_name . '/cid/' . $catId->category_id . '", "name": "' . ucwords($catId->category_name) . '" } ';
					}

					$position++;
				}

				if (isset($productObj) && !empty($productObj)) {
					$schemaContent .= ',{ "@type": "ListItem", "position": ' . $position . ', "name": "' . ucwords($productData['product_name']) . '" }';
				}

				$schemaContent .= ']';
				$schemaContent .= '}';
				$schemaContent .= '</script>';

				return $schemaContent;
			} else {
				return false;
			}
		} catch (\Exception $exception) {
			return false;
		}
	}
}


/*
 * Function will create/get product schema.
 * */
if (!function_exists('getProductSchema')) {
	function getProductSchema($productObj)
	{
		try {
			$productData = $productObj;
			$brandName = '';

			if (!empty($productObj)) {
				$brandName = $productObj->brand;
			}
			$categoryName = '';
			if (!empty($productObj)) {
				$categoryName = $productObj->category_name;
				
			}
			
			$mainImage = trim($productData->image_name);

			$additionalImages = array();
			if (isset($mainImage) && !empty($mainImage)) {
				$additionalImages = Get_Product_Image_URL($mainImage, 'LARGE');
			}

			$mainImageLarge = $additionalImages;

		$getCanonicalURLRes = url()->current();
			if ($getCanonicalURLRes != false) {
				$canonicalURL = $getCanonicalURLRes;
			}
			$logo = config('const.LOGO');

			$schemaContent = '<script type="application/ld+json">';
			$schemaContent .= '{';
			$schemaContent .= '"@type": "Product",';
			$schemaContent .= '"@context": "http://schema.org",';
			$schemaContent .= '"url": "' . $canonicalURL . '",';
			$schemaContent .= '"name": "' . ucwords($productData->product_name) . '",';
			$schemaContent .= '"image": "' . $mainImageLarge . '",';
			$schemaContent .= '"description": "' . $productData->description . '",';
			$schemaContent .= '"brand": "' . $brandName . '",';
			$schemaContent .= '"sku": "' . $productData->sku . '",';
			$schemaContent .= '"mpn": "' . $productData->sku . '",';
			$schemaContent .= '"category": "' . ucwords($categoryName) . '",';
			$schemaContent .= '"logo": "'.$logo.'"';
			$schemaContent .= '}';
			$schemaContent .= '</script>';

			return $schemaContent;
		} catch (\Exception $exception) {
			return false;
		}
	}
}
if (!function_exists('is_html')) {
	function is_html($string)
	{
	return preg_match("/<[^<]+>/",$string,$m) != 0;
	}
}



	
if (!function_exists('getCurrencyArray')) {
	function getCurrencyArray()
	{
		if (Cache::has('getAllCurrencyArr_cache')){
			$CurrencyRS = Cache::get('getAllCurrencyArr_cache');
		} else {
			$CurrencyRS = Currency::select('currencies_id', 'title', 'code', 'symbol_left', 'symbol_right', 'decimal_point', 'thousands_point', 'decimal_places', 'value', 'last_updated', 'country_id', 'status')->where('status', '=', '1')->orderBy('title', 'ASC')->get();	
			// $table_prefix = env('DB_PREFIX', '');
			// $CurrencyRS = DB::table($table_prefix.'currencies')
			// ->select('currencies_id', 'title', 'code', 'symbol_left', 'symbol_right', 'decimal_point', 'thousands_point', 'decimal_places', 'value', 'last_updated', 'country_id', 'status')
			// ->where('status', '=', '1')
			// ->orderBy('title', 'ASC')
			// ->get();
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$CurrencyEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$currencydiffInMinutes = diffInMinutes($currentDatetime,$CurrencyEndDatetime);
			Cache::put('getAllCurrencyArr_cache', $CurrencyRS,$currencydiffInMinutes);	
		}
		$CurrencyArr = $CurrencyRS->mapWithKeys(function ($Currency, $key) {
		return [title($Currency['title']) => $Currency];
		})->toarray();
		return $CurrencyArr;
	}
}
if (!function_exists('getSelectedCurrenctyCode')) {
	function getSelectedCurrenctyCode(){
		$data = new CustomerController();
		$CurrenctyCode = $data->getSelectedCurrenctyCode();
		 return $CurrenctyCode;
	}
}

if (!function_exists('getPriceCategoryWise')) {
	function getPriceCategoryWise($categoryname=''){
		if($categoryname=='skin-care'){
			$priceArrayFilter = array(
				'1_25' => 'Under $25',
				'25_50' => '$25 - $50',
				'50_100' => '$50 - $100',
				'100' => 'Over $100'
			);

		}else if($categoryname=='hair-care'){
			$priceArrayFilter = array(
				'1_15' => 'Under $15',
				'15_30' => '$15 - $30',
				'30_50' => '$30 - $50',
				'50' => 'Over $50'
			);

		}else if($categoryname=='eye-care'){
			$priceArrayFilter = array(
				'1_75' => 'Under $75',
				'75_125' => '$75 - $125',
				'125_175' => '$125 - $175',
				'175_250' => '$175 - $250',
				'250_400' => '$250 - $400',
				'400' => 'Over $400'
			);

		}else{
			$priceArrayFilter = array(
				'1_75' => 'Under $75',
				'75_125' => '$75 - $125',
				'125_175' => '$125 - $175',
				'175_250' => '$175 - $250',
				'250_400' => '$250 - $400',
				'400' => 'Over $400'
			);
		}
		return $priceArrayFilter;
	}
}

function replaceMultipleDashes($str) {
    return preg_replace('/-+/', '-', $str);
}

if (!function_exists('getOtherCategories')) {
	function getOtherCategories($limit=''){
		if(empty($limit) ){
			$limit = 99999;
		}
		if (!Cache::has('otherCategories_menu_cache')) {
			$CatDetails = GetCatTree();	
			$records = Category::where('parent_id', '=', '0')->where('url_name', '=', 'other-categories')->orderBy('category_name', 'asc')->with(['childrenRecursive' => function ($query) {
										$query->orderBy('category_name', 'asc');
									}])->get()->toArray();
			$othercategoryArr = [];
			foreach($records as $recordsKey => $recordsValue){
				
				foreach($recordsValue['children_recursive'] as $childrenRecursiveKey => $childrenRecursiveValue){
					$childrenRecursiveValue['category_full_url'] ='';
					$child_category_id = $childrenRecursiveValue['category_id'];
					if($childrenRecursiveValue['status']=='1'){
					if (isset($CatDetails['CatForProd'][$child_category_id]) && !empty($CatDetails['CatForProd'][$child_category_id])) {
						$category_url = $CatDetails['CatForProd'][$child_category_id]['category_url'];
						$urlParts = explode('/', $category_url);
						$urlParts = array_map('replaceMultipleDashes', $urlParts);
						$newUrl = implode('/', $urlParts);
						
						$childrenRecursiveValue['category_full_url'] = $newUrl;
					}	
					
					$othercategoryArr[]=$childrenRecursiveValue;
					}
				}
				
				
			}
			$getAllCategory = $othercategoryArr;
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$otherCatEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$ohterCatiffInMinutes = diffInMinutes($currentDatetime,$otherCatEndDatetime);
			$getAllCategory = json_decode(json_encode($getAllCategory), true);
				Cache::put('otherCategories_menu_cache', $getAllCategory,$ohterCatiffInMinutes);
			} else {
				
				$getAllCategory = Cache::get('otherCategories_menu_cache');
			}

	
			if(count($getAllCategory) > $limit){
				$getAllCategory = array_slice($getAllCategory);
			}
			//dd($getAllCategory);
		
			return $getAllCategory;
		}
	}


if (!function_exists('getOtherCategoriesNew')) {
	function getOtherCategoriesNew()
	{
		$Ocategories = Category::select('hba_category.category_id', 'hba_category.category_name', 'hba_category.url_name')
    ->join('hba_category as p', 'hba_category.parent_id', '=', 'p.category_id')
    ->where('p.category_name', '=', 'other categories')
	->orderBy('hba_category.category_name', 'ASC')
    ->get();
		
	$getAllCategory = json_decode(json_encode($Ocategories), true);
	//dd($getAllCategory);
		/*$data = new CategoryController();
				$getAllCategory = $data->get_all_categories();
				$CatDetails = GetCatTree();	
				
			$getAllCategory = $getAllCategory
			->unique('category_id','category_name')
			->filter(function ($categoryObj, $key) use ($CatDetails) {
				$category_id = $categoryObj->category_id;
				if (isset($CatDetails['CatForProd'][$category_id]) && !empty($CatDetails['CatForProd'][$category_id]) && $categoryObj->display_on_other_category=='Yes') {
					$category_url = $CatDetails['CatForProd'][$category_id]['category_url'];
					$urlParts = explode('/', $category_url);
					$urlParts = array_map('replaceMultipleDashes', $urlParts);
					$newUrl = implode('/', $urlParts);
					$categoryObj->category_full_url = $newUrl;
					return $categoryObj;
				} else {
					return false;
				}
			})
			->sortBy('category_name')->slice(0,$limit)
			->values()
			->toArray();
		
		$getAllCategory = json_decode(json_encode($getAllCategory), true);*/
		
		
		return $getAllCategory;

	}
}