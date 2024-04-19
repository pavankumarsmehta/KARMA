<?php

namespace App\Http\Controllers\Traits;

use Session;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Representative;
use App\Models\Customer;
use App\Models\Wishlist;
use App\Models\BottomHtml;
use App\Models\StaticPages;
use DB;
use Cache;
//use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\SentMessage;
//use Illuminate\Support\Facades\Mail;
//use Symfony\Component\Mime\Email;
//use Symfony\Component\Mime\Part\AbstractPart;
use Route;


trait generalTrait
{
	public function getProductRewriteURL($products_id, $product_name = null, $category_id)
	{
		if (empty($products_id))
			return false;

		if (empty(trim($product_name))) {
			$product = Product::select(['product_name'])->where('products_id', '=', (int)$products_id);

			if ($product->count() > 0) {
				$ProductRes = $product->first();
				$product_name = $ProductRes->product_name;
			} else {
				return false;
			}
		}

		$product_name = $this->removeSpecialChars($product_name);

		//$category = Category::select(['category_id'])->where('category_id', '=', (int)$category_id)->where('status', '=', '1');


		/*if(empty($category_id))
		{
			$categoryRes = Category::from('products_category as pcr')
								->select(['pcr.products_id', 'c.category_id'])
								->join('category as c','pcr.category_id', '=', 'c.category_id')
								->where('c.status', '=', '1')
								->where('pcr.products_id', '=', (int)$products_id)
								->orderBy('c.display_position')
								->orderBy('c.category_name')
								->first();


			$category_id = $categoryRes->category_id;
		}*/

		if (empty($product_name)) {
			$category_url = $this->getParentCategoryRewriteURL($category_id);

			return config('const.SITE_URL') . "/" . $category_url . "-pid-" . $products_id . "-" . $category_id . ".html";
		} else {
			return config('const.SITE_URL') . "/" . $product_name . "-pid-" . $products_id . "-" . $category_id . ".html";
		}
	}
	## SendSMTPM1ail
	/*function sendSMTPMail($to, $subject, $emailBody, $from, $fromName = 'HBAsales', $type = 'text/html', $ReplyTo = NULL, $cc = '', $bcc = '', $attment = '')
	{
		$data = array();

		Mail::send([], $data, function ($message) use ($to, $from, $fromName, $cc, $bcc, $ReplyTo, $subject, $attment, $emailBody) {
			$message->to($to)->from($from, $fromName)->replyTo($from)->subject($subject)->setBody($emailBody, 'text/html');
		});

		// Mail::raw($emailBody, function ($message) use ($to, $from, $fromName, $cc, $bcc, $ReplyTo, $subject, $attment) {
		// 	$message->to($to)->from($from, $fromName)->replyTo($from)->subject($subject);
		// });

		if (count(Mail::failures()) > 0) {
			$mailStatus = 'failure';
		} else {
			$mailStatus = 'success';
		}
		return $mailStatus;
	}*/

	function sendSMTPMail($to, $subject, $emailBody, $from, $fromName = 'HBA Store', $type = 'text/html', $ReplyTo = NULL, $cc = '', $bcc = '', $attment = '')
	{
		$data = array();
		
		//dd($to, $subject, $from);
		Mail::send([], $data, function($message) use ($to, $from, $fromName, $cc, $bcc, $ReplyTo, $subject, $attment, $emailBody) {
			//$message->to($to)->from($from, $fromName)->replyTo($from)->subject($subject)->setBody($emailBody, 'text/html');
			$message->to($to)->from($from, $fromName)->replyTo($from)->subject($subject)->html($emailBody)->text('Plain Text');
			
		});
		//dd(Mail::failures());
		
		if (Mail::flushMacros()) {
			// Handle failures
			foreach(Mail::flushMacros() as $emailAddress) {
				// Log or handle each failed email address
				$mailStatus = "Failed";
			}
		} else {
			// Email sent successfully
			$mailStatus = "success";
		}

		/*if (count(Mail::failures()) > 0) {
			$mailStatus = 'failure';
		} else {
			$mailStatus = 'success';
		}*/
		/*if (Mail::send())
        {
            $mailStatus = 'success';
        }
        else
        {
            $mailStatus = 'failure';
        }*/
		return $mailStatus;

	}

	function sendSMTPMail_Normal($to, $subject, $emailBody, $headers)
	{
		$xyz = @mail($to, $subject, $emailBody, $headers);
		if ($xyz == 1) {
			$mailStatus = 'success';
		} else {
			$mailStatus = 'failure';
		}
		return $mailStatus;
	}


	function SendMail($Subject, $EmailBody, $To, $From, $CC = '', $BCC = '')
	{
		//return true;
		// echo 'Body is :<br>' . $EmailBody;
		// exit;
		//$To = "gequaldev@gmail.com";
		$To = "sachin.qualdev@gmail.com";

		$SendMail = $MailSend = Mail::send(array(), array(), function ($message) use ($To, $Subject, $EmailBody, $From, $CC, $BCC) {
			$message->to($To)
				->subject($Subject)
				->setBody($EmailBody, 'text/html');
			if ($CC != '')
				$message->cc($CC);
			if ($BCC != '')
				$message->bcc($BCC);

			$message->cc('sachin.qualdev@gmail.com');
		});
		if (count(Mail::failures()) > 0) {
			$mailStatus = 'fail';
		} else {
			$mailStatus = 'success';
		}
		return $mailStatus;
	}


	function Get_Price_Val($price)
	{
		
		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
			$price['retail_price'] = $price['retail_price']*$curencyvalue;
			$price['our_price'] = $price['our_price']*$curencyvalue;
			
			$price['sale_price'] = $price['sale_price']*$curencyvalue;
		}else{
			$curencySymbol = '';
		}
		$Price_Val = [];
		$current_time = \Carbon\Carbon::now()->toDateTimeString();

		if ($price['sale_price'] > 0)
		{
			$Price_Val['retail_price'] = $price['retail_price'];
			$Price_Val['our_price'] = $price['our_price'];
			$Price_Val['sale_price'] = $price['sale_price'];
			if(!isset($price['deal_description'])){
				
				if(isset($price['on_sale']) && $price['on_sale']=='Yes'){
					$Price_Val['retail_price'] = $Price_Val['our_price'];
					$Price_Val['our_price'] = $Price_Val['sale_price'];
					$price['retail_price'] = $price['our_price'];
					$price['our_price'] = $price['sale_price'];
				}
			}else{
				$Price_Val['retail_price'] = $Price_Val['our_price'];
			}
			$Price_Val['retail_price_disp'] = Make_Price($price['retail_price'], true,false,$curencySymbol);
			$Price_Val['our_price_disp'] = Make_Price($price['our_price'], true,false,$curencySymbol);
			$Price_Val['sale_price_disp'] = Make_Price($price['sale_price'], true,false,$curencySymbol);
		}
		else
		{
			$Price_Val['retail_price'] = $price['retail_price'];
			$Price_Val['our_price'] = $price['our_price'];
			$Price_Val['sale_price'] = 0;
			$Price_Val['retail_price_disp'] = Make_Price($price['retail_price'], true,false,$curencySymbol);
			$Price_Val['our_price_disp'] = Make_Price($price['our_price'], true,false,$curencySymbol);
			$Price_Val['sale_price_disp'] = Make_Price(0, true,false,$curencySymbol);
		}

