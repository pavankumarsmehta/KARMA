@extends('layouts.app')
@section('content')
@if(isset($categoryBanner) && !empty($categoryBanner))  
	@if($categoryBanner[0]['banner_position']!='LEFT')  
	<section class="catbanner  catbanner-thumb-{{strtolower($categoryBanner[0]['banner_position'])}}">
	@else
	<section class="catbanner">	
	@endif
	<div class="container">
	<div class="catbanner-inner">
		@if($categoryBanner[0]['banner_position']!='FULL')  
		<div class="catbanner-left">
			<div class="catbanner-dis">
				<h1 tabindex="0">{{$categoryBanner[0]['category_name']}}</h1>
				@if(isset($categoryBanner[0]['category_description']) && !empty($categoryBanner[0]['category_description']))  
				{{--  <p class="desc" tabindex="0">Lorem ipsum dolor sit amet consectetur. Et neque viverra sed sed risus ut at faucibus. Blandit in nulla augue phasellus nec.</p>  --}}
				<p class="desc" tabindex="0">{!! $categoryBanner[0]['category_description'] !!}</p>
				@endif
				@if(isset($categoryBanner[0]['banner_image_link']) && !empty($categoryBanner[0]['banner_image_link']))  
				<div><button type="button" onClick="window.location.href='{{$categoryBanner[0]['banner_image_link']}}'" class="btn btn-white btn-border" title="SHOP All" aria-label="SHOP All">SHOP All</button></div>
				@endif
			</div>
		</div>
		@endif
		<div class="catbanner-right">
			<picture>
				<source class="owl-lazy" media="(min-width:768px)" data-srcset="{{$categoryBanner[0]['banner_image']}}" srcset="{{$categoryBanner[0]['banner_image']}}" alt="Make Up">
				<img class="owl-lazy" data-srcset="{{$categoryBanner[0]['banner_image']}}" alt="{{$categoryBanner[0]['category_name']}}" width="1740" height="675" src="{{$categoryBanner[0]['banner_image']}}" style="opacity: 1;" title="{{$categoryBanner[0]['category_name']}}" loading="lazy">
			</picture>
		</div>
		</div>
	</div>
	</section>
	@endif
