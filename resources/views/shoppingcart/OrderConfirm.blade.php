@extends('layouts.app')
@section('content')
@php 
	$APP_URLS = config('const.APP_URLS');
@endphp
<?php //dd(Session::get('ShoppingCart')); ?>
<div class="cart-page">
	<div class="container">
		<div class="breadcrumb">
			<a href="{{config('const.SITE_URL')}}/">
				Home
				<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
					<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
				</svg>
			</a>
			<span class="active">Review Your Order</span> 
		</div>
		<div class="checkout_title">
			<h2 class="chtpd">Review Your Order</h2>
		</div>
		<form action="{{config('const.SITE_URL')}}/checkout-action" method="post" name="frm_order_process" id="frm_order_process">
		{{ csrf_field() }}
		<div class="checkout_row">
			<div class="checkout_left">
				<div class="checkout-form">
					<hr class="mt-0" />
					<div class="checkout-address py-1">
						<div class="row row10">
							<div class="col-sm-6 py-2">
								<h2>Shipping Address</h2>
								<p>
								{{$ship_firstname}} {{$ship_lastname}}<br />
								{{$ship_address1}}<br />
								@if($ship_address2!="")
								{{$ship_address2}}<br />
								@endif
								{{$ship_city}}, <br />
								{{$ship_state}} {{$ship_zip}}<br>
								{{$ship_country}}
								</p>
								<a href="{{config('const.SITE_URL')}}/checkout" class="text_c1 tdu" title="Change Shipping Address">Change Shipping Address</a> 
							</div>
							<div class="col-sm-6 py-2">
								<h2>Billing Address</h2>
								<p>
								{{$bill_firstname}} {{$bill_lastname}}<br />
								{{$bill_address1}}<br />
								@if($bill_address2!="")
								{{$bill_address2}}<br />
								@endif
								{{$bill_city}}, <br />
								{{$bill_state}} {{$bill_zip}}<br>
								{{$bill_country}}
								</p>
								<a href="{{config('const.SITE_URL')}}/checkout" class="text_c1 tdu" title="Change Billing Address">Change Billing Address</a> 
							</div>
						</div>
					</div>
					<hr />
					@if($payment_method == 'PAYMENT_BRAINTREECC')
					<div class="checkout-payment py-3">
						<h2>
							Payment Method 
							<a href="#" class="text_c1 ms-2">
								<svg class="svg_lock" width="15px" height="17px" aria-hidden="true" fill="none" role="img">
									<use href="#svg_lock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_lock"></use>
								</svg>
								Secure and Encrypted
							</a>
						</h2>
						<div class="payment-chnage pt-3"> <span class="pm-logo me-4"><img src="images/check_credit_card.png" alt="Credit Card"></span> Credit Card <a href="{{config('const.SITE_URL')}}/checkout" class="text_c1 tdu ms-4">Change</a> </div>
					</div>
					@elseif($payment_method == 'PAYMENT_BRAINTREEPAYPAL')
					<div class="checkout-payment py-3">
						<h2>
							Payment Method 
							<a href="#" class="text_c1 ms-2">
								<svg class="svg_lock" width="15px" height="17px" aria-hidden="true" fill="none" role="img">
									<use href="#svg_lock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_lock"></use>
								</svg>
								Secure and Encrypted
							</a>
						</h2>
						<div class="payment-chnage pt-3"> <span class="pm-logo me-4"><img src="images/check_credit_card.png" alt="Credit Card"></span> PayPal <a href="{{config('const.SITE_URL')}}/checkout" class="text_c1 tdu ms-4">Change</a> </div>
					</div>
					@endif
					<hr />
					<div class="checkout-item pt-3">
						<h2>YOUR ITEMS</h2>
						<div class="cart_table">
						<?php //dd($cart_data); ?>
							@if(count($cart_data) > 0)	
								@foreach($cart_data as $key => $val)
									<div class="loop">
										<div class="cart_row">
											<div class="thumb">
												<a href="{{$APP_URLS.$val['product_url']}}" class="img-wrapper" title="{{ $val['ProductName'] }}"><img src="{{ $val['Image'] }}" alt="{{ $val['ProductName'] }}" width="130" height="130" /></a>
												<div class="hidden-sm-up qty">
													<div class="qty-text pt-2">Qty: {{ $val['Qty'] }}</div>
												</div>
											</div>
											<div class="info">
												<div class="mb-3"><a href="{{$APP_URLS.$val['product_url']}}" class="name text_c1">{{ $val['ProductName'] }}</a></div>
												<div class="pb-2">SKU#<span class="ps-2">{{ $val['SKU'] }}</span></div>
												@if(isset($val['size_dimension']) && $val['size_dimension'] != "")
													<div class="pb-2">Size<span class="ps-2">{{$val['size_dimension']}}</span></div>
												@endif
												@if(isset($val['shipping_text']) && $val['shipping_text'] != "")
												<div>{{$val['shipping_text']}}</div>
												@endif
												<div class="hidden-sm-up">
													<div class="cart_price">
														<div class="price"> <span class="special-price">{{Make_Price($val['TotPrice'],true) }}</span> </div>
													</div>
												</div>
											</div>
											<div class="qty hidden-xs-down">
												<div class="qty-text">Qty: {{ $val['Qty'] }}</div>
											</div>
											<div class="cart_price hidden-xs-down">
												<div class="price"> <span class="special-price">{{Make_Price($val['TotPrice'],true) }}</span> </div>
											</div>
										</div>
									</div>
								@endforeach
							@endif
						</div>
						<div class="row">
							<div class="col-xs-12 pb-3">
								<div class="form-check fr">
									<input class="form-check-input" type="checkbox" id="rpolicy" name="rpolicy">
									<label class="form-check-label f-14" for="rpolicy" id="redcolor"> By submitting this order, I agree to the HBASales Return Policy and Terms & Conditions</label>
								</div>
							</div>
							<div class="col-xs-12 tar pb-3 mb-3" id="replace1">
								<a href="javascript:void(0)" class="mb-3 btn f700 btn-xs-block btn-success place-ord-btn placeorder" title="PLACE ORDER">PLACE ORDER</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="checkout_right">
				<div class="order_summary">
					<h2 class="">Order Summary</h2>
					<table class="ord_sum">
						<tbody>
							<tr>
								<td>Subtotal ({{$TotalItemInCart}} items)</td>
								<td>${{$SubTotal}}</td>
							</tr>
							<tr>
								<td>Shipping</td>
								<td>Free</td>
							</tr>
							@if($SalesTax > 0)
							<tr>
								<td>Estimated tax</td>
								<td>{{$SalesTax}}</td>
							</tr>
							@endif
							@if(isset($AutoDiscount) && $AutoDiscount > 0)
							<tr>
								<td>Auto Discount</td>
								<td>-${{$AutoDiscount}}</td>
							</tr>
							@endif
							@if(isset($QuantityDiscount) && $QuantityDiscount > 0)
							<tr>
								<td>Quantity Discount</td>
								<td>-${{$QuantityDiscount}}</td>
							</tr>
							@endif
							@if(isset($CouponDiscount) && $CouponDiscount > 0)
							<tr class="savings">
								<td>Coupon Discount:</td>
								<td>-${{$CouponDiscount}}</td>
							</tr>
							@endif
						</tbody>
						<tfoot>
							<tr class="ord_total">
								<td>Estimated total</td>
								<td>${{$Total_Amount}}</td>
							</tr>
						</tfoot>
					</table>
					<div class="proced-msec">
						<div class="hidden-sm-up">
							<div class="row row10">
								<div class="col-xs-4 d-md-none"> <span>{{$TotalItemInCart}} items</span> </div>
								<div class="col-xs-8 tar"> <strong>Subtotal: ${{$SubTotal}}</strong> </div>
							</div>
						</div>
						<div id="replace2">
							<a href="javascript:void(0)" class="btn btn-success f700 btn-block mt-2 placeorder" title="PLACE ORDER">PLACE ORDER</a>
						</div>
					</div>
				</div>
				@include('shoppingcart.needAssistance')
			</div>
			<div class="clearfix"></div>
		</div>
		</form>
	</div>
</div>

@if(isset($GA4_GOOGLE_ADD_PAYMENT_INFO_EVENT_DATA ) && $GA4_GOOGLE_ADD_PAYMENT_INFO_EVENT_DATA != "")
<script>
  window.dataLayer = window.dataLayer || [];
  {!! $GA4_GOOGLE_ADD_PAYMENT_INFO_EVENT_DATA !!}
</script>	
@endif

@endsection