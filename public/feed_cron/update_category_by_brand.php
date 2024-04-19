<?php
ob_start();
set_time_limit(0);
ini_set("memory_limit", -1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING);
include_once("config_setting.php");
set_time_limit(0);

function myfunction($value) 
{ 
	$brand_id = $value['brand_id'];
	$category_id = $value['category_id'];

	$insert_brand_category_string.= "($brand_id,$category_id,'1'),";
	return $insert_brand_category_string;
    
} 

$db_query = "SELECT b.brand_id,pc.category_id FROM hba_brand  b INNER JOIN hba_products p ON b.brand_id = p.brand_id INNER JOIN hba_products_category pc ON p.product_id =  pc.products_id GROUP BY pc.category_id,b.brand_id ORDER BY b.brand_id,pc.category_id";
$db_recs = $obj->select($db_query); 
//echo '<pre>';
//print_r($db_recs);
$array_chunk_db_recs = array_chunk($db_recs,100);

if(isset($db_recs) && !empty($db_recs)){
		$truncate_db_query = "TRUNCATE TABLE hba_brand_category";
		$truncate_db_recs = $obj->sql_query($truncate_db_query); 
	foreach($array_chunk_db_recs as $array_chunk_db_recs_key => $array_chunk_db_recs_value){
		$insert_brand_category_string = '';
		$brand_category_string = '';	
		$brand_category_string  = array_map("myfunction",$array_chunk_db_recs_value); 
		$brand_category_string = trim(implode("",$brand_category_string),",");
		 $insert_db_query = "INSERT INTO hba_brand_category (brand_id, category_id, `status`)VALUES $brand_category_string;";
		$db_recs = $obj->insertNew($insert_db_query); 
	}
	echo "successfully update category id in hba_brand_category table";
}else{
	echo "Not brand avaible for update category id ";
}

?>