@extends('layouts.app')
@section('content')
<style>

</style>
<div class="container">
	<div class="breadcrumb">
		<a href="{{config('const.SITE_URL')}}" tabindex="0" title="Home" aria-label="Home">Home
			<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
				<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
			</svg> 
		</a> 
		<a href="{{config('const.SITE_URL')}}/brand-perfumes.html" tabindex="0" title="Brand Listing" aria-label="Brand Listing">Brand Listing
			<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
				<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
			</svg> 
		</a> 
		<span class="active" tabindex="0">{{str_replace("-"," ",ucwords(Request::segment(2)));}}</span>
	</div>
	
	<div class="static-inner brandpage-main">
        <div class="row">
			<div class="col-xs-12">
				<div class="brandpage breadcrumb">
					<b style="font-size:18px;">{{str_replace("-"," ",ucwords(Request::segment(2)));}}</b>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
@endsection