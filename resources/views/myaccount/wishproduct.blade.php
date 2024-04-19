@extends('layouts.app')
@section('content')
@php 
	$APP_URLS = config('const.APP_URLS');
@endphp
<div class="container myact">
	@include('myaccount.breadcrumbs')
	<h1 class="hidden-md-up h2" tabindex="0">My Wishlist</h1>
	@include('myaccount.myaccountmenu')
	<h1 class="pb-3 hidden-sm-down h2" tabindex="0">My Wishlist</h1>	
	@if (Session::has('error'))
	<div class="text-center pt-3">
		<x-message :attr="[
								'classname' => 'frmerror', 
								'message' => Session::get('error')]" />
	</div>
	@endif
	@if (Session::has('success'))
	<div class="text-center pt-3">
		<x-message :attr="[
								'classname' => 'frmsuccess', 
								'message' => Session::get('success')]" />
	</div>
	@endif
	@if($WishCatRS)
	<div class="myact_subhd">{{ $WishCatRS->name }}</div>
	@endif
	<form name="frmwish" id="frmwish" method="delete" action="">
		<input type="hidden" name="action" value="" />
		<table class="res_table ordhis_table mb-4" width="100%">
			<thead>
				<tr>
					@if(count($WishProdRS) > 0)
					<td><input class="form-check-input" type="checkbox" name="chk" value="1" id="checkAll" aria-label="Hidden Label" /></td>
					@endif
					<td tabindex="0">IMAGE</td>
					<td tabindex="0">PRODUCT NAME</td>
					<td tabindex="0">DESCRIPTION</td>
				</tr>
			</thead>
			<tbody class="align-top">
				@if(count($WishProdRS) > 0)
				@foreach($WishProdRS as $wish_cate_key => $wish_cat_value)
				<tr>
					<td data-title="Select">
					<input type="checkbox" id="WishId_{{$wish_cat_value['wishlist_id']}}" name="ch[]" value="{{$wish_cat_value['wishlist_id']}}" class="form-check-input" aria-label="Hidden Label" />
					</td>
					<td data-th="IMAGE">
						<a href="{{$APP_URLS.$wish_cat_value['p_link']}}" tabindex="0" title="{{$wish_cat_value['product_name']}}" aria-label="{{$wish_cat_value['product_name']}}">
							<img src="{{$wish_cat_value['thumb_image']}}" alt="{{$wish_cat_value['product_name']}}" title="{{$wish_cat_value['product_name']}}" width="90" /></a>
					</td>
					<td data-th="PRODUCT NAME">
						<div class="pb5">
							<a href="{{$APP_URLS.$wish_cat_value['p_link']}}" tabindex="0" title="{{$wish_cat_value['product_name']}}" aria-label="{{$wish_cat_value['product_name']}}">{{$wish_cat_value['product_name']}}</a>
						</div>
						<div class="pb5">
							<strong>Item SKU :</strong> {{$wish_cat_value['sku']}}
						</div>
					</td>
					<td data-th="DESCRIPTION">{{$wish_cat_value['short_description']}}</td>
				</tr>
				@endforeach
				@else
				<tr class="text-center">
					<td colspan="5" class="errmsg tac" tabindex="0">No Record Found</td>
				</tr>
				@endif
			</tbody>
		</table>
	</form>
	<div class="myact-pagination mb-4">{!! $WishProdRS->onEachSide(0)->links('layouts.pagination') !!}</div>
	<div class="myact-btn">
		@if(count($WishProdRS) > 0)
			<button type="button" class="btn" id="btnDeleteWishProduct" title="Delete" aria-label="Delete">Delete</button>
		@endif
		<a href="{{config('const.SITE_URL').'/wish-category.html'}}" class="myact-back" tabindex="0" title="Back" aria-label="Back">
			<svg class="svg_arrow_right" aria-hidden="true" role="img" width="7" height="14"><use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use></svg>Back
		</a>
	</dv>
</div>
@endsection