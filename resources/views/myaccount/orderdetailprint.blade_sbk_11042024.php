@extends('layouts.printapp')
@section('content')
<style>
.myact {
	padding-top: 25px;
	padding-bottom: 25px
}
.myact_hd {
	border-bottom: 1px solid #bdbdbd;
	padding: 20px 0;
	position: relative
}
.myact_hd h2 {
	margin: 0;
	padding: 5px 0 0 0;
	text-transform: inherit;
	font-size: 28px;
	color: #000;
	font-weight: 600
}
.myorder_dtl {
	padding-top: 30px
}
.myorder_dtl .wlc_msg {
	border-bottom: 1px solid #e5e5e5;
	font-size: 22px;
	line-height: 30px;
	padding-top: 25px;
	padding-bottom: 29px;
	color: #585858
}
.myorder_dtl .order_info {
	padding-top: 25px;
	padding-bottom: 52px
}
.myorder_dtl .order_info table {
	border: 0;
	border-collapse: 0;
	border-spacing: 0;
	text-align: left;
	display: inline-block
}
.myorder_dtl .order_info table td {
	font-size: 13px;
	text-transform: uppercase;
	line-height: 13px;
	color: #000;
	padding: 5px 10px 5px 0
}
.myorder_dtl .order_info table td strong {
	font-weight: 600
}
.myorder_dtl .st_table {
	border: 0;
	border-collapse: 0;
	border-spacing: 0;
	text-align: left;
	/*width: 100%*/
	width:400px;
}
.myorder_dtl .st_table td {
	background-color: #f5f5f5;
	font-size: 15px;
	line-height: 15px;
	color: #000;
	padding: 10px
}
.myorder_dtl .st_table td strong {
	font-weight: 600;
	font-size: 16px
}
.myorder_dtl .st_table th {
	background-color: #ddd;
	font-size: 20px;
	font-weight: 600;
	padding: 10px
}
.myorder_dtl .st_table th strong {
	font-size: 20px;
	font-weight: 600
}
.deliv_dtl{
	float: right;	
	margin-right: -10px;
}
.myorder_dtl .deliv_dtl h3 {
	text-transform: none;
	font-size: 20px;
	font-weight: 600
}
.myorder_dtl .deliv_dtl .yline {
	border-right: 1px solid #ddd
}
.myorder_dtl .deliv_dtl {
	font-size: 15px;
	color: #4c4c4c;
	line-height: 20px
}
.myorder_dtl .deliv_dtl strong {
	font-weight: 600;
	font-size: 13px
}
/*@media (max-width:574px) {
.myorder_dtl .deliv_dtl .yline {
	border-right: 0;
	border-bottom: 1px solid #ddd;
	padding-bottom: 5px
}
}*/
.myorder_dtl .deliv_dtl .help>li {
	margin-top: 10px
}
.myorder_dtl .deliv_dtl .help div {
	padding-left: 32px;
	font-size: 15px
}
.myorder_dtl .deliv_dtl .sv_phon {
	width: 23px;
	height: 23px
}
.myorder_dtl .deliv_dtl .sv_email_opan {
	width: 23px;
	height: 23px
}
#Rptable table {
	border: 0;
	width: 100%;
	border-collapse: 0;
	border-spacing: 0;
	color: #000
}
#Rptable table thead td, #Rptable table thead th {
	padding: 10px;
	border-bottom: 2px solid #000;
	font-size: 14px;
	text-transform: uppercase;
	font-weight: 600
}
#Rptable table tbody td {
	padding: 10px;
	border-bottom: 1px solid #e2e2e2;
	vertical-align: top; 
	font-size: 15px
}

#Rptable table .img-wrapper{position: relative;padding-bottom: 100%;display: block;overflow: hidden;}
#Rptable table .img-wrapper>img{position: absolute;max-width: 100%;max-height: 100%;top: 50%;left: 50%;transform: translate(-50%) translateY(-50%);object-fit: contain;}