<div class="container">
	
	
	@if(isset($popularBrand) && !empty($popularBrand))  
	<section class="space70">
		<div class="cm-hd">
			<div class="cm-hd-left"><div class="h5" tabindex="0">Popular Brands</div></div>
			<div class="cm-hd-right"><a href="{{config('const.SITE_URL')}}/brand-all.html" title="View all Popular Brands" class="linksbb" tabindex="0"><strong>View all <span class="hidden-xs-down">Popular Brands</span></strong></a></div>
		</div>
		<div class="slick-brand slick-space-20 slickarrow">
			<x-popularbrandbox :popularBrandData="$popularBrand" />
		</div>
	</section>
	<section class="space70">
		<div class="separator-border">&nbsp;</div>
	</section>
	@endif

	@if(isset($Products) && !empty($Products))  
	<section class="space70">
		<div class="gallery-slider">
			<x-slider :sliderData="$Products" >
				@slot('section_start')
				<div class="cm-hd">
					<div class="cm-hd-left"><div class="h5" tabindex="0">Best Sellers</div><span class="cm-hd-items">{{count($Products)}} items</span></div>
					<div class="cm-hd-right"><a href="{{config('const.SITE_URL')}}/{{$Category_detail->url_name}}/best-seller" title="View all Best Sellers" class="linksbb" tabindex="0"><strong>View all <span class="hidden-xs-down">Best Sellers</span></strong></a></div>
				</div>
				@endslot
				@slot('product_start_section')
				<div class="slick-seller slick-space-20 slickarrow-ovr bs-slick">
				@endslot
				@slot('product_content')
				<x-productbox :prodData="$Products" />
				@endslot
			    @slot('product_end_section')
			    </div>
			    @endslot
				@slot('section_end')
				<div class="slick-progress-bestseller slick-progress-other" role="progressbar" aria-valuemin="0" aria-valuemax="100">
							<span class="slick-label"></span>
					</div>
				@endslot
			</x-slider>
		</div>
	</section>
	@endif	
	@if(isset($CatNewArrivalProducts) && !empty($CatNewArrivalProducts))  
	<section class="space70" aria-label="New Arrivals">
			<x-slider :sliderData="$CatNewArrivalProducts" >
				@slot('section_start')
					<div class="gallery-slider clearfix">
						<div class="cm-hd">
							<div class="cm-hd-left">
							<div class="h5" tabindex="0">New Arrivals</div>
							<span class="cm-hd-items" tabindex="0">{{count($CatNewArrivalProducts)}} items</span>
							</div>
							<div class="cm-hd-right">
							<a href="{{config('const.SITE_URL')}}/{{$Category_detail->url_name}}/new-arrival" class="linksbb" title="View all New Arrivals" aria-label="View all New Arrivals" tabindex="0"><strong>View all <span class="hidden-xs-down">New Arrivals</span></strong></a>
						</div>
						</div>
				@endslot
				@slot('product_start_section')
						<div class="slick-seller-newarrival slick-space-20">
				@endslot
						@slot('product_content')
							<x-productbox :prodData="$CatNewArrivalProducts" />
						@endslot
				@slot('product_end_section')
						</div>
						<div class="slick-progress-newarrival slick-progress-other" role="progressbar" aria-valuemin="0" aria-valuemax="100">
							<span class="slick-label"></span>
						</div>
				@endslot
				@slot('section_end')
					</div>
				@endslot
			</x-slider>
		</section>
	@endif

	@if(isset($CatFeaturedProducts) && !empty($CatFeaturedProducts))  
	<section class="space70" aria-label="Featured Items">
			<x-slider :sliderData="$CatFeaturedProducts" >
				@slot('section_start')
					<div class="gallery-slider clearfix">
						<div class="cm-hd">
							<div class="cm-hd-left">
							<div class="h5" tabindex="0">Featured Items</div>
							<span class="cm-hd-items" tabindex="0">{{count($CatFeaturedProducts)}} items</span>
							</div>
							<div class="cm-hd-right">
							<a href="{{config('const.SITE_URL')}}/{{$Category_detail->url_name}}/featured-items" class="linksbb" title="View all Featured Items" aria-label="View all Featured Items" tabindex="0"><strong>View all <span class="hidden-xs-down">Featured Items</span></strong></a>
						</div>
						</div>
				@endslot
				@slot('product_start_section')
						<div class="slick-scroll slick-space-20">
				@endslot
						@slot('product_content')
							<x-productbox :prodData="$CatFeaturedProducts" />
						@endslot
				@slot('product_end_section')
						</div>
						<div class="slick-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
							<span class="slick-label"></span>
						</div>
				@endslot
				@slot('section_end')
					</div>
				@endslot
			</x-slider>
		</section>
	@endif

	@if(isset($categoryPromotion) && !empty($categoryPromotion))  
	<section class="space70">
		<div class="promotions-banner">
			<x-promotion :promotionData="$categoryPromotion" >
			</x-promotion>	
		</div>
	</section>
	@endif
	{{-- @if(isset($categoryBeauty) && !empty($categoryBeauty))  
		<section class="space70">
			<div class="beautytips">
				<x-beautytip :beautytipsData="$categoryBeauty" >
				</x-promotion>	
				
			</div> 
		</section>        
	@endif --}}

	@if(isset($HomePageBanner_Bottom) && count($HomePageBanner_Bottom) > 0)
		<section class="space70" aria-label="Beauty Tips">
			<div class="beautytips clearfix">
				<div class="cm-hd">
					<div class="cm-hd-left">
						<div class="h5" tabindex="-1">Beauty Tips</div>
					</div>
					{{-- <div class="cm-hd-right"><a href="{{config('const.SITE_URL')}}/#read-more" onClick="return false;" class="linksbb" title="Read More" rel="noindex nofollow" aria-label="Read More" tabindex="0"><strong>Read More</strong></a></div> --}}
				</div>
				<div class="slick-beauty slick-space-10 slickarrow-ovr">
					@for($i=0;$i<count($HomePageBanner_Bottom);$i++)
						@if($HomePageBanner_Bottom[$i]['banner_position'] == 'HOME_BOTTOM')
							@if($HomePageBanner_Bottom[$i]['thumb_image'] != "")
							@php 
							$link = (isset($HomePageBanner_Bottom[$i]['more_link']) && !empty($HomePageBanner_Bottom[$i]['more_link'])) ? $HomePageBanner_Bottom[$i]['more_link'] : config('const.SITE_URL').'#read-more';
							$on_click = (isset($HomePageBanner_Bottom[$i]['more_link']) && !empty($HomePageBanner_Bottom[$i]['more_link'])) ? '' : 'onClick="return false;"';
							$rel = (isset($HomePageBanner_Bottom[$i]['more_link']) && !empty($HomePageBanner_Bottom[$i]['more_link'])) ? '' : 'rel="noindex nofollow"';
							
							@endphp
								<div class="beautybanner">
									<picture><img src="{{$HomePageBanner_Bottom[$i]['thumb_image']}}" alt="{{$HomePageBanner_Bottom[$i]['image_alt_text']}}" width="500" height="535" class="fwidth" loading="lazy" /></picture>
									<div class="over">
										<div class="date" tabindex="-1">{{$HomePageBanner_Bottom[$i]['added_date']}}</div>
										<div class="title" tabindex="-1">{{$HomePageBanner_Bottom[$i]['title']}}</div>
										<a aria-label="Read More" {!! $rel !!} title="{{$HomePageBanner_Bottom[$i]['image_alt_text']}}" {!! $on_click !!} href="{{$link}}" class="linksbb" tabindex="0">
										<strong>Read More</strong></a>
									</div>
								</div>
							@endif
						@endif
					@endfor	
				</div>
			</div>
		</section>
		@endif
@if(isset($categoryBanner) && !empty($categoryBanner))  
	@if($categoryBanner[0]['banner_position']=='FULL')  
	<section class="space70 makeup-text">
		<div class="pb-5 tac">
			<h3>{{$categoryBanner[0]['category_name']}}</h3>
		</div>
		{{-- <p class="tac">Lorem ipsum dolor sit amet consectetur. Maecenas dictum bibendum massa feugiat elementum pharetra ut ultrices ultrices. Varius est dictumst ultricies ipsum sollicitudin velit. Purus elit vitae sapien natoque nunc et in posuere sit. Lorem ipsum dolor sit amet consectetur. Maecenas dictum bibendum massa feugiat elementum pharetra ut ultrices ultrices. Varius est dictumst ultricies ipsum sollicitudin velit.</p> --}}
		@if(isset($categoryBanner[0]['category_description']) && !empty($categoryBanner[0]['category_description']))  
		<p class="tac" tabindex="0">{!! $categoryBanner[0]['category_description'] !!}</p>
		@endif
	</section>
	@endif
@endif	
	

</div>
@endsection
    