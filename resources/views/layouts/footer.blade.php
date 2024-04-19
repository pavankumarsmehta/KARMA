<footer>
	<div class="container">
		<div class="ft-top">
			<div class="ft-news">
				<div class="hd" tabindex="0">Join Our Mailing List</div>
				<div class="por">
					<form class="needs-validation error-selector newsletter-form" method="POST" action="">
						{{ csrf_field() }}
						<input type="hidden" name="action" id="action" value="newsletter_subscribe" />
						<input type="text" name="bottom_email" id="bottom_email" class="form-control" placeholder="Your email address" aria-label="Email">
						<button class="btn" tabindex="0" type="button" href="javascript:void(0);" aria-label="Subscribe" onclick="return check_newsletter();">Subscribe</button>
						<p id="error_bottom_email" class=""></p>
						<p id="success_bottom_email" class=""></p>
					</form>
				</div>
			</div>
			<div class="ft_social">
				<div class="hd" tabindex="0">Follow</div>
					<div class="ft_icon">            
            <a href="https://www.facebook.com/profile.php?id=61552934259064" target="_blank" title="Facebook" aria-label="Facebook" tabindex="0"> 
              <svg class="svg_facebook" aria-hidden="true" role="img" width="35" height="35" loading="lazy">
                <use href="#svg_facebook" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_facebook"></use>
              </svg> 
            </a>
            <a href="https://www.instagram.com/hbastore1/" target="_blank" title="Instagram" aria-label="Instagram" tabindex="0">
              <svg class="svg_instagram" aria-hidden="true" role="img" width="35" height="35" loading="lazy">
                <use href="#svg_instagram" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_instagram"></use>
              </svg> 
            </a>
            <a href="https://www.pinterest.com/socialhbastore/" target="_blank" title="Pinterest" aria-label="Pinterest" tabindex="0"> 
              <svg class="svg_pinterest" aria-hidden="true" role="img" width="35" height="35" loading="lazy">
                <use href="#svg_pinterest" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pinterest"></use>
              </svg>
            </a>
            <a href="https://www.tiktok.com/@hbastore1?is_from_webapp=1&sender_device=pc" target="_blank" title="Tiktok" aria-label="Tiktok" tabindex="0">
              <svg class="svg_tiktok" aria-hidden="true" role="img" width="37" height="38" loading="lazy">
                <use href="#svg_tiktok" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_tiktok"></use>
              </svg> 
            </a> 
            <a href="https://youtube.com/@HBAStoreSocial?si=SyZwYxBjzoeVmokC" target="_blank" title="Youtube" aria-label="Youtube" tabindex="0"> 
              <svg class="svg_youtube" aria-hidden="true" role="img" width="35" height="35" loading="lazy">
                <use href="#svg_youtube" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_youtube"></use>
              </svg> 
            </a>
            <a href="https://twitter.com/Hba_store" target="_blank" title="Twitter" aria-label="Twitter" tabindex="0"> 
              <svg class="svg_twitter" aria-hidden="true" role="img" width="35" height="35" loading="lazy">
                <use href="#svg_twitter" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_twitter"></use>
              </svg> 
            </a>
          </div>
			</div>
		</div>
		{!! config('Footer')['Bottom'] !!}
	</div>
</footer>
<a href="{{config('const.SITE_URL')}}/#got-top" onClick="return false;" title="Go Top" aria-label="Go Top" class="go-top" tabindex="0"> </a>
</div>