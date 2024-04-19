@extends('pnkpanel.layouts.app')
@section('content')
@php
	$status_bg = '#0102D0';
	$status_bg_arr = array(
		'Pending' => '#0102D0',
		'Completed' => '#006300',
		'Canceled' => '#E78442',
		'Declined' => '#AA0000',
		'Refunded' => '#EC2E15',
		'Partial Refund' => '#EC2E15',
		'Admin Review' => '#9E996B',
	);
	if(isset($status_bg_arr[$order->status]) && $status_bg_arr[$order->status] != '') {
		$status_bg = $status_bg_arr[$order->status];
	}

	$pay_status_bg = 'badge-danger';
	$pay_status_bg_arr = array(
		'Paid' => 'badge-success',
		'Unpaid' => 'badge-danger',
		'Inprocess' => 'badge-warning',
	);
	if(isset($pay_status_bg_arr[$order->pay_status]) && $pay_status_bg_arr[$order->pay_status] != '') {
		$pay_status_bg = $pay_status_bg_arr[$order->pay_status];
	}

	$previous_order = $order->previous();
	$next_order = $order->next();
@endphp
<style>
.spars-order td{padding:10px; font-size:15px; letter-spacing:1px; vertical-align:top;}
tr.spars-order:nth-child(even) {background: #ffffff}
tr.spars-order:nth-child(odd) {background: #f5f5f5}
</style>
<form action="{{ route('pnkpanel.order.update') }}" method="post" name="frmOrderDetails" id="frmOrderDetails" class="order-details action-buttons-fixed">
	<input type="hidden" name="order_id" id="order_id" value="{{ $order->order_id }}">
	<input type="hidden" name="actType" id="actType" value="UpdateOrder">
	@csrf
	<input type="hidden" name="total_items" id="total_items" value="{{ $order->returnOrderItems->count() }}">
	
	<div class="row">
		<div class="col-xl-4 mb-4 mb-xl-0">
			<div class="card card-modern">
				<div class="card-header">
					<h2 class="card-title">Order Information</h2>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-xl-auto mr-xl-5 pr-xl-5 mb-4 mb-xl-0">
							<label class="d-block"><strong class="text-color-dark">Order Number : </strong></label><label class="d-block">{{ $order->order_id }}</label>
							<label class="d-block"><strong class="text-color-dark">Order Date : </strong></label><label class="d-block">{{ \Carbon\Carbon::parse($order->order_datetime)->format('m/d/Y H:i:s') }}</label>
							<label class="d-block"><strong class="text-color-dark"> Order Status : </strong></label><label class="d-block"><span class="badge p-2" style="color:#FFFFFF;background-color:{{$status_bg}}">{{ $order->status }}</span></label>
							<label class="d-block"><strong class="text-color-dark"> Order Payment Status : </strong></label><label class="d-block"><span class="badge {{$pay_status_bg}} p-2">{{ $order->pay_status }}</span></label>
							<label class="d-block"><strong class="text-color-dark"> Customer IP Address : </strong></label><label class="d-block">{{ $order->customer_ip }}</label>
							<label class="d-block"><strong class="text-color-dark"> Customer Browser : </strong></label><label class="d-block">{{ $order->customer_browser }}</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-8">
			<div class="card card-modern">
				<div class="card-header">
					<h2 class="card-title">Addresses</h2>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6 mb-4 mb-xl-0">
							<h3 class="text-color-dark font-weight-bold text-4 line-height-1 mt-0 mb-3">BILLING</h3>
							<ul class="list list-unstyled list-item-bottom-space-0">
								<li>{{ $order->bill_first_name }} {{ $order->bill_last_name }}</li>
								<li>{{ $order->bill_address1 }}</li>
								<li>{{ $order->bill_address2 }}</li>
								<li>{{ $order->bill_city }}, {{ $order->bill_state }} {{ $order->bill_zip }}</li>
								<li>{{ $order->bill_country }}</li>
							</ul>
							<strong class="d-block text-color-dark">Email address:</strong> <a href="mailto:{{ $order->bill_email }}">{{ $order->ship_email }}</a>
							<strong class="d-block text-color-dark mt-3">Phone:</strong> <a href="tel:{{ $order->bill_phone }}" class="text-color-dark">{{ $order->bill_phone }}</a> 
						</div>

						<div class="col-sm-6">
							<h3 class="font-weight-bold text-color-dark text-4 line-height-1 mt-0 mb-3">SHIPPING</h3>
							<ul class="list list-unstyled list-item-bottom-space-0">
								<li>{{ $order->ship_first_name }} {{ $order->ship_last_name }}</li>
								<li>{{ $order->ship_address1 }}</li>
								<li>{{ $order->ship_address2 }}</li>
								<li>{{ $order->ship_city }}, {{ $order->ship_state }} {{ $order->ship_zip }}</li>
								<li>{{ $order->ship_country }}</li>
							</ul>
							<strong class="d-block text-color-dark">Email address:</strong> <a href="mailto:{{ $order->bill_email }}">{{ $order->ship_email }}</a>
							<strong class="d-block text-color-dark mt-3">Phone:</strong> <a href="tel:{{ $order->bill_phone }}" class="text-color-dark">{{ $order->ship_phone }}</a> 

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<section class="card card-collapsed">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Customer Old Order Number Information</h2>
				</header>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							@if(count($customerOtherOrders) > 0)
							@foreach($customerOtherOrders  as $customerOtherOrder)
							<a target="_blank" href="{{ route('pnkpanel.order.details', $customerOtherOrder->order_id) }}">{{ $customerOtherOrder->order_id}}</a>@if(!$loop->last) | @endif
							@endforeach
							@else
							None
							@endif
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="card card-modern">
				<div class="card-header">
					<h2 class="card-title">Items Ordered</h2>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-ecommerce-simple table-ecommerce-simple-border-bottom table-striped mb-0" style="min-width: 910px;">
							<thead>
								<tr>
									<th width="2%" class="text-center">Select</th>
									<th width="10%" class="text-center">Image</th>
									<th width="15%">Product Details</th>
									<th width="15%">Price Details</th>
									<th width="20%" class="text-center">Accept/Reject And Reason</th>
								</tr>
							</thead>
							<tbody>
								@foreach($order->returnOrderItems as $orderItem)	
								<?
									if(isset($order->products) && !empty($order->products) && $order->products->count() > 0){
										
										$main_image = $order->products[$loop->index]['image_name'];
									}else{
									
										$main_image = '';
									}
										
										$img_arr = Get_Product_Image_URL($main_image);
										
										if (isset($img_arr)){
											$thumb_img = $img_arr;
										} else {
											$thumb_img = config('const.NO_IMAGE_300');
										}
									 ?>
								<tr>
									<input type="hidden" name="orders_detail_id{{ $loop->index }}" id="orders_detail_id{{ $loop->index }}" value="{{ $orderItem->order_detail_id }}">
									<td class="text-center border-bottom">
										@if($orderItem->return_request_accept_reject == "")
										<input type="checkbox" name="bulk_select_return_items[]" id="bulk_select_return_items_{{ $orderItem->order_detail_id }}" value="{{ $orderItem->order_detail_id }}" class="bulk_select_return_item" data-id="{{ $orderItem->order_detail_id }}">
										@endif
									</td>
									<td class="text-center border-bottom">
										<a target="_blank" href="{{route('pnkpanel.product.edit', $orderItem->products_id)}}">
											<img src="{{ $thumb_img }}" border="0" width="125"></a>
									</td>
									<?php //dd($orderItem); ?>
									<td class="border-bottom">
										@if($orderItem->product_sku != '')
										<strong>Product SKU # : </strong><a href="{{route('pnkpanel.product.edit', $orderItem->products_id)}}" target="_blank" style="color:#ed407d;">{!! $orderItem->product_sku !!}</a><br/>
										@endif
										@if(trim($orderItem->product_name) != '')
										<strong>Product Name : </strong>{!! trim($orderItem->product_name) !!}<br/>
										@endif
										<strong>Return Message : </strong>{!! trim($orderItem->return_message) !!}<br/>
										<strong>Return Quantity : </strong>{!! trim($orderItem->return_request_quantity) !!}<br/>
										<a href="{{config('const.SITE_URL').'/'.trim($orderItem->product_url)}}" target="_blank" style="text-decoration:underline; color:#ed407d; line-height:4">Product URL</a><br/>
										
										@if($orderItem->attribute_info != '')
										<? 
											$att_info = explode("@@@",$orderItem->attribute_info);
											for($a = 0; $a < count($att_info); $a++){
												$att_arr = explode(":",$att_info[$a]);
												if(stristr($att_arr[1],"#")=="" && $att_arr[0]!='General Information'){?>
													<strong><?=$att_arr[0]?> : </strong><?=$att_arr[1]?><br>
												<? }
											}
										?>
										@endif
									</td>

									<td class="border-bottom">
										<p><b>Unit Price ($) : </b>{{ number_format($orderItem->unit_price, 2, '.', '') }}</p>
										<p><b>Quantity : </b>{{ $orderItem->quantity }}</p>
										<p><b>Total Price ($) : </b>{{ number_format($orderItem->total_price, 2, '.', '') }}</p>
									</td>

									<td class="text-center border-bottom">
										<div class="col-12">
											@if($orderItem->return_request_accept_reject_reason != "")
												<p>{{$orderItem->return_request_accept_reject_reason}}</p>
											@else
												<textarea class="form-control form-control-modern text-4 m-1" name="return_request_accept_reject_reason" id="return_request_accept_reject_reason_{{$orderItem->order_detail_id}}"></textarea>
											@endif
										</div>
										<div class="col-12">
											@if($orderItem->return_request_accept_reject == '0')
												<span class="btn btn-primary disabled">Rejected</span>
											@elseif($orderItem->return_request_accept_reject == '1')
												<span class="btn btn-primary disabled">Accepted</span>
											@else
												<div id="acceptRejectReturnItemDiv_{{$orderItem->order_detail_id}}">
													<a class="btn btn-success btnAcceptRejectReturnItem" data-type="accept" data-id="{{$orderItem->order_detail_id}}"><i class="fa fa-check mr-1"></i> Accept</a>
													<a class="btn btn-danger btnAcceptRejectReturnItem" data-type="reject" data-id="{{$orderItem->order_detail_id}}"><i class="fa fa-window-close mr-1"></i> Reject</a>
												</div>
											@endif
										</div>
										
									</td>

								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="row my-3">
						<div class="col-2 col-sm-2 col-xl-2 col-lg-2 col-md-2"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Common Reason</h3></div>
						<div class="col-5 col-sm-5 col-xl-5 col-lg-5 col-md-5">
							<textarea class="form-control form-control-modern text-4 m-1 mt-4" name="return_request_accept_reject_reason" id="return_request_accept_reject_reason" rows="1"></textarea>
						</div>
						<div class="col-5 col-sm-5 col-xl-5 col-lg-5 col-md-5 mt-4">
							<a class="btn btn-success btnAcceptRejectReturnItem" data-type="bulk_accept"><i class="fa fa-check mr-1"></i> Bulk Accept</a>
							<a class="btn btn-danger btnAcceptRejectReturnItem" data-type="bulk_reject"><i class="fa fa-window-close mr-1"></i> Bulk Reject</a>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="card card-modern">
				<div class="card-header">
					<h2 class="card-title">Refund calculation</h2>
				</div>
				<div class="card-body">
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Order Subtotal</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->sub_total }}" name="sub_total" id="sub_total" class="form-control form-control-modern text-4 m-1" size="10" readonly>
						</div>
					</div>
					@if($order->total_accepted_returned_amount > 0)
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Accepted Return Order total</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->total_accepted_returned_amount }}" name="total_accepted_returned_amount" id="total_accepted_returned_amount" class="form-control form-control-modern text-4 m-1" size="10" readonly>
						</div>
					</div>
					@endif
					
					@if($order->total_rejected_returned_amount > 0)
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Rejected Return Order total</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->total_rejected_returned_amount }}" name="total_rejected_returned_amount" id="total_rejected_returned_amount" class="form-control form-control-modern text-4 m-1" size="10" readonly>
						</div>
					</div>
					@endif
					
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Shipping Charge</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->shipping_amt }}" name="shipping_amt" id="shipping_amt" class="form-control form-control-modern text-4 m-1" size="10">
						</div>
					</div>
					
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Sales Tax</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->tax }}" name="tax" id="tax" class="form-control form-control-modern text-4 m-1" size="10">
						</div>
					</div>
					
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Auto Discount</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->auto_discount }}" name="auto_discount" id="auto_discount" class="form-control form-control-modern text-4 m-1" size="10">
						</div>
					</div>
					
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Quntity Discount</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->quantity_discount }}" name="quantity_discount" id="quantity_discount" class="form-control form-control-modern text-4 m-1" size="10">
						</div>
					</div>
					
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right">@if($order->coupon_code != '')<small>( Coupon Code Used :{{ $order->coupon_code }} )</small>@endif<h3 class="font-weight-bold text-color-dark text-4 mb-3">Coupon Discount</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->coupon_amount }}" name="coupon_amount" id="coupon_amount" class="form-control form-control-modern text-4 m-1" size="10">
						</div>
					</div>
					
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Order Total</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->order_total }}" name="order_total" id="order_total" class="form-control form-control-modern text-4 m-1" size="10" readonly>
						</div>
					</div>

					@if($order->total_accepted_returned_amount > 0)
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Total Refund Amount</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->total_accepted_returned_amount }}" name="total_accepted_returned_amount" id="total_accepted_returned_amount" class="form-control form-control-modern text-4 m-1" size="10" readonly>
						</div>
					</div>
					@endif
					
					<div class="row justify-content-end flex-lg-row mt-3">
						<div class="col-12 text-right">
							<a class="btn btn-primary btnRecalculate"><i class="bx bx-calculator mr-1"></i> Recalculate</a>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="row action-buttons">
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);" class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a> </div>
	</div>
	
</form>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.order.return_order') }}";
let url_edit = "{{ route('pnkpanel.order.return_order.details', ':id') }}";
let url_update = "{{ route('pnkpanel.order.update') }}";
let url_delete = "{{ route('pnkpanel.order.delete', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/return_order_details.js') }}"></script>
@endpush
