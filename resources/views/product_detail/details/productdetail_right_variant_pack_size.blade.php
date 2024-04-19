@if(isset($PackSizeWiseArray) && count($PackSizeWiseArray) > 0)
	<div class="swatch-attribute-label" tabindex="0"><span>{{ (isset($Product['attribute_2']) && !empty($Product['attribute_2'])) ? $Product['attribute_2'] : 'Pack Size' }}:</span><strong>{{$ActivePackSize}}</strong></div>
	<div class="swatch-attribute-options swatch-size swatch-packsize">
		@foreach($PackSizeWiseArray as $key =>$value)
		<?php //dd($SizeWiseArray);
		$pack_size = strtolower($value['pack_size']);
		$selectedPackSize = strtolower($ActivePackSize);
		$activePackSize = false;
		$checkShowMoreeClass = '';
		$count =  $loop->index;
		// if($count>7){
		// 	if(!isset($checkShowMoreDynamicClass)){
		// 		$checkShowMoreeClass = 'showhide-variant-box-js hidden-lg-down';
		// 	}else{
		// 		$checkShowMoreeClass = $checkShowMoreDynamicClass;
		// 	}
		// }
		if(isset($PackSizeSelectedWiseArray) && !empty($PackSizeSelectedWiseArray)){
			if(isset($PackSizeSelectedWiseArray[title($pack_size)])){
				$activePackSize = true;
			}
		}else{
			$activePackSize = true;
		}
		?>
		<a href="#pack-size-{{title($pack_size)}}" onClick="return false;" tabindex="0" title="{{$value['product_name']}}" data-variant-name="pack_size" data-variant-value="{{$value['pack_size']}}" data-product-group-code="{{$Product['product_group_code']}}" @if($selectedPackSize == $pack_size) class="active varint-change-js varint-change-pack-size-js {{$checkShowMoreeClass}}" @endif @if(!$activePackSize) class="disabled {{$checkShowMoreeClass}}" @endif class="varint-change-js varint-change-pack-size-js {{$checkShowMoreeClass}}" rel="nofollow, noindex">
			{{-- <picture><img src="{{$value['product_small_image']}}" alt="{{$value['product_name']}}" width="30" height="30" loading="lazy" /></picture> --}}
			<span>{{$value['pack_size']}}</span>
		</a>
		@endforeach
	</div>
@endif