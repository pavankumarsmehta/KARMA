<?php

namespace App\Http\Controllers\Traits;
//use Avalara\AvaTaxClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductsReview;
use App\Models\Category;
use App\Models\Order;
use App\Models\TaxAreaRates;
use App\Models\TaxArea;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\productTrait;
use App\Http\Controllers\Traits\CartTrait;
use App\Helpers\GlobalHelper;
use DB;
use Session;

trait ShoppingCartTrait
{
	use generalTrait;
	use productTrait;
	use CartTrait;
	
	public function getNetDiscount() 
	{
		$discount = ($this->getAutoDiscount() + $this->getQuantityDiscount() + $this->getCouponDiscount());
		
		return 	$this->NumberFormat($discount);	
	}

	public function getNetTotal() 
	{
		$GiftCouponInfo 	= $this->getGiftCouponInfo();
		
		$tot = $this->getSubTotal() + $this->getShippingCharge() + $this->getTaxValue();
		$discount = $this->getNetDiscount() + $GiftCouponInfo['Value'];
		//echo $discount; exit;
		Session::put('ShoppingCart.NetTotal', $tot - $discount);
		Session::save();
		
		return $this->NumberFormat( $tot - $discount );
	}

	public function setShippingCharge($val)	
	{
		Session::put('ShoppingCart.Shipping.ShippingCharge', $this->NumberFormat($val));
		Session::save();
		return NULL;
	}
	
	public function getShippingCharge()	
	{
		if(Session::has('ShoppingCart.Shipping'))
			$temp = Session::get('ShoppingCart.Shipping');
		else
			return 0;
		
		if(Session::has('ShoppingCart.PromoCoupon.FreeShipping') && Session::get('ShoppingCart.PromoCoupon.FreeShipping') == 'Yes' && 
		   Session::has('ShoppingCart.PromoCoupon.FreeShippingModeID') && $temp['ShippingModeID'] == Session::get('ShoppingCart.PromoCoupon.FreeShippingModeID'))	
		{
			$this->setShippingCharge(0);
		}
		
		return $this->NumberFormat(Session::get('ShoppingCart.Shipping.ShippingCharge'));
	}
	
	
	
	
	
	
	//Start 05-12-2022
	public function getTotalItemInCart()	
	{
		if(Session::has('ShoppingCart.TotalItemInCart') == true && Session::get('ShoppingCart.TotalItemInCart') > 0)
		{
			return Session::get('ShoppingCart.TotalItemInCart');
		}
		else
		{
			return 0;
		}	
	}
	
	public function getSubTotal() 
	{
		if(Session::has('ShoppingCart.SubTotal'))
			return Session::get('ShoppingCart.SubTotal');
		else
			return 0;	
	}
	public function destroyCart()	
	{
		Session::put('ShoppingCart', NULL);
		Session::forget('ShoppingCart');
		return;
	}	
	
	public function setBillingAddress($request) 
	{
		$temp = array();
		if($this->getBillingAsShipping() == 'Yes')	
		{
			$ship = $this->getShippingAddress();			
			$temp['first_name'] = $ship['first_name'];
			$temp['last_name']  = $ship['last_name'];
			$temp['company']    = $ship['company'];
			$temp['address1'] 	= $ship['address1'];
			$temp['address2'] 	= $ship['address2'];
			$temp['city'] 		= $ship['city'];
			$temp['country'] 	= $ship['country'];
			$temp['state'] 		= $ship['state'];
			$temp['zip'] 		= $ship['zip'];
			$temp['phone'] 		= $ship['phone'];
			$temp['email'] 		= $ship['email'];

			//$temp['title']		= '';
			$temp['AddressBookId']	= 0;

		} 
		else 
		{
			if($request->bl_country	 != 'US') 
			{
				$state = $request->bl_otherstate;
			} 
			else 
			{
				$state = $request->bl_state;
			}
			
			$temp['first_name'] 	= stripslashes($request->bl_fname);
			$temp['last_name']  	= stripslashes($request->bl_lname);
			$temp['company']    	= '';
			$temp['address1'] 		= stripslashes($request->bl_Addr1);
			$temp['address2'] 		= stripslashes($request->bl_Addr2);
			$temp['city'] 			= stripslashes($request->bl_city);
			$temp['country'] 		= $request->bl_country;
			$temp['state'] 			= $state;
			$temp['zip'] 			= $request->bl_zip;
			$temp['phone'] 			= $request->bl_phone;
			$temp['email'] 			= $request->bl_sh_email;
			
			//$temp['title']		= '';			
			$temp['AddressBookId']	= 0;
		}
		Session::put('ShoppingCart.BillingAddress', $temp);
		Session::save();
		return NULL;
	}
	
