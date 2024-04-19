		<?php /* Head Start */ ?>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5.0" uw-rm-meta-viewport="">-->
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
	<!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=10, minimum-scale=1, user-scalable=yes" />-->
	{{-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0, user-scalable=maximum-scale, user-scalable=yes"/> --}}
	<meta name="mobile-web-app-capable" content="yes">
	<title>{{ (isset($meta_title) && $meta_title != '') ? $meta_title : config('global.META_TITLE') }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="description" content="{{ (isset($meta_description) && $meta_description != '') ? $meta_description : config('global.META_DESCRIPTION') }}" />
	<meta name="keywords" content="{{ (isset($meta_keywords) && $meta_keywords != '') ? $meta_keywords : config('global.META_KEYWORDS') }}" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="author" content="">
	<meta name="theme-color" content="">
	@if(isset($canonical_url) && $canonical_url != '')
		<link rel="canonical" href="{{ $canonical_url }}" />
	@else
		<link rel="canonical" href="{{ url()->full() }}" />
	@endif
	<?php /* Favicon Icon Start */ ?>
	<link rel="shortcut icon" type="image/x-icon" href="{{asset('images/favicon.ico')}}">
	<link rel="icon" type="image/x-icon" href="{{asset('images/favicon.ico')}}">
	<link rel="apple-touch-icon" href="{{asset('images/apple-touch-icon.png')}}">
	<?php /* Favicon Icon End */ ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
	<!--<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet" />-->
	<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow">	
	<!-- External Styles  -->	
	<!-- IE8 & lower version CSS -->
	<!--[if (lte IE 8) & (!IEMobile)]>
	<link href="style/ie8.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<!-- IE8 & lower version CSS -->
	@php 
	  $FilePath = config('const.SITE_URL').'public'; 
	  $ConfigMsg = json_encode(config('fmessages'));
	@endphp
	<script>
	  var site_url = '{{config("const.SITE_URL")}}';
	  var base_url_new = "{{config('app.url')}}";
	  function GetMessage(Module,Message)
	  {
		var config = <?php echo $ConfigMsg?>;
		return config[Module][Message];
	  }
	</script>
	<script type="text\javascript">
		var base_url_old = "{{config('app.url_old')}}";
		var base_url_new = "{{config('app.url')}}";
		var site_url = "{{config('const.SITE_URL')}}";
	</script>

	@if(request()->get('third_party') != 'no')
	<!-- Yotpo Code Start -->
	<script type="text/javascript">
	 (function e(){var e=document.createElement("script");e.type="text/javascript", e.id="yotpojs", e.async=true,e.src="//staticw2.yotpo.com/ftjanVWD3XCtYEFeeMsmLS0NiX6v29Y3hREHN9mC/widget.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();
	</script>
	<!-- Yotpo Code End -->
	@endif

	<!-- Doofinder Search Code Start -->
	<script>
  const dfLayerOptions = {
    installationId: '927283d9-011e-4976-9dd9-237f25d2e9df',
    zone: 'us1'
  };

  

  (function (l, a, y, e, r, s) {
    r = l.createElement(a); r.onload = e; r.async = 1; r.src = y;
    s = l.getElementsByTagName(a)[0]; s.parentNode.insertBefore(r, s);
  })(document, 'script', 'https://cdn.doofinder.com/livelayer/1/js/loader.min.js', function () {
    doofinderLoader.load(dfLayerOptions);
  });
</script>

	
	<!-- Doofinder Search Code End -->
	
	<?php /* External Styles Start */ ?>
	@include('css.indexcss',(isset($CSSFILES)?['CSSFILES' => $CSSFILES]:['CSSFILES' =>[]]))
	<?php /* External Styles End */ ?>
	@stack('styles')
		
		