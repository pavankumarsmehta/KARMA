@if(config('const.ENV') == 'Dev')

<link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/bootstrap.css?'.config('Settings.FILE_JSCSS_VER')) }}" />
<link rel="stylesheet" type="text/css" media="all" href="{{config('const.SITE_URL')}}/css/front/custom.css?ver={{config('Settings.FILE_JSCSS_VER')}}" />
<link rel="stylesheet" type="text/css" media="all" href="{{config('const.SITE_URL')}}/css/jquery.lightbox.css?ver={{config('Settings.FILE_JSCSS_VER')}}" />
<?php //echo count($CSSFILES); exit;
if (count($CSSFILES) > 0) { //echo "<pre>"; print_r($CSSFILES); exit;
	foreach ($CSSFILES as $cssfile) { ?>
		<link rel="stylesheet" type="text/css" media="all" href="{{config('const.SITE_URL')}}/css/{{ $cssfile }}?ver={{config('Settings.FILE_JSCSS_VER')}}" />
<?php
	}
}
?>
@else
<?php if (count($CSSFILES) > 0) { ?>
	<style>
		<?php
		foreach ($CSSFILES as $cssfile) {
			require_once(public_path('/css/' . $cssfile));
		}
		?>
	</style>
<?php } ?>
@endif