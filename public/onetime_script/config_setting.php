<?php
	set_time_limit(10000);
	ini_set('memory_limit',"500M");
	ini_set('display_errors',1);
	
	ini_set('error_reporting',E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
	##############LIVE SETTING START########################
	define('DB_SERVER','localhost');
	define('DB_USERNAME','hbasales_dbusr');
	define('DB_PASSWORD','#zCeuuDR6?WA');
	define('DB_DATABASE', 'hbasales_prodb');
	define("TABLE_PREFIX","hba_");
	
	
	$physical_path = "/home/hbasales/public_html/hbastore/";	
	
	
	//require_once($physical_path."public/feed_cron/functions.php");
	
	$Site_URL	   = "https://www.hbastore.com/";	
	$INSECURED_PATH = "https://www.hbastore.com/";
	$CDN_SSL_IMG_URL="https://www.hbastore.com/";
	$CDN_IMG_URL="https://www.hbastore.com/";
	##############LIVE SETTING END########################
	
	###########IMAGES PATH AND URL DEFINED START###################
	define('NO_IMAGE',$Site_URL."images/noimage.jpg");
	define('NO_IMAGE_THUMB',$Site_URL."images/noimage_th.jpg");
	define('NO_IMAGE_MEDIUM',$Site_URL."images/noimage_md.jpg");
	define('NO_IMAGE_LARGE',$Site_URL."images/noimage_lr.jpg");
	define('PRD_THUMB_IMG_PATH',$physical_path."productimages/thumb/");
	define('PRD_THUMB_IMG_URL',$Site_URL."productimages/thumb/");
	define('PRD_MEDIUM_IMG_PATH',$physical_path."productimages/medium/");
	define('PRD_MEDIUM_IMG_URL',$Site_URL."productimages/medium/");
	define('PRD_LARGE_IMG_PATH',$physical_path."productimages/large/");
	define('PRD_LARGE_IMG_URL',$Site_URL."productimages/large/");
	##########IMAGES PATH AND URL DEFINED END###################
	
	##########GENERATED FILE PATH AND URL#######################
	define('EXPORT_CSV_PATH',$physical_path.'feed_cron/');
	define('EXPORT_CSV_URL',$Site_URL.'feed_cron/');
	define('TITLE','HBA');
	##########INCLUDE MYSQL CLASS#######################
	
	define('ERROR_LOG_FOLDER', $physical_path.'err_log/');
	require_once($physical_path."lib/mysql.cls.php");
	$obj = new MySqlClass;
	
	/********************************************/
	$setting_res=$obj->select("select * from `".TABLE_PREFIX."site_settings` ORDER BY site_settings_id");
	//echo "select * from `".TABLE_PREFIX."site_settings` ORDER BY site_settings_id";
	//echo "<pre>"; print_r($setting_res); exit;
	for($i=0;$i<count($setting_res);$i++)
	{	
		define($setting_res[$i]["var_name"],$setting_res[$i]["setting"]);
	}
	/********************************************/
?>