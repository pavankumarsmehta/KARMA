@extends('pnkpanel.layouts.app_print')
@push('styles')
<style>
@media print {
   .noprint {
	  display: none;
      visibility: hidden;
   }
   .pagebreak {
	   border:0;
	   page-break-after: always;
   }
}
.pagebreak{
	cursor:default;
	display:block;
	border:0;
	width:100%;
	height:5px;
	border:1px dashed #666;
	margin-top:15px;
	page-break-before:always
}

#scissors {
    height: 43px; /* image height */
    width: 90%;
    margin: auto auto;
    background-image: url('{{ asset('pnkpanel/images/scissors_icon.png') }}');
    background-repeat: no-repeat;
    background-position: right;
    background-size: 20px 16.5px;
    position: relative;
    overflow: hidden;
}
#scissors:after {
    content: "";
    position: relative;
    top: 50%;
    display: block;
    border-top: 1px dashed #777;
    margin-top: -1px;
    page-break-after: always;
}
</style>
@endpush
@section('content')
@if(count($allOrders) > 0)

	@foreach($allOrders as $order)
	@if(!is_null($order))
	<section class="body" style="min-height: unset;">
		@include('pnkpanel.layouts.header_print')
		
		<div class="inner-wrapper pt-0" style="min-height: unset;">
			<section role="main" class="content-body content-body-modern mt-0">
				
				<div class="row noprint">
					<div class="col text-right">
						<a class="" href="javascript:printpreview();"><i class="bx bx-printer mr-1"></i> Print Order Slip</a>
					</div>
				</div>
				
				<div class="row pt-0">
					<div class="col">
						<section class="card">
							<header class="card-header p-3">
								<h2 class="card-title">Order Information</h2>
							</header>
							<div class="card-body p-3">
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Order Number </label>
									<div class="col-md-9 pt-2">
										<span>{{ $order->order_id }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Order Date </label>
									<div class="col-md-9 pt-2">
										<span>{{ \Carbon\Carbon::parse($order->order_datetime)->format('m/d/Y') }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Order Status </label>
									<div class="col-md-9 pt-2">
										<span>{{ $order->status }}</span>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
				
				
				<div class="row">
					<div class="col-sm-6">
						<section class="card">
							<header class="card-header p-3">
								<h2 class="card-title">Billing Address</h2>
							</header>
							<div class="card-body p-3">
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Customer Name </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_first_name }} {{ $order->bill_last_name }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Address 1 </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_address1 }}</span>
									</div>
								</div>
								@if(isset($order->bill_address2) && $order->bill_address2 != '')
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Address 2 </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_address2 }}</span>
									</div>
								</div>
								@endif
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">City </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_city }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Postal Code / Zip </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_zip }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">State </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_state }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Country </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_country }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Phone # </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_phone }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Email ID </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->bill_email }}</span>
									</div>
								</div>
		
							</div>
						</section>
					</div>
					<div class="col-sm-6">
						<section class="card">
							<header class="card-header p-3">
								<h2 class="card-title">Shipping Address</h2>
							</header>
							<div class="card-body p-3">
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Customer Name </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_first_name }} {{ $order->ship_last_name }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Address 1 </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_address1 }}</span>
									</div>
								</div>
								@if(isset($order->ship_address2) && $order->ship_address2 != '')
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Address 2 </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_address2 }}</span>
									</div>
								</div>
								@endif
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">City </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_city }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Postal Code / Zip </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_zip }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">State </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_state }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Country </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_country }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Phone # </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_phone }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-5 control-label font-weight-bold text-md-left pt-2">Email ID </label>
									<div class="col-md-7 pt-2">
										<span>{{ $order->ship_email }}</span>
									</div>
								</div>
		
							</div>
						</section>
					</div>
				</div>
				
				<div class="row">
					<div class="col">
						<section class="card">
							<header class="card-header p-3">
								<h2 class="card-title">Payment Information & Shipping Information</h2>
							</header>
							<div class="card-body p-3">
								@if(isset($order->shipping_information) && $order->shipping_information != '')
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Shipping Method </label>
									<div class="col-md-9 pt-2">
										<span>{{ $order->shipping_information }}</span>
									</div>
								</div>
								@endif
								@if(isset($order->payment_method) && $order->payment_method != '')
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Payment Method </label>
									<div class="col-md-9 pt-2">
										<span>{{ $order->payment_method }}</span>
									</div>
								</div>
								@endif
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Order Payment Status </label>
									<div class="col-md-9 pt-2">
										<span>{{ $order->pay_status }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Order Shipment Status </label>
									<div class="col-md-9 pt-2">
										<span>{{ $order->ship_status }}</span>
									</div>
								</div>
								@if(isset($order->ship_method) && $order->ship_method != '')
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Shipping Via </label>
									<div class="col-md-9 pt-2">
										<span>{{ $order->ship_method }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Tracking Number </label>
									<div class="col-md-9 pt-2">
										<span>{{ $order->tracking_no }}</span>
									</div>
								</div>
								@endif
								@if(isset($order->order_comment) && $order->order_comment != '')
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Admin Comment </label>
									<div class="col-md-9 pt-2">
										<span>{{ str_replace("\n", "<br>", $order->order_comment) }}</span>
									</div>
								</div>
								@endif
							</div>
						</section>
					</div>
				</div>
				
				<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-header p-3">
								<h2 class="card-title">Items Ordered</h2>
							</div>
							<div class="card-body p-3">
								<div class="table-responsive">
									<table class="table table-ecommerce-simple table-ecommerce-simple-border-bottom table-striped mb-0" style="min-width: 690px;">
										<thead>
											<tr>
												<th class="font-weight-bold">Product Details</th>
												<th width="12%" class="text-center">Unit Price</th>
												<th width="12%" class="text-center">Quantity</th>
												<th width="12%" class="text-center">Total Price</th>
											</tr>
										</thead>
										<tbody>
											@foreach($order->orderItems as $orderItem)
											<tr>
												<td class="border-bottom">
													@if($orderItem->product_sku != '')
													<strong>Product SKU # : </strong>{!! $orderItem->product_sku !!}<br/>
													@endif
													@if(trim($orderItem->product_name) != '')
													<strong>Product Name : </strong>{!! trim(stripslashes($orderItem->product_name)) !!}<br/>
													@endif
													@if($orderItem->attribute_info != '')
													{!! stripslashes($orderItem->attribute_info) !!}
													@endif
												</td>
												<td class="text-center border-bottom">${{ number_format($orderItem->unit_price, 2, '.', '') }}</td>
												<td class="text-center border-bottom">{{ $orderItem->quantity }}</td>
												<td class="text-center border-bottom">${{ number_format($orderItem->total_price, 2, '.', '') }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								
													
								<div class="row justify-content-end flex-lg-row my-3">
									<div class="col-10 col-sm-8 col-xl-11 col-lg-10 col-md-10 text-right"><label class="font-weight-bold text-color-dark">Order Subtotal</label></div>
									<div class="col-2 col-sm-4 col-xl-1 col-lg-2 col-md-2 ">${{ $order->sub_total }}</div>
								</div>
								
								@if($order->shipping_amt > 0)
								<div class="row justify-content-end flex-lg-row my-3">
									<div class="col-10 col-sm-8 col-xl-11 col-lg-10 col-md-10 text-right"><label class="font-weight-bold text-color-dark">Shipping Charge</label></div>
									<div class="col-2 col-sm-4 col-xl-1 col-lg-2 col-md-2 ">${{ $order->shipping_amt }}</div>
								</div>
								@endif
								
								@if($order->tax > 0)
								<div class="row justify-content-end flex-lg-row my-3">
									<div class="col-10 col-sm-8 col-xl-11 col-lg-10 col-md-10 text-right"><label class="font-weight-bold text-color-dark">Sales Tax</label></div>
									<div class="col-2 col-sm-4 col-xl-1 col-lg-2 col-md-2 ">${{ $order->tax }}</div>
								</div>
								@endif
								
								@if($order->auto_discount > 0)
								<div class="row justify-content-end flex-lg-row my-3">
									<div class="col-10 col-sm-8 col-xl-11 col-lg-10 col-md-10 text-right"><label class="font-weight-bold text-color-dark">Auto Discount</label></div>
									<div class="col-2 col-sm-4 col-xl-1 col-lg-2 col-md-2 ">${{ $order->auto_discount }}</div>
								</div>
								@endif
								
								@if($order->quantity_discount > 0)
								<div class="row justify-content-end flex-lg-row my-3">
									<div class="col-10 col-sm-8 col-xl-11 col-lg-10 col-md-10 text-right"><label class="font-weight-bold text-color-dark">Quntity Discount</label></div>
									<div class="col-2 col-sm-4 col-xl-1 col-lg-2 col-md-2 ">${{ $order->quantity_discount }}</div>
								</div>
								@endif
								
								@if($order->coupon_amount > 0)
								<div class="row justify-content-end flex-lg-row my-3">
									<div class="col-10 col-sm-8 col-xl-11 col-lg-10 col-md-10 text-right"><label class="font-weight-bold text-color-dark">Coupon Discount</label></div>
									<div class="col-2 col-sm-4 col-xl-1 col-lg-2 col-md-2 ">${{ $order->coupon_amount }}</div>
								</div>
								@endif
								
								<div class="row justify-content-end flex-lg-row my-3">
									<div class="col-10 col-sm-8 col-xl-11 col-lg-10 col-md-10 text-right"><label class="font-weight-bold text-color-dark">Order Total</label></div>
									<div class="col-2 col-sm-4 col-xl-1 col-lg-2 col-md-2 ">${{ $order->order_total }}</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="row noprint pt-0">
					<div class="col text-right">
						<a class="" href="javascript:printpreview();"><i class="bx bx-printer mr-1"></i> Print Order Slip</a>
					</div>
				</div>
				
			</section>
		</div>
	</section>
	{{--<!--@if(count($allOrders) > 1)-->--}}
	<div id="scissors"></div>
	{{--<!--@endif-->--}}
	@endif
	@endforeach
	
@else
	No Records Available...
@endif	
@endsection
@push('scripts')
<script>
function printpreview()
{
	rtn = confirm('Do You Want To Print Order Slip?')
	if(rtn==false) 
	{
		//window.close();
	} else 
	{
		window.print();
	}
}
</script>
@endpush
