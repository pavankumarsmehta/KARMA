<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Traits\EncryptTrait;
use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\generalTrait;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\AutoDiscount;
use App\Models\QuantityDiscount;
use App\Models\Shoppingcart;
use App\Models\Wishlist;
use DB;
use Session;
use Cookie;
use App\Models\DealWeek;
use Cache;

trait CartTrait
{
	//use CommonTrait;
	use generalTrait;
	use EncryptTrait;

	public function ShowCart()
	{
		$ShoppingCart = [];
		if (Session::has('ShoppingCart')) {
			$ShoppingCart = Session::get('ShoppingCart');
		}
		return $ShoppingCart;
	}

	public function AddToCart($products_id, $qty = 1, $cookiee = 'No', $button_type = "add_to_bag", $page = "product_detail")
	{ 
		$ProductChkStock = $this->ProductCheckInStock($products_id, $qty, "insert", $cookiee);
		//dd($ProductChkStock);
		$CartErrors = [];
		if ($ProductChkStock == '1111')
			$CartErrors[] = config('fmessages.Cart.ProductNotAvailable');
		if ($ProductChkStock == '2222')
			$CartErrors[] = config('fmessages.Cart.QuantityNotAvailable');

		//dd($CartErrors);
		if (count($CartErrors) > 0) {
			Session::flash('CartErrors', $CartErrors);
			return response()->json(array('Added' => 0, 'CartErrors' => $CartErrors));
		}

		if ($cookiee == 'Yes')
			$ProductChkFlg = $this->ProductCheckInCart($products_id, $qty, 'insert', $cookiee);
		else
			$ProductChkFlg = $this->ProductCheckInCart($products_id, $qty);


		if ($ProductChkFlg == 1) {
			$a = $this->CalculateSubTotal();
			return response()->json(array('Added' => 0));
		}


		$per = 0;
		$val = 0;

		$ProdInfo = DB::table('hba_products as p')
			->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
			->where(function ($query) {
				$query->orWhere('p.status', '=', '1');
			})
			->where('p.product_id', '=', $products_id)->get();

		//$cart_arr[$key]['accessories_products'] = $this->getAccessoriesProducts($val->accessories);
		if (!$ProdInfo || $ProdInfo->count() == 0) {
			return response()->json(array('Added' => 0));
		}


		$ProductRs = $this->SetProduct($ProdInfo[0]);



		$ProductRs->product_zoom_image = Get_Product_Image_URL($ProductRs->image_name, 'ZOOM');
		$ProductRs->product_medium_image = Get_Product_Image_URL($ProductRs->image_name, 'MEDIUM');
		$ProductRs->product_thumb_image = Get_Product_Image_URL($ProductRs->image_name, 'THUMB');
		$ProductRs->product_small_image = Get_Product_Image_URL($ProductRs->image_name, 'SMALL');

		$p_link = $ProductRs->product_url;


		$temp_ary = array();
		$temp_ary['product_id']   		= $ProductRs->product_id;
		$temp_ary['SKU']         		= $ProductRs->sku;
		$temp_ary['ProductName'] 		= $ProductRs->product_name;
		$temp_ary['short_description'] 	= strip_tags($ProductRs->product_description);
		$temp_ary['on_sale'] 	= strip_tags($ProductRs->on_sale);

		$ProductName_description 		= $temp_ary['short_description'];


		//deal of week
		$allDealOFWeekArr = get_deal_of_week_by_sku();
		if(isset($allDealOFWeekArr) && !empty($allDealOFWeekArr)){
			if(isset($allDealOFWeekArr[$ProductRs->sku]) && !empty($allDealOFWeekArr[$ProductRs->sku])){
				$ProductRs->our_price = $allDealOFWeekArr[$ProductRs->sku]->deal_price;
				$ProductRs->sale_price = $allDealOFWeekArr[$ProductRs->sku]->deal_price;
				$ProductRs->deal_description = $allDealOFWeekArr[$ProductRs->sku]->description;
			}	
		}	

		if (strlen($ProductName_description) > 34)
			$temp_ary['ProductName_description'] = substr($ProductName_description, 0, 34) . '...';
		else
			$temp_ary['ProductName_description'] = $ProductName_description;

		$check_deal_of_week = $this->check_product_on_deal($ProductRs->sku);
		if(!empty($check_deal_of_week)){
			$ProductPriceRs = $this->Get_Price_Val_Obj($ProductRs, $check_deal_of_week->deal_price,false);
		}else{
			$ProductPriceRs = $this->Get_Price_Val_Obj($ProductRs,'',false);
		}
		## set check out process price
		$temp_ary['retail_price']   = $ProductPriceRs['retail_price'];
		$temp_ary['our_price']    	= $ProductPriceRs['our_price'];
		$temp_ary['sale_price'] 	= $ProductPriceRs['sale_price'];

		$temp_ary['retail_price_disp']   = $ProductPriceRs['retail_price_disp'];
		$temp_ary['our_price_disp']    	= $ProductPriceRs['our_price_disp'];
		$temp_ary['sale_price_disp'] 	= $ProductPriceRs['sale_price_disp'];
		if(isset($ProductRs->deal_description) && !empty($ProductRs->deal_description)){
			$temp_ary['deal_description'] 	= $ProductRs->deal_description;
		}


		$temp_ary['Qty'] 		 	= $qty;

		if(!empty($check_deal_of_week)){
				$temp_ary['TotPrice']    	= NumberFormat($temp_ary['our_price'] * $temp_ary['Qty']);
		}elseif($temp_ary['sale_price'] > 0 && $temp_ary['on_sale'] == 'Yes'){
				$temp_ary['TotPrice']    	= NumberFormat($temp_ary['sale_price'] * $temp_ary['Qty']);
		}else{
				$temp_ary['TotPrice']    	= NumberFormat($temp_ary['our_price'] * $temp_ary['Qty']);
		}
		$temp_ary['Image']       	= $ProductRs->product_medium_image;
		$temp_ary['product_url']    = $p_link;
		$temp_ary['product_group_code']     = $ProductRs->product_group_code;
		$temp_ary['parent_sku']     = $ProductRs->parent_sku;
		$temp_ary['parent_category_id'] = $ProductRs->parent_category_id;

		$temp_ary['size_dimension']     = $ProductRs->size;
		$temp_ary['product_width']      = $ProductRs->product_width;
		$temp_ary['product_height']     = $ProductRs->product_height;
		$temp_ary['product_length']     = $ProductRs->product_length;
		$temp_ary['shipping_text']      = $ProductRs->shipping_text;
		$temp_ary['shipping_days']      = $ProductRs->shipping_days;
		$temp_ary['upc']         		= $ProductRs->upc;

		if(auth()->user()){
			$productWish = Wishlist::whereProductsId($ProductRs->product_id)->whereCustomerId(auth()->user()->customer_id)->first();	
			$temp_ary['is_wish']      = $productWish ? true : false;
		} else {
			$temp_ary['is_wish'] = false;
		}
		if ($temp_ary['our_price'] <= 0 && $temp_ary['sale_price'] <= 0) {
			return response()->json(array('Added' => 0));
		}

		$Cart = Session::get('ShoppingCart.Cart');


		if ($Cart && count($Cart) > 0)
			$Cart = array_values($Cart);

		$Cart[] = $temp_ary;
		
		//dd($Cart);
		Session::put('ShoppingCart.Cart', $Cart);

		$a = $this->CalculateSubTotal();

		$AllDiscounts = $this->GetAllDiscounts();

		$TotalValue = NumberFormat(Session::get('ShoppingCart.SubTotal')) - $AllDiscounts['TotalDiscount'];

		if (isset($ProductRs->products_id)) {
			return response()->json(array('Added' => 1));
		}
		return response()->json(array('Added' => 0));
	}


