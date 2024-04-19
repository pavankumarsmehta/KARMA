<div class="need_assistance">
	<div class="h4">Need Help?</div>
	<span class="fnormal dblock mb-1">We are here everyday from 7am - 11pm CT</span>
	<a href="tel:{{ config('Settings.TOLL_FREE_NO') }}" title="Call Us" class="nhlink">
		<span>
			<svg class="svg-phone" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
				<use href="#svg-phone" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-phone"></use>
			</svg>
		</span>
		{{ config('Settings.TOLL_FREE_NO') }}
	</a>
	<a href="javascript:void(0);" onclick="openWidget()" title="Live Chat" class="nhlink">
		<span>
			<svg class="svg-chat" aria-hidden="true" role="img" width="25" height="25" loading="lazy">
				<use href="#svg-chat" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-chat"></use>
			</svg>
		</span>
		Chat with a specialist
	</a>
</div>