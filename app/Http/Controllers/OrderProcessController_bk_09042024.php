<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\ShoppingCartTrait;
use App\Http\Controllers\Traits\ProductsTrait;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Redirect;
use GlobalHelper;


class OrderProcessController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	use generalTrait;     
	use ShoppingCartTrait;     
    
	public function __construct()
    {
        $this->current_url = URL::full();
        $this->prefix = config('const.DB_TABLE_PREFIX');
    }
	
	public function index(Request $request) 
	{	
		if($this->getTotalItemInCart() <= 0){
			$a = $this->destroyCart();
			return redirect('shoppingcart');
		}
		
		
		#### OverWrite Billing Address Request Var With Shipping Address - Added 13-apr-2022
		if($request->same_asship == 'on'){
				$request->bl_fname		= $request->sh_fname;
				$request->bl_lname 		= $request->sh_lname;
				$request->bl_Addr1 		= $request->sh_Addr1;
				$request->bl_Addr2 		= $request->sh_Addr2;
				$request->bl_city 		= $request->sh_city;
				$request->bl_country 	= $request->sh_country;
				$request->bl_state 		= $request->sh_state;
				$request->bl_zip 		= $request->sh_zip;
				$request->bl_phone 		= $request->sh_phone;
		}	

		#### If customer want to check out as a guest....start ############		
		if(!Auth::user()){ 

			$CustomerAdd = array (	
									'first_name'		=> stripslashes($request->bl_fname),
									'last_name' 		=> stripslashes($request->bl_lname),
									'address1' 			=> stripslashes($request->bl_Addr1),
									'address2' 			=> stripslashes($request->bl_Addr2),
									'city' 				=> stripslashes($request->bl_city),
									'state' 			=> $request->bl_state,
									'country' 			=> $request->bl_country,
									'zip' 				=> $request->bl_zip,
									'phone' 			=> $request->bl_phone,
									'email' 			=> $request->bl_sh_email,
									'registration_type' => 'G',
									'status' 			=> '1',
									'customer_ip' 		=> $request->ip(),		
									'customer_browser' 	=> $request->header('User-Agent'),
									'upd_datetime' 		=> date('Y-m-d H:i:s')				
					);
			
					
			// Check If Customer Has Member Account					
			$check_cust = DB::table($this->prefix.'customer')
										->select('customer_id','registration_type')
										->where('email',trim($request->bl_sh_email))
										->first();					
			
			// Check If Customer Has Guest Account					
			if(empty($check_cust)){	
				$check_cust = DB::table($this->prefix.'customer')
										->select('customer_id','registration_type')
										->where('registration_type','=','G')
										->where('email','=',trim($request->bl_sh_email))
										->first();					
			}

			if(!empty($check_cust)){
				
				$customer_id = $check_cust->customer_id;			
						
				Session::put('customer_id', $customer_id);
				Session::put('customer_email', $request->bl_sh_email);
				Session::put('etype', 'G');
				Session::save();
				
				$CustomerAdd['registration_type'] = $check_cust->registration_type == 'M' ? 'M' : 'G';
				
				$affected = DB::table($this->prefix.'customer')
								  ->where('customer_id', '=', $customer_id)
								  ->update($CustomerAdd);
				
			} else {	
				$customer_id = DB::table($this->prefix .'customer')->insertGetId($CustomerAdd);			
						
				Session::put('customer_id', $customer_id);
				Session::put('customer_email', $request->bl_sh_email);
				Session::put('etype', 'G');
				Session::save();
			}
		} else if(Auth::user() && Session::has('customer_id') && !empty(Session::get('customer_id'))){
			$customer_id = (int)Session::get('customer_id');
			
			$check_cust = DB::table($this->prefix.'customer')
										->select('customer_id')
										->where('registration_type','=','M')
										->where('customer_id','=',Session::get('customer_id'))
										->whereNotNull('first_name')
										->whereNotNull('last_name')
										->whereNotNull('address1')
										->whereNotNull('city')
										->whereNotNull('state')
										->first();	
											
			if(!empty($check_cust)){				
				$custDataAry = array (	
										'first_name' 		=> stripslashes($request->bl_fname),
										'last_name' 		=> stripslashes($request->bl_lname),
										'address1' 			=> stripslashes($request->bl_Addr1),
										'address2' 			=> stripslashes($request->bl_Addr2),
										'city' 				=> stripslashes($request->bl_city),
										'state' 			=> $request->bl_state,
										'country' 			=> $request->bl_country,
										'zip' 				=> $request->bl_zip,
										'phone' 			=> $request->bl_phone,	
										'upd_datetime' 		=> date('Y-m-d H:i:s')		
									);				
				$affected = DB::table($this->prefix.'customer')
								  ->where('customer_id', '=', Session::get('customer_id'))
								  ->update($custDataAry);
			}
		}else{
			return redirect('login');
		}	

		####  If customer want to check out as a guest....End ######## 


		$this->setShippingAddress($request);
		
		if($request->same_asship == 'on')
		{
			$this->setBillingAsShipping('Yes');
		}
		else
		{
			$this->setBillingAsShipping('No');
		}
		$this->setBillingAddress($request);

		$tmp = $this->setPaymentDetail($request);
		
		if($request->is_newsletter == 'on' && trim($request->bl_sh_email)!=''){
			$check_news = DB::table($this->prefix.'news_letter')->select('news_letter_id')->where('email','=',trim($request->bl_sh_email))->first();
			if(empty($check_news)){
				$arrInsert = array( 
					'email' 	=> trim($request->bl_sh_email), 
					'status'	=> '1',
					'customer_ip'	=> $request->ip()
				);
				$NewsId = DB::table($this->prefix .'news_letter')->insertGetId($arrInsert);			
			}
		}

		$Billing  = $this->getBillingAddress();

		$ShippingInfo 	 = $this->getShippingInfo();
		$ShippingAddress = $this->getShippingAddress();

		if($ShippingInfo['ShippingModeID'] == '' || trim($request->shippingModeId) != '' )
		{
			$shipping_mode_id = (int)$request->shippingModeId;
		}	
		else	
		{
			$shipping_mode_id = $ShippingInfo['ShippingModeID'];
		}		

		$ship_country 	= trim($ShippingAddress['country']);
		$ship_state     = trim($ShippingAddress['state']);
		$ship_zip 	 	= trim($ShippingAddress['zip']);

		$ShippingModeRS = DB::table($this->prefix.'shipping_mode')
										->select('shipping_mode_id','shipping_title')
										->where('status','=','1')
										->where('shipping_mode_id','=',$shipping_mode_id)
										->first();
										

		$shipping_mode_id = $this->CheckAvailableShippingMethod($ShippingModeRS, $ship_country, $ship_state,$ship_zip);
		
		if(is_int($shipping_mode_id) == false && $shipping_mode_id <= 0)
		{
			$msg = "There is no shipping method available to your destination based on your country and state or zipcode you entered. Please fill a different shipping address.";
			return redirect('checkout')->with('msg',$msg);	
		}

		########################################################################
		// For Braintree Check Payment Method Nonce and save in Session Start //
		########################################################################
		
		if(Session::has('bt_payment_method_nonce'))
		{
			Session::forget('bt_payment_method_nonce');
		}
		
		
		if($request->paymentMethod == "PAYMENT_BRAINTREECC" or
		   $request->paymentMethod == "PAYMENT_BRAINTREEPAYPAL"	or
		   $request->paymentMethod == "PAYMENT_BRAINTREEGOOGLEPAY" or
		   $request->paymentMethod == "PAYMENT_BRAINTREEAPPLEPAY")	
		{
			
			if(trim($request->bt_payment_method_nonce) =='')
			{
				$msg = 'Error in Processing Request Please try again. Payment nonce not created.';	
				return redirect('checkout')->with('msg',$msg);
			}

			Session::put('bt_payment_method_nonce', $request->bt_payment_method_nonce);
			Session::save();
		}	
		
		########################################################################
		// For Braintree Check Payment Method Nonce and save in Session END //
		########################################################################
		if( $request->paymentMethod == "PAYMENT_PAYPALEC" && $request->paypalec == 0)
		{
			return redirect('paypal-express-checkout')->with('paypalec', $request->paypalec);
		} 
		else
		{	
			return redirect('order-process');
		}
	}

	public function orderProcess(Request $request){

		if($this->getTotalItemInCart() <= 0)
		{
			$a = $this->destroyCart();
			return redirect('shoppingcart');
		}
		
		if(Session::has('customer_id') == false || empty(Session::get('customer_id')) )	
		{
			$a = $this->destroyCart();
			return redirect('shoppingcart');
		}

		$arrPaymentDetail = $this->getPaymentDetail();
		
		if($arrPaymentDetail['Payment_Type'] == 'PAYMENT_PAYPALEC')
		{
			if (!empty($request->token))	
			{	
				Session::forget('PayPalToken');			
				Session::put('PayPalToken',$request->token);
				Session::put('PayPalPayerID',$request->PayerID);
				Session::save();			
			}
			elseif(!empty($request->L_ERRORCODE0))
			{				
				return redirect('checkout')->with('msg',$request->L_LONGMESSAGE0);
			}
		}
		
		if(empty($arrPaymentDetail['Payment_Type']) || $arrPaymentDetail['Payment_Type']=='undefined'){
			return redirect('checkout')->with('msg','Something went wrong with the payment process, can you please try again. ');
		}
		
		$customer_id = Session::get('customer_id');
		echo "<div align='center' style='color:#FF0000;'><h1>Please wait while your transaction is being processed.<br>Do not refresh the page.</h1></div>";
	
		### Inster into order tabel start ######
		$checkout_type = Session::has('etype') && Session::get('etype') == 'M' ? 'M' : 'G';
		
		$currency_info 	= config('global.CURRENCY_CODE');
		
		$BillingAdd  	= $this->getBillingAddress();
		$ShippingAdd 	= $this->getShippingAddress();
		$ShippingInfo	= $this->getShippingInfo();

		$CouponCode 	= $this->getCouponCode();

		
		$order_status	= 'Pending';
		$orderedfrom 	= 'desktop';

		/*$agent = new Agent();
		
		if($agent->isMobile() == 1 || $agent->isTablet() == 1 || $agent->isPhone() == 1 )
		{
			$orderedfrom = 'phone';
		}*/
		
		$customer_type = '';
		if(Session::has('customer_type'))
		{
			$customer_type = Session::get('customer_type');
		}
		
		$paid_status = 'Unpaid';
		if((trim(strtolower($customer_type)) == 'memo') || ($this->getNetTotal() <= 0))
		{
			$paid_status = 'Paid';
		}

		
		$OrderInsert = array ( 
			'order_datetime'		=> date("Y-m-d H:i:s"),
			'customer_id'			=> $customer_id,
			'sub_total' 			=> $this->getSubTotal(),
			'shipping_amt' 			=> $this->getShippingCharge(),
			'tax' 					=> $this->getTaxValue(),
			//'tax_rate'			=> $this->getTaxRateValue(),
			//'tax_rate_type' 		=> $this->getTaxRateTypeValue(),
			'auto_discount' 		=> $this->getAutoDiscount(),
			//'auto_discount_label' 	=> $this->getAutoDiscountLabel(),
			'quantity_discount'		=> $this->getQuantityDiscount(),
			'coupon_amount' 		=> $this->getCouponDiscount(),
			'order_total' 			=> $this->getNetTotal(),
			'payment_type' 			=> $arrPaymentDetail['Payment_Type'],
			'payment_method' 		=> $arrPaymentDetail['Payment_Method'],
			'pay_status' 			=> $paid_status,
			'status'				=> $order_status,//'Pending',
			'ccinfo' 				=> "",
			'currency_info'			=> $currency_info,
			'checkout_type' 		=> $checkout_type,
			'bill_first_name' 		=> $BillingAdd['first_name'],
			'bill_last_name' 		=> $BillingAdd['last_name'],
			'bill_company' 			=> $BillingAdd['company'],
			'bill_address1' 		=> $BillingAdd['address1'],
			'bill_address2' 		=> $BillingAdd['address2'],
			'bill_city' 			=> $BillingAdd['city'],
			'bill_zip' 				=> $BillingAdd['zip'],
			'bill_state' 			=> $BillingAdd['state'],
			'bill_country' 			=> $BillingAdd['country'],
			'bill_phone' 			=> $BillingAdd['phone'],
			'bill_email' 			=> $BillingAdd['email'],
			'shipping_information' 	=> $ShippingInfo['ShippingMethodName'],								
			'ship_first_name' 		=> $ShippingAdd['first_name'],
			'ship_last_name' 		=> $ShippingAdd['last_name'],
			'ship_company' 			=> $ShippingAdd['company'],
			'ship_address1' 		=> $ShippingAdd['address1'],
			'ship_address2' 		=> $ShippingAdd['address2'],
			'ship_city' 			=> $ShippingAdd['city'],
			'ship_zip' 				=> $ShippingAdd['zip'],
			'ship_state' 			=> $ShippingAdd['state'],
			'ship_country' 			=> $ShippingAdd['country'],
			'ship_phone' 			=> $ShippingAdd['phone'],
			'ship_email' 			=> $ShippingAdd['email'],
			'customer_ip' 			=> $request->ip(),
			'customer_browser' 		=> $request->header('User-Agent'),	
			'orderedfrom'			=> $orderedfrom,
			'ordersessionid'		=> Session::getId(),
		);
		//dd($this->getAutoDiscountLabel());
		$couponRS 		 = array();
		if(!empty($CouponCode))
		{
			$couponRS = DB::table($this->prefix.'coupon')
									->select('coupon_id','coupon_number')
									->where('coupon_number','=',$CouponCode)->first();
			if(!empty($couponRS))
			{
				$OrderInsert['coupon_id'] 	= $couponRS->coupon_id;
				$OrderInsert['coupon_code'] = $couponRS->coupon_number;
			}
		}
		$OrderID 	= DB::table($this->prefix .'orders')->insertGetId($OrderInsert);	
		$aa 		= $this->setOrderID($OrderID); ## set order id in cart
		
		### Inster into order table end  ###
		
		### Insert into order detail table start ###
		$tempCart = $this->getCart();
		$cnt_row  = count($tempCart);

		for($i=0; $i<$cnt_row; $i++)	
		{
			//dd($tempCart,$cnt_row);
			$OrderDetailInsert = array ( 
				'order_id'			 	=> $OrderID,
				'products_id'			=> $tempCart[$i]['product_id'],
				'product_sku' 			=> $tempCart[$i]['SKU'],
				'product_name'			=> $tempCart[$i]['ProductName'],
				'Image'					=> $tempCart[$i]['Image'],
				'product_url'			=> $tempCart[$i]['product_url'],
				'size_dimension'		=> $tempCart[$i]['size_dimension'],
				'attribute_1'			=> $tempCart[$i]['attribute_1'],
				'pack_size'				=> $tempCart[$i]['pack_size'],
				'attribute_2'			=> $tempCart[$i]['attribute_2'],
				'flavour'				=> $tempCart[$i]['flavour'],
				'attribute_3'			=> $tempCart[$i]['attribute_3'],
				'quantity' 			 	=> $tempCart[$i]['Qty'],
				'unit_price' 			=> $tempCart[$i]['Price'],
				'total_price' 			=> $tempCart[$i]['TotPrice'],
				'status' 			  	=> '1',
			);
			$OrderDetailID = DB::table($this->prefix .'order_detail')->insertGetId($OrderDetailInsert);	
		}
		### Insert into order detail table end ###
		$Payment_Type = Session::get('ShoppingCart.payment_method');

		if($Payment_Type == 'PAYMENT_PAYPALEC')
		{			
			return redirect('paypal-payment');
		}
		elseif($Payment_Type == "PAYMENT_BRAINTREECC" or
			   $Payment_Type == "PAYMENT_BRAINTREEPAYPAL" or	
			   $Payment_Type == "PAYMENT_BRAINTREEGOOGLEPAY" or
			   $Payment_Type == "PAYMENT_BRAINTREEAPPLEPAY")
		{						
							   
			$updArray = array ( 
				'pay_status' => 'Unpaid'
			);		
			//echo $this->getOrderID(); exit;
			$upOrderRes = DB::table($this->prefix.'orders')->where('order_id', '=', $this->getOrderID())->update($updArray);
									  
			return redirect('braintree-payment-cc');
		}
	}
	
}