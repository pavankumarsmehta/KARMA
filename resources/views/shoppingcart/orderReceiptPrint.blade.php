@extends('layouts.app')
@section('content')
@section('content')
@php 
	$APP_URLS = config('const.APP_URLS');
@endphp
<div class="checkout">
	<div class="container">
		<div class="ord-receipt">
			<div class="checkout_title tac">
				<h2>Order Receipt Print</h2>
				<h3 class="text_c3">Thank You, your order is placed!</h3>
				<p>Your order number is <span class="f700">{{$OrdersInfo->order_id}}.</span> A confirmation email is headed your way at {{$OrdersInfo->ship_email}}.</p>
			</div>
			<div class="date-print">
				<span class="text_c2">Date:  {{date("m/d/Y", strtotime($OrdersInfo->order_datetime))}}</span> 
			</div>
			<hr />
			<div class="checkout-address py-1">
				<div class="row row10">
					<div class="col-sm-6 py-2">
						<h2>Shipping Address</h2>
						<p class="mb-0">{{$OrdersInfo->ship_first_name}}  {{$OrdersInfo->ship_last_name}}<br />
						{{$OrdersInfo->ship_address1}},<br />
							@if(isset($OrdersInfo->ship_address2) && $OrdersInfo->ship_address2 != "")
								{{$OrdersInfo->ship_address2}}<br />
							@endif
							{{$OrdersInfo->ship_city}}, {{$OrdersInfo->ship_state}} {{$OrdersInfo->ship_zip}}
						</p>
					</div>
					<div class="col-sm-6 py-2">
						<h2>Billing Address</h2>
						<p class="mb-0">
							{{$OrdersInfo->bill_first_name}}  {{$OrdersInfo->bill_last_name}} <br />
							{{$OrdersInfo->bill_address1}},<br />
							@if(isset($OrdersInfo->bill_address2) && $OrdersInfo->bill_address2 != "")
								{{$OrdersInfo->bill_address2}}<br />
							@endif
							{{$OrdersInfo->bill_city}}, {{$OrdersInfo->bill_state}} {{$OrdersInfo->bill_zip}}
						</p>
					</div>
				</div>
			</div>
			<hr />
			@if($OrdersInfo->payment_type == 'PAYMENT_BRAINTREECC')
			<div class="checkout-payment py-3">
				<h2>
					Payment Method 
					<a href="javascript:void(0);" class="text_c1 ms-2">
						<svg class="svg_lock" width="15px" height="17px" aria-hidden="true" fill="none" role="img">
							<use href="#svg_lock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_lock"></use>
						</svg>
					</a>
				</h2>
				<span>Secure and Encrypted</span>
				<div class="payment-chnage pt-3"> <span class="pm-logo me-4"><img src="{{config('app.url')}}/images/check_credit_card.png" alt="Credit Card"></span> Credit Card</div>
			</div>
			@elseif($OrdersInfo->payment_type == 'PAYMENT_BRAINTREEPAYPAL')
				<div class="checkout-payment py-3">
					<h2>
						Payment Method 
						<a href="javascript:void(0);" class="text_c1 ms-2">
							<svg class="svg_lock" width="15px" height="17px" aria-hidden="true" fill="none" role="img">
								<use href="#svg_lock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_lock"></use>
							</svg>
						</a>
					</h2>
					<span>Secure and Encrypted</span>
					<div class="payment-chnage pt-3"> <span class="pm-logo me-4"><img src="{{config('app.url')}}/images/check_credit_card.png" alt="Credit Card"></span> PayPal</div>
				</div>
			@endif
			<hr />
			<div class="checkout-item py-3">
				<h2>YOUR ITEMS</h2>
				<div class="cart_table">
					@if(count($OrderDetails) > 0)	
						@foreach($OrderDetails as $key => $val)
							<div class="loop">
								<div class="cart_row">
									<div class="thumb">
										<a href="{{$APP_URLS.$val['product_url']}}" title="Test"><img src="{{ $val['Image'] }}" alt="" width="175" height="175"></a>
									</div>
									
									<div class="info">
										<div class="mb-3"><a href="" class="name text_c1" title="Test">{{ $val['product_name'] }}</a></div>
										<div class="pb-2">SKU:<span class="ps-2">{{ $val['product_sku'] }}</span></div>
										<div class="hidden-sm-up">
											<div class="qty-box p-0">
												Qty: {{ $val['quantity'] }}
											</div>
										</div>
									</div>
									<div class="cart_price hidden-xs-down">
										<div class="price"> <span>Subtotal</span> <span class="special-price">{{Make_Price($val['total_price'],true) }}</span></div>
										<div class="qty-box">
											Qty: {{ $val['quantity'] }}
										</div>
									</div>
								</div>
							</div>
						@endforeach
					@endif	
				</div>
			</div>
			<div class="row row10">
				<div class="col-xs-12 col-sm-6 pt-5 hidden-xs-down">&nbsp;</div>
				<div class="col-xs-12 col-sm-6">
					<div class="order_summary">
						<table class="ord_sum">
							<tbody>
								<tr>
									<td>Subtotal:</td>
									<td>{{Make_Price($OrdersInfo->sub_total,true)}}</td>
								</tr>
								@if($OrdersInfo->shipping_amt > 0)
								<tr>
									<td>Shipping:</td>
									<td>{{Make_Price($OrdersInfo->shipping_amt,true)}}</td>
								</tr>
								@endif
								@if($OrdersInfo->tax > 0)
								<tr>
									<td>Sales Tax</td>
									<td>{{Make_Price($OrdersInfo->tax,true)}}</td>
								</tr>
								@endif
								@if($OrdersInfo->auto_discount > 0)
								<tr>
									<td>Auto Discount</td>
									<td>-{{Make_Price($OrdersInfo->auto_discount,true)}}</td>
								</tr>
								@endif
								@if($OrdersInfo->quantity_discount > 0)
								<tr>
									<td>Quantity Discount</td>
									<td>-{{Make_Price($OrdersInfo->quantity_discount,true)}}</td>
								</tr>
								@endif
								@if($OrdersInfo->coupon_amount > 0)
								<tr class="savings">
									<td>Coupon Discount:</td>
									<td>-{{Make_Price($OrdersInfo->coupon_amount,true)}}</td>
								</tr>
								@endif
							</tbody>
							<tfoot>
								@if($OrdersInfo->order_total > 0)
								<tr class="ord_total">
									<td>Final Total:</td>
									<td>{{Make_Price($OrdersInfo->order_total,true)}}</td>
								</tr>
								@endif
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection