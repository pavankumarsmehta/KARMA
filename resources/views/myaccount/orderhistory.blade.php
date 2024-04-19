@extends('layouts.app')
@section('content')
<div class="container myact orderhistory">
	@include('myaccount.breadcrumbs')
	<h1 class="hidden-md-up h2" tabindex="0">Order History</h1>
	@include('myaccount.myaccountmenu')
	<div class="hd">
		<h1 class="hidden-sm-down h2" tabindex="0">Order History</h1>
		<form action="" id="frmsearchorder" method="post" class="filters_ser">
			<input type="hidden" name="type" value="{{$type}}"/>
			@csrf
				<input type="text" class="form-control" name="orders_no" id="orders_no" value="{{ $orders_no }}" placeholder="Search by Order No" required="">
				<svg class="svg_search" aria-hidden="true" role="img" width="23" height="23" fill="none">
					<use href="#svg_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_search"></use>
				</svg>
				<a href="javascript:void(0);" class="btn" id="search_order" tabindex="0" title="Filters" aria-label="Filters">
					<svg class="svg_filter me-1" aria-hidden="true" role="img" width="20" height="20">
						<use href="#svg_filter" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_filter"></use>
					</svg>
					Filters
				</a>			
		</form>
	</div>
	<ul class="order_flinks">
		<li class="{{($type=='all'?'active':'')}}"><a href="{{route('order-history')}}">All({{$all}})</a></li>
		<li class="{{($type=='pending'?'active':'')}}"><a href="{{route('order-history')}}?type=pending">Pending({{$Pending}})</a></li>
		<li class="{{($type=='declined'?'active':'')}}"><a href="{{route('order-history')}}?type=declined">Declined({{$Declined}})</a></li>
		<li class="{{($type=='canceled'?'active':'')}}"><a href="{{route('order-history')}}?type=canceled">Canceled({{$Canceled}})</a></li>
		<li class="{{($type=='completed'?'active':'')}}"><a href="{{route('order-history')}}?type=completed">Success({{$Completed}})</a></li>
	</ul>
	<table class="res_table ordhis_table mb-4 tac_md" width="100%">
		<thead>
			<tr>
				<th>Order No</th>
				<th>Order Date</th>
				<th>Sub Total</th>
				<th>Total Amount</th>
				<th>Payment Status</th>
				<th>Tracking No</th>
				<th>Order Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@if(count($OrdResult) > 0)
				@php
					//dd($OrdResult);
					$status = '';
				@endphp
				@foreach($OrdResult as $order_result_key => $order_result_value)
				@if($order_result_value->status == 'Pending')
					@php
					$status = 'pending';
					@endphp
				@elseif($order_result_value->status == 'Declined')
					@php
					$status = 'declined';
					@endphp
				@elseif($order_result_value->status == 'Refunded')
					@php
					$status = 'refunded';
					@endphp
				@elseif($order_result_value->status == 'Canceled')
					@php
					$status = 'canceled';
					@endphp
				@else
					@php
					$status = 'success';
					@endphp
				@endif
				<tr>
					<td data-th="Order No">{{ $order_result_value->order_id }}</td>
					<td data-th="Order Date">{{ $order_result_value->datetime }}</td>
					<td data-th="Sub Total">{{ Make_Price($order_result_value->sub_total,true) }}</td>
					<td data-th="Total Amount">{{ Make_Price($order_result_value->order_total,true) }}</td>
					<td data-th="Payment Status" class="red-color">{{ $order_result_value->pay_status }}</td>
					<td data-th="Payment Status" class="red-color">
					@if(strtolower($order_result_value->ship_method) == "fedex")
						<a href="https://www.fedex.com/fedextrack/?trknbr={{$order_result_value->tracking_no}}" style="color:#333333;" target="_blank">{{$order_result_value->tracking_no}}</a>
					@elseif(strtolower($order_result_value->ship_method) == "usps")
						<a href="https://www.trackingmore.com/track/en/{{$order_result_value->tracking_no}}" style="color:#333333;" target="_blank">{{$order_result_value->tracking_no}}</a>
					@elseif(strtolower($order_result_value->ship_method) == "ups")
						<a href="https://www.ups.com/mobile/track?trackingNumber={{$order_result_value->tracking_no}}" style="color:#333333;" target="_blank">{{$order_result_value->tracking_no}}</a>
					@endif
					</td>
					<td data-th="Order Status" class=""><span class="order_status {{$status}}">{{ $order_result_value->status }}</span></td>
					<td data-th="Action" class="reorder_edit">
						<a href="{{route('order-detail',$order_result_value->order_id)}}" title="View">
							<svg class="svg_eye" aria-hidden="true" role="img" width="25" height="25">
								<use href="#svg_eye" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye"></use>
							</svg>
						</a>
						{{--<a href="javascript:void(0);">
							<svg class="svg_reorder" aria-hidden="true" role="img" width="25" height="25">
								<use href="#svg_reorder" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_reorder"></use>
							</svg>
						</a>--}}
					</td>
				</tr>
				@endforeach
			@else
				<tr>
					<td colspan="8" class="no-data-td tac"><span class="errmsg tac fwidth">Sorry, No orders found!</span></td>
				</tr>
			@endif
		</tbody>
	</table>
	<div class="myact-pagination">
		{!! $OrdResult->appends(array('type' => $type))->onEachSide(0)->links('layouts.pagination') !!}
	</div>
	<div class="myact-btn">
		<a href="{{ route('myaccount') }}" class="myact-back" tabindex="0" title="Back" aria-label="Back">
			<svg class="svg_arrow_right" aria-hidden="true" role="img" width="7" height="14"><use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use></svg>Back
		</a>
	</div>	
</div>
@endsection