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


class OrderReceiptController extends Controller
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
		
		$SITE_URL 				= config('const.SITE_URL');
		   
		$metaArray = array();
		$metaArray['meta_title'] 		= "Order Receipt - ".config('const.SITE_NAME');
		$metaArray['meta_keywords'] 	= "'".config('const.SITE_NAME')."'";
		$metaArray['meta_description'] 	= "Checkout at '".config('const.SITE_NAME')."' to receive your purchase today!";
		
		$this->PageData = $metaArray;
		
		$orderID 		= (int)$this->getOrderID();	
		//$orderID 		= '10076';
		//dd($orderID);
		if(trim($orderID) == '' or empty($orderID) or trim($orderID) == '0') 
		{
			return redirect($SITE_URL);
		}
		
		if(Session::has('customer_id') == false)
		{ 
			return redirect($SITE_URL);
		}		
		$customerID 	= (int)Session::get('customer_id');
		
		$OrdersInfo 	= $this->getOrderData($orderID, $customerID);
		
		$OrderDetails 	= $this->getOrderDetails($orderID, $customerID);
		$OrderDetails	= json_decode(json_encode($OrderDetails), true);
		
		
		$this->updateOrderStatus($orderID);
		//echo $OrdersInfo->ship_country; exit; 
		### Inset into Avalara [START] ###
		//&& $customerID != '1'
		/*if(isset($OrdersInfo->avalara_transaction_id) && empty($OrdersInfo->avalara_transaction_id)){
			if($orderID != '' && $OrdersInfo->ship_zip !='' && $OrdersInfo->ship_state !='' && $OrdersInfo->ship_country !='' )
			{
				$this->avalaraTaxCalculation( $OrdersInfo->ship_address1, $OrdersInfo->ship_address2, $OrdersInfo->ship_city, $OrdersInfo->ship_zip, $OrdersInfo->ship_state, $OrdersInfo->ship_country, $orderID, 'yes');
			}
		}*/
		### Inset into Avalara [END] ###
		
		//dd($OrdersInfo);
		$this->sendOrderEmail($orderID, $customerID);
		//echo $OrderDetails[$nk]product_sku
		#### Deduct product stock [START] #####
		if(count($OrderDetails) > 0)
		{
			for($nk=0;$nk<count($OrderDetails);$nk++)
			{
				$this->ProductDeductStock($OrderDetails[$nk]['product_sku'],$OrderDetails[$nk]['quantity']);
			}
		} 
		#### Deduct product stock [END] #####
		
		
		## Destroy cart 
		##-------------
		$this->destroyCart(); 

		##If guest customer then unset session.
		##-------------------------------------
		if(Session::has('etype') && Session::get('etype') == "G" && Session::has('customer_id') && Session::get('customer_id') > 0)
		{
			Session::forget('customer_id');		
			Session::forget('etype');		
			Session::save();
		}
		
		
		//GA4 Google eCommerce code Start
		/*$GA4_GOOGLE_ORDER_PURCHASE_EVENT_DATA = '';
		$ga4_google_purchase_item_str_gtm = "";
		if(count($OrderDetails) > 0)
		{
			for($go=0;$go<count($OrderDetails);$go++)
			{	
				$ga4_google_purchase_item_str_gtm .= '{
					"item_id": "'.$OrderDetails[$go]['product_sku'].'",
					"item_name": "'.$OrderDetails[$go]['product_name'].'",
					"affiliation": "HBA",
					"currency": "USD",
					"item_category": "",
					"price": "'.$OrderDetails[$go]['unit_price'].'",
					"quantity": "'.$OrderDetails[$go]['quantity'].'"
				},';	
			}
		}
		
		$GA4_GOOGLE_ORDER_PURCHASE_EVENT_DATA = '
		dataLayer.push({ ecommerce: null });
		dataLayer.push({
			event: "purchase",
			ecommerce: {
				"transaction_id": "'.$OrdersInfo->order_id.'",
				"affiliation": "HBA",
				"value": "'.$OrdersInfo->order_total.'",
				"tax": "'.$OrdersInfo->tax.'",
				"shipping": "Free",
				"currency": "USD",
				"coupon": "'.$OrdersInfo->coupon_code.'",
				"items": ['.rtrim($ga4_google_purchase_item_str_gtm, ',').']
			}
		});';
		$this->PageData['GA4_GOOGLE_ORDER_PURCHASE_EVENT_DATA'] =  $GA4_GOOGLE_ORDER_PURCHASE_EVENT_DATA;*/
		//GA4 Google eCommerce code End
		
		$this->PageData['CSSFILES'] = ['cart.css'];
		$this->PageData['OrdersInfo'] =  $OrdersInfo;
		$this->PageData['OrderDetails'] =  $OrderDetails;		
		
		return view('shoppingcart.OrderReceipt')->with($this->PageData);
		//return view('shoppingcart.OrderReceipt',compact('OrdersInfo','OrderDetails'))->with($this->PageData);
	}
	
	
	public function ProductDeductStock($product_sku, $quantity)
	{
		$ProductInfo = DB::table($this->prefix.'products')
			->select('current_stock','sold_qunantity')
			->where('sku','=',$product_sku)->first();
		
		$new_stock 						= 0;
		$sold_qunantity					= 0;
		
		if(!empty($ProductInfo))
		{
			$sold_qunantity 				= $ProductInfo->sold_qunantity;
			$new_stock = $ProductInfo->current_stock - $quantity;
			
			if($new_stock <= 0)
			{
				$new_stock = 0;
			}

			$upStock['current_stock']  		= $new_stock;
			$upStock['sold_qunantity'] 	= $sold_qunantity + $quantity;
			
			$UpdateStock = DB::table($this->prefix.'products')
						  ->where('sku', '=', $product_sku)
						  ->update($upStock);
		}
	}
	
	public function getOrderData($orderID, $customerID)
	{
		$SITE_URL 		= config('const.SITE_URL');
		
		$OrdersInfo = DB::table($this->prefix.'orders')
									->select('*')
									->where('order_id','=',$orderID)
									->where('customer_id','=',$customerID)->first();
		
									//dd($orderID); exit;
		$OrdersInfo->merchandise_subtotal = '0.00';
		$OrdersInfo->merchandise_subtotal = $OrdersInfo->sub_total - ( $OrdersInfo->auto_discount + $OrdersInfo->quantity_discount + $OrdersInfo->coupon_amount);
		
		return $OrdersInfo;						
	}
	
	public function getOrderDetails($orderID, $customerID)
	{
		$OrderDetails = DB::table($this->prefix.'order_detail')
			->select('*')
			->where('order_id','=',$orderID)
			->orderBy('order_detail_id')->get();
		
		return $OrderDetails;						
	}
	
	public function getCouponDetails($couponID)
	{
		$CouponDetails = DB::table($this->prefix.'coupon')
			->select('*')
			->where('coupon_id','=',$couponID)
			->where('type','=',1)->first();
			
	
		return $CouponDetails;	
		
	}
	
	public function updateOrderStatus($orderID)
	{
		$OrdersInfo = DB::table($this->prefix.'orders')
								->select('payment_type','status')
								->where('order_id','=',$orderID)->first();
		
		if(!empty($OrdersInfo))
		{
			$paymentMethod = $OrdersInfo->payment_type;
			
			$upOrder = array();
			
			$upOrder['status'] = 'In Process';
			$upOrder['horder_id'] = 'HBA'.$orderID;
			$uporderRES = DB::table($this->prefix.'orders')
			  ->where('order_id', '=', $orderID)
			  ->update($upOrder);
			
		}
	}
	
	public function sendOrderEmail($orderID, $customerID)
	{
		$SITE_URL 		= config('const.SITE_URL');
	
		$OrdersInfo 	= $this->getOrderData($orderID, $customerID);
		$OrderDetails 	= $this->getOrderDetails($orderID, $customerID);
		
		## Billing Detils Start
		##---------------------
		$billing_details	= '';
		$billing_address	= '';
		
		if(trim($OrdersInfo->bill_address1) != "") { $billing_address .= $OrdersInfo->bill_address1; }
		if(trim($OrdersInfo->bill_address2) != "") { $billing_address .= '<br>'.$OrdersInfo->bill_address2; }
		
		$billing_details .='<table border="0" class="fullbox" cellpadding="0" cellspacing="0">
			<tr class="flex" align="center">
				<td class="" style="color:rgba(33, 33, 33);font-size:20px;font-family:Arial, \'Helvetica Neue\', sans-serif;padding:0px 10px;font-weight:700;">Billing Address</td>
			</tr>
			<tr class="flex" align="center">
				<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">'.$OrdersInfo->bill_first_name.' '.$OrdersInfo->bill_last_name.'<br clear="hide">'.$billing_address.',<br clear="hide">'.$OrdersInfo->bill_city.', '.$OrdersInfo->bill_state.' - '.$OrdersInfo->bill_zip.', '.$OrdersInfo->bill_country.'</td>
			</tr>
			<tr class="flex" align="center">
				<td class="" style="color:#30303f;font-size:14px;font-family:Arial, \'Helvetica Neue\', sans-serif;padding:0px 10px;line-height:1.4;"><b>Phone:</b> <a href="tel:+'.$OrdersInfo->bill_phone.'" style="color:rgba(51, 51, 51);text-decoration:none;">'.$OrdersInfo->bill_phone.'</a></td>
			</tr>
			<tr class="flex" align="center">
				<td class="" style="color:#30303f;font-size:14px;font-family:Arial, \'Helvetica Neue\', sans-serif;padding:0px 10px;line-height:1.4;">'.$OrdersInfo->ship_email.'</a></td>
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
		
		$shipping_details .='<table border="0" class="fullbox" cellpadding="0" cellspacing="0">
			<tr class="flex" align="center">
				<td class="" style="color:rgba(33, 33, 33);font-size:20px;font-family:Arial, \'Helvetica Neue\', sans-serif;padding:0px 10px;font-weight:700;">Shipping Address</td>
			</tr>
			<tr class="flex" align="center">
				<td class="" style="color:rgba(51, 51, 51);font-size:14px;font-family:\'Lato\', sans-serif;padding:0px 10px;line-height:1.5; font-weight: 400;">'.$OrdersInfo->ship_first_name.' '.$OrdersInfo->ship_last_name.'<br clear="hide">'.$shipping_address.',<br clear="hide"> '.$OrdersInfo->ship_city.' - '.$OrdersInfo->ship_state.' - '.$OrdersInfo->ship_zip.', '.$OrdersInfo->ship_country.'</td>
			</tr>
			<tr class="flex" align="center">
				<td class="" style="color:#30303f;font-size:14px;font-family:Arial, \'Helvetica Neue\', sans-serif;padding:0px 10px;line-height:1.4;"><b>Phone:</b> <a href="tel:+'.$OrdersInfo->ship_phone.'" style="color:#30303f;text-decoration:none;">'.$OrdersInfo->ship_phone.'</a></td>
			</tr>
			<tr class="flex" align="center">
				<td class="" style="color:#30303f;font-size:14px;font-family:Arial, \'Helvetica Neue\', sans-serif;padding:0px 10px;line-height:1.4;"> <a href="#" style="color:#30303f;text-decoration:none;">'.$OrdersInfo->ship_email.'</a></td>
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
							<td style="margin:0px; padding:10px 5px; border-bottom:1px solid #e2e6ea;"><strong>Unit&nbsp;Price</strong></td>
							<td style="margin:0px; padding:10px 5px; border-bottom:1px solid #e2e6ea;"><strong>Quantity</strong></td>
							<td style="margin:0px; padding:10px 0px 10px 5px; border-bottom:1px solid #e2e6ea;" align="right"><strong>Total&nbsp;Price</strong></td>
						</tr>';
										
						//For Loop Start
						$OrderDetails = json_decode(json_encode($OrderDetails), true);
						for($od=0;$od<count($OrderDetails);$od++)
						{
						$STR_EMAIL_ITEMS .='<tr style="margin:0px; padding:0px;" align="center">
							<td style="margin:0px; padding:10px 5px 10px 0px;" align="left">
								<table cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td style="width:55px;">
												<img src="'.$OrderDetails[$od]['Image'].'" alt="'.$OrderDetails[$od]['product_name'].'" style="width:50px; height:50px;">
											</td>
											<td style="font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">
												<strong><a href="'.$OrderDetails[$od]['product_url'].'" style="text-decoration:none;"><font color="#000000">'.$OrderDetails[$od]['product_name'].'</font></a></strong>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td style="margin:0px; padding:10px 5px; font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">$'.$OrderDetails[$od]['unit_price'].'</td>
							<td style="margin:0px; padding:10px 5px;">'.$OrderDetails[$od]['quantity'].'</td>
							<td style="margin:0px; padding:10px 0px 10px 5px;" align="right">$'.$OrderDetails[$od]['total_price'].'</td>
						</tr>';
						//For Loop End
						}
						$STR_EMAIL_ITEMS .='
						
						
					</tbody>
				</table>
			</td>
		</tr>
		<tr style="margin:0px; padding:0px;" align="right">
			<td style="margin:0px; padding:0px;">
				<table style="margin:0px; padding:0px;font-family:Lato, sans-serif;color:rgba(51, 51, 51);font-size:12px;line-height:18px;">
					<tbody>
						<tr style="margin:0px; padding:0px;" align="right">
							<td style="margin:0px; padding:5px 2px 1px 5px;">Subtotal:</td>
							<td style="margin:0px; padding:5px 0px 1px 2px;">$'.$OrdersInfo->sub_total.'</td>
						</tr>';
						if($OrdersInfo->tax > 0)
						{
						$STR_EMAIL_ITEMS .='
						<tr style="margin:0px; padding:0px;" align="right">
							<td style="margin:0px; padding:1px 2px 1px 5px;">Sales Tax:</td>
							<td style="margin:0px; padding:1px 0px 1px 2px;">$'.$OrdersInfo->tax .'</td>
						</tr>';
						}
						if($OrdersInfo->auto_discount > 0)
						{
						$STR_EMAIL_ITEMS .='<tr style="margin:0px; padding:0px;" align="right">
							<td style="margin:0px; padding:1px 2px 1px 5px;">Auto Discount:</td>
							<td style="margin:0px; padding:1px 5px 1px 2px;">-$'.$OrdersInfo->auto_discount.'</td>
						</tr>';
						}
						if($OrdersInfo->quantity_discount > 0)
						{
						$STR_EMAIL_ITEMS .='<tr style="margin:0px; padding:0px;" align="right">
							<td style="margin:0px; padding:1px 2px 1px 5px;">Quantity Discount:</td>
							<td style="margin:0px; padding:1px 5px 1px 2px;">-$'.$OrdersInfo->quantity_discount.'</td>
						</tr>';
						}
						if($OrdersInfo->coupon_amount > 0)
						{
						$STR_EMAIL_ITEMS .='<tr style="margin:0px; padding:0px;" align="right">
							<td style="margin:0px; padding:1px 2px 1px 5px;">Coupon Discount:</td>
							<td style="margin:0px; padding:1px 0px 1px 2px;">-$'.$OrdersInfo->coupon_amount.'</td>
						</tr>';
						}
						$STR_EMAIL_ITEMS .='<tr style="margin:0px; padding:0px;" align="right">
							<td style="margin:0px; padding:6px 2px 0px 5px;"><strong>Total Amount:</strong></td>
							<td style="margin:0px; padding:6px 0px 0px 2px;"><strong>$'.$OrdersInfo->order_total.'</strong></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>';
		
		##Send Email TO Customer
		##---------------------
		$res_mail 	= GetMailTemplate("ORDER_RECEIPT");		 
		$ToEmail 	= $OrdersInfo->ship_email;
		$Subject	= $res_mail[0]->subject. " Order Id - ". $OrdersInfo->order_id;
		
		$EmailBody = $res_mail[0]->mail_body;
		
		$EmailBody = str_replace('{$SITE_URL}',  config('Settings.SITE_URL'), $EmailBody);
		$EmailBody = str_replace('{$first_name}',  ucfirst($OrdersInfo->bill_first_name), $EmailBody);
		$EmailBody = str_replace('{$iorder_id}',  $OrdersInfo->order_id, $EmailBody);
		$EmailBody = str_replace('{$bill_address}', $billing_details, $EmailBody);
		$EmailBody = str_replace('{$ship_address}', $shipping_details, $EmailBody);
		$EmailBody = str_replace('{$tablepro}', $STR_EMAIL_ITEMS, $EmailBody);
		$EmailBody = str_replace('{CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);
		$EmailBody = str_replace('{TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
		$EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);
		$EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
		$EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
		
		
		$From = config('Settings.CONTACT_MAIL');
		
		## Send Email TO Customer
		##------------------------
		$a = $this->sendSMTPMail($ToEmail, $Subject, $EmailBody, $From );
		$b = $this->sendSMTPMail('sachin.qualdev@gmail.com', $Subject, $EmailBody, $From );
		$c = $this->sendSMTPMail(config('Settings.ADMIN_MAIL'), $Subject, $EmailBody, $From);
		## Send Email TO Admin 	
		##----------------------
		
		
	}
	
	public function printOrderReceipt(Request $request)
	{
		$SITE_URL 		= config('const.SITE_URL');
		$orderID 	 	= (int)$request->orderId;					

		if(Session::has('customer_id') && Session::get('customer_id') > 0)
		{
			$customerID 	= (int)Session::get('customer_id');
		}
		else
		{
			$customerID 	= (int)$request->customer_id;
		}

		if(trim($orderID) == '' or empty($orderID) or trim($orderID) == '0') 
		{
			return redirect($SITE_URL);
		}
			
		$OrdersInfo 	= $this->getOrderData($orderID, $customerID);
		if(empty($OrdersInfo))
		{
			return redirect($SITE_URL);
		}
		$OrderDetails 	= $this->getOrderDetails($orderID, $customerID);
		$OrderDetails	= json_decode(json_encode($OrderDetails), true);
		
		$this->PageData['CSSFILES'] = ['cart.css'];
		$this->PageData['OrdersInfo'] =  $OrdersInfo;
		$this->PageData['OrderDetails'] =  $OrderDetails;
		return view('shoppingcart.orderReceiptPrint')->with($this->PageData);
		
		//return view('shoppingcart.orderReceiptPrint',compact('OrdersInfo','OrderDetails'));
	}
}