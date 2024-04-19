<table class="ord_sum">
	<tbody>
		<tr>
			<td>Subtotal</td>
			<td>{{make_price($SubTotal,true)}}</td>
		</tr>
		<!-- @if((float)$ShippingCharge > 0 )
		<tr>
			<td>Shipping</td>
			<td>
				{{make_price($ShippingCharge,true)}}
			</td>
		</tr>
		@endif -->
		<tr>
			<td>Shipping</td>
			<td>
				@if($ShippingCharge == '0')
					Free
				@else 
					{{make_price($ShippingCharge,true)}}
				@endif
			</td>
		</tr>
		@if($TaxValue > 0)
		<tr>
			<td>Sales Tax</td>
			<td>{{make_price($TaxValue,true)}}</td>
		</tr>
		@endif
		@if($AutoDiscount > 0)
		<tr>
			<td>Auto Discount</td>
			<td>-{{make_price($AutoDiscount,true)}}</td>
		</tr>
		@endif
		@if($QuantityDiscount > 0)
		<tr>
			<td>Quantity Discount</td>
			<td>-{{make_price($QuantityDiscount,true)}}</td>
		</tr>
		@endif
		@if($CouponDiscount > 0)
		<tr class="savings">
			<td>Coupon Discount</td>
			<td>-{{make_price($CouponDiscount)}}</td>
		</tr>
		@endif
	</tbody>
	@if($Total_Amount > 0)
		<tfoot>
			<tr class="ord_total">
				<td>Total Amount</td>
				<td>{{make_price($Total_Amount,true)}}</td>
			</tr>
		</tfoot>
	@endif
</table>

<input type="hidden" name="IS_PAYMENT_VIA_GC" id="IS_PAYMENT_VIA_GC" value="No">
<input type="hidden" name="ga_item_subtotal" id="ga_item_subtotal" value="{{$SubTotal}}">
	{{--<input type="hidden" name="ga_order_total_amount" id="ga_order_total_amount" value="{{$Total_Amount}}">--}}