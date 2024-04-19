
	<div class="dtl-rview">
		<div class="cm-hd">
			<div class="cm-hd-left">
				<div class="h3">Reviews</div>
			</div>
		</div>
		<?php //dd($Product['product_zoom_image']);?>
		@if(request()->get('third_party') != 'no')
			<div class="yotpo yotpo-main-widget" data-product-id="{{$Product['sku']}}" data-price="{{ $Product['our_price_disp'] }}" data-currency="" data-name="{{$Product['product_name']}}" data-url="{{$Product['product_url'] ?? ''}}" data-image-url="{{$Product['product_zoom_image']}}"></div>
		@endif
		{{--<ul class="dtl-rview-loop">
			<li class="dtl-rview-top">
				<div class="dtl-rview-cont">
					<div class="dtl-rview-left">
						<span>4.7</span><br />
						<a href="javascript:void(0)" rel="nofollow" aria-label="Review rating" title="Review rating"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="Star" title="Star" width="72" height="13" loading="lazy"></a><br />
						<strong>251 Reviews</strong>                  
					</div>
					<div class="dtl-rview-right">
						<ul class="dtl-rview-rt">
							<li class="rv5">5</li>
							<li class="rv4">4</li>
							<li class="rv3">3</li>
							<li class="rv2">2</li>
							<li>1</li>
						</ul>
						<!-- <a href="javascript:void(0)" rel="nofollow" class="btn btn-twilight-border" title="Write A Review">write A review</a> -->
						<button type="button" class="btn btn-twilight-border" title="Write A Review">write A review</button>
					</div>
				</div>
			</li>
			<li>
				<div class="dtl-rview-cont">
					<div class="dtl-rview-left">
						<a href="javascript:void(0)" rel="nofollow" aria-label="Review rating" title="Review rating"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="Star" title="Star" width="72" height="13" loading="lazy"></a><br />
						<strong>Lorem Ipsum</strong><br />
						<span>May 06, 2023</span>
					</div>
					<div class="dtl-rview-right">
						<strong>Stunning</strong>
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
					</div>
				</div>
			</li>
			<li>
				<div class="dtl-rview-cont">
					<div class="dtl-rview-left">
						<a href="javascript:void(0)" rel="nofollow" aria-label="Review rating" title="Review rating"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="Star" title="Star" width="72" height="13" loading="lazy"></a><br />
						<strong>Lorem Ipsum</strong><br />
						<span>May 06, 2023</span>
					</div>
					<div class="dtl-rview-right">
						<strong>Stunning</strong>
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
					</div>
				</div>
			</li>
			<li>
				<div class="dtl-rview-cont">
					<div class="dtl-rview-left">
						<a href="javascript:void(0)" rel="nofollow" aria-label="Review rating" title="Review rating"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="Star" title="Star" width="72" height="13" loading="lazy"></a><br />
						<strong>Lorem Ipsum</strong><br />
						<span>May 06, 2023</span>
					</div>
					<div class="dtl-rview-right">
						<strong>Stunning</strong>
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
					</div>
				</div>
			</li>
		</ul>
		<div class="tac pt-5">
			<!-- <a href="javascript:void(0)" rel="nofollow" class="btn btn-twilight-border" aria-label="Load more" title="Load More">Load more</a> -->
			<button type="button" class="btn btn-twilight-border" aria-label="Load more" title="Load More">Load more</button>
		</div>--}}
	</div>
