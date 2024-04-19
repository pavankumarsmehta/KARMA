@extends('layouts.app')
@section('content')
<div class="static-page">
	<div class="container">
		<div class="breadcrumb">
			<a href="{{ route('home') }}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
			<span class="active" tabindex="0">Flooring Articles</span> 
		</div>
		<div class="static-hd"><h2 tabindex="0">Flooring Articles</h2></div>
		<div class="static-con">
			<div class="static-content art-st">
				<h1 class="sub-hd1 red-color">Flooring Articles</h1>
				@foreach($arrArticle as $Article)
				@if($loop->index != 0) <hr> @endif
				<blockquote class="@if($loop->index != 0) mt-4 @endif mb-4">
					<h3 class="sub-hd3 mb-0"><a href="{{ $Article['page_url'] }}" class="btn-link link-blue text-decoration-none" title="{{ $Article['title'] }}">{{ $Article['title'] }}</a></h3>
					<p>{{ $Article['short_desc'] }}</p>
					<div class="pb5"><a class="btn-link" href="{{ $Article['page_url'] }}" title="{{ $Article['title'] }}"><span>Read More</span></a></div>
				</blockquote>
				@endforeach
			</div>
			@endsection
		</div>
	</div>    
</div>


	