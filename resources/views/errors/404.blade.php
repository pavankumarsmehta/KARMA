@extends('layouts.app')
@section('content')
<section class="home_slider">
	<div class="container tac">
		<div class="mt-5">&nbsp;</div>
		<div class="mt-5 h3"><b>404</b></div>
		<div class="mb-2 pnf_text">Page Not Found</div>
		<!-- <div class="mb-5 h6">Sorry, can’t find this page.</div> -->
		
		<div class="col-auto mb-5">
			<a href="{{config('const.SITE_URL')}}" title="Back to Home" class="btn btn-border">
				<svg class="svg_arrow_right me-1" aria-hidden="true" role="img" width="7" height="14">
					<use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use>
				</svg> Back to Home</a>
			
		</div>
		
	</div>
</section>
<section class="space70">
	<div class="container">
		<div class="ctbox-box clearfix">
				<x-categorybox :catData="$get_otherMainCategoryList"  /> 
		</div>
</div>	 
</section>
<section class="space70">
		<div class="separator-border">&nbsp;</div>
	</section>
@if(isset($otherCategoryPopularBrandst) && !empty($otherCategoryPopularBrandst))  
	<section class="space70">
	<div class="container">
		<div class="cm-hd">
			<div class="cm-hd-left"><div class="h5">Popular Brands</div></div>
			<div class="cm-hd-right"><a href="{{config('const.SITE_URL')}}/brand-perfumes.html" title="View all Popular Brands" class="linksbb"><strong>View all <span class="hidden-xs-down">Popular Brands</span></strong></a></div>
		</div>
		<div class="slick-brand slick-space-20 slickarrow">
			<x-popularbrandbox :popularBrandData="$otherCategoryPopularBrandst" />
		</div>
	</div>
	</section>
	<section class="space70">
		<div class="separator-border">&nbsp;</div>
	</section>		
@endif		
@endsection