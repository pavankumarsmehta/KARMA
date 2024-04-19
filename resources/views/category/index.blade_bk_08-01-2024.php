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
	
	@if(isset($popularBrand) && !empty($popularBrand))  
	<section class="space70">
		<div class="cm-hd">
			<div class="cm-hd-left"><div class="h5" tabindex="0">Popular Brands</div></div>
			<div class="cm-hd-right"><a href="{{config('const.SITE_URL')}}/brand-perfumes.html" title="View all Popular Brands" class="linksbb" tabindex="0"><strong>View all <span class="hidden-xs-down">Popular Brands</span></strong></a></div>
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
				<div class="slick-scroll slick-space-20">
				@endslot
				@slot('product_content')
				<x-productbox :prodData="$Products" />
				@endslot
			    @slot('product_end_section')
			    </div>
			    @endslot
				@slot('section_end')
				<div class="slick-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
							<span class="slick-label"></span>
					</div>
				@endslot
			</x-slider>
		</div>
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
	@if(isset($categoryBeauty) && !empty($categoryBeauty))  
		<section class="space70">
			<div class="beautytips">
				<x-beautytip :beautytipsData="$categoryBeauty" >
				</x-promotion>	
				
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
    