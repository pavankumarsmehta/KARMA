<?php 
		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
		}
		
	?>
<div class="sb-slidebar sb-right sb-width-wide sb-style-overlay cart-right-slid" id="cart-open">
	<div class="sp-hd" tabindex="0">
		Your Bag
		{{-- <a href="{{config('const.SITE_URL')}}/#close" onClick="return sb_close_cart();" rel="noindex nofollow" tabindex="0" aria-label="Close" class="sb-close sb-cart-close"> --}}

		<a href="{{config('const.SITE_URL')}}/#close"  rel="noindex nofollow" onClick="return sb_close_cart();" tabindex="0" aria-label="Close" class="sb-close sb-cart-close">
			<svg class="svg_close vam" aria-hidden="true" role="img" width="25" height="25" loading="lazy">
				<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
			</svg>
		</a>
	</div>
	<?php 
	$CartDetails = Session::get('ShoppingCart'); 
		$getAllCurrencyObj = getCurrencyArray();
		
		$currentSelectedCurrencyCode = Session::get('currency_code');
		if(isset($getAllCurrencyObj[$currentSelectedCurrencyCode])){
			$getcurrentSelectedcCurrencyObj = $getAllCurrencyObj[$currentSelectedCurrencyCode];
			$curencySymbol = $getcurrentSelectedcCurrencyObj['symbol_left'];
			$curencyvalue = $getcurrentSelectedcCurrencyObj['value'];
		}
		
	?>
	<div class="sp-content <?php if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0){ }else{?>sp-content-empty<?php }?>" id="shopcart">
		<div class="sp-inner sp-basket">
			<?php $CartDetails = Session::get('ShoppingCart'); ?>
			@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
				<!--<div class="tar f14"> 
					<a href="javascript:void(0);" id="clear-bag" class="more-link">Clear Your Cart</a> 
				</div>-->
			@endif
			@if(Session::has('CartErrors') && count(Session::get('CartErrors')) > 0)
				<div style="padding:8px;">
				@foreach(Session::get('CartErrors') as $ekey => $CartError)
					<x-message :attr="[
					'classname' => 'alert alert-danger', 
					'message' => $CartError ]"/>
				@endforeach
			</div>
			@endif
			@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
			<ol class="Cart-items">
				@foreach($CartDetails['Cart'] as $key => $CartItem)
				<?php 
				//dd($CartDetails);
				//echo $CartItem['retail_price_disp']; exit; ?>
				<li>
					<div class="product">
						<a href="{{$CartItem['product_url']}}" title="{{$CartItem['ProductName']}}" aria-label="{{$CartItem['ProductName']}}" tabindex="0" class="product_thumb">
							@if(isset($CartItem['Image']) && $CartItem['Image'] != "")
							<picture><img src="{{$CartItem['Image']}}" alt="{{$CartItem['ProductName']}}" width="75" height="75" loading="lazy" /></picture>
							@endif
						</a>
						<div class="product_name"><a href="{{$CartItem['product_url']}}" title="{{$CartItem['ProductName']}}" aria-label="{{$CartItem['ProductName']}}">{{$CartItem['ProductName']}}</a></div>
						<div class="product-size">{{$CartItem['SKU']}}</div>
						<div class="product_qty">Qty: {{$CartItem['Qty']}}</div>
						
						@if (isset($CartItem['flavour']) && $CartItem['flavour'] != "")
							<div class="product_qty">@if(isset($CartItem['attribute_3']) && $CartItem['attribute_3'] != "") <b>{{$CartItem['attribute_3']}}:</b> @endif {{$CartItem['flavour']}}</div>
						@endif
						
						@if (isset($CartItem['size_dimension']) && $CartItem['size_dimension'] != "")
							<div class="product_qty">@if(isset($CartItem['attribute_1']) && $CartItem['attribute_1'] != "") <b>{{$CartItem['attribute_1']}}:</b> @endif {{$CartItem['size_dimension']}}</div>
						@endif
						
						@if (isset($CartItem['pack_size']) && $CartItem['pack_size'] != "")
							<div class="product_qty">@if(isset($CartItem['attribute_2']) && $CartItem['attribute_2'] != "") <b>{{$CartItem['attribute_2']}}:</b> @endif {{$CartItem['pack_size']}}</div>
						@endif
						
						<?php //dd($CartItem); ?>
						<div class="product_price">
							@if(!isset($CartItem['deal_description']))
								@if(isset($CartItem['sale_price']) && $CartItem['sale_price'] > 0 && $CartItem['on_sale'] == 'Yes')
								<span class="old-price">{{make_price($CartItem['retail_price'],true)}}</span>
								<span class="special-price">
									{{make_price($CartItem['sale_price'],true)}}
								</span>
								@else
									@if(isset($CartItem['retail_price']))
									<span class="old-price">{{make_price($CartItem['retail_price'],true)}}</span>
									<span class="special-price">
										{{make_price($CartItem['our_price'],true)}}
									</span>
									@endif
								@endif
							@else
							<span class="special-price">
								{{make_price($CartItem['our_price'],true)}}
							</span>
							@endif
							{{-- <span class="old-price">$139.99</span> --}}
							<a href="javascript:void(0);" class="remove itemremove" data-index="{{ $key }}" title="Remove" aria-label="Remove Item">
								<svg class="svg_close" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
									<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
								</svg>
							</a>
						</div>
					</div>
				</li>
				@endforeach
			</ol>
			@else
			<ol class="items empty-items">
				<li>
					<p tabindex="0">Your Bag is Empty!</p>
					<p><a href="{{config('const.SITE_URL')}}/#continue_shopping" onClick="return sb_close_cart();" rel="noindex nofollow" tabindex="0" aria-label="Continue Shopping"  tabindex="0" rel="nofollow" title="Continue Shopping" class="linksbb" aria-label="Continue">Continue Shopping</a></p>
				</li>
			</ol>
			@endif
			@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0)
			<div class="sp-bottom">
				<p><span class="price-text dflex aic jcb f14 f600">CART SUBTOTAL: <strong class="text_c3">@if(isset($CartDetails['SubTotal'])) {{make_price($CartDetails['SubTotal'],true)}} @else make_price('0.00',true) @endif</strong></span></p>
				<p>
					{{-- <a href="{{ route('checkout') }}" rel="nofollow" class="btn btn-success btn-block" title="Checkout" aria-label="Checkout">Checkout</a> --}}
					<button type="button" onClick="window.location.href='{{ route('checkout') }}'" class="btn btn-success btn-block" title="Checkout" aria-label="Checkout">Checkout</button>
				</p>
				<p class="mb-0"><a href="{{url('cart')}}" rel="nofollow" class="linksbb" title="View Your Cart" aria-label="View Your Cart"><strong>View Your Cart</strong></a></p>
			</div>
			@endif
		</div>
	</div>
</div>