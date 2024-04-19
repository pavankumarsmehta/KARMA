@extends('layouts.app')
@section('content')

<div class="static-page">
@include('staticPages.static_page_breadcrumb')
	<div class="container">
		<div class="breadcrumb">
			<a href="{{ route('home') }}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
			<span class="active" tabindex="0">About US</span> 
		</div>
		<div class="static-hd"><h1 tabindex="0" class="h2">About Us</h2></div>
		<div class="static-con"><p>about us description</p></div>
	</div>    
</div>
@endsection



