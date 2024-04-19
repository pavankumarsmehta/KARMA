<?php //dd($cart_data);?>
@php 
	$prod_id = ""; 
@endphp
@if(count($cart_data) > 0)	
@php 
	$APP_URLS = config('const.APP_URLS');
@endphp
	@foreach($cart_data as $key => $val)
		<div class="loop">
			<div class="cart_row">
				<div class="thumb"><a href="{{$APP_URLS.$val['product_url']}}" title="{{ $val['ProductName'] }}"><picture><img src="{{ $val['Image'] }}" width="175" height="175" alt="{{ $val['ProductName'] }}" loading="lazy"></picture></a>
					<div class="hidden-sm-up">
						<div class="qty-box mt-2">
							<button type="button" class="left-qty btn-number" data-type="minus" data-field="prod_{{$key}}">-</button>
							<label for="prod_{{$key}}_mob" class="dnone">Quantity box</label>
							<input type="text" aria-label="Quantity box" name="{{$key}}" id="prod_{{$key}}_mob" data-field="prod_{{$key}}" data-productid="{{ $val['product_id'] }}" class="form-control input-number prod_{{$key}}" value="{{ $val['Qty'] }}" min="1" max="10" />
							<button type="button" class="right-qty btn-number" data-type="plus" data-field="prod_{{$key}}">+</button>
						</div>
					</div>
				</div>
				<div class="info">
					<div class="mb-3"><a href="{{$APP_URLS.$val['product_url']}}" class="name" title="{{$APP_URLS.$val['product_url']}}">{{ $val['ProductName'] }}</a></div>
					<div class="pb-2">SKU:<span class="ps-2">{{ $val['SKU'] }}</span></div>

					@if (isset($val['flavour']) && $val['flavour'] != "")
						<div class="pb-1">@if(isset($val['attribute_3']) && $val['attribute_3'] != "") <b>{{$val['attribute_3']}}:</b> @endif {{$val['flavour']}}</div>
					@endif
					
					@if (isset($val['size_dimension']) && $val['size_dimension'] != "")
						<div class="pb-1">@if(isset($val['attribute_1']) && $val['attribute_1'] != "") <b>{{$val['attribute_1']}}:</b> @endif {{$val['size_dimension']}}</div>
					@endif
					
					@if (isset($val['pack_size']) && $val['pack_size'] != "")
						<div class="pb-1">@if(isset($val['attribute_2']) && $val['attribute_2'] != "") <b>{{$val['attribute_2']}}:</b> @endif {{$val['pack_size']}}</div>
					@endif
					
					<div class="msclinks mt-3">
						<a href="javascript:void(0);" class="displaypopupboxwishlist wishlist {{ (isset($val['is_wish']) && $val['is_wish']) ? 'active' : '' }}" data-productid="{{ $val['product_id'] }}" title="Wishlist">
						<!--<svg class="svg_heart" width="19px" height="16px" aria-hidden="true" role="img" loading="lazy">
							<use href="#svg_heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_heart"></use>
						</svg>-->
						<svg width="19" height="17" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg" loading="lazy">
							<path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokeWidth="1" strokeLinejoin="round" />
						</svg>  
						Add to wishlist</a>
						<a href="javascript:void(0);" class="remove" data-index="{{ $key }}" title="Remove">
						<svg class="svg_close" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
							<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
						</svg> Remove</a>
					</div>

				</div>
				<div class="cart_price hidden-xs-down">
					<div class="price"> <span>Subtotal</span> <span class="special-price">{{ make_price($val['TotPrice'],true) }}</span></div>
					<div class="qty-box">
						<button type="button" class="left-qty btn-number" data-type="minus" data-field="prod_{{$key}}">-</button>
						<label for="prod_{{$key}}_desk" class="dnone">Quantity box</label>
						<input type="text" aria-label="Quantity box" name="{{$key}}" id="prod_{{$key}}_desk" data-field="prod_{{$key}}"  data-productid="{{ $val['product_id'] }}" class="form-control input-number prod_{{$key}}" value="{{ $val['Qty'] }}" min="1" max="10">
						<button type="button" class="right-qty btn-number" data-type="plus" data-field="prod_{{$key}}">+</button>
					</div>
					{{-- <div class="available">
						<svg class="svg_star" aria-hidden="true" role="img" width="12" height="12" loading="lazy">
							<use href="#svg_star" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_star"></use>
						</svg>
						<span>Only 1 Available</span>
					</div> --}} 
				</div>
			</div>
		</div>
	
		@php
		$prod_id .= ",".$val['product_id'];
		@endphp
	@endforeach
@endif
@php
	$prod_id = substr($prod_id,1);
@endphp
<input type="hidden" name="prodid_list" id="prodid_list" value="{{ $prod_id }}">