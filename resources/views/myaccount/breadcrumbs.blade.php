<div class="breadcrumb">
	<a href="{{config('const.SITE_URL')}}" tabindex="0" title="Home" aria-label="Home">Home
		<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img">
			<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
		</svg> 
	</a>
	<a href="{{route('myaccount')}}" tabindex="0" title="My Account" aria-label="My Account">My Account
		<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img">
			<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
		</svg> 
	</a> 
	@if(isset($Breadcrumbs))
		<span class="active" tabindex="0">{{ $Breadcrumbs }}</span>
	@endif
</div>