@if(count($related_item) > 0)
@php 
	$APP_URLS = config('const.APP_URLS');
@endphp
<section class="space70">
	<div class="gallery-slider clearfix">
		<div class="cm-hd">
			<div class="cm-hd-left">
				<div class="h5">Related items</div>
			</div>
		</div>
		<?php //dd($related_item);?>
		<div class="slick-scroll slick-space-20">
			@for($i=0;$i<count($related_item);$i++)
			<div class="product">
				<div class="product_thumb">
					<a href="{{$APP_URLS.$related_item[$i]['product_url']}}" title="{{$related_item[$i]['product_name_hover']}}">
						<picture><img src="{{$related_item[$i]['product_thumb_image']}}" alt="" title="" width="365" height="365" loading="lazy" /></picture>
					</a>
					<a href="javascript:void(0)" data-productid="{{$related_item[$i]['product_id']}}" rel="nofollow" title="Add to wishlist" class="displaypopupboxwishlist wishlist">
						<svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg" loading="lazy">
							<path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokeWidth="1" strokeLinejoin="round" />
						</svg>
					</a>
				</div>
				<div class="product_sku">{{strip_tags($related_item[$i]['product_description'])}}</div>
				<div class="product_name"><a href="{{$APP_URLS.$related_item[$i]['product_url']}}" rel="nofollow" title="{{$related_item[$i]['product_name_hover']}}">{{$related_item[$i]['product_name']}}</a></div>
				<div class="product_price">{{$related_item[$i]['our_price_disp']}}</div>
				<div class="product_review"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="Star" title="Star" width="72" height="13" loading="lazy"/>(110)</div>
			</div>
			@endfor
		</div>
		<div class="slick-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"><span class="slick-label"></span></div>
	</div>
</section>
@endif