/*@media (max-width:767px) {
#Rptable {
	margin: 0 auto;
	width: 100%
}
#Rptable table, #Rptable tbody, #Rptable tbody td, #Rptable tbody tr, #Rptable tfoot, #Rptable tfoot td, #Rptable tfoot tr, #Rptable thead, #Rptable thead th {
	display: block
}
#Rptable thead tr {
	position: absolute;
	top: -9999px;
	left: -9999px
}
#Rptable tbody tr, #Rptable thead tr {
	border: 1px solid #ccc
}
#Rptable tbody td, #Rptable thead td {
	border: none;
	border-bottom: 1px solid #eee;
	position: relative;
	padding-left: 50%!important;
	text-align: left
}
#Rptable tbody td:before {
	content: attr(data-title) ""
}
#Rptable tbody td:before {
	position: absolute;
	top: 9px;
	left: 10px;
	width: 45%;
	text-align: left;
	line-height: 17px;
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden
}
#Rptable tbody td[data-title^=full] {
	padding: 10px!important
}
#Rptable tbody td[data-title^=full]:before {
	display: none
}
#Rptable tbody td[data-title="full center"] {
	text-align: center
}
}*/
.print_link {
	color: #000;
	font-size: 16px;
	line-height: 16px
}
.print_link svg.sv-print {
	width: 25px;
	height: 23px;
	fill: #000;
	vertical-align: middle;
	margin-right: 5px
}
</style>
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-12 col-md-12 col-xs-12">
	               <div class="myact_hd"> 
	               	{{-- <img src="{{ config('global.SITE_IMAGES') }}logo.jpg" class="logo-print"> --}}

					   <a href="{{config('const.SITE_URL')}} " class="logo">
						<svg class="svg_logo" width="234px" height="74px" aria-hidden="true" role="img">
							<use href="#svg_logo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_logo"></use>
						</svg>
					</a>

	               </div>
	               <div class="myorder_dtl">
	               <!-- <div class="myact_subhd">Order Details</div> -->
	                  <div class="wlc_msg">
	                     <div class="row">
	                        <div class="col-md-8 pt-2 font_ssp"><strong>Order# {{ $OrderRs->order_id }}</strong></div>
	                        <div class="col-md-4 pt-2 text-left text-md-right hiderow"><a href="javascript:printpreview();" class="font_ssp print_link"><svg class="sv-print" aria-hidden="true" role="img">
	                           <use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
	                           </svg>Print</a>
	                       	</div>
	                     </div>
	                  </div>
				      <div class="order_info">
				         <div class="row row5">
				            <div class="col-md-12 pt-2">
							   <table>
									<tr>
										<td valign="top" width="34%">
											<table>
											<!-- <tr>
											   <td>Order Number :</td>
											   <td><strong>{{ $OrderRs->orders_no }}</strong></td>
											</tr> -->				                  
											<tr>
											   <td><strong>Order Date :</strong></td>
											   <td>{{ $OrderRs->datetime }}</td>
											</tr>
											<tr>
											   <td><strong>Payment Method :</strong></td>
											   <td>{{ $OrderRs->payment_method }}</td>
											</tr>
											<tr>
											   <td><strong>Payment Status :</strong></td>
											   <td>{{ $OrderRs->pay_status }}</td>
											</tr>
											<tr>
											   <td><strong>Shipment Status :</strong></td>
											   <td>{{ $OrderRs->ship_status }}</td>
											</tr>
											<tr>
											   <td><strong>Order Status :</strong></td>
											   <td>{{ $OrderRs->status }}</td>
											</tr>
											@if($OrderRs->total_refund_amount > 0)
											<tr>
											   <td><strong>Refund Amount :</strong></td>
											   <td>{{make_price($OrderRs->total_refund_amount,true) }}</td>
											</tr>
											@endif				                  
										 </table></td>
										<td valign="top" width="33%"><table><tr>
											<td width="50%" valign="top"><strong>Billing Address :</strong></td>
											<td>{{$OrderRs->bill_first_name}} {{$OrderRs->bill_last_name}}<br>
											 {{$OrderRs->bill_address1}}, {{$OrderRs->bill_address2}},<br>
											 {{$OrderRs->bill_city}} - {{$OrderRs->bill_zip}},<br>
											 {{$OrderRs->bill_state}}, {{$OrderRs->bill_country}}</td>
										 </tr>
										</table></td>
										<td valign="top" width="33%">
											<table>
												<tr>
													<td width="50%" valign="top"><strong>Shipping Address :</strong></td>
													<td>{{$OrderRs->ship_first_name}} {{$OrderRs->ship_last_name}}<br>
													 {{$OrderRs->ship_address1}}, {{$OrderRs->ship_address2}},<br>
													 {{$OrderRs->ship_city}} -  {{$OrderRs->ship_zip}},<br>
													 {{$OrderRs->ship_state}}, {{$OrderRs->ship_country}}</td>
												 </tr>
											</table>
										</td>
									</tr>
							   </table>
				               
				            </div>
				         </div>
				      </div>
				      <div id="Rptable">
				         <table class="text-center">
				            <thead>
				               <tr>
				                  <td class="text-left">Item Description</td>				                  
				                  <td>Unit price</td>
				                  <td>Quantity</td>
				                  <td class="text-right">Total Price</td>
				               </tr>
				            </thead>
				            <tbody class="align-top">
				            	@if(count($OrderDetailRs) > 0)
				            	@foreach($OrderDetailRs as $order_details_key => $order_details_value)
				               <tr>
				                  <td data-title="full center" class="text-left">
				                     <div class="row row5">
				                        <div class="col-lg-4 col-md-5 col-sm-6 text-center text-sm-left">
											<a href="#">
											<span class="img-wrapper"><img src="{{$order_details_value->thumb_image}}" width="185" height="185" alt="{{$order_details_value->product_name}}" /></span>	
										</a></div>
				                        <div class="col-lg-8 col-md-7 col-sm-6 text-center text-sm-left pt-2 pt-sm-0" style="text-align: left !important;">
											<a href="#"><strong>{!! $order_details_value->product_name !!}</strong></a>
											<br><strong>SKU : </strong>{{ $order_details_value->product_sku }}
				                        </div>
				                     </div>
				                  </td>				                  
				                  <td data-title="Unit price">{{make_price($order_details_value->unit_price,true)}}</td>
				                  <td data-title="Quantity">{{$order_details_value->quantity}}</td>
				                  <td data-title="Total Price" class="text-md-right text-left">{{make_price($order_details_value->total_price,true)}}</td>
				               </tr>
				               @endforeach
				               @else
				               No items found!
				               @endif
				            </tbody>
				         </table>
				      </div>
				      <div class="deliv_dtl">
				         <div class="row row5">
				            <div class="col-md-4 order-md-2 pt-3">
				               <table class="st_table">
				                  <tr>
				                     <td width="48%">Subtotal:</td>
				                     <td class="text-left text-md-right"><strong>{{make_price($OrderRs->sub_total,true)}}</strong></td>
				                  </tr>
								  @if($OrderRs->shipping_amt > 0)
				                  <tr>
				                     <td>Shipping:</td>
				                     <td class="text-left text-md-right"><strong>{{make_price($OrderRs->shipping_amt,true)}}</strong></td>
				                  </tr>
				                  @endif				                  								  
				                  @if($OrderRs->tax > 0)
				                  <tr>
				                     <td>Sales Tax: </td>
				                     <td class="text-left text-md-right"><strong>{{make_price($OrderRs->tax,true)}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->gift_charge > 0)
				                  <tr>
				                     <td>Gift Wrapping Charge: </td>
				                     <td class="text-left text-md-right"><strong>{{make_price($OrderRs->gift_charge,true)}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->auto_discount > 0)
				                  <tr>
				                     <td>Auto Discount: </td>
				                     <td class="text-left text-md-right"><strong>-{{make_price($OrderRs->auto_discount,true)}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->quantity_discount > 0)
				                  <tr>
				                     <td>Quantity Discount: </td>
				                     <td class="text-left text-md-right"><strong>-{{make_price($OrderRs->quantity_discount,true)}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->coupon_amount > 0)
				                  <tr>
				                     <td>Coupon Discount: </td>
				                     <td class="text-left text-md-right"><strong>-{{make_price($OrderRs->coupon_amount,true)}}</strong></td>
				                  </tr>
				                  @endif				                  				                  
				                  <tr>
				                     <th>Order Total</th>
				                     <th class="text-left text-md-right"><strong>{{make_price($OrderRs->order_total,true)}}</strong></th>
				                  </tr>
				               </table>
				            </div>
				            <div class="col-md-8 order-md-1 pt-3">
				               <div class="row">
				                  <!-- <div class="col-md-12 pb-2">
				                     <h3>Delivery Details</h3>
				                  </div>
				                  <div class="col-sm-4 yline mt-2">
				                     <div class="pb-2"><strong class="ttu">Estimated Delivery:</strong><br />
				                        Monday 26th March 2020
				                     </div>
				                     <strong>Delivery Method:</strong><br />
				                     Standard Shipping
				                  </div>
				                  
				                  <div class="col-sm-4 yline mt-2"> <strong class="ttu">Shipping to:</strong><br />
									  {{$OrderRs->ship_first_name}} {{$OrderRs->ship_last_name}}<br />
									  {{$OrderRs->ship_address1}}, {{$OrderRs->ship_address2}}<br />
									  {{$OrderRs->ship_city}} -  {{$OrderRs->ship_zip}}<br />
									  {{$OrderRs->ship_state}} ,{{$OrderRs->ship_country}}
				                  </div>
				                  
				                  <div class="col-sm-4 mt-2 "> <strong class="ttu">Billing to:</strong><br />
		                     		{{$OrderRs->bill_first_name}} {{$OrderRs->bill_last_name}}<br />
								  	{{$OrderRs->bill_address1}}, {{$OrderRs->bill_address2}}<br />
								  	{{$OrderRs->bill_city}} - {{$OrderRs->bill_zip}}<br />
								  	{{$OrderRs->bill_state}}, {{$OrderRs->bill_country}}
				                  </div> -->
				                  @if($OrderRs->gift_from !='' || $OrderRs->gift_to !='' || $OrderRs->gift_message_customer !='' || $OrderRs->free_gift!="")
				                  <div class="col-sm-4 mt-2 "> <strong class="ttu">Billing to:</strong><br />
		                     		@if($OrderRs->gift_from !='')
		                     			<p><strong>Gift From: </strong>{{$OrderRs->gift_from}}</p>
		                     		@endif
		                     		@if($OrderRs->gift_to !='')
		                     			<p><strong>Gift To: </strong>{{$OrderRs->gift_to}}</p>
		                     		@endif
		                     		@if($OrderRs->gift_message_customer !='')
		                     			<p><strong>Customer Message: </strong>{{$OrderRs->gift_message_customer}}</p>
		                     		@endif
		                     		@if($OrderRs->free_gift !='')
		                     			<p><strong>Got a Free Gift Of {{$OrderRs->free_gift}}</strong></p>
		                     		@endif
				                  </div>
				                  @endif
				               </div>
				            </div>
				         </div>
				      </div>
	               </div>

				</div>
			</div>
		</div>
	</div>
</main>	
@endsection
<script language="javascript">
	function printpreview()
	{
		rtn = confirm('Do You Want To Print Order Slip?')
		if(rtn==false) 
		{
			//window.close();
		} 
		else 
		{			
			$(".hiderow").hide();
			window.print();
		}
	}
</script>