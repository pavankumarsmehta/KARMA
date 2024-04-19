<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shoppingcart;
use Illuminate\Support\Facades\Route;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use App\Models\MetaInfo;
use GlobalHelper;

use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\productTrait;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\ShoppingCartTrait;
use App\Http\Controllers\Helpers;

use Illuminate\Support\Facades\DB;
use Session;
use Cookie;
use App\Models\DealWeek;
use Cache;

class ShoppingcartController extends Controller
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
		/*$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type','=',$PageType)->get();
		if($MetaInfo->count() > 0 )
		{
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}*/

		$this->PageData['meta_title'] = "Shopping Cart - HBASales.com";
		$this->PageData['meta_description'] = "Shopping Cart - HBASales.com";
		$this->PageData['meta_keywords'] = "Shopping Cart - HBASales.com";
	}

	public function index(Request $Request)
	{
		$this->PageData['CSSFILES'] = ['cart.css', 'detail.css'];
		//Session::forget('ShoppingCart');
		$cart_arr = array();
		$SubTotal = 0;
		$shipping_charge = 0;
		$Total_Amount = 0;
		$TotalQty = 0;
		$customer_id = 0;
		$coupon_code = "";

		//Session::put('ShoppingCart.Cart',$cart_arr);

		if (Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0) {
			$customer_id = Session::get('sess_icustomerid');
		}
		$cart_data = $this->setupCart();
		//dd($cart_data);
		$this->CalculateSubTotal();
		/*$data = session()->all();
		echo "<pre>";
		print_r($data);
		exit;*/
		//$recent_view = Session::get('RECENT_VIEWED_ITEMS');
		//echo Session::has('ShoppingCart.CouponCode'); exit;
		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
		}else{
			$curencySymbol = '$';
		}
	

		$this->PageData['cart_data'] = $cart_data;
		$this->PageData['AutoDiscount'] = Session::get('ShoppingCart.AutoDiscount');

		$this->PageData['QuantityDiscount'] = Session::get('ShoppingCart.QuantityDiscount');
		$this->PageData['AutoQuantityDiscount'] = Session::get('ShoppingCart.AutoQuantityDiscount');

		$this->PageData['SubTotal'] = Session::get('ShoppingCart.SubTotal');
		$this->PageData['SalesTax'] = Make_Price('0.00');

		$this->PageData['Total_Amount'] =  $this->CalculateNetTotal();

		$this->PageData['CouponDiscount'] = Session::get('ShoppingCart.CouponDiscount');
		if (Session::has('ShoppingCart.CouponCode')) {
			$coupon_code = Session::get('ShoppingCart.CouponCode');
		}
		$this->PageData['CouponCode'] = $coupon_code;

		//$this->PageData['wishlist_products'] = $this->getWishlistProducts();

		//$this->PageData['recent_viewed_products'] = $this->getRecentViewedProducts();
		//$this->PageData['recent_viewed_products'] = array();
		//echo "<pre>"; print_r($cart_data); exit;
		$this->PageData['cart_product_ids'] = $this->getCartProductIds();
		$this->PageData['recent_viewed_products'] = $this->getRecent_ViewedItems();

		$this->PageData['TotalQty'] = $this->getTotalQuantity(); //Session::get('ShoppingCart.TotalQty');
		$this->PageData['customer_id'] =  $customer_id;

		//echo $this->PageData['prod_url'] = Get_Product_URL(7485,'Binsor Flush Mount','No',109,'FLU4054A','Flush Mount Ceiling Lights');

		//$this->PageData['JSFILES'] = ['cart.js','slidebars.js','jquery.drilldown.js','owl.carousel.min.js','custom.js'];
		$this->PageData['JSFILES'] = ['cart.js', 'slidebars.js'];
		//return view('shoppingcart.cart')->with($this->PageData);

		$bt_api_details = $this->get_Braintree_APIDetails();
		//dd($bt_api_details);
		$this->PageData['bt_api_details'] =  $bt_api_details;
		
		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForStaticPages('Your Cart');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################

		$this->PageData['CurencySymbol'] = $curencySymbol;
		if($_SERVER['REMOTE_ADDR']=='122.167.69.240'){
			//dd(Session::get('ShoppingCart'));
		}

		return view('shoppingcart.cart')->with($this->PageData);
	}

	public function getCartProductIds()
	{
		$product_id_arr = array();
		if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0) {
			$prodcart_arr = Session::get('ShoppingCart.Cart');
			for ($i = 0; $i < count($prodcart_arr); $i++) {
				array_push($product_id_arr, $prodcart_arr[$i]['product_id']);
			}
		}
		return $product_id_arr;
	}

	public function SetCart(Request $request)
	{
		//Session::forget('ShoppingCart');
		//exit;
		if ($request->ajax()) {
			if (isset($request->action)) {
				if ($request->action == 'insert') {
					$products_id = (int)$request->products_id;
					if (!isset($request->prodqty) && empty($request->prodqty))
						$quantity = 1;
					else
						$quantity = (int)$request->prodqty;

						// check button type
						if (isset($request->button_type) && !empty($request->button_type) && $request->button_type == "buy_now"){
							$button_type = "buy_now";
						}else{
							$button_type = "add_to_bag";
						}

						// check page(Home deal section buy now button, Product detail page buy now button)
						if (isset($request->page) && !empty($request->page) && $request->page == "buy_now"){
							$page = $request->page;
						}else{
							$page = "";
						}

						// if (isset($request->price) && !empty($request->price)){
						// 	$price = $request->price;
						// }else{
						// 	$price = "";
						// }

						return $this->AddToCart($products_id, $quantity, $cookiee = 'No', $button_type, $page);
				}
				if ($request->action == 'remove') {
					return $this->RemoveFromCart($request->CartID);
				}
				if ($request->action == 'update') {
					$products_id = (int)$request->products_id;
					if (!isset($request->prodqty) && empty($request->prodqty))
						$quantity = 1;
					else
						$quantity = (int)$request->prodqty;
					return $this->UpdateCart($products_id, $quantity, $giftwrap);
				}
			}
			if ($request->action == 'clear_bag') {
				Session::forget('ShoppingCart');
				return true;
			}
		}
	}

    public function setTempCookie()
	{
		$cookie_id = "1625899628_"; //time()."_".Session::getId();
		Cookie::make('MY_SHOP_CART_COOKIE', $cookie_id, time() + 60 * 60 * 24 * 15);
	}

	public function UpdateQty($CartID, $prod_qty, $update_quantity)
	{
		//$ShoppingCart = Session::get('ShoppingCart.cart');
		$ShoppingCart = Session::get('ShoppingCart.Cart');
		$product_id = $ShoppingCart[$CartID]['product_id'];
		$ProductChkStock = $this->ProductCheckInStock($product_id, $prod_qty, "update_quantity");
		$CartErrors = [];
		if ($ProductChkStock == '1111')
			$CartErrors[] = config('fmessages.Cart.ProductNotAvailable');
		if ($ProductChkStock == '2222')
			$CartErrors[] = config('fmessages.Cart.QuantityNotAvailable');

		//dd($CartErrors);
		if (count($CartErrors) > 0) {
			//Session::flash('CartErrors', $CartErrors);
			return response()->json(array('CartErrors' => $CartErrors));
		}

		//dd($ShoppingCart);
		if ($CartID != '' && $ShoppingCart != null && count($ShoppingCart) > 0) {
			if ($CartID >= 0) {
				if (isset($ShoppingCart[$CartID])) {

					$oprice = 0;
					$sprice = 0;
					if(isset($ShoppingCart[$CartID]['sale_price']) && $ShoppingCart[$CartID]['sale_price'] > 0 && $ShoppingCart[$CartID]['on_sale'] == 'Yes')
					{
						$oprice = $ShoppingCart[$CartID]['our_price'];
						$sprice = $ShoppingCart[$CartID]['sale_price'];
					}
					else
					{
						$oprice = $ShoppingCart[$CartID]['retail_price'];
						$sprice = $ShoppingCart[$CartID]['our_price'];
					}

					$ShoppingCart[$CartID]['Qty'] = $prod_qty;
					$ShoppingCart[$CartID]['TotPrice'] = Make_Price($ShoppingCart[$CartID]['Qty'] * $sprice);
					$ShoppingCart[$CartID]['oldTotPrice'] = Make_Price($ShoppingCart[$CartID]['Qty'] * $oprice);
				}
			}
			$ShoppingCart = array_values($ShoppingCart);
			//Session::put('ShoppingCart.cart',$ShoppingCart);
			Session::put('ShoppingCart.Cart', $ShoppingCart);
			//$ShoppingCart = Session::get('ShoppingCart.cart');
			$ShoppingCart = Session::get('ShoppingCart.Cart');
		}
		$this->getTotalQuantity();
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
		Session::put('ShoppingCart.TotalQty', $tot_qty);
		return $tot_qty;
	}

	public function RemoveFromCart($CartID)
	{
		//$ShoppingCart = Session::get('ShoppingCart.cart');
		$ShoppingCart = Session::get('ShoppingCart.Cart');

		if ($CartID != '' && $ShoppingCart != null && count($ShoppingCart) > 0) {
			if ($CartID >= 0) {
				//GA4 Google Remove from cart code Start
				if(isset($ShoppingCart[$CartID]))
				{
					$GA4_GOOGLE_REMOVE_TO_CART_EVENT_DATA = '';
					$ga4_google_remove_to_cart_item_str_gtm = "";

					if(isset($ShoppingCart) && count($ShoppingCart) > 0)
					{
						$oprice = 0;
						$sprice = 0;
						if(isset($ShoppingCart[$CartID]['sale_price']) && $ShoppingCart[$CartID]['sale_price'] > 0 && $ShoppingCart[$CartID]['on_sale'] == 'Yes')
						{
							$oprice = $ShoppingCart[$CartID]['our_price'];
							$sprice = $ShoppingCart[$CartID]['sale_price'];
						}
						else
						{
							$oprice = $ShoppingCart[$CartID]['retail_price'];
							$sprice = $ShoppingCart[$CartID]['our_price'];
						}


						$ga4_google_remove_to_cart_item_str_gtm .= '{
							"item_id": "'.$ShoppingCart[$CartID]['SKU'].'",
							"item_name": "'.$ShoppingCart[$CartID]['ProductName'].'",
							"affiliation": "HBASales",
							"coupon": "",
							"discount": "",
							"index": "0",
							"item_brand": "HBASales",
							"currency": "USD",
							"item_category": "",
							"price": "'.$sprice.'",
							"quantity": "'.$ShoppingCart[$CartID]['Qty'].'"
						},';

					}
					/*$SubTotalVal = 0;
					if(Session::get('ShoppingCart.SubTotal') > 0)
					{
						$SubTotalVal = Session::get('ShoppingCart.SubTotal');
					}*/
					$GA4_GOOGLE_REMOVE_TO_CART_EVENT_DATA = '
					dataLayer.push({ ecommerce: null });
					dataLayer.push({
						event: "remove_from_cart",
						ecommerce: {
							"currency": "USD",
							"value": "'.Session::get('ShoppingCart.SubTotal').'",
							"items": ['.rtrim($ga4_google_remove_to_cart_item_str_gtm, ',').']
						}
					});';
					//dd($GA4_GOOGLE_REMOVE_TO_CART_EVENT_DATA);
					$this->PageData['GA4_GOOGLE_REMOVE_TO_CART_EVENT_DATA'] =  $GA4_GOOGLE_REMOVE_TO_CART_EVENT_DATA;
				}
				//GA4 Google Remove from cart code end
				if (isset($ShoppingCart[$CartID])) {
					unset($ShoppingCart[$CartID]);
				}
			}

			$ShoppingCart = array_values($ShoppingCart);
			Session::put('ShoppingCart.Cart', $ShoppingCart);

			$this->CalculateSubTotal();
			$Msg = "Item removed successfully.";
			$this->getTotalQuantity();
			return response()->json(['message' => $Msg]);
		}
	}

	


	public function GetCartHTML(Request $request)
	{
		if ($request->ajax()) {
			$ShoppingCart = [];
			$TotalItemInCart = 0;

			if (Session::has('ShoppingCart')) {
				$ShoppingCart = Session::get('ShoppingCart');
				//dd($ShoppingCart);
				if (isset($ShoppingCart['Cart']) && count($ShoppingCart['Cart']) > 0)
					$TotalItemInCart = $ShoppingCart['TotalItemInCart'];
			}

			$this->PageData['CartDetails'] = $ShoppingCart;
			//$this->SetAmazonConfig();
			//$this->StoreShopCartInCookie();

			$this->StoreCartInCookie();
			
			//dd($ShoppingCart);
			//GA4 Google Add to cart code Start
			$GA4_GOOGLE_ADD_TO_CART_EVENT_DATA = '';
			$ga4_google_add_to_cart_item_str_gtm = "";

			$ga4cart = array();
			if(isset($ShoppingCart['Cart']) && count($ShoppingCart['Cart']) > 0)
			{
				$ga4cart = $ShoppingCart['Cart'];

				$gg = 0;
				for($ga=0;$ga<count($ShoppingCart['Cart']);$ga++)
				{
					$oprice = 0;
					$sprice = 0;
					if(isset($ga4cart[$ga]['sale_price']) && $ga4cart[$ga]['sale_price'] > 0 && $ga4cart[$ga]['on_sale'] == 'Yes')
					{
						$oprice = $ga4cart[$ga]['our_price'];
						$sprice = $ga4cart[$ga]['sale_price'];
					}
					else
					{
						$oprice = $ga4cart[$ga]['retail_price'];
						$sprice = $ga4cart[$ga]['our_price'];
					}

					$ga4_google_add_to_cart_item_str_gtm .= '{
						"item_id": "'.$ga4cart[$ga]['SKU'].'",
						"item_name": "'.$ga4cart[$ga]['ProductName'].'",
						"affiliation": "HBASales",
						"coupon": "",
						"discount": "",
						"index": "'.$gg.'",
						"item_brand": "HBASales",
						"currency": "USD",
						"item_category": "",
						"price": "'.$sprice.'",
						"quantity": "'.$ga4cart[$ga]['Qty'].'"
					},';
					$gg++;
				}
			}
			$GA4_GOOGLE_ADD_TO_CART_EVENT_DATA = '
			dataLayer.push({ ecommerce: null });
			dataLayer.push({
				event: "add_to_cart",
				ecommerce: {
					"currency": "USD",
					"value": "'.$ShoppingCart['SubTotal'].'",
					"items": ['.rtrim($ga4_google_add_to_cart_item_str_gtm, ',').']
				}
			});';
			//dd($GA4_GOOGLE_ADD_TO_CART_EVENT_DATA);
			$this->PageData['GA4_GOOGLE_ADD_TO_CART_EVENT_DATA'] =  $GA4_GOOGLE_ADD_TO_CART_EVENT_DATA;
			//GA4 Google Add to cart code End

			$CartHTML = view('layouts.sidecart_ajax')->with($this->PageData)->render();

			return response()->json(array('ShoppingCart' => $CartHTML, 'TotalItemInCart' => $TotalItemInCart));
		}
	}

	public function cart_action(Request $Request)
	{
		$action = $Request->action;
		//echo $action; exit;
		if ($action == 'remove') {
			$this->RemoveFromCart($Request->cart_id);
			return $this->cart_details($Request);
		} elseif ($action == 'remove_sidepanel') {
			$this->RemoveFromCart($Request->cart_id);
			return $this->GetCartHTML($Request);
		} else if ($action == 'update_quantity') {
			$arr = $this->UpdateQty($Request->cart_id, $Request->prod_qty, 'update_quantity');
			if ($arr != "") {
				return $arr;
			} else {
				return $this->cart_details($Request);
			}
		} else if ($action == 'add_wishlist') {
			return $this->add_wishlist($Request);
			//return $this->cart_details($Request);
		} else if ($action == 'apply_coupon') {
			$this->apply_coupon($Request);
			return $this->cart_details($Request);
		} else if ($action == 'remove_coupon') {
			$this->remove_coupon($Request);
			return $this->cart_details($Request);
		} else if ($action == 'getcart') {
			return $this->cart_details($Request);
		} else if ($action == 'check_product_exists') {
			return $this->cart_product_exists($Request);
		}
	}

	function cart_product_exists(Request $Request)
	{
		$product_id = $Request->product_id;
		$check_exists = false;
		$cart_id = "";
		if ($product_id > 0) {
			if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0) {
				$prodcart_arr = Session::get('ShoppingCart.Cart');
				for ($i = 0; $i < count($prodcart_arr); $i++) {
					if ($prodcart_arr[$i]['product_id'] == $product_id) {
						$check_exists = true;
						$cart_id = $i;
					}
				}
			}
		}
		return response()->json(array('check_exists' => $check_exists, 'cart_id' => $cart_id));
	}



	public function remove_coupon(Request $Request)
	{
		Session::forget('ShoppingCart.CouponDiscount');
		Session::forget('ShoppingCart.CouponCode');
	}

	public function apply_coupon(Request $Request)
	{

		if (Session::has('ShoppingCart.CouponDiscount')) {
			Session::forget('ShoppingCart.CouponDiscount');
		}
		if (Session::has('ShoppingCart.CouponCode')) {
			Session::forget('ShoppingCart.CouponCode');
		}
		$discount_amount = 0;
		$CouponCode 	 = trim($Request->coupon_code);

		//$customer_id 	 = (int)Session::get('sess_icustomerid');
		$customer_id 	 = (int)Session::get('customer_id');
		//$cart_data 		 = Session::get('ShoppingCart.cart');
		$cart_data 		 = Session::get('ShoppingCart.Cart');
		$total_items = count($cart_data);
		$select  = 'start_date, end_date, type, order_amount, sku, orders, discount, is_once';
		if ($CouponCode != '10%off' && $CouponCode != "") {

			$CouponRes = DB::table($this->prefix . 'coupon')
				->where('coupon_number', $CouponCode)
				->where('status', '1')
				->where('start_date', '<=', DB::raw('curdate()'))
				->where('end_date', '>=', DB::raw('curdate()'))
				->select(DB::raw($select))->get();
			//dd($CouponRes);
			if (count($CouponRes) > 0) {
				$result = json_decode(json_encode($CouponRes), true);

				$orders = $result[0]['orders'];
				$coupon_order_amount = $result[0]['order_amount'];
				$coupon_discount_type = $result[0]['type'];
				$CouponDiscount = $result[0]['discount'];
				$coupon_sku = $result[0]['sku'];
				$coupon_is_once = $result[0]['is_once'];

				if ($orders == '0') { // Based on order amount
					$SubTotal = Session::get('ShoppingCart.SubTotal');
					if ($SubTotal > $coupon_order_amount) {
						if ($coupon_discount_type == '0') { // discount in amount
							$discount_amount = $CouponDiscount;
						} else if ($coupon_discount_type == '1') { // discount in percentage
							$discount_amount = Make_Price(($SubTotal * $CouponDiscount) / 100);
						}
					}
				} else if ($orders == '1') { // based on sku
					$matched_sku_total = 0;
					$arr_coupon_sku = explode(",", $coupon_sku);
					$arr_coupon_sku = array_unique(array_map('trim', $arr_coupon_sku));
					$arr_coupon_sku  = array_filter($arr_coupon_sku, 'strlen');
					if (count($arr_coupon_sku) > 0) { //sachin
						//$cart_data = Session::get('ShoppingCart.cart');
						$cart_data = Session::get('ShoppingCart.Cart');
						for ($c = 0; $c < count($cart_data); $c++) {
							if (in_array($cart_data[$c]['sku'], $arr_coupon_sku)) {
								$matched_sku_total = $matched_sku_total + $cart_data[$c]['TotPrice'];
							}
						}
					}
					if ($matched_sku_total > 0) {
						if ($coupon_discount_type == '0') { // discount in amount
							$discount_amount = $CouponDiscount;
						} else if ($coupon_discount_type == '1') { // discount in percentage
							$discount_amount = Make_Price(($matched_sku_total * $CouponDiscount) / 100);
						}
					}
				} else if ($orders == '3') { // based on categories
					$matched_cat_total = 0;
					$arr_categories = explode(",", $coupon_sku);
					$arr_categories = array_unique(array_map('trim', $arr_categories));
					$arr_categories = array_filter($arr_categories, 'strlen');
					if (count($arr_categories) > 0) {
						//$cart_data = Session::get('ShoppingCart.cart');
						$cart_data = Session::get('ShoppingCart.Cart');
						for ($c = 0; $c < count($cart_data); $c++) {
							if (in_array($cart_data[$c]['parent_category_id'], $arr_categories)) {
								$matched_cat_total = $matched_cat_total + $cart_data[$c]['TotPrice'];
							}
						}
					}
					if ($matched_cat_total > 0) {
						if ($coupon_discount_type == '0') { // discount in amount
							$discount_amount = $CouponDiscount;
						} else if ($coupon_discount_type == '1') {
							$discount_amount = Make_Price(($matched_cat_total * $CouponDiscount) / 100);
						}
					}
				} else if ($orders == '4') { // for free shipping

				}
			}
			if ($discount_amount > 0) {
				if ($coupon_is_once != '0') {
					$orderRes = DB::table($this->prefix . 'orders')
						->where('coupon_code', $CouponCode)
						->where('pay_status', 'Paid');
					if ($coupon_is_once == '1') { // Only once
						$orderRes = $orderRes->get();
						$orderRes = json_decode(json_encode($orderRes), true);
						if (count($orderRes) > 0) {
							$discount_amount = 0;
						}
					}
					if ($coupon_is_once == '2') { // Only once per customer
						if ($customer_id > 0) {
							$orderRes = $orderRes->where('customer_id', $customer_id)->get();
							//$orderRes = json_decode(json_encode($orderRes), true);
							//dd(count($orderRes));
							if (count($orderRes) > 0) {
								$discount_amount = 0;
							}
						} else {
							$discount_amount = 0;
						}
					}
				}
			}
			//echo $Request->coupon; exit;
		} else {

			if (Auth::user()) {
				if ($CouponCode != "") {
					$coupon_code = $CouponCode;
				} else {
					$coupon_code = '10%OFF';
				}
				//$result = Shoppingcart::where('customer_id', '=', Session::get('sess_icustomerid'))->get();
				$CouponRes = DB::table($this->prefix . 'coupon')
					->where('coupon_number', '=', $coupon_code)
					->where('status', '1')
					->where('start_date', '<=', DB::raw('curdate()'))
					->where('end_date', '>=', DB::raw('curdate()'))
					->select(DB::raw($select))->get();

				if (count($CouponRes) > 0) {
					$result = json_decode(json_encode($CouponRes), true);
					$orders = $result[0]['orders'];
					$coupon_order_amount = $result[0]['order_amount'];
					$coupon_discount_type = $result[0]['type'];
					$CouponDiscount = $result[0]['discount'];
					$coupon_sku = $result[0]['sku'];
					$coupon_is_once = $result[0]['is_once'];
					//dd($customer_id);
					if ($coupon_is_once == '2') { // Only once per customer
						if ($customer_id > 0) {
							$orderCustRes = DB::table($this->prefix . 'orders')
								->where('customer_id', $customer_id)->get();
							//dd($orderCustRes);
							if (count($orderCustRes) == 0) {
								//dd($orderCustRes);
								$matched_cat_total = 0;
								$cart_data = Session::get('ShoppingCart.Cart');
								$CouponCode = '10%OFF';
								for ($c = 0; $c < count($cart_data); $c++) {
									//if (in_array($cart_data[$c]['parent_category_id'], $arr_categories)) {
									$matched_cat_total = $matched_cat_total + $cart_data[$c]['TotPrice'];
									//}
								}
								if ($matched_cat_total > 0) {
									$discount_amount = Make_Price(($matched_cat_total * $CouponDiscount) / 100);
								} else {
									$discount_amount = 0;
								}
							}
						}
					}
					//dd($CouponCode);
				}
			}
		}
		if ($discount_amount > 0) {
			Session::put('ShoppingCart.CouponCode', $CouponCode);
			Session::put('ShoppingCart.CouponDiscount', Make_Price($discount_amount));
		}
		//echo $discount_amount;
	}

	public function apply_coupon11(Request $Request)
	{
		$CouponCode = $Request->CouponCode;
		$discount_amount = 0;
		if ($CouponCode != '') {
			$cur_date = date('Y-m-d');
			$select = 'start_date, end_date, type, order_amount, sku, orders, discount';
			$getDiscount = DB::table($this->prefix . 'coupon')
				->where('coupon_number', $CouponCode)
				->where('status', '1')
				->where('start_date', '<', $cur_date)
				->where('end_date', '>', $cur_date)
				->select(DB::raw($select))->get();
			if (count($getDiscount) > 0) {
				foreach ($getDiscount as $key => $val) {
					$start_date = $val->start_date;
					$end_date = $val->end_date;
					$type = $val->type;
					$order_amount = $val->order_amount;
					$sku = $val->sku;
					$orders = $val->orders;
					$discount = $val->discount;
					$SubTotal = Session::get('ShoppingCart.SubTotal');
					if ($type == 1) {
						$discount_amount = Make_Price(($SubTotal * $discount) / 100);
					} else if ($type == 0) {
						$discount_amount = $discount;
					}
				}
			}
			Session::put('ShoppingCart.CouponDiscount', Make_Price($discount_amount));

			//return $CouponCode;
		}
	}

	

	public function setupCart()
	{
		$cart_data = array();

		if (Session::has('ShoppingCart.Cart') && count(Session::get('ShoppingCart.Cart')) > 0) {
			$cart_data = Session::get('ShoppingCart.Cart');
		}
		return $cart_data;
	}

	public function cart_details(Request $Request)
	{

		$this->PageData['cart_data'] = $this->setupCart();
		$this->CalculateSubTotal();
		$CouponDiscount = 0;
		if (Session::has('ShoppingCart.CouponDiscount') && Session::get('ShoppingCart.CouponDiscount') > 0) {
			$CouponDiscount = Session::get('ShoppingCart.CouponDiscount');
		}

		//$this->PageData['SubTotal'] = Session::get('ShoppingCart.cart');
		$this->PageData['SubTotal'] = Session::get('ShoppingCart.SubTotal');
		$this->PageData['CouponDiscount'] = $CouponDiscount; //Session::get('ShoppingCart.CouponDiscount');

		$this->PageData['AutoDiscount'] = Session::get('ShoppingCart.AutoDiscount');
		$this->PageData['QuantityDiscount'] = Session::get('ShoppingCart.QuantityDiscount');
		$this->PageData['AutoQuantityDiscount'] = Session::get('ShoppingCart.AutoQuantityDiscount');

		$this->PageData['SalesTax'] = Make_Price('0.00');
		$coupon_code = "";
		if (Session::has('ShoppingCart.CouponCode')) {
			$coupon_code = Session::get('ShoppingCart.CouponCode');
		}
		$this->PageData['CouponCode'] = $coupon_code;

		$this->PageData['Total_Amount'] =  $this->CalculateNetTotal();
		$this->PageData['cart_product_ids'] = $this->getCartProductIds();
		//$this->PageData['TotalQty'] = Session::get('ShoppingCart.TotalQty');
		$TotalQty = $this->getTotalQuantity(); //Session::get('ShoppingCart.TotalQty');
		$this->PageData['TotalQty'] = $TotalQty;
		$CartHTML = view('shoppingcart.cart_details')->with($this->PageData)->render();
		$cartSummary = view('shoppingcart.cart_summary')->with($this->PageData)->render();
		$cartEmpty = "";
		if ($TotalQty == null or $TotalQty == 0) {
			$cartEmpty = view('shoppingcart.cart_empty')->with($this->PageData)->render();
		}
		//$CouponDiscount = Session::get('ShoppingCart.CouponDiscount');
		return response()->json(array('cart_details' => $CartHTML, 'cart_summary' => $cartSummary, 'TotalQty' => $TotalQty, 'CouponDiscount' => $CouponDiscount, 'cart_empty' => $cartEmpty, 'page' => 'checkout'));
	}

	public function Checkout(Request $Request)
	{
		//echo "11"; exit;
		$this->PageData['CSSFILES'] = ['cart.css'];
		//Session::forget('ShoppingCart');
		$cart_arr = array();
		$SubTotal = 0;
		$shipping_charge = 0;
		$Total_Amount = 0;
		$TotalQty = 0;
		$customer_id = 0;

		//dd(Session::get('ShoppingCart.Cart'));
		$cart_data = $this->setupCart();
		if (Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0) {
			$customer_id = Session::get('sess_icustomerid');
		}

		//$this->CalculateSubTotal();

		#### Get billing and shipping Address Start ############
		$Shipping = $this->getShippingAddress();

		if ($Shipping['first_name'] == '' && $customer_id != '') {
			$custRS = DB::table($this->prefix . 'customer')
				->select('*')
				->where('customer_id', '=', (int)$customer_id)->first();
			//dd($custRS);
			if (!empty($custRS)) {
				$Shipping['first_name'] 	= $custRS->first_name;
				$Shipping['last_name']  	= $custRS->last_name;
				$Shipping['company']    	= $custRS->company_name;
				$Shipping['address1']   	= $custRS->address1;
				$Shipping['address2']   	= $custRS->address2;
				$Shipping['city'] 	   		= $custRS->city;
				$Shipping['zip'] 	   		= $custRS->zip;
				$Shipping['state'] 	   		= $custRS->state;
				$Shipping['country']    	= $custRS->country;
				$Shipping['phone'] 	   		= $custRS->phone;
				$Shipping['email'] 	   		= trim($custRS->email);
			}

			if (Auth::user()) {
				if (Session::has('email')) {
					$Shipping['email'] = trim(Session::get('email'));
				}
			}
		}

		if (Auth::user()) {
			if (Session::has('email')) {
				$Shipping['email'] = trim(Session::get('email'));
			}
		}

		$Billing  = $this->getBillingAddress();
		$IsBillingAsShipping = $this->getBillingAsShipping();

		//dd($IsBillingAsShipping);
		#### Get billing and shipping Address End ############

		$aCountry = $this->getCountryBoxArray();
		$aState = $this->getStateBoxArray();

		$this->PageData['Shipping'] = $Shipping;
		$this->PageData['Billing'] = $Billing;
		$this->PageData['IsBillingAsShipping'] = $IsBillingAsShipping;
		$this->PageData['aCountry'] = $aCountry;
		$this->PageData['aState'] = $aState;
		$this->PageData['cart_data'] = $cart_data;
		$this->PageData['AutoDiscount'] = Session::get('ShoppingCart.AutoDiscount');
		//echo "<pre>"; print_r(Session::get('ShoppingCart.AutoDiscount')); exit;
		$this->PageData['QuantityDiscount'] = Session::get('ShoppingCart.QuantityDiscount');
		$this->PageData['AutoQuantityDiscount'] = Session::get('ShoppingCart.AutoQuantityDiscount');
		$this->PageData['SubTotal'] = Session::get('ShoppingCart.SubTotal');
		$this->PageData['SalesTax'] = Make_Price('0.00');
		$this->PageData['Total_Amount'] =  $this->CalculateNetTotal();
		$this->PageData['CouponDiscount'] = Session::get('ShoppingCart.CouponDiscount');
		//$this->PageData['wishlist_products'] = $this->getWishlistProducts();

		$this->PageData['meta_title'] = "Checkout - HBASales.com";
		$this->PageData['meta_description'] = "Checkout - HBASales.com";
		$this->PageData['meta_keywords'] = "Checkout - HBASales.com";

		//$this->PageData['TotalQty'] = $this->getTotalQuantity(); //Session::get('ShoppingCart.TotalQty');
		$this->PageData['customer_id'] =  $customer_id;

		$this->PageData['JSFILES'] = ['checkoutnew.js'];
		//return view('shoppingcart.cart')->with($this->PageData);

		return view('shoppingcart.checkoutnew')->with($this->PageData);
	}

	
}
