@extends('layouts.app')
@section('content')
<div class="checkout">
	<div class="container">
		<div class="ord-receipt">
			<div class="checkout_title pb-1">
				<h3 class="text_c3">Thank You, Your order is placed!</h3>
				<div class="date-print"><span class="f600">Order #{{$OrdersInfo->order_id}}</span>
					<a href="javascript:void(0);" class="text_c2" onclick="return  window.open('{{ config('const.SITE_URL') }}/order-receipt-print/{{$OrdersInfo->order_id}}/{{$OrdersInfo->customer_id}}','xwin','toolbar=0,scrollbars=1,location=0,status=0,menubars=0,resizable=1, width=1000,height=800,top=0,left=0,maximize=0');">Print Receipt</a> 
				</div>
				<p></p>
				<p>When you shop at HBASales, you can shop with confidence. Every HBASales purchase comes with an appraisal report, free shipping, free returns within 30 days, and our unique free lifetime upgrade policy.</p>
			</div>

			<hr />
			<div class="checkout-address py-1">
				<div class="row row10">
					<div class="col-sm-6 py-2">
						<h2>Billing Address</h2>
						<p class="mb-0">
							<strong>{{$OrdersInfo->bill_first_name}}  {{$OrdersInfo->bill_last_name}}</strong><br />
							{{$OrdersInfo->bill_address1}},<br />
							@if($OrdersInfo->bill_address2 != "")
								{{$OrdersInfo->bill_address2}}<br />
							@endif
							{{$OrdersInfo->bill_city}}, {{$OrdersInfo->bill_state}} {{$OrdersInfo->bill_zip}}<br />{{$OrdersInfo->bill_country}}
						</p>
					</div>
					<div class="col-sm-6 py-2">
						<h2>Shipping Address</h2>
						<p class="mb-0">
							<strong>{{$OrdersInfo->ship_first_name}}  {{$OrdersInfo->ship_last_name}}</strong><br />
							{{$OrdersInfo->ship_address1}},<br />
							@if($OrdersInfo->ship_address2 != "")
								{{$OrdersInfo->ship_address2}}<br />
							@endif
							{{$OrdersInfo->ship_city}}, <br />
							{{$OrdersInfo->ship_state}} {{$OrdersInfo->ship_zip}}<br />
							{{$OrdersInfo->ship_country}}
						</p>
					</div>
				</div>
			</div>
			<hr />
			<div class="checkout-address py-1">
				<div class="row row10">
					<div class="col-sm-6 py-2">
						<h2>Shipping Method</h2>
						<p class="mb-0"><strong>Free 2nd Day Air</strong><br />
							Est. Receive by Jul. 19</p>
					</div>
					<div class="col-sm-6 py-2">
						<h2>Payment Method</h2>
						<p class="mb-0"><strong>Wire Transfer</strong><br />
							You will be contacted shortly.</p>
					</div>
				</div>
			</div>
			<hr />
			<div class="checkout-item py-3">
				<h2>Order Details <span>{{ count($OrderDetails)}} Items</span></h2>
				<div class="cart_table">
					@if(count($OrderDetails) > 0)	
						@foreach($OrderDetails as $key => $val)
						<div class="loop">
							<div class="cart_row">
								<div class="thumb">
									<a href="{{$val['product_url']}}" title="{{ $val['product_name'] }}"><img src="{{ $val['Image'] }}" alt="" width="175" height="175" /></a>
								</div>
								
								<div class="info">
									<div class="mb-3"><a href="{{$val['product_url']}}" class="name text_c1" title="{{ $val['product_name'] }}">{{ $val['product_name'] }}</a></div>
									<div class="pb-2">Item ID:<span class="ps-2">{{ $val['product_sku'] }}</span></div>
									@if(isset($val['size_dimension']) && $val['size_dimension'] != "")
										<div class="pb-2">Size<span class="ps-2">{{$val['size_dimension']}}</span></div>
									@endif
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
									<td>Subtotal ({{ count($OrderDetails)}} items)</td>
									<td>{{Make_Price($OrdersInfo->sub_total,true)}}</td>
								</tr>
								<tr>
									<td>Shipping</td>
									<td>Free</td>
								</tr>								
								@if($OrdersInfo->tax > 0)
								<tr>
									<td>Estimated tax <a href="javascript:void(0);"><svg class="svg_quchanmark" aria-hidden="true" role="img" width="15" height="15" loading="lazy">
												<use href="#svg_quchanmark" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_quchanmark"></use>
											</svg></a></td>
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
									<td>Coupon Discount</td>
									<!--<td>-$150.00</td>-->
									<td>-{{Make_Price($OrdersInfo->coupon_amount,true)}}</td>
								</tr>
								@endif
							</tbody>
							<tfoot>
								@if($OrdersInfo->order_total > 0)
								<tr class="ord_total">
									<td>Estimated total</td>
									<td>{{Make_Price($OrdersInfo->order_total,true)}}</td>
								</tr>
								@endif
							</tfoot>
						</table>
					</div>
				</div>
			</div>
			<div class="row row10">
				<div class="col-xs-12 col-sm-12"><a href="{{config('app.url')}}" class="btn f700 btn-xs-block btn-success place-ord-btn">CONTINUE SHOPPING</a></div>
			</div>
		</div>
	</div>
</div>
@endsection