@extends('layouts.app')
@section('content')
<style>
.bo-bottom{border-bottom:0px;}
.bo-top{border-top:0px;padding-top: 0px;}
</style>
<main id="cart_main">
	<div class="cart-page">
		<div class="container">
			<div class="breadcrumb">
				<a href="#">Home<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img" loading="lazy"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
				<span class="active">Your Cart</span> 
			</div>
			
			@if($TotalQty <= 0)
			<div class="checkout_hd">
				<h1 class="h2">Your Shopping Bag</h1>
			</div>
			@endif
			<div class="checkout_row">
				<div class="checkout_left">
					<div class="checkout_hd">
						@if($TotalQty > 0)
							<h1 class="h2">Your Shopping Bag </h1>
							<span class="itemqty" id="total_qty">{{$TotalQty}} item(s)</span>							
						@endif
					</div>
					@guest
								<p class="f16 anacnt">Already have an account? <a href="javascript:void(0)" class="tdu loginpopup" title="Sign in">Sign in</a> Or 
								<a href="javascript:void(0);" onclick="return show_user_register_popup();" class="tdu caa-link" title="Create an Account">Create an Account</a> to save the items in your bag or track your order.</p>
							@endguest	
					@if($TotalQty > 0)
					<div class="cart_table">
						@include('shoppingcart.cart_details')
					</div>
					{{-- <a href="{{ config('const.SITE_URL') }}" class="btn btn-twilight-border">Continue Shopping</a>--}}
					<button type="button" onClick="window.location.href='{{ config('const.SITE_URL') }}'" class="btn btn-twilight-border" title="Continue Shopping" aria-label="Continue Shopping">Continue Shopping</button>
					@else 
						<div class="cart-empty">
							<h3>Your Shopping Cart is Empty</h3>
							@guest
								<div class="pb-2">Have an Account? </div>
								<div class="pb-2"><a href="javascript:void(0)" class="btn btn-success loginpopup">sign in</a></div>
								<a href="javascript:void(0);" onclick="return show_user_register_popup();" class="text_c1 tdu">Create My {{config('const.SITE_NAME')}} Account</a> 
							@endguest
						</div>
					@endif
				</div>
				<div class="checkout_right">
					@include('shoppingcart.Free_Shipping')
					<div class="order_summary">
						<div id="order_summary">
							@include('shoppingcart.cart_summary')
						</div>
						
						
						{{-- <div class="dividerr_or"><span>or</span></div>--}}
						{{-- <a href="#" class="btn btn-block btn-yellow">Pay With <picture><img src="images/paypal_icon.png" width="78" alt="" loading="lazy"/></picture></a> --}}
						{{-- <button type="button" onClick="return false;" class="btn btn-block btn-yellow" title="Pay With PayPal" aria-label="Pay With PayPal">Pay With <picture><img src="images/paypal_icon.png" width="78" alt="" loading="lazy"/></picture></button> --}}
						<div class="coupon-code" id="cpcode">
							<div class="h4">coupon code</div>
							<div class="coupon-content">
								<div class="coupon-inner">
									<form action="" method="post">
									<label for="coupon" class="dnone">Coupon code</label>
										<div class="input-group">
										  
											<input type="text" aria-label="Coupon code" class="form-control" id="coupon" @if($CouponCode!='' || Session::get('ShoppingCart.CouponCode') != "") value="{{Session::get('ShoppingCart.CouponCode')}}" readonly @endif  name="coupon">
											<button class="btn btn-border ttu" type="button" id="coupon_remove" onclick="removeCoupon();" @if($CouponCode=='') style="display: none;" @endif >Remove</button>
											<button class="btn btn-border ttu" type="button" id="coupon_apply" onclick="applyCoupon();" @if($CouponCode!='') style="display: none;" @endif   >Apply</button>
											
										</div>
										<div class="frmerror error" role="alert" id="coupon_alert" style="display: none;">Pleae enter valid coupon code</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					@include('shoppingcart.needAssistance')
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</main>
<script src="https://js.braintreegateway.com/web/dropin/1.33.4/js/dropin.min.js"></script>
<form id="frmCheckOut" name="frmCheckOut" action="{{config('global.SITE_URL')}}checkout-actiononcart" method="POST">
 {{ csrf_field() }}
 <input type="hidden" name="action_paypal" value="bt_express_checkout">
 
 <input type="hidden" name="bl_sh_email" id="bl_sh_email" value="">
 <input type="hidden" name="sh_fname" id="sh_fname" value="">
 <input type="hidden" name="sh_lname" id="sh_lname" value="">
 <input type="hidden" name="sh_Addr1" id="sh_Addr1" value="">
 <input type="hidden" name="sh_Addr2" id="sh_Addr2" value="">
 <input type="hidden" name="sh_city" id="sh_city" value="">
 <input type="hidden" name="sh_state" id="sh_state" value="">
 <input type="hidden" name="sh_otherstate" id="sh_otherstate" value="">
 <input type="hidden" name="sh_zip" id="sh_zip" value="">
 <input type="hidden" name="sh_country" id="sh_country" value="">
 <input type="hidden" name="sh_phone" id="sh_phone" value="">
 
 <input type="hidden" name="bt_express_payment_method_type" id="bt_express_payment_method_type" value="" /> 
 <input type="hidden" name="bt_express_payment_method_nonce" id="bt_express_payment_method_nonce" value="" /> 
 
 <input type="hidden" name="BRAINTREE_TOKENIZATION_KEY" id="BRAINTREE_TOKENIZATION_KEY" value="{{$bt_api_details->BRAINTREE_TOKENIZATION_KEY}}" /> 
 <input type="hidden" name="BRAINTREE_GOOGLE_MERCHANT_ID" id="BRAINTREE_GOOGLE_MERCHANT_ID" value="{{$bt_api_details->BRAINTREE_GOOGLE_MERCHANT_ID}}" /> 
 
<form>
@endsection