	public function getTotalQuantity()
	{
		$ShoppingCart = array(); // Session::get('ShoppingCart.Cart');
		if (Session::has('ShoppingCart.Cart')) {
			$ShoppingCart = Session::get('ShoppingCart.Cart');
		}
		$tot_qty = 0;
		for ($s = 0; $s < count($ShoppingCart); $s++) {
			if (isset($ShoppingCart[$s]['Qty'])) {
				$tot_qty = $tot_qty + $ShoppingCart[$s]['Qty'];
			}
		}
		Session::put('ShoppingCart.total_qty', $tot_qty);
		return $tot_qty;
	}


	public function CalculateNetTotal()
	{
		$SubTotal = Session::get('ShoppingCart.SubTotal');
		$shipping_charges = 0;
		$CouponDiscount = Session::get('ShoppingCart.CouponDiscount');
		$QuantityDiscount = Session::get('ShoppingCart.QuantityDiscount');
		$AutoDiscount = Session::get('ShoppingCart.AutoDiscount');

		$net_total = $SubTotal + $shipping_charges - $CouponDiscount - $QuantityDiscount - $AutoDiscount;
		return Make_Price($net_total);
	}
	public function GetAllDiscounts($DiscountName = '')
	{
		$Discounts = [];
		if (Session::has('ShoppingCart.AutoDiscount') && Session::get('ShoppingCart.AutoDiscount') > 0)
			$Discounts['AutoDiscount'] = ['label' => 'Auto Discount', 'discount' => Session::get('ShoppingCart.AutoDiscount')];
		if (Session::has('ShoppingCart.QuantityDiscount') && Session::get('ShoppingCart.QuantityDiscount') > 0)
			$Discounts['QuantityDiscount'] = ['label' => 'Quantity Discount', 'discount' => Session::get('ShoppingCart.QuantityDiscount')];

		$CouponTotal = 0;
		if (Session::has('ShoppingCart.CouponDiscount') && Session::get('ShoppingCart.CouponDiscount') > 0)
			$CouponTotal += NumberFormat(Session::get('ShoppingCart.CouponDiscount'));

		if ($CouponTotal > 0)
			Session::put('ShoppingCart.CouponDiscount', $CouponTotal);


		if ($DiscountName != '') {
			$DiscountDetail = 0;
			if (isset($Discounts[$DiscountName]))
				$DiscountDetail = NumberFormat($Discounts[$DiscountName]['discount']);
			return $DiscountDetail;
		} else {
			$TotalDiscount = array_sum(array_column($Discounts, 'discount'));
			$DiscountInfo  = ['Discounts' => $Discounts, 'TotalDiscount' => NumberFormat($TotalDiscount)];
			return $DiscountInfo;
		}
	}

