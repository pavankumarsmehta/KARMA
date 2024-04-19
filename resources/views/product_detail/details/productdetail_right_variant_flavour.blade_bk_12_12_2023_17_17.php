@if(isset($FlavourWiseArray) && count($FlavourWiseArray) > 0)
		<div class="swatch-attribute-label" tabindex="0"><span>{{(isset($Product['attribute_3']) && !empty($Product['attribute_3']))? $Product['attribute_3'] : 'Flavour' }}:</span><strong>{{$ActiveFlavour}}</strong></div>

		<div class="swatch-attribute-options swatch-size swatch-flavour">
			@foreach($FlavourWiseArray as $key =>$value)
			<?php //dd($SizeWiseArray);
			$flavour = strtolower($value['flavour']);
			$selectedFlavour = strtolower($ActiveFlavour);
			$activeFlavour = false;
			$checkShowMoreeClass = '';
			$count =  $loop->index;
			if($count>9){
				if(!isset($checkShowMoreDynamicClass)){
					$checkShowMoreeClass = 'showhide-variant-box-js hidden-lg-down';
					$flagShowHideVarinat = 'showhide-variant-box-js hidden-lg-down';
				}else{
					$checkShowMoreeClass = $checkShowMoreDynamicClass;
					if(!isset($flagShowHideVarinat)){
						$flagShowHideVarinat = 'showhide-variant-box-js hidden-lg-down';
					}
				}
			}
			if(isset($FlavourSelectedWiseArray) && !empty($FlavourSelectedWiseArray)){
				if(isset($FlavourSelectedWiseArray[title($flavour)])){
					$activeFlavour = true;
				}
			}else{
				$activeFlavour = true;
			}
			?>
			<a href="#favour-{{title($flavour)}}" onClick="return false;" data-variant-name="flavour" data-variant-value="{{$value['flavour']}}" data-product-group-code="{{$Product['product_group_code']}}" rel="nofollow, noindex" tabindex="0" title="{{$value['product_name']}}" @if($selectedFlavour == $flavour) class="active varint-change-js varint-change-flavour-js {{$checkShowMoreeClass}}" @endif @if(!$activeFlavour) class="disabled {{$checkShowMoreeClass}}" @endif class="varint-change-js varint-change-flavour-js {{$checkShowMoreeClass}}">
			{{--<picture><img src="{{$value['product_small_image']}}" alt="{{$value['product_name']}}" width="30" height="30" loading="lazy" /></picture> --}}
				<span>{{$value['flavour']}}</span>
			</a>
			@endforeach
		</div>
		<div class="swatch-show">
			<a href="#swatch-showmore" tabindex="0" data-showhide-variant="{{ !empty($flagShowHideVarinat) ? 'true':'false'}}" data-show-variant-text="Show More" data-hide-variant-text="Show Less"  rel="nofollow, noindex" aria-label="{{ !empty($flagShowHideVarinat) ? 'Show More':'Show Less'}}" class="showhide-link-variant-js {{ !empty($flagShowHideVarinat) ? '':'swatch-show-less'}} @if(count($FlavourWiseArray) <= 9) hidden-lg-down @endif">{{ !empty($flagShowHideVarinat) ? 'Show More':'Show Less'}} <span class="arrpw"></span></a>
			<input type="hidden" name="flag-show-hide-varinat" id="flag-show-hide-varinat" value="{{ !empty($flagShowHideVarinat) ? 'true':'false'}}"
			{{--<a href="#swatch-hideless" tabindex="0" data-showhide="false" rel="nofollow, noindex" aria-label="Show Less" class="swatch-show-less showhide-link-variant-js">Show Less <span class="arrpw"></span></a>--}}
		</div>
	@endif