		return $Price_Val;
	}


	function Get_Price_Val_Obj($price, $dealPrice = "",$convert_currency=true)
	{
	
		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode]) && $convert_currency){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
			$price->retail_price = $price->retail_price*$curencyvalue;
			$price->our_price = $price->our_price*$curencyvalue;
			$price->sale_price = $price->sale_price*$curencyvalue;
			if($dealPrice != ""){
				$dealPrice = $dealPrice * $curencyvalue;
			}
		}else{
			$curencySymbol = '';
		}

	  $Price_Val = [];
	  $current_time = \Carbon\Carbon::now()->toDateTimeString();

	  if($dealPrice == ""){
	    if ($price->sale_price > 0)
	    {
	      $Price_Val['retail_price'] = $price->retail_price;
	      $Price_Val['our_price'] = $price->our_price;
	      $Price_Val['sale_price'] = $price->sale_price;
		  if(!isset($price->deal_description)){
			if(isset($price->on_sale) && $price->on_sale=='Yes'){
				$Price_Val['retail_price'] = $Price_Val['our_price'];
				$Price_Val['our_price'] = $Price_Val['sale_price'];
				$price->retail_price = $price->our_price;
				$price->our_price = $price->sale_price;
			}
			}else{
				$Price_Val['retail_price'] = $Price_Val['our_price'];
			}
	      $Price_Val['retail_price_disp'] = Make_Price($price->retail_price, true,false,$curencySymbol);
	      $Price_Val['our_price_disp'] = Make_Price($price->our_price, true,false,$curencySymbol);
	      $Price_Val['sale_price_disp'] = Make_Price($price->sale_price, true,false,$curencySymbol);
	    }
	    else {
	      $Price_Val['retail_price'] = $price->retail_price;
	      $Price_Val['our_price'] = $price->our_price;
	      $Price_Val['sale_price'] = 0;

	      $Price_Val['retail_price_disp'] = Make_Price($price->retail_price, true,false,$curencySymbol);
	      $Price_Val['our_price_disp'] = Make_Price($price->our_price, true,false,$curencySymbol);
	      $Price_Val['sale_price_disp'] = Make_Price(0, true,false,$curencySymbol);
	    }
	  }else{
	    $Price_Val['retail_price'] = $price->retail_price;
	    $Price_Val['our_price'] = $dealPrice;
	    $Price_Val['sale_price'] = $price->sale_price;
	    $Price_Val['retail_price_disp'] = Make_Price($price->retail_price, true,false,$curencySymbol);
	    $Price_Val['our_price_disp'] = Make_Price($dealPrice, true,false,$curencySymbol);
	    $Price_Val['sale_price_disp'] = Make_Price($price->sale_price, true,false,$curencySymbol);
	  }
	  return $Price_Val;
	}

	public function getCategoryUrlTrait($cat_nm, $cat_id)
	{
		$cat_nm = preg_replace('~ [\\\\/:*?"<>|] ~', '-', strtolower($cat_nm));
		$cat_nm = str_replace(" ", "-", $cat_nm);
		$url = url($cat_nm . '/' . $cat_id);
		return $url;
	}

	public function getColorShapeStyleUrlTrait($name, $id, $url_prefix)
	{
		//$color_name = "Aqua / Fuchsia";
		$name = preg_replace('~ [\\\\/:*?"<>|] ~', '-', strtolower($name));
		$name = str_replace(" ", "-", $name);
		$name = str_replace("/", "-", $name);
		if ($id > 0) {
			$url = url("rugs/" . $url_prefix . '/' . $name . '/' . $id);
		} else {
			$url = url("rugs/" . $url_prefix . '/' . $name);
		}
		return $url;

		//return url($url_prefix.'/'.$name.'/'.$id);
	}

	function getParentCategoryRewriteURL($category_id)
	{
		$new_vcat_name = '';


		$categoryRes = Category::select(['category_id', 'parent_id', 'category_name'])
			->where('category_id', '=', (int)$category_id)
			->where('status', '=', '1')
			->orderBy('category_name')->first();


		if ($categoryRes) {
			$new_iparent_id = $categoryRes->parent_id;
			$new_icat_id 	= $categoryRes->category_id;

			$new_vcat_name = $this->removeSpecialChars(trim($categoryRes->category_name));

			while ($new_iparent_id != 0 && $new_iparent_id != null) {
				$newCategoryRes = Category::select(['category_id', 'parent_id', 'category_name'])
					->where('category_id', '=', (int)$new_iparent_id)
					->where('status', '=', '1')
					->orderBy('category_name')
					->first();

				if (!empty($newCategoryRes)) {
					$new_iparent_id = (!empty($newCategoryRes->parent_id) && $newCategoryRes->parent_id != null ? $newCategoryRes->parent_id : "0");
					$new_icat_id 	= $newCategoryRes->category_id;
					$new_vcat_name = $this->removeSpecialChars(trim($newCategoryRes->category_name)) . "/" . $new_vcat_name;
				}
			}
		}

		return $new_vcat_name;
	}

	function getChildCatIdStr($category_id, $rcnt = 0, $string_catID = null)
	{

		if ($rcnt == 0) {
			$string_catID = '';
		}
		$rcnt = $rcnt + 1;

		$category = Category::select(['category_id'])
			->where('parent_id', '=', (int)$category_id)
			->where('status', '=', '1');

		if ($category->count() > 0) {
			$categoryRes = $category->get();
			foreach ($categoryRes as $category) {
				$temp_id = $category->category_id;

				$string_catID .= "'" . $temp_id . "',";

				$category1 = Category::select(['category_id'])
					->where('parent_id', '=', (int)$temp_id)
					->where('status', '=', '1');

				if ($category1->count() > 0) {
					$string_catID = $this->getChildCatIdStr($temp_id, $rcnt, $string_catID);
				}
			}
		}

		return $string_catID;
	}

	function getCustomerSalesRepresentative($customer_id)
	{

		$sales_rep_id = 0;


		$currentSalesRep = Customer::from('customer as c')
			->select(['c.representative_id'])
			->join('sales_representative as sr', 'sr.representative_id', '=', 'c.representative_id')
			->where('sr.status', '=', '1')
			->where('c.customer_id', '=', $customer_id);

		if ($currentSalesRep->count() > 0) {
			$currentSalesRep = $currentSalesRep->first();
			$sales_rep_id = $currentSalesRep->representative_id;

			if ($sales_rep_id != 0) {
				return $sales_rep_id;
			} else {
				return $this->getRandomSalesRepresentative();
			}
		} else {
			return $this->getRandomSalesRepresentative();
		}
	}

	function getRandomSalesRepresentative()
	{

		$cur_date = date('Y-m-d');

		$SalesRepresentative = Representative::select(['representative_id'])
			->where('inquiry_count_date', '!=', $cur_date)
			->where('status', '=', '1');


		if ($SalesRepresentative->count() > 0) {
			$SalesRepresentativeRes = $SalesRepresentative->get();

			foreach ($SalesRepresentativeRes as $SalesRep) {
				$UpdateData =	[
					'inquiry_count_date'	=> $cur_date,
					'per_day_inquiry_count'	=> 0
				];
				Representative::where('representative_id', '=', $SalesRep->representative_id)->update($UpdateData);
			}
		}

		$SalesRepresentative1 = Representative::select(['representative_id'])
			->where('inquiry_count_date', '!=', $cur_date)
			->where('per_day_inquiry_count', '<', 'per_day_inquiry_count')
			->where('status', '=', '1')
			->orderBy('rank', 'ASC')
			->first();

		if ($SalesRepresentative1) {
			$UpdateData =	['per_day_inquiry_count'	=> DB::raw('per_day_inquiry_count + 1')];
			Representative::where('representative_id', '=', $SalesRepresentative1->representative_id)->update($UpdateData);

			return $SalesRepresentative1->representative_id;
		}



		$SalesRepresentative2 = Representative::select(['representative_id'])
			->where('representative_id', '>', 'last_ins_id')
			->where('status', '=', '1')
			->orderBy('representative_id', 'ASC')
			->first();


		if ($SalesRepresentative2) {
			$UpdateData =	['last_ins_id'	=> $SalesRepresentative2->representative_id];
			Representative::query()->update($UpdateData);

			return $SalesRepresentative2->representative_id;
		} else {
			$SalesRepresentative3 = Representative::select(['representative_id'])
				->where('status', '=', '1')
				->orderBy('representative_id', 'ASC')
				->first();

			if ($$SalesRepresentative3) {

				$UpdateData =	['last_ins_id'	=> $SalesRepresentative2->representative_id];
				Representative::update($UpdateData);

				return $SalesRepresentative3->representative_id;
			}
		}
		return false;
	}

	function checkSalesRepresentativeActive($sales_rep_id)
	{
		$SalesRepresentative = Representative::select(['representative_id'])
			->where('representative_id', '=', $sales_rep_id)
			->where('status', '=', '1')
			->get();

		if ($SalesRepresentative) {
			$sales_rep_id = $sales_rep_id;
		} else {
			$sales_rep_id = $this->getRandomSalesRepresentative();
		}

		return $sales_rep_id;
	}

	function getSalesRepresentativeEmail($sales_rep_id)
	{
		$SalesRepresentative = Representative::select(['email'])
			->where('representative_id', '=', $sales_rep_id)
			->first();

		if ($SalesRepresentative) {
			return $SalesRepresentative->email;
		} else {
			return false;
		}
	}

	function getSalesRepresentativeName($sales_rep_id)
	{

		$SalesRepresentative = Representative::select(['first_name', 'last_name'])
			->where('representative_id', '=', $sales_rep_id)
			->first();

		if ($SalesRepresentative) {
			return $SalesRepresentative->first_name . "&nbsp;" . $SalesRepresentative->last_name;
		} else {
			return false;
		}
	}

	function calculatePrice($price_value, $unit_measure)
	{
		$price_array['sf'] 	= 0;
		$price_array['sy'] 	= 0;
		$price_array['lf'] 	= 0;
		$price_array['pp'] 	= 0;
		$price_array['f'] 	= 0;

		$unit_measure = preg_replace('/\s+/', '', $unit_measure);
		if ((preg_match("/square foot/i", strtolower($unit_measure)) || preg_match("/squarefoot/i", strtolower($unit_measure)) || preg_match("/sf/i", strtolower($unit_measure)) || preg_match("/ft/i", strtolower($unit_measure)) || preg_match("/sqft/i", strtolower($unit_measure))) && !preg_match("/linear/i", strtolower($unit_measure))) {
			$price_array['sf'] = $price_value;
			$price_array['sy'] = round(($price_value * 9), 2);
			$price_array['lf'] =  round(($price_value * 12), 2);
		} else if (preg_match("/yard/i", strtolower($unit_measure)) || preg_match("/sy/i", strtolower($unit_measure)) || preg_match("/yd/i", strtolower($unit_measure)) || preg_match("/sqyd/i", strtolower($unit_measure))) {
			$price_array['sy'] = $price_value;
			$price_array['sf'] = round(($price_value / 9), 2);
			$price_array['lf'] = round(($price_array['sf'] * 12), 2);
		} else if (preg_match("/linear/i", strtolower($unit_measure)) || preg_match("/lf/i", strtolower($unit_measure)) || preg_match("/lft/i", strtolower($unit_measure))) {
			$price_array['lf'] = $price_value;
			$price_array['sf'] = round(($price_value / 12), 2);
			$price_array['sy'] = round(($price_array['sf'] * 9), 2);
		} else if (preg_match("/foot/i", strtolower($unit_measure))) {
			$price_array['f'] = $price_value;
		} else if (preg_match("/each/i", strtolower($unit_measure)) || preg_match("/piece/i", strtolower($unit_measure))) {
			$price_array['pp'] = $price_value;
		} else {
			$price_array['pp'] = $price_value;
		}
		return $price_array;
	}

	function getImageURL($image_name, $image_type)
	{
		if ($image_type == 'THUMB') {
			if (file_exists(config("const.PRD_THUMB_IMG_PATH") . $image_name) && $image_name != '')
				return config("const.PRD_THUMB_IMG_URL") . $image_name;
			else
				return config("const.NO_IMAGE_THUMB");
		} else if ($image_type == 'MEDIUM') {
			if (file_exists(config("const.PRD_MEDIUM_IMG_PATH") . $image_name) && $image_name != '')
				return config("const.PRD_MEDIUM_IMG_URL") . $image_name;
			else
				return config("const.NO_IMAGE_MEDIUM");
		} else if ($image_type == 'LARGE') {
			if (file_exists(config("const.PRD_LARGE_IMG_PATH") . $image_name) && $image_name != '')
				return config("const.PRD_LARGE_IMG_URL") . $image_name;
			else
				return config("const.NO_IMAGE_LARGE");
		} else {
			if (file_exists(config("const.PRD_THUMB_IMG_PATH") . $image_name) && $image_name != '')
				return config("const.PRD_THUMB_IMG_URL") . $image_name;
			else
				return config("const.NO_IMAGE_THUMB");
		}
	}

	function getTopSellerImg($id)
	{

		if ($id == 0 or $id == '') {
			return false;
		}
		$top_seller_res = config('TopSellerImg');

		if (count($top_seller_res) > 0 && file_exists(config('const.POPULAR_CAT_IMAGE_PATH') . $top_seller_res[$id - 1]['top_seller_image']) && $top_seller_res[$id - 1]['top_seller_image'] != "") {
			$img_url = config('const.POPULAR_CAT_IMAGE_URL') . $top_seller_res[$id - 1]['top_seller_image'];
			$img_title = $top_seller_res[$id - 1]['title'];
			$return_ary['IMG_URL'] 		= $img_url;
			$return_ary['IMG_TITLE'] 	= $img_title;
			return $return_ary;
		} else {
			return false;
		}
	}

	function getTopParent($cat_id)
	{
		$categoryRes = Category::select('parent_id')
			->where('status', '=', '1')
			->where('category_id', '=', $cat_id)
			->first();

		if ($categoryRes && $categoryRes->parent_id != '0') {
			return $this->getTopParent($categoryRes->parent_id);
		} else {
			return $cat_id;
		}
	}

	function getChildCatIdArray($category_id, $rcnt = 0, $strcatarray = null)
	{

		if ($rcnt == 0) {
			$string_catID = '';
			$strcatarray = array();
		}
		$rcnt = $rcnt + 1;
		$strcatarray[] = $category_id;

			$getAllCategories = $this->get_all_categories();
			$categoryRes = $getAllCategories->filter(function ($category, $key) use($category_id) {
				if($category->parent_id == $category_id){
					return true;
				}else{
					return false;
				}
			});


		if (count($categoryRes) > 0) {
			foreach ($categoryRes as $category) {
				$temp_id = $category->category_id;
				$strcatarray[] = $temp_id;

				$strcatarray = $this->getChildCatIdArray($temp_id, $rcnt, $strcatarray);
			}
		}

		return $strcatarray;
	}

	function getCategoryNavigationPrdInfo($category_id)
	{
		$parentCatArr = $this->getCategoryTree($category_id);
		$cat_navigation = '';
		$cnt = count($parentCatArr);
		for ($i = $cnt - 1; $i >= 0; $i--) {

			$category = Category::select(['category_id', 'category_name'])
				->where('category_id', '=', (int)$parentCatArr[$i])->first();
			if ($category) {
				if ($i == 0) {
					$cat_navigation .= $category->category_name;
				} else {
					$cat_url = $this->getCategoryRewriteURL($category->category_id);
					$cat_navigation .= "" . $category->category_name . "||";
				}
			}
		}
		$cat_navigation = $cat_navigation;

		return $cat_navigation;
	}

	function getCategoryTree($category_id,  $parentCatArr = null)
	{

		$parentCatArr[] = $category_id;

		$category = Category::select(['category_id', 'parent_id'])
			->where('category_id', '=', (int)$category_id);

		$catCount = $category->count();
		$catRes = $category->first();


		if ($catCount > 0 && $catRes->parent_id != 0) {
			return $this->getCategoryTree($catRes->parent_id, $parentCatArr);
		} else {
			return $parentCatArr;
		}
	}
	public function getCountryBoxArray()
	{
		$CountryRS = DB::table($this->prefix . 'countries')
			->select('countries_name', 'countries_iso_code_2')
			->where('status', '=', '1')
			->orderBy('countries_name', 'asc')
			->get()->toArray();

		//dd($CountryRS);
		## Return US if no country found start
		$ArrTemp = array();
		if (empty($CountryRS)) {
			$ArrTemp = array("US" => "US United States");
			return $ArrTemp;
		}
		## Return US if no country found end

		$ArrTemp 		= array();
		$arrCountryCode = array();
		$arrCountryName = array();

		foreach ($CountryRS as $Country) {
			$arrCountryCode[] = $Country->countries_iso_code_2;
			$arrCountryName[] = $Country->countries_iso_code_2 . " - " . $Country->countries_name;
		}

		$ArrTemp = array_combine($arrCountryCode, $arrCountryName);

		return $ArrTemp;
	}

	public function getStateBoxArray()
	{
		$StateRS = DB::table($this->prefix . 'state')
			->select('name', 'code')
			->where('status', '=', '1')
			->where('countries_id', '=', '223')
			->orderBy('name', 'asc')
			->get()->toArray();


		## Return NY if no state found start
		$ArrTemp = array();

		if (empty($StateRS)) {
			$ArrTemp = array("NY" => "New York");
			//$ArrTemp = array("" => "Select State");
			return $ArrTemp;
		}
		## Return NY if no state found end

		$ArrTemp 	  = array();
		$arrStateCode = array();
		$arrStateName = array();

		foreach ($StateRS as $State) {
			$arrStateCode[] = $State->code;
			$arrStateName[] = $State->name;
		}



		$ArrTemp = array_combine($arrStateCode, $arrStateName);

		return $ArrTemp;
	}
	function getCategoryRewriteURL($category_id)
	{

		$category = Category::select(['category_id', 'parent_id', 'category_name'], 'template_page')
			->where('category_id', '=', (int)$category_id)->first();

		$url_str = '';

		if ($category) {
			$template_file = $category->template_page;

			$category_url = $this->getParentCategoryRewriteURL($category->category_id);

			if ($template_file == "category_list") {
				if ($category->category_id == 90) {
					$url_str = config('const.SITE_URL') . 'carpet-pad';
				} else if ($category->category_id == 76) {
					$url_str = config('const.SITE_URL') . 'sheet-vinyl-flooring';
				} else if ($category->category_id == 69) {
					$url_str = config('const.SITE_URL') . 'hardwood-flooring';
				} else if ($category->category_id == 73) {
					$url_str = config('const.SITE_URL') . 'laminate-flooring';
				} else {
					$url_str = config('const.SITE_URL') . $category_url . "-cid-" . $category->category_id . ".html";
				}
			} elseif ($template_file == "product_list") {
				if ($category->category_id == 136) {
					$url_str = config('const.SITE_URL') . 'carpet/residential/berber-carpet';
				} else if ($category->category_id == 203) {
					$url_str = config('const.SITE_URL') . 'carpet/residential/sisal-carpet';
				} else if ($category->category_id == 246) {
					$url_str = config('const.SITE_URL') . 'carpet/residential/soft-carpet';
				} else if ($category->category_id == 122) {
					$url_str = config('const.SITE_URL') . 'hardwood/engineered/hand-scraped-hardwood';
				} else if ($category->category_id == 303) {
					$url_str = config('const.SITE_URL') . 'carpet/carpet-tile';
				} else if ($category->category_id == 392) {
					$url_str = config('const.SITE_URL') . 'site-page/Coretec.html';
				} else if ($category->category_id == 90) {
					$url_str = config('const.SITE_URL') . 'carpet-pad';
				} // added by HK start
				else if ($category->category_id == 76) {
					$url_str = config('const.SITE_URL') . 'sheet-vinyl-flooring';
				} else if ($category->category_id == 69) {
					$url_str = config('const.SITE_URL') . 'hardwood-flooring';
				} else if ($category->category_id == 73) {
					$url_str = config('const.SITE_URL') . 'laminate-flooring';
				}  // added by HK end
				else {
					$url_str = config('const.SITE_URL') . $category_url . "-pcid-" . $category->category_id . ".html";
				}
			} else {
				if ($category->category_id == 136) {
					$url_str = config('const.SITE_URL') . 'carpet/residential/berber-carpet';
				} else if ($category->category_id == 203) {
					$url_str = config('const.SITE_URL') . 'carpet/residential/sisal-carpet';
				} else if ($category->category_id == 246) {
					$url_str = config('const.SITE_URL') . 'carpet/residential/soft-carpet';
				} else if ($category->category_id == 122) {
					$url_str = config('const.SITE_URL') . 'hardwood/engineered/hand-scraped-hardwood';
				} else if ($category->category_id == 303) {
					$url_str = config('const.SITE_URL') . 'carpet/carpet-tile';
				} else if ($category->category_id == 392) {
					$url_str = config('const.SITE_URL') . 'site-page/Coretec.html';
				} else if ($category->category_id == 90) {
					$url_str = config('const.SITE_URL') . 'carpet-pad';
				} // added by HK start
				else if ($category->category_id == 76) {
					$url_str = config('const.SITE_URL') . 'sheet-vinyl-flooring';
				} else if ($category->category_id == 69) {
					$url_str = config('const.SITE_URL') . 'hardwood-flooring';
				} else if ($category->category_id == 73) {
					$url_str = config('const.SITE_URL') . 'laminate-flooring';
				}  // added by HK end
				else {
					$url_str = config('const.SITE_URL') . $category_url . "-pcid-" . $category->category_id . ".html";
				}
			}
		}
		return $url_str;
	}

	function removeSpecialChars($str)
	{
		$str = preg_replace("/[,^!<>@\/()\"&#$*~`{}'?:;.?%]*/", "", trim($str));
		$str = str_replace("  ", " ", strtolower($str));
		$str = str_replace(" ", "-", strtolower($str));
		$str = str_replace("--", "-", strtolower($str));
		$str = str_replace("--", "-", strtolower($str));
		return $str;
	}
	function getFooter()
	{

		$footerHtml = [];
		$bottomHtmlRes = BottomHtml::get();
		foreach ($bottomHtmlRes as $bottomHtml) {
			$bottomHtml->home_html_text = html_entity_decode(stripcslashes($bottomHtml->home_html_text));
			$bottomHtml->home_html_text = str_replace('{$SITE_URL}', config('const.SITE_URL'), $bottomHtml->home_html_text);
			$bottomHtml->home_html_text = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $bottomHtml->home_html_text);
			$bottomHtml->home_html_text = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $bottomHtml->home_html_text);
			$bottomHtml->home_html_text = str_replace('{$IMG_URL}', config('const.SITE_IMAGES_URL'), $bottomHtml->home_html_text);
			$bottomHtml->home_html_text = str_replace('{$DATE}', date('Y'), $bottomHtml->home_html_text);
			$bottomHtml->home_html_text = str_replace('target="_blank"', '', $bottomHtml->home_html_text);
			//echo "<pre>"; print_r($bottomHtml); exit;

			$footerHtml[$bottomHtml->section] = $bottomHtml->home_html_text;
		}
		return $footerHtml;
	}

	function removeSpaceCharsSeo($str)
	{
		$str = str_replace(" ", "+", strtolower($str));
		return $str;
	}

	function removeSpecialCharInFiberSpecies($str)
	{
		$str = preg_replace("/[^a-zA-Z0-9]/", "-", strtolower(trim($str)));
		$str = preg_replace('/\^+/', '-', $str);
		$str = str_replace("--", "-", strtolower($str));

		if (substr($str, strlen($str) - 1, strlen($str)) == "-")
			$str = substr($str, 0, strlen($str) - 1);
		return $str;
	}
	function getTreeSub($category_id)
	{

		$child_ary = array();
		$categoryRes = Category::select('category_id', 'parent_id', 'category_name', 'menu_image', 'menu_link')
			->where('status', '=', '1')
			->where('parent_id', '=', $category_id)
			->orderBy('display_position')
			->orderBy('category_name')
			->get();

		if (count($categoryRes) > 0) {
			foreach ($categoryRes as $category) {
				$menu_image = '';
				if (!empty($category->menu_image) && is_file(config('const.CAT_IMAGE_PATH') . $category->menu_image) && file_exists(config('const.CAT_IMAGE_PATH') . $category->menu_image))
					$menu_image = config('const.CAT_IMAGE_URL') . 'noimage_menu_cat.jpg'; //$menu_image = config('const.CAT_IMAGE_URL').$category->menu_image;
				$child_ary[] = array(
					'category_id'		=> $category->category_id,
					'category_name'		=> $category->category_name,
					'menu_image'		=> $menu_image,
					'menu_link'	=> $category->menu_link ? $category->menu_link : 'javascript:void(0);',
					'parent_id'			=> $category->parent_id,
					'category_url'		=> $this->getCategoryRewriteURL($category->category_id),
					'child'				=> $this->getTreeSub($category->category_id)
				);
			}
			return $child_ary;
		} else {
			return $child_ary;
		}
	}

	function generateCategories11(array $categories, $parentId = 0, $falg = 0)
	{
		$branch = array();
		foreach ($categories as $category) {

			if ($category['parent_id'] ==  $parentId) {

				$children = $this->generateCategories11($categories, $category['category_id'], $falg++);

				if ($children) {
					$category['children'] = $children;
				}

				if ($category['parent_id'] == 0) {
					$branch[$category['category_id']][] = $category;
				} else {
					$branch[] = $category;
				}
			}
		}
		return $branch;
	}


	public function getRugsLeftfilter($categoryName, $requestType = '')
	{

		$selectedfields = 'hba_products.color_family_id,
		hba_products.color_id,
		hba_products.size_dimension,
		hba_products.size_family,
		hba_products.material2,
		hba_products.room,
		hba_products.collection,
		hba_products.shape_id,
		hba_products.weave,
		hba_products.product_width,
		hba_products.product_height,
		hba_products.product_length,
		hba_products.material as material_id,
		hba_products.style_id';

		$columnType = array('color_id', 'material', 'style_id', 'room', 'shape_id', 'collection', 'size_family');

		// foreach ($columnType as $val) {
		// 	$filterquery = Product::select(DB::raw($selectedfields))
		// 		->where('hba_products.status', '=', '1');

		// 	if ($requestType == 'rugs') {
		// 		$filterquery->where('hba_products.parent_category_id', '=', '1');
		// 	} else {
		// 		$filterquery->where('hba_products.category', 'LIKE', '%' . $categoryName . '%');
		// 	}

		// 	$filterquery->groupBy('hba_products' . $val);


		// 	// $Result[$val] = $filterquery->get();
		// }


		// return $Result;
	}

	function getDisplayRanking($categoryData)
	{
		$displayRanking = array();
		foreach ($categoryData as $key => $value) {
			$displayRanking[$value->display_position] = $value;
			$displayRanking[$value->display_position]->categoryId = (isset(explode('/', $value->category_link)[5]) ? explode('/', $value->category_link)[5] : "");
		}
		ksort($displayRanking);
		return $displayRanking;
	}
	public function ProductSlider($Flag='',$FileFlag)
	{
			$table_prefix = env('DB_PREFIX', '');
			$product_limit = config('const.LIMIT_SHOW_PRODUCT_SLIDER') ;
			$SliderObj = array();
			//$ProductsObj = Product::select('product_id','parent_category_id','category','product_name','sku','product_description','image_name','product_url','our_price','retail_price','on_sale','sale_price')->where('status','=','1');

			$ProductsObj = DB::table($table_prefix.'products as po')
			->join($table_prefix.'products_category as pc','po.product_id','=','pc.products_id')
			->join($table_prefix.'category as c','pc.category_id','=','c.category_id')
		    ->select('po.product_id','po.current_stock','po.parent_category_id','po.category','po.product_name','po.sku','po.product_description','po.image_name','po.product_url','po.our_price','po.retail_price','po.on_sale','po.sale_price')
			->where('po.status','=','1')
			->where('c.status','=','1');

			if($Flag == 'NEW_ARRIVALS'){
				$ProductsObj =  $ProductsObj->where('po.new_arrival','=','Yes');
			}else if($Flag == 'SEOSONAL_SPECIALS'){
				$ProductsObj =  $ProductsObj->where('po.seasonal_specials','=','Yes');
			}

			$SliderProducts = $ProductsObj->orderBy('po.display_rank')->distinct('po.product_id')->take($product_limit)->get()->toArray();

			if(count($SliderProducts) > 0)
		    {
				foreach($SliderProducts as $key => $ProductObj)
				{
					$ProductObj = json_decode(json_encode($ProductObj),true);
					$SliderProducts[$key] = $this->get_whishlist($ProductObj);

					$SliderProducts[$key]['image_url'] = Get_Product_Image_URL($ProductObj['image_name'],'THUMB');

					if(empty($ProductObj['product_url'])){
					 $SliderProducts[$key]['product_url'] = Get_Product_URL($ProductObj['product_id'], $ProductObj['product_name'],'',$ProductObj['parent_category_id'],$ProductObj['category'],$ProductObj['sku'],'');
				    }

					$SliderProducts[$key]['price_arr'] = $this->Get_Price_Val($ProductObj);
			    }
			}
			return $SliderProducts;
	}
	public function MainCategoryList($Flag='',$limit=6)
	{
		$CAT_IMAGE_PATH = config('const.CAT_IMAGE_PATH');
		$CAT_IMAGE_URL = config('const.CAT_IMAGE_URL');


		$categoriesAllObj = $this->get_all_categories();

		$mainCategoryArr =  $categoriesAllObj->filter(function ($category, $key) use($Flag, $CAT_IMAGE_PATH,$CAT_IMAGE_URL) {

			if($category->parent_id == 0 && (!empty($category->thumb_image)) && $Flag=='CategoryPage' && $category->display_on_category=='Yes'){

				if(file_exists($CAT_IMAGE_PATH.$category->thumb_image)){
					$category->thumb_image = $CAT_IMAGE_URL.$category->thumb_image;
				}
				else{
					return false;	
				}
				
				if(empty($category->category_url)){
					$category->category_url=get_category_url($category);
				}

				return $category;
			}
			else if($category->parent_id == 0 && (!empty($category->thumb_image)) && $Flag=='HomePage' && $category->display_on_home=='Yes'){

				if(file_exists($CAT_IMAGE_PATH.$category->thumb_image)){
					$category->thumb_image = $CAT_IMAGE_URL.$category->thumb_image;
				}
				else{
					return false;
				}
				
				if(empty($category->category_url)){
					$category->category_url=get_category_url($category);
				}
				return $category;
			}
			else{
				return false;
			}
		})->toArray();
		if(isset($mainCategoryArr) && !empty($mainCategoryArr)){
			$mainCategoryArr = array_sort($mainCategoryArr, 'display_position', SORT_ASC);
			$mainCategoryArr = array_slice($mainCategoryArr,0,$limit);
		}else{
			$mainCategoryArr = array();
		}
		return $mainCategoryArr;

	}
	function getCategoryBySku($sku){
		$productDetails = Product::select(['category'])->where('sku', '=', $sku)->get();

		$categoryData = explode('#',$productDetails[0]->category);
		$categoryArray = array();
		if(isset($categoryData[0]) && !empty($categoryData[0])){
			$cateExplodeByColon = explode(':',$categoryData[0]);
			$i = 0;
			foreach($cateExplodeByColon as $key => $value){
				if($i==0){
					$categoryArray['item_category'] = $value;
				}else{
					$categoryArray['item_category'.$i] = $value;
				}
				$i++;
			}
		}
		return $categoryArray;
	}

	// public function GetCatTree($CatArray)
	// {
	// 	//$Categories = Category::where('parent_id','=','0')->where('status','=','1')->with('children')->get();
	// 	$Categories = Category::where('status','=','1')->orderBy('display_position')->get();
	// 	$SubCatsTree=[];$key=0;
	// 	$AllCats = $this->MyCatTree($Categories);
	// 	foreach($AllCats as $MainCat)
	// 	{
	// 		if(in_array($MainCat->category_id,$CatArray) || $CatArray[0] == 0)
	// 		{
	// 			$SubCatsTree[$key][]=['category_id' => $MainCat->category_id, 'category_name' => $MainCat->category_name, 'Level' => 0];

	// 			if(isset($MainCat->childs) && count($MainCat->childs) > 0 ){
	// 				foreach($MainCat->childs as $SubLevel1){
	// 					$SubAllCats = isset($SubLevel1->childs)?$SubLevel1->childs:[];
	// 					$SubCatsTree[$key][]=['category_id' => $SubLevel1->category_id, 'category_name' => $SubLevel1->category_name,'hasChild' => ($SubAllCats != null && count($SubAllCats) > 0) ? 'Yes':'No', 'Level' => 1];
	// 					$SubCats[]=['category_id' => $SubLevel1->category_id, 'category_name' => $SubLevel1->category_name];
	// 					if($SubAllCats){
	// 						foreach($SubAllCats as $SubLevel2){
	// 							$SubCatsTree[$key][]=['category_id' => $SubLevel2->category_id, 'category_name' => $SubLevel2->category_name, 'Level' => 2];
	// 							$SubCats[]=['category_id' => $SubLevel2->category_id, 'category_name' => $SubLevel2->category_name];
	// 							$key++;
	// 						}
	// 					}
	// 					$key++;
	// 				}
	// 			}
	// 			$key++;
	// 		}
	// 	}
	// 	return $SubCatsTree;
	// }

	// public function MyCatTree($Cats)
	// {
	// 	$childs = array();
	// 	foreach($Cats as $item){
	// 		$childs[$item->parent_id][] = $item;
	// 		unset($item);
	// 	}
	// 	foreach($Cats as $item){
	// 		if (isset($childs[$item->category_id])){
	// 			$item['childs'] = $childs[$item->category_id];
	// 		}
	// 	}
	// 	return $childs[0];
	// }
	// public function SetFilters($Params)
	// {
	// 	$ExpFilters = explode("/",$Params->filters);
	// 	if(isset($Params->mid) && $Params->mid != '')
	// 		$ExpFilters[]='mid-'.$Params->mid;

	// 	$AllFilters = [];
	// 	$ParamString = ['cid' => 'categories', 'brid' => 'brands','family' => 'fragrance_family', 'type' => 'vtype',
	// 			'formulation' => 'formulation', 'stock' => 'stock', 'size' => 'size',
	// 			'special' => 'special', 'coverage' => 'coverage', 'finish' => 'finish',
	// 			'skin' => 'skin_type', 'features' => 'features'];
	// 	foreach($ExpFilters as $AllParam)
	// 	{
	// 		$ExpParam = explode("-",$AllParam);
	// 		if(count($ExpParam)>0 && array_key_exists($ExpParam[0],$ParamString))
	// 		{
	// 			$Key = $ParamString[$ExpParam[0]];
	// 			$AllFilters[$Key] = explode(',',$ExpParam[1]);
	// 		} else if(count($ExpParam)>0 && $ExpParam[0] == 'key'){
	// 			$AllFilters['key'] = $ExpParam[1];
	// 		} else if(count($ExpParam)>0 && $ExpParam[0] == 'price'){
	// 			$AllFilters['minprice'] = $ExpParam[1];
	// 			$AllFilters['maxprice'] = $ExpParam[2];
	// 		}
	// 	}
	// 	return $AllFilters;
	// }

	function get_whishlist($Product){
		$Product['PrdWishArr']  = array();
		if(Session::has('customer_id') && !empty($Product)){
			$PrdWishArr = $this->get_all_wishlist();
			if(isset($PrdWishArr[$Product['product_id']]) && !empty($PrdWishArr[$Product['product_id']])){
				$PrdWishRes = $PrdWishArr[$Product['product_id']];
				if(!empty($PrdWishRes)){
					$Product['PrdWishArr'] = $PrdWishRes;
				}
			}else{
				$Product['PrdWishArr'] = array();
			}
		}
		return $Product;
	}
	function get_all_wishlist(){
		$cacheName = 'getAllWishlist_'.Session::get('customer_id').'_cache';
		if (Cache::has($cacheName)) {
			$getAllWishlistArr = Cache::get($cacheName);
			Session::put('wishList.totalQty', count($getAllWishlistArr));
			return $getAllWishlistArr;
		} else {
			$PrdWishArr = array();
			if (Session::has('customer_id')) {
					$PrdWishArr1 = Wishlist::select('customer_id','products_id','sku')->whereCustomerId(Session::get('customer_id'))->get();
					Session::put('wishList.totalQty', count($PrdWishArr1));
					$PrdWishArr = $PrdWishArr1->mapWithKeys(function ($item, $key) {
						return [$item['products_id'] => $item];
					})->toarray();
					
						Cache::put($cacheName, $PrdWishArr);
					
			}
			return $PrdWishArr;

		}
	}

	function addRemoveProductToWishListArray($productId, $newWishArray, $categoryId, $brandId){
		if(Session::get('ShoppingCart.Cart') != null)
		{
			$Cart = Session::get('ShoppingCart.Cart');
			$updatedCart = array_map(function ($item) use ($productId, $newWishArray) {
				if(isset($item['product_id'])){
					if($item['product_id'] === $productId) {
						$item['is_wish'] = empty($newWishArray) ? false : true;
					}
				}
				return $item;
			}, $Cart);
			Session::forget('ShoppingCart.Cart');
			Session::put('ShoppingCart.Cart', $updatedCart);
		}

		$brandCacheName = 'homePopularBrands_cache';
		$newArrivalCacheName = 'listingnewarriaval_cache';
		$seasonSpecialCacheName = 'listingseasonspecial_cache';
		$saleCacheName = 'listingsale_cache';
		$dealOfWeekCacheName = 'listingdealofweek_cache';
		$homeNewArrivalCacheName = 'homenewarrivals_cache';
		$homeSeasonalSpecialCacheName = 'homeseasonalSpecials_cache';
		$categoryCacheName = 'getAllCategories_cache';
		if (Cache::has($brandCacheName)) {
			$brandArr = Cache::get('homePopularBrands_cache');
			$brandArrResult = $this->wishListArrayMaping($brandArr, $productId, $newWishArray);
			Cache::forget('homePopularBrands_cache');
			Cache::put('homePopularBrands_cache', $brandArrResult);
	    }

	    if (Cache::has($newArrivalCacheName)) {
	    	$newArrivalArr = Cache::get('listingnewarriaval_cache');
			$newArrivalArrResult = $this->wishListArrayMaping($newArrivalArr, $productId, $newWishArray);
			Cache::forget('listingnewarriaval_cache');
			Cache::put('listingnewarriaval_cache', $newArrivalArrResult);
		}

		if (Cache::has($seasonSpecialCacheName)) {
			$seasonSpecialArr = Cache::get('listingseasonspecial_cache');
			$seasonSpecialArrResult = $this->wishListArrayMaping($seasonSpecialArr, $productId, $newWishArray);
			Cache::forget('listingseasonspecial_cache');
			Cache::put('listingseasonspecial_cache', $seasonSpecialArrResult);
	    }

	    if (Cache::has($saleCacheName)) {
	    	$saleArr = Cache::get('listingsale_cache');
			$saleArrResult = $this->wishListArrayMaping($saleArr, $productId, $newWishArray);
			Cache::forget('listingsale_cache');
			Cache::put('listingsale_cache', $saleArrResult);
		}

		if (Cache::has($dealOfWeekCacheName)) {
			$dealOfWeekArr = Cache::get('listingdealofweek_cache');
			$dealOfWeekArrResult = $this->wishListArrayMaping($dealOfWeekArr, $productId, $newWishArray);
			Cache::forget('listingdealofweek_cache');
			Cache::put('listingdealofweek_cache', $dealOfWeekArrResult);
		}

		if (Cache::has($homeNewArrivalCacheName)) {
	    	$homeNewArrivalArr = Cache::get('homenewarrivals_cache');
			$homeNewArrivalArrResult = $this->wishListArrayMaping($homeNewArrivalArr, $productId, $newWishArray);
			Cache::forget('homenewarrivals_cache');
			Cache::put('homenewarrivals_cache', $homeNewArrivalArrResult);
	    }

	    if (Cache::has($homeSeasonalSpecialCacheName)) {
	    	$homeSeasonalSpecialArr = Cache::get('homeseasonalSpecials_cache');
			$homeSeasonalSpecialArrResult = $this->wishListArrayMaping($homeSeasonalSpecialArr, $productId, $newWishArray);
			Cache::forget('homeseasonalSpecials_cache');
			Cache::put('homeseasonalSpecials_cache', $homeSeasonalSpecialArrResult);
	    }

	    if(isset($brandId)){
	    	//
	    	$listingBrandCacheName = 'listingbrand_'.$brandId.'_cache';
	    	if (Cache::has($listingBrandCacheName)) {
		    	$listingBrandArr = Cache::get($listingBrandCacheName);
				$listingBrandArrResult = $this->wishListArrayMaping($listingBrandArr, $productId, $newWishArray);
				Cache::forget($listingBrandCacheName);
				Cache::put($listingBrandCacheName, $listingBrandArrResult);
	        }
	    }

	    if(isset($categoryId)) {
	    	$listingCategoryCacheName = 'listingcategory_'.$categoryId.'_cache';
	    	if (Cache::has($listingCategoryCacheName)) {
		    	$listingCategoryArr = Cache::get($listingCategoryCacheName);
				$listingCategoryArrResult = $this->wishListArrayMaping($listingCategoryArr, $productId, $newWishArray);
				Cache::forget($listingCategoryCacheName);
				Cache::put($listingCategoryCacheName, $listingCategoryArrResult);
		    }
	    }
	}

	function wishListArrayMaping($productArray, $productId, $newWishArray) {
		$response = array_map(function ($item) use ($productId, $newWishArray) {
		    if (isset($item['product'])) {
		        $item['product'] = array_map(function ($product) use ($productId, $newWishArray) {
		            if ($product['product_id'] == $productId) {
		            	if((isset($product['PrdWishArr']) && !empty($product['PrdWishArr']))){
		            		if ($product['PrdWishArr']['products_id'] == $productId) {
		            			$product['PrdWishArr'] = [];
		            		}
		            	} else {
		            		$product['PrdWishArr'] = $newWishArray;
		            	}
		            }
		            return $product;
		        }, $item['product']);
		    } else {
		    	$item = json_decode(json_encode($item), true);
		    	if(is_array($item)){
		    		 $item = array_map(function ($product) use ($productId, $newWishArray) {
 		    		 if(isset($product['product_id']) && !empty($product['product_id'])){
			    		if ($product['product_id'] == $productId) {
			            	if((isset($product['PrdWishArr']) && !empty($product['PrdWishArr']))){
			            		if ($product['PrdWishArr']['products_id'] == $productId) {
			            			$product['PrdWishArr'] = [];
			            		}
			            	} else {
			            		$product['PrdWishArr'] = $newWishArray;
			            }
			        }
	    		}
		            return $product;
		        }, $item);
		    	}
		    	$item = json_decode(json_encode($item), true);
		    }
		    return $item;
		}, $productArray);
		return $response;
	}
	## Clear section wise cache on Add/Remove wishlist event
	function removeWishListSectionCache($sectionName) {
		// dd($sectionName);

	}


	function get_all_categories(){
		$prefix = config('const.DB_TABLE_PREFIX');
		$cacheName = 'getAllCategories_cache';
		if (Cache::has($cacheName)) {
			return $Categories = Cache::get($cacheName);
		} else {
			//$Categories = Category::where('status','=','1')->orderBy('display_position')->get();
			  $Categories = DB::table($prefix.'category as c')
			  ->where('c.status', '=', '1')
			  ->orderBy('c.display_position')->get();
			// dd($Categories);
			//$Categories  = array();
			if(!empty($Categories)){
				Cache::put($cacheName, $Categories);
			}
			return $Categories;
		}
	}
	function get_all_brands(){
		$cacheName = 'getAllBrands_cache';
		if (Cache::has($cacheName)) {
			return $brandArr = Cache::get($cacheName);
		} else {
			$bransCollection =  Brand::where('status','=','1')->get();
			$brandArr = $bransCollection->mapWithKeys(function ($brand, $key) {
				return [$brand['brand_id'] => $brand];
			})->toarray();
			if(!empty($brandArr)){
				Cache::put($cacheName, $brandArr);
			}
			return $brandArr;
		}
	}
	function get_all_productWithCategory(){
		$table_prefix = config('const.DB_TABLE_PREFIX');
		$cacheName = 'getAllProductWithCategory_cache';
		// if (Cache::has($cacheName)) {

		// 	return $productResult = Cache::get($cacheName);
		// } else {
			$productResult = DB::table($table_prefix.'products as p')
			->join($table_prefix.'products_category as pc','p.product_id','=','pc.products_id')
			->join($table_prefix.'category as c','pc.category_id','=','c.category_id')
			->select('p.attribute_1','p.attribute_2','p.attribute_3','p.pack_size','p.flavour','p.stock','p.current_stock','p.short_description','p.brand','p.product_weight','p.metric_size','p.ingredients_pdf','p.ingredients','p.gender','p.product_availability','p.manufacturer','p.brand','p.status','p.meta_description','p.meta_keyword','p.meta_title','p.display_rank','p.badge','p.video_url','p.extra_images','p.image_name','p.shipping_text','p.on_sale','p.sale_price','p.our_price','p.retail_price','p.size','p.product_description','p.product_description as description','p.brand_id','p.product_url','p.product_name','p.parent_category_id','p.product_type','p.color','p.related_sku','p.parent_sku','p.product_group_code','p.sku','p.product_id','c.category_id','c.category_name','c.url_name','c.display_on_category','c.status as category_status','c.category_description','c.display_on_other_category','c.parent_id')->get();
			
			// $productResult = Product::from($table_prefix.'products as p')
			// 	->select('p.*','c.category_name','c.category_id','c.status as category_status')
			// 	->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
			// 	->join('hba_category as c', 'pc.category_id', '=', 'c.category_id')
			// 	->get();
			// if(!empty($productResult)){
			// 	Cache::put($cacheName, $productResult);
			// }
			return $productResult;
		//}
	}

	function product_listing_page_limit($page_limit = ''){
		/*if(isset($page_limit) && !empty($page_limit)){
			Session::put('sess_product_listing_page_limit', $page_limit);
			$PAGE_LIMIT = $page_limit;
			return $PAGE_LIMIT;
		}else{
			if(Session::has('sess_product_listing_page_limit')){
				$PAGE_LIMIT = Session::get('sess_product_listing_page_limit');
				return  $PAGE_LIMIT;
			}else{
				$PAGE_LIMIT = '48';
				if(config('const.PRODUCT_LISTING_PAGE_LIMIT') > 0 )
				{
					$PAGE_LIMIT = config('const.PRODUCT_LISTING_PAGE_LIMIT');
				}
				Session::put('sess_product_listing_page_limit', $PAGE_LIMIT);
				return  $PAGE_LIMIT;
			}
		}*/
		if(isset($page_limit) && !empty($page_limit)){
			//Session::put('sess_product_listing_page_limit', $page_limit);
			$PAGE_LIMIT = $page_limit;
			return $PAGE_LIMIT;
		}else{
			$PAGE_LIMIT = '48';
			if(config('const.PRODUCT_LISTING_PAGE_LIMIT') > 0 )
			{
				$PAGE_LIMIT = config('const.PRODUCT_LISTING_PAGE_LIMIT');
			}
			return  $PAGE_LIMIT;
		}
	}
	function search_brand_category_ids($brand_id = '',$category_id = ''){
		if(!empty($brand_id) || !empty($category_id)){
			$brandObj = getAllBrand(); 
			if(isset($brandObj) && !empty($brandObj)){
				$filterBrandArr = $brandObj->filter(function ($brand, $key) use($brand_id,$category_id) {
						if(!empty($brand_id)){
							if($brand->brand_id == $brand_id){
								return true;
							}else{
								return false;
							}
						}else if(!empty($category_id)){
							if($brand->category_id == $category_id){
								return true;
							}else{
								return false;
							}
						}else{
							return false;
						}
						
				});
				if(!empty($brand_id)){
					$brandArr = $filterBrandArr->unique('category_id')->toarray();
					$brandArr = json_decode(json_encode($brandArr),true);
					return array_values($brandArr);

				}else if(!empty($category_id)){
					$brandArr = $filterBrandArr->unique('brand_id')->toarray();
					$brandArr = json_decode(json_encode($brandArr),true);
					return array_values($brandArr);
				}else{
					return [];
				}
			}else{
				return [];
			}
		}
	}	


}
