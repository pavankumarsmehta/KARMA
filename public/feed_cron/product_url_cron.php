<?php

$physical_path = "/home/hbasales/public_html/hbastore/";
define("TABLE_PREFIX","hba_");

include_once("config_setting.php");


ini_set('display_errors',1);

error_reporting(E_ALL);

require_once($physical_path."classes/GlobalClassConstruct.cls.php");
include_once($physical_path."classes/general.cls.php");


if(!isset($generalobj))
{
    $generalobj = new General($obj,$smarty);
}


$start_limit 		= 0;
$end_limit			= 50;
$total_batch		= 0;
$total_record_batch	= 0;

$sql = "SELECT DISTINCT(p.product_id) FROM ".TABLE_PREFIX."products AS p WHERE (p.product_url = '' || p.product_url IS NULL) GROUP BY p.product_id";
$res_total = $obj->select($sql);
$total_prod = count($res_total);

$total_record_batch  = ceil($total_prod/$end_limit);		
$msg = 'No';
$rec_counter = 1;

for($b=0;$b<$total_prod;$b++ )
{	
	$sel_pro = "SELECT p.product_id, p.sku, p.product_name, p.product_type, p.category, p.parent_category_id, p.product_url, p.parent_sku,c.category_id,p.status,c.category_name FROM ".TABLE_PREFIX."products AS p INNER JOIN ".TABLE_PREFIX."products_category AS pc ON(p.product_id = pc.products_id) INNER JOIN ".TABLE_PREFIX."category AS c ON(pc.category_id = c.category_id) WHERE (p.product_url = '' || p.product_url IS NULL) GROUP BY p.product_id"; 
	$result = $obj->select($sel_pro);
	$tot_rows = count($result);

	$file_content = '';	
	for( $i = 0; $i < $tot_rows; $i++ )	
	{	
		$product_name = clean($result[$i]['product_name']);
		$prod_link = $generalobj->getProductRewriteURL($result[$i]['product_id'],$product_name,'No',$result[$i]['category_id'],$result[$i]['sku'],$result[$i]['category_name']);
		$prod_link = cleanurl($prod_link);
		//echo $prod_link; exit;
		$param['category'] = $result[$i]['category_id'];
		$param['sku'] = $result[$i]['sku'];
		$param['product_name'] = $result[$i]['product_name'];
		$param['product_type'] = $result[$i]['product_type'];
		$param['category'] = $result[$i]['category'];
		$param['parent_category_id'] = $result[$i]['parent_category_id'];
		$param['parent_sku'] = $result[$i]['parent_sku'];
		$param['status'] = $result[$i]['status'];
		$param['product_id'] =$result[$i]['product_id'];
	

		$do_update = 0;
		$old_product_url = $result[$i]['product_url'];

		if($old_product_url != $prod_link)
			$do_update = 1;		

		if($do_update == 1) {
			$update_rank_sql = "UPDATE ".TABLE_PREFIX."products SET product_url = \"".$prod_link."\" WHERE product_id = ".$result[$i]['product_id'];
			$update_rank_result = $obj->sql_query($update_rank_sql);
		}
		$rec_counter++;
	}

	$start_limit = $start_limit+$end_limit;
	$total_batch = $total_batch+1;
	if($total_batch == $total_record_batch ) 
	{	
		$msg = "Product URL Generated Successfully";
	}
}

################# send the mail regarding the cron executed############
$email_subject = "HBA Product URL generated ". $rec_counter ;
$email_body = "There are total ".$rec_counter." records.";
$from = "cs@hbasales.com";
$to = 'sachin.qualdev@gmail.com';
$xyz = @mail($to,$email_subject,$email_body, $from);



function clean($string) {

   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

}



function cleanurl($title) {

	return strtolower(str_replace(" ","-",preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($title))));

}

function cleanproductTitle($title) {

	return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($title));

}
echo $msg;
unset($obj);
?>