@extends('pnkpanel.layouts.app_print')
@push('styles')
<style>
@media print {
   .noprint {
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
    height: 26px; /* image height */
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
						<a class="" href="javascript:printpreview();"><i class="bx bx-printer mr-1"></i> Print Packing Slip</a>
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
							</div>
						</section>
					</div>
				</div>
				
				
				<div class="row">
					<div class="col">
						<section class="card">
							<header class="card-header p-3">
								<h2 class="card-title">Shipping Information</h2>
							</header>
							<div class="card-body p-3">
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Shipping Adress </label>
									<div class="col-md-9 pt-2">
										<span class="font-weight-bold">{{ $order->ship_first_name }} {{ $order->ship_last_name }}</span><br/>
										<span>{{ $order->ship_address1 }},</span><br/>
										@if(isset($order->ship_address2) && $order->ship_address2 != '')
										<span>{{ $order->ship_address2 }},</span><br/>
										@endif
										<span>{{ $order->ship_city }} - {{ $order->ship_zip }}</span><br/>
										<span>{{ $order->ship_state }}, {{ $order->ship_country }}</span>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3 control-label font-weight-bold text-md-left pt-2">Shipiment Status </label>
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
								
							</div>
						</section>
					</div>
				</div>
				
				<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-header p-3">
								<h2 class="card-title">Items Information</h2>
							</div>
							<div class="card-body p-3">
								<div class="table-responsive">
									<table class="table table-ecommerce-simple table-ecommerce-simple-border-bottom table-striped mb-0" style="min-width: 690px;">
										<thead>
											<tr>
												<th class="font-weight-bold">Product Details</th>
												<th width="20%" class="text-center">Quantity</th>
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
												<td class="text-center border-bottom">{{ $orderItem->quantity }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="row noprint pt-0">
					<div class="col text-right">
						<a class="" href="javascript:printpreview();"><i class="bx bx-printer mr-1"></i> Print Packing Slip</a>
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
	if(confirm('Do You Want To Print Order Packing Slip?')) 
	{
		window.print();
	}
}
</script>
@endpush
