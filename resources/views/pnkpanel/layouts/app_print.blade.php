<!DOCTYPE html>
<html>
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
</head>

<body>
@yield('content')

@include('pnkpanel.component.svg')

@stack('scripts')

</body>
</html>