	public function ProductCheckInStock($Product_id, $qty = 1, $opt, $cookiee = 'No')
	{
		//echo $Product_id."<br>".$qty; exit;
		//DB::enableQueryLog();
		$ProdInfo = DB::table('hba_products as p')
			->join('hba_products_category as pc', 'p.product_id', '=', 'pc.products_id')
			->where(function ($query) {
				$query->orWhere('p.status', '=', '1');
			})
			->where('p.product_id', '=', $Product_id)->get();
		//dd(DB::getQueryLog());

		if (!$ProdInfo || $ProdInfo->count() == 0)
			return 1111;

		//dd($cookiee);
		if ($cookiee == 'Yes' && $opt == "insert") {
			$originalquantity = $this->ProductStockInCart($Product_id);

			if ($originalquantity > $qty) {
				$productQuantity = $qty + $originalquantity;
			} else {
				$productQuantity = $originalquantity;
			}
		}

		if ($cookiee == 'No') {
			if ($opt == "insert") {

				$productQuantity = $this->ProductStockInCart($Product_id) + $qty;
			} else {
				$productQuantity = $qty;
			}
		}

		$ProductStock = $this->SetProduct($ProdInfo[0]);
		$availableStock =  $ProductStock->current_stock - $productQuantity;
		// dd($ProductStock->current_stock,$productQuantity); exit;
		return ($ProductStock->current_stock >= $productQuantity) ? 3333 : 2222;
		// return ($productQuantity > $availableStock)?2222:3333;
	}

