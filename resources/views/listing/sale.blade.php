@extends('layouts.app')
@section('content')
<div class="container">
	<div class="breadcrumb">
		<a href="{{ config('const.SITE_URL') }}" tabindex="0" title="{{ config('const.SITE_URL') }}" aria-label="{{ config('const.SITE_URL') }}">
			Home
			<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
				<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
			</svg>
		</a>
		<span class="active" tabindex="0">Sale</span>
	</div>
	<div class="list_hd"><h2 tabindex="0">Sale</h2></div>
	<div class="toolbar">
		<a class="filter_mob_btn" href="#filter_mob" tabindex="0" title="Product Show/Hide Filter" aria-label="Product Show/Hide Filter"> 
			<svg class="filters-svg me-2" aria-hidden="true" role="img" width="25" height="25">
				<use href="#filters-svg" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#filters-svg"></use>
			</svg>
			<span class="vam filter-hide">Hide Filter</span>
			<span class="vam filter-show">Show Filter</span> 
		</a>
		<div class="toolbar-results hidden-xs-down" tabindex="0">{{$SaleProductsCount}} Results</div>
		{{-- <div class="form-floating">
			<label class="hidden-xs-down" for="itemperpage" tabindex="0">Item on Pages:</label>
			<select class="form-select" id="selsort" aria-label="Floating label select example">
				<option value="twofour">24</option>
				<option value="threesix">36</option>
				<option value="foureight">48</option>
				<option value="seventwo">72</option>
			</select>
			<input type="hidden" id="sortopt" value="" />
		</div> --}}
		<div class="form-floating">
			<label class="hidden-xs-down" for="itemperpage" tabindex="0">Sort By:</label>
			<select class="form-select" id="itemsortby" aria-label="Floating label select example">
				<option>Sort By</option>                                
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
			@include('listing.product_sale_filter')
		</div>
		<div class="lst_right">
			<div class="row row-no-gutters">
				<div class="col-12">
				{{--
					<ul class="filter-current">
						<li>Color: 
							<a href="#" tabindex="0" title="Black" aria-label="Black">Black 
								<svg class="svg_close" width="16px" height="16px" aria-hidden="true" role="img">
									<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
								</svg>
							</a>
							<a href="#" tabindex="0" title="Navy" aria-label="Navy">Navy 
								<svg class="svg_close" width="16px" height="16px" aria-hidden="true" role="img">
									<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
								</svg>
							</a>
						</li>
						<li>Shape: 
							<a href="#" tabindex="0" title="Accent Pieces, Poufs" aria-label="Accent Pieces, Poufs">Accent Pieces, Poufs 
								<svg class="svg_close" width="16px" height="16px" aria-hidden="true" role="img">
									<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
								</svg>
							</a>
						</li>
						<li><a href="#" class="Clear All" tabindex="0" title="Clear All" aria-label="Clear All">Clear All</a></li>
					</ul>
				--}}	
				</div>             
				<div class="col-xs-12">
					<ul class="live_view">
						@if(count($SaleProducts) > 0)
							@for($i=0;$i<count($SaleProducts);$i++)
								<li>
									<div class="product clearfix">
										<div class="product_thumb">
											<a href="{{$SaleProducts[$i]['product_url']}}" tabindex="0" title="{{$SaleProducts[$i]['product_name']}}" aria-label="{{$SaleProducts[$i]['product_name']}}">
												<picture><img src="{{$SaleProducts[$i]['product_medium_image']}}" alt="{{$SaleProducts[$i]['product_name']}}" title="{{$SaleProducts[$i]['product_name']}}" width="365" height="365" loading="lazy" /></picture>
											</a>
											<a href="javascript:void(0)" data-productid="{{$SaleProducts[$i]['product_id']}}" tabindex="0" title="Add to wishlist" aria-label="Add to wishlist" class="displaypopupboxwishlist wishlist active-">
												<svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg" loading="lazy">
													<path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokeWidth="1" strokeLinejoin="round" />
												</svg> 
											</a>
										</div>    
										<div class="product_sku" tabindex="0">{{$SaleProducts[$i]['sku']}}</div>
										<div class="product_name">
											<a href="{{$SaleProducts[$i]['product_url']}}" tabindex="0" title="{{$SaleProducts[$i]['product_name']}}" aria-label="{{$SaleProducts[$i]['product_name']}}">{{$SaleProducts[$i]['product_name']}}</a>
										</div>
										<div class="product_price" tabindex="0">
											<span class="old-price">{{$SaleProducts[$i]['our_price_disp']}}</span>
											<span class="special-price">{{$SaleProducts[$i]['sale_price_disp']}}</span>
										 </div>
										<div class="product_review" tabindex="0"><img src="images/star.png" alt="" width="72" height="13" loading="lazy"/>(110)</div>
									</div>
								</li>
								@if($i == 3 || $i == 9)
									<li>
									<div class="product-banner">
										<a href="#" tabindex="0" title="Product banner" aria-label="Product banner">
											<picture>
												<source media="(min-width:768px)" srcset="images/365x500.webp">
												<img src="images/768x800.webp" alt="" title="" width="365" height="500" loading="lazy" /> 
											</picture>
										</a>
									</div>
									</li> 
								@endif
							@endfor	
						@endif
					</ul>
				</div>
				<div class="col-xs-12 pt-4">
					<a class="btn btn-block btn-loadmore" data-page="" tabindex="0" title="Load More ..." aria-label="Load More">Load More ...</a>
				</div>
				<div class="col-xs-12 pt-4">
					<div class="lst_dis" tabindex="0">
						<p>Lorem ipsum dolor sit amet consectetur. Maecenas dictum bibendum massa feugiat elementum pharetra ut ultrices ultrices. Varius est dictumst ultricies ipsum sollicitudin velit. Purus elit vitae sapien natoque nunc et in posuere sit. Lorem ipsum dolor sit amet consectetur. Maecenas dictum bibendum massa feugiat elementum pharetra ut ultrices ultrices. Varius est dictumst ultricies ipsum sollicitudin velit. </p>
					</div>
				</div>
			</div>
		</div><div class="clearfix"></div>
	</div>
</div>
@endsection