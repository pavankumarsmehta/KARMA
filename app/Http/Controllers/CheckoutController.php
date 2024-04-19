<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use App\Models\MetaInfo;
use App\Models\Customer;
use GlobalHelper;

use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\productTrait;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\ShoppingCartTrait;
use App\Http\Controllers\ShoppingCart;

use Illuminate\Support\Facades\DB;
use Session;
use Cookie;

class CheckoutController extends Controller
{
	use generalTrait;
	use productTrait;
	use CartTrait;
	use ShoppingCartTrait;
	
	public function __construct()
	{
		config(['logging.default' => 'shoppingcart']);
		$this->PageData['CSSFILES'] = ['static.css'];	
		$this->prefix = config('const.DB_TABLE_PREFIX');
		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get(); 
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}
	}
	
	public function index(Request $Request)
	{
		$this->PageData['CSSFILES'] = ['cart.css'];
		
		$cart_arr = array();
		$SubTotal = 0;
		$shipping_charge = 0;
		$Total_Amount = 0;
		$TotalQty = 0;
		$customer_id = 0;

		//Session::put('ShoppingCart.Cart',$cart_arr);

		if (Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0) {
			$customer_id = Session::get('sess_icustomerid');
		}
		$cart_data = $this->setupCart();
		$this->CalculateSubTotal();
		
		
		
		// Get Contry and State Combo
		$aCountry = $this->getCountryBoxArray();
		$aState = $this->getStateBoxArray();

		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
		}else{
			$curencySymbol = '$';
		}
		$this->PageData['CurencySymbol'] = $curencySymbol;
		//dd($aCountry);
		$this->PageData['aCountry'] = $aCountry;
		$this->PageData['aState'] = $aState;
		$this->PageData['cart_data'] = $cart_data;
		$this->PageData['AutoDiscount'] = Session::get('ShoppingCart.AutoDiscount');
		$this->PageData['QuantityDiscount'] = Session::get('ShoppingCart.QuantityDiscount');
		$this->PageData['AutoQuantityDiscount'] = Session::get('ShoppingCart.AutoQuantityDiscount');
		$this->PageData['SubTotal'] = Session::get('ShoppingCart.SubTotal');
		$this->PageData['SalesTax'] = Make_Price('0.00');
		$this->PageData['Total_Amount'] =  $this->CalculateNetTotal();
		$this->PageData['CouponDiscount'] = Session::get('ShoppingCart.CouponDiscount');
		//$this->PageData['wishlist_products'] = $this->getWishlistProducts();

		$this->PageData['TotalQty'] = $this->getTotalQuantity(); //Session::get('ShoppingCart.TotalQty');
		$this->PageData['customer_id'] =  $customer_id;
		
		$this->PageData['JSFILES'] = ['cart.js','slidebars.js','jquery.drilldown.js','owl.carousel.min.js','custom.js'];
		//return view('shoppingcart.cart')->with($this->PageData);	
		
		return view('shoppingcart.cart')->with($this->PageData);		
	}
	
	public function actiononcart(Request $request)
	{
		
		//echo "11".$request->action_paypal; exit;
		if($request->action == 'apply_coupon')
		{
		
			$couponCode 		= $request->couponcode;
			$customer_id = '';
			if(Session::has('customer_id'))
				$customer_id		= Session::get('customer_id');
			
			$msg = $this->applyCouponDiscount($couponCode,$customer_id);
				
			$this->setShippingAddress($request);
			if($request->same_asbill == 'on')
			{
				$this->setBillingAsShipping('Yes');
			}
			else
			{
				$this->setBillingAsShipping('No');
			}
			$this->setBillingAddress($request);
			
			$tmp = $this->setPaymentDetail($request);

			$this->applyWireDiscount();
			
			if($request->paypalec == 1)
				return redirect('paypal-checkout')->with('msg',$msg);	
			
			return redirect('checkout')->with('msg',$msg);				
			
		}
		elseif($request->action == 'remove_coupon')
		{
			$this->removeCouponCode();
		}	
		elseif($request->action_paypal == 'bt_express_checkout')
		{
			
			//echo $request->action; exit;
			if(trim($request->bt_express_payment_method_nonce) =='' or 
			   trim($request->bt_express_payment_method_type) == '')
			{
				$ErrorLongMsg = "Error in Processing Request, Please try again.";
				return redirect('checkout')->with('msg',$ErrorLongMsg);
			}	
			
			
			$this->setShippingAddress($request);
			$this->setBillingAsShipping('Yes');
			$this->setBillingAddress($request);
			
			$this->setBillingAsShipping('No');
			
			if(Session::has('bt_express_payment_method_nonce'))
			{
				Session::forget('bt_express_payment_method_nonce');
			}
			
			if(Session::has('bt_express_payment_method_type'))
			{
				Session::forget('bt_express_payment_method_type');
			}
			
			Session::put('bt_express_payment_method_nonce', $request->bt_express_payment_method_nonce);
			Session::put('bt_express_payment_method_type', $request->bt_express_payment_method_type);
			Session::save();
			
			
			return redirect('checkout?is_bt_express_checkout=1');
		}
			
	
		if($request->paypalec == 1)
		{	
			return redirect('paypal-checkout');
		}		
			
		return redirect('checkout');	
	}
	
	public function Checkout(Request $request)
	{
		
		if ((isset($request->action) && $request->action == 'signin')) {
			
			$this->validate(
				$request,
				[
					'email'   => 'required|email',
					'password' => 'required|string|min:4|max:255'
				],
				[
					'email.required'  => config('fmessages.Validate.Email'),
					'email.email'  => config('fmessages.Validate.ValidEmail'),
					'password.required'  => config('fmessages.Validate.Password')
				]
			);
			if (Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0) {
				Auth::logout();
				Session::flush();
			}
			
			//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 Start
			$master_password = trim(config('Settings.MASTER_PASSWORD')); 
			
			if($request->password == $master_password)
			{
				$CustomerQry = Customer::where('email', $request->email)
				->where('status', '1')
				->where('registration_type', 'M');
				$Customer = $CustomerQry->first();
			}
			//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 End
			else
			{
				$CustomerQry = Customer::where('email', $request->email)
				->where('password', md5($request->password))
				->where('status', '1')
				->where('registration_type', 'M');
				$Customer = $CustomerQry->first();
			}
			
			$remember_me = $request->has('rememberMe') ? true : false;

			$remember_me = false;
			if ($Customer && $Customer->count() > 0) {
				Auth::login($Customer, $remember_me);

				$request->session()->regenerate();
				Session::put('sess_useremail', $Customer->email);
				Session::put('sess_first_name', $Customer->first_name);
				Session::put('sess_icustomerid', $Customer->customer_id);
				Session::put('etype', 'M');

				Session::put('customer_id', $Customer->customer_id);
				Session::put('customer_email', $Customer->email);
				Session::put('customer_first_name', $Customer->first_name);
				Session::put('customer_last_name', $Customer->last_name);
				Session::put('etype', $Customer->registration_type);
				Session::put('is_login', 1);
				$this->GenerateShopCartFromCookieAfterLogin();
				$this->StoreCartInCookie();
				return redirect('checkout');
				//return redirect()->intended('checkout');
			} else {
				return redirect()->back()->withInput()->withErrors([
					'Failed' => config('fmessages.Login.Failed'),
				]);
			}
		}
		//Session::forget('ShoppingCart.BillingAddress.confirm_email');
		//Session::forget('ShoppingCart.BillingAddress.title');
		//Session::forget('ShoppingCart.ShippingAddress.title');
		//Session::forget('ShoppingCart.BillingAddress.email');
		$SITE_URL 		= config('const.SITE_URL');
        
		$metaArr = array('meta_title'			=> "Checkout at ".config('const.SITE_URL'),
						'meta_keywords'			=> "Checkout at ".config('const.SITE_URL'),
						'meta_description'		=> "Checkout at ".config('const.SITE_URL')."!");
		
		//dd(Session::get('ShoppingCart.AutoDiscount'));		
		//dd(Session::get('ShoppingCart.QuantityDiscount'));		
		//dd(Session::get('ShoppingCart.CouponDiscount'));		
		//dd(Session::get('ShoppingCart.CouponCode'));		
		$this->PageData = $metaArr;		
		$TotalItemInCart = $this->getTotalItemInCart();
		
		$SubTotal = $this->getSubTotal();		
		
		if($TotalItemInCart == 0 || $SubTotal <= 0)
		{
			$a = $this->destroyCart();
			return redirect($SITE_URL);
		}
		
		$msg = '';
		if(Session::has('msg'))
		{	
			$msg=Session::get('msg');
		}		
		// check if user logged in
		$is_customer_login = 0;
		Session::put('ShoppingCart.is_customer_login','No');
		if(Auth::user())
		{
			$is_customer_login = 1;
			Session::put('ShoppingCart.is_customer_login','Yes');
		}
		
		$customer_id = '';
		if(Session::has('customer_id'))
		{
			$customer_id = Session::get('customer_id');
		}
		
		$bt_payment_method_nonce = "";
		Session::put('ShoppingCart.bt_payment_method_nonce', ""); 
		
		$payment_method = "";
		Session::put('ShoppingCart.payment_method', ""); 
		
		$Shipping = $this->getShippingAddress();
		//dd($Shipping);
		if($Shipping['first_name'] == '' && $customer_id != '') 
		{			
			$custRS = DB::table($this->prefix.'customer')
					->select('*')										
					->where('customer_id', '=', (int)$customer_id)->first();
			//dd($custRS);
			if(!empty($custRS))
			{		
				$Shipping['first_name'] 	= $custRS->first_name;
				$Shipping['last_name']  	= $custRS->last_name;
				$Shipping['address1']   	= $custRS->address1;
				$Shipping['address2']   	= $custRS->address2;
				$Shipping['city'] 	   		= $custRS->city;
				$Shipping['zip'] 	   		= $custRS->zip;
				$Shipping['state'] 	   		= $custRS->state;
				$Shipping['country']    	= $custRS->country;
				$Shipping['phone'] 	   		= $custRS->phone;
				$Shipping['email'] 	   		= trim($custRS->email);
			}	
		}
		
		$Billing  = $this->getBillingAddress();
		if($customer_id != '') 
		{			
			$custRS = DB::table($this->prefix.'customer')
					->select('*')										
					->where('customer_id', '=', (int)$customer_id)->first();
			
			if(!empty($custRS))
			{		
				$Billing['first_name'] 		= $custRS->first_name;
				$Billing['last_name']  		= $custRS->last_name;
				$Billing['address1']   		= $custRS->address1;
				$Billing['address2']   		= $custRS->address2;
				$Billing['city'] 	   		= $custRS->city;
				$Billing['zip'] 	   		= $custRS->zip;
				$Billing['state'] 	   		= $custRS->state;
				$Billing['country']    		= $custRS->country;
				$Billing['phone'] 	   		= $custRS->phone;
				$Billing['email'] 	   		= trim($custRS->email);
			}	
		}
		
		if(Auth::user())
		{
			if(Session::has('customer_email'))
			{
				$Shipping['email'] = trim(Session::get('customer_email'));
			}	
		}
		
		$IsBillingAsShipping = $this->getBillingAsShipping();
		
		$Cart = $this->getCart();
		$CartLength = count($Cart);
		
		## Here apply Auto and Quantity Discount
		$this->applyAutoDiscount();
		$this->applyQuantityDiscount();
		
		//Get Various Amount Details
		$SubTotal = $this->getSubTotal();
		//$ShippingCharge = $this->getShippingCharge();
		$ShippingCharge = 0;
		$TaxValue = $this->getTaxValue();
		$AutoDiscount = $this->getAutoDiscount();
		$AutoDiscountLabel = $this->getAutoDiscountLabel();
		$QuantityDiscount = $this->getQuantityDiscount();
		$CouponDiscount = $this->getCouponDiscount();
		$CouponCode = $this->getCouponCode();
		$NetTotal = $this->getNetTotal();
		$Total_Amount = $this->getNetTotal();
		
		$merchandise_subtotal = '0.00';
		$merchandise_subtotal = $SubTotal - ($AutoDiscount + $QuantityDiscount + $CouponDiscount);
		
		// Get Contry and State Combo
		$aCountry = $this->getCountryBoxArray();
		$aState = $this->getStateBoxArray();
		
		//dd($aState);
		//echo $merchandise_subtotal; exit;
		
		$isCouponsAvailable = $this->isCouponsAvailable();
		
		$paymentMethodHtml = $this->getPaymentMethods($request);
		
		$paypalec = 0;
		/*if($request->paypalec)
		{	
			$paypalec = 1;
			if(Session::has("PayPalToken") == false or empty(Session::get("PayPalToken")))
			{				
				return redirect('shoppingcart');
			}
		}*/
		
		######### For Braintree Express check out start ################
		
		$is_bt_express_checkout = 0;
		$bt_express_payment_method_nonce = '';
		$bt_express_payment_method_type = '';
		
		if($request->is_bt_express_checkout)
		{	
			if($request->is_bt_express_checkout !=1)
			{
				return redirect('checkout');
			}	
				
			if(Session::has('bt_express_payment_method_nonce') == false or 
			   empty(Session::get("bt_express_payment_method_nonce")) or
			   Session::has('bt_express_payment_method_type') == false or 
			   empty(Session::get("bt_express_payment_method_type")))
			{				
				return redirect('checkout');
			}
			
			$is_bt_express_checkout = 1;
			$bt_express_payment_method_nonce = Session::get('bt_express_payment_method_nonce');
			$bt_express_payment_method_type = Session::get('bt_express_payment_method_type');
			
			if(isset($bt_express_payment_method_type) && $bt_express_payment_method_type = "PAYMENT_BRAINTREEPAYPAL")	
			{	
				Session::put('ShoppingCart.payment_method',$bt_express_payment_method_type);	
			}
		}
		
		$bt_api_details = $this->get_Braintree_APIDetails();
		######### For Braintree Express check out End ################
		
		//echo $PName."<br>++++++<br>";
		//exit;
		$PName = 'Test';
		$PName = substr($PName, 1);
		
		//GA4 Google Begin Checkout code Start
		/*$GA4_GOOGLE_BEGIN_CHECKOUT_EVENT_DATA = '';
		$ga4_google_begin_checkout_item_str_gtm = "";
		
		if(isset($Cart) && count($Cart) > 0)
		{
			$gg = 0;
			for($ga=0;$ga<count($Cart);$ga++)
			{
				$oprice = 0;
				$sprice = 0;
				if(isset($Cart[$ga]['sale_price']) && $Cart[$ga]['sale_price'] > 0 && $Cart[$ga]['on_sale'] == 'Yes')
				{
					$oprice = $Cart[$ga]['our_price'];
					$sprice = $Cart[$ga]['sale_price'];
				}
				else
				{
					$oprice = $Cart[$ga]['retail_price'];
					$sprice = $Cart[$ga]['our_price'];
				}
				$ga4_google_begin_checkout_item_str_gtm .= '{
					"item_id": "'.$Cart[$ga]['SKU'].'",
					"item_name": "'.$Cart[$ga]['ProductName'].'",
					"affiliation": "'.config('const.SITE_NAME').'",
					"coupon": "",
					"discount": "",
					"index": "'.$gg.'",
					"item_brand": "'.config('const.SITE_NAME').'",
					"currency": "USD",
					"item_category": "",
					"price": "'.$sprice.'",
					"quantity": "'.$Cart[$ga]['Qty'].'"
				},';
				$gg++;
			}
		}
		$GA4_GOOGLE_BEGIN_CHECKOUT_EVENT_DATA = '
		dataLayer.push({ ecommerce: null }); 
		dataLayer.push({
			event: "begin_checkout",
			ecommerce: {
				"currency": "USD",
				"value": "'.$SubTotal.'",				
				"items": ['.rtrim($ga4_google_begin_checkout_item_str_gtm, ',').']
			}
		});';
		//dd($GA4_GOOGLE_BEGIN_CHECKOUT_EVENT_DATA);
		$this->PageData['GA4_GOOGLE_BEGIN_CHECKOUT_EVENT_DATA'] =  $GA4_GOOGLE_BEGIN_CHECKOUT_EVENT_DATA;*/
		
		//GA4 Google Begin Checkout code End

		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
		}else{
			$curencySymbol = '$';
		}
		$this->PageData['CurencySymbol'] = $curencySymbol;
		
		//$this->PageData['PName'] = $PName;
		$this->PageData['JSFILES'] = ['checkoutnew.js'];
		$this->PageData['Cart'] = $Cart;
		$this->PageData['CartLength'] = $CartLength;
		$this->PageData['TotalItemInCart'] = $TotalItemInCart;
		$this->PageData['SubTotal'] = $SubTotal;
		$this->PageData['ShippingCharge'] = $ShippingCharge;
		$this->PageData['TaxValue'] = $TaxValue;
		$this->PageData['AutoDiscount'] = $AutoDiscount;
		$this->PageData['AutoDiscountLabel'] = $AutoDiscountLabel;
		$this->PageData['QuantityDiscount'] = $QuantityDiscount;
		$this->PageData['isCouponsAvailable'] = $isCouponsAvailable;
		$this->PageData['CouponCode'] = $CouponCode;
		$this->PageData['CouponDiscount'] = $CouponDiscount;
		$this->PageData['NetTotal'] = $NetTotal;
		$this->PageData['msg'] = $msg;
		$this->PageData['Billing'] = $Billing;
		$this->PageData['Shipping'] = $Shipping;
		$this->PageData['IsBillingAsShipping'] = $IsBillingAsShipping;
		$this->PageData['aState'] = $aState;
		$this->PageData['aCountry'] = $aCountry;
		$this->PageData['paymentMethodHtml'] = $paymentMethodHtml;
		$this->PageData['paypalec'] = $paypalec;
		$this->PageData['is_customer_login'] = $is_customer_login;
		$this->PageData['merchandise_subtotal'] = $merchandise_subtotal;
		$this->PageData['is_bt_express_checkout'] = $is_bt_express_checkout;
		$this->PageData['bt_express_payment_method_nonce'] = $bt_express_payment_method_nonce;
		$this->PageData['bt_express_payment_method_type'] = $bt_express_payment_method_type;
		$this->PageData['bt_api_details'] = $bt_api_details;
		$this->PageData['Total_Amount'] = $Total_Amount;
		
		Log::channel('checkout_page')->info($this->PageData);
		
		###################### BREADCRUMBLIST SCHEMA START ####################
        $breadcrumbListSchemaData = getBLSchemaForStaticPages('Checkout');
        if ($breadcrumbListSchemaData != false)
        {
            $this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
        }
        ###################### BREADCRUMBLIST SCHEMA END ####################	
		//dd(Session::get('ShoppingCart'));
		$this->PageData['CSSFILES'] = ['cart.css'];
		
		return view('shoppingcart.checkoutnew')->with($this->PageData);		
	}
	
	public function getPaymentMethods(Request $request)
	{
		$ROW_STR = '';
		
		$NetTotal = $this->getNetTotal();
		
		$Billing  		= $this->getBillingAddress();
		if(!empty($request->bill_country))
		{
			$bill_country 	= $request->bill_country;
		}
		else
		{ 
			$bill_country 	=  $Billing['country'];
		}		
		
		########################################################
		## CHECK BILLING COUNTRY AND SHOW PAYMENT METHODS START
		########################################################
		$arrPaymentDetail = $this->getPaymentDetail();		
	
		$PaymentMethodList = DB::table($this->prefix.'payment_methods')
											->select('*')
											->where('pm_status','=','Active');													
			
		if($request->paypalec == 1)
		{
			$PaymentMethodList = $PaymentMethodList->where('pm_group_name','=','PAYMENT_PAYPALEC');													
		}
		elseif($request->is_bt_express_checkout == 1)
		{
			$bt_express_payment_method_type = Session::get('bt_express_payment_method_type');
			
			$PaymentMethodList = $PaymentMethodList->where('pm_group_name','=',$bt_express_payment_method_type);	
		}
		else if($bill_country=="US")
		{		
			$PaymentMethodList = $PaymentMethodList->whereIn('pm_available',array('US','BOTH'));
		}
		else if($bill_country!="US")
		{			
			$PaymentMethodList = $PaymentMethodList->whereIn('pm_available',array('INTERNATIONAL','BOTH'));
		}	
		
		$PaymentMethodList = $PaymentMethodList->orderBy('pm_position','ASC')->get()->toArray();

		
		$ROW_STR = '';
		$temp_count = 0; // This var used for count availabe method
				
		$p = 0;
		
		if(!empty($PaymentMethodList))
		{
			for($p=0; $p<count($PaymentMethodList); $p++)
			{
				//$p_checked = '';
				$p_checked = 'checked';
				
				if(count($PaymentMethodList)==1)
				{
					$p_checked = 'checked';
				}	
											
				$pmname = $PaymentMethodList[$p]->pm_name;
				//echo "<pre>"; print_r($PaymentMethodList); exit;			
				$cls_pm_display = "d-block"	;
				
				if( $PaymentMethodList[$p]->pm_group_name == 'PAYMENT_BRAINTREEPAYPAL' or
					$PaymentMethodList[$p]->pm_group_name == 'PAYMENT_BRAINTREEGOOGLEPAY' or
					$PaymentMethodList[$p]->pm_group_name == 'PAYMENT_BRAINTREEAPPLEPAY')
				{
					if($request->is_bt_express_checkout !=1)
					{	 
						$cls_pm_display = "d-none";
					}	
				}	 
					
				$ROW_STR .= '
				<div class="checkout-pmact-loop">
					<div class="checkout-pmact-hd '.$cls_pm_display.'">
						<div class="form-check">
							<input class="form-check-input check-input-rd" type="radio" name="paymentMethod" id="paymentMethod'.$p.'" value="'.$PaymentMethodList[$p]->pm_group_name.'" '.$p_checked.' data-pmname="'.$pmname.'">
							<label class="form-check-label" for="credit_cart"> '.$PaymentMethodList[$p]->pm_name.'</label>
						</div>
						<picture><img src="images/card_icon.png" alt="Credit Card" title="Credit Card" width="150" height="24" loading="lazy"/></picture>
					</div>';					
					if( $PaymentMethodList[$p]->pm_group_name == 'PAYMENT_BRAINTREECC')
					{
						$ROW_STR .= '
						<div class="content" style="display:block;">
							<div class="inner">
								<div class="row">
									<div class="col-sm-6 mb-3">
										<label for="cc-name">Card Holder Name</label>
										<div class="form-control" id="cc-name"></div>
										<div class="invalid-feedback">Name on card is required</div>
									</div>
									<div class="col-sm-6 mb-3"></div>
								</div>
								<div class="row">
									<div class="col-sm-6 mb-3">
										<label for="cc-number">Credit Card Number</label>
										<div class="form-control" id="cc-number"></div>
										<div class="invalid-feedback">Credit card number is required</div>
									</div>
									<div class="col-sm-3 mb-3">
										<label for="cc-expiration">Expiration Date</label>
										<div class="form-control" id="cc-expiration"></div>
										<div class="invalid-feedback">Expiration date required</div>
									</div>
									<div class="col-sm-3 mb-3">
										<label for="cc-cvv">CVV</label>
										<div class="form-control" id="cc-cvv"></div>
										<div class="invalid-feedback">Security code required</div>
									</div>
								</div>
							</div>
						</div>';
						$ROW_STR .= '<div class="placeorder-btn"><a href="javascript:void(0);" onclick="valid_payment_detail();" class="btn btn-primary" id="btnPlaceOrder-cc">PLACE ORDER</a></div>';

						$ROW_STR .= '<div class="f11 pl-1 pt-3 pb-2">By clicking Place Order, you agree to our <a href="'.config('const.SITE_URL') .'pages/terms-conditions" class="tcp_link" target="_blank">Terms & Conditions</a> and <a href="'.config('const.SITE_URL') .'pages/privacy-policy"  class="tcp_link" target="_blank">Privacy Policy</a>.</div>';

						 //$ROW_STR .= '</div>';
					}
					else
					{
						//$ROW_STR .= '<div class="bootstrap-basic ind-paymethod-button pt-2"><div class="placeorder-btn"><a href="javascript:void(0);" onclick="valid_payment_detail();" class="btn btn-primary">ORDER CONFIRM</a></div></div>';
					}
					$ROW_STR .= '
				</div>';
			}
		}		
				
		if($p == 0)
		{	
			$ROW_STR .= '<div class="clear errmsg pl10"> Checkout is temporarily unavailable at this time, Please contact administrator. </div>';
		}	
			
		return $ROW_STR .= '<input type="hidden" name="count_paymentmethod" id="count_paymentmethod" value="'.$p.'" />';

		########################################################
		## CHECK BILLING COUNTRY AND SHOW PAYMENT METHODS START
		########################################################

	}
	
	
	public function save_checkoutstep()
	{	
		//echo "<pre>"; print_r($_POST); exit;
		
		if($_POST['sh_fname']!=''){
			/*Session::put('ShoppingCart.ShippingAddress.ship_firstname',$_POST['sh_fname']);
			Session::put('ShoppingCart.ShippingAddress.ship_lastname',$_POST['sh_lname']);
			Session::put('ShoppingCart.ShippingAddress.ship_address1',$_POST['sh_Addr1']);
			Session::put('ShoppingCart.ShippingAddress.ship_address2',$_POST['sh_Addr2']);
			Session::put('ShoppingCart.ShippingAddress.ship_city',$_POST['sh_city']);
			Session::put('ShoppingCart.ShippingAddress.ship_state',$_POST['sh_state']);
			Session::put('ShoppingCart.ShippingAddress.ship_zip',$_POST['sh_zip']);
			Session::put('ShoppingCart.ShippingAddress.ship_phone',$_POST['sh_phone']);
			Session::put('ShoppingCart.ShippingAddress.ship_country',$_POST['sh_country']);
			Session::put('ShoppingCart.ShippingAddress.ship_email',$_POST['bl_sh_email']);
			Session::put('ShoppingCart.is_customer_info','yes');*/
			
			Session::put('ShoppingCart.ShippingAddress.first_name',$_POST['sh_fname']);
			Session::put('ShoppingCart.ShippingAddress.last_name',$_POST['sh_lname']);
			Session::put('ShoppingCart.ShippingAddress.address1',$_POST['sh_Addr1']);
			Session::put('ShoppingCart.ShippingAddress.address2',$_POST['sh_Addr2']);
			Session::put('ShoppingCart.ShippingAddress.city',$_POST['sh_city']);
			Session::put('ShoppingCart.ShippingAddress.state',$_POST['sh_state']);
			Session::put('ShoppingCart.ShippingAddress.zip',$_POST['sh_zip']);
			Session::put('ShoppingCart.ShippingAddress.phone',$_POST['sh_phone']);
			Session::put('ShoppingCart.ShippingAddress.country',$_POST['sh_country']);
			Session::put('ShoppingCart.ShippingAddress.email',$_POST['bl_sh_email']);
			Session::put('ShoppingCart.ShippingAddress.is_newsletter',$_POST['is_newsletter']);
			Session::put('ShoppingCart.is_customer_info','yes');
			//Session::save();
		}
		//dd($_POST);
		/*if((isset($_POST['sh_choose_password']) && $_POST['sh_choose_password']!='') && (isset($_POST['sh_re_password']) && $_POST['sh_re_password']!=''))
		{
			Session::put('ShoppingCart.ShippingAddress.sh_choose_password',$_POST['sh_choose_password']);
			Session::put('ShoppingCart.ShippingAddress.sh_re_password',$_POST['sh_re_password']);
		}*/
		//dd(Session::get('ShoppingCart'));
		/* condition to set billing/shipping session for info on 20 Feb 2019 - Start */
		if($_POST['sh_Addr1']!='')
		{
			Session::put('ShoppingCart.is_billing_info','yes');
			//Session::save();
		}
		
		if($_POST['is_billing_info'] == 'sameasship')
		{
			Session::put('ShoppingCart.is_billing_info','sameasship');
			/*Session::put('ShoppingCart.BillingAddress.bill_firstname',$_POST['sh_fname']);
			Session::put('ShoppingCart.BillingAddress.bill_lastname',$_POST['sh_lname']);
			Session::put('ShoppingCart.BillingAddress.bill_address1',$_POST['sh_Addr1']);
			Session::put('ShoppingCart.BillingAddress.bill_address2',$_POST['sh_Addr2']);
			Session::put('ShoppingCart.BillingAddress.bill_city',$_POST['sh_city']);
			Session::put('ShoppingCart.BillingAddress.bill_state',$_POST['sh_state']);
			Session::put('ShoppingCart.BillingAddress.bill_zip',$_POST['sh_zip']);
			Session::put('ShoppingCart.BillingAddress.bill_phone',$_POST['sh_phone']);
			Session::put('ShoppingCart.BillingAddress.bill_country',$_POST['sh_country']);*/
			
			Session::put('ShoppingCart.BillingAddress.first_name',$_POST['sh_fname']);
			Session::put('ShoppingCart.BillingAddress.last_name',$_POST['sh_lname']);
			Session::put('ShoppingCart.BillingAddress.address1',$_POST['sh_Addr1']);
			Session::put('ShoppingCart.BillingAddress.address2',$_POST['sh_Addr2']);
			Session::put('ShoppingCart.BillingAddress.city',$_POST['sh_city']);
			Session::put('ShoppingCart.BillingAddress.state',$_POST['sh_state']);
			Session::put('ShoppingCart.BillingAddress.zip',$_POST['sh_zip']);
			Session::put('ShoppingCart.BillingAddress.phone',$_POST['sh_phone']);
			Session::put('ShoppingCart.BillingAddress.country',$_POST['sh_country']);
			//Session::save();
			
		}
		elseif($_POST['is_billing_info'] == 'not_sameasship')
		{
			Session::put('ShoppingCart.is_billing_info','not_sameasship');
			Session::put('ShoppingCart.BillingAddress.first_name',$_POST['bl_fname']);
			Session::put('ShoppingCart.BillingAddress.last_name',$_POST['bl_lname']);
			Session::put('ShoppingCart.BillingAddress.address1',$_POST['bl_Addr1']);
			Session::put('ShoppingCart.BillingAddress.address2',$_POST['bl_Addr2']);
			Session::put('ShoppingCart.BillingAddress.city',$_POST['bl_city']);
			Session::put('ShoppingCart.BillingAddress.state',$_POST['bl_state']);
			Session::put('ShoppingCart.BillingAddress.zip',$_POST['bl_zip']);
			Session::put('ShoppingCart.BillingAddress.phone',$_POST['bl_phone']);
			Session::put('ShoppingCart.BillingAddress.country',$_POST['bl_country']);
			//Session::save();
		}
		/* condition to set billing/shipping session for info on 20 Feb 2019 - End */
		Session::put('ShoppingCart.ShippingAsBilling',trim($_POST['BillingAsShipping']));
		
		if(isset($_POST['bt_payment_method_nonce']) && $_POST['bt_payment_method_nonce'] != "")
		{
			Session::put('ShoppingCart.bt_payment_method_nonce',$_POST['bt_payment_method_nonce']);
		}
		
		if(isset($_POST['payment_method']) && $_POST['payment_method'] != "")
		{
			Session::put('ShoppingCart.payment_method',$_POST['payment_method']);
		}	
		
		Session::save();
		echo 1; exit;
	}
	public function getShippingMethods(Request $request)
	{
		$ship_country 	= isset($request->ship_country)?trim($request->ship_country):'';
		$ship_state 	= isset($request->ship_state)?trim($request->ship_state):'';
		$ship_zip  		= isset($request->ship_zip)?trim($request->ship_zip):'';
		
		$ShippingModeRS = DB::table($this->prefix.'shipping_mode')
					->select('*')
					->where('status','=','1')
					->orderBy('display_position','asc')
					->get()->toArray();

		
		$Sess_ShippingInfo = $this->getShippingInfo();
		
		$ROW_STR ='';
		$temp_count = 0; // This var used for count availabe method
		$tempCharge = 0;
		$charge_str = '';
		foreach($ShippingModeRS as $ShippingMode )
		{

			$shipping_mode_id = $this->CheckAvailableShippingMethod($ShippingMode, $ship_country,$ship_state,$ship_zip); 
			if(is_int($shipping_mode_id) == true and $shipping_mode_id > 0) 
			{
				$tempCharge = $this->CalculateAvailableShippingCharge($shipping_mode_id,$ship_country,$ship_state,$ship_zip);
				
				$charge_str = 'Free';
				if($tempCharge>0)
				{					
					$charge_str = config('const.CURRENCY_CODE').$tempCharge;
				}
				elseif($tempCharge==0)
				{
					if(Session::has('ShoppingCart.PromoCoupon.FreeShipping') and Session::get('ShoppingCart.PromoCoupon.FreeShipping') == 'Yes' and Session::has('ShoppingCart.PromoCoupon.FreeShippingModeID') and Session::get('ShoppingCart.PromoCoupon.FreeShippingModeID') == $shipping_mode_id)	
					{
							$charge_str = 'Free';
					}
				}		
				
				if(Session::has('ShoppingCart.PromoCoupon.FreeShippingModeID') and Session::get('ShoppingCart.PromoCoupon.FreeShippingModeID') == $shipping_mode_id)
				{
					$r_sel = " checked ";
				}	
				else if(empty($Sess_ShippingInfo['ShippingModeID']))
				{
					 if($temp_count==0) 
						$r_sel = " checked ";
					else 
						$r_sel = "";	
				}
				else
				{
					if($Sess_ShippingInfo['ShippingModeID']==$ShippingMode->shipping_mode_id)
							$r_sel = " checked ";
					else
							$r_sel = "";		
					
				}	
					
				$ROW_STR .='<div class="ship-time">
							<div class="form-check">
							<input type="radio" name="shippingModeId" id="shippingModeId'.$temp_count.'" value="'.$ShippingMode->shipping_mode_id.'" '.$r_sel.' onclick="Ajax_GetOrder_Summery();"  data-charge="'.$tempCharge.'" data-shipname="'.utf8_decode($ShippingMode->shipping_title).'"  class="form-check-input check-input-rd">
							<label class="form-check-label" for="shippingModeId'.$temp_count.'"> '.utf8_decode($ShippingMode->shipping_title). ($charge_str !== 'Free' ? ' ('.$charge_str.') ' : '').'</label>
						</div>
					</label>';
						
			
				 $temp_count = $temp_count +1; 
			}
			else
			{
				continue;
			}

		}
		
		if(trim($ROW_STR)=='')
		{
			$ROW_STR = '<div class="clear errmsg">There is no shipping method available to your destination based on your country and state or zipcode you entered. <br>Please fill a different shipping address.</div>';
		}
		$ROW_STR .= '<input type="hidden" name="count_shipmethod" id="count_shipmethod" value="'.$temp_count.'" />';
		
		return $ROW_STR;
	}
	
	function checkUserEmail(Request $request)
	{
		$emailExist = 'no';		
		//echo $request->email; exit;
		if(isset($request->email))
		{
			$email 	= trim($request->email);
			
			$customer = Customer::select('customer_id')
				->where('email', $email)
				->where('registration_type','M')
				->where('status','1')
				->first();

			if($customer && $customer->count() > 0) 
			{
				$emailExist = 'yes';
			}
		}
		return $emailExist;
	}
	
	public function OrderConfirm(Request $request)
	{
		
		dd(Session::get('ShoppingCart'));
		//unset(Session::get('ShoppingCart.Cart'));
		//Session::forget('ShoppingCart.Cart');		
		//Session::forget('etype');		
		//Session::save();
		$temp_ary = array();
		$temp_ary['product_id']   		= 1111;
		$temp_ary['SKU']         		= 'ABC123';
		$temp_ary['ProductName'] 		= 'Test123';
		$temp_ary['short_description'] 	= 'ABC123';
		$temp_ary['is_sale'] 			= '11';
		$ProductName_description 		= $temp_ary['short_description'];

		if (strlen($ProductName_description) > 34)
			$temp_ary['ProductName_description'] = substr($ProductName_description, 0, 34) . '...';
		else
			$temp_ary['ProductName_description'] = $ProductName_description;

		$temp_ary['ItemPrice'] = NumberFormat(400);

		## set check out process price
		$temp_ary['Price']       	= NumberFormat(300);
		$temp_ary['Qty'] 		 	= 2;
		$temp_ary['TotPrice']    	= NumberFormat(300 * 2);
		$temp_ary['oldTotPrice']    = NumberFormat(400 * 2);
		$temp_ary['retail_price'] 	= 400;
		$temp_ary['shipping_price'] = NumberFormat(15);
		$temp_ary['total_shipping_price'] = NumberFormat(15 * 2);

		$temp_ary['Image']       	= 'https://placehold.co/100x100/EEE/31343C';
		$temp_ary['product_url']    = 'Product URL';


		
		
		
		if ($temp_ary['Price'] <= 0) {
			return response()->json(array('Added' => 0));
		}

		//dd($temp_ary);
		$Cart = Session::get('ShoppingCart.Cart');

		$this->CalculateSubTotal();
		
		if ($Cart && count($Cart) > 0)
			$Cart = array_values($Cart);
		
		$Cart[] = $temp_ary;

		//dd($Cart);
		Session::put('ShoppingCart.Cart', $Cart);



		/*$bt_payment_method_nonce = "";
		Session::put('ShoppingCart.bt_payment_method_nonce', ""); 
		if($request['bt_payment_method_nonce'] != "")
		{
			$bt_payment_method_nonce = $request['bt_payment_method_nonce'];
			Session::put('ShoppingCart.bt_payment_method_nonce', $bt_payment_method_nonce); 
		}*/
		
		//$bt_payment_method_nonce = Session::get('ShoppingCart.bt_payment_method_nonce');
		$fnameVal = Session::get('ShoppingCart.ShippingAddress.first_name');
		$lnameVal = Session::get('ShoppingCart.ShippingAddress.last_name');
		$add1Val = Session::get('ShoppingCart.ShippingAddress.address1');
		$cityVal = Session::get('ShoppingCart.ShippingAddress.city');
		$zipVal = Session::get('ShoppingCart.ShippingAddress.zip');
		$phoneVal = Session::get('ShoppingCart.ShippingAddress.phone');
		
		//dd(Session::get('ShoppingCart'));
		/*if($fnameVal == '' || $lnameVal == '' || $add1Val == '' || $cityVal == '' || $zipVal == '' || $phoneVal == '')
		{
			return redirect('/checkout')->with('error_msg', 'Please add shipping information properly');
		}*/
		
		
		
		$cart_data = Session::get('ShoppingCart.Cart');
		//dd($cart_data);
		$this->PageData['CSSFILES'] = ['cart.css'];
		$this->PageData['JSFILES'] = ['OrderConfirm.js'];
		
		
		if(Session::get('ShoppingCart.Cart') != null)
		{
			$Cart = Session::get('ShoppingCart');
			
			$this->PageData['cart_data'] = $Cart['Cart'];
			
			//GA4 Google add payment info code Start
			/*$GA4_GOOGLE_ADD_PAYMENT_INFO_EVENT_DATA = '';
			$ga4_google_add_payment_info_item_str_gtm = "";
			
			if(isset($cart_data) && count($cart_data) > 0)
			{
				$gg = 0;
				for($ga=0;$ga<count($cart_data);$ga++)
				{
					$ga4_google_add_payment_info_item_str_gtm .= '{
						"item_id": "'.$cart_data[$ga]['SKU'].'",
						"item_name": "'.$cart_data[$ga]['ProductName'].'",
						"affiliation": "'.config('const.SITE_NAME').'",
						"coupon": "",
						"discount": "",
						"index": "'.$gg.'",
						"item_brand": "'.config('const.SITE_NAME').'",
						"currency": "USD",
						"item_category": "",
						"price": "'.$cart_data[$ga]['Price'].'",
						"quantity": "'.$cart_data[$ga]['Qty'].'"
					},';
					$gg++;
				}
			}
			$GA4_GOOGLE_ADD_PAYMENT_INFO_EVENT_DATA = '
			dataLayer.push({ ecommerce: null }); 
			dataLayer.push({
				event: "add_payment_info",
				ecommerce: {
					"currency": "USD",
					"value": "'.$Cart['SubTotal'].'",	
					"payment_type": "Credit Card",
					"items": ['.rtrim($ga4_google_add_payment_info_item_str_gtm, ',').']
				}
			});';
			//dd($GA4_GOOGLE_ADD_PAYMENT_INFO_EVENT_DATA);
			$this->PageData['GA4_GOOGLE_ADD_PAYMENT_INFO_EVENT_DATA'] =  $GA4_GOOGLE_ADD_PAYMENT_INFO_EVENT_DATA;*/
			//GA4 Google add payment info code End
			
			/*if($Cart['payment_method'] == '')
			{
				return view('shoppingcart.checkoutnew')->with($this->PageData);	
			}*/
			//$this->PageData['title'] = "Order Information :: ".config('const.META_TITLE');
			//dd(config('const')); exit;
			$this->PageData['meta_title'] = "Order Information - ".config('const.META_TITLE');
			$this->PageData['meta_keywords'] = "Order Information ".config('const.META_KEYWORDS');
			$this->PageData['meta_description'] = "Order Information ".config('const.META_DESCRIPTION');
			
			//dd($Cart);
			$this->PageData['SubTotal'] 			= $Cart['SubTotal'];
			$this->PageData['SalesTax'] 			= 15;
			//$this->PageData['SalesTax'] 			= $Cart['Tax'];
			$this->PageData['TotalItemInCart'] 		= $Cart['TotalItemInCart'];
			$this->PageData['AutoQuantityDiscount'] = $Cart['AutoQuantityDiscount'];
			$this->PageData['QuantityDiscount'] 	= $Cart['QuantityDiscount'];
			$this->PageData['AutoDiscount'] 		= $Cart['AutoDiscount'];
			$this->PageData['QuantityDiscount'] 	= $Cart['QuantityDiscount'];
			$this->PageData['TotalQty'] 			= $Cart['TotalItemInCart'];
			$this->PageData['CouponDiscount'] 		= Session::get('ShoppingCart.CouponDiscount');
			$this->PageData['Total_Amount'] 		= $this->getNetTotal();
			
			/*$this->PageData['ship_firstname'] 		= $Cart['ShippingAddress']['first_name'];
			$this->PageData['ship_lastname'] 		= $Cart['ShippingAddress']['last_name'];
			$this->PageData['ship_company'] 		= $Cart['ShippingAddress']['company'];
			$this->PageData['ship_address1'] 		= $Cart['ShippingAddress']['address1'];
			$this->PageData['ship_address2'] 		= $Cart['ShippingAddress']['address2'];
			$this->PageData['ship_city'] 			= $Cart['ShippingAddress']['city'];
			$this->PageData['ship_state'] 			= $Cart['ShippingAddress']['state'];
			$this->PageData['ship_zip'] 			= $Cart['ShippingAddress']['zip'];
			$this->PageData['ship_phone'] 			= $Cart['ShippingAddress']['phone'];
			$this->PageData['ship_email'] 			= $Cart['ShippingAddress']['email'];
			$this->PageData['ship_addressbookid'] 	= $Cart['ShippingAddress']['AddressBookId'];
			$this->PageData['ship_country'] 		= $Cart['ShippingAddress']['country'];

			$this->PageData['bill_firstname'] 		= $Cart['BillingAddress']['first_name'];
			$this->PageData['bill_lastname'] 		= $Cart['BillingAddress']['last_name'];
			$this->PageData['bill_company'] 		= $Cart['BillingAddress']['company'];
			$this->PageData['bill_address1'] 		= $Cart['BillingAddress']['address1'];
			$this->PageData['bill_address2'] 		= $Cart['BillingAddress']['address2'];
			$this->PageData['bill_city'] 			= $Cart['BillingAddress']['city'];
			$this->PageData['bill_state'] 			= $Cart['BillingAddress']['state'];
			$this->PageData['bill_zip'] 			= $Cart['BillingAddress']['zip'];
			$this->PageData['bill_phone'] 			= $Cart['BillingAddress']['phone'];
			$this->PageData['bill_addressbookid'] 	= $Cart['BillingAddress']['AddressBookId'];
			$this->PageData['bill_country'] 		= $Cart['BillingAddress']['country'];
			$this->PageData['BillingAsShipping'] 	= $Cart['BillingAsShipping'];
			$this->PageData['payment_method'] 		= $Cart['payment_method'];
			//$this->PageData['bill_email'] 		= $Cart['BillingAddress']['email'];*/
			
			$this->PageData['ship_firstname'] 		= 'fQualdev';
			$this->PageData['ship_lastname'] 		= 'lQualdev';
			$this->PageData['ship_company'] 		= 'Qualdev';
			$this->PageData['ship_address1'] 		= '179 B Old South Path';
			$this->PageData['ship_address2'] 		= '';
			$this->PageData['ship_city'] 			= 'Mellville';
			$this->PageData['ship_state'] 			= 'NY';
			$this->PageData['ship_zip'] 			= '11747';
			$this->PageData['ship_phone'] 			= '1234567890';
			$this->PageData['ship_email'] 			= 'gequaldev@gmail.com';
			$this->PageData['ship_addressbookid'] 	= '1';
			$this->PageData['ship_country'] 		= 'US';

			$this->PageData['bill_firstname'] 		= 'fQualdev';
			$this->PageData['bill_lastname'] 		= 'lQualdev';
			$this->PageData['bill_company'] 		= 'Qualdev';
			$this->PageData['bill_address1'] 		= '179B South Path';
			$this->PageData['bill_address2'] 		= '';
			$this->PageData['bill_city'] 			= 'Mellville';
			$this->PageData['bill_state'] 			= 'NY';
			$this->PageData['bill_zip'] 			= '11747';
			$this->PageData['bill_phone'] 			= '1234567800';
			$this->PageData['bill_addressbookid'] 	= '1';
			$this->PageData['bill_country'] 		= 'US';
			$this->PageData['BillingAsShipping'] 	= 'Yes';
			$this->PageData['payment_method'] 		= 'CC';
			//$this->PageData['bill_email'] 		= $Cart['BillingAddress']['email'];

			return view('shoppingcart.OrderConfirm')->with($this->PageData);

			/*if($flag == "true"){
				session()->flash('error_msg', $msg);
				return redirect()->back();	
			}else{
				return view('shoppingcart.OrderConfirm')->with($this->PageData);
			}*/

			/*$ShipMode = DB::table($this->prefix.'shipping_mode')
								->where('ishipmethod_id','=',Session::get('shoppingcart.cart.ShippingMethodID'))	
								->get();
			$ShippingMethodName = $ShipMode[0]->vtype;
			
			Session::put('shoppingcart.cart.ShippingMethodName',$ShippingMethodName);*/
			
			//return view('order_confirm',compact('CartDetails','Cart','ShippingMethodName'))->with($this->PageData);	
				
		} else {
			return redirect('/');
		}
	}
	function apply_quantity_discount()
	{
		if (Session::has('ShoppingCart.QuantityDiscount')) {
			Session::forget('ShoppingCart.QuantityDiscount');
		}

		$qty_discount = 0;
		$SubTotal = Session::get('ShoppingCart.SubTotal');
		$TotalQty = Session::get('ShoppingCart.TotalItemInCart');
		//dd(Session::get('ShoppingCart'));
		if ($SubTotal > 0) {
			$select = 'quantity, quantity_discount_amount, type';
			$getQtyDiscount = DB::table($this->prefix . 'quantity_discount')
				->where('status', '1')
				->where('start_date', '<=', DB::raw('curdate()'))
				->where('end_date', '>=', DB::raw('curdate()'))
				->where('quantity', '<=', $TotalQty)
				->select(DB::raw($select))->get();
			if (count($getQtyDiscount) > 0) {
				$result = json_decode(json_encode($getQtyDiscount), true);
				$qty = $result[0]['quantity'];
				$qty_discount_amount = $result[0]['quantity_discount_amount'];
				$type = $result[0]['type'];
				if ($type == '0') {	// amount discount
					$qty_discount = $auto_discount_amount;
				} else if ($type == '1') { // discount in percentage
					$qty_discount = Make_Price(($SubTotal * $qty_discount_amount) / 100);
				}
			}
		}
		Session::put('ShoppingCart.QuantityDiscount', Make_Price($qty_discount));
	}
	function apply_auto_discount()
	{
		//check amount discount
		if (Session::has('ShoppingCart.AutoDiscount')) {
			Session::forget('ShoppingCart.AutoDiscount');
		}
		$AutoDiscount = 0;
		$SubTotal = Session::get('ShoppingCart.SubTotal');
		if ($SubTotal > 0) {
			$select = 'order_amount, auto_discount_amount, type';
			$getAutoDiscount = DB::table($this->prefix . 'auto_discount')
				->where('status', '1')
				->where('start_date', '<=', DB::raw('curdate()'))
				->where('end_date', '>=', DB::raw('curdate()'))
				->where('order_amount', '<=', $SubTotal)
				->select(DB::raw($select))->get();

			if (count($getAutoDiscount) > 0) {
				$result = json_decode(json_encode($getAutoDiscount), true);
				$order_amount = $result[0]['order_amount'];
				$auto_discount_amount = $result[0]['auto_discount_amount'];
				$type = $result[0]['type'];
				if ($type == '0') {	// amount discount
					$AutoDiscount = $auto_discount_amount;
				} else if ($type == '1') { // discount in percentage
					$AutoDiscount = Make_Price(($SubTotal * $auto_discount_amount) / 100);
				}
			}
		}
		Session::put('ShoppingCart.AutoDiscount', Make_Price($AutoDiscount));
	}
	public function CalculateSubTotal()
	{
		$cart_data = Session::get('ShoppingCart.Cart');
		//dd($cart_data);
		$SubTotal = 0;
		$TotalQty = 0;
		if (!empty($cart_data)) {
			for ($i = 0; $i < count($cart_data); $i++) {
				if (isset($cart_data[$i]['TotPrice'])) {
					$SubTotal = $SubTotal + $cart_data[$i]['TotPrice'];
				}
				if (isset($cart_data[$i]['Qty'])) {
					$TotalQty = $TotalQty + $cart_data[$i]['Qty'];
				}
			}
		}

		Session::put('ShoppingCart.SubTotal', Make_Price($SubTotal));
		Session::put('ShoppingCart.TotalItemInCart', $TotalQty);
		$this->apply_quantity_discount();
		$this->apply_auto_discount();
		//dd(Session::get('ShoppingCart'));
		$AutoQuantityDiscount = Session::get('ShoppingCart.AutoDiscount') + Session::get('ShoppingCart.QuantityDiscount');
		Session::put('ShoppingCart.AutoQuantityDiscount', Make_Price($AutoQuantityDiscount));

		//dd($AutoQuantityDiscount);
	}
	// Added code for Avalara Tax as on 25-10-2022 Start

	public function checkoutOrderSummaryAjax(Request $request)
	{
		
		$ship_addr_1        = $request->ship_addr_1;
		$ship_addr_2        = $request->ship_addr_2;
		$ship_city			= $request->ship_city;
		$ship_state 		= $request->ship_state;
		$ship_zip  			= $request->ship_zip;
		$ship_country 		= $request->ship_country;
		
		$shipping_mode_id 	= $request->shipping_mode_id;		
		$ShippingMethodName	= $request->ShippingMethodName;
		
		$ShippingCharge		= $request->ShippingCharge;
		$PaymentMethod		= $request->paymentMethod;

		$checking 			= $request->checking;
		
		########################################################
		## CODE FOR Shipping And Tax Calclulation
		########################################################

		$this->setShippingCharge($ShippingCharge);
		
		Session::put('ShoppingCart.Shipping.ShippingMethodName', $ShippingMethodName);	
		Session::put('ShoppingCart.Shipping.ShippingModeID',  $shipping_mode_id);	
		Session::save();		
		
		if((trim($ship_state) != '') && (trim($ship_country) != '') && (trim($ship_zip) != ''))
		{
			$avaTax = $this->TaxCalculation($ship_country, $ship_state, $ship_zip);
			//$avaTax = $this->avalaraTaxCalculation($ship_addr_1,$ship_addr_2,$ship_city,$ship_zip,$ship_state,$ship_country,null,'no');
		}
		else
		{
			$avaTax = 0;
		}
		
		//$avaTax = 0;
		$this->setTaxValue($avaTax);
		$this->setTaxRateTypeValue("percent");
		//$Tax = $this->TaxCalculation($ship_country, $ship_state, $ship_zip);
		
		## Here Assing Cart Variable
		$SubTotal = Session::get('ShoppingCart.SubTotal');

		$AutoQuantityDiscount = Session::get('ShoppingCart.AutoQuantityDiscount');
		
		$customer_id = '';
		if(Session::has('customer_id'))
			$customer_id = Session::get('customer_id');
		
		$AutoDiscount = Session::get('ShoppingCart.AutoDiscount');

		$QuantityDiscount = Session::get('ShoppingCart.QuantityDiscount');

		$CouponDiscount = Session::get('ShoppingCart.CouponDiscount');

		//$Total_Amount = $this->CalculateNetTotal();

		$Total_Amount = $this->getNetTotal();

		$merchandise_subtotal = '0.00';
		$merchandise_subtotal = $SubTotal - ($AutoDiscount + $QuantityDiscount + $CouponDiscount);
		
		## Here Check If need to Payment Method or Not
		$IS_PAYMENT_VIA_GC = 'No';
		
		$TaxValue = $avaTax;

		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$CurencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
		}else{
			$CurencySymbol = '$';
		}
		
		return View('shoppingcart.checkout-order-summary',compact('SubTotal', 'ShippingCharge', 'TaxValue','AutoQuantityDiscount','AutoDiscount','QuantityDiscount','CouponDiscount','Total_Amount','IS_PAYMENT_VIA_GC','merchandise_subtotal','CurencySymbol'));
	}
	
	// Added code for Avalara Tax as on 25-10-2022 End
	
	## User Sign Up
	##--------------------------------
	function userSignUp(Request $request)
	{
		
		$sucessfullySignUp = 'no';
		
		if(isset($request['email']))
		{
			$ChkEmail = Customer::where('email','=',$request->email)->where('registration_type','=','M')->first();
			
			if($ChkEmail)
			{
				$sucessfullySignUp = 'exist';
			}
			else 
			{
				// check if customer has guest account then convert to member
				$check_guest_cust = DB::table($this->prefix.'customer')
				->select('customer_id', 'first_name', 'last_name', 'email', 'registration_type')
				->where('registration_type','=','G')
				->where('email','=',trim($request->email))
				->first();	
				
				if(!empty($check_guest_cust))
				{
					
					$customer_id = $check_guest_cust->customer_id;
					$UserData = array(
						'email' 			=> $request['email'],
						'password' 			=> md5($request['password']),
						'status' 			=> '1',
						'registration_type' => 'M',
						//'reg_datetime' 	=> date('Y-m-d H:i:s'),
						'upd_datetime' 		=> date('Y-m-d H:i:s'),
						'customer_ip'  		=> $request->ip(),
						'customer_browser'	=> $request->header('User-Agent'),
					);
					$affected = DB::table($this->prefix.'customer')
								  ->where('customer_id', '=', $customer_id)
								  ->update($UserData);
								  
					$check_guest_cust->registration_type = 'M';
					
					$User = Customer::select('customer_id', 'first_name', 'last_name', 'email', 'registration_type')
							->where('customer_id', $customer_id)
							->first();
				}
				else
				{
					$UserData = array(
						'first_name' 		=> $request['firstname'],
						'last_name' 		=> $request['lastname'],
						'phone' 			=> $request['phone'],
						'email' 			=> $request['email'],
						'password' 			=> md5($request['password']),
						'status' 			=> '1',
						'reg_datetime' 		=> date('Y-m-d H:i:s'),
						'registration_type' => 'M',
						'site_type' => 'B2C',
						'customer_ip'  		=> $request->ip(),
						'customer_browser'	=> $request->header('User-Agent'),
					);
					//dd($UserData);
					$User = Customer::create($UserData);
					$LastClientID = $User->customer_id;
				}	

				if($User)
				{
					$sucessfullySignUp = 'yes';
					$remember_me = false; 
					//$request->session()->regenerate();
					Session::put('customer_id',$User->customer_id);
					Session::put('sess_icustomerid',$User->customer_id);
					Session::put('customer_email',$User->email);
					Session::put('customer_first_name',$User->first_name);
					Session::put('sess_first_name',$User->first_name);
					Session::put('customer_last_name',$User->last_name);
					Session::put('etype',$User->registration_type);
					Session::put('is_login',1);
					Session::put('customer_type','Normal');
					Auth::login($User,$remember_me);
					
					$EmailBody = '';
					//$Template = GetMailTemplate("CUSTOMER_REGISTER");
					$Template = GetMailTemplate("CUSTOMER_REGISTER_NEW");
					//echo $Template; exit;
					$EmailBody = str_replace('{$vFirstName}', $User->first_name, $Template[0]->mail_body);
					$EmailBody = str_replace('{$SITE_URL}', config('const.SITE_URL'), $EmailBody);
					$EmailBody = str_replace('{$Site_URL}', config('const.SITE_URL'), $EmailBody);
					$EmailBody = str_replace('{$vLastName}', $User->last_name, $EmailBody);
					$EmailBody = str_replace('{$vemail}', $User->email, $EmailBody);
					$EmailBody = str_replace('{$password}', $request['password'], $EmailBody);
					$EmailBody = str_replace('{$COUPON_CODE_VALUE}', config('Settings.COUPON_CODE_VALUE'), $EmailBody);
					$EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);
					$FreeShipping = "";
					$EmailBody = str_replace('{$freeshippinginfo}', $FreeShipping, $EmailBody);
					
					
					//$EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
					$EmailBody = view('email_templates.content_register')->with(compact('EmailBody'))->render();
					//echo $EmailBody; exit;
					//$To = $ChkEmail[0]->email;
					$To = $User->email;
					$Subject = $Template[0]->subject;
					$From = config('Settings.CONTACT_MAIL');
					//echo $EmailBody; exit;
					$sendMailStatus = $this->sendSMTPMail($To, $Subject, $EmailBody, $From );
					
					$headers  = "From: " . strip_tags($From) . "\r\n";
					$headers .= "Reply-To: " . strip_tags($From) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					$sendMailStatus = $this->sendSMTPMail_Normal($To, $Subject, $EmailBody, $headers);
				}
			}
		}
		return $sucessfullySignUp;
	}
	
	
}