	public function SetProduct($Product)
	{
		if ($Product->current_stock > 0) {
			$Product->stock = $Product->current_stock;
		} else {
			return redirect()->back();
		}

		$Product->retail_price = isset($Product->retail_price) ? $Product->retail_price : 0;
		if ($Product->sale_price > 0) {
			$Product->price = isset($Product->sale_price) ? $Product->sale_price : 0;
		} else {
			$Product->price = isset($Product->our_price) ? $Product->our_price : 0;
		}
		$Product->current_stock = isset($Product->stock) ? $Product->stock : 0;

		return $Product;
	}

	public function getSystemStockAvalilable($Product)
	{

		if ($Product->minimum_stock > $Product->current_stock && $Product->current_stock <= 0)
			return "Out";
		else
			return "In";
	}
	public function ProductCheckInCart($products_id, $qty, $opt = 'insert', $cookiee = 'No', $giftwrap = 'No')
	{
		$Cart = Session::get('ShoppingCart.Cart');
		$ProductInCart = 0;
		if (Session::has('ShoppingCart.Cart') && count($Cart) > 0) {
			if ($qty == 0)
				$qty = 1;
			for ($a = 0; $a < count($Cart); $a++) {
				if ($Cart[$a]['product_id'] == $products_id && $products_id != 0 && !isset($Cart[$a]['IS_Free_Gift'])) {
					if ($opt == 'insert') {
						if ($cookiee == 'Yes') {
							if ($Cart[$a]['Qty'] > $qty) {
								$Cart[$a]['Qty'] += $qty;
							} else {
								$Cart[$a]['Qty'] = $qty;
							}
						} else {
							$Cart[$a]['Qty'] += $qty;
						}
					} else {
						$Cart[$a]['Qty'] = $qty;
					}

if($Cart[$a]['sale_price'] > 0 && $Cart[$a]['on_sale'] == 'Yes')
{
	$Cart[$a]['TotPrice'] = NumberFormat($Cart[$a]['Qty'] * $Cart[$a]['sale_price']);
}
else
{
	$Cart[$a]['TotPrice'] = NumberFormat($Cart[$a]['Qty'] * $Cart[$a]['our_price']);
}
					$ProductInCart = 1;
				}
			}
		}
		if ($ProductInCart == 1) {
			Session::put('ShoppingCart.Cart', $Cart);
			return true;
		} else {
			return false;
		}
	}

	public function ProductCheckInBuyCart($products_id, $qty, $opt = 'insert', $cookiee = 'No', $giftwrap = 'No')
	{
		$Cart = Session::get('ShoppingCart.BuyCart');

		$ProductInCart = 0;
		if (Session::has('ShoppingCart.BuyCart') && count($Cart) > 0) {
			if ($qty == 0)
				$qty = 1;
			for ($a = 0; $a < count($Cart); $a++) {
				if ($Cart[$a]['product_id'] == $products_id && $products_id != 0) {
					if ($opt == 'insert') {
						if ($cookiee == 'Yes') {
							if ($Cart[$a]['Qty'] > $qty) {
								$Cart[$a]['Qty'] = $qty;
							} else {
								$Cart[$a]['Qty'] = $qty;
							}
						} else {
							$Cart[$a]['Qty'] = $qty;
						}
					} else {
						$Cart[$a]['Qty'] = $qty;
					}
					//$Cart[$a]['TotPrice'] = NumberFormat($Cart[$a]['Qty'] * $Cart[$a]['Price']);
					//$Cart[$a]['gift_wrap'] = $giftwrap;
					$ProductInCart = 1;
				}
			}
		}
		if ($ProductInCart == 1) {
			Session::put('ShoppingCart.BuyCart', $Cart);
			return true;
		} else {
			return false;
		}
	}

