@extends('layouts.app')
@section('content')
<div class="static-page">
	<div class="container">
		<div class="breadcrumb">
			<a href="{{ route('home') }}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
			<span class="active" tabindex="0">Press</span> 
		</div>
		<div class="static-hd"><h2 tabindex="0">Press</h2></div>
		<div class="static-con">Coming Soon</div>
	</div>    
</div>       
@endsection