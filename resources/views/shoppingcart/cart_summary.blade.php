<div class="h2">Order Summary</div>
<table class="ord_sum">
	<tbody>
		<tr>
			<td>Subtotal ({{$TotalQty}} items)</td>
			<td>{{ make_price($SubTotal,true) }}</td>
		</tr>
		@if(isset($AutoDiscount) && $AutoDiscount > 0)
		<tr>
			<td>Auto Discount</td>
			<td>-{{make_price($AutoDiscount,true)}}</td>
		</tr>
		@endif
		@if(isset($QuantityDiscount) && $QuantityDiscount > 0)
		<tr>
			<td>Quantity Discount</td>
			<td>-{{make_price($QuantityDiscount,true)}}</td>
		</tr>
		@endif
		@if(isset($CouponDiscount) && $CouponDiscount > 0)
		<tr class="savings">
			<td>Coupon Discount:</td>
			<td>-{{make_price($CouponDiscount,true)}}</td>
		</tr>
		@endif
		<tr>
			<td>Shipping</td>
			<td>Free</td>
		</tr>
		<tr>
			<td>Estimated tax</td>
			<td>Calculated in checkout</td>
		</tr>
		
	</tbody>
	<tfoot>
		<tr class="ord_total">
			<td>Estimated total</td>
			<td>{{make_price($Total_Amount,true)}}</td>
		</tr>
	</tfoot>
</table>
<div class="proced-msec">
	<div class="hidden-md-up">
		<div class="row row10">
			<div class="col-xs-4 d-md-none"> <span>{{$TotalQty}} items</span> </div>
			<div class="col-xs-8 tar"> <strong>Subtotal: {{ make_price($Total_Amount,true) }}</strong> </div>
		</div>
	</div>
	{{-- <a href="{{config('const.SITE_URL')}}/checkout" class="btn btn-success btn-block mt-2">Checkout</a> --}}
	<button type="button" onClick="window.location.href='{{config('const.SITE_URL')}}/checkout'" class="btn btn-success btn-block mt-2" title="Checkout" aria-label="Checkout">Checkout</button>
</div>