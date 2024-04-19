@php 
	$APP_URLS = config('const.APP_URLS');
@endphp
<section class="space70">
	<div class="cm-hd">
		<div class="cm-hd-left">
			<div class="h5" tabindex="0">Compare Similar Products</div>
		</div>
	</div>
	<div class="dtl-compare">
		<div class="dtl-compare-left">
			<div class="cp-lebal">Rating</div>
			<div class="cp-lebal">Price</div>
		</div>
		<div class="dtl-compare-right" id="dtl-compare-right">
			@if(count($SimilarProducts) > 0)
				@for($si=0;$si<count($SimilarProducts);$si++)
				<div class="cp-product">
					<div class="cp-product-info">
						<div class="cp-product-thumb">
							<a href="{{$APP_URLS.$SimilarProducts[$si]['product_url']}}" title="{{$SimilarProducts[$si]['product_name']}}"><picture><img src="{{$SimilarProducts[$si]['product_thumb_image']}}" alt="{{$SimilarProducts[$si]['product_name']}}" title="{{$SimilarProducts[$si]['product_name']}}" width="240" height="240" loading="lazy" /></picture>
							</a>
						</div>
						<div class="cp-product-name"><a href="{{$APP_URLS.$SimilarProducts[$si]['product_url']}}" title="{{$SimilarProducts[$si]['product_name']}}">{{$SimilarProducts[$si]['product_name']}}</a></div>
						
						<button type="button" onclick="window.location='{{$APP_URLS.$SimilarProducts[$si]['product_url']}}'" title="{{$SimilarProducts[$si]['product_name']}}" aria-label="See Details" class="btn btn-border btn-block">See Details</button>
					</div>
					<div class="cp-product-price">{{$SimilarProducts[$si]['our_price_disp']}}</div>
					<div class="cp-product-star"><a href="javascript:void(0)" rel="nofollow"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="Star" title="" ="Star" class="vam" width="72" height="13" loading="lazy"> (110)</a></div>
				</div>
				@endfor
			@endif
		</div>
	</div>
</section>