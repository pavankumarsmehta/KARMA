<?php
include_once("config_setting.php");

require_once($physical_path . "classes/GlobalClassConstruct.cls.php");
include_once($physical_path . "classes/general.cls.php");
require_once($physical_path . "config/exportproductnew.php");
if (!isset($generalobj)) {
    $generalobj = new General($obj, $smarty);
}
set_time_limit(0);
ini_set("display_errors", 0);
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


if(!empty($status)){
	$sqlquery = "SELECT * FROM " . TABLE_PREFIX . "products where status=".$status;
}else{
	//$sqlquery = "SELECT * FROM " . TABLE_PREFIX . "products limit 0,500";
	$sqlquery = "SELECT * FROM " . TABLE_PREFIX . "products where product_url != '' && our_price > 0 AND status='1'";
}
//echo $sqlquery; exit;
$result = $obj->select($sqlquery);
//echo "<pre>"; print_r($result); exit;
$totalprodcount = count($result);

//echo $totalprodcount; exit;
//var_dump($fp); exit;
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
$header_row = '';

$header_row = "id,title,link,image_url,price,sale_price,best_price,category,product_id,group_id,group_leader,description,availability,Condition,gtin,brand,retail_price,price_range,slug,color,size,pack_size,flavour,current_stock,product_weight,product_length,general_informatioin,gender,manufacturer,product_type,skin_type,clearance,best_seller,new_arrival,featured,display_rank,status,product_width,product_height,shipping_weight,shipping_length,shipping_width,shipping_height,country_of_origin,is_hazmat,is_multipack,is_set,parent_sku,variant,age_group,multi_pack_sku,temp,nioxin_system,nioxin_size,nioxin_type,ship_international,free_text_1,free_text_2\n";

$numItems = count($gen_csv_fields_arr);
foreach($gen_csv_fields_arr as $gen_csv_fields_arr_key => $gen_csv_fields_arr_value){
	if(++$i != $numItems) {
		//$Csv_data_str_23.= $gen_csv_fields_arr_value['export_header_val'].',';
	}else{
		//$Csv_data_str_23.= $gen_csv_fields_arr_value['export_header_val'];
	}
}

$export_file_name = "Doofinder_Export.csv";
//echo $export_file_name; exit;
if(file_exists($export_file_name))
{
	@unlink($export_file_name); 
}
//$stream = fopen($filename, 'w'); // Open file for writing
$stream = fopen($export_file_name, "a+");



//$header_row .= "\n";
fwrite($stream,$header_row);


//echo $totalprodcount; exit;
if($totalprodcount <=0){
	setcookie('fileLoading', 'false', 0, '/');
	$msg = urlencode("Product CSV can't downloaded due to there is no prdocuts found");
	header("Location: $Site_URL"."pnkpanel/product/export?msg="."$msg");
	
	exit;
}

