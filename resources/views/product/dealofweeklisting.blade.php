@extends('layouts.app')
@section('content')
<div class="container">
	<input type="hidden" name="product-type-flag" id="product-type-flag" value="{{ (isset($productTypeFlag) && !empty($productTypeFlag)) ? $productTypeFlag : 'ProductListPage' }}">
	<div class="breadcrumb">
		<a href="{{ config('const.SITE_URL') }}" tabindex="0" title="{{ config('const.SITE_URL') }}" aria-label="{{ config('const.SITE_URL') }}">
			Home
			<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
				<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
			</svg>
		</a>
		<span class="active" tabindex="0">Sale</span>
	</div>
	<div class="list_hd"><h2 tabindex="0">{{$PageTitle}}</h2></div>
	<div class="toolbar">
		<a class="filter_mob_btn" href="#filter_mob" tabindex="0" title="Product Show/Hide Filter" aria-label="Product Show/Hide Filter"> 
			<svg class="filters-svg me-2" aria-hidden="true" role="img" width="25" height="25">
				<use href="#filters-svg" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#filters-svg"></use>
			</svg>
			<span class="vam filter-hide">Hide Filter</span>
			<span class="vam filter-show">Show Filter</span> 
		</a>
		<div class="toolbar-results hidden-xs-down">{{ $TotalProducts }} Results</div>
		
		<div class="form-floating">
			<label class="hidden-xs-down" for="itemperpage" tabindex="0">Sort By:</label>
			<select class="form-select" id="sortopt" aria-label="Floating label select example">
				<option value="PLTH">Price Low to High</option>
				<option value="PHTL">Price High to Low</option>
				<option value="AZ">A-Z</option>
				<option value="ZA">Z-A</option>
				<option value="newest">Newest</option>
			</select>
		</div>
	</div>
	<div class="lst_con">
		<div class="lst_left">
		 @include('product.listing_filter')	
		</div>
		<div class="lst_right">
			<div class="row row-no-gutters">
				<div class="col-12">
				
				</div>             
				<div class="col-xs-12">
					<ul class="live_view" id="dealofweek" data-load='0'>
						@if(count($Products) > 0)
                            <x-productbox :prodData="$Products" />
						@endif
					</ul>
				</div>
				
				<div class="col-xs-12 pt-4" id="loadmore" style="@if($TotalProducts < 2) display:none @endif">
					<a class="btn btn-block btn-loadmore list-more" data-page='1'>Load More ...</a>
				</div>
				<div class="errmsg  pb-5 text-center" id="noprod" style="@if($TotalProducts > 0) display:none @endif">No Record Found</div>
				<div class="col-xs-12 pt-4">
					 <div class="lst_dis">
						<p>Lorem ipsum dolor sit amet consectetur. Maecenas dictum bibendum massa feugiat elementum pharetra ut ultrices ultrices. Varius est dictumst ultricies ipsum sollicitudin velit. Purus elit vitae sapien natoque nunc et in posuere sit. Lorem ipsum dolor sit amet consectetur. Maecenas dictum bibendum massa feugiat elementum pharetra ut ultrices ultrices. Varius est dictumst ultricies ipsum sollicitudin velit. </p>
					</div> 
				</div>
				<div class="col-xs-12">
					<div class="lst_toolbar bottom_lst_toolbar">
					  <div class="form-floating hidden-sm-down">
						<select class="form-select" id="itemperpage" aria-label="Floating label select example">
						  <option>12</option>
						  <option value="1">24</option>
						  <option value="2">36</option>
						</select>
						<label for="itemperpage">Item Per Page</label>
					  </div>
				  </div>
			</div>
		</div><div class="clearfix"></div>
	</div>
</div>
@endsection