@include('layouts.popups')
<div class="wrapper">
<header class="ck-header">
	<div class="container">
		<div class="ckhd-mid">
			<ul class="ckhd-link">
				<li>
					<a href="callto:{{ config('Settings.TOLL_FREE_NO') }}" title="Call Us">
						<svg class="svg-phone" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
							<use href="#svg-phone" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-phone"></use>
						</svg>
						<span>{{ config('Settings.TOLL_FREE_NO') }}</span>
					</a>
				</li>
				<li>
					<a href="mailto:{{ config('Settings.CONTACT_MAIL') }}" title="Email Us">
						<svg class="svg-email" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
							<use href="#svg-email" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-email"></use>
						</svg>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);" onclick="openWidget()" title="Live Chat">
						<svg class="svg-chat" aria-hidden="true" role="img" width="27" height="27" loading="lazy">
							<use href="#svg-chat" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-chat"></use>
						</svg>
					</a>
				</li>
			</ul>
			<div class="ckhd-pis"><img src="{{config('const.SITE_URL')}}/images/nortan.png" width="81" height="35" loading="lazy" alt="Nortan Antivirus" title="Nortan Antivirus" /></div>
			<div class="ckhd-tebs">
				<h1 class="logo">
					<a href="{{config('const.SITE_URL')}}" title="HBA Sales">
						<svg class="svg_logo" width="234px" height="74px" aria-hidden="true" role="img" loading="lazy">
							<use href="#svg_logo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_logo"></use>
						</svg>
					</a>
				</h1>
			</div>
		</div>
	</div>
</header>