for ($i = 0; $i < $totalprodcount; $i++) 
{
	$products_id		= $result[$i]["product_id"];
	$product_sku		= "H-".$result[$i]["sku"];
	
	$product_group_code	= '';
	if(isset($product_group_code)&& $product_group_code != "")
	{
		$product_group_code	= $result[$i]["product_group_code"];
	}
	else
	{
		$product_group_code	= $product_sku;
	}
	
	//echo "<pre>"; print_r($_SERVER); exit;
	$parent_sku			= $result[$i]["parent_sku"];
	$product_id			= $result[$i]["product_id"];
	$related_sku		= $result[$i]["related_sku"];
	$product_type		= $result[$i]["product_type"];
	$category			= $result[$i]["category"] ? $result[$i]["category"]:'';
	$category 			= str_replace(":"," > ", $category);
	$category 			= str_replace("#"," , ", $category);
	$general_information= $result[$i]["general_information"];
	$product_name		= filter_desc(str_replace('"','', $result[$i]["product_name"]));
	$product_name		= str_replace("'",'', $product_name);
	$product_url		= $result[$i]["product_url"];
	
	$image_name = trim($result[$i]["image_name"]);
	if (isset($image_name) && $image_name != "") {
    	$imagePath = "/home/hbasales/public_html/hbastore/public/images/productimages/medium/" . $image_name;
    	if (file_exists($imagePath)) {
        	$main_image = "https://www.hbastore.com/images/productimages/medium/" . $image_name;
    	} else {
        	$main_image = "https://www.hbastore.com/images/medium_listing.jpg";
    	}
	} else {
    	$main_image = "https://www.hbastore.com/images/medium_listing.jpg";
	}
//echo $main_image; exit;
	$short_description		= $result[$i]["short_description"];
	$brand				= $result[$i]["brand"];
	$manufacturer		= $result[$i]["manufacturer"];
	
	$product_description1	= $result[$i]["product_description"]?$result[$i]["product_description"]:'';
	//$product_description	= preg_replace("/[^a-zA-Z0-9\s!?.,\'\"]/", "", $product_description1);
	$product_description	= preg_replace('/\s\s+/', ' ',$product_description1);
	$product_description	= preg_replace('/[,]/', '', $product_description);
	$product_description	= str_replace('"','', $product_description);
	$product_description	= str_replace("'", '', $product_description);
	$product_description	= filter_desc($product_description);
	$product_description	= strip_tags($product_description);
	
	$size		= $result[$i]["size"] ? $result[$i]["size"] : '';
	$pack_size		= $result[$i]["pack_size"] ? $result[$i]["pack_size"] : '';
	$flavour		= $result[$i]["flavour"] ? $result[$i]["flavour"] : '';
	$retail_price   	= $result[$i]["retail_price"] ? $result[$i]["retail_price"] : 0;
	$our_price   		= $result[$i]["our_price"] ? $result[$i]["our_price"] : 0;
	$sale_price   		= $result[$i]["sale_price"] ? $result[$i]["sale_price"] : 0;
	$on_sale   			= $result[$i]["on_sale"] ? $result[$i]["on_sale"] : 'No';
	$wholesale_price   	= $result[$i]["wholesale_price"] ? $result[$i]["wholesale_price"] : 0;

	$best_price = 0;
	if($sale_price > 0 && $on_sale == 'Yes')
	{
		$best_price = $sale_price;
	}
	else
	{
		$best_price = $our_price;
	}
	

	//Code for price range :: Start
	$price_range ="";
	if($best_price<25)
	{
		$price_range = "Under $25";
	}
	else if($best_price>=25 && $best_price<50)
	{
		$price_range = "$25 - $50";
	}
	else if($best_price>=50 && $best_price<100)
	{
		$price_range = "$50 - $100";
	}
	else if($best_price>=100)
	{
		$price_range = "Over $100";
	}		
	//Code for price range :: End	

	
	$wholesale_markup_percent   = $result[$i]["wholesale_markup_percent"] ? $result[$i]["wholesale_markup_percent"] : 0;
	$our_cost   		= $result[$i]["our_cost"] ? $result[$i]["our_cost"] : 0;
	
	$shipping_text		= $result[$i]["shipping_text"];
	$shipping_days		= $result[$i]["shipping_days"];
	$video_url			= $result[$i]["video_url"];
	$badge				= $result[$i]["badge"];
	$display_rank		= $result[$i]["display_rank"];
	$is_multipack		= $result[$i]["is_multipack"];
	$is_set				= $result[$i]["is_set"];
	$variant			= $result[$i]["variant"];
	$meta_title			= $result[$i]["meta_title"];
	$meta_keyword		= $result[$i]["meta_keyword"];
	$meta_description	= $result[$i]["meta_description"];
	$upc				= $result[$i]["upc"];
	$quantity			= $result[$i]["quantity"];
	$sold_qunantity		= $result[$i]["sold_qunantity"];
	$status				= $result[$i]["status"];
	$is_imagexist		= $result[$i]["is_imagexist"];
	$is_topsellers		= $result[$i]["is_topsellers"];
	$is_newarrival		= $result[$i]["is_newarrival"];
	$clearance			= $result[$i]["clearance"];
	$best_seller		= $result[$i]["best_seller"];
	$new_arrival		= $result[$i]["new_arrival"];
	$featured			= $result[$i]["featured"];
	$product_availability	= $result[$i]["product_availability"];
	$is_atomizer		= $result[$i]["is_atomizer"];
	$gender				= $result[$i]["gender"];
	$ingredients		= preg_replace('/\s\s+/', ' ',$result[$i]["ingredients"]);
	$ingredients		= preg_replace('/[,]/', '', $ingredients);
	$uses				= preg_replace('/\s\s+/', ' ',$result[$i]["uses"]);
	$uses				= preg_replace('/[,]/', '', $uses);
	$key_features		= preg_replace('/\s\s+/', ' ', $result[$i]["key_features"]);
	$key_features		= preg_replace('/[,]/', '', $key_features);
	$metric_size		= $result[$i]["metric_size"];
	$product_weight		= $result[$i]["product_weight"];
	$product_length		= $result[$i]["product_length"];
	$product_width		= $result[$i]["product_width"];
	$product_height		= $result[$i]["product_height"];
	$shipping_weight		= $result[$i]["shipping_weight"];
	$shipping_length		= $result[$i]["shipping_length"];
	$shipping_width		= $result[$i]["shipping_width"];
	$shipping_height		= $result[$i]["shipping_height"];
	$country_of_origin		= $result[$i]["country_of_origin"];
	$is_hazmat		= $result[$i]["is_hazmat"];
	$current_stock		= $result[$i]["current_stock"];
	$age_group		= $result[$i]["age_group"];
	$skin_type		= $result[$i]["skin_type"];
	
	//$product_url     = $result[$i]["product_url"];
	
	$multi_pack_sku		= $result[$i]["multi_pack_sku"];
	$temp		= $result[$i]["temp"];
	$nioxin_system		= $result[$i]["nioxin_system"];
	$nioxin_size		= $result[$i]["nioxin_size"];
	$nioxin_type		= $result[$i]["nioxin_type"];
	$ship_international		= $result[$i]["ship_international"];
	$free_text_1		= $result[$i]["free_text_1"];
	$free_text_2		= $result[$i]["free_text_2"];
	$color              = $result[$i]["color"];
	$ingredients_pdf		= $result[$i]["ingredients_pdf"];
	//	Is_Multipack	Is_Set	Parent_Sku	Variant	Age_Group
	
	
	$Csv_data_str_23 .= '"'.$product_sku.'","'.$product_name.'","'.$product_url.'","'.$main_image.'","'.$our_price.'","'.$sale_price.'","'.$best_price.'","'.$category.'","'.$product_id.'","'.$product_group_code.'",true,"'.$product_description.'","'.$product_availability.'","","'.$upc.'","'.$brand.'","'.$retail_price.'","'.$price_range.'","","'.$color.'","'.$size.'","'.$pack_size.'","'.$flavour.'","'.$current_stock.'","'.$product_weight.'","'.$product_length.'","'.$general_information.'","'.$gender.'","'.$manufacturer.'","'.$product_type.'","'.$skin_type.'","'.$clearance.'","'.$best_seller.'","'.$new_arrival.'","'.$featured.'","'.$display_rank.'","'.$status.'","'.$product_width.'","'.$product_height.'","'.$shipping_weight.'","'.$shipping_length.'","'.$shipping_width.'","'.$shipping_height.'","'.$country_of_origin.'","'.$is_hazmat.'","'.$is_multipack.'","'.$is_set.'","'.$parent_sku.'","'.$variant.'","'.$age_group.'","'.$multi_pack_sku.'","'.$temp.'","'.$nioxin_system.'","'.$nioxin_size.'","'.$nioxin_type.'","'.$ship_international.'","'.$free_text_1.'","'.$free_text_2.'"';
	$Csv_data_str_23 .= "\n";
	
}

$stream = fopen ($export_file_name, "a+");
fwrite($stream,$Csv_data_str_23);
fclose($stream);	
//exit;
/*$filename = "doofinder_products" . time() . ".csv";
header('Content-type: application/csv');
header('Set-Cookie: fileLoading=true'); 
setcookie('fileLoading', 'true', 0, '/');
header('Content-Disposition: attachment; filename=' . $filename);
echo $Csv_data_str_23;*/
exit;
