<?php
include_once("config_setting.php");

require_once($physical_path . "classes/GlobalClassConstruct.cls.php");
include_once($physical_path . "classes/general.cls.php");
require_once($physical_path . "config/limitedexportproductnew.php");
if (!isset($generalobj)) {
    $generalobj = new General($obj, $smarty);
}
set_time_limit(0);
ini_set("display_errors", 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING);
ini_set("memory_limit", -1);
$gen_csv_fields_arr = $export_data;
$status = '';
if(isset($_GET['status'])  && $_GET['status'] == 1){
	$status = '"'.'1'.'"';
}if(isset($_GET['status']) && $_GET['status'] == 0){
	$status = '"'.'0'.'"';
}
function general_info_arr($general_information)
{
	$info_arr = array();
	if ($general_information != '') {
		$arr = explode("#", $general_information);
		for ($i = 0; $i < count($arr); $i++) {
			$arr2 = explode(":", $arr[$i]);
			if (isset($arr2[0]) && isset($arr2[1])) {
				$info_arr = array_merge($info_arr, array($arr2[0] => $arr2[1]));
			}
		}
	}
	return $info_arr;
}
function filter_desc($desc){
	$desc = str_replace('"','""',$desc);
	//$desc = preg_replace('/\s\s+/', ' ',$desc);
	$desc = preg_replace('/[,]/', '', $desc);
	//$desc = str_replace('"','', $desc);
	//$desc = str_replace("'", '', $desc);
	return $desc;
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
//print_r(array_values($gen_csv_fields_arr));
$gen_csv_fields_arr = array_values($gen_csv_fields_arr);
$gen_csv_fields_arr = array_sort($gen_csv_fields_arr, 'display_rank', SORT_ASC);
$i = 0;
$Csv_data_str_23 = '';
 $numItems = count($gen_csv_fields_arr);
foreach($gen_csv_fields_arr as $gen_csv_fields_arr_key => $gen_csv_fields_arr_value){
	if(++$i != $numItems) {
		$Csv_data_str_23.= $gen_csv_fields_arr_value['export_header_val'].',';
	}else{
		$Csv_data_str_23.= $gen_csv_fields_arr_value['export_header_val'];
	}
}
//$Csv_data_str_23 = "SKU,Product Group Code,UPC,Product Name,Product Description,Category,General Informatioin,Gender,Manufacturer,Brand,Size,Product Type,Related SKU,Color,Skin Type,Retail Price,Our Price,Sale Price,On Sale,Wholesale Price,Wholesale Markup Percent,Our Cost,Video URL,Current Stock,Meta Title,Meta Keyword,Meta Description,Clearance,Best Seller,New Arrival,Featured,Display Rank,Is Atomizer,Status,Product Availability,Ingredients,Ingredients PDF,Uses,Key Features,Metric_Size,Product_Weight,Product_Length,Product_Width,Product_Height,Shipping_Weight,Shipping_Length,Shipping_Width,Shipping_Height,Country_of_origin,Is_Hazmat,Is_Multipack,Is_Set,Parent_Sku,Variant,Age_Group,Multi Pack Sku,Temp,NIOXIN_System,NIOXIN_Size,NIOXIN_Type,Ship International,Free Text_1,Free Text_2\n";
$Csv_data_str_23 .= "\n";
if(!empty($status)){
	$sqlquery = "SELECT * FROM " . TABLE_PREFIX . "products where status=".$status;
}else{
	$sqlquery = "SELECT * FROM " . TABLE_PREFIX . "products";
}
$result = $obj->select($sqlquery);
$totalprodcount = count($result);
//echo "<pre>"; print_r($result); exit;
if($totalprodcount <=0){
	setcookie('fileLoading', 'false', 0, '/');
	$msg = urlencode("Product CSV can't downloaded due to there is no prdocuts found");
	header("Location: $Site_URL"."pnkpanel/product/updateexportproduct?msg="."$msg");
	
	exit;
}
for ($i = 0; $i < $totalprodcount; $i++) 
 {
	$j = 0;

	//$Csv_data_str_23.= '\'';
	foreach($gen_csv_fields_arr as $gen_csv_fields_arr_key => $gen_csv_fields_arr_value){
		if(isset($result[$i][$gen_csv_fields_arr_value['export_field']])){
			
			if($gen_csv_fields_arr_value['export_field'] == 'sku'){
				$result[$i]['sku']  = 'H-'.$result[$i]["sku"];
			
			}
			if($gen_csv_fields_arr_value['export_field'] == 'product_group_code'){
				$result[$i]['product_group_code']  = 'H-'.$result[$i]["product_group_code"];
			}
			if($gen_csv_fields_arr_value['export_field'] == 'product_weight'){
				$result[$i]['product_weight']  = '"'.filter_desc($result[$i]["product_weight"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'product_length'){
				$result[$i]['product_length']  = '"'.filter_desc($result[$i]["product_length"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'product_width'){
				$result[$i]['product_width']  = '"'.filter_desc($result[$i]["product_width"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'product_height'){
				$result[$i]['product_height']  = '"'.filter_desc($result[$i]["product_height"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'shipping_weight'){
				$result[$i]['shipping_weight']  = '"'.filter_desc($result[$i]["shipping_weight"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'shipping_length'){
				$result[$i]['shipping_length']  = '"'.filter_desc($result[$i]["shipping_length"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'shipping_width'){
				$result[$i]['shipping_width']  = '"'.filter_desc($result[$i]["shipping_width"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'country_of_origin'){
				$result[$i]['country_of_origin']  = '"'.filter_desc($result[$i]["country_of_origin"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'multi_pack_sku'){
				$result[$i]['multi_pack_sku']  = '"'.filter_desc($result[$i]["multi_pack_sku"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'temp'){
				$result[$i]['temp']  = '"'.filter_desc($result[$i]["temp"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'nioxin_system'){
				$result[$i]['nioxin_system']  = '"'.filter_desc($result[$i]["nioxin_system"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'nioxin_size'){
				$result[$i]['nioxin_size']  = '"'.filter_desc($result[$i]["nioxin_size"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'nioxin_type'){
				$result[$i]['nioxin_type']  = '"'.filter_desc($result[$i]["nioxin_type"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'free_text_2'){
				$result[$i]['free_text_2']  = '"'.filter_desc($result[$i]["free_text_2"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'free_text_1'){
				$result[$i]['free_text_1']  = '"'.filter_desc($result[$i]["free_text_1"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'shipping_height'){
				$result[$i]['shipping_height']  = '"'.filter_desc($result[$i]["shipping_height"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'metric_size'){
				$result[$i]['metric_size']  = '"'.filter_desc($result[$i]["metric_size"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'general_information'){
				$result[$i]['general_information']  = '"'.filter_desc($result[$i]["general_information"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'variant'){
				$result[$i]['variant']  = '"'.filter_desc($result[$i]["variant"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'is_set'){
				$result[$i]['is_set']  = '"'.filter_desc($result[$i]["is_set"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'category'){
				$result[$i]['category']  = '"'.filter_desc($result[$i]["category"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'size'){
				$result[$i]['size']  = '"'.filter_desc($result[$i]["size"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'pack_size'){
				$result[$i]['pack_size']  = '"'.filter_desc($result[$i]["pack_size"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'flavour'){
				$result[$i]['flavour']  = '"'.filter_desc($result[$i]["flavour"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'ingredients'){
				$result[$i]['ingredients']  = '"'.filter_desc($result[$i]["ingredients"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'product_name'){
				
				$result[$i]['product_name']  = '"'.filter_desc($result[$i]["product_name"]).'"';
				
			}
			/*if($gen_csv_fields_arr_value['export_field'] == 'product_description'){
				$result[$i]['product_description']  = '"'.filter_desc($result[$i]["product_description"]).'"';
			}*/
			if($gen_csv_fields_arr_value['export_field'] == 'product_description'){
				$product_description = $result[$i]["product_description"];
				$result[$i]['product_description'] = '"' . str_replace('"', '""', $product_description) . '"';

				/*$product_description = $result[$i]["product_description"];
				$result[$i]['product_description'] = (strpos($product_description, ',') !== false) ? '"' . $product_description . '"' : $product_description;*/

				/*$result[$i]['product_description'] = filter_desc($product_description);*/
			}

			if($gen_csv_fields_arr_value['export_field'] == 'short_description'){
				$short_description = $result[$i]["short_description"];
				$result[$i]['short_description'] = '"' . str_replace('"', '""', $short_description) . '"';	
				
				//$result[$i]['short_description']  = '"'.filter_desc($result[$i]["short_description"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'meta_title'){
				$meta_title = $result[$i]["meta_title"];
				$result[$i]['meta_title'] = '"' . str_replace('"', '""', $meta_title) . '"';

				//$result[$i]['meta_title']  = '"'.filter_desc($result[$i]["meta_title"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'meta_keyword'){
				$meta_keyword = $result[$i]["meta_keyword"];
				$result[$i]['meta_keyword'] = '"' . str_replace('"', '""', $meta_keyword) . '"';

				//$result[$i]['meta_keyword']  = '"'.filter_desc($result[$i]["meta_keyword"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'meta_description'){
				$meta_description = $result[$i]["meta_description"];
				$result[$i]['meta_description'] = '"' . str_replace('"', '""', $meta_description) . '"';

				//$result[$i]['meta_description']  = '"'.filter_desc($result[$i]["meta_description"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'upc'){
				$upc = $result[$i]["upc"];
				$result[$i]['upc'] = '"' . str_replace('"', '""', $upc) . '"';

				//$result[$i]['upc']  = '"'.filter_desc($result[$i]["upc"]).'"';
			}
			if($gen_csv_fields_arr_value['brand'] == 'brand'){
				$brand = $result[$i]["brand"];
				$result[$i]['brand'] = '"' . str_replace('"', '""', $brand) . '"';

				//$result[$i]['brand']  = '"'.filter_desc($result[$i]["brand"]).'"';
			}
			if($gen_csv_fields_arr_value['brand'] == 'manufacturer'){
				$manufacturer = $result[$i]["manufacturer"];
				$result[$i]['manufacturer'] = '"' . str_replace('"', '""', $manufacturer) . '"';
				//$result[$i]['manufacturer']  = '"'.filter_desc($result[$i]["manufacturer"]).'"';
			}
			
			if($gen_csv_fields_arr_value['export_field'] == 'uses'){
				$uses = $result[$i]["uses"];
				$result[$i]['uses'] = '"' . str_replace('"', '""', $uses) . '"';
				//$result[$i]['uses']  = '"'.filter_desc($result[$i]["uses"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'key_features'){
				$key_features = $result[$i]["key_features"];
				$result[$i]['key_features'] = '"' . str_replace('"', '""', $key_features) . '"';

				//$result[$i]['key_features']  = '"'.filter_desc($result[$i]["key_features"]).'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'product_group_code'){
				$product_group_code = $result[$i]["product_group_code"];
				$result[$i]['product_group_code'] = '"' . str_replace('"', '""', $product_group_code) . '"';

				//$result[$i]['product_group_code']  = '"'.$result[$i]["product_group_code"].'"';
			}
			if($gen_csv_fields_arr_value['export_field'] == 'product_type'){
				$product_type = $result[$i]["product_type"];
				$result[$i]['product_type'] = '"' . str_replace('"', '""', $product_type) . '"';

				/*$product_type = $result[$i]["product_type"];
				$result[$i]['product_type'] = (strpos($product_type, ',') !== false) ? '"' . $product_type . '"' : $product_type;*/
				//$result[$i]['product_type'] = filter_desc($product_type);
			}
			if(++$j != $numItems) {
				$Csv_data_str_23 .= $result[$i][$gen_csv_fields_arr_value['export_field']].',';
			}else{
				$Csv_data_str_23 .= $result[$i][$gen_csv_fields_arr_value['export_field']];
			}
			
		}
	}
	//$Csv_data_str_23 .= '\'';
	$Csv_data_str_23 .= "\n";
	//  echo $Csv_data_str_23;
	//   exit;
 }	

// for ($i = 0; $i < $totalprodcount; $i++) 
// {
// 	$products_id		= $result[$i]["product_id"];
// 	$product_sku		= "H-".$result[$i]["sku"];
// 	$product_group_code	= $result[$i]["product_group_code"];
// 	$product_group_code	= '';
// 	$parent_sku			= $result[$i]["parent_sku"];
// 	$related_sku		= $result[$i]["related_sku"];
// 	$product_type		= $result[$i]["product_type"];
// 	$category			= $result[$i]["category"] ? $result[$i]["category"]:'';
// 	$general_information= $result[$i]["general_information"];
// 	$product_name		= $result[$i]["product_name"];
// 	$product_url		= $result[$i]["product_url"];
// 	$short_description		= $result[$i]["short_description"];
// 	$brand_id			= $result[$i]["brand_id"];
// 	$manufacturer		= $result[$i]["manufacturer"];
	
// 	$product_description1	= $result[$i]["product_description"]?$result[$i]["product_description"]:'';
// 	//$product_description	= preg_replace("/[^a-zA-Z0-9\s!?.,\'\"]/", "", $product_description1);
// 	$product_description	= preg_replace('/\s\s+/', ' ',$product_description1);
// 	$product_description	= preg_replace('/[,]/', '', $product_description);
// 	$product_description	= str_replace('"','', $product_description);
// 	$product_description	= str_replace("'", '', $product_description);
	
// 	$size_dimension		= $result[$i]["size_dimension"] ? $result[$i]["size_dimension"] : '';
// 	$retail_price   	= $result[$i]["retail_price"] ? $result[$i]["retail_price"] : 0;
// 	$our_price   		= $result[$i]["our_price"] ? $result[$i]["our_price"] : 0;
// 	$sale_price   		= $result[$i]["sale_price"] ? $result[$i]["sale_price"] : 0;
// 	$on_sale   			= $result[$i]["on_sale"] ? $result[$i]["on_sale"] : 'No';
// 	$wholesale_price   	= $result[$i]["wholesale_price"] ? $result[$i]["wholesale_price"] : 0;
	
// 	$wholesale_markup_percent   = $result[$i]["wholesale_markup_percent"] ? $result[$i]["wholesale_markup_percent"] : 0;
// 	$our_cost   		= $result[$i]["our_cost"] ? $result[$i]["our_cost"] : 0;
	
// 	$shipping_text		= $result[$i]["shipping_text"];
// 	$shipping_days		= $result[$i]["shipping_days"];
// 	$video_url			= $result[$i]["video_url"];
// 	$badge				= $result[$i]["badge"];
// 	$display_rank		= $result[$i]["display_rank"];
// 	$is_multipack		= $result[$i]["is_multipack"];
// 	$is_set				= $result[$i]["is_set"];
// 	$variant			= $result[$i]["variant"];
// 	$meta_title			= $result[$i]["meta_title"];
// 	$meta_keyword		= $result[$i]["meta_keyword"];
// 	$meta_description	= $result[$i]["meta_description"];
// 	$upc				= $result[$i]["upc"];
// 	$quantity			= $result[$i]["quantity"];
// 	$sold_qunantity		= $result[$i]["sold_qunantity"];
// 	$status				= $result[$i]["status"];
// 	$is_imagexist		= $result[$i]["is_imagexist"];
// 	$is_topsellers		= $result[$i]["is_topsellers"];
// 	$is_newarrival		= $result[$i]["is_newarrival"];
// 	$clearance			= $result[$i]["clearance"];
// 	$best_seller		= $result[$i]["best_seller"];
// 	$new_arrival		= $result[$i]["new_arrival"];
// 	$featured			= $result[$i]["featured"];
// 	$product_availability		= $result[$i]["product_availability"];
// 	$is_atomizer		= $result[$i]["is_atomizer"];
// 	$gender				= $result[$i]["gender"];
// 	$ingredients		= preg_replace('/\s\s+/', ' ',$result[$i]["ingredients"]);
// 	$ingredients		= preg_replace('/[,]/', '', $ingredients);
// 	$uses				= preg_replace('/\s\s+/', ' ',$result[$i]["uses"]);
// 	$uses				= preg_replace('/[,]/', '', $uses);
// 	$key_features		= preg_replace('/\s\s+/', ' ', $result[$i]["key_features"]);
// 	$key_features		= preg_replace('/[,]/', '', $key_features);
// 	$metric_size		= $result[$i]["metric_size"];
// 	$product_weight		= $result[$i]["product_weight"];
// 	$product_length		= $result[$i]["product_length"];
// 	$product_width		= $result[$i]["product_width"];
// 	$product_height		= $result[$i]["product_height"];
// 	$shipping_weight		= $result[$i]["shipping_weight"];
// 	$shipping_length		= $result[$i]["shipping_length"];
// 	$shipping_width		= $result[$i]["shipping_width"];
// 	$shipping_height		= $result[$i]["shipping_height"];
// 	$country_of_origin		= $result[$i]["country_of_origin"];
// 	$is_hazmat		= $result[$i]["is_hazmat"];
// 	$current_stock		= $result[$i]["current_stock"];
// 	$age_group		= $result[$i]["age_group"];
// 	$skin_type		= $result[$i]["skin_type"];
	
// 	//$product_url     = $result[$i]["product_url"];
	
// 	$multi_pack_sku		= $result[$i]["multi_pack_sku"];
// 	$temp		= $result[$i]["temp"];
// 	$nioxin_system		= $result[$i]["nioxin_system"];
// 	$nioxin_size		= $result[$i]["nioxin_size"];
// 	$nioxin_type		= $result[$i]["nioxin_type"];
// 	$ship_international		= $result[$i]["ship_international"];
// 	$free_text_1		= $result[$i]["free_text_1"];
// 	$free_text_2		= $result[$i]["free_text_2"];
// 	$color              = $result[$i]["color"];
// 	$ingredients_pdf		= $result[$i]["ingredients_pdf"];
// 	//	Is_Multipack	Is_Set	Parent_Sku	Variant	Age_Group
	

// 	$Csv_data_str_23 .= '"'.$product_sku.'","'.$product_group_code.'","'.$upc.'","'.$product_name.'","'.$product_description.'","'.$category.'","'.$general_information.'","'.$gender.'","'.$manufacturer.'","'.$brand.'","'.$size.'","'.$product_type.'","'.$related_sku.'","'.$color.'","'.$skin_type.'","'.$retail_price.'","'.$our_price.'","'.$sale_price.'","'.$on_sale.'","'.$wholesale_price.'","'.$wholesale_markup_percent.'","'.$our_cost.'","'.$video_url.'","'.$current_stock.'","'.$meta_title.'","'.$meta_keyword.'","'.$meta_description.'","'.$clearance.'","'.$best_seller.'","'.$new_arrival.'","'.$featured.'","'.$display_rank.'","'.$is_atomizer.'","'.$status.'","'.$product_availability.'","'.$ingredients.'","'.$ingredients_pdf.'","'.$uses.'","'.$key_features.'","'.$metric_size.'","'.$product_weight.'","'.$product_length.'","'.$product_width.'","'.$product_height.'","'.$shipping_weight.'","'.$shipping_length.'","'.$shipping_width.'","'.$shipping_height.'","'.$country_of_origin.'","'.$is_hazmat.'","'.$is_multipack.'","'.$is_set.'","'.$parent_sku.'","'.$variant.'","'.$age_group.'","'.$multi_pack_sku.'","'.$temp.'","'.$nioxin_system.'","'.$nioxin_size.'","'.$nioxin_type.'","'.$ship_international.'","'.$free_text_1.'","'.$free_text_2.'"';
// 	$Csv_data_str_23 .= "\n";
// }

//echo "<pre>"; print_r($Csv_data_str_23); exit;
//die;
$filename = "limited_export_products_new" . time() . ".csv";
header('Content-type: application/csv');
header('Set-Cookie: fileLoading=true'); 
setcookie('fileLoading', 'true', 0, '/');
header('Content-Disposition: attachment; filename=' . $filename);
echo $Csv_data_str_23;
exit;
