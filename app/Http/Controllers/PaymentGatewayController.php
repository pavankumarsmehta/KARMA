<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Controllers\Traits\GeneralTrait;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\ShoppingCartTrait;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Redirect;
use Srmklive\PayPal\Services\ExpressCheckout;
use App\Helpers\GlobalHelper;

class PaymentGatewayController extends Controller
{
	use GeneralTrait;     
	use CartTrait;     
	use ShoppingCartTrait;     
	
	
    public function __construct()
    {    		
        $this->current_url = URL::full();
        $this->prefix = config('const.DB_TABLE_PREFIX');
    }
	
	public function getBraintreeAPIDetails()
	{
		$bt_res = DB::table($this->prefix.'payment_methods')
			->select('*')
			->where('pm_status','=','Active')
			->where('pm_group_name','=','PAYMENT_BRAINTREECC')
			->orderBy('pm_type','desc') 
			->first();	
		//dd($bt_res);
		$bt_details = array();
		$bt_details['IS_BRAINTREE_CHECKOUT'] 	  = 'No';
		$bt_details['BRAINTREE_MERCHANT_ID'] 	  = '';
		$bt_details['BRAINTREE_PUBLIC_API_KEY']   = '';
		$bt_details['BRAINTREE_PRIVATE_API_KEY']  = '';
		$bt_details['BRAINTREE_TOKENIZATION_KEY'] = '';
		$bt_details['BRAINTREE_GOOGLE_MERCHANT_ID'] = '';
		$bt_details['BRAINTREE_TRANSACTION_MODE'] = '';
		//echo "<pre>"; print_r($bt_res); exit;
		if(!empty($bt_res)) 
		{
			$arrPEVar									= unserialize($bt_res->pm_details);		
			if(Session::get('ShoppingCart.ShippingAddress.email') == 'gequaldev@gmail.com' || Session::get('ShoppingCart.ShippingAddress.email') == 'qqualdev@gmail.com' || Session::get('ShoppingCart.ShippingAddress.email') == 'qualdev.support@gmail.com' || Session::get('ShoppingCart.ShippingAddress.email') == 'qatesting@mailinator.com' || Session::get('ShoppingCart.ShippingAddress.email') == 'hitumc_1348752712_per@gmail.com')
			{	
				$bt_details['IS_BRAINTREE_CHECKOUT'] 		= 'Yes';
				$bt_details['BRAINTREE_MERCHANT_ID']		= 'j5vyzpwpfzvfk597';
				$bt_details['BRAINTREE_PUBLIC_API_KEY']		= 'yr22k85rkhfg4sw7';
				$bt_details['BRAINTREE_PRIVATE_API_KEY']	= '52b762f50d12a6162a33144825665e01';
				$bt_details['BRAINTREE_TOKENIZATION_KEY']	= 'sandbox_6md674h5_j5vyzpwpfzvfk597';
				$bt_details['BRAINTREE_GOOGLE_MERCHANT_ID']	= '';
				$bt_details['BRAINTREE_TRANSACTION_MODE']	= 'sandbox';
			}
			else
			{
				$bt_details['IS_BRAINTREE_CHECKOUT'] 		= 'Yes';
				$bt_details['BRAINTREE_MERCHANT_ID']		= GlobalHelper::Decrypt($arrPEVar['BRAINTREE_MERCHANT_ID']);
				$bt_details['BRAINTREE_PUBLIC_API_KEY']		= GlobalHelper::Decrypt($arrPEVar['BRAINTREE_PUBLIC_API_KEY']);
				$bt_details['BRAINTREE_PRIVATE_API_KEY']	= GlobalHelper::Decrypt($arrPEVar['BRAINTREE_PRIVATE_API_KEY']);
				$bt_details['BRAINTREE_TOKENIZATION_KEY']	= GlobalHelper::Decrypt($arrPEVar['BRAINTREE_TOKENIZATION_KEY']);
				$bt_details['BRAINTREE_GOOGLE_MERCHANT_ID']	= '';
				$bt_details['BRAINTREE_TRANSACTION_MODE']	= $arrPEVar['BRAINTREE_TRANSACTION_MODE'];
				//echo '2222<br>++++++<br>';
				//dd($bt_details);
			}
		}
		//dd($bt_details);
		$bt_Data = (object) $bt_details;
		return $bt_Data;
	}
	
