<?php
class General extends GlobalClassConstruct	
{
	var $smarty;
	function __construct(&$dbobj,&$smarty)
	{
		parent::__construct();
		$this->dbobj = $dbobj;
		$this->smarty = $smarty;
	}
	//This function is used to get the Mail Templates for the Mails ( send to friend, registration, etc )
	function Get_Mail_Template($mail_name) 
	{
		$aEmailTemplate = $this->dbobj->select("SELECT `subject`, `mail_body` FROM `".TABLE_PREFIX."email_templates` WHERE 	template_var_name='".$mail_name."' AND status='1'");
		
		if( count($aEmailTemplate) > 0 ) 
		{
			return $aEmailTemplate;
		}
		else 
		{
			return false;
		}
	}
	
	//Functions For the URL Re-Writing Start Here
	function getCategoryRewriteURL($cat_id)
	{
		global $Site_URL, $memcache_obj;
		
		/* New code added by HK on May 31,2014 START */
		$cURL = $this->getManualCategoryURL((int)$cat_id);
		if($cURL != '')
			return $Site_URL.$cURL;
		
		$cat_res = false;
		if($memcache_obj){
			$cat_res = $memcache_obj->get('GEN_GetCategoryRewriteURL_'.(int)$cat_id);
		}
		if($cat_res === false)
		{
			$cat_sql = "SELECT category_id,parent_id,category_name,template_page ";
			$cat_sql .= " FROM ".TABLE_PREFIX."category ";
			$cat_sql .= " WHERE category_id = ".(int)$cat_id;
			
			$cat_res = $this->dbobj->select($cat_sql);
			if($memcache_obj){
				$memcache_obj->add('GEN_GetCategoryRewriteURL_'.(int)$cat_id, $cat_res, 43200);
			}
		}
		
		
		$url_str = '';
		
		if ( count($cat_res) > 0 )
		{
			$template_file = $cat_res[0]['template_page'];
			
			
			
			$category_url = $this->getParentCategoryRewriteURL($cat_res[0]['category_id'],1);
				
			if ( $template_file == "build_your_ring" )
			{
				$category_url = $this->getParentCategoryRewriteURL($cat_res[0]['category_id'],1);
				$url_str = $Site_URL.$category_url."/bcid/".$cat_res[0]['category_id'];
			}
			elseif ( $template_file == "product_list" )
			{
				$category_url = $this->getParentCategoryRewriteURL($cat_res[0]['category_id'],1);
				if($_GET['category_id'] == '15')
				{
					$category_url = str_replace("---","-",$category_url);	
				}
				$url_str = $Site_URL.$category_url."/pcid/".$cat_res[0]['category_id'];
			}
			elseif ( $template_file == "category_list" )
			{
				$category_url = $this->getParentCategoryRewriteURL($cat_res[0]['category_id'],1);
				// Diamond Category
				//if($cat_res[0]['category_id'] == 3)
					//$url_str = $Site_URL."diamond-jewelry";
				//else
					$url_str = $Site_URL.$category_url."/cid/".$cat_res[0]['category_id'];
			}
			elseif ( $template_file == "stud_solitaire" )
			{
				$category_url = $this->getParentCategoryRewriteURL($cat_res[0]['category_id'],1);
				$url_str = $Site_URL.$category_url."/scid/".$cat_res[0]['category_id'];
			}
			else
			{
				$url_str = $Site_URL."index.php?file=".$cat_res[0]['template_page']."&category_id=".$cat_res[0]['category_id']."&track=head";
			}
		}
		return $url_str;
	}
	
