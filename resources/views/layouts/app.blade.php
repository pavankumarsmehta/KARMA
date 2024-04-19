<!DOCTYPE html>
<html class="sb-scroll-lock" lang="en">
	<head>	
		@include('layouts.head')
	</head>	
	@if($CurrentController == 'StaticPagesController')
		@php $bodyClass = 'static-pages'; @endphp
	@elseif($CurrentController == 'ShoppingcartController')
		@php $bodyClass = 'cart-body'; @endphp
	@elseif($CurrentController == 'ProductDetailController')
		@php $bodyClass = 'detail-body'; @endphp
	@else
		@php $bodyClass = ''; @endphp
	@endif 	
	<body class="{{$bodyClass}}">
		<div id="page-spinner"></div>
		@if($CurrentController == 'CheckoutController' || $CurrentController == 'OrderReceiptController')
			@include('layouts.cart_header_print')
		@else
			@include('layouts.header')
		@endif	
		<main>
			@yield('content')
		</main>
		@if($CurrentController == 'CheckoutController' || $CurrentController == 'OrderReceiptController')
			@include('layouts.cart_footer') 
		@else
			@include('layouts.footer') 
		@endif
		@include('layouts.schema')
		@stack('scripts')
		@include('js.indexjs',(isset($JSFILES)?['JSFILES' => $JSFILES]:['JSFILES' =>[]]))
		@include('components.svg')
		@yield('modal')
	</body>
</html>