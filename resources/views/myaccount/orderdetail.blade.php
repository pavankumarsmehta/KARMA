@extends('layouts.app')
@section('content')
<div class="container">
	@include('myaccount.breadcrumbs')
	<div class="myact mt-lg-0 mt-4 pb-sm-5 pb-0 mb-5">
		<div class="mb-3 hidden-md-up">
		    <div class="h2">My Orders</div>
		</div>
		@include('myaccount.myaccountmenu')
		<div class="pb-3 hidden-sm-down">
			<div class="h2">My Orders</div>
		</div>
		<div class="border_bottom mb-5">
			<div class="row order-dtl-main mb-2">
				<div class="col-md-7 col-sm-6 order-dtl-inner mb-3 mb-sm-0">
					<div class="h4 mb-1">Order Details</div>
					<p>Your order has been sent. We hope you love it!</p>
				</div>
				<ul class="col-md-5 col-sm-6 ordtop_detail dflex aic jcdfe mb-3">
					{{-- <li>
						<a href="#">
							<svg class="svg_pdf_file" aria-hidden="true" role="img" width="25" height="25">
								<use href="#svg_pdf_file" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pdf_file"></use>
							</svg>
							Save PDF
						</a>
					</li> --}}
					<li>
						<a href="javascript:void(0)" onclick="window.open('{{route('order-detail-print',$OrderRs[0]->order_id)}}', 'xwin','toolbar=0,scrollbars=1,location=0,status=0,menubars=0,resizable=0, width=820,height=600,top=0,left=0,maximize=0')" >
							<svg class="svg_printer" aria-hidden="true" role="img" width="25" height="25">
								<use href="#svg_printer" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_printer"></use>
							</svg>
							Print
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="track-order-main">
			<div class="row mb-4">
				<div class="col-lg-8 col-sm-7 mb-4 mb-sm-0">
					<table class="track-dtl-table f16">
						<tbody>
							<tr>
								<td width="50%">Order Number: </td>
								<td width="50%"><strong>#{{ $OrderRs[0]->order_id}}</strong></td>
							</tr>
							<tr>
								<td>Order Date: </td>
								<td><strong>{{$OrderRs[0]->datetime}}</strong></td>
							</tr>
							<tr>
								<td>Payment Method: </td>
								<td><strong>{{$OrderRs[0]->payment_method}}</strong></td>
							</tr>
							<tr>
								<td>Payment Status: </td>
								<td><strong>{{$OrderRs[0]->pay_status}}</strong></td>
							</tr>
							<tr>
								<td>Order Status: </td>
								<td><strong>{{$OrderRs[0]->status}}</strong></td>
							</tr>
							<tr>
								<td>Ship Status: </td>
								<td><strong>{{$OrderRs[0]->ship_status}}</strong></td>
							</tr>
							@if(isset($isOrderReturnable))
								@if($isOrderReturnable == 1 && $OrderReturnDate >= date('Y-m-d'))
								<tr>
									<td>Return Upto: </td>
									<td><strong>{{date('m/d/Y', strtotime($OrderReturnDate))}}</strong></td>
								</tr>
								@endif
							@endif
						</tbody>
					</table>
				</div>
				<div class="col-lg-4 col-sm-5">
					@if($OrderRs[0]->tracking_no != "")
						
						@if(strtolower($OrderRs[0]->ship_method) == "fedex")
							<a href="https://www.fedex.com/fedextrack/?trknbr={{$OrderRs[0]->tracking_no}}" class="btn btn-success btn-block" target="_blank">Track Order</a>
						@elseif(strtolower($OrderRs[0]->ship_method) == "usps")
							<a href="https://www.trackingmore.com/track/en/{{$OrderRs[0]->tracking_no}}" class="btn btn-success btn-block" target="_blank">Track Order</a>
						@elseif(strtolower($OrderRs[0]->ship_method) == "ups")
							<a href="https://www.ups.com/mobile/track?trackingNumber={{$OrderRs[0]->tracking_no}}" class="btn btn-success btn-block" target="_blank">Track Order</a>
						@endif	
					@endif
				</div>
			</div>
			<table class="res_table orddtl_table mb-4" width="100%">
				<thead>
					<tr>
						<th width="60%">Item Description</th>
						<th>Quantity</th>
						@if(isset($isOrderReturnable))
							@if($isOrderReturnable == 1)
								<th width="15%">Return Request</th>
							@endif
						@endif
						<th class="tar_md" width="20%">Total Price</th>
					</tr>
				</thead>
				<tbody>
					@foreach($OrderDetailRs as $order_details_key => $order_details_value)
					<?php //dd($order_details_value); ?>
					<tr valign="top">
						<td data-th="Item Description" class="orddtl_des">
							<div class="item-dtl-main dflex">
								<div class="thumb pe-2 me-lg-3">
									<a href="{{config('const.SITE_URL')}}/{{$order_details_value->product_url}}" target="_blank" class="d-block" title="{{$order_details_value->product_name}}">										
										@if(!empty($order_details_value->Image))
										<span class="img-wrapper">{!! $order_details_value->Image !!}</span>  {{-- for track order --}}
										@else
										<span class="img-wrapper"><img src="{{$order_details_value->thumb_image}}" width="185" height="185" alt="" /></span>
										@endif
									</a></div>
								<div class="detail">
									<div class="h6 mb-1"><a href="{{config('const.SITE_URL')}}/{{$order_details_value->product_url}}" target="_blank">
											{{$order_details_value->product_name}}</a></div>
									
									<p class="mb-0"><strong>SKU: </strong>{{$order_details_value->product_sku}}</p>
								</div>
							</div>
						</td>
						<td data-th="Quantity">{{$order_details_value->quantity}}</td>
						@if(isset($isOrderReturnable))
							@if($isOrderReturnable == 1)
								<td data-th="Return Request">
									@if($order_details_value->is_return_request == 0 && $OrderReturnDate >= date('Y-m-d'))
										<a href="javascript:void(0)" tabindex="0" rel="nofollow" class="btn btn-danger btn-block p-2" id="returnOrderItem" data-odid="{{$order_details_value->order_detail_id}}" data-quantity="{{$order_details_value->quantity}}" data-oid="{{$order_details_value->order_id}}" title="Return Item">Return Item</a>
									@elseif($order_details_value->is_return_request == 1 && $order_details_value->return_request_accept_reject == '1')
										<h6 style="color:green;"><b>Accepted </b></h6>
									@elseif($order_details_value->is_return_request == 1 && $order_details_value->return_request_accept_reject == '0')
										<h6 style="color:red;"><b>Rejected </b></h6>
									@elseif($order_details_value->is_return_request == 1 && $OrderReturnDate >= date('Y-m-d'))
									<p style="color:red;font-size:14px">Your return request has been submitted, you will get an update on email on your return request within 24 hours.</p>
									@endif

								</td>
							@endif
						@endif
						
						<td data-th="Total Price" class="tar_md">
							<div class="price">{{make_price($order_details_value->total_price,true)}}</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<div class="ord_dtl_foot mb-5">
				<div class="row">
					<div class="col-lg-4 col-md-5 order_2 mb-lg-0 mb-5">
						<table class="ordtotal_table bg_c6" width="100%">
							<tbody>
								<tr>
									<td>Subtotal:</td>
									<td>{{make_price($OrderRs[0]->sub_total,true)}}</td>
								</tr>
								@if($OrderRs[0]->shipping_amt > 0)
								<tr>
									<td>Shipping:</td>
									<td>{{make_price($OrderRs[0]->shipping_amt,true)}}</td>
								</tr>
								@endif								
								@if($OrderRs[0]->tax > 0)
									<tr>
										<td><strong>Sales Tax :</strong></td>
										<td>{{make_price($OrderRs[0]->tax,true)}}</td>
									</tr>
								@endif
								@if($OrderRs[0]->auto_discount > 0)
									<tr>
										<td><strong>Auto Discount :</strong></td>
										<td><span class="text_c3">-{{make_price($OrderRs[0]->auto_discount,true)}}</span></td>
									</tr>
								@endif
								@if($OrderRs[0]->quantity_discount > 0)
									<tr>
										<td><strong>Quantity Discount :</strong></td>
										<td><span class="text_c3">-{{make_price($OrderRs[0]->quantity_discount)}}</span></td>
									</tr>
								@endif
								@if($OrderRs[0]->coupon_amount > 0)
									<tr>
										<td><strong>Coupon Discount :</strong></td>
										<td><span class="text_c3">-{{make_price($OrderRs[0]->coupon_amount,true)}}</span></td>
									</tr>
								@endif
								<tr class="total-amount">
									<td>Total Amount</td>
									<td>{{make_price($OrderRs[0]->order_total,true)}}</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-lg-8 col-md-7 order_1">
						
						<div class="h4 mb-3">Order Details</div>
						<ul class="ship_method dflex dfwrap">
							<li>
								@if($OrderRs[0]->ship_method != "")
									<div class="h6 mb-1">Delivery Method</div>
									<p class="mb-0">{{$OrderRs[0]->ship_method}}</p><br>
								@endif
								@if($OrderRs[0]->tracking_no != "")
									<div class="h6 mb-1">Tracking</div>
									<p class="mb-0">
									@if(strtolower($OrderRs[0]->ship_method) == "fedex")
										<a href="https://www.fedex.com/fedextrack/?trknbr={{$OrderRs[0]->tracking_no}}" style="color:#333333;" target="_blank">{{$OrderRs[0]->tracking_no}}</a>
									@elseif(strtolower($OrderRs[0]->ship_method) == "usps")
										<a href="https://www.trackingmore.com/track/en/{{$OrderRs[0]->tracking_no}}" style="color:#333333;" target="_blank">{{$OrderRs[0]->tracking_no}}</a>
									@elseif(strtolower($OrderRs[0]->ship_method) == "ups")
										<a href="https://www.ups.com/mobile/track?trackingNumber={{$OrderRs[0]->tracking_no}}" style="color:#333333;" target="_blank">{{$OrderRs[0]->tracking_no}}</a>
									@endif	
									</p><br>
								@endif
							</li>
							<li>
								<div class="h6 mb-1">Shipping Address</div>
								<p class="mb-0">
									{{$OrderRs[0]->ship_first_name}} {{$OrderRs[0]->ship_last_name}}<br>
									{{$OrderRs[0]->ship_address1}}, {{$OrderRs[0]->ship_address2}}<br>
									{{$OrderRs[0]->ship_city}} - {{$OrderRs[0]->ship_zip}}<br>
									{{$OrderRs[0]->ship_state}} ,{{$OrderRs[0]->ship_country}}
								</p>
							</li>
							<li>
								<div class="h6 mb-1">Billing Address</div>
								<p class="mb-0">
									{{$OrderRs[0]->bill_first_name}} {{$OrderRs[0]->bill_last_name}}<br />
									{{$OrderRs[0]->bill_address1}}, {{$OrderRs[0]->bill_address2}}<br>
									{{$OrderRs[0]->bill_city}} - {{$OrderRs[0]->bill_zip}}<br>
									{{$OrderRs[0]->bill_state}}, {{$OrderRs[0]->bill_country}}<br>
								</p>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="diblock">
				<a href="{{ route('order-history') }}" class="text_c2 dflex aic">
					<svg class="svg_arrow_right me-1" aria-hidden="true" role="img" width="7" height="14">
						<use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use>
					</svg>
					Back
				</a>
			</div>
		</div>
	</div>
</div>
@endsection