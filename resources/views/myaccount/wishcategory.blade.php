@extends('layouts.app')
@section('content')
<div class="container myact">
	@include('myaccount.breadcrumbs')
	<h1 class="hidden-md-up h2" tabindex="0">Wishlist</h1>
	@include('myaccount.myaccountmenu')
	<h1 class="pb-3 hidden-sm-down h2" tabindex="0">Wishlist</h1>
	@if (Session::has('success'))
	<div class="text-center pt-3">
		<x-message :attr="[
							'classname' => 'frmsuccess', 
							'message' => Session::get('success')]" />
	</div>
	@endif
	@if (Session::has('error'))
	<div class="text-center pt-3">
		<x-message :attr="[
							'classname' => 'frmerror frmerror_shw', 
							'message' => Session::get('error')]" />
	</div>
	@endif
	<form action="" name="frmwish" id="frmwish" method="delete" action="">
		<input type="hidden" name="action" value="DeleteCat" />
		@csrf
		<table class="res_table wishlist_table mb-4 tac_md" width="100%">
			<thead>
				<tr>
					@if(count($WishCatRS) > 0)
					<th class="hide-td">
					<input type="checkbox" name="chk" value="1" id="checkAll" aria-label="Hidden Label" class="form-check-input" />
					</th>
					@endif
					<th>Category Name</th>
					<th>Description</th>
					<th>Edit</th>
					<th>View Products</th>
				</tr>
			</thead>
			<tbody class="align-top">
				@if(count($WishCatRS) > 0)
				@foreach($WishCatRS as $wish_cate_key => $wish_cat_value)
				<tr>
					<td data-title="Select">
						<input type="checkbox" id="WishId_{{$wish_cat_value->wishlist_category_id}}" name="ch[]" value="{{$wish_cat_value->wishlist_category_id}}" class="form-check-input" aria-label="Hidden Label" />
					</td>
					<td data-th="Category Name">{{$wish_cat_value->name}}</td>
					<td data-th="Description">{{$wish_cat_value->description}}</td>
					<td data-th="Edit"><a href="{{ config('global.SITE_URL') }}wish-category/{{$wish_cat_value->wishlist_category_id}}.html" tabindex="0" title="Edit" aria-label="Edit">Edit</a></td>
					<td data-th="View Products"><a href="{{ config('global.SITE_URL') }}wish-product/{{$wish_cat_value->wishlist_category_id}}.html" tabindex="0" title="View" aria-label="View">View</a></td>
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
	<div class="myact-pagination"> {!! $WishCatRS->onEachSide(0)->links('layouts.pagination') !!}</div>

	<div class="myact-btn">
		@if(count($WishCatRS) > 0)
			<button type="button" class="btn" id="btnDeleteWishCategory" title="Delete" aria-label="Delete">Delete</button>
		@endif
		<a href="{{ route('myaccount') }}" class="myact-back" tabindex="0" title="Back" aria-label="Back">
			<svg class="svg_arrow_right" aria-hidden="true" role="img" width="7" height="14"><use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use></svg>Back
		</a>
	</dv>
</div>
@endsection