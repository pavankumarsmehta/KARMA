<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ProjectNotes;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Session;
use DateTime;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\productTrait;
use App\Models\Wishlist;
use App\Models\WishlistCategory;
use App\Models\CustomerPhoto;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use File;
use Cache;
use Psy\Util\Json;
use App\Models\OrderDetail;
use App\Models\Order;

class PopupController extends Controller
{
    use generalTrait;
    use productTrait;
    public $PageData;

   
    public function EmailFriend(Request $request)
    {
        if ($request->ajax()) {

            if (isset($request['isAction'])) {

                $validatedData = $request->validate([
                    'friend_email1' => 'required|email',
                    'your_email' => 'required|email',
                    'g-recaptcha-response' => 'required|captcha'
                ], [
                    'fmail1.required' => config('message.Validate.ValidEmail'),
                    'fmail1.email' => config('message.Login.ValidEmail'),
                    'your_email.required' => config('message.Validate.ValidEmail'),
                    'your_email.email' => config('message.Login.ValidEmail'),
                    'g-recaptcha-response.required' => config('fmessages.Validate.GRecaptchaResponse')
                ]);


                $friend_email1 = $request['friend_email1'];
                $friend_email2 = $request['friend_email2'];
                $your_email = $request['your_email'];
                $your_name = $request['your_name'];

                $message  = stripslashes(nl2br(strtr($request['message'], array('\r' => chr(13), '\n' => chr(10)))));
                $message  = str_replace("<br />", "", strip_tags($message));

                $arr_toemail   = array($friend_email1, $friend_email2);

                //if (isset($request['isAction']) && $request['isAction'] == 'TellAFriend') {
                $products_id     = (int)$request->products_id;

                ## get product info here ##
                $Product = Product::where('product_id', '=', $products_id)
                    ->first();

                $product_name         = $Product->product_name;
                $short_desc         = $Product->product_description;

				$url = config('const.SITE_URL');
                if ($Product->product_url) {
                    $product_page_link  = $url.'/'.$Product->product_url;
                } else {
                    $product_page_link = '';
                }
                if ($Product->sale_price > 0)
                    $arr_price = '$' . $Product->sale_price;
                else
                    $arr_price = '$' . $Product->our_price;


                /*$mainimage = array(
                    'main_image_zoom' => $Product->main_image, 'main_image_thumb' => $Product->main_image_thumb, 'main_image_small' => $Product->main_image_small,
                    'main_image_large' => $Product->main_image_large
                );*/

                $medium_image = Get_Product_Image_URL($Product->image_name, 'MEDIUM');
                // $medium_image = "";
                // if (isset($swatch_image1['main_image_thumb'])) {
                //     $medium_image = $swatch_image1['main_image_thumb'];
                // }


                //$medium_image = $this->getImageURL($Product->main_image,'THUMB');

                //$medium_image = '<img src="'.$medium_image.'" border="0" alt="" />';

                //$product_page_link = $this->getProductRewriteURL($products_id,'','',$Product->product_url);

                ####### Set Mail Here=========
                $Template           = GetMailTemplate('SEND_TO_FRIEND');
                //$medium_image = '';

                $EmailSubject = stripslashes($Template[0]->subject);
                $EmailBody = stripslashes($Template[0]->mail_body);
                $EmailBody = str_replace('{$your_email}', $your_email, $EmailBody);
                $EmailBody = str_replace('{$message}', $message, $EmailBody);
                $EmailBody = str_replace('{$medium_image}', $medium_image, $EmailBody);
                $EmailBody = str_replace('{$product_name}', $product_name, $EmailBody);
                $EmailBody = str_replace('{$short_desc}', $short_desc, $EmailBody);
                $EmailBody = str_replace('{$sale_price}', $arr_price, $EmailBody);
                $EmailBody = str_replace('{$product_page_link}', $product_page_link, $EmailBody);
                $EmailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $EmailBody);
                $EmailBody = str_replace('{$SITE_URL}', config('const.SITE_URL'), $EmailBody);
                $EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
                $EmailBody = str_replace('{$ADMIN_MAIL}', config('Settings.ADMIN_MAIL'), $EmailBody);
                $EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);

                $EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
                //print_r($product_page_link);print_r($medium_image);
                // echo "<pre>";
                // print_r($EmailBody);
                // exit;

                //}
                //echo "<pre>"; print_r($EmailBody); exit;
                for ($i = 0; $i < count($arr_toemail); $i++) {
                    if (isset($arr_toemail[$i]) && !empty($arr_toemail[$i])) {

                        $To = $arr_toemail[$i];
                        $Subject = stripslashes($Template[0]->subject);
                        // $To = 'gequaldev@gmail.com';
                        // $EmailBody1 = str_replace('{$UnSubscribeEmail}', $To, $EmailBody);
                        // //$From = config('Settings.ADMIN_MAIL');						
                        // $From = 'qqualdev@gmail.com';
                        //SendMail($EmailSubject, $EmailBody1, $To, $From);
                        //$From = 'cs@hba.com';
                        $From = config('Settings.CONTACT_MAIL');

                        $sendMailStatus = $this->sendSMTPMail($To, $Subject, $EmailBody, $From);
                        $sendMailStatus = $this->sendSMTPMail('sachin.qualdev@gmail.com', $Subject, $EmailBody, $From);
                        /*$headers  = "From: " . strip_tags($From) . "\r\n";
                        $headers .= "Reply-To: " . strip_tags($From) . "\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                        $sendMailStatus = $this->sendSMTPMail_Normal('sachin.qualdev@gmail.com', $Subject, $EmailBody, $headers);
                        $sendMailStatus = $this->sendSMTPMail_Normal('ankit.qualdev@gmail.com', $Subject, $EmailBody, $headers);*/
                        $response['message'] = "Mail has been sent successfully to " . $arr_toemail[$i]; // Mail has been sent successfully
                        $response['action'] = true;
                    }
                }

                return response()->json($response);
            }


            $this->PageData['emailafriend'] = 'emailafriend';

