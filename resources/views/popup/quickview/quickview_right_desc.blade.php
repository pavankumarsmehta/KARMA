<div class="dtl-top hidden-sm-down">
              <h4 class="dtl-top-name">{{$Product['product_name']}}</h4>
              @if(isset($Product['short_description']) && !empty($Product['short_description']))
                    <div class="dtl-top-short-desc" tabindex="0"> {{$Product['short_description']}} </div>
                @endif
              <div class="dtl-top-rs">
                {{--<div class="dtl-top-rview"><a href="#"><img src="images/star.png" alt="" class="vam" width="72" height="13"> 901 Reviews</a></div>--}}
                <div class="yotpo bottomLine dtl-top-rview" data-yotpo-product-id="{{$Product['sku']}}"></div>
                <a href="#" class="dtl-top-share">
                  <svg class="svg_share" width="14px" height="15px" aria-hidden="true" role="img">
                    <use href="#svg_share" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_share"></use>
                  </svg> Share
                </a>
              </div>
            </div>
            {{-- <div class="dtl-size">50ML / 1.7 US FL OZ</div> --}}
            <div class="dtl-price">
             {{-- <span class="old-price">$295</span>
              <span class="special-price">$195</span>
              <span class="sale-price">You Save $100</span> --}}

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
            {{--<div class="dtl-affirm">Starting at $51/mo with &nbsp;<img src="images/affirm.png" class="vat" alt="Affirm" width="38" height="19">&nbsp; <a href="#" class="tdu">Prequalify now</a></div> --}}
            <span class="dtl-sku" tabindex="0">SKU: {{$Product['sku']}} </span>
            @if($Product['current_stock'] > 0)
                <span class="dtl-stock instock" tabindex="0">in stock</span>
            @else
                <span class="dtl-stock outofstock" tabindex="0">Out Of Stock </span>
            @endif
            <div class="swatch-options">
                <div class="swatch-attribute swatch-attribute-flavour-js">
                    @include('popup.quickview.productdetail_right_variant_flavour')
                </div>
                <div class="swatch-attribute swatch-attribute-size-js">
                    @include('popup.quickview.productdetail_right_variant_size')
                </div>
                <div class="swatch-attribute swatch-attribute-pack-size-js">
                    @include('popup.quickview.productdetail_right_variant_pack_size')
                </div>
        	</div>
            {{--  <div class="swatch-attribute">
                <div class="swatch-attribute-label"><span>Size:</span> <strong>2 oz/ 60 mL</strong></div>
                <div class="swatch-attribute-options swatch-size">
                  <a href="#">
                    <picture><img src="images/30x30.webp" alt="" width="30" height="30" loading="lazy"></picture>
                    <span>1 oz/ 30 mL</span>
                  </a>
                  <a href="#">
                    <picture><img src="images/30x30.webp" alt="" width="30" height="30" loading="lazy"></picture>
                    <span>2 oz/ 60 mL</span>
                  </a>
                  <a href="#">
                    <picture><img src="images/30x30.webp" alt="" width="30" height="30" loading="lazy"></picture>
                    <span>2 oz/ 60 mL</span>
                  </a>
                  <a href="#">
                    <picture><img src="images/30x30.webp" alt="" width="30" height="30" loading="lazy"></picture>
                    <span>10 oz/ 295 mL</span>
                  </a>
                  <a href="#">
                    <picture><img src="images/30x30.webp" alt="" width="30" height="30" loading="lazy"></picture>
                    <span>6.7 oz/ 200 mL</span>
                  </a>
                </div>
              </div>
            </div>--}}
            <div class="dtl-cart-sec">
		<p class="buy_now_error hidden-lg-down"></p>
    @if($Product['current_stock'] > 0)
		<div class="row row5">
			<div class="col-xs-2"><label for="prodqty" class="dnone" tabindex="0">Quantity</label><input type="text" aria-label="Quantity" name="prodqty" id="prodqty" class="form-control qty-input" value="1" aria-labelledby="Quantity" /></div>
			<div class="col-xs-10">
            <div class="pb-2"><button type="button" class="buynow btn btn-block" data-product="{{$Product['product_id']}}" data-page="product_detail" title="Buy Now" aria-label="Buy Now">Buy Now</button></div>
                <button type="button" class="addtocart btn btn-block btn-twilight-border mb-2" data-product="{{$Product['product_id']}}" title="Add To Bag" aria-label="Add To Bag">Add To Bag</button>
                <div class="tac"><a href="{{$Product['product_url']}}" tabindex="0" class="linksbb" aria-label="View More Details"><strong>View More Details</strong></a></div>
			</div>
		</div>
	</div>
  @else
  <div class="row row5">
			<div class="col-xs-10">
                <button type="button" class="btn btn-block btn-twilight-border mb-2 disabled" data-product="{{$Product['product_id']}}" title="Add To Bag" aria-label="Add To Bag">Out Of Stock</button>
                <div class="tac"><a href="{{$Product['product_url']}}" tabindex="0" class="linksbb" aria-label="View More Details"><strong>View More Details</strong></a></div>
			</div>
		</div>
	</div>
  @endif	
           {{--<div class="row row5">
              <div class="col-xs-2"><input type="text" class="form-control qty-input" value="1"></div>
              <div class="col-xs-10">
                <div class="pb-2"><a href="#" class="btn btn-block">Buy Now</a></div>
                <a href="javascript:void(0)" class="btn btn-block btn-twilight-border mb-2">Add To Bag</a>
                <div class="tac"><a href="javascript:void(0)" class="linksbb"><strong>View More Details</strong></a></div>
              </div>
            </div>    --}}
            <ul class="dtl-service">
              <li>
                <span class="icon">
                  <svg class="svg_delivery_truck" aria-hidden="true" role="img" width="22" height="22">
                    <use href="#svg_delivery_truck" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_delivery_truck"></use>
                  </svg>
                </span>
                <strong>Free Shipping</strong>
                <p>{{$Product['shipping_text']}}</p>
                
              </li>
              <li>
                <span class="icon">
                  <svg class="svg_returns" aria-hidden="true" role="img" width="22" height="22">
                    <use href="#svg_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_returns"></use>
                  </svg>
                </span>
                <strong>Easy Returns</strong>
                <p>Within 7 Days after receiving order. </p>
              </li>
              <li>
                <span class="icon">
                  <svg class="svg_guarantee" aria-hidden="true" role="img" width="22" height="22">
                    <use href="#svg_guarantee" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_guarantee"></use>
                  </svg>
                </span>
                <strong>Price Match Guarantee &nbsp;{{-- <a href="#"><svg class="svg_quchanmark" aria-hidden="true" role="img" width="15" height="15">
                    <use href="#svg_quchanmark" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_quchanmark"></use>
                  </svg></a> --}}</strong>
              </li>
            </ul>