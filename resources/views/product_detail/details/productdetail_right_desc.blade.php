	<div class="dtl-top hidden-sm-down">
		<h1 class="dtl-top-name" tabindex="0">{{$Product['product_name']}}</h1>
		@if(isset($Product['short_description']) && !empty($Product['short_description']))
		<div class="dtl-short-desc" tabindex="0"> {{$Product['short_description']}} </div>
	@endif
		<div class="dtl-top-rs">
			{{--<div class="dtl-top-rview"><a href="javascript:void(0)" rel="nofollow" title="Reviews" tabindex="0"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="Review Stars" title="Review Stars" class="vam" width="72" height="13" loading="lazy"/> 901 Reviews</a></div>--}}
			<div class="yotpo bottomLine" data-yotpo-product-id="{{$Product['sku']}}"></div>

			<a href="javascript:void(0)" tabindex="0" rel="nofollow" class="dtl-top-share emailafriend" id="emailafriend_share" data-pid="{{$Product['product_id']}}" title="Email a Friend">
				<svg class="svg_share" width="14px" height="15px" aria-hidden="true" role="img" loading="lazy">
					<use href="#svg_share" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_share"></use>
				</svg>
				Share
			</a>
		</div>
	</div>	
	<div class="dtl-sku-stock">
		<span class="dtl-sku" tabindex="0">SKU: {{$Product['sku']}} </span>
		@if($Product['current_stock'] > 0)
		<span class="dtl-stock instock" tabindex="0">in stock</span>
		@else
		<span class="dtl-stock outofstock" tabindex="0">Out Of Stock </span>
		@endif
	</div>
	@if($Product['size'] != "")
		<!--<div class="dtl-size" tabindex="0">Size: {{$Product['size']}} </div>-->
	@endif
	<div class="dtl-price">
	@if(!isset($Product['deal_description']))
		@if(isset($Product['sale_price']) && $Product['sale_price'] > 0 && $Product['on_sale'] == 'Yes')
		@if($Product['retail_price'] == $Product['our_price'])
				<span class="special-price" tabindex="0">{{$Product['our_price_disp']}}</span>
			@else
			<span class="old-price" tabindex="0">{{$Product['retail_price_disp']}}</span>
				<span class="special-price" tabindex="0">{{$Product['our_price_disp']}}</span>
				<span class="sale-price" tabindex="0">You Save {{$CurencySymbol}}{{Make_Price($Product['retail_price'] - $Product['our_price'])}}</span>
			@endif
		@elseif(isset($Product['retail_price']) && $Product['retail_price'] > 0)
			@if($Product['retail_price'] == $Product['our_price'])
				<span class="special-price" tabindex="0">{{$Product['our_price_disp']}}</span>
			@else
				<span class="old-price" tabindex="0">{{$Product['retail_price_disp']}}</span>
				<span class="special-price" tabindex="0">{{$Product['our_price_disp']}}</span>
				<span class="sale-price" tabindex="0">You Save {{$CurencySymbol}}{{Make_Price($Product['retail_price'] - $Product['our_price'])}}</span>
			@endif	
		@endif
	@else
		<span class="special-price" tabindex="0">{{$Product['our_price_disp']}}</span>
	@endif
	</div>
	{{--<div class="dtl-affirm" tabindex="0">Starting at $51/mo with &nbsp;<img src="{{ config('const.SITE_URL') }}/images/affirm.png" class="vat" alt="Affirm" width="38" height="19" loading="lazy"/>&nbsp; <a href="javascript:void(0)" rel="nofollow" class="tdu" title="Prequalify now">Prequalify now</a></div>--}}
	

	<div class="swatch-options">
		<div class="swatch-attribute swatch-attribute-flavour-js">
			@include('product_detail.details.productdetail_right_variant_flavour')
		</div>
		<div class="swatch-attribute swatch-attribute-size-js">
			@include('product_detail.details.productdetail_right_variant_size')
		</div>
		<div class="swatch-attribute swatch-attribute-pack-size-js">
			@include('product_detail.details.productdetail_right_variant_pack_size')
		</div>
		
	</div>
	@if($Product['current_stock'] > 0)
	<div class="dtl-cart-sec">
		<p class="buy_now_error hidden-lg-down"></p>
		<div class="row row5">
			<div class="col-xs-2"><label for="prodqty" class="dnone" tabindex="0">Quantity</label><input type="text" aria-label="Quantity" name="prodqty" id="prodqty" class="form-control qty-input" value="1" aria-labelledby="Quantity" /></div>
			<div class="col-xs-10">
				<button type="button" class="buynow btn btn-block" data-product="{{$Product['product_id']}}" data-page="product_detail" title="Buy Now" aria-label="Buy Now">Buy Now</button>
			</div>
		</div>
	</div>
	@endif	
	<div class="row row5 pt-2">
		<div class="col-md-10 {{ $Product['current_stock'] > 0 ? 'col-md-offset-2': '' }} col-xs-12">
			{{-- <button type="button" class="addtocart btn btn-block btn-twilight-border" data-product="{{$Product['product_id']}}" title="Add To Bag" aria-label="Add To Bag">Add To Bag</button> --}}
			@if($Product['current_stock'] > 0)
			<button type="button" class="addtocart btn btn-block btn-twilight-border" data-product="{{$Product['product_id']}}" title="Add To Bag" aria-label="Add To Bag">Add To Bag</button>
		@else
			<button type="button" class="btn btn-block btn-twilight-border disabled" data-product="{{$Product['product_id']}}" title="Add To Bag" aria-label="Add To Bag">Out of Stock</button>	
			
		@endif	
		</div>
	</div>
	<ul class="dtl-service">
		<li tabindex="0">
			<span class="icon">
				<svg class="svg_delivery_truck" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
					<use href="#svg_delivery_truck" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_delivery_truck"></use>
				</svg>
			</span>
			<strong>Free Shipping</strong>
			<p>{{$Product['shipping_text']}}</p>
		</li>
		<li tabindex="0">
			<span class="icon">
				<svg class="svg_returns" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
					<use href="#svg_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_returns"></use>
				</svg>
			</span>
			<strong>Easy Returns</strong>
			<p>Within 7 Days after receiving order. </p>
		</li>
		<li tabindex="0">
			<span class="icon">
				<svg class="svg_guarantee" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
					<use href="#svg_guarantee" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_guarantee"></use>
				</svg>
			</span>
			<strong>
				Price Match Guarantee &nbsp;
				{{-- <a href="javascript:void(0)" rel="nofollow" aria-label="Questionmark" title="Questionmark">
					<svg class="svg_quchanmark" aria-hidden="true" role="img" width="15" height="15" loading="lazy">
						<use href="#svg_quchanmark" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_quchanmark"></use>
					</svg>
				</a> --}}
			</strong>
		</li>
	</ul>
	<div class="accordion" id="accordion">
		@if(isset($Product['product_description']) && $Product['product_description'] != "")
			<div class="accordion_title" tabindex="0">Description</div>
		@endif
		<div class="accordion_content" tabindex="0">
			
			@if(!is_html($Product['product_description']))
			<p>{!! $Product['product_description'] !!}</p>
		@else
			{!! $Product['product_description'] !!}
		@endif
		</div>
		
		{{-- <div class="accordion_title" tabindex="0">Overview</div>
		<div class="accordion_content" tabindex="0" style="display: none;" >
			<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown .</p>
			<h6 class="pb-1 pt-1 ttu f14">Lorem Ipsum</h6>
			<ul class="unorder-list">
				<li>Lorem Ipsum</li>
				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
				<li>Lorem Ipsum</li>
			</ul>
			<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown .</p>
		</div> --}}
		

		@if(isset($Product['ingredients']) && $Product['ingredients'] != "")
		<div class="accordion_title" tabindex="0">Ingredients</div>
		<div class="accordion_content" tabindex="0" style="display: none;">
			@if(!is_html($Product['ingredients']))
				<p style="max-height: 100px; overflow: hidden;" id="content">{!! $Product['ingredients'] !!}</p>
			@else
				{!! $Product['ingredients'] !!}
			@endif
			@if(isset($Product['ingredients_pdf']) && $Product['ingredients_pdf'] != "")
				<br><br>
				<a href="{{$Product['ingredients_pdf']}}" class="linksbb" title="Click here to download Ingredients" target="_blank">Click here to download Ingredients PDF</a>
			@endif	
			<a href="javascript:void(0);" class="linksbb" id="read-more-btn" onclick="toggleContent()" title="Read More">Read More</a>
		</div>
		@endif
		
		
		@if(isset($Product['uses']) && $Product['uses'] != "")
		<dv class="accordion_title" tabindex="0">How to Use</dv>
		<div class="accordion_content" tabindex="0" style="display: none;">

		<p>{{preg_replace('/\\\\\\\\\\\\/', '', $Product['uses'])}}</p>
				{{-- <h6 class="pb-1 ttu f14">Lorem Ipsum</h6>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p> --}}
		</div>
		@endif
	</div>