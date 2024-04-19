@extends('layouts.app')
@section('content')
<div class="container">
	@include('myaccount.breadcrumbs')
	<div class="myact mt-lg-0 mt-4 pb-sm-5 pb-0 mb-5">
		<div class="mb-3 hidden-md-up">
			<h2 tabindex="0">Order History</h2>
		</div>
		@include('myaccount.myaccountmenu')
		<div class="row order-dtl-main mb-3">
			<div class="col-md-7 col-sm-6 order-dtl-inner mb-3 mb-sm-0 hidden-sm-down">
				<h2 tabindex="0">Wishlist</h2>
			</div>
			@if (Session::has('success'))
			<div class="col-md-12 text-center pt-3">
				<x-message :attr="[
									'classname' => 'frmsuccess', 
									'message' => Session::get('success')]" />
			</div>
			@endif
			@if (Session::has('error'))
			<div class="col-md-12 text-center pt-3">
				<x-message :attr="[
									'classname' => 'frmerror frmerror_shw', 
									'message' => Session::get('error')]" />
			</div>
			@endif
		</div>
		<div class="track-order-main">
			<form action="" name="frmwish" id="frmwish" method="delete" action="">
				<input type="hidden" name="action" value="DeleteCat" />

				@csrf
				<table class="res_table wishlist_table mb-4 tac_md" width="100%">
					<thead>
						<tr>
							@if(count($WishCatRS) > 0)
							<th class="hide-td">
								<label class="checkbox-label">
									<div class="chebox">
										<input type="checkbox" name="chk" value="1" id="checkAll">
										<span class="checkmark"></span>
									</div>
								</label>
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
								<label class="checkbox-label" tabindex="0">
									<div class="chebox">
										<!-- <input type="checkbox"> -->
										<input type="checkbox" id="WishId_{{$wish_cat_value->wishlist_category_id}}" name="ch[]" value="{{$wish_cat_value->wishlist_category_id}}" class="noborder">
										<span class="checkmark"></span>
									</div>
								</label>
							</td>
							<td data-title="Category Name" tabindex="0">{{$wish_cat_value->name}}</td>
							<td data-title="Description" tabindex="0">{{$wish_cat_value->description}}</td>
							<td data-title="Edit"><a href="{{ config('global.SITE_URL') }}wish-category/{{$wish_cat_value->wishlist_category_id}}.html" tabindex="0" title="Edit" aria-label="Edit">Edit</a></td>
							<td data-title="View Products"><a href="{{ config('global.SITE_URL') }}wish-product/{{$wish_cat_value->wishlist_category_id}}.html" tabindex="0" title="View" aria-label="View">View</a></td>
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
            <div class="tac jcb aic"> {!! $WishCatRS->onEachSide(0)->links('layouts.pagination') !!}</div>
            <div class="order-row pt-3">
				@if(count($WishCatRS) > 0)
					<a href="javascript:void(0);" class="btn btn-primary order-sm-2 btn-xs-block mb-2 mb-sm-0" id="btnDeleteWishCategory" tabindex="0" title="Delete" aria-label="Delete">Delete</a>
				@endif
				<a href="{{ route('myaccount') }}" class="text_c2 order-sm-1" tabindex="0" title="Back" aria-label="Back">
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