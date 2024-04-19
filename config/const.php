<?php
/**
 * Define value
 *
 * @return  [type]  [return description]
 */
 
$PHYSICAL_PATH = base_path() . "/";
$SITE_URL = config('app.url');
$SITE_URL_CDN = "https://cdnurl.cdnurl.come";
//$SITE_URL_CDN = $SITE_URL;
//$SITE_URL_LIVE = config('app.url')."/";
$SITE_URL_LIVE = config('app.url');
$APP_URLS = config('app.url').'/';

$SITE_IMAGES_PATH = public_path('images') . '/';
$SITE_IMAGES_URL = $SITE_URL_LIVE .'/images/';

$SITE_CSV_PATH = public_path('csv_import') . '/';
$SITE_CSV_EXPORT_PATH = public_path('csv_export') . '/';
$SITE_CATALOGS_PATH =public_path()."/";
$SITE_CATALOGS_URL =config('app.url')."/";
//$SITE_ASSET_URL = $SITE_URL;
$BlankFooterRoute = array('checkout','orderconfirm','orderprocess');
//echo "11"; exit;

return [
    'ENV' => 'Dev',
    "referral_email" => array(''),
    
    'PHYSICAL_PATH' 			=> $PHYSICAL_PATH,
	'SITE_URL' 					=> $SITE_URL,
	'SITE_URL_CDN' 				=> $SITE_URL_LIVE,
	'APP_URLS' 					=> $APP_URLS,
    'SITE_NAME' 				=> 'HBA Store',
    'FROM_MAIL' 				=> 'info@pbkriscosales.net',
    'INFO_MAIL' 				=> 'info@pbkriscosales.net',
	'SITE_URL_LIVE' 			=> $SITE_URL_LIVE,
	'DB_TABLE_PREFIX' 			=> 'hba_',
	'PRODUCT_PER_PAGE' 			=> '36',
	'SITE_IMAGES_URL' 			=> $SITE_IMAGES_URL,
	'SITE_IMAGES_PATH' 			=> $SITE_IMAGES_PATH,
	'CURRENCY_CODE' 			=> '$',
    'CAT_IMAGE_PATH' 			=> $SITE_IMAGES_PATH.'catimages/',
    'CAT_IMAGE_URL' 			=> $SITE_IMAGES_URL . 'catimages/',
    'CAT_IMAGE_THUMB_WIDTH' 	=> 240,
    'CAT_IMAGE_THUMB_HEIGHT' 	=> 240,
	'CAT_BANNER_WIDTH' 			=> 987,
	'CAT_BANNER_HEIGHT' 		=> 192,
	'CAT_IMAGE_MENU_WIDTH' 		=> 260,
	'CAT_IMAGE_MENU_HEIGHT' 	=> 430,

	'CAT_BANNER_PATH' 			=> $SITE_IMAGES_PATH.'catbanners/',
    'CAT_BANNER_URL' 			=> $SITE_IMAGES_URL . 'catbanners/',
	'CAT_BANNER_THUMB_WIDTH' 	=> 1053,
    'CAT_BANNER_THUMB_HEIGHT' 	=> 550,
	
	'CAT_PROMOTION_PATH' 			=> $SITE_IMAGES_PATH.'catpromotion/',
    'CAT_PROMOTION_URL' 			=> $SITE_IMAGES_URL . 'catpromotion/',
	'CAT_PROMOTION_THUMB_WIDTH' 	=> 1363,
    'CAT_PROMOTION_THUMB_HEIGHT' 	=> 160,

	'BRAND_IMAGE_PATH' 			=> $SITE_IMAGES_PATH.'brandimages/',
	'BRAND_IMAGE_URL' 			=> $SITE_IMAGES_URL . 'brandimages/',
    'BRAND_IMAGE_THUMB_WIDTH' 	=> 240,
    'BRAND_IMAGE_THUMB_HEIGHT' 	=> 240,
	'BRAND_BANNER_WIDTH' 		=> 987,
	'BRAND_BANNER_HEIGHT' 		=> 192,
	'BRAND_IMAGE_MENU_WIDTH' 	=> 260,
	'BRAND_IMAGE_MENU_HEIGHT' 	=> 430,

	
	'TRADESHOW_IMAGE_PATH' 			=> $SITE_IMAGES_PATH.'tradeshowimages/',
	'TRADESHOW_IMAGE_URL' 			=> $SITE_IMAGES_URL . 'tradeshowimages/',
    'TRADESHOW_IMAGE_THUMB_WIDTH' 	=> 240,
    'TRADESHOW_IMAGE_THUMB_HEIGHT' 	=> 240,
	
	// Added code for Category Tile Images as on 19-10-2022 Start

	'CAT_IMAGE_BEAUTY_WIDTH' => 500,
    'CAT_IMAGE_BEAUTY_HEIGHT' => 535,
    'CAT_BEAUTY_IMAGE_PATH' => $SITE_IMAGES_PATH.'cat_beauty_images/',
    'CAT_BEAUTY_IMAGE_URL' => $SITE_IMAGES_URL . 'cat_beauty_images/',
    
    // Added code for Category Tile Images as on 19-10-2022 End
	
	//Category Landing Page HBAStore
	'CAT_IMAGE_URL_LANDING' => $SITE_IMAGES_URL . 'cat_landing_images/',
	'CAT_IMAGE_PATH_LANDING' => $SITE_IMAGES_PATH.'cat_landing_images/',
	
	'PDF_PATH' => $SITE_IMAGES_PATH . 'Groupon_Ingredients/',
	'PDF_URL' => $SITE_IMAGES_URL.'Groupon_Ingredients/',
	
	'CAT_ADS_IMAGE_PATH' => $SITE_IMAGES_PATH.'cat_ads_images/',
	'CAT_ADS_IMAGE_URL' => $SITE_IMAGES_URL . 'cat_ads_images/',
	'CAT_ADS1_BANNER_WIDTH' => 365,
	'CAT_ADS2_BANNER_WIDTH' => 615,
	'CAT_ADS_BANNER_HEIGHT' => 500,
	
	'BlankFooterRoute' => $BlankFooterRoute,
	'CAT_BOTTOM_BANNER_WIDTH' => 1250,
	'CAT_BOTTOM_BANNER_HEIGHT' => 450,
	'CAT_BOTTOM_BANNER_WIDTH_MOBILE' => 767,
	'CAT_BOTTOM_BANNER_HEIGHT_MOBILE' => 400,
	
	'NO_IMAGE_SMALL' => 'https://via.placeholder.com/200x200?text=Coming%20Soon',
	'NO_IMAGE_THUMB' => 'https://via.placeholder.com/500x500?text=Coming%20Soon',
	'NO_IMAGE_MEDIUM' => 'https://via.placeholder.com/800x800?text=Coming%20Soon',
	'NO_IMAGE_LARGE' => 'https://via.placeholder.com/1000x1000?text=Coming%20Soon',
	'NO_IMAGE_ZOOM' => 'https://via.placeholder.com/1500x1500?text=Coming%20Soon',

	'NO_IMAGE_PRD_SMALL' => 'https://via.placeholder.com/200x200?text=Coming%20Soon',
	'NO_IMAGE_PRD_THUMB' => 'https://via.placeholder.com/500x500?text=Coming%20Soon',
	'NO_IMAGE_PRD_MEDIUM' => 'https://via.placeholder.com/800x800?text=Coming%20Soon',
	'NO_IMAGE_PRD_LARGE' => 'https://via.placeholder.com/1000x1000?text=Coming%20Soon',
	'NO_IMAGE_PRD_ZOOM' => 'https://via.placeholder.com/1500x1500?text=Coming%20Soon',
	
	

    'NO_IMAGE_1250_450' => 'https://via.placeholder.com/1250x450?text=Coming%20Soon',
    'NO_IMAGE_767_400' => 'https://via.placeholder.com/767x400?text=Coming%20Soon',
    'NO_IMAGE_374_250' => 'https://via.placeholder.com/374x250.jpg?text=Coming%20Soon',

	
    'IMG_URL' =>  'https://s3.amazonaws.com/',
    //'IMG_URL_CDN' =>  'https://dza1z3oi4v7yf.cloudfront.net/',
    'IMG_URL_CDN' =>  'https://d2g2nohzesoj6p.cloudfront.net/',
    //'IMG_URL' =>  'https:',

    
	
	'NO_IMAGE' => $SITE_IMAGES_URL . 'noimage.jpg',
	'NO_IMAGE_THUMB' => $SITE_IMAGES_URL  . 'noimage_th.jpg',
	'NO_IMAGE_POPULAR' => $SITE_IMAGES_URL . 'defaultpopular.jpg',
	'NO_IMAGE_MEDIUM' => $SITE_IMAGES_URL . 'noimage_md.jpg',
    //'NO_IMAGE_LARGE' => $SITE_IMAGES_URL . 'noimage_lr.jpg',
    'NO_IMAGE_LARGE' => $SITE_IMAGES_URL . 'noimage-830X830.jpg',
	
	'NO_IMAGE_100' => $SITE_IMAGES_URL.'noimage-100X100.jpg',
	'NO_IMAGE_240' => $SITE_IMAGES_URL.'noimage-240X240.jpg',
	'NO_IMAGE_300' => $SITE_IMAGES_URL.'noimage-300X300.jpg',
	'NO_IMAGE_830' => $SITE_IMAGES_URL.'noimage-830X830.jpg',
	
	'PRD_INGREDIENTS_PDF_PATH' => $SITE_IMAGES_PATH . 'productimages/pdf/',
	'PRD_INGREDIENTS_PDF_URL' => $SITE_IMAGES_URL . 'productimages/pdf/',
	
	'PRD_THUMB_IMG_PATH' => $SITE_IMAGES_PATH . 'productimages/thumb/',
	'PRD_THUMB_IMG_URL' => $SITE_IMAGES_URL . 'productimages/thumb/',
	
	'PRD_SMALL_IMG_PATH' => $SITE_IMAGES_PATH . 'productimages/small/',
	'PRD_SMALL_IMG_URL' => $SITE_IMAGES_URL . 'productimages/small/',
	
	'PRD_MEDIUM_IMG_PATH' => $SITE_IMAGES_PATH . 'productimages/medium/',
	'PRD_MEDIUM_IMG_URL' => $SITE_IMAGES_URL . 'productimages/medium/',
	
	'PRD_LARGE_IMG_PATH' => $SITE_IMAGES_PATH . 'productimages/large/',
	'PRD_LARGE_IMG_URL' => $SITE_IMAGES_URL . 'productimages/large/',
	
	'PRD_ZOOM_IMG_PATH' => $SITE_IMAGES_PATH . 'productimages/zoom/',
	'PRD_ZOOM_IMG_URL' => $SITE_IMAGES_URL . 'productimages/zoom/',
	
	'PRD_IMG_PATH' => $SITE_IMAGES_PATH . 'productimages/',
	'PRD_IMG_URL' => $SITE_IMAGES_URL . 'productimages/',
		
	'PRD_IMG_WIDTH' => 1200,
	'PRD_IMG_HEIGHT' => 700,
	
	'PRD_ZOOM_IMG_WIDTH' => 1200,
	'PRD_ZOOM_IMG_HEIGHT' => 1200,
		
	'PRD_LARGE_IMG_WIDTH'=>900,
	'PRD_LARGE_IMG_HEIGHT'=>900,
	
	'PRD_MEIDUM_IMG_WIDTH'=>600,
	'PRD_MEIDUM_IMG_HEIGHT'=>600,
	
	'PRD_THUMB_IMG_WIDTH'=>300,
	'PRD_THUMB_IMG_HEIGHT'=>300,
	
	'PRD_SMALL_IMG_WIDTH'=>50,
	'PRD_SMALL_IMG_HEIGHT'=>50,
	
	//'PRD_video_PATH' => $PHYSICAL_PATH . 'video/',
	//'PRD_video_URL' => $SITE_URL . 'video/',
	
	'COLOR_SWATCHES_PATH' => $SITE_IMAGES_PATH . 'color_swatches/',
	'COLOR_SWATCHES_URL' => $SITE_IMAGES_URL . 'color_swatches/',
	'COLOR_SWATCHES_DEFAULT' => $SITE_IMAGES_URL . 'color.jpg',
	
	//'PRD_THUMB_MAX_WIDTH' => 130,
	'PRD_THUMB_MAX_WIDTH' => 150,
	'PRD_THUMB_MAX_HEIGHT' => 150,
	'PRD_MEDIUM_MAX_WIDTH' => 445,
	'PRD_MEDIUM_MAX_HEIGHT' => 445,
	'PRD_LARGE_MAX_WIDTH' => 600,
	'PRD_LARGE_MAX_HEIGHT' => 600,
	
	
	'POPULAR_CAT_IMAGE_PATH' => $SITE_IMAGES_PATH . 'popularcatimg/',
    'POPULAR_CAT_IMAGE_URL' => $SITE_IMAGES_URL . 'popularcatimg/',
    'POPULAR_CAT_IMAGE_WIDTH' => 209,
    'POPULAR_CAT_IMAGE_HEIGHT' => 209,
	
	'HOMEPAGE_CAT_IMAGE_PATH' => $SITE_IMAGES_PATH . 'popularcatimg/',
    'HOMEPAGE_CAT_IMAGE_URL' => $SITE_IMAGES_URL . 'popularcatimg/',
    'HOMEPAGE_CAT_IMAGE_WIDTH' => 240,
    'HOMEPAGE_CAT_IMAGE_HEIGHT' => 240,
	
    'PRESS_LARGE_MAX_WIDTH_SIZE' =>  '500px X 500px',
    'PRESS_LARGE_MAX_WIDTH' =>  500,
    'PRESS_LARGE_MAX_HEIGHT' =>  500,
    'PRESS_THUMB_MAX_WIDTH' =>  260,
    'PRESS_THUMB_MAX_HEIGHT' =>  260,
    'PRESS_THUMB_IMAGE_PATH' => $SITE_IMAGES_PATH . 'pressimages/thumb/',
    'PRESS_THUMB_IMAGE_URL' => $SITE_IMAGES_URL . 'pressimages/thumb/',
    'PRESS_LARGE_IMAGE_PATH' => $SITE_IMAGES_PATH . 'pressimages/large/',
    'PRESS_LARGE_IMAGE_URL' => $SITE_IMAGES_URL . 'pressimages/large/',
    'PDF_FILE_PATH' => $SITE_IMAGES_PATH . 'pressimages/pdf/',
    'PDF_FILE_URL' => $SITE_IMAGES_URL . 'pressimages/pdf/',
    'POLL_IMAGE_WIDTH' =>  365,
    'POLL_IMAGE_HEIGHT' => 365,
	'ARTICAL_IMAGE_PATH' => $SITE_IMAGES_PATH ."artical_images/",
	'ARTICAL_IMAGE_URL' => $SITE_IMAGES_URL ."artical_images/",
	'ARTICAL_IMAGE_THUMB_WIDTH' => 209,
	'ARTICAL_IMAGE_THUMB_HEIGHT'=> 209,
	'STATIC_IMAGE_PATH' => $SITE_IMAGES_PATH."static_images/",
	'STATIC_IMAGE_URL' => $SITE_IMAGES_PATH."static_images/",
	'STATIC_IMAGE_THUMB_WIDTH' => 209,
	'STATIC_IMAGE_THUMB_HEIGHT' => 209,
	'POLL_IMAGE_PATH'=> $SITE_IMAGES_PATH."pollimg/",
	'POLL_IMAGE_URL'=> $SITE_IMAGES_URL."pollimg/",
	'POLL_IMAGE_WIDTH'=> 365,
	'POLL_IMAGE_HEIGHT'=> 365,

    'BRAND_LOGO_PATH' => $SITE_IMAGES_PATH . 'brandlogo/',
    'BRAND_LOGO_URL' => $SITE_IMAGES_URL . 'brandlogo/',
    'BRAND_LOGO_WIDTH' =>  175,
    'BRAND_LOGO_HEIGHT' =>  73,
    'BRAND_DEFAULT_IMG' => $SITE_IMAGES_URL . 'brand_default.jpg',

    'MANUFACTURE_LOGO_PATH' => $SITE_IMAGES_PATH . 'manufacturelogo/',
    'MANUFACTURE_LOGO_URL' => $SITE_IMAGES_URL . 'manufacturelogo/',
    'MANUFACTURE_LOGO_WIDTH' =>  175,
    'MANUFACTURE_LOGO_HEIGHT' =>  75,

    'MANUFACTURER_IMAGE_PATH' 			=> $SITE_IMAGES_PATH.'manufacturerimages/',
	'MANUFACTURER_IMAGE_URL' 			=> $SITE_IMAGES_URL . 'manufacturerimages/',
    'MANUFACTURER_IMAGE_THUMB_WIDTH' 	=> 240,
    'MANUFACTURER_IMAGE_THUMB_HEIGHT' 	=> 240,

    'MENUIMAGE_PATH' 			=> $SITE_IMAGES_PATH.'menuimage/',
	'MENUIMAGE_URL' 			=> $SITE_IMAGES_URL . 'menuimage/',
    'MENUIMAGE_WIDTH' 	=> 250,
    'MENUIMAGE_HEIGHT' 	=> 250,

    'HOME_IMAGE_PATH' => $SITE_IMAGES_PATH . 'homeimg/',
    'HOME_IMAGE_URL' => $SITE_IMAGES_URL . 'homeimg/',
    'HOME_BANNER_WIDTH' =>  1900,
    'HOME_BANNER_HEIGHT' =>  474,
    'HOME_BOTTOM_BANNER_WIDTH' =>  189,
    'HOME_BOTTOM_BANNER_HEIGHT' =>  113,

    'LOOKBOOK_IMAGE_PATH' => $SITE_IMAGES_PATH . 'lookbookimg/',
    'LOOKBOOK_IMAGE_URL' => $SITE_IMAGES_URL . 'lookbookimg/',

    'DREAMROOM_IMAGE_PATH' => $SITE_IMAGES_PATH . 'dreamroomimg/',
    'DREAMROOM_IMAGE_URL' => $SITE_IMAGES_URL . 'dreamroomimg/',

    'SPECIES_IMAGE_PATH' => $SITE_IMAGES_PATH . 'speciesimg/',
    'SPECIES_IMAGE_URL' => $SITE_IMAGES_URL . 'speciesimg/',

    'IMPORT_CSV_PATH' => $SITE_CSV_PATH,
    'EXPORT_CSV_PATH' => $SITE_CSV_EXPORT_PATH,

    'FIBER_IMAGE_PATH' => $SITE_IMAGES_PATH . 'fiberimg/',
    'FIBER_IMAGE_URL' => $SITE_IMAGES_URL . 'fiberimg/',

    'SALES_REPRESENTATIVE_IMAGE_PATH' => $SITE_IMAGES_PATH . 'sales_representative/',
    'SALES_REPRESENTATIVE_IMAGE_URL' => $SITE_IMAGES_URL . 'sales_representative/',
    'SALES_REPRESENTATIVE_WIDTH' =>  129,
    'SALES_REPRESENTATIVE_HEIGHT' =>  129,

    'HOME_PAGE_DESKTOP_BANNER_WIDTH' =>  1670,
    'HOME_PAGE_DESKTOP_BANNER_HEIGHT' =>  550,
	
	'HOME_PAGE_MOBILE_BANNER_WIDTH' =>  770,
    'HOME_PAGE_MOBILE_BANNER_HEIGHT' =>  610,
	
	'HOME_PAGE_MIDDLE_DESKTOP_PROMOTION_WIDTH' =>  1520,
    'HOME_PAGE_MIDDLE_DESKTOP_PROMOTION_HEIGHT' =>  175,
	
	'HOME_PAGE_MIDDLE_MOBILE_PROMOTION_WIDTH' =>  767,
    'HOME_PAGE_MIDDLE_MOBILE_PROMOTION_HEIGHT' =>  350,
	
	'HOME_PAGE_MIDDLE_DESKTOP_WHOLESALER_WIDTH' =>  1363,
    'HOME_PAGE_MIDDLE_DESKTOP_WHOLESALER_HEIGHT' =>  125,
	
	'HOME_PAGE_MIDDLE_MOBILE_WHOLESALER_WIDTH' =>  767,
    'HOME_PAGE_MIDDLE_MOBILE_WHOLESALER_HEIGHT' =>  300,
	
	'HOME_PAGE_BOTTOM_DESKTOP_BEAUTY_WIDTH' =>  500,
    'HOME_PAGE_BOTTOM_DESKTOP_BEAUTY_HEIGHT' =>  535,
	
	'HOME_PAGE_BOTTOM_MOBILE_BEAUTY_WIDTH' =>  500,
    'HOME_PAGE_BOTTOM_MOBILE_BEAUTY_HEIGHT' =>  535,
	
    
	'HOME_PAGE_COMMON_DESKTOP_BANNER_WIDTH' =>  1500,
    'HOME_PAGE_COMMON_DESKTOP_BANNER_HEIGHT' =>  650,
    
    //'HOME_PAGE_POPULAR_CAT_IMAGE_WIDTH' => 515,
    //'HOME_PAGE_POPULAR_CAT_IMAGE_HEIGHT' => 360,
	
	'HOME_PAGE_POPULAR_CAT_IMAGE_WIDTH' => 240,
    'HOME_PAGE_POPULAR_CAT_IMAGE_HEIGHT' => 240,
	
    'SITE_CURRENCY_SYMBOL' 	=> '$',
	'hrd_lm_lv_cat_id_arr' 	=> array(69,73,79),
	'zero_price' 			=> " case when manufacture_id in ('34','35','36','26','33','55','47') then 0 else sale_price end as sale_price,
								case when manufacture_id in ('34','35','36','26','33','55','47') then 0 else retail_price end as retail_price,
								case when manufacture_id in ('34','35','36','26','33','55','47') then 0 else our_price end as our_price ",
	'req_qt_restricted_manu_id_arr' => array(),
	'zero_price_formated' 			=> " case when manufacture_id in ('34','35','36','26','33','55','47') then 0 else sale_price end as sale_price,case when manufacture_id in ('34','35','36','26','33','55','47') then 0 else retail_price end as retail_price,case when manufacture_id in ('34','35','36','26','33','55','47') then 0 else our_price end as our_price ",	
	'COLOR_IMAGE' => $SITE_CATALOGS_PATH . 'color_image/',
	'COLOR_IMAGE_URL' => $SITE_CATALOGS_URL . 'color_image/',
	'SHAPE_IMAGE' => $SITE_CATALOGS_PATH . 'shape_image/',
	'SHAPE_IMAGE_URL' => $SITE_CATALOGS_URL . 'shape_image/',
	'STYLE_IMAGE' => $SITE_CATALOGS_PATH . 'style_image/',
	'STYLE_IMAGE_URL' => $SITE_CATALOGS_URL . 'style_image/',	
	'BRAND_IMAGE' => $SITE_CATALOGS_PATH . 'brand_image/',
	//'BRAND_IMAGE_URL' => $SITE_CATALOGS_URL . 'brand_image/',	
    'MATERIAL_IMAGE' => $SITE_CATALOGS_PATH . 'material_image/',
	'MATERIAL_IMAGE_URL' => $SITE_CATALOGS_URL . 'material_image/',							
	'CATALOGS_WIDTH' =>  310,
    'CATALOGS_HEIGHT' =>  310,
	'CUSTOMER_PHOTO_WIDTH' =>  310,
    'CUSTOMER_PHOTO_HEIGHT' =>  310,
	'SHAPE_CATALOGS_WIDTH' =>  231, //200
    'SHAPE_CATALOGS_HEIGHT' =>  231, //200
	'COLOR_CATALOGS_WIDTH' =>  280,
    'COLOR_CATALOGS_HEIGHT' =>  280,	
    'STYLE_HOMEPAGE_WIDTH'=>300,
    'STYLE_HOMEPAGE_HEIGHT'=>300,
    'MATERIAL_HOMEPAGE_WIDTH' =>300,
    'MATERIAL_HOMEPAGE_HEIGHT' => 300,
    'ENABLE_MEMCACHED'	=> 1,
    // Added code for Avalara Tax as on 25-10-2022 Start

    /*'AVATAX_USERNAME' 	=> 'qualdevcsteam@gmail.com1',
    'AVATAX_PASSWORD' 	=> 'QUAL@Dev6505',
    'AVATAX_MODE' 		=> 'sandbox',*/
	
	'AVATAX_USERNAME' 	=> '',
    'AVATAX_PASSWORD' 	=> '',
    'AVATAX_MODE' 		=> 'live',
	'IS_SEARCH_SPRING_ENABLED' 		=> 1,
	
	

    // Added code for size family custome changes
    'COMMON_CUSTOM_SIZE_FOR_CATEGORY' => array('313','314','92'), // '2','148','150','151','153','211'
	'LIMIT_SHOW_PRODUCT_SLIDER' => 10,
	'LIMIT_SHOW_BRAND_LOGO_HOME' => 10,
	'PRODUCT_LISTING_PAGE_LIMIT' => 30,
	'LIMIT_SHOW_CATEGORY'=>6,
	'LIMIT_OTHER_CATEGORY'=>47,
	'LIMIT_OTHER_CATEGORY_MOBILE'=>15,

	//SCHEMA CONST
	'LOGO' => $SITE_IMAGES_URL.'logo.svg',
	'SAMEAS' => '[]',
	'TELEPHONE' => '(732) 514-0100',

	'ADDRESSSTREETADRESS' => '10B Jules Lane',
	'ADDRESSLOCALITY' => 'New Brunswick',
	'ADDRESSREGION' => 'New Jersey',
	'ADDRESSPOSTCODE' => '08901',
	'ADDRESSCOUNTRY' => 'United States',

	'NEWSPRESS_IMG_PATH' => $SITE_IMAGES_PATH . 'news_press/',

	'LIMITED_IMPORT_CSV_PATH' => public_path('csv_limited_import') . '/',
    'LIMITED_EXPORT_CSV_PATH' => public_path('csv_limited_import') . '/',
];