	//product detail url
	function getProductRewriteURL($productid='',$product_name='',$is_build='No',$icatn='',$sku='',$catName='',$url='')
	{	
		global $Site_URL, $memcache_obj;
		
		
		//if($url != '')
			//return $url;
		
		//echo $catName; exit;
		if($productid=='')
			return false;
		
		
		$product_name = $product_name;	
		
		if(trim($product_name) == '')
		{
			$productnameres = $this->dbobj->select("SELECT product_name,sku FROM `".TABLE_PREFIX."products` WHERE product_id=".(int)$productid." limit 0,1 ");
			
			if ( count($productnameres) > 0 )
			{
				$product_name = $productnameres[0]['product_name']."-".$productnameres[0]['sku'];
			}	
			else
			{
				return false;	
			}	
		}
				
		$product_name = $this->remove_special_chars_cat($product_name);
		
		$catName = $this->remove_special_chars_cat($catName);
		//echo $catName; exit;
		/*$check_cat_res = false;
		if($memcache_obj){
			$check_cat_res = $memcache_obj->get('GetProductRewriteURL_CHKCAT_'.(int)$icatn);
		}
		if($check_cat_res === false)
		{*/
		$check_cat_sql = "SELECT category_id,parent_id FROM ".TABLE_PREFIX."category WHERE category_id = '".(int)$icatn."' AND status='1'";
		$check_cat_res = $this->dbobj->select($check_cat_sql);	
		
			/*if($memcache_obj){
				$memcache_obj->add('GetProductRewriteURL_CHKCAT_'.(int)$icatn, $check_cat_res, 43200);
			}
		}*/
		
		
		if($icatn=='' || count($check_cat_res)==0)
		{
			$prod_query = "SELECT pcr.products_id,c.category_id FROM ".TABLE_PREFIX."products_category as pcr,
							".TABLE_PREFIX."category AS c WHERE pcr.category_id = c.category_id
							 AND c.status = '1' AND pcr.products_id = '".(int)$productid."'
							ORDER BY c.display_position,c.category_name LIMIT 0,1";
	
			$prod_sql_res =$this->dbobj->select($prod_query);
			$icatid=$prod_sql_res[0]['category_id'];
		}
		
		$category_url = $this->getParentCategoryRewriteURL($icatn,1);
		
		/*if($product_name=='')
		{	
			//return $Site_URL.$category_url."/pid/".$productid."/".$icatid;
			//return $Site_URL.$product_name."-pid-".$productid."-cid-".$icatid.".html";
			return $Site_URL.$product_name."-pid-".$productid.".html";
		}	
		else
		{
			//return $Site_URL.$category_url."/".$product_name."/pid/".$productid."/".$icatid;
			//return $Site_URL.$product_name."-pid-".$productid."-cid-".$icatid.".html";
			return $Site_URL.$product_name."-pid-".$productid.".html";
		}*/
		if($product_name=='')
		{	
			//return $Site_URL.$category_url."/pid/".$productid."/".$icatid;
			//return $Site_URL.$product_name."-pid-".$productid."-cid-".$icatid.".html";
			//return $Site_URL.$catName.'/product/'.$product_name."/".strtolower($sku).".html";
			return $catName.'/product/'.$product_name."/".strtolower($sku).".html";
		}	
		else
		{
			//return $Site_URL.$category_url."/".$product_name."/pid/".$productid."/".$icatid;
			//return $Site_URL.$product_name."-pid-".$productid."-cid-".$icatid.".html";
			//return $Site_URL.$catName.'/product/'.$product_name."/".strtolower($sku).".html";
			return $catName.'/product/'.$product_name."/".strtolower($sku).".html";
		}
		
		/*if($product_name=='')
		{	
			//return $Site_URL.$category_url."/pid/".$productid."/".$icatid;
			//return $Site_URL.$product_name."-pid-".$productid."-cid-".$icatid.".html";
			return $Site_URL.$product_name."/pid/".$sku;
		}	
		else
		{
			//return $Site_URL.$category_url."/".$product_name."/pid/".$productid."/".$icatid;
			//return $Site_URL.$product_name."-pid-".$productid."-cid-".$icatid.".html";
			return $Site_URL.$product_name."/pid/".$sku;
		}*/	
	}
	/*
	generate the all levels of category url from child to parent
	*/ 
	function getParentCategoryRewriteURL($catid,$replace_flg = 0)
	{
		global $Site_URL,$memcache_obj;
		
		$tmp_new_vcat_name = false;
		if($memcache_obj){
			$tmp_new_vcat_name = $memcache_obj->get('ParentCategoryRewriteURL_'.$catid.'_'.$replace_flg);
			if($tmp_new_vcat_name !== false)
			{
				return $tmp_new_vcat_name;
			}
		}
		
		$new_vcat_name='';
		
		$cSQL = "SELECT category_id,parent_id,category_name ";
		$cSQL .= " FROM `".TABLE_PREFIX."category` ";
		$cSQL .= " WHERE `category_id` = ".(int)$catid." AND status='1' ";
		//$cSQL .= " ORDER BY category_name";
		
		$cRes = $this->dbobj->select($cSQL);
		
		if ( count($cRes) > 0 )
		{
			$new_iparent_id = $cRes[0]["parent_id"];
			$new_icat_id = $cRes[0]["category_id"];
			
			if($replace_flg == 1)
			$new_vcat_name = $this->remove_special_chars_cat(trim($cRes[0]["category_name"]));
			else
			$new_vcat_name = $this->remove_special_chars(trim($cRes[0]["category_name"]));
		
			while($new_iparent_id!=0)
			{	
				//$newcres = $this->dbobj->select("SELECT category_id,parent_id,category_name FROM `".TABLE_PREFIX."category` WHERE `category_id` = ".(int)$new_iparent_id." AND status='1' ORDER BY category_name");
				$newcres = $this->dbobj->select("SELECT category_id,parent_id,category_name FROM `".TABLE_PREFIX."category` WHERE `category_id` = ".(int)$new_iparent_id." AND status='1'");
				
				$new_iparent_id = $newcres[0]["parent_id"];
				$new_icat_id = $newcres[0]["category_id"];
				if($replace_flg == 1)
				$new_vcat_name= $this->remove_special_chars_cat(trim($newcres[0]["category_name"]))."/".$new_vcat_name;
				else
				$new_vcat_name= $this->remove_special_chars(trim($newcres[0]["category_name"]))."/".$new_vcat_name;
				
			}
		}
		if($memcache_obj){
			$memcache_obj->add('ParentCategoryRewriteURL_'.$catid.'_'.$replace_flg, $new_vcat_name, 43200);
		}
		
		return $new_vcat_name;
	}
	
	/*
	Remove the Special Characters from the url re-writing  
	*/
	function remove_special_chars($str)
	{
		$str = preg_replace("/[,^!<>@\/()\"]*/","",$str);
		//$str = str_replace("-","_",strtolower($str));
		$str = str_replace("  "," ",strtolower($str));
		$str = str_replace(" ","_",strtolower($str));
		return $str;
	}
	
	function remove_special_chars_cat($str)
	{
		$str = preg_replace("/[,^!<>@\/()\"]*/","",$str);
		//$str = str_replace("-","_",strtolower($str));
		$str = str_replace("  "," ",strtolower($str));
		$str = str_replace("+","-",strtolower($str));
		$str = str_replace(" ","-",strtolower($str));
		$str = str_replace("&","and",strtolower($str));
		return $str;
	}
	/*
	/*=====================This function is use for shorting array by key value===========================*/
	function aasort (&$array, $key) {
		$sorter=array();
		$ret=array();
		reset($array);
		foreach ($array as $ii => $va) {
			$sorter[$ii]=$va[$key];
		}
		asort($sorter);
		foreach ($sorter as $ii => $va) {
			$ret[]=$array[$ii];
		}
		$array=$ret;
	}
}
?>
