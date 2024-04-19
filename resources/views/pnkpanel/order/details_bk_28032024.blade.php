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
<form action="{{ route('pnkpanel.order.update') }}" method="post" name="frmOrderDetails" id="frmOrderDetails" class="order-details action-buttons-fixed">
	<input type="hidden" name="order_id" value="{{ $order->order_id }}">
	<input type="hidden" name="actType" id="actType" value="UpdateOrder">
	@csrf
	<input type="hidden" name="total_items" id="total_items" value="{{ $order->orderItems->count() }}">
	
	<div class="row">
		<div class="col-xl-4">
			<ul class="pager m-0">
				<li class="previous @if(is_null($previous_order)){{ 'disabled' }}@endif"><a href="{{ is_null(optional($order->previous())->order_id) ? 'javascript:void(0);' : route('pnkpanel.order.details', optional($order->previous())->order_id) }}"><i class="fas fa-angle-left"></i> Previous Order </a></li>
				<li class="next @if(is_null($next_order)){{ 'disabled' }}@endif"><a href="{{ is_null(optional($order->next())->order_id) ? 'javascript:void(0);' : route('pnkpanel.order.details', optional($order->next())->order_id) }}"> Next Order <i class="fas fa-angle-right"></i></a></li>
			</ul>
		</div>
		<div class="col-xl-8 text-right mt-3 mt-xl-0">
			<a class="btn btn-primary btn-sm mr-1 btnPrintPackingSlip" data-id="{{ $order->order_id }}" onclick="window.open('{{ route('pnkpanel.order.packing_slip', ['start_id' => $order->order_id, 'end_id' => $order->order_id]) }}', 'xwin','toolbar=0,scrollbars=1,location=0,status=0,menubars=0,resizable=0, width=820,height=600,top=0,left=0,maximize=0')"><i class="bx bx-printer mr-1"></i> Print Packing Slip</a>
			<a class="btn btn-primary btn-sm btnPrintOrderSlip" data-id="{{ $order->order_id }}" onclick="window.open('{{ route('pnkpanel.order.order_slip', ['start_id' => $order->order_id, 'end_id' => $order->order_id]) }}', 'xwin','toolbar=0,scrollbars=1,location=0,status=0,menubars=0,resizable=0, width=820,height=600,top=0,left=0,maximize=0')"><i class="bx bx-printer mr-1"></i> Print Order Slip</a>
		</div>
	</div>
	
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
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group mb-3">
										<input type="text" class="form-control form-control-modern @error('ship_first_name') error @enderror" id="ship_first_name" name="ship_first_name" value="{{ old('ship_first_name', $order->ship_first_name) }}" placeholder="First Name">
										@error('ship_first_name')
										<label class="error" for="ship_first_name" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group mb-3">
										<input type="text" class="form-control form-control-modern @error('ship_last_name') error @enderror" id="ship_last_name" name="ship_last_name" value="{{ old('ship_last_name', $order->ship_last_name) }}" placeholder="Last Name">
										@error('ship_last_name')
										<label class="error" for="ship_last_name" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group mb-3">
										<input type="text" class="form-control form-control-modern @error('ship_address1') error @enderror" id="ship_address1" name="ship_address1" value="{{ old('ship_address1', $order->ship_address1) }}" placeholder="Address 1" size="30">
										@error('ship_address1')
										<label class="error" for="ship_address1" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group mb-3">
										<input type="text" class="form-control form-control-modern @error('ship_address2') error @enderror" id="ship_address2" name="ship_address2" value="{{ old('ship_address2', $order->ship_address2) }}" placeholder="Address 2" size="30">
										@error('ship_address2')
										<label class="error" for="ship_address2" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group mb-3">
										<input type="text" class="form-control form-control-modern @error('ship_city') error @enderror" id="ship_city" name="ship_city" value="{{ old('ship_city', $order->ship_city) }}" placeholder="City">
										@error('ship_city')
										<label class="error" for="ship_city" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group mb-3">
										<input type="text" class="form-control form-control-modern @error('ship_zip') error @enderror" id="ship_zip" name="ship_zip" value="{{ old('ship_zip', $order->ship_zip) }}" placeholder="Postal Code / Zip">
										@error('ship_zip')
										<label class="error" for="ship_zip" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group mb-3">
										<select class="form-control form-control-modern @error('ship_country') error @enderror" id="ship_country" name="ship_country" onchange="Ship_StateCheck();" data-plugin-selectTwo>
											{!! displaycountry(old('ship_country', $order->ship_country)) !!}
										</select>
										@error('ship_country')
										<label class="error" for="ship_country" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group mb-3" id="DIV_SH_US_STATE">
										<select class="form-control form-control-modern @error('ship_state') error @enderror" id="ship_state" name="ship_state" data-plugin-selectTwo>
											{!! displaystate(old('ship_state', $order->ship_state)) !!}
										</select>
										@error('ship_state')
										<label class="error" for="ship_state" role="alert">{{ $message }}</label>
										@enderror
									</div>
									<div class="form-group mb-3 pt-0" id="DIV_SH_OTHER_STATE" style="display: none;">
										@php
										$ship_state_other ='';
										if($order->ship_country != 'US') {
											$ship_state_other = $order->ship_state;
										}
										@endphp
										<input type="text" class="form-control form-control-modern @error('ship_state_other') error @enderror" id="ship_state_other" name="ship_state_other" value="{{ old('ship_state_other', ($order->ship_country != 'US' ? $ship_state_other : '')) }}">
										@error('ship_state_other')
										<label class="error" for="ship_state_other" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group mb-3">
										<input type="text" class="form-control form-control-modern @error('ship_phone') error @enderror" id="ship_phone" name="ship_phone" value="{{ old('ship_phone', $order->ship_phone) }}" placeholder="Phone Number">
										@error('ship_phone')
										<label class="error" for="ship_phone" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group mb-3">
										<input type="text" class="form-control form-control-modern @error('ship_email') error @enderror" id="ship_email" name="ship_email" value="{{ old('ship_email', $order->ship_email) }}" placeholder="Email Address" size="30">
										@error('ship_email')
										<label class="error" for="ship_email" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="col-sm-12">
									<a class="btn btn-primary btnUpdateShippingAddress"><i class="bx bx-save mr-1"></i> Update Shipping Address</a>
								</div>
							</div>
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
			<section class="card card-collapsed">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Order Payment & Shipping Information</h2>
				</header>
				<div class="card-body">
					@if($order->available_shipping_method != '')
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Available Shipper During Order Processing </label>
						<div class="col-lg-6">
							<div class="row">
								<div class="col-lg-4 col-6"><strong>Shipper Name</strong></div>
								<div class="col-lg-4 col-6"><strong>Shipping Charge</strong></div>
							</div>
							@php ($available_shipping_methods = unserialize($order->available_shipping_method))
							@foreach($available_shipping_methods as $key => $value)
							<div class="row">
								<div class="col-lg-4 col-6">{{ $key }}</div>
								<div class="col-lg-4 col-6">{{ $value }}</div>
							</div>
							@endforeach
							<span class="help-block">Note: Above charges not include product surcharges and additional state charges.</span>
						</div>
					</div>
					@endif
					
					@if($order->shipper != '')
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Order Placed Shipper Name </label>
						<div class="col-lg-6 pt-2">
							<span>
							@if(strtolower($order->shipper) == "fedex")
								FedEx Ground
							@elseif(strtolower($order->shipper) == "fedexfreight")
								FedEx Freight Line
							@elseif(strtolower($order->shipper) == "southeastern")
								South Eastern Freight Line
							@elseif(strtolower($order->shipper) == "xpressglobal")
								Xpress Global
							@else
								{{ $order->shipper }}
							@endif
							</span>
						</div>
					</div>
					@endif

					@if($order->shipping_information != '')
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Shipping Method </label>
						<div class="col-lg-6 pt-2">
							<span>{{ $order->shipping_information }}</span>
						</div>
					</div>
					@endif
					
					@if($order->payment_method != '')
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Payment Method </label>
						<div class="col-lg-6 pt-2">
							<span>{{ $order->payment_method }}</span>
						</div>
					</div>
					@endif
					
					@if($order->payment_gateway_response != '' && preg_match("/^The credit card number is invalid/i", $order->payment_gateway_response))
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Payment Information </label>
						<div class="col-lg-6 pt-2">
							<span>{!! $order->ccinfo !!}</span>
						</div>
					</div>
					@endif
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Transaction Response </label>
						<div class="col-lg-6">
							<textarea name="transaction_info" id="transaction_info" class="form-control form-control-modern" cols="70" rows="4">{!! old('transaction_info', htmlentities($order->transaction_info)) !!}</textarea>
							@error('transaction_info')
							<label class="error" for="transaction_info" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Payment Gateway Response </label>
						<div class="col-lg-6">
							<textarea name="payment_gateway_response" id="payment_gateway_response" class="form-control form-control-modern" cols="70" rows="4">{!! old('payment_gateway_response', htmlentities($order->payment_gateway_response)) !!}</textarea>
							@error('payment_gateway_response')
							<label class="error" for="payment_gateway_response" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Order Comment </label>
						<div class="col-lg-6">
							<textarea name="order_comment" id="order_comment" class="form-control form-control-modern" cols="70" rows="4">{!! old('order_comment', htmlentities($order->order_comment)) !!}</textarea>
							@error('order_comment')
							<label class="error" for="order_comment" role="alert">{{ $message }}</label>
							@enderror
							<span class="help-block">Order comment from admin to customer. it will show in My Account under order history.</span>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Administrator Remarks </label>
						<div class="col-lg-6">
							<textarea name="admin_remark" id="admin_remark" class="form-control form-control-modern" cols="70" rows="4">{!! old('admin_remark', htmlentities($order->admin_remark)) !!}</textarea>
							@error('admin_remark')
							<label class="error" for="admin_remark" role="alert">{{ $message }}</label>
							@enderror
							<span class="help-block">Order remark from site admin and it will show only in panel.</span>
						</div>
					</div>
					
					
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Order Status </label>
						<div class="col-lg-6">
							<input type="hidden" name="old_order_status" value="{{ $order->status }}" >
							<select name="order_status" id="order_status" class="form-control form-control-modern">
								@foreach($allOptions = ['Pending','In Process', 'Completed', 'Canceled','Declined','Refunded','Partial Refund','Admin Review'] as $option)
								<option value="{{ $option }}" {{ $order->status == $option ? 'selected' : '' }}>{{ $option }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Order Payment Status </label>
						<div class="col-lg-6">
							<select name="pay_status" id="pay_status" class="form-control form-control-modern">
								@foreach($allOptions = ['Paid', 'Unpaid' ,'Inprocess'] as $option)
								<option value="{{ $option }}" {{ $order->pay_status == $option ? 'selected' : '' }}>{{ $option }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Order Shipment Status </label>
						<div class="col-lg-6">
							<select name="ship_status" id="ship_status" class="form-control form-control-modern">
								@foreach($allOptions = ['Pending', 'Shipped'] as $option)
								<option value="{{ $option }}" {{ $order->ship_status == $option ? 'selected' : '' }}>{{ $option }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Shipping Via </label>
						<div class="col-lg-6">
							<select name="ship_method" id="ship_method" class="form-control form-control-modern">
								@foreach($allOptions = ['','USPS','UPS','FedEx'] as $option)
								<option value="{{ $option }}" {{ $order->ship_method == $option ? 'selected' : '' }}>{{ $option }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Tracking Number </label>
						<div class="col-lg-6">
							<input type="text" name="tracking_no" id="tracking_no" value="{{ $order->tracking_no }}" class="form-control form-control-modern" size="20" />
							@error('tracking_no')
							<label class="error" for="tracking_no" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					{{--
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Customer Comment </label>
						<div class="col-lg-6 pt-2">
							<span>@if(trim($order->customer_comment) != '' ){!! htmlentities($order->customer_comment) !!}@else{{ 'None' }}@endif</span>
						</div>
					</div>
					--}}
					
				</div>
			</section>
		</div>
	</div>
	
	{{--<div class="row">
		<div class="col">
			<section class="card card-collapsed">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Refund Order</h2>
				</header>
				<div class="card-body">
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Total Refunded Amount </label>
						<div class="col-lg-6 pt-2">
							<span>{{ $order->total_refund_amount }}</span>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Refund Amount </label>
						<div class="col-lg-6">
							<input type="text" name="refund_amount" id="refund_amount" value="{{ old('refund_amount') }}" class="form-control form-control-modern" size="20" />
							@error('refund_amount')
							<label class="error" for="refund_amount" role="alert">{{ $message }}</label>
							@enderror
							<span class="help-block">Note : Please enter only positive numeric value.</span>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Refund Comment </label>
						<div class="col-lg-6">
							<textarea name="refund_comment" id="refund_comment" class="form-control form-control-modern" cols="70" rows="4">{!! old('refund_comment') !!}</textarea>
							@error('refund_comment')
							<label class="error" for="refund_comment" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2"></label>
						<div class="col-lg-6">
							<a class="btn btn-primary btnProcessRefund"><i class="bx bx-undo mr-1"></i> Process Refund</a>
						</div>
					</div>
					
				</div>
			</section>
		</div>
	</div>
	
	<div class="row">
		<div class="col">
			<section class="card card-collapsed">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Refund History</h2>
				</header>
				<div class="card-body">
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Total Refunded Amount </label>
						<div class="col-lg-6 pt-2">
							<span>{{ $order->total_refund_amount }}</span>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Refund Transaction Response </label>
						<div class="col-lg-6 pt-2">
							<span>{!! $order->refund_transaction_response !!}</span>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2">Refund Comment </label>
						<div class="col-lg-6 pt-2">
							<span>{!! $order->refund_comment !!}</span>
						</div>
					</div>
					
				</div>
			</section>
		</div>
	</div>--}}

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
									<th width="15%" class="text-center">Image</th>
									<th width="49%">Product Details</th>
									<th width="12%" class="text-center">Unit Price ($)</th>
									<th width="12%" class="text-center">Quantity</th>
									<th width="12%" class="text-center">Total Price ($)</th>
								</tr>
							</thead>
							<tbody>
								
								@foreach($order->orderItems as $orderItem)	
									<?
									//dd($order->products);
									if($order->products)
										$main_image = $order->products[$loop->index]['image_name'];
										
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
										<a target="_blank" href="{{route('pnkpanel.product.edit', $orderItem->products_id)}}">
											<img src="{{ $thumb_img }}" border="0" width="125"></a>
									</td>
									<?php //dd($orderItem); ?>
									<td class="border-bottom">
										@if($orderItem->product_sku != '')
										<strong>Product SKU # : </strong><a href="{{config('app.SITE_URL')}}/pnkpanel/product/edit/{{$orderItem->products_id}}" target="_blank" style="color:#ed407d;">{!! $orderItem->product_sku !!}</a><br/>
										@endif
										@if(trim($orderItem->product_name) != '')
										<strong>Product Name : </strong>{!! trim($orderItem->product_name) !!}<br/>
										<a href="{{trim($orderItem->product_url)}}" target="_blank" style="text-decoration:underline; color:#ed407d; line-height:4">Product URL</a><br/>
										@endif
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
										{{-- {!! stripslashes($orderItem->attribute_info) !!} --}}
										@endif
									</td>
									<td class="text-center border-bottom"><input type="text" name="unit_price{{ $loop->index }}" id="unit_price{{ $loop->index }}" value="{{ number_format($orderItem->unit_price, 2, '.', '') }}" class="form-control form-control-modern" size="7"></td>
									<td class="text-center border-bottom"><input type="text" name="quantity{{ $loop->index }}" id="quantity{{ $loop->index }}" value="{{ $orderItem->quantity }}" class="form-control form-control-modern" size="3"></td>
									<td class="text-center border-bottom"><input type="text" name="total_price{{ $loop->index }}" id="total_price{{ $loop->index }}" value="{{ number_format($orderItem->total_price, 2, '.', '') }}" class="form-control form-control-modern" size="10" readonly></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					
					<div class="row justify-content-end flex-lg-row my-3">
						<div class="col-7 col-sm-8 col-xl-10 col-lg-9 col-md-8 text-right"><h3 class="font-weight-bold text-color-dark text-4 mb-3">Order Subtotal</h3></div>
						<div class="col-5 col-sm-4 col-xl-2 col-lg-3 col-md-4 ">
							<input type="text" value="{{ $order->sub_total }}" name="sub_total" id="sub_total" class="form-control form-control-modern text-4 m-1" size="10" readonly>
						</div>
					</div>
					
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
		<div class="col-12 col-md-auto">
			<button type="button" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Update Order </button>
		</div>
		{{--<div class="col-12 col-md-auto pl-md-0 mt-3 mt-md-0">
			<a class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSendEmailToClient" data-loading-text="Loading..."> <i class="bx bx-mail-send text-4 mr-2"></i> Send Email to Client </a>
		</div>--}}
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);" class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a> </div>
		{{--<!--<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);" data-id="{{ $order->order_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete Order </a> </div>-->--}}
	</div>
</form>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.order.list') }}";
let url_edit = "{{ route('pnkpanel.order.details', ':id') }}";
let url_update = "{{ route('pnkpanel.order.update') }}";
let url_delete = "{{ route('pnkpanel.order.delete', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/order_details.js') }}"></script>
@endpush