	public function braintreePaymentCredidCard(Request $request)
	{
		
		if($this->getTotalItemInCart() <= 0)
		{
			$a = $this->destroyCart();
			return redirect('cart');
		}
		
		//echo $this->getTotalItemInCart(); exit;
		###################################################
		## Check For Duplicate Request and Prevent Start
		###################################################
		//echo Session::get('ShoppingCart.OrderID'); exit;
		$OrdersInfo = DB::table($this->prefix.'orders')
			->select('order_id','payment_type','payment_gateway_response','status','pay_status')
			->where('order_id','=',$this->getOrderID())
			->first();
			
		if(!empty($OrdersInfo))
		{
			if(in_array($OrdersInfo->payment_type, array('PAYMENT_BRAINTREECC','PAYMENT_BRAINTREEPAYPAL','PAYMENT_BRAINTREEGOOGLEPAY','PAYMENT_BRAINTREEAPPLEPAY')))
			{
				if($OrdersInfo->status == 'In Process' and trim($OrdersInfo->payment_gateway_response) !='')	
				{
					$bt_respponse_arr = json_decode($OrdersInfo->payment_gateway_response,true);

					if(isset($bt_respponse_arr['success']))
					{
						if($bt_respponse_arr['success'] == true)
						{
							if(isset($bt_respponse_arr['transaction']['id']))
							{
								if($bt_respponse_arr['transaction']['id'] !='')
								{
									return redirect('order-receipt');	
								}
							}	
						}	
					}	
				}	
			}	
		}		
		###################################################
		## Check For Duplicate Request and Prevent End
		###################################################
		
		$bt_details = $this->getBraintreeAPIDetails();	
		
		$IS_BRAINTREE_CHECKOUT 	  	= $bt_details->IS_BRAINTREE_CHECKOUT;
		$BRAINTREE_MERCHANT_ID 	  	= $bt_details->BRAINTREE_MERCHANT_ID;
		$BRAINTREE_PUBLIC_API_KEY   = $bt_details->BRAINTREE_PUBLIC_API_KEY;
		$BRAINTREE_PRIVATE_API_KEY  = $bt_details->BRAINTREE_PRIVATE_API_KEY;
		$BRAINTREE_TOKENIZATION_KEY = $bt_details->BRAINTREE_TOKENIZATION_KEY;
		$BRAINTREE_TRANSACTION_MODE = $bt_details->BRAINTREE_TRANSACTION_MODE;
		
		if($IS_BRAINTREE_CHECKOUT == 'No') 
		{			
			$msg = "Pay by credit card service is temporarily unavailable at this time, Please choose other payment method.";
			return redirect('checkout')->with('msg',$msg);
		}		
		
		$bt_payment_method_nonce = '';
		if(Session::has('ShoppingCart.bt_payment_method_nonce'))
		{
			$bt_payment_method_nonce = Session::get('ShoppingCart.bt_payment_method_nonce');
			if(Session::has('ShoppingCart.bt_payment_method_nonce'))
			{
				Session::forget('ShoppingCart.bt_payment_method_nonce');
			}
		}
		$payment_method = '';
		if(Session::has('ShoppingCart.payment_method'))
		{
			$payment_method = Session::get('ShoppingCart.payment_method');
			if(Session::has('ShoppingCart.payment_method'))
			{
				Session::forget('ShoppingCart.payment_method');
			}
		}
		
		$billingInfo  	= $this->getBillingAddress();
		$shippingInfo 	= $this->getShippingAddress();		
			
		$BRAINTREE_LIB_PATH 	= config('const.PHYSICAL_PATH');
		
		require_once($BRAINTREE_LIB_PATH.'vendor/braintree/braintree_php/lib/Braintree.php');
		
		$gateway = new \Braintree\Gateway([
											'environment' 	=> $BRAINTREE_TRANSACTION_MODE,
											'merchantId' 	=> $BRAINTREE_MERCHANT_ID,
											'publicKey' 	=> $BRAINTREE_PUBLIC_API_KEY,
											'privateKey' 	=> $BRAINTREE_PRIVATE_API_KEY
										]);
			$customerResult_ID = '';
			if($payment_method == 'PAYMENT_BRAINTREEPAYPAL')
			{
				
				$result = $gateway->transaction()->sale([
						'paymentMethodNonce' => $bt_payment_method_nonce, 
						//'deviceData' => $deviceDataFromTheClient,
						'options' => [ 'submitForSettlement' => True ],
						'amount' => $this->getNetTotal(),
						'orderId' => $this->getOrderID(),
						'billing' => [
							'firstName' 	=> $billingInfo['first_name'],
							'lastName'  	=> $billingInfo['last_name'],
							'company' 		=> '',
							'streetAddress' => $billingInfo['address1'],
							'extendedAddress' => $billingInfo['address2'],
							'locality' 		=> $billingInfo['city'],
							'region' 		=> $billingInfo['state'],
							'postalCode' 	=> $billingInfo['zip'],
							'countryCodeAlpha2' => $billingInfo['country']
						],
						'shipping' => [
							'firstName' 		=> $shippingInfo['first_name'],
							'lastName' 			=> $shippingInfo['last_name'],
							'company' 			=> '',
							'streetAddress' 	=> $shippingInfo['address1'],
							'extendedAddress' 	=> $shippingInfo['address2'],
							'locality' 			=> $shippingInfo['city'],
							'region' 			=> $shippingInfo['state'],
							'postalCode' 		=> $shippingInfo['zip'],
							'countryCodeAlpha2' => $shippingInfo['country']
						]
				]);
			}
			else
			{
				/*$customerResult = $gateway->customer()->create([
					'firstName' => $shippingInfo['first_name'],
					'lastName' => $shippingInfo['last_name'],
					'email' => Session::get('ShoppingCart.ShippingAddress.email'),
					'paymentMethodNonce' => $bt_payment_method_nonce
				]);*/
				//dd($gateway);
				$result = $gateway->transaction()->sale([
					'paymentMethodNonce' => $bt_payment_method_nonce,
					//'customerId' => $customerResult->customer->id, 
					//'deviceData' => $deviceDataFromTheClient,
					'options' => [ 'submitForSettlement' => True ],
					'amount' => $this->getNetTotal(),
					'orderId' => $this->getOrderID(),
					'billing' => [
									'firstName'			=> $billingInfo['first_name'],
									'lastName'  		=> $billingInfo['last_name'],
									'company' 			=> '',
									'streetAddress' 	=> $billingInfo['address1'],
									'extendedAddress' 	=> $billingInfo['address2'],
									'locality' 			=> $billingInfo['city'],
									'region' 			=> $billingInfo['state'],
									'postalCode' 		=> $billingInfo['zip'],
									'countryCodeAlpha2' => $billingInfo['country']
					  ],
					 'shipping' => [
									'firstName' 		=> $shippingInfo['first_name'],
									'lastName' 			=> $shippingInfo['last_name'],
									'company' 			=> '',
									'streetAddress' 	=> $shippingInfo['address1'],
									'extendedAddress' 	=> $shippingInfo['address2'],
									'locality' 			=> $shippingInfo['city'],
									'region' 			=> $shippingInfo['state'],
									'postalCode' 		=> $shippingInfo['zip'],
									'countryCodeAlpha2' => $shippingInfo['country']
					  ]
				]);
				//dd($result); 
				//$customerResult_ID = $customerResult->customer->id;
			}	
			if ($result->success) 
			{
				//print_r("success!: " . $result->transaction->id);				
				$transaction_info = "This transaction has been approved.";
				$payment_gateway_response = json_encode($result);
				
				$updAray = array();				

				$updAray = array (
					'status'					=> 'In Process',
					'pay_status' 	   			=> 'Paid',
					'transaction_info' 			=> $transaction_info,
					//'braintree_customer_id' 	=> $customerResult_ID,
					'payment_gateway_response' 	=> $payment_gateway_response
				);
						  			
				$updOrder = DB::table($this->prefix.'orders')
					->where("order_id","=",$this->getOrderID())
					->update($updAray);
				//echo $payment_gateway_response; exit;				
				return redirect('order-receipt'); 				
			} 
			else if ($result->transaction) 
			{
				//print_r("Error processing transaction:");
				//print_r("\n  code: " . $result->transaction->processorResponseCode);
				//print_r("\n  text: " . $result->transaction->processorResponseText);
								
				$transaction_info = "This transaction has been Declined.";
				$payment_gateway_response = json_encode($result);
				
				$updAray = array();
				
				$updAray = array (
					'status'					=> 'Declined',
					'pay_status' 	   			=> 'Unpaid',
					'transaction_info' 			=> $transaction_info,
					//'braintree_customer_id' 	=> $customerResult_ID,
					'payment_gateway_response' 	=> $payment_gateway_response
				);
							  			
				$updOrder = DB::table($this->prefix.'orders')
								->where("order_id","=",$this->getOrderID())
								->update($updAray);
								
				//$msg = 'Error in Processing Request Please try again.'.$result->transaction->processorResponseText;	
				$msg = 'This transaction has been Declined. Please try again. '.$result->transaction->processorResponseText;
				
				return redirect('checkout')->with('msg',$msg);
				
			} 
			else 
			{				
				//$msg = 'Error in Processing Request Please try again.';	
				$msg = 'This transaction has been Declined. Please try again. ';
				
				foreach($result->errors->deepAll() AS $error) 
				{
				  //print_r($error->attribute ." : ". $error->code . ": " . $error->message . "\n");
				  $msg .= $error->message;	
				}
				
				$transaction_info = "This transaction has been Declined.";
				
				$payment_gateway_response = json_encode($result);
				
				$updAray = array();
				$updAray = array (
					'status'					=> 'Declined',
					'pay_status' 	   			=> 'Unpaid',
					'transaction_info' 			=> $transaction_info,
					//'braintree_customer_id' 	=> $customerResult_ID,
					'payment_gateway_response' 	=> $payment_gateway_response
				);
							  			
				$updOrder = DB::table($this->prefix.'orders')
								->where("order_id","=",$this->getOrderID())
								->update($updAray);
				
				return redirect('checkout')->with('msg',$msg);
				
			}			
			//$json = json_encode($result);
			//dd($json);		
			
		    $msg = 'Error in Processing Request Please try again.';	
			return redirect('checkout')->with('msg',$msg);
	}
	
	 
}