	public function ProductStockInCart($Product_id)
	{
		$cart_qty = 0;
		if (Session::has('ShoppingCart.Cart')) {
			$count = count(Session::get('ShoppingCart.Cart'));
			$Cart = Session::get('ShoppingCart.Cart');

			for ($a = 0; $a < $count; $a++) {
				if ($Cart[$a]['product_id'] == $Product_id && $Product_id != 0) {
					$cart_qty += $Cart[$a]['Qty'];
				}
			}
		}
		//dd('asasas',$cart_qty);
		return $cart_qty;
	}



	/*public function getNetDiscount()
	{
		$Cart = Session::get('ShoppingCart');

		$discount = ($this->getAutoDiscount() + $this->getQuantityDiscount() + $this->getCouponDiscount());
		//dd($discount);
		return 	$this->NumberFormat($discount);
	}*/

	public function NumberFormat($val)
	{
		if ($val != 'undefined') {
			return number_format($val, 2, '.', '');
		} else {
			return 0;
		}
	}
	/*
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
		Session::put('ShoppingCart.BillingAddress.email', '');
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

		$temp['AddressBookId']	= 0;

		Session::put('ShoppingCart.ShippingAddress', $temp);
		Session::save();
		return NULL;
	}

	public function getShippingAddress()
	{
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
	*/

