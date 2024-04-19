@if (isset($dealOfWeekData) and count($dealOfWeekData))
@php 
	$APP_URLS = config('const.APP_URLS');
@endphp
<div class="dealday">
	{{ $header }}
	<div>
		<div class="dealday-slider no-loaded fxheight">
			@foreach($dealOfWeekData as $dealOfWeekObj)
				<div class="product"><a class="product_thumb" href="{{ $APP_URLS.$dealOfWeekObj->product_url }}" aria-label="{{ $dealOfWeekObj->product_name}}" title="{{ $dealOfWeekObj->product_name}}" tabindex="0"><img src="{{ $dealOfWeekObj->prod_image}}" alt="{{ $dealOfWeekObj->product_name}}" width="450" height="450" loading="lazy"/></a>
				@if(($dealOfWeekObj->current_stock <= 0))
					<span class="dealday-stock outofstock" tabindex="0">Out Of Stock </span>
				@endif
				</div>
			@endforeach
		</div>
		<div class="dealday_detail">
			<div class="dealday_detail_slid  no-loaded">
				@foreach($dealOfWeekData as $dealOfWeekObj)
				<div>
					<div class="product_tx" tabindex="-1">{{ $dealOfWeekObj->description}}</div>
					<a class="product_name" href="{{ $APP_URLS.$dealOfWeekObj->product_url }}" aria-label="{{ $dealOfWeekObj->product_name}}" title="{{ $dealOfWeekObj->product_name}}" tabindex="-1">{{ $dealOfWeekObj->product_name}}</a>
					<div class="product_price">
					{{-- <span class="old-price">{{ $dealOfWeekObj->retail_price}}</span> --}}
						<span class="deal-price" id="deal_price_{{$dealOfWeekObj->product_id}}" tabindex="-1">Deal Price: {{ $dealOfWeekObj->deal_price}}</span>
					</div>
					<!-- <button class="btn buy_now" data-product="{{$dealOfWeekObj->product_id}}" data-type="buy_now_home_page" type="submit">Buy Now</button> -->
					<button type="button" class="buynow btn" title="Buy Now"  aria-label="Buy Now" data-product="{{$dealOfWeekObj->product_id}}" data-page="home" data-price="{{$dealOfWeekObj->deal_price}}">Buy Now</button>
					<p class="buy_now_error"></p>
				</div>
				@endforeach
			</div>
		</div>
	</div>
   {{ $footer }}
</div>
@endif