            $emailname = $request->emailname;
            $products_id = $request->productId;
            return view('popup.email-a-friend-popup', compact('emailname', 'products_id'))->with($this->PageData);
        }
    }

    public function quickViewPopup(Request $request)
    {
        try { 
            if ($request->ajax()) { 
            $products_id = $request->products_id;
            if(empty($products_id)){
            
                return abort(404);	
            }
            
            $ProductsObj = $this->get_all_productWithCategory();
            $status_val = 1;
            
            $ProductResult = $ProductsObj->filter(function ($product, $key) use($products_id,$status_val) {
			
                if($product->product_id == $products_id && $product->status==$status_val){
                    return true;
                }else{
                    return false;
                }
            })->unique('product_id');
            $ProductsArr = json_decode(json_encode($ProductResult), true);
			$ProductsArr = array_values($ProductsArr);
            
            if(isset($ProductsObj) && !empty($ProductsObj)){
                $Product = $ProductsArr[0];

                
                if(isset($Product['shipping_text']) && $Product['shipping_text'] != "")
                {
                    $Product['shipping_text'] = $Product['shipping_text'];
                }
                else
                {
                    $today = date('Y-m-d');
                    $tomorrow_date = date('M. d', strtotime($today . ' + 1 days'));
                    $future_date = date('M. d', strtotime($today . ' + 7 days'));
                    //echo $future_date; exit;
                    $shipping_text = "Get it as soon as ".$tomorrow_date." - ".$future_date."";
                    $Product['shipping_text'] = $shipping_text;
                }
                
                
                $arr_extra_image = array();
                $product_group_code = $Product['product_group_code'];
                $Product['product_zoom_image'] = Get_Product_Image_URL($Product['image_name'], 'ZOOM');
                $Product['product_medium_image'] = Get_Product_Image_URL($Product['image_name'], 'MEDIUM');
                $Product['product_large_image'] = Get_Product_Image_URL($Product['image_name'], 'LARGE');
                $Product['product_thumb_image'] = Get_Product_Image_URL($Product['image_name'], 'THUMB');
                $Product['product_small_image'] = Get_Product_Image_URL($Product['image_name'], 'SMALL');
                if (trim($Product['product_large_image']) != "") {
                    $arr_extra_image[] = array(
                        'extra_image_name' => $Product['product_large_image'],
                        'extra_zoom_url' => $Product['product_zoom_image'],
                        'extra_large_url' => $Product['product_large_image'],
                        'extra_medium_url' => $Product['product_medium_image'],
                        'extra_thumb_url' => $Product['product_thumb_image'],
                        'extra_small_url' => $Product['product_small_image'],
                        'video_image_type' => 'image',
                    );
                }
                $extra_images = array();
                if (trim($Product['extra_images']) != '') {
                    $extra_images = explode('#', $Product['extra_images']);

                    if (count($extra_images) > 0) {
                        for ($k = 0; $k < count($extra_images); $k++) {
                            $extra_zoom_url = Get_Product_Image_URL($extra_images[$k], 'ZOOM');
                            $extra_large_url = Get_Product_Image_URL($extra_images[$k], 'LARGE');
                            $extra_medium_url = Get_Product_Image_URL($extra_images[$k], 'MEDIUM');
                            $extra_thumb_url = Get_Product_Image_URL($extra_images[$k], 'THUMB');
                            $extra_small_url = Get_Product_Image_URL($extra_images[$k], 'SMALL');

                            if (!preg_match("/noimage/i", $extra_thumb_url) or !preg_match("/noimage/i", $extra_large_url)) {
                                $arr_extra_image[] = array(
                                    'extra_image_name' => $extra_images[$k],
                                    'extra_zoom_url' => $extra_zoom_url,
                                    'extra_large_url' => $extra_large_url,
                                    'extra_medium_url' => $extra_medium_url, //show large image
                                    'extra_thumb_url' => $extra_thumb_url,
                                    'extra_small_url' => $extra_small_url,
                                    'video_image_type' => "image",
                                );
                            }

                        }
                    }
                }

                if ($Product['video_url'] != "") 
                {
                    if (strpos($Product['video_url'], 'you') !== false){
                            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $Product['video_url'], $matches);
                            if (isset($matches[1]) && strlen($matches[1]) > 0) {
                                $arr_extra_image[] = array(
                                    'extra_image_name' => $matches[1],
                                    'extra_zoom_url' => $matches[1],
                                    'extra_large_url' => $matches[1],
                                    'extra_medium_url' => $matches[1],
                                    'extra_thumb_url' => '<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
                                        <use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
                                    </svg>',
                                    'video_image_type' => 'mp4',
                                    'extra_small_url' => $matches[1],
                                );

                            }
                    } else {
                        $arr_extra_image[] = array(
                            'extra_image_name' => $Product['video_url'],
                            'extra_zoom_url' => $Product['video_url'],
                            'extra_large_url' => $Product['video_url'],
                            'extra_medium_url' => $Product['video_url'],
                            'extra_small_url' => $Product['video_url'],
                            'extra_thumb_url' => '<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
                                <use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
                            </svg>',
                            'video_image_type' => 'mp4',
                        );
                    }
                }
                $allDealOFWeekArr = get_deal_of_week_by_sku();
                if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
                    
                    if(isset($allDealOFWeekArr[$Product['sku']]) && !empty($allDealOFWeekArr[$Product['sku']])){
                        $Product['our_price'] = $allDealOFWeekArr[$Product['sku']]->deal_price;
                        $Product['sale_price'] = $allDealOFWeekArr[$Product['sku']]->deal_price;
                        $Product['deal_description'] = $allDealOFWeekArr[$Product['sku']]->description;
                    }	
                }
                if(empty($Product['product_url'])){
                    $Product['product_url'] = Get_Product_URL($Product['product_id'], $Product['product_name'],'',$Product['parent_category_id'],$Product['category'],$Product['sku'],'');
                }
        
                $ingredients_pdf = '';
                if (isset($Product->ingredients_pdf) && file_exists(config('const.PDF_PATH').$Product->ingredients_pdf))
                {
        
                    $ingredients_pdf =  stripslashes(config('const.PDF_URL').$Product['ingredients_pdf']);
                }
                $Product['ingredients_pdf'] = $ingredients_pdf;

                if(isset($flagShowHideVarinat) && !empty($flagShowHideVarinat)){
                    if($flagShowHideVarinat=='true'){
                        $checkShowMoreDynamicClass = 'showhide-variant-box-js hidden-lg-down';
                        $flagShowHideVarinat  = 'showhide-variant-box-js hidden-lg-down';
                    }elseif($flagShowHideVarinat=='false'){
                        $checkShowMoreDynamicClass = 'showhide-variant-box-js';
                        $flagShowHideVarinat  = '';
                    }else{
                        $checkShowMoreDynamicClass = 'showhide-variant-box-js hidden-lg-down';
                        $flagShowHideVarinat  = 'showhide-variant-box-js hidden-lg-down';
                    }
                }else{
                    $checkShowMoreDynamicClass = 'showhide-variant-box-js hidden-lg-down';
                    $flagShowHideVarinat  = 'showhide-variant-box-js hidden-lg-down';
                }
                $getAllCurrencyObj = getCurrencyArray();
                $currentSelectedCurrencyCode = Session::get('currency_code');
                if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
                    $getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
                    $curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
                    $curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
                }else{
                    $curencySymbol = '$';
                }
                $productPrice = $this->Get_Price_Val($Product);
		
                $Product['retail_price'] = $productPrice['retail_price'];
                $Product['our_price'] = $productPrice['our_price'];
                $Product['sale_price'] = $productPrice['sale_price'];
                $Product['retail_price_disp'] = $productPrice['retail_price_disp'];
                $Product['our_price_disp'] = $productPrice['our_price_disp'];
                $Product['sale_price_disp'] = $productPrice['sale_price_disp'];


                $PrdWishRes = $this->get_all_wishlist();
                if(isset($PrdWishRes[$Product['product_id']]) && !empty($PrdWishRes[$Product['product_id']])){
                    $PrdWishRes = $PrdWishRes[$Product['product_id']];
                }

                $product_size = $Product['size'];
                $product_pack_size = $Product['pack_size'];
                $product_flavour = $Product['flavour'];

                $PackSizeWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$product_size) {
                    if($product->pack_size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1  && strtolower($product->size) == strtolower($product_size)){
                        return true;
                    }else{	
                        return false;
                    }
                })->unique('pack_size');

                $SizeWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$product_flavour) {
                    if($product->size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1 && strtolower($product->flavour) == strtolower($product_flavour)){
                        return true;
                    }else{
                        return false;
                    }
                })->unique('size');
                
                $SizeWiseProductsSelected = json_decode(json_encode($SizeWiseProductsSelected), true);
                $SizeWiseProductsSelected = array_values($SizeWiseProductsSelected);
                $SizeWiseProductsSelected = uniqueArray($SizeWiseProductsSelected,'size');
                $SizeWiseProductsSelected = array_sort($SizeWiseProductsSelected, 'display_rank', SORT_ASC);

                $SizeWiseProductsSelected_arr = [];
                foreach ($SizeWiseProductsSelected as $key => $SizeWiseProduct)
                {
                    $SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['product_id'] = $SizeWiseProduct['product_id'];
                    $SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['product_name'] = $SizeWiseProduct['product_name'];
                    $SizeWiseProductsSelected_arr[strtolower(title($SizeWiseProduct['size']))]['pack_size'] = $SizeWiseProduct['size'];
                }
            
                //dd($SizeWiseProductsSelected_arr);
                $SizeWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
                    if($product->size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
                        return true;
                    }else{
                        return false;
                    }
                })->unique('size');
                $SizeWiseProducts = json_decode(json_encode($SizeWiseProducts), true);
                $SizeWiseProducts = array_values($SizeWiseProducts);
                $SizeWiseProducts = uniqueArray($SizeWiseProducts,'size');
                $SizeWiseProducts = array_sort($SizeWiseProducts, 'display_rank', SORT_ASC);
                

                $SizeWiseProducts_arr = [];
                foreach ($SizeWiseProducts as $key => $SizeWiseProduct)
                {
                    $SizeWiseProducts_arr[$key]['product_id'] = $SizeWiseProduct['product_id'];
                    $SizeWiseProducts_arr[$key]['sku'] = $SizeWiseProduct['sku'];
                    $SizeWiseProducts_arr[$key]['product_name'] = $SizeWiseProduct['product_name'];
                    $SizeWiseProducts_arr[$key]['product_url'] = $SizeWiseProduct['product_url'];
                    $SizeWiseProducts_arr[$key]['size'] = $SizeWiseProduct['size'];
                    $SizeWiseProducts_arr[$key]['product_description'] = $SizeWiseProduct['product_description'];
                    $SizeWiseProducts_arr[$key]['category_id'] = $SizeWiseProduct['category_id'];
                    $SizeWiseProducts_arr[$key]['retail_price'] = $SizeWiseProduct['retail_price'];
                    $SizeWiseProducts_arr[$key]['our_price'] = $SizeWiseProduct['our_price'];
                    $SizeWiseProducts_arr[$key]['sale_price'] = $SizeWiseProduct['sale_price'];
                    $SizeWiseProducts_arr[$key]['image_name'] = $SizeWiseProduct['image_name'];
                    $SizeWiseProducts_arr[$key]['extra_images'] = $SizeWiseProduct['extra_images'];

                    $SizeWiseProductPrice = $this->Get_Price_Val($SizeWiseProduct);

                    $SizeWiseProducts_arr[$key]['retail_price'] = $SizeWiseProductPrice['retail_price'];
                    $SizeWiseProducts_arr[$key]['our_price'] = $SizeWiseProductPrice['our_price'];
                    $SizeWiseProducts_arr[$key]['sale_price'] = $SizeWiseProductPrice['sale_price'];
                    $SizeWiseProducts_arr[$key]['retail_price_disp'] = $SizeWiseProductPrice['retail_price_disp'];
                    $SizeWiseProducts_arr[$key]['our_price_disp'] = $SizeWiseProductPrice['our_price_disp'];
                    $SizeWiseProducts_arr[$key]['sale_price_disp'] = $SizeWiseProductPrice['sale_price_disp'];

                    //if(isset($SizeWiseProducts_arr[$key]['image_name']) && $SizeWiseProducts_arr[$key]['image_name'] != "")
                    //{

                    $SizeWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'ZOOM');
                    $SizeWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'LARGE');
                    $SizeWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'MEDIUM');
                    $SizeWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'THUMB');
                    $SizeWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($SizeWiseProducts_arr[$key]['image_name'], 'SMALL');
                    //}
                }
                

                $PackSizeWiseProductsSelected = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val,$product_flavour,$product_size) {
                    if($product->pack_size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1  && strtolower($product->size) == strtolower($product_size) && strtolower($product->flavour) == strtolower($product_flavour)){
                        return true;
                    }else{	
                        return false;
                    }
                })->unique('pack_size');
                
                $PackSizeWiseProductsSelected = json_decode(json_encode($PackSizeWiseProductsSelected), true);
                $PackSizeWiseProductsSelected = array_values($PackSizeWiseProductsSelected);
                $PackSizeWiseProductsSelected = uniqueArray($PackSizeWiseProductsSelected,'pack_size');
                $PackSizeWiseProductsSelected = array_sort($PackSizeWiseProductsSelected, 'display_rank', SORT_ASC);

                $PackWiseProductsSelected_arr = [];
                foreach ($PackSizeWiseProductsSelected as $key => $PackSizeWiseProduct)
                {
                    $PackWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['product_id'] = $PackSizeWiseProduct['product_id'];
                    $PackWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['product_name'] = $PackSizeWiseProduct['product_name'];
                    $PackWiseProductsSelected_arr[strtolower(title($PackSizeWiseProduct['pack_size']))]['pack_size'] = $PackSizeWiseProduct['pack_size'];
                }
            
                $PackSizeWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
                    if($product->pack_size != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
                        return true;
                    }else{	
                        return false;
                    }
                })->unique('pack_size');
                $PackSizeWiseProducts = json_decode(json_encode($PackSizeWiseProducts), true);
                $PackSizeWiseProducts = array_values($PackSizeWiseProducts);
                $PackSizeWiseProducts = uniqueArray($PackSizeWiseProducts,'pack_size');
                $PackSizeWiseProducts = array_sort($PackSizeWiseProducts, 'display_rank', SORT_ASC);

                
                $PackWiseProducts_arr = [];
                foreach ($PackSizeWiseProducts as $key => $PackSizeWiseProduct)
                {
                    $PackWiseProducts_arr[$key]['product_id'] = $PackSizeWiseProduct['product_id'];
                    $PackWiseProducts_arr[$key]['sku'] = $PackSizeWiseProduct['sku'];
                    $PackWiseProducts_arr[$key]['product_name'] = $PackSizeWiseProduct['product_name'];
                    $PackWiseProducts_arr[$key]['product_url'] = $PackSizeWiseProduct['product_url'];
                    $PackWiseProducts_arr[$key]['pack_size'] = $PackSizeWiseProduct['pack_size'];
                    $PackWiseProducts_arr[$key]['product_description'] = $PackSizeWiseProduct['product_description'];
                    $PackWiseProducts_arr[$key]['category_id'] = $PackSizeWiseProduct['category_id'];
                    $PackWiseProducts_arr[$key]['retail_price'] = $PackSizeWiseProduct['retail_price'];
                    $PackWiseProducts_arr[$key]['our_price'] = $PackSizeWiseProduct['our_price'];
                    $PackWiseProducts_arr[$key]['sale_price'] = $PackSizeWiseProduct['sale_price'];
                    $PackWiseProducts_arr[$key]['image_name'] = $PackSizeWiseProduct['image_name'];
                    $PackWiseProducts_arr[$key]['extra_images'] = $PackSizeWiseProduct['extra_images'];
                    $PackWiseProducts_arr[$key]['display_rank'] = $PackSizeWiseProduct['display_rank'];
                    $PackSizeWiseProduct = $this->Get_Price_Val($PackSizeWiseProduct);

                    $PackWiseProducts_arr[$key]['retail_price'] = $PackSizeWiseProduct['retail_price'];
                    $PackWiseProducts_arr[$key]['our_price'] = $PackSizeWiseProduct['our_price'];
                    $PackWiseProducts_arr[$key]['sale_price'] = $PackSizeWiseProduct['sale_price'];
                    $PackWiseProducts_arr[$key]['retail_price_disp'] = $PackSizeWiseProduct['retail_price_disp'];
                    $PackWiseProducts_arr[$key]['our_price_disp'] = $PackSizeWiseProduct['our_price_disp'];
                    $PackWiseProducts_arr[$key]['sale_price_disp'] = $PackSizeWiseProduct['sale_price_disp'];
                    

                    //if(isset($SizeWiseProducts_arr[$key]['image_name']) && $SizeWiseProducts_arr[$key]['image_name'] != "")
                    //{

                    $PackWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'ZOOM');
                    $PackWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'LARGE');
                    $PackWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'MEDIUM');
                    $PackWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'THUMB');
                    $PackWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($PackWiseProducts_arr[$key]['image_name'], 'SMALL');
                    //}
                }


                $FlavourWiseProducts = $ProductsObj->filter(function ($product, $key) use($product_group_code,$status_val) {
                    if($product->flavour != '' && $product->product_group_code == $product_group_code && $product->status==$status_val && $product->category_status==1){
                        return true;
                    }else{	
                        return false;
                    }
                })->unique('flavour');
                $FlavourWiseProducts = json_decode(json_encode($FlavourWiseProducts), true);
                $FlavourWiseProducts = array_values($FlavourWiseProducts);
                $FlavourWiseProducts = uniqueArray($FlavourWiseProducts,'flavour');
                $FlavourWiseProducts = array_sort($FlavourWiseProducts, 'display_rank', SORT_ASC);

                $FlavourWiseProducts_arr = [];
                foreach ($FlavourWiseProducts as $key => $FlavourWiseProduct)
                {
                    $FlavourWiseProducts_arr[$key]['product_id'] = $FlavourWiseProduct['product_id'];
                    $FlavourWiseProducts_arr[$key]['sku'] = $FlavourWiseProduct['sku'];
                    $FlavourWiseProducts_arr[$key]['product_name'] = $FlavourWiseProduct['product_name'];
                    $FlavourWiseProducts_arr[$key]['product_url'] = $FlavourWiseProduct['product_url'];
                    $FlavourWiseProducts_arr[$key]['flavour'] = $FlavourWiseProduct['flavour'];
                    $FlavourWiseProducts_arr[$key]['product_description'] = $FlavourWiseProduct['product_description'];
                    $FlavourWiseProducts_arr[$key]['category_id'] = $FlavourWiseProduct['category_id'];
                    $FlavourWiseProducts_arr[$key]['retail_price'] = $FlavourWiseProduct['retail_price'];
                    $FlavourWiseProducts_arr[$key]['our_price'] = $FlavourWiseProduct['our_price'];
                    $FlavourWiseProducts_arr[$key]['sale_price'] = $FlavourWiseProduct['sale_price'];
                    $FlavourWiseProducts_arr[$key]['image_name'] = $FlavourWiseProduct['image_name'];
                    $FlavourWiseProducts_arr[$key]['extra_images'] = $FlavourWiseProduct['extra_images'];

                    $FlavourWiseProduct = $this->Get_Price_Val($FlavourWiseProduct);

                    $FlavourWiseProducts_arr[$key]['retail_price'] = $FlavourWiseProduct['retail_price'];
                    $FlavourWiseProducts_arr[$key]['our_price'] = $FlavourWiseProduct['our_price'];
                    $FlavourWiseProducts_arr[$key]['sale_price'] = $FlavourWiseProduct['sale_price'];
                    $FlavourWiseProducts_arr[$key]['retail_price_disp'] = $FlavourWiseProduct['retail_price_disp'];
                    $FlavourWiseProducts_arr[$key]['our_price_disp'] = $FlavourWiseProduct['our_price_disp'];
                    $FlavourWiseProducts_arr[$key]['sale_price_disp'] = $FlavourWiseProduct['sale_price_disp'];

                    //if(isset($SizeWiseProducts_arr[$key]['image_name']) && $SizeWiseProducts_arr[$key]['image_name'] != "")
                    //{

                    $FlavourWiseProducts_arr[$key]['product_zoom_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'ZOOM');
                    $FlavourWiseProducts_arr[$key]['product_large_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'LARGE');
                    $FlavourWiseProducts_arr[$key]['product_medium_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'MEDIUM');
                    $FlavourWiseProducts_arr[$key]['product_thumb_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'THUMB');
                    $FlavourWiseProducts_arr[$key]['product_small_image'] = Get_Product_Image_URL($FlavourWiseProducts_arr[$key]['image_name'], 'SMALL');
                    //}
                }
		
                $SizeWiseArray = $SizeWiseProducts_arr;
                $PackSizeWiseArray = $PackWiseProducts_arr;
                $FlavourWiseArray = $FlavourWiseProducts_arr;
                $PackSizeSelectedWiseArray = $PackWiseProductsSelected_arr;
                $SizeSelectedWiseArray = $SizeWiseProductsSelected_arr;

                    $this->PageData['Product'] = $Product;
                    $this->PageData['product_sku'] = $Product['sku'];
                    $this->PageData['ActiveSize'] =  $Product['size'];
                    $this->PageData['ActivePackSize'] =  $Product['pack_size'];
                    $this->PageData['ActiveFlavour'] =  $Product['flavour'];
                    $Wishproducts_id = 0;
                    if(isset($PrdWishRes[0]['products_id']) && $PrdWishRes[0]['products_id'] !="")
                    {
                        $Wishproducts_id = $PrdWishRes[0]['products_id'];
                    }
                    $this->PageData['Wishproducts_id'] =  $Wishproducts_id;
                    $this->PageData['arr_extra_image'] = $arr_extra_image;
                    $this->PageData['SizeWiseArray'] = $SizeWiseArray;
                    $this->PageData['PackSizeWiseArray'] = $PackSizeWiseArray;
                    $this->PageData['FlavourWiseArray'] = $FlavourWiseArray;
                    $this->PageData['PackSizeSelectedWiseArray'] = $PackSizeSelectedWiseArray;
                    $this->PageData['SizeSelectedWiseArray'] = $SizeSelectedWiseArray;
                    $this->PageData['CurencySymbol'] = $curencySymbol;
                   
                    return view('popup.quickviewpopup')->with($this->PageData);
                }

            
        }
        } catch(\Exception $e){
           
            return  view('errors.404')->with($this->PageData);
        } 	
    }
    
    public function wishlistAdd(Request $request)
    {

        $this->PageData['GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA'] =  "";
        if ($request->ajax()) {
            $this->PageData['var_msg'] = "";
            $this->PageData['isAction'] = $request['isAction'];
            $this->PageData['SITE_URL'] = config('const.SITE_URL');
            $this->PageData['is_loginpopup_action'] = "0";

            if ($request['isAction'] == 'wish_forget') {

                if (!isset($request['isPopup'])) {

                    $validatedData = $request->validate([
                        'email' => 'required|email'
                    ], [
                        'email.required' => config('fmessages.Login.Email'),
                        'email.email' => config('fmessages.Login.ValidEmail'),
                    ]);

                    $email = $request['email'];
                    $password = "";
                    $ChkEmail = Customer::where('email', '=', $request['email'])->where('registration_type', '=', 'M')->get();

                    if ($ChkEmail && $ChkEmail->count() > 0) {
                        $password = $ChkEmail[0]['password'];
                    } else {
                        $password = "";
                    }

                    if (trim($password) != '') {

                        $token = Str::random(40);
                        $UserDataArray = array(
                            'reset_token' => $token
                        );
                        $User = Customer::find($ChkEmail[0]->customer_id);
                        $firstname = $User->first_name;
                        $User->update($UserDataArray);
                        $Template = GetMailTemplate("RESET_PASSWORD");
                        $EmailBody = $Template[0]->mail_body;

                        $siteUrl =  config('Settings.Site_URL') . 'reset/' . $token;
                        $EmailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $EmailBody);
                        $EmailBody = str_replace('{$vemail}', $ChkEmail[0]->email, $EmailBody);
                        $EmailBody = str_replace('{$firstname}', $firstname, $EmailBody);
                        $EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
                        $EmailBody = str_replace('{$Site_URL}', $siteUrl, $EmailBody);

                        $EmailBody = view('admin.email_templates.content')->with(compact('EmailBody'))->render();
                        // echo $EmailBody;
                        // exit;
                        $To = $ChkEmail[0]->email;
                        $Subject = $Template[0]->subject;
                        $From = config('Settings.CONTACT_MAIL');
                        // SendMail($Subject,$EmailBody,$To,$From);

                        Session::flash('failedfp', '');
                        Session::flash('successfp', config('fmessages.Forgot.Success'));
                    } else {
                        Session::flash('successfp', '');
                        Session::flash('failedfp', config('fmessages.Forgot.NotExistEmail'));
                    }
                }

                return view('popup.wishlist-add-popup')->with($this->PageData);
            }

            if ($request['isAction'] == 'login_popup') {

                $email = ($request['email'] ? $request['email'] : "");
                $password = ($request['password'] ? md5($request['password']) : "");
                $this->PageData['is_loginpopup_action'] = "1";
                $this->PageData['checkoutVal'] = "No";

                //Session::put('failed', config('fmessages.Login.Failed'));
                session()->forget('failed');
                if ($request['checkoutVal'] == 'Yes') {
                    $this->PageData['checkoutVal'] = "Yes";
                }

                if ($request->has('check_value') && $request->check_value == 1) {
                    $validatedData = $request->validate([
                        'email' => 'required|email',
                        'password' => 'required'
                    ], [
                        'email.required' => config('fmessages.Login.Email'),
                        'email.email' => config('fmessages.Login.ValidEmail'),
                        'password.required' => config('fmessages.Login.Password')
                    ]);
                }

                if (trim($email) != '' && trim($password) != '') {
                    $isLogin = $this->LoginpProcess($email, $password);
                    //dd($isLogin);
                    if ($isLogin == false) {
                        //Session::flash('failed', config('fmessages.Login.Failed'));
                        Session::put('failed', config('fmessages.Login.Failed'));
                        return view('popup.login-popup')->with($this->PageData);
                    } else {
                        return true;
                    }
                } else {
                    //$this->PageData['isAction'] = 'login_popup';
                    return view('popup.login-popup')->with($this->PageData);
                }
                //return view('popup.wishlist-add-popup')->with($this->PageData);
                //return view('popup.login-popup')->with($this->PageData);
            }

            if ($request['isAction'] == 'register_popup') {
                if (isset($request['action']) && $request['action'] == 'signup') {
                    $email = ($request['email'] ? $request['email'] : "");
                    $password = ($request['password'] ? md5($request['password']) : "");
                    $this->PageData['is_loginpopup_action'] = "1";
                    $this->PageData['checkoutVal'] = "No";
                    $this->PageData['email_val'] = '';
                    if ($request['email_val'] != 'Yes') {
                        $this->PageData['email_val'] = $request['email_val'];
                    }
                    if ($request['checkoutVal'] == 'Yes') {
                        $this->PageData['checkoutVal'] = "Yes";
                    }

                    if ($request->has('check_value') && $request->check_value == 1) {
                        $validatedData = $request->validate([
                            'email' => 'required|email',
                            'password' => 'required'
                        ], [
                            'email.required' => config('fmessages.Login.Email'),
                            'email.email' => config('fmessages.Login.ValidEmail'),
                            'password.required' => config('fmessages.Login.Password')
                        ]);
                    }

                    /*if (trim($email) != '' && trim($password) != '') {
						$isLogin = $this->LoginpProcess($email, $password);
						if ($isLogin == false) {
							Session::flash('failed', config('fmessages.Login.Failed'));
						} else {
							return true;
						}
					} else {*/
                    return view('popup.checkout-register-popup')->with($this->PageData);
                    //}
                }
            }

            if ($request['isAction'] == 'wish_login') {

                $customer_id = Session::get('sess_icustomerid');
                $this->PageData['checkoutVal'] = "No";
                if ($request['checkoutVal'] == 'Yes') {
                    $this->PageData['checkoutVal'] = "Yes";
                }
                if (isset($request['products_id']) && $request['products_id'] != '') {
                    $products_id = $request['products_id'];
                    Session::put('Wish_ProductsID', $products_id);
                } else {
                    $products_id = Session::get('Wish_ProductsID');
                }

                $email = ($request['email'] ? $request['email'] : "");
                $password = ($request['password'] ? md5($request['password']) : "");

                if ($request->has('check_value') && $request->check_value == 1) {
                    $validatedData = $request->validate([
                        'email' => 'required|email',
                        'password' => 'required'
                    ], [
                        'email.required' => config('fmessages.Login.Email'),
                        'email.email' => config('fmessages.Login.ValidEmail'),
                        'password.required' => config('fmessages.Login.Password')
                    ]);
                }

                if (trim($email) != '' && trim($password) != '') {

                    $isLogin = $this->LoginpProcess($email, $password);

                    if ($isLogin == false) {
                        Session::flash('failed', config('fmessages.Login.Failed'));
                    }
                }

                if (Session::get('sess_icustomerid') && !empty(Session::get('sess_icustomerid')) && Session::get('etype') == 'M') {

                    $WishCatRS = WishlistCategory::select('*')
                        ->where('customer_id', '=', Session::get('sess_icustomerid'))
                        ->orderBy('wishlist_category_id', 'DESC')
                        ->get();

                    $prod_info = Product::select('product_id', 'sku', 'product_name')
                        ->where('product_id', '=', Session::get('Wish_ProductsID'))
                        ->get();

                    $this->PageData['prod_info'] = $prod_info;
                    $this->PageData['WishCatRS'] = $WishCatRS;
                    $this->PageData['isAction'] = 'wish_product';
                    Session::flash('success', '');
                } else {
                    $this->PageData['isAction'] = 'wish_login';
                }

                $this->PageData['section_name'] = $request['section_name'];
                $this->PageData['brand_id'] = $request['brand_id'];
                $this->PageData['category_id'] = $request['category_id'];
                return view('popup.wishlist-add-popup')->with($this->PageData);
            }


            ## Add Remove wishList functionality
            if ($request['isAction'] == 'remove_wish') {
                $wishlistCount = Cache::get('wishList.totalQty');
                $productId = $request['products_id'];
                $sectionName = $request['section_name'];
                $sessionCustomerId = Session::get('sess_icustomerid');
                $wishProduct = Wishlist::where('customer_id', '=', $sessionCustomerId)->where('products_id', '=', $productId)->first();
                if ($wishProduct) {
                    $wishProduct->delete();
                    $wishListCacheKey = 'getAllWishlist_'.Session::get('customer_id').'_cache';
                    $cachedData = Cache::get($wishListCacheKey);
                    $this->addRemoveProductToWishListArray($productId, [], $request['category_id'], $request['brand_id']);
                    $wishlistCount = Wishlist::whereCustomerId($sessionCustomerId)->count();
                    Session::put('wishList.totalQty', $wishlistCount);
                    unset($cachedData[$productId]);
                    if (isset($cachedData) && !empty($cachedData)) {
                        Cache::put($wishListCacheKey, $cachedData);
                    }
    
                } 
                return response()->json(['wishlistCount' => $wishlistCount]);
            }

            if ($request['isAction'] == 'wish_category') {

                $this->PageData['Wish_ProductsID'] = Session::get('Wish_ProductsID');
                return view('popup.wishlist-add-popup')->with($this->PageData);
            }

            if ($request['isAction'] == 'AddWishProduct') {
                $validatedData = $request->validate([
                    // 'description' => 'required',
                    'wishlist_category_id' => 'required'
                ]);
                // [
                // 	'description.required' => config('fmessages.WishList.AddDescription'),
                // 	'wishlist_category_id.required' => config('fmessages.WishList.Category')
                // ]

                $description  = stripslashes(nl2br(strtr($request['description'], array('\r' => chr(13), '\n' => chr(10)))));
                $description  = str_replace("<br />", "", strip_tags($request['description']));
                $sessionCustomerId = Session::get('sess_icustomerid');
                $productId = $request['productsId'];
                $WishListProduct = array();
                $WishListProduct['wishlist_category_id'] = $request['wishlist_category_id'];
                $WishListProduct['customer_id'] = $sessionCustomerId;
                $WishListProduct['products_id'] = $productId;
                $WishListProduct['sku'] = $request['sku'];
                $WishListProduct['description'] = $description;
                // Perform the updateOrInsert operation
                /*Wishlist::updateOrInsert(
                                            ['customer_id' => $sessionCustomerId, 'products_id' => $productId],
                                            $WishListProduct
                                        );*/
                $sectionName = $request['section_name'];
                $wishProduct = Wishlist::where('customer_id', '=', $sessionCustomerId)->where('products_id', '=', $productId)->first();
                if (!$wishProduct) {
                    $newWish = Wishlist::create($WishListProduct);
                    $wishListCacheKey = 'getAllWishlist_'.Session::get('customer_id').'_cache';
                    $selectedFieldsOfWishList = [
                                    "customer_id" => $newWish["customer_id"],
                                    "products_id" => $newWish["products_id"],
                                    "sku" => $newWish["sku"]
                                  ];
                    $PrdWishArr = [$productId => $selectedFieldsOfWishList];              
                    if(!empty($PrdWishArr)){
                        Cache::put($wishListCacheKey, $PrdWishArr);
                        $this->addRemoveProductToWishListArray($newWish["products_id"], $selectedFieldsOfWishList, $request['category_id'], $request['brand_id']);
                    }
                    $wishListCacheKey = 'getAllWishlist_'.Session::get('customer_id').'_cache';
                    $cachedData = Cache::get($wishListCacheKey);
                    $cachedData1 = Cache::get($cachedData[(int)$productId]);
                    
                }
                $wishlistCount = Wishlist::whereCustomerId($sessionCustomerId)->count();
                Session::put('wishList.totalQty', $wishlistCount);
                // $userId = Auth::user()->customer_id;
                // dd($userId);
                // dd(Session::all());
                    
                
                
                
                
                if (Session::get('sess_icustomerid') && !empty(Session::get('sess_icustomerid')) && Session::get('etype') == 'M') {

                    $WishCatRS = WishlistCategory::select('*')
                        ->where('customer_id', '=', Session::get('sess_icustomerid'))
                        ->orderBy('wishlist_category_id', 'DESC')
                        ->get();

                    $prod_info = Product::select('product_id', 'sku', 'product_name')
                        ->where('product_id', '=', Session::get('Wish_ProductsID'))
                        ->get();

                    $this->PageData['prod_info'] = $prod_info;
                    $this->PageData['WishCatRS'] = $WishCatRS;
                    $this->PageData['isAction'] = 'wish_product';
                }

                //GA4 Google eCommerce add_to_wishlist code Start
                 $prod_info = Product::select('product_id', 'sku', 'product_name', 'our_price')
                     ->where('product_id', '=', $request['productsId'])
                     ->get();

                 $GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA = '';

                 $ga4_google_add_to_wishlist_str_gtm = array(
                    "item_id" => $request['sku'],
                    "item_name" => $prod_info[0]->product_name,
                    "affiliation" => "HBA",
                    "currency" => "USD",
                    "price" => $prod_info[0]->our_price,
                    "quantity" => "1",
                 );

                $categoyrArryLilst =  $this->getCategoryBySku($request['sku']);

                $ga4_categoryList = json_encode(array_merge($ga4_google_add_to_wishlist_str_gtm,$categoyrArryLilst));

                 $GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA = '
                 dataLayer.push({ ecommerce: null });
                 dataLayer.push({
                     event: "add_to_wishlist",
                     ecommerce: {
                         "currency": "USD",
                         "items": ['.rtrim($ga4_categoryList).']
                     }
                 });';

                 $this->PageData['GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA'] =  $GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA;
                //GA4 Google eCommerce add_to_wishlist code End

                //Session::flash('successwc', config('fmessages.WishList.AddSuccess'));
                $this->PageData['prod_info'] = $prod_info;
                $this->PageData['WishCatRS'] = $WishCatRS;
                $this->PageData['isAction'] = 'wish_product';


                /*$AllWishlistCacheName = 'getAllWishlist_'.Session::get('customer_id').'_cache';
                Cache::forget($AllWishlistCacheName);*/
                return response()->json(['wishlistCount' => $wishlistCount, 'WishAddSuccessMessage' => config('fmessages.WishList.AddSuccess')]);
                // return view('popup.wishlist-add-popup')->with($this->PageData);
            }

            if ($request['isAction'] == 'AddWishCategory') {

                $validatedData = $request->validate([
                    'category_name' => 'required',
                    // 'description' => 'required'
                ], [
                    'category_name.required' => config('fmessages.WishList.AddCategory'),
                    // 'description.required' => config('fmessages.WishList.AddDescription')
                ]);

                $this->PageData['Wish_ProductsID'] = Session::get('Wish_ProductsID');
                $wishcategory = WishlistCategory::where('name', '=', trim($request['category_name']))->where('customer_id', '=', Session::get('sess_icustomerid'))->get();

                if (count($wishcategory) > 0) {
                    Session::flash('successwc', '');
                    Session::flash('failedwc', config('fmessages.WishListCategory.ExistCategory'));
                } else {
                    $description  = stripslashes(nl2br(strtr($request['description'], array('\r' => chr(13), '\n' => chr(10)))));
                    $description  = str_replace("<br />", "", strip_tags($description));

                    $WishListCategory = array();
                    $WishListCategory['customer_id'] = Session::get('sess_icustomerid');
                    $WishListCategory['name'] = $request['category_name'];
                    $WishListCategory['description'] = $description;
                    $WishListCategory['status'] = '1';
                    WishlistCategory::create($WishListCategory);
                    Session::flash('failedwc', '');
                    Session::flash('successwc', config('fmessages.WishListCategory.AddSuccess'));
                }

                $this->PageData['isAction'] = 'wish_category';
                return view('popup.wishlist-add-popup')->with($this->PageData);
            }
        }
    }
    public function LoginRegister(Request $request)
    {
        if ($request['isAction'] == 'login_popup') {
            $email = ($request['email'] ? $request['email'] : "");
            $password = ($request['password'] ? md5($request['password']) : "");
            $this->PageData['is_loginpopup_action'] = "1";
            $this->PageData['checkoutVal'] = "No";

            session()->forget('failed');
            if ($request['checkoutVal'] == 'Yes') {
                $this->PageData['checkoutVal'] = "Yes";
            }

            if ($request->has('check_value') && $request->check_value == 1) {
                $validatedData = $request->validate([
                    'email' => 'required|email',
                    'password' => 'required'
                ], [
                    'email.required' => config('fmessages.Login.Email'),
                    'email.email' => config('fmessages.Login.ValidEmail'),
                    'password.required' => config('fmessages.Login.Password')
                ]);
            }

            if (trim($email) != '' && trim($password) != '') {
                $isLogin = $this->LoginpProcess($email, $password);
                if ($isLogin == false) {
                    Session::put('failed', config('fmessages.Login.Failed'));
                    return view('popup.login-popup')->with($this->PageData);
                } else {
                    return true;
                }
            } else {
                return view('popup.register-login-popup')->with($this->PageData);
            }
        }

        if ($request['isAction'] == 'register_popup') {
            if (isset($request['action']) && $request['action'] == 'signup') {
                $email = ($request['email'] ? $request['email'] : "");
                $password = ($request['password'] ? md5($request['password']) : "");
                $this->PageData['is_loginpopup_action'] = "1";
                $this->PageData['checkoutVal'] = "No";
                $this->PageData['email_val'] = '';
                if ($request['email_val'] != 'Yes') {
                    $this->PageData['email_val'] = $request['email_val'];
                }
                if ($request['checkoutVal'] == 'Yes') {
                    $this->PageData['checkoutVal'] = "Yes";
                }

                if ($request->has('check_value') && $request->check_value == 1) {
                    $validatedData = $request->validate([
                        'email' => 'required|email',
                        'password' => 'required'
                    ], [
                        'email.required' => config('fmessages.Login.Email'),
                        'email.email' => config('fmessages.Login.ValidEmail'),
                        'password.required' => config('fmessages.Login.Password')
                    ]);
                }

                /*if (trim($email) != '' && trim($password) != '') {
					$isLogin = $this->LoginpProcess($email, $password);
					if ($isLogin == false) {
						Session::flash('failed', config('fmessages.Login.Failed'));
					} else {
						return true;
					}
				} else {*/
                return view('popup.register-login-popup')->with($this->PageData);
                //}
            }
        }
    }
    public function LoginpProcess($email, $password)
    {
        $Customer = Customer::where('email', $email)
            ->where('password', $password)
            ->where('status', '1')
            ->where('registration_type', 'M')
            ->first();

        if ($Customer && $Customer->count() > 0) {
            if ($Customer->eusertype == "Wholesaler Pending") {
                $Customer->eusertype = "Retailer";
            }

            Auth::login($Customer, false);
            Session::put('sess_useremail', $Customer->email);
            Session::put('sess_first_name', $Customer->first_name);
            Session::put('sess_icustomerid', $Customer->customer_id);
            Session::put('customer_id', $Customer->customer_id);
            Session::put('eusertype', $Customer->eusertype);
            Session::put('is_dropshipper', $Customer->is_dropshipper);
            Session::put('etype', 'M');
            Session::put('payment_amount', $Customer->payment_amount);

            // Session::put('customer_id',$Customer->customer_id);	
            // Session::put('customer_email',$Customer->email);	
            // Session::put('customer_first_name',$Customer->first_name);	
            // Session::put('customer_last_name',$Customer->last_name);	
            // Session::put('etype',$Customer->registration_type);	
            // Session::put('is_login',1);

            return true;
        } else {
            return false;
        }
    }

    
    public function return_order_item(Request $request)
    {
        if ($request->ajax()) {

            if (isset($request['isAction'])) {

                $validatedData = $request->validate([
                    'message' => 'required',
                    'return_request_quantity' => 'required'
                ], [
                    'message.required' =>  'Please Enter Message',
                    'return_request_quantity.required' => 'Please select quantity for return'
                ]);

                $message = $request['message'];
                $return_request_quantity = $request['return_request_quantity'];
                $order_detail_id = $request['order_detail_id'];
                $customerID = (int)Session::get('customer_id');

                $updateOrderItem = OrderDetail::where('order_detail_id', '=', $order_detail_id)->first();
                if($updateOrderItem){

                    $updateOrderItem->return_message = $message;
                    $updateOrderItem->is_return_request = '1';
                    $updateOrderItem->return_request_datetime = date('Y-m-d H:i:s');
                    $updateOrderItem->return_request_quantity = $return_request_quantity;
                    $updateOrderItem->save();
                    
                    $OrdResult = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))->where('order_id', '=', $updateOrderItem->order_id)->first();
                    $OrdResult->status = 'Return Request';
                    $OrdResult->save();
                }
                $this->sendReturnOrderEmail($updateOrderItem->order_id, $customerID, $order_detail_id);
                $response['message'] = "Return Request has been send successfully"; // Mail has been sent successfully
                $response['action'] = true;
                return response()->json($response);
            }


            $this->PageData['returnorderitem'] = 'returnorderitem';
            $order_detail_id = $request->order_detail_id;
            $orderItemQuantity = $request->orderItemQuantity;
            return view('popup.return-order-item-popup', compact('order_detail_id', 'orderItemQuantity'))->with($this->PageData);
        }
    }

    public function sendReturnOrderEmail($orderID, $customerID,$order_detail_id)
	{
		$SITE_URL 		= config('const.SITE_URL');
        $OrdersInfo = Order::where('order_id','=',$orderID)->where('customer_id','=',$customerID)->first();
        $OrderDetail = OrderDetail::where('order_id','=',$orderID)->where('order_detail_id', $order_detail_id)->first();
		## Billing Detils Start
		##---------------------
		$billing_details	= '';
		$billing_address	= '';
		
		if(trim($OrdersInfo->bill_address1) != "") { $billing_address .= $OrdersInfo->bill_address1; }
		if(trim($OrdersInfo->bill_address2) != "") { $billing_address .= '<br>'.$OrdersInfo->bill_address2; }
		
		$billing_details .='<table border="0" class="fullbox spacing_smallnone" cellpadding="0" cellspacing="0" style="width:100%; padding:0 20px;">
			<tr class="flex" align="left">
				<td class="" style="color:rgba(51, 51, 51);font-size:20px;font-family:\'Lato\', sans-serif;padding:0px 10px;font-weight:700; text-transform:uppercase;">Billing Address</td>
			</tr>
			<tr><td class="flex" height="20"></td></tr>
			<tr class="flex" align="left">
				<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">'.$OrdersInfo->bill_first_name.' '.$OrdersInfo->bill_last_name.'<br clear="hide">'.$billing_address.',<br clear="hide">'.$OrdersInfo->bill_city.', '.$OrdersInfo->bill_state.' - '.$OrdersInfo->bill_zip.', '.$OrdersInfo->bill_country.'</td>
			</tr>
			<tr class="flex" align="left">
				<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">Phone: <a href="tel:+'.$OrdersInfo->bill_phone.'" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->bill_phone.'</a></td>
			</tr>
			<tr class="flex" align="left">
				<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">Email: <a href="#" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->ship_email.'</a></td>
			</tr>
		</table>';
		## Billing Detils End
		##---------------------
		
		## Shipping Details Start
		##-----------------------
		$shipping_details	= '';
		$shipping_address	= '';
		
		if(trim($OrdersInfo->ship_address1) != "") { $shipping_address .= $OrdersInfo->ship_address1; }
		if(trim($OrdersInfo->ship_address2) != "") { $shipping_address .= '<br>'.$OrdersInfo->ship_address2; }
		
		$shipping_details .='<table border="0" class="fullbox spacing_smallnone" cellpadding="0" cellspacing="0" style="width:100%; padding:0 20px;">
			<tr class="flex" align="left">
				<td class="" style="color:rgba(51, 51, 51);font-size:20px;font-family:\'Lato\', sans-serif;padding:0px 10px;font-weight:700; text-transform:uppercase;">Shipping Address</td>
			</tr>
			<tr><td class="flex" height="20"></td></tr>
			<tr class="flex" align="left">
				<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">'.$OrdersInfo->ship_first_name.' '.$OrdersInfo->ship_last_name.'<br clear="hide">'.$shipping_address.',<br clear="hide"> '.$OrdersInfo->ship_city.' - '.$OrdersInfo->ship_state.' - '.$OrdersInfo->ship_zip.', '.$OrdersInfo->ship_country.'</td>
			</tr>
			<tr class="flex" align="left">
				<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">Phone: <a href="tel:+'.$OrdersInfo->ship_phone.'" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->ship_phone.'</a></td>
			</tr>
			<tr class="flex" align="left">
				<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">Email: <a href="#" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->ship_email.'</a></td>
			</tr>
		</table>';
		## Shipping Details End
		##-----------------------
		
		//Item Detail Start
		$STR_EMAIL_ITEMS ='
		<tr>
			<td>
				<table style="width:100%; font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;" cellpadding="0" cellspacing="0" align="center">
					<tbody>
						<tr style="margin:0px; padding:0px;" align="center">
							<td style="margin:0px; padding:10px 5px 10px 0px; border-bottom:1px solid #e2e6ea;" align="left"><strong>Item&nbsp;Description</strong></td>
							<td style="margin:0px; padding:10px 5px; border-bottom:1px solid #e2e6ea;"><strong>	Return Requested Quantity</strong></td>
						</tr>';
										
						//For Loop Start
						$STR_EMAIL_ITEMS .='<tr style="margin:0px; padding:0px;" align="center">
							<td style="margin:0px; padding:10px 5px 10px 0px;" align="left">
								<table cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td style="width:55px;">
												<img src="'.$OrderDetail->Image.'" alt="'.$OrderDetail->product_name.'" style="width:50px; height:50px;">
											</td>
											<td style="font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">
												<strong><a href="'.config('const.SITE_URL').'/'.$OrderDetail->product_url.'" style="text-decoration:none;"><font color="#000000">'.$OrderDetail->product_name.'</font></a></strong>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td style="margin:0px; padding:10px 5px; font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">
								<strong><font color="#000000">'.$OrderDetail->return_request_quantity.'</font></strong>
							</td>

						</tr>';
						$STR_EMAIL_ITEMS .='
					</tbody>
				</table>
			</td>
		</tr>';
		
		##Send Email TO Customer
		##---------------------
		$res_mail 	= GetMailTemplate("RETURN_REQUEST");		 
		$ToEmail 	= $OrdersInfo->ship_email;
		$Subject	= $res_mail[0]->subject. " Order Id - ". $OrdersInfo->order_id;
		
		$EmailBody = $res_mail[0]->mail_body;
		
		$EmailBody = str_replace('{$SITE_URL}',  config('app.url'), $EmailBody);
		$EmailBody = str_replace('{$first_name}',  $OrdersInfo->bill_first_name, $EmailBody);
		$EmailBody = str_replace('{$iorder_id}',  $OrdersInfo->order_id, $EmailBody);
		$EmailBody = str_replace('{$bill_address}', $billing_details, $EmailBody);
		$EmailBody = str_replace('{$ship_address}', $shipping_details, $EmailBody);
		$EmailBody = str_replace('{$tablepro}', $STR_EMAIL_ITEMS, $EmailBody);
		$EmailBody = str_replace('{CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);
		$EmailBody = str_replace('{TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
		$EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);
		$EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
		$EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
		//echo $EmailBody; exit;
		## Send Email TO Customer
		##------------------------
		$From = config('Settings.CONTACT_MAIL');
		$a = $this->sendSMTPMail($ToEmail, $Subject, $EmailBody, $From);
		$b = $this->sendSMTPMail('sachin.qualdev@gmail.com', $Subject, $EmailBody, $From);
		## Send Email TO Admin 	
		##----------------------
		$c = $this->sendSMTPMail(config('Settings.ADMIN_MAIL'), $Subject, $EmailBody, $From);
		
	}
}
