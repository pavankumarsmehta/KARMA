@if(isset($SizeWiseArray) && count($SizeWiseArray) > 0)
	<div class="swatch-attribute-label" tabindex="0"><span>{{(isset($Product['attribute_1']) && !empty($Product['attribute_1']))? $Product['attribute_1'] : 'Size' }}:</span><strong>{{$ActiveSize}}</strong></div>

	<div class="swatch-attribute-options swatch-size">
		@foreach($SizeWiseArray as $key =>$value)
		<?php //dd($SizeWiseArray);
		$size = strtolower($value['size']);
		$selectedSize = strtolower($ActiveSize);
		$activeSize = false;
		$count =  $loop->index;
		$checkShowMoreeClass = '';
		// if($count>7){
		// 	if(!isset($checkShowMoreDynamicClass)){
		// 		$checkShowMoreeClass = 'showhide-variant-box-js hidden-lg-down';
		// 	}else{
		// 		$checkShowMoreeClass = $checkShowMoreDynamicClass;
		// 	}
		// }
		if(isset($SizeSelectedWiseArray) && !empty($SizeSelectedWiseArray)){
			if(isset($SizeSelectedWiseArray[title($size)])){
				$activeSize = true;
			}
		}else{
			$activeSize = true;
		}
		?>
		<a href="#size-{{title($size)}}" onClick="return false;" tabindex="0" title="{{$value['product_name']}}" data-variant-name="size" data-variant-value="{{$value['size']}}" data-product-group-code="{{$Product['product_group_code']}}" @if($selectedSize == $size) class="active varint-change-js varint-change-size-js {{$checkShowMoreeClass}}" @endif @if(!$activeSize) class="disabled {{$checkShowMoreeClass}}" @endif class="{{$checkShowMoreeClass}} varint-change-js varint-change-size-js" rel="nofollow, noindex">
			{{-- <picture><img src="{{$value['product_small_image']}}" alt="{{$value['product_name']}}" width="30" height="30" loading="lazy" /></picture> --}}
			<span>{{$value['size']}}</span>
		</a>
		@endforeach
	</div>
@endif