	public function getBillingAddress() 
	{
		if(Session::has('ShoppingCart.BillingAddress'))
			return Session::get('ShoppingCart.BillingAddress');
		
		Session::put('ShoppingCart.BillingAddress.first_name', '');
		Session::put('ShoppingCart.BillingAddress.last_name','');
		Session::put('ShoppingCart.BillingAddress.company', '');
		Session::put('ShoppingCart.BillingAddress.address1', '');
		Session::put('ShoppingCart.BillingAddress.address2', '');
		Session::put('ShoppingCart.BillingAddress.city', '');
		Session::put('ShoppingCart.BillingAddress.country', '');
		Session::put('ShoppingCart.BillingAddress.state', '');
		Session::put('ShoppingCart.BillingAddress.zip', '');
		Session::put('ShoppingCart.BillingAddress.phone', '');
		//Session::put('ShoppingCart.BillingAddress.email', '');
		//Session::put('ShoppingCart.BillingAddress.confirm_email', '');
		//Session::put('ShoppingCart.BillingAddress.title', '');
		Session::put('ShoppingCart.BillingAddress.AddressBookId', 0);
		Session::save();
		
		return Session::get('ShoppingCart.BillingAddress');
	}
	
	public function setShippingAddress($request) 
	{
		$temp = array();
		if($request->sh_country != 'US') 
		{
			$state = $request->sh_otherstate;
		} 
		else 
		{
			$state = $request->sh_state;
		}

		$temp['first_name'] = stripslashes($request->sh_fname);
		$temp['last_name']  = stripslashes($request->sh_lname);
		$temp['company']    = '';
		$temp['address1'] 	= stripslashes($request->sh_Addr1);
		$temp['address2'] 	= stripslashes($request->sh_Addr2);
		$temp['city'] 		= stripslashes($request->sh_city);
		$temp['country'] 	= $request->sh_country;
		$temp['state'] 		= $state;
		$temp['zip'] 		= $request->sh_zip;
		$temp['phone'] 		= $request->sh_phone;
		$temp['email'] 		= $request->bl_sh_email;
		$temp['sh_reoffers'] 		= $request->sh_reoffers;

		//$temp['title']		= '';
		$temp['AddressBookId']	= 0;
		
		Session::put('ShoppingCart.ShippingAddress', $temp);
		Session::save();
		return NULL;	
	}

	public function getShippingAddress() 
	{
		//dd(Session::get('ShoppingCart.ShippingAddress'));
		if(Session::has('ShoppingCart.ShippingAddress'))
			return Session::get('ShoppingCart.ShippingAddress');
		
		Session::put('ShoppingCart.ShippingAddress.first_name','');
		Session::put('ShoppingCart.ShippingAddress.last_name','');
		Session::put('ShoppingCart.ShippingAddress.company','');
		Session::put('ShoppingCart.ShippingAddress.address1','');
		Session::put('ShoppingCart.ShippingAddress.address2','');
		Session::put('ShoppingCart.ShippingAddress.city','');
		Session::put('ShoppingCart.ShippingAddress.country','');
		Session::put('ShoppingCart.ShippingAddress.state','');
		Session::put('ShoppingCart.ShippingAddress.zip','');
		Session::put('ShoppingCart.ShippingAddress.phone','');
		Session::put('ShoppingCart.ShippingAddress.email','');
			
		//Session::put('ShoppingCart.ShippingAddress.title','');
		Session::put('ShoppingCart.ShippingAddress.AddressBookId',0);		
		Session::save();
		
		return Session::get('ShoppingCart.ShippingAddress');
	}
		
	public function setBillingAsShipping($val)	
	{  
		Session::put('ShoppingCart.BillingAsShipping', $val);
		Session::save();
	}	
	
	public function getBillingAsShipping()	
	{
		if(Session::has('ShoppingCart.BillingAsShipping'))
			return Session::get('ShoppingCart.BillingAsShipping');
		
		Session::put('ShoppingCart.BillingAsShipping', 'No');
		Session::save();
		
		return Session::get('ShoppingCart.BillingAsShipping');
	}	
	
	public function unsetBilling_ShippingAddress()
	{
		Session::forget('ShoppingCart.BillingAddress');
		Session::forget('ShoppingCart.ShippingAddress');
		Session::forget('ShoppingCart.BillingAsShipping');
		Session::save();	
		return;
	}

	public function getShippingInfo () 
	{
		if(Session::has('ShoppingCart.Shipping'))
			return Session::get('ShoppingCart.Shipping');
	
		return null;
	}	

	public function CheckAvailableShippingMethod($ShippingMode, $ship_country,$ship_state,$ship_zip) 
	{			
	
		if(preg_match('/usps/i',$ShippingMode->shipping_title) and $ship_state == '')
				return false;
			
		if ($ship_country != "") 
		{					
			$rid = DB::table($this->prefix.'shipping_rule')
								->select('shipping_mode_id')
								->where('shipping_mode_id','=',$ShippingMode->shipping_mode_id)
								->where('zipcode_to','>=',$ship_zip)
								->where('zipcode_from','<=',$ship_zip)
								->where('state','like','%'.$ship_state.'%')
								->where('country','like','%'.$ship_country.'%')
								->where('state','!=','')
								// ->where('zipcode_to','!=','')
								// ->where('zipcode_from','!=','')
								->first();
			
			## this condition is for Z + C
			if(empty($rid)) 	
			{			
				$rid = DB::table($this->prefix.'shipping_rule')
								->select('shipping_mode_id')
								->where('shipping_mode_id','=',$ShippingMode->shipping_mode_id)
								->where('zipcode_to','>=',$ship_zip)
								->where('zipcode_from','<=',$ship_zip)
								->where('country','like','%'.$ship_country.'%')
								->where('state','=','')
								// ->where('zipcode_to','!=','')
								// ->where('zipcode_from','!=','')
								->first();
				
				## this condition is for S + C
				if(empty($rid))	
				{
					$rid = DB::table($this->prefix.'shipping_rule')
								->select('shipping_mode_id')
								->where('shipping_mode_id','=',$ShippingMode->shipping_mode_id)
								->where('state','like','%'.$ship_state.'%')
								->where('country','like','%'.$ship_country.'%')
								->where('state','!=','')
								// ->where('zipcode_to','=','')
								// ->where('zipcode_from','=','')
								->first();
					
					## this condition is for only C
					if(empty($rid))
					{					
						$rid = DB::table($this->prefix.'shipping_rule')
								->select('shipping_mode_id')
								->where('shipping_mode_id','=',$ShippingMode->shipping_mode_id)								
								->where('country','like','%'.$ship_country.'%')
								->where('state','=','')
								// ->where('zipcode_to','=','')
								// ->where('zipcode_from','=','')
								->first();
					}
				}
			}
			
			if (!empty($rid))
			{										
				return (int)$ShippingMode->shipping_mode_id;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function CalculateAvailableShippingCharge($shipping_mode_id,$ship_country,$ship_state,$ship_zip)	
	{
		$GiftCouponInfo = $this->getGiftCouponInfo();		
		$subTotal = $this->getSubTotal() - ($this->getNetDiscount() + $this->getGiftCertiTotal() ); 		
		$subTotal = $this->NumberFormat($subTotal);	
		
		$ship_country  = substr($ship_country, 0, 2);
		$shipping_mode_id = (int)$shipping_mode_id;
		if ($ship_country != "") 
		{
			## this condition is for Z + S + C
			$rid = DB::table($this->prefix.'shipping_rule')
								->select('*')
								->where('shipping_mode_id','=',$shipping_mode_id)
								->where('zipcode_to','>=',$ship_zip)
								->where('zipcode_from','<=',$ship_zip)
								->where('state','like','%'.$ship_state.'%')
								->where('country','like','%'.$ship_country.'%')
								->where('state','!=','')
								// ->where('zipcode_to','!=','')
								// ->where('zipcode_from','!=','')
								->first();
			## this condition is for Z + C
			if (empty($rid)) 	
			{									
				$rid = DB::table($this->prefix.'shipping_rule')
								->select('*')
								->where('shipping_mode_id','=',$shipping_mode_id)
								->where('zipcode_to','>=',$ship_zip)
								->where('zipcode_from','<=',$ship_zip)								
								->where('country','like','%'.$ship_country.'%')
								->where('state','=','')
								// ->where('zipcode_to','!=','')
								// ->where('zipcode_from','!=','')
								->first();
				
				## this condition is for S + C
				if (empty($rid)) 	
				{					
					$rid = DB::table($this->prefix.'shipping_rule')
								->select('*')
								->where('shipping_mode_id','=',$shipping_mode_id)								
								->where('state','like','%'.$ship_state.'%')
								->where('country','like','%'.$ship_country.'%')
								->where('state','!=','')
								// ->where('zipcode_to','=','')
								// ->where('zipcode_from','=','')
								->first();
					
					## this condition is for only C
					if (empty($rid)) 	
					{						
						$rid = DB::table($this->prefix.'shipping_rule')
								->select('*')
								->where('shipping_mode_id','=',$shipping_mode_id)
								->where('country','like','%'.$ship_country.'%')
								->where('state','=','')
								// ->where('zipcode_to','=','')
								// ->where('zipcode_from','=','')
								->first();						
					}
				}
			}
		}

		$shipping_rule_id 	= $rid->shipping_rule_id;
		$rule_type  		= $rid->rule_type;		
	
		if ($shipping_rule_id != "" && $rule_type == 1 )		
		{			
			$resultrate = DB::table($this->prefix.'shipping_rate')
								->select('*')
								->where('shipping_rule_id','=',$shipping_rule_id)
								->where('order_amount','<=',$subTotal)
								->orderBy('order_amount','desc')
								->first();	
							
		}	
		else if($shipping_rule_id != "" && $rule_type==0)	
		{
			$totalitem = $this->getTotalItemInCart() - $this->getGiftCertiCount(); 
			
			$resultrate = DB::table($this->prefix.'shipping_rate')
								->select('*')
								->where('shipping_rule_id','=',$shipping_rule_id)
								->where('order_amount','<=',$totalitem)
								->orderBy('order_amount','desc')
								->first();		

			############ FOR FREE SHIPPING FOR ITEM COUNT ##########
				if($rid->is_free_ship=="Yes")	
				{
					if($rid->free_ship_amt<=$subTotal)	
					{
						$temp_ShippingCharge=0;
						
						return $temp_ShippingCharge;
					}
				}
			############## FOR FREE SHIPPING FOR ITEM COUNT ##############
		}

		if (!empty($resultrate) && $resultrate->charge > 0)	
			$temp_ShippingCharge = $resultrate->charge;
		else				
			$temp_ShippingCharge = 0;

		########### START CODE FOR CALCULATE PROP SHIP CHARGE###########
		if($rid->prop_item > 0)	
		{
			if($rid->prop_charge > 0)	
			{
				if($totalitem >= $rid->prop_item)	
				{
					$extraitem = ($totalitem-$rid->prop_item) + 1;
					$propshippingcharge  = ($rid->prop_charge*$extraitem); 
					$temp_ShippingCharge = $temp_ShippingCharge+$propshippingcharge;
				}
			}
		}
		########### END CODE FOR CALCULATE PROP SHIP CHARGE###########
		
		## Here Check For Free Ship Coupon
		if(Session::has('ShoppingCart.PromoCoupon.FreeShipping') and Session::get('ShoppingCart.PromoCoupon.FreeShipping') == 'Yes' and Session::has('ShoppingCart.PromoCoupon.FreeShippingModeID') and Session::get('ShoppingCart.PromoCoupon.FreeShippingModeID') == $shipping_mode_id)	  
		{
				$temp_ShippingCharge =0;
		}
		
		
		return $temp_ShippingCharge;
	} 
	
	public function getCart()	
	{
		
		
		if(Session::has('ShoppingCart.Cart'))
			return Session::get('ShoppingCart.Cart');
		else
			return array();
	}
	
	public function getAutoDiscount()
	{
		if(Session::has('ShoppingCart.AutoDiscount'))
			return Session::get('ShoppingCart.AutoDiscount');
		else
			return 0;
	}
	public function getQuantityDiscount()
	{
		if(Session::has('ShoppingCart.QuantityDiscount'))
			return Session::get('ShoppingCart.QuantityDiscount');
		else
			return 0;
	}
	
	public function applyQuantityDiscount()	
	{
		$QuantityDiscount = 0;
		
		$subTotal 	= $this->NumberFormat($this->getSubTotal() - $this->getGiftCertiTotal()); 
		$TotalItem 	= $this->getTotalItemInCart() - $this->getGiftCertiCount(); 
		
		if($subTotal <= 0 or $TotalItem <= 0) 
		{ 
			Session::put('ShoppingCart.QuantityDiscount', 0.0);
			Session::save();	
			return NULL; 
		}

		$QtyRS = DB::table($this->prefix.'quantity_discount')
					->select('*')											
					->where('start_date','<=',date('Y-m-d'))
					->where('end_date','>=',date('Y-m-d'))
					->where('quantity','<=',$TotalItem)
					->where('status','=','1')
					->orderBy('quantity_discount_id')->first();
		
		
		if(!empty($QtyRS))	
		{
			if($QtyRS->type == '1')	
				$QuantityDiscount = ( $subTotal * ($QtyRS->quantity_discount_amount/100) );
			else 
				$QuantityDiscount = $QtyRS->quantity_discount_amount;
		}	
		else 
		{
			$QuantityDiscount = 0;
		}
		
		Session::put('ShoppingCart.QuantityDiscount', $this->NumberFormat($QuantityDiscount));
		Session::save();
		return NULL;
	}
	
	
	
	public function getCouponDiscount()
	{
		if(Session::has('ShoppingCart.CouponDiscount'))
			return Session::get('ShoppingCart.CouponDiscount');
		else
			return 0;		
	}
	public function getAutoDiscountLabel()
	{
		if(Session::has('ShoppingCart.AutoDiscountLabel'))
			return Session::get('ShoppingCart.AutoDiscountLabel');
		else
			return 0;
	}
	public function getCouponCode()
	{
		if(Session::has('ShoppingCart.CouponCode'))
			return Session::get('ShoppingCart.CouponCode');
		else
			return NULL;
	}
	
	public function applyAutoDiscount()	
	{
		$auto_discount 	= 0;
		$discount_label = '';
		
		$subTotal  = $this->NumberFormat($this->getSubTotal() - $this->getGiftCertiTotal()); 			
		
		if($subTotal <= 0 ) 
		{ 
			Session::put('ShoppingCart.AutoDiscount', 0.0); 
			Session::put('ShoppingCart.AutoDiscountLabel', $discount_label);
			Session::save();
			return NULL; 
		}		
				
		
		$AutoRS = DB::table($this->prefix.'auto_discount')
					->select('*')											
					->where('start_date','<=',date('Y-m-d'))
					->where('end_date','>=',date('Y-m-d'))
					->where('order_amount','<=',$subTotal)
					->where('status','=','1')
					//->orderBy('auto_discount_id')->first();
					->orderBy('order_amount','DESC')->first();
					
		if(!empty($AutoRS))	
		{
			if($subTotal > 0)
			{
				if($AutoRS->type == '1')	
					$auto_discount = ( $subTotal * ($AutoRS->auto_discount_amount/100) );
				else 
					$auto_discount = $AutoRS->auto_discount_amount;
			}
			else{
				$auto_discount = 0.0;
			}
			
			if(trim($AutoRS->discount_label) != '')
			{
				$discount_label = $AutoRS->discount_label;
			}
		}	
		else 
		{
			$auto_discount  = 0.0;
			$discount_label = '';
		}
		Session::put('ShoppingCart.AutoDiscount', $this->NumberFormat($auto_discount));
		Session::put('ShoppingCart.AutoDiscountLabel', $discount_label);
		return NULL;
	}
	
	public function getGiftCertiTotal()	
	{
		if(Session::has('ShoppingCart.GiftCertiTotal'))
			return $this->NumberFormat(Session::get('ShoppingCart.GiftCertiTotal'));
		
		return 0;
	}
	
	public function getGiftCertiCount()	
	{
		if(Session::has('ShoppingCart.GiftCertiCount'))
			return Session::get('ShoppingCart.GiftCertiCount');
		
		return 0;		
	}
	
	public function isCouponsAvailable() 
	{
		$CouponRS = DB::table($this->prefix.'coupon')
							->select(DB::raw('coupon_id'))
							->where('start_date','<=',date('Y-m-d'))
							->where('end_date','>=',date('Y-m-d'))
							->where('status','=','1')->first();  		
		
		if(!empty($CouponRS)) 
			return true;
		else 
			return false;
	}
	
	//End 05-12-2022
	
	
	
	
	
	
	public function getGiftCouponInfo() 
	{
		if(Session::has('ShoppingCart.GiftCoupon'))
			return Session::get('ShoppingCart.GiftCoupon');
		else{
			Session::put('ShoppingCart.GiftCoupon.Code', '');
			Session::put('ShoppingCart.GiftCoupon.Value', 0.0);
			Session::put('ShoppingCart.GiftCoupon.Applicable_Value', 0.0);
			Session::save();
			return Session::get('ShoppingCart.GiftCoupon');
		}
	}

	public function setTaxValue($val) 
	{
		Session::put('ShoppingCart.Tax', $this->NumberFormat($val));
		Session::save();
		return NULL;
	}

	public function getTaxValue() 
	{
		if(Session::has('ShoppingCart.Tax'))
			return $this->NumberFormat(Session::get('ShoppingCart.Tax'));
		
		return 0;
	}
	
	public function setTaxRateValue($val) 
	{
		Session::put('ShoppingCart.TaxRate', $this->NumberFormat($val));
		Session::save();
		return NULL;
	}

	public function setPaymentDetail($request)
	{
		
		$temp['Payment_Type']     	= $request->paymentMethod;
		$temp['Payment_Method']   	= $this->getPaymentMethodName($request->paymentMethod);
		
		if($temp['Payment_Type'] =='PAYMENT_AUTHORIZENETCC' || $temp['Payment_Type'] =='PAYMENT_LAYAWAY' || 
		   $temp['Payment_Type'] =='PAYMENT_FIDELITY')
		{
			$temp['CCType']   	= $request->CCType;
			$temp['CCNumber'] 	= $request->CCNumber;
			$temp['CCMonth']  	= $request->CCMonth;
			$temp['CCYear']   	= $request->CCYear;
			$temp['CSC']      	= $request->CSC;
			$temp['CCName']     = $request->CCName;
		}
		else
		{
			$temp['CCType']   	= '';
			$temp['CCNumber'] 	= '';
			$temp['CCMonth']  	= '';
			$temp['CCYear']   	= '';
			$temp['CSC']      	= '';
			$temp['CCName']     = '';
		}
		
		Session::put('ShoppingCart.Payment_Detail', $temp);
		Session::save();
		return NULL;
	}
	
	public function getPaymentDetail() 
	{		
		
		if(Session::has('ShoppingCart.Payment_Detail'))
			return Session::get('ShoppingCart.Payment_Detail');
		else
		{
			$temp['Payment_Type']   	= '';
			$temp['Payment_Method']   	= '';
			$temp['CCType']   	= '';
			$temp['CCNumber'] 	= '';
			$temp['CCMonth']  	= '';
			$temp['CCYear']   	= '';
			$temp['CSC']      	= '';
			$temp['CCName']     = '';
			
			Session::put('ShoppingCart.Payment_Detail', $temp);
			Session::save();
			
			return Session::get('ShoppingCart.Payment_Detail');
		}
			
	}

	public function getPaymentMethodName($pType)
	{
		switch ($pType) 
		{
			case 'PAYMENT_BRAINTREECC':
				return 'Credit Card';
				break;	
			case 'PAYMENT_BRAINTREEGOOGLEPAY':
				return 'Google Pay';
				break;	
			case 'PAYMENT_BRAINTREEAPPLEPAY':
				return 'Apple Pay';
				break;		
			case 'PAYMENT_BRAINTREEPAYPAL':
				return 'PayPal';
				break;
			default:
				return NULL;
				break;
		}
		return NULL;
	}

	public function getTaxRateValue() 
	{
		if(Session::has('ShoppingCart.TaxRate'))
			return $this->NumberFormat( Session::get('ShoppingCart.TaxRate') );
		
		return 0;
	}

	public function setTaxRateTypeValue($val) 
	{
		Session::put('ShoppingCart.TaxRateType', $val);
		Session::save();
		return NULL;
	}

	public function getTaxRateTypeValue() 
	{
		if(Session::has('ShoppingCart.TaxRateType'))
			return Session::get('ShoppingCart.TaxRateType');
		
		return null;
	}
	
	public function setOrderID ($OrderID)	
	{
		Session::put('ShoppingCart.OrderID', $OrderID);
		Session::save();
	}
	
	public function getOrderID()	
	{
		if(Session::has('ShoppingCart.OrderID'))
			return Session::get('ShoppingCart.OrderID');
		
		return '';
	}

	

	
	// Added code for Avalara Tax as on 10-10-2022 Start

	public function avalaraTaxCalculation($ship_add1, $ship_add2, $ship_city,$ship_zip, $ship_state, $ship_country, $order_no = '', $is_order='no') 
	{
		$TaxValues = '0.00';
		
		$NetTotal = $this->getNetTotal();
		if((Session::has('customer_type') && trim(strtolower(Session::get('customer_type'))) == 'memo') || ($NetTotal <= 0))
		{
			return $TaxValues;
		}
		
		
		if($is_order == 'yes')
		{
			$type = "SalesInvoice";	// Type to create a record on avalara
		}
		else
		{
			$type = "SalesOrder"; 		// Type to only get tax value and not create record
		}
		
		if(Session::has('customer_id'))
		{
			$customer_id = (int)Session::get('customer_id');
		}
		else
		{
			$customer_id = rand(999, 10000);
		}
		//AUGUSTA HOME LLC
		//AJS Creations LLC
		$client = new \Avalara\AvaTaxClient('AUGUSTA HOME LLC', '1.0', 'localhost', config('const.AVATAX_MODE'));
		$client->withSecurity(config('const.AVATAX_USERNAME'),config('const.AVATAX_PASSWORD'));
		$tb = new \Avalara\TransactionBuilder($client, "DEFAULT", $type, $customer_id);
		
		//'40 Harbor Park Dr Port', null, null, 'Washington', 'NY', '11050-4602', 'US'
		//'40 Harbor Park Dr.', null, null, 'Port Washington', 'NY', '11050', 'US'
		$tb->withAddress('ShipFrom', '40 Harbor Park Dr Port', null, null, 'Washington', 'NY', '11050-4602', 'US')
        	->withAddress('ShipTo', $ship_add1, $ship_add1, null, $ship_city, $ship_state, $ship_zip, $ship_country);
					
        //$ShopCart 		= $this->getCart();
		$ShopCart = Session::get('ShoppingCart.Cart');
        //$shippingInfo 	= $this->getShippingInfo();
		$shippingInfo = Session::get('ShoppingCart.Shipping');
        $ShopCartLength = count($ShopCart);
		$discount 		= $this->getNetDiscount();
		
		if($ShopCartLength > 0)
		{
        	for($a=0; $a<$ShopCartLength; $a++)
        	{
				$prdPrice 	= $ShopCart[$a]['TotPrice'];
				$prdSku 	= $ShopCart[$a]['SKU'];
				$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : ''; 
				$AvaCode = 'PH060771';
				if($ShopCart[$a]['parent_category_id'] == '1'){ // Rugs
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PH060771';					
				}else if($ShopCart[$a]['parent_category_id'] == '3'){ // Furniture
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PH402994';
				}else if($ShopCart[$a]['parent_category_id'] == '16'){ // Storage Furniture
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PH402994';
				}else if($ShopCart[$a]['parent_category_id'] == '38'){ // Outdoor
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PH402994';
				}else if($ShopCart[$a]['parent_category_id'] == '78'){ // hbasales
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PH402994';
				}else if($ShopCart[$a]['parent_category_id'] == '108'){ // Dining Tables
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PH402994';
				}else if($ShopCart[$a]['parent_category_id'] == '109'){ // Lighting
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
				}else if($ShopCart[$a]['parent_category_id'] == '128'){ // Accessories
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PA200546';
				}else if($ShopCart[$a]['parent_category_id'] == '132'){ // Kids
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
				}else if($ShopCart[$a]['parent_category_id'] == '141'){ // Solea
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
				}else if($ShopCart[$a]['parent_category_id'] == '166'){ // Outdoor Accessories
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PA200546';
				}else if($ShopCart[$a]['parent_category_id'] == '168'){ // Outdoor Furniture
					$prdSku 	= $ShopCart[$a]['SKU'];
					$ProductName = isset($ShopCart[$a]['ProductName']) ? trim($ShopCart[$a]['ProductName']) : '';
					$AvaCode = 'PH402994';
				}

				$quantity 	= (int)$ShopCart[$a]['Qty'];
				if($prdPrice > 0){
					$tb->withLine($prdPrice, $quantity, $prdSku, $AvaCode);
					$tb->withLineDescription($ProductName);
				}
			}
			//dd($this->getShippingCharge());
			if($this->getShippingCharge() != '')
			{
				$shipMethod = '';
				$shippingCharge = '0.00';
				$shipMethod = htmlentities($shippingInfo['ShippingMethodName']);
				//$shipping_methods_array = config('const.shipping_methods_array');
				//$shipping_method_label = isset($shipping_methods_array[strtoupper($shipMethod)]) ? $shipping_methods_array[strtoupper($shipMethod)] : $shipMethod;
				$shipping_method_label = $shipMethod;
				//$shippingCharge = $this->getShippingCharge();
				$tb->withLine($shippingCharge, 1, $shipMethod, "FR000000");
				$tb->withLineDescription($shipping_method_label);

			}
			
			if($discount > 0)
			{
				$discountVal = "-".$discount;
				$tb->withLine($discountVal, 1, 'Discount', 'OD010000');
				$tb->withLineDescription('Discount Offered to Customer');
			}

        	if($order_no != '')
			{
				$tb->withPurchaseOrderNo($order_no);
				$tb->withCommit();
			}
			
			$taxRates = $tb->create();
			//\Log::channel('avalara')->info(json_encode($taxRates).'\n');
			
			if(isset($taxRates->id) && !empty($order_no)){
				$order = Order::find($order_no);
				if($order){
					$order->avalara_transaction_id = $taxRates->id;
					$order->save();
				}
			}
			if(isset($taxRates->totalTax))
			{
				$TaxValues = $taxRates->totalTax;
			}
			//dd($taxRates);
		}
		return $TaxValues;
	}
	
	
	
	function TaxCalculation($ship_country, $ship_state, $ship_zip) 
	{
		
		$TaxValues = '0.00';
		$subTotal = ($this->getSubTotal()  + $this->getShippingCharge()) - ($this->getAutoDiscount() + $this->getQuantityDiscount() + $this->getCouponDiscount()); 
		
		//$subTotal = ($this->getSubTotal()) - ($this->getAutoDiscount() + $this->getQuantityDiscount() + $this->getCouponDiscount()); 
		$subTotal = $this->NumberFormat($subTotal);
		
		if($ship_zip == '' ) 
			$ship_zip = '0';
				
		## Compare Zip and country		
		$TaxAreaRes = TaxArea::select('tax_areas_id')
								->where('zip_from', '>=', (int)$ship_zip)
								->where('zip_to', '<=', (int)$ship_zip)
								->where('country', '=', $ship_country)
								->where('status', '=', '1')
								->first();
		
		if (!empty($TaxAreaRes))	
		{
			$TaxAreaRatesRes = TaxAreaRates::where('tax_areas_id', '=', (int)$TaxAreaRes->tax_areas_id)
								->where('amount_from', '<=', $subTotal)
								->orderBy('amount_from', 'DESC')
								->first();
			
			if(!empty($TaxAreaRatesRes))
			{
				$pertex = $TaxAreaRatesRes->charge_amount;
				
				if($subTotal>=$TaxAreaRatesRes->amount_from)
				{
					
					if ($TaxAreaRatesRes->amount_in_percent == 'Y')	
					{
						$temp_tax = (($subTotal * $pertex) / 100);
						$this->setTaxValue($temp_tax);
						$TaxValues = $temp_tax;
						return $this->NumberFormat($TaxValues);
						//return NULL;
					}	
					else	
					{
						//echo $pertex; exit;
						$this->setTaxValue($pertex);
						$TaxValues = $pertex;
						return $this->NumberFormat($TaxValues);
						//return NULL;
					}
				}	
			}
		}
		
		## Compare Country or State or Zip
		$TaxAreaRes = TaxArea::select('tax_areas_id')
								->where('country', '=', $ship_country)								
								->where('status', '=', '1')
								->whereRaw("states = '".$ship_state."' OR (zip_from >=". (int)$ship_zip." AND zip_to <= ".(int)$ship_zip.")")
								
								->first();
		//dd($TaxAreaRes);
		if (!empty($TaxAreaRes))	
		{				
			$TaxAreaRatesRes = TaxAreaRates::where('tax_areas_id', '=', (int)$TaxAreaRes->tax_areas_id)
								->where('amount_from', '<=', $subTotal)
								->orderBy('amount_from', 'DESC')
								->first();
			//dd($TaxAreaRatesRes);
			if(!empty($TaxAreaRatesRes))
			{
				$pertex = $TaxAreaRatesRes->charge_amount;
				
				if($subTotal>=$TaxAreaRatesRes->amount_from)
				{
					if ($TaxAreaRatesRes->amount_in_percent == 'Y')	
					{
						$temp_tax = (($subTotal * $pertex) / 100);						
						$this->setTaxValue($temp_tax);
						$TaxValues = $temp_tax;
						return $this->NumberFormat($TaxValues);
						//return NULL;
					}	
					else	
					{
						$this->setTaxValue($pertex);
						//echo $pertex; exit;
						$TaxValues = $pertex;
						return $this->NumberFormat($TaxValues);
						//return NULL;
					}
				}	
			}
		}
		
		## Compare Country 				
		$TaxAreaRes = TaxArea::select('tax_areas_id')
								->where('country', '=', $ship_country)								
								->where('country', '!=', 'US')																
								->where('status', '=', '1')
								->first();
		
		if (!empty($TaxAreaRes))	
		{				
			$TaxAreaRatesRes = TaxAreaRates::where('tax_areas_id', '=', (int)$TaxAreaRes->tax_areas_id)
								->where('amount_from', '<=', $subTotal)
								->orderBy('amount_from', 'DESC')
								->first();
		
			if(!empty($TaxAreaRatesRes))
			{
				$pertex = $TaxAreaRatesRes->charge_amount;
				
				if($subTotal>=$TaxAreaRatesRes->amount_from)
				{
					if ($TaxAreaRatesRes->amount_in_percent == 'Y')	
					{
						$temp_tax = (($subTotal * $pertex) / 100);						
						$this->setTaxValue($temp_tax);
						$TaxValues = $temp_tax;
						return $this->NumberFormat($TaxValues);
						//return NULL;
					}	
					else	
					{
						$this->setTaxValue($pertex);
						//echo $pertex; exit;
						$TaxValues = $pertex;
						return $this->NumberFormat($TaxValues);
						//return NULL;
					}
				}	
			}
		}					
		$this->setTaxValue(0);
		//$TaxValues = $temp_tax;
		//return $TaxValues;
		return NULL;
	}
	
	public function get_Braintree_APIDetails()
	{
		$bt_res = DB::table($this->prefix.'payment_methods')
				->select('*')
				->where('pm_status','=','Active')
				->where('pm_group_name','=','PAYMENT_BRAINTREECC')
				->orderBy('pm_type','desc')
				->first();	
		
		$bt_details = array();
		$bt_details['IS_BRAINTREE_CHECKOUT'] 	  = 'No';
		$bt_details['BRAINTREE_MERCHANT_ID'] 	  = '';
		$bt_details['BRAINTREE_PUBLIC_API_KEY']   = '';
		$bt_details['BRAINTREE_PRIVATE_API_KEY']  = '';
		$bt_details['BRAINTREE_TOKENIZATION_KEY'] = '';
		$bt_details['BRAINTREE_GOOGLE_MERCHANT_ID'] = '';
		$bt_details['BRAINTREE_TRANSACTION_MODE'] = '';
		//echo "<pre>"; print_r($bt_res); 
		if(!empty($bt_res)) 
		{
			$arrPEVar						= unserialize($bt_res->pm_details);					
			$bt_details['IS_BRAINTREE_CHECKOUT'] = 'Yes';
			$bt_details['BRAINTREE_MERCHANT_ID']  	  	=	GlobalHelper::Decrypt($arrPEVar['BRAINTREE_MERCHANT_ID']);
			$bt_details['BRAINTREE_PUBLIC_API_KEY']   	=	GlobalHelper::Decrypt($arrPEVar['BRAINTREE_PUBLIC_API_KEY']);
			$bt_details['BRAINTREE_PRIVATE_API_KEY']  	=	GlobalHelper::Decrypt($arrPEVar['BRAINTREE_PRIVATE_API_KEY']);
			$bt_details['BRAINTREE_TOKENIZATION_KEY'] 	=	GlobalHelper::Decrypt($arrPEVar['BRAINTREE_TOKENIZATION_KEY']);
			$bt_details['BRAINTREE_GOOGLE_MERCHANT_ID'] =	'';
			$bt_details['BRAINTREE_TRANSACTION_MODE'] 	=	$arrPEVar['BRAINTREE_TRANSACTION_MODE'];
		}
		//dd($bt_details);
		//exit;
		$bt_Data = (object) $bt_details;
		return $bt_Data;
	}
	
	public function AvaTaxValidateAddr(Request $request)
	{
		$ship_country 	= $request->ship_country;
		$ship_state 	= $request->ship_state;
		$ship_zip  		= $request->ship_zip;
		$ship_add1      = $request->ship_addr_1;
		$ship_add2      = $request->ship_addr_2;
		$ship_city		= $request->ship_city;

		$check_zip 		= $ship_zip."-";
		$cur_zip_code 	= $ship_zip;
		$current_state  = $ship_state;
		
		$data = array(
			'textCase' 		=> 'Upper',
		    'line1' 		=> $ship_add1,
		    'line2' 		=> $ship_add2,
		    'city' 			=> $ship_city,
		    'region' 		=> $ship_state,
		    'country'	 	=> $ship_country,
		    'postalCode' 	=> $ship_zip,
		);

		$client = new \Avalara\AvaTaxClient('AUGUSTA HOME LLC', '1.0', 'localhost', config('const.AVATAX_MODE'));
		$client->withSecurity(config('const.AVATAX_USERNAME'),config('const.AVATAX_PASSWORD'));

		$validateAddress = $client->resolveAddressPost($data);
		//dd($validateAddress);
		if(isset($validateAddress->messages))
		{
			$response       = $validateAddress->messages[0]->severity;
			$error_detail   = $validateAddress->messages[0]->summary;
		}
		else
		{
			$response 		= '';
			$error_detail 	= '';
		}
		
		if(isset($validateAddress->validatedAddresses))
		{
			$responce_zip_code  = $validateAddress->validatedAddresses[0]->postalCode;
			$responce_state     = $validateAddress->validatedAddresses[0]->region;

			$sug_address = $validateAddress->validatedAddresses[0]->line1."##".$validateAddress->validatedAddresses[0]->line2."##".$validateAddress->validatedAddresses[0]->city."##".$validateAddress->validatedAddresses[0]->region."##".$validateAddress->validatedAddresses[0]->country."##".$validateAddress->validatedAddresses[0]->postalCode;
		}
		else
		{
			$responce_zip_code  = '';
			$responce_state     = '';
			$sug_address 		= '';
		}

		$is_same_zip = 'no';

		if(strpos($responce_zip_code, $check_zip) !== false || $responce_zip_code == $cur_zip_code)
        {
            $is_same_zip = 'yes';
        }

		if($response != '' && $response == 'Error')
		{
			$message = "Invalid##".$error_detail;
		}
		else if($response == '' &&  ($cur_zip_code != $responce_zip_code || $is_same_zip == 'no' || $current_state != $responce_state))
		{
			$message = "ValidZip##".$sug_address;
		}
		else
		{
		    $message = "Valid";
		}
		return $message;
	}
	
	// Added code for Avalara Tax as on 10-10-2022 End
}
