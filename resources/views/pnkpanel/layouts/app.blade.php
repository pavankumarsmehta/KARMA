<!DOCTYPE html>
<html class="" data-style-switcher-options="{'changeLogo': false}">
<head>
	
<!-- Responsive Metatags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<!-- Responsive Metatags -->

<!-- Site Title -->
<title>{{ (isset($meta_title) && $meta_title != '') ? $meta_title.' - ' : '' }} {{ config('Settings.SITE_TITLE') }} Admin Panel</title>
<!-- Site Title -->

<!-- Favicon Icon -->
<link rel="icon" href="{{ asset('pnkpanel/images/favicon.ico') }}" type="image/x-icon" />
<link rel="icon" href="{{ asset('pnkpanel/images/favicon.ico') }}" type="ico" />
<link rel="SHORTCUT ICON" href="{{ asset('pnkpanel/images/favicon.ico') }}" />

<meta name="robots" content="noindex, nofollow, noarchive" />
<meta name="csrf-token" content="{{ csrf_token() }}">

@include('pnkpanel.component.css_head')
@stack('styles')

<script src="{{ asset('pnkpanel/js/plugins/modernizr.js') }}"></script>

@if (!Pnkpanel::isLoginPage() && !Pnkpanel::isLogoutPage() && !Pnkpanel::isLockScreenPage())
<script src="{{ asset('pnkpanel/js/plugins/style.switcher.localstorage.js') }}"></script>
@endif

</head>

<body>
<script>
var site_url = '<?= config("const.SITE_URL") ?>';
if (typeof localStorage !== 'undefined') {
	if (Boolean(localStorage.getItem('style-sidebar-left-toggle-collapsed'))) {
		var html = document.getElementsByTagName('html')[0];
		html.className = html.className + ' sidebar-left-collapsed';
	}
}
</script>
@if ((Pnkpanel::isLoginPage() || Pnkpanel::isLogoutPage() || Pnkpanel::isLockScreenPage()))
	@yield('content')
@else
	<section class="body">
	@include('pnkpanel.layouts.header')
	
	<div class="inner-wrapper">
		@include('pnkpanel.layouts.sidebar')
		<section role="main" class="content-body {{ Route::currentRouteName() != 'pnkpanel.dashboard' ? 'content-body-modern mt-0' : '' }} ">
		@include('pnkpanel.component.page_header')
		@yield('content')
		</section>
	</div>
	
	@include('pnkpanel.layouts.footer')
	</section>	
@endif

@include('pnkpanel.component.svg')

@include('pnkpanel.component.js_bottom')
@stack('scripts')

</body>
</html>
