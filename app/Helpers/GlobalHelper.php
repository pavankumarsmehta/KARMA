<?php

namespace App\Helpers;

class GlobalHelper
{

	public static function private_key()
	{
		$private_key = "HBAENCRYPTKEY";

		return md5($private_key);
	}

	public static function encrypt($str1)
	{
		$len = strlen($str1);

		$encrypt_str = "";

		for ($i = 0; $i < $len; $i++) {
			$char = substr($str1, $i, 1);

			$keychar = substr(GlobalHelper::private_key(), ($i % strlen(GlobalHelper::private_key())) - 1, 1);

			$char = chr((ord($char) + ord($keychar)) + ord('&'));

			$encrypt_str .= $char;
		}

		$encrypt_str = GlobalHelper::encoding($encrypt_str);

		return $encrypt_str;
	}

	public static function Decrypt($string)
	{
		$string = GlobalHelper::decoding($string);

		$len = strlen($string);

		$decrypt_str = "";

		for ($i = 0; $i < $len; $i++) {
			$char = substr($string, $i, 1);

			$keychar = substr(GlobalHelper::private_key(), ($i % strlen(GlobalHelper::private_key())) - 1, 1);

			$char = chr((ord($char) - ord($keychar)) - ord('&'));

			$decrypt_str .= $char;
		}

		return $decrypt_str;
	}

	public static function encoding($str)
	{
		return base64_encode(gzdeflate($str));
	}

	public static function decoding($str)
	{
		return gzinflate(base64_decode($str));
	}

	public static function isCrawlingBotDetected()
	{
		// Bots list
		$bot_list = array('Googlebot', 'PetalBot', 'bingbot', 'SemrushBot', 'AhrefsBot',  'DotBot', 'MJ12bot', 'Pinterestbot', 'DataForSeoBot', 'SiteAuditBot', 'MegaIndex', 'PetalBot', 'AddThis.com', 'Applebot', 'facebookexternalhit', 'Yahoo! Slurp', 'DuckDuckBot', 'Baiduspider', 'YandexBot', 'Sogou', 'facebot',      'ia_archiver', 'archive.org_bot', 'Symfony Spider', 'SiteBot', 'Google-HTTP-Java-Client');

		$crawl_user_agent = request()->header('User-Agent');

		foreach ($bot_list as $bl) {
			if (stripos($crawl_user_agent, $bl) !== false) {
				return true;
			}
		}

		return false;
	}

	public static function fbViewPageEventCode()
	{
		$SITE_URL 			= config('global.SITE_URL');
		$IS_TRACKING_WEBSITE 	= config('global.IS_TRACKING_WEBSITE');

		$is_crawler_bot = GlobalHelper::isCrawlingBotDetected();

		if ($is_crawler_bot) {
			return '';
		}

		if ($IS_TRACKING_WEBSITE != 1) {
			return '';
		}


		$eventID     			= '';
		$str_result 			= '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$eventID    			= substr(str_shuffle($str_result), 0, 10);

		//////////////////////////////////
		//Fb product View Cotnent Start
		//////////////////////////////////
		$fb_page_view_event_cotnent = '';

		$fb_page_view_event_cotnent = "fbq('track', 'PageView', {},{eventID:'" . $eventID . "'} );";

		//////////////////////////////////
		//Fb product View Cotnent End
		//////////////////////////////////
		$sourceURL = url()->full();

		$FACEBOOK_LIBPATH 	= config('global.PHYSICAL_PATH');

		$fbrandchar	= '';
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$fbrandchar = substr(str_shuffle($str_result), 0, 36);

		require_once($FACEBOOK_LIBPATH . 'facebook-sdk/vendor/autoload.php');

		$access_token = config('global.FACEBOOB_ACCESS_TOKEN');
		$pixel_id = config('global.FACEBOOB_PIXEL_ID');

		$api = \FacebookAds\Api::init(null, null, $access_token);
		$api->setLogger(new \FacebookAds\Logger\CurlLogger);

		// Set User Data Start
		$user_data = new \FacebookAds\Object\ServerSide\UserData;
		$user_data = $user_data->setClientIpAddress(request()->ip());
		$user_data = $user_data->setClientUserAgent(request()->header('User-Agent'));

		if (session()->has('fbclid')) {
			if (trim(session()->get('fbclid')) != '') {
				$fbclid = session()->get('fbclid');
			} else {
				$fbclid = $fbrandchar;
			}
		} else {
			$fbclid = $fbrandchar;
		}
		//$user_data = $user_data->setFbc("fb.1.".strtotime(date('Y-m-d h:i:s')).'.'.$fbclid);
		//$user_data = $user_data->setFbp("fb.1.".strtotime(date('Y-m-d h:i:s')).'.'.rand(1111111111,9999999999));

		// Set User Data End


		// Set product cotnent Data Start
		/*
		$arrayProducts = [];
		$content = new \FacebookAds\Object\ServerSide\Content;
		$content = $content->setProductId($fb_product_sku);
		$content = $content->setItemPrice($fb_product_price);
		$content = $content->setTitle($fb_product_name);
		$content = $content->setQuantity(1);
		array_push($arrayProducts, $content);
		*/
		// Set product cotnent Data End

		// Set custome Data Start
		/*
		$custom_data = new \FacebookAds\Object\ServerSide\CustomData;
		//$custom_data = $custom_data->setContents($arrayProducts);
		$custom_data = $custom_data->setContentType('product');
		$custom_data = $custom_data->setContentIds(array($fb_product_sku));
		$custom_data = $custom_data->setContentName($fb_product_name);
		$custom_data = $custom_data->setValue($fb_product_price);
		$custom_data = $custom_data->setCurrency(config('global.CURRENCY'));
		*/
		// Set custome Data End

		// Set event and execute Start
		$event = new \FacebookAds\Object\ServerSide\Event;
		$event = $event->setEventName('PageView');
		$event = $event->setEventID($eventID);
		$event = $event->setEventTime(time());
		$event = $event->setEventSourceUrl($sourceURL);
		$event = $event->setUserData($user_data);
		//$event = $event->setCustomData($custom_data);

		$events = array();
		array_push($events, $event);

		$request = new \FacebookAds\Object\ServerSide\EventRequest($pixel_id);
		$request->setEvents($events);
		$response = $request->execute();
		//echo "<pre>";print_r($response);exit;
		// Set event and execute End

		return $fb_page_view_event_cotnent;
	}
}