	public function StoreCartInCookie()
	{
		//if(Session::has('ShoppingCart.cart') && count(Session::get('ShoppingCart.cart')) > 0)
		if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0) {
			//$tempCart = Session::get('ShoppingCart.cart');
			$tempCart = Session::get('ShoppingCart.Cart');
			$ArrayCookie = array();
			for ($c = 0; $c < count($tempCart); $c++) {
				$temp_cookie_array = array();
				$temp_cookie_array['sku'] = $tempCart[$c]['SKU'];
				$temp_cookie_array['qty'] = $tempCart[$c]['Qty'];
				$ArrayCookie[] = $temp_cookie_array;
			}
			if (Cookie::has("MY_SHOP_CART_COOKIE") && Cookie::get("MY_SHOP_CART_COOKIE") != "") {
				$cookie_id = Cookie::get("MY_SHOP_CART_COOKIE");
				Shoppingcart::where('cookie_id', '=', $cookie_id)->where('customer_id', '=', 0)->delete();
			}
			if (count($ArrayCookie) > 0) {
				$cookie_id = time() . "_" . Session::getId();
				Cookie::queue(Cookie::make('MY_SHOP_CART_COOKIE', $cookie_id, time() + 60 * 60 * 24 * 15));

				if (Auth::user()) {
					$result = Shoppingcart::where('customer_id', '=', Session::get('sess_icustomerid'))->get();

					if ($result && $result->count() <= 0) {
						$InsertCart = array(
							'customer_id' 		=> Session::get('sess_icustomerid'),
							'cookie_id' 		=> $cookie_id,
							'cart_string' 		=> serialize($ArrayCookie),
							'created_date' 		=> date("Y-m-d H:i:s")
						);
						DB::table($this->prefix . 'shoppingcart')->insert($InsertCart);
					} else {
						$UpdateCart = array(
							'cookie_id' 		=> $cookie_id,
							'cart_string' 		=> serialize($ArrayCookie),
							'created_date' 		=> date("Y-m-d H:i:s")
						);
						DB::table($this->prefix . 'shoppingcart')->where('customer_id', '=', Session::get('sess_icustomerid'))->update($UpdateCart);
					}
				} else {
					$InsertCart = array(
						'customer_id' 		=> '0',
						'cookie_id' 		=> $cookie_id,
						'cart_string' 		=> serialize($ArrayCookie),
						'created_date' 		=> date("Y-m-d H:i:s")
					);
					DB::table($this->prefix . 'shoppingcart')->insert($InsertCart);
				}
			}
		}
	}
	
	public function GenerateShopCartFromCookieAfterLogin() 
	{
		$ArrMyShopCart = array();
		
		$IsGiftCertificateItem = '';
		
		if( Auth::user())
		{
			$CustomerID = Session::get('sess_icustomerid');
			$ArrMyShopCart = Shoppingcart::where('customer_id','=',$CustomerID)->get();
			if($ArrMyShopCart && $ArrMyShopCart->count() > 0)
				$ArrMyShopCart = unserialize(stripslashes($ArrMyShopCart[0]["cart_string"]));
		}elseif(trim(Cookie::get('MY_SHOP_CART_COOKIE')) != ''){
			$CookieID = trim(Cookie::get('MY_SHOP_CART_COOKIE'));
			$ArrMyShopCart = Shoppingcart::where('cookie_id','=',$CookieID)->get();
			if($ArrMyShopCart && $ArrMyShopCart->count() > 0)
				$ArrMyShopCart = unserialize(stripslashes($ArrMyShopCart[0]["cart_string"]));
		}
		
        if (count($ArrMyShopCart) == 0) {
            return null;
        }

       Session::put("RemoveItem",'');
        $RemoveItem = '';
		$CartRequest = new \Illuminate\Http\Request();
		for ($p = 0; $p < count($ArrMyShopCart); $p++) {
            $prod_sku = strtolower(trim($ArrMyShopCart[$p]['sku']));
            $quantity = (int) $ArrMyShopCart[$p]['qty'];
            
           
				$ProductRs = Product::where('status','=','1')->where(DB::raw('lower(sku)'),'=',$prod_sku)->get();

				if($ProductRs && $ProductRs->count() > 0)
				{	
					$ProductRs = $this->SetProduct($ProductRs[0]);
					
					if($ProductRs->price > 0 && ($ProductRs->current_stock > 0 ))
					{
						$RemoveItem.= $prod_sku.",";
						$products_id = $ProductRs->product_id;
						$this->AddToCart($products_id,$quantity,'Yes','','');
					}
				}
        }
		//$this->StoreShopCartInCookie();
         Session::put("RemoveItem",substr($RemoveItem,0,-1));
        return null;
    }

	function check_product_on_deal($sku){
		$cacheName = 'checkProductOnDeal_cache';
		if (Cache::has($cacheName)) {
			return $check_deal_of_week = Cache::get($cacheName);
		} else {
			$table_prefix = env('DB_PREFIX', '');
			$DealDetails =[];
			$bransCollection =  DealWeek::where('product_sku','=','1')->get();
			$currentDate = getDateTimeByTimezone('Y-m-d');
			$currentDatetime = getDateTimeByTimezone('Y-m-d H:i:s');
			$dealofWeekEndDatetime = getDateTimeByTimezone('Y-m-d H:i:s',date('Y-m-d 23:59:00'));
			$dealofWeekdiffInMinutes = diffInMinutes($currentDatetime,$dealofWeekEndDatetime);

			$DealOfWeeks = DB::table($table_prefix.'dealofweek as dw')
							->select('dw.dealofweek_id','dw.product_sku','dw.deal_price','p.retail_price','p.our_price','p.product_name','p.product_id')
							->join($table_prefix.'products as p','dw.product_sku','=','p.sku')
							->where('dw.product_sku', $sku)
							->where('dw.status','=','1')
							->Where('dw.display_on_home','Yes')
							->where('dw.start_date','<=',$currentDate)->where('dw.end_date','>=',$currentDate)
							->where('dw.deal_type','=','Weekly')
							->where('p.status','=','1')
							->where('p.current_stock', '>', '0')->first();
							if(!empty($DealOfWeeks)){
								//Cache::put($cacheName, $DealOfWeeks);
								return $DealOfWeeks;
							}else{
								return '0';
							}
			}
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

	
}
