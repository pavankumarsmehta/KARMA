@extends('layouts.app')
@section('content')
<style>
	.no-loaded {
    opacity: 0;
    visibility: hidden;
    transition: opacity 1s ease;
    -webkit-transition: opacity 1s ease;
}

.slick-slider.finally-loaded {
    visibility: visible;
    opacity: 1;
    transition: opacity 1s ease;
    -webkit-transition: opacity 1s ease;
}
</style>	
<div class="container-pd">
	<div class="container-1670">
		<?php //dd($HomePageBanner_Main);?>
		@if(isset($HomePageBanner_Main) && count($HomePageBanner_Main) > 0)
		<section class="home_slider clearfix" aria-label="Hba slid">
			<div id="hero-slider">
				@foreach ($HomePageBanner_Main as $banner)
					@if($banner['banner_position'] == 'HOME_MAIN')
						@if($banner['thumb_image'] != "" && $banner['thumb_image_mobile'] != "")
						
						@if(isset($banner['more_link']) && !empty($banner['more_link']))
							<a title="{{$banner['image_alt_text']}}" href="{{$banner['more_link']}}">
							@endif
								<picture>
									<source media="(min-width: 768px)" srcset="{{$banner['thumb_image']}}">
									<img src="{{$banner['thumb_image_mobile']}}" alt="{{$banner['image_alt_text']}}"  title="{{$banner['image_alt_text']}}" class="fwidth" width="1530" height="505" loading="lazy" />
								</picture>
								@if(isset($banner['more_link']) && !empty($banner['more_link']))
							</a>
							@endif	
						@endif
					@endif
				@endforeach		
			</div>
		</section>
		@endif
		<section class="sales clearfix" aria-label="sales">
			<ul>
				<li tabindex="0">
					<svg class="svg_shipping" aria-hidden="true" role="img" width="25" height="26" loading="lazy">
						<use href="#svg_shipping" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_shipping"></use>
					</svg>
					Free ground shipping over $75
				</li>
				<li tabindex="0">
					<svg class="svg_delivery" aria-hidden="true" role="img" width="25" height="25" loading="lazy">
						<use href="#svg_delivery" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_delivery"></use>
					</svg>
					Free priority shipping over $150
				</li>
				<li tabindex="0">
					<svg class="svg_returning" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
						<use href="#svg_returning" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_returning"></use>
					</svg>
					Easy 30 days returns
				</li>
				<li tabindex="0">
					<svg class="svg_secure" aria-hidden="true" role="img" width="25" height="26" loading="lazy">
						<use href="#svg_secure" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_secure"></use>
					</svg>
					100% Authentic, 100% Secure
				</li>
			</ul>
		</section>
		{{-- <section class="beautyquizzes fxheight clearfix" aria-label="Beauty Quizzes">
			<h2 tabindex="0">Beauty Quizzes</h2>
			<div class="loop">
				<div class="bqitem">
					<svg class="svg_bqperfume" aria-hidden="true" role="img" width="56" height="56" loading="lazy">
						<use href="#svg_bqperfume" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_bqperfume"></use>
					</svg>
					<div class="text" tabindex="0">Perfume</div>
				</div>
				<div class="bqitem">
					<svg class="svg_foundationquiz" aria-hidden="true" role="img" width="56" height="56" loading="lazy">
						<use href="#svg_foundationquiz" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_foundationquiz"></use>
					</svg>
					<div class="text" tabindex="0">Foundation Quiz</div>
				</div>
				<div class="bqitem">
					<svg class="svg_bqskincarequiz" aria-hidden="true" role="img" width="56" height="56" loading="lazy">
						<use href="#svg_bqskincarequiz" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_bqskincarequiz"></use>
					</svg>
					<div class="text" tabindex="0">Skincare Quiz</div>
				</div>
				<div class="bqitem">
					<svg class="svg_haircarequiz" aria-hidden="true" role="img" width="56" height="56" loading="lazy">
						<use href="#svg_haircarequiz" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_haircarequiz"></use>
					</svg>
					<div class="text" tabindex="0">Haircare Quiz</div>
				</div>
				<div class="bqitem">
					<svg class="svg_lashquiz" aria-hidden="true" role="img" width="56" height="56" loading="lazy">
						<use href="#svg_lashquiz" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_lashquiz"></use>
					</svg>
					<div class="text" tabindex="0">Lash Quiz</div>
				</div>
			</div>  
			<button type="button" class="btn btn-border" title="Start Now" aria-label="Start Now" tabindex="0">Start Now</button>   
		</section> --}}
		<section class="space70" aria-label="Deal Of The Week">
			<x-deal_of_week :dealOfWeekData="$dealOfWeekProduct" >
              @slot('header')
	            <h3 tabindex="0">Deal Of The Week</h3>
				<input type="hidden" id="deal_start_date" value="{{ getDateTimeByTimezone('d-m-Y H:i:s') }}"/>
				<input type="hidden" id="deal_end_date" value="{{getDateTimeByTimezone('d-m-Y H:i:s',date('Y-m-d 23:59:00'))}}"/>
		        <h4 class="deal-timecounter" tabindex="0" id="counter_1"></h4>
              @endslot
	          @slot('footer')
	            <div><a href="{{config('const.SITE_URL')}}/promotions/dealofweek.html"  class="linksbb" title="View all Deals" aria-label="View all Deals"><strong>View all Deals</strong></a></div>
	          @endslot
         </x-deal_of_week>
		</section>
	</div>
	<div class="container">
		<section class="space70" aria-label="Seasonal Specials">
		<x-slider :sliderData="$seasonalSpecials" >
				@slot('section_start')
					<div class="gallery-slider clearfix">
						<div class="cm-hd">
							<div class="cm-hd-left">
							<div class="h5" tabindex="0">Seasonal Specials</div>
							<span class="cm-hd-items" tabindex="0">{{count($seasonalSpecials)}} items</span>
							</div>
							<div class="cm-hd-right">
							<div class="cm-hd-right"><a href="{{config('const.SITE_URL')}}/seasonal-specials.html" class="linksbb" title="View all Seasonal Specials" tabindex="0"><strong>View all <span class="hidden-xs-down">Seasonal Specials<span></strong></a></div>
						</div>
						</div>
				@endslot
				@slot('product_start_section')
						<div class="slick-scroll slick-space-20">
				@endslot
						@slot('product_content')
							<x-productbox :prodData="$seasonalSpecials" />
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
		<section class="space70" aria-label="category products">
			<div class="ctbox-box clearfix">
				 <x-categorybox :catData="$Category"  /> 
			</div>
		</section>
		@if(isset($HomePageBanner_Middle) && count($HomePageBanner_Middle) > 0)
		<section class="space70" aria-label="Promotion Banner">
			<a href="{{$HomePageBanner_Middle[0]['more_link']}}" title="{{$HomePageBanner_Middle[0]['title']}}" class="promotions-banner" tabindex="0">
				<picture>
					<source media="(min-width:768px)" srcset="{{$HomePageBanner_Middle[0]['thumb_image']}}">
					<img src="{{$HomePageBanner_Middle[0]['thumb_image_mobile']}}" alt="{{$HomePageBanner_Middle[0]['image_alt_text']}}" title="{{$HomePageBanner_Middle[0]['title']}}" aria-label="{{$HomePageBanner_Middle[0]['title']}}" class="fwidth" width="1520" height="175" loading="lazy" /> 
				</picture>
			</a>
			
			{{--
			Do not remove this code. If client need Text based then open the comment.
			<div class="promotions-banner clearfix">
				<a href="{{$HomePageBanner_Middle[0]['more_link']}}" class="linksbb" title="{{$HomePageBanner_Middle[0]['banner_text']}}" aria-label="{{$HomePageBanner_Middle[0]['banner_text']}}">
				<picture>
					<source media="(min-width:768px)" srcset="{{$HomePageBanner_Middle[0]['thumb_image']}}">
					<img src="{{$HomePageBanner_Middle[0]['thumb_image']}}" alt="{{$HomePageBanner_Middle[0]['banner_text']}}" aria-label="{{$HomePageBanner_Middle[0]['banner_text']}}" title="{{$HomePageBanner_Middle[0]['banner_text']}}" width="1520" height="180" loading="lazy" /> 
				</picture>
				</a>
				<div class="over">
					<div class="big-hd" tabindex="0">{{$HomePageBanner_Middle[0]['title']}}</div>
					<div>
						<div class="small-hd">{{$HomePageBanner_Middle[0]['banner_text']}}</div>
						<a href="{{$HomePageBanner_Middle[0]['more_link']}}" class="linksbb" title="Shop Now" aria-label="Shop Now" tabindex="0">Shop Now</a>
					</div>
				</div>
			</div>
			Do not remove this code. If client need Text based then open the comment.
			--}}
		</section>
		@endif
		
		@if (isset($popularBrand) and (count($popularBrand) >0))
		<section class="space70" aria-label="Explore popular brands for you">
		
			<x-slider :sliderData="$popularBrand" >
				@slot('section_start')
				<div class="popularbrands clearfix">
					<div class="cm-hd">
						<div class="cm-hd-left">
							<div class="h5" tabindex="0">Explore popular brands for you</div>
							<a href="{{config('const.SITE_URL')}}/brand-perfumes.html" class="linksbb hidden-sm-down1" title="View all popular brands" tabindex="0"><strong>View all popular brands</strong></a>
						</div>
					</div>
				@endslot
				@slot('product_start_section')
					<div class="popularbrands-tabs-man">
						<div class="popularbrands-tabs">
							@if (isset($popularBrand) and (count($popularBrand) >0))
								@foreach($popularBrand as $brand)
									@if (isset($brand['brand_product_count']) and !empty($brand['brand_product_count']))
										<a href="javascript:ShowTab({{$brand['brand_id']}});" title="{{ ucfirst($brand['brand_name']) }}" class="anchor-{{$brand['brand_id']}} {{ $loop->first ? 'active' : ''}}" tabindex="0">
											<picture><img src="{{ $brand['brand_logo_image_url'] }}" alt="{{ ucfirst($brand['brand_name']) }}" title="{{ ucfirst($brand['brand_name']) }}" width="165" height="53" loading="lazy" /></picture>
										</a>
									@endif
								@endforeach
							@endif	
						</div>
					</div>
				   <div class="popularbrands-content">
				@endslot
				@slot('product_content')
					@if (isset($popularBrand) and (count($popularBrand) >0))
						@foreach($popularBrand as $brand)
							@if (isset($brand['brand_product_count']) and !empty($brand['brand_product_count']))
							<div class="inner {{ $loop->first ? 'active' : ''}}" id="tab-{{ $brand['brand_id'] }}">
								<div class="slick-scroll slick-space-20">
									<x-productbox :prodData="$brand['product']" />
								</div>
								<div class="slick-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
									<span class="slick-label"></span>
								</div>
							</div>
							@endif
						@endforeach
					@endif	
					<div class="hidden-md-up tac mt-2"><a href="{{config('const.SITE_URL')}}/brand-perfumes.html" class="linksbb" title="View all popular brands" tabindex="0" aria-label="View all popular brands"><strong>View all popular brands</strong></a></div>
				@endslot
				@slot('product_end_section')
					</div>		
				@endslot
				@slot('section_end')
				</div>	
				@endslot
			</x-slider>
		</section>
		@endif
		<section class="space70" aria-label="New Arrivals">
			<x-slider :sliderData="$newArrivals" >
				@slot('section_start')
					<div class="gallery-slider clearfix">
						<div class="cm-hd">
							<div class="cm-hd-left">
							<div class="h5" tabindex="0">New Arrivals</div>
							<span class="cm-hd-items" tabindex="0">{{count($newArrivals)}} items</span>
							</div>
							<div class="cm-hd-right">
							<a href="{{config('const.SITE_URL')}}/new-arrival.html" class="linksbb" title="View all New Arrivals" aria-label="View all New Arrivals" tabindex="0"><strong>View all <span class="hidden-xs-down">New Arrivals</span></strong></a>
						</div>
						</div>
				@endslot
				@slot('product_start_section')
						<div class="slick-scroll slick-space-20">
				@endslot
						@slot('product_content')
							<x-productbox :prodData="$newArrivals" />
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
		<?php //dd($HomePageBanner_Middle_Wholesaler); ?>
		@if(isset($HomePageBanner_Middle_Wholesaler) && count($HomePageBanner_Middle_Wholesaler) > 0)
		<section class="space70" aria-label="Become a wholesaler">
			<a href="{{$HomePageBanner_Middle_Wholesaler[0]['more_link']}}" title="{{$HomePageBanner_Middle_Wholesaler[0]['title']}}" class="promotions-banner" aria-label="{{$HomePageBanner_Middle_Wholesaler[0]['title']}}" tabindex="0">
				<picture>
					<source media="(min-width:768px)" srcset="{{$HomePageBanner_Middle_Wholesaler[0]['thumb_image']}}">
					<img src="{{$HomePageBanner_Middle_Wholesaler[0]['thumb_image_mobile']}}" alt="{{$HomePageBanner_Middle_Wholesaler[0]['title']}}" title="{{$HomePageBanner_Middle_Wholesaler[0]['title']}}" class="fwidth" width="1363" height="125" loading="lazy" /> 
				</picture>
			</a>
			{{--
			Do not remove this code. If client need Text based then open the comment.
			<div class="wholesaler-banner clearfix">
				<svg class="svg_wholesalericon" aria-hidden="true" role="img" width="54" height="55" loading="lazy">
					<use href="#svg_wholesalericon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_wholesalericon"></use>
				</svg>
				<div class="big-hd" tabindex="0">Become a Wholesaler</div>
				<a href="javascript:void(0)" class="btn btn-border btn-white" title="Register Now" aria-label="Register Now" tabindex="0">Register Now</a>
			</div>
			Do not remove this code. If client need Text based then open the comment.
			--}}
		</section>
		@endif	
		<section class="space70" aria-label="Our Happy Customers">
			<div class="happyman clearfix">
				<div class="hd">
					<div class="h3" tabindex="0">Our Happy <br/> Customers</div>
					<span><a href="{{config('const.SITE_URL')}}/#customer-reviews" onClick="return false;" rel="noindex nofollow" class="linksbb" title="View All Customer Reviews" aria-label="View All Customer Reviews" tabindex="0"><strong>View All Customer Reviews</strong></a></span>
				</div>
				<div class="inner">
					<div class="happyman-slick slick-space-20 slickarrow-ovr">
						<div class="box">
							<div class="box-top">
								<a href="{{config('const.SITE_URL')}}/#start" onClick="return false;" rel="noindex nofollow" title="Product Review" tabindex="0"><img src="images/star.png" title="star"  alt="star" width="72" height="13" loading="lazy"></a>
								<strong class="dblock" tabindex="-1">Makes me happy</strong>
								<p tabindex="-1">Lorem ipsum dolor sit amet consectetur. Nibh sed sed turpis sem quis hendrerit. Amet quis diam orci pulvinar tortor augue vel consequat.</p>
								<span tabindex="-1">Jesicca A.</span>
							</div>
							<div class="box-bottom">
								<picture><img src="images/58.webp" alt="Yoga Stap jsfu" width="58" height="58" loading="lazy" /></picture>
								<div>
									<span tabindex="-1">Yoga Stap -jsfu</span>
									<strong class="dblock" tabindex="-1">$16.50</strong>
								</div>
							</div>
						</div>
						<div class="box">
							<div class="box-top">
								<a href="{{config('const.SITE_URL')}}/#start" onClick="return false;" rel="noindex nofollow" title="Product Review" tabindex="0"><img src="images/star.png" title="star"  alt="star" width="72" height="13" loading="lazy"></a>
								<strong class="dblock" tabindex="-1">Makes me happy</strong>
								<p tabindex="-1">Lorem ipsum dolor sit amet consectetur. Nibh sed sed turpis sem quis hendrerit. Amet quis diam orci pulvinar tortor augue vel consequat.</p>
								<span tabindex="-1">Jesicca A.</span>
							</div>
							<div class="box-bottom">
								<picture><img src="images/58.webp" alt="Yoga Stap jsfu" width="58" height="58" loading="lazy" /></picture>
								<div>
									<span tabindex="-1">Yoga Stap -jsfu</span>
									<strong class="dblock" tabindex="-1">$16.50</strong>
								</div>
							</div>
						</div>
						<div class="box">
							<div class="box-top">
								<a href="{{config('const.SITE_URL')}}/#start" onClick="return false;" rel="noindex nofollow" title="Product Review" tabindex="0"><img src="images/star.png" title="star"  alt="star" width="72" height="13" loading="lazy"></a>
								<strong class="dblock" tabindex="-1">Makes me happy</strong>
								<p tabindex="-1">Lorem ipsum dolor sit amet consectetur. Nibh sed sed turpis sem quis hendrerit. Amet quis diam orci pulvinar tortor augue vel consequat.</p>
								<span tabindex="-1">Jesicca A.</span>
							</div>
							<div class="box-bottom">
								<picture><img src="images/58.webp" alt="Yoga Stap jsfu" width="58" height="58" loading="lazy" /></picture>
								<div>
									<span tabindex="-1">Yoga Stap -jsfu</span>
									<strong class="dblock" tabindex="-1">$16.50</strong>
								</div>
							</div>
						</div>
						<div class="box">
							<div class="box-top">
								<a href="{{config('const.SITE_URL')}}/#start" onClick="return false;" rel="noindex nofollow" title="Product Review" tabindex="0"><img src="images/star.png" title="star"  alt="star" width="72" height="13" loading="lazy"></a>
								<strong class="dblock" tabindex="-1">Makes me happy</strong>
								<p tabindex="-1">Lorem ipsum dolor sit amet consectetur. Nibh sed sed turpis sem quis hendrerit. Amet quis diam orci pulvinar tortor augue vel consequat.</p>
								<span tabindex="-1">Jesicca A.</span>
							</div>
							<div class="box-bottom">
								<picture><img src="images/58.webp" alt="Yoga Stap jsfu" width="58" height="58" loading="lazy" /></picture>
								<div>
									<span tabindex="-1">Yoga Stap -jsfu</span>
									<strong class="dblock" tabindex="-1">$16.50</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
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
		
		<section class="space70" aria-label="Reviews from our community">
			<div class="homabo clearfix">
				<div id="haboutus"></div>
				<div class="homabo-rview">
					<h5 tabindex="0">Reviews from our community</h5>
					<ul>
						<li>
							<div class="homabo-rview-numbar" tabindex="-1">1,45,000 +</div>
							<div class="homabo-rview-lebal" tabindex="-1">Product Reviews</div>
							<div class="homabo-rview-star">
								<a href="{{config('const.SITE_URL')}}/#verified-customers" onClick="return false;" class="homabo-verified-link" tabindex="0" title="Verified Customers" aria-label="Verified Customers">
									<svg class="svg_verified" aria-hidden="true" role="img" width="26" height="27" loading="lazy">
										<use href="#svg_verified" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_verified"></use>
									</svg>
									Verified Customers
								</a>
							</div>
						</li>
						<li>
							<div class="homabo-rview-numbar" tabindex="-1">30,000 +</div>
							<div class="homabo-rview-lebal" tabindex="-1">Bizrate Reviews</div>
							<div class="homabo-rview-star">
								<svg class="svg_ratings" aria-hidden="true" role="img" width="117" height="21" loading="lazy">
									<use href="#svg_ratings" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_ratings"></use>
								</svg>
								<span tabindex="-1">9.7/10</span>
							</div>
							<div class="homabo-rview-link"><a href="{{config('const.SITE_URL')}}/#view-reviews" onClick="return false;" class="linksbb" tabindex="0" title="View Reviews" aria-label="View Reviews">View Reviews</a></div>
						</li>
						<li>
							<div class="homabo-rview-numbar" tabindex="-1">9,800 +</div>
							<div class="homabo-rview-lebal" tabindex="-1">Google Customer Reviews</div>
							<div class="homabo-rview-star">
								<svg class="svg_ratings" aria-hidden="true" role="img" width="117" height="21" loading="lazy">
									<use href="#svg_ratings" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_ratings"></use>
								</svg>
								<span tabindex="-1">9.7/10</span>
							</div>
							<div class="homabo-rview-link"><a href="{{config('const.SITE_URL')}}/#view-reviews" onClick="return false;" class="linksbb" tabindex="0" title="View Reviews" aria-label="View Reviews">View Reviews</a></div>
						</li>
					</ul>
				</div>
			</div>
		</section>
		<section class="space70" aria-label="HBA Store on Instagram">
			<div class="instagram-slider clearfix">
				<div class="cm-hd">
					<div class="cm-hd-left">						
						<div class="h5" tabindex="-1">{{config('const.SITE_NAME')}} on Instagram</div>
					</div>
					<div class="cm-hd-right"><a href="{{config('const.SITE_URL')}}/#get-inspired" onClick="return false;" class="linksbb" tabindex="0" title="Get Inspired" aria-label="Get Inspired"><strong>Get Inspired</strong></a></div>
				</div>
				<div class="instagramowl slick-space-20 slickarrow-ovr">
					<a href="{{config('const.SITE_URL')}}/#name" onClick="return false;" title="Name">
						<picture><img src="images/365x365.webp" alt="HBA Store on Instagram" width="365" height="365" loading="lazy" /></picture>
					</a>
					<a href="{{config('const.SITE_URL')}}/#name" onClick="return false;" title="Name">
						<picture><img src="images/365x365.webp" alt="HBA Store on Instagram" width="365" height="365" loading="lazy" /></picture>
					</a>
					<a href="{{config('const.SITE_URL')}}/#name" onClick="return false;" title="Name">
						<picture><img src="images/365x365.webp" alt="HBA Store on Instagram" width="365" height="365" loading="lazy" /></picture>
					</a>
					<a href="{{config('const.SITE_URL')}}/#name" onClick="return false;" title="Name">
						<picture><img src="images/365x365.webp" alt="HBA Store on Instagram" width="365" height="365" loading="lazy" /></picture>
					</a>
					<a href="{{config('const.SITE_URL')}}/#name" onClick="return false;" title="Name">
						<picture><img src="images/365x365.webp" alt="HBA Store on Instagram" width="365" height="365" loading="lazy" /></picture>
					</a>
					<a href="{{config('const.SITE_URL')}}/#name" onClick="return false;" title="Name">
						<picture><img src="images/365x365.webp" alt="HBA Store on Instagram" width="365" height="365" loading="lazy" /></picture>
					</a>
				</div>
			</div>
		</section>
	</div>
</div>
@endsection

