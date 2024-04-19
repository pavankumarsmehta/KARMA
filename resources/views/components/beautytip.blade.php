@if(isset($beautytipsData) && !empty($beautytipsData))  
<div class="cm-hd">
	<div class="cm-hd-left"><div class="h5" tabindex="0">Beauty Tips</div></div>
	{{-- <div class="cm-hd-right"><a href="javascript:void(0)"  class="linksbb" title="Read More" aria-label="Read More" tabindex="0"><strong>Read More</strong></a></div> --}}
</div>
<div class="slick-beauty slick-space-10 slickarrow-ovr">
	@foreach($beautytipsData[0]['category_beauty_json'] as $categoryBeauty)
	@if(isset($categoryBeauty['category_status']) && !empty($categoryBeauty['category_status']) && $categoryBeauty['category_status']==1)  
		<div class="beautybanner">
			<picture><img src="{{$categoryBeauty['category_beauty_image']}}" alt="{{$categoryBeauty['category_name']}}" title="{{$categoryBeauty['category_name']}}" width="500" height="535" class="fwidth" loading="lazy" /></picture>
			<div class="over">
				{{--  <div class="date" tabindex="0">15 JUN 23</div>  --}}
				@if(isset($categoryBeauty['category_name']) && !empty($categoryBeauty['category_name']))  
				<div class="title" tabindex="-1">{{$categoryBeauty['category_name']}}</div>
				@if(isset($categoryBeauty['category_link']) && !empty($categoryBeauty['category_link']))  
					<a href="{{$categoryBeauty['category_link']}}" title="{{$categoryBeauty['category_name']}}" class="linksbb" aria-label="Read More" tabindex="0"><strong>Read More</strong></a>
				@endif
				@endif
			</div>
		</div>
		@endif
	@endforeach
</div>
@endif
