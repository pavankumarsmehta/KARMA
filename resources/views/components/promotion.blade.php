@if(isset($promotionData) && !empty($promotionData))  
<picture>
	<source media="(min-width:768px)" srcset="{{$promotionData[0]['promotion_banner_image']}}">
	<img src="{{$promotionData[0]['promotion_banner_image']}}" alt="{{$promotionData[0]['promotion_title']}}" width="1520" height="180" loading="lazy" /> 
	</picture>
	<div class="over">                    
		<div class="big-hd">{{$promotionData[0]['promotion_title']}}</div>
		<div>
			@if(isset($promotionData[0]['promotion_text']) && !empty($promotionData[0]['promotion_text']))             
			<div class="small-hd">{{$promotionData[0]['promotion_text']}}</div>
			@endif
			@if(isset($promotionData[0]['promotion_image_link']) && !empty($promotionData[0]['promotion_image_link']))             
			<a href="{{$promotionData[0]['promotion_image_link']}}" title="Shop Now" class="linksbb">Shop Now</a>
			@endif
		</div>
	</div>
@endif