@php
   $advertisementArr = (isset($advertisementCategoryArr)  && !empty($advertisementCategoryArr)) ? $advertisementCategoryArr[0] : '';
   $sectionName = (isset($ProductListingType) && !empty($ProductListingType)) ? $ProductListingType : 'ProductListPage';
   $pageType = 'Listing';
@endphp

@extends('layouts.app')
@section('content')

<input type="hidden" name="product_listing_type" id="product_listing_type" value="{{$ProductListingType}}" />
<input type="hidden" name="page_limit" id="page_limit" value="{{$Pagelimit}}" />
@if(isset($CategoryId) && !empty($CategoryId))
<input type="hidden" name="category_id" id="category_id" value="{{$CategoryId}}" />
@endif
<div class="container">
	<input type="hidden" name="product-type-flag" id="product-type-flag" value="{{ (isset($productTypeFlag) && !empty($productTypeFlag)) ? $productTypeFlag : 'ProductListPage' }}">
	<div class="breadcrumb">
		@if(!empty($Bredcrum)){!!$Bredcrum!!}@endif
	</div>
	<div class="list_hd"><h1 tabindex="0">@if(!empty($PageTitle)){{$PageTitle}}@endif</h1></div>
	<div class="toolbar">
		@if(count($Products) > 0)	
		<div class="toolbar-left">
			<a class="filter_mob_btn" href="#filter_mob" tabindex="0" title="Product Show/Hide Filter" aria-label="Product Show/Hide Filter"> 
				<svg class="filters-svg me-2" aria-hidden="true" role="img" width="25" height="25">
					<use href="#filters-svg" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#filters-svg"></use>
				</svg>
				<span class="vam filter-hide">Hide Filter</span>
				<span class="vam filter-show">Show Filter</span> 
			</a>
			<div class="toolbar-results hidden-xs-down total-prouct-count-js" tabindex="0">{{ $TotalProducts }} Results</div>
		</div>
		<div class="toolbar-right">
			<div class="form-floating hidden-xs-down">
				<select class="form-select" id="itemperpage" aria-label="Floating label select example">
				<option {{ $Pagelimit==15 ? 'selected' : '' }} value="15">15</option>
				<option {{ $Pagelimit==30 ? 'selected' : '' }} value="30">30</option>
					<option {{ $Pagelimit==45 ? 'selected' : '' }} value="45">45</option>
					<option {{ $Pagelimit==60 ? 'selected' : '' }} value="60">60</option>
					<option {{ $Pagelimit==75 ? 'selected' : '' }} value="75">75</option>
				</select>
				<label for="itemperpage">Item Per Page</label>
			</div>				
			<div class="form-floating">
				<select class="form-select" id="sortopt" tabindex="0" aria-label="Floating label select example">
					<option disabled selected>Sort By:</option>
					<option value="PLTH">Price Low to High</option>
					<option value="PHTL">Price High to Low</option>
					<option value="AZ">A-Z</option>
					<option value="ZA">Z-A</option>
					<option value="newest">Newest</option>
				</select>
			</div>
		</div>
		@endif
	</div>
	<div class="lst_con">
	@if(count($Products) > 0)	
		<div class="lst_left">
		 @include('product.listing_filter')	
		</div>
		<div class="lst_right">
			<div class="row row-no-gutters">
				<div class="col-12">
					<ul class="filter-current ft-list-js">&nbsp;</ul>
				  </div>              
				<div class="col-xs-12 pt-5">
					<ul class="live_view" id="product_listing" data-load='0'>
						@if(count($Products) > 0)
                            <x-productbox :prodData="$Products" :pageType="$pageType" :advertisementData="$advertisementArr" :sectionName="$sectionName"   />
						@endif
					</ul>
				</div>
				<div class="col-xs-12 pt-5" id="loadmore" style="@if(isset($NEW_PAGE_LIMIT) && $NEW_PAGE_LIMIT != "") @if($TotalProducts <= $NEW_PAGE_LIMIT) display:none @endif @else @if($TotalProducts < $Pagelimit) display:none @endif @endif">
					{{-- <a class="btn btn-block ldmore list-more" data-page='1' title="Load More ...">Load More ...</a> --}}
					<button tabindex="0" type="button" class="btn btn-block ldmore list-more" data-page='@if(isset($page) && $page != ""){{$page}}@else 1 @endif' title="Load More" aria-label="Load More">Load More ...</button>
				</div>
				<div class="col-xs-12 pt-5"></div>  
				<div class="errmsg  pb-5 text-center" tabindex="0" id="noprod" style="@if($TotalProducts > 0) display:none @endif">Sorry, there are not any Items available right now. Please visit back soon.</div>
				
				</div>
				@if(!empty($PageDescription))
					<div class="col-xs-12 pt-4">
						<div class="lst_dis">
							<p tabindex="0">{{$PageDescription}}</p>
						</div> 
					</div>
				@endif
		</div><div class="clearfix"></div>
	</div>
	@else
		@if($TotalProducts <= 0)
			<div class="errmsg  pb-5 text-center" tabindex="0" id="noprod">
				Sorry, there are not any Items available right now. Please visit back soon.
				@if(isset($flagWithouFilterOnLoad) && !empty($flagWithouFilterOnLoad))
				<div class="pb-5" ><a href="{{url()->current()}}" title="Reset All" aria-label="Reset All" tabindex="0" class="linksbb">Reset All</a></div>
				@endif
			</div>
			@if(isset($Category) && !empty($Category))  
			<section class="space70">
				<div class="ctbox-box">
					<x-categorybox :catData="$Category" />
				</div>
			</section>
			<section class="space70">
				<div class="separator-border">&nbsp;</div>
			</section>
			@endif
		@endif
	@endif
</div>
@endsection