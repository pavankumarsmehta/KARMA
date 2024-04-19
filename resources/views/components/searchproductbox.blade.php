@foreach($prodData  as $key =>  $prod)
@if(isset($pageType) && !empty($pageType) && $pageType=='Listing')
<li>
@endif 

<div class="product clearfix">
  <div class="product_thumb">
    <a href="{{$prod['product_url']}}" title="{{$prod['product_name']}}" tabindex="0" aria-label="{{$prod['product_name']}}">
      <picture><img src="{{$prod['image_url']}}" alt="{{$prod['product_name']}}" width="365" height="365" loading="lazy" @if(isset($prod['first_extra_image']) && $prod['first_extra_image'] != "") data-extra-image="{{ $prod['first_extra_image'] }}" @endif /></picture>
    </a>

    {{-- <a href="javascript:void(0)" tabindex="0" title="Add to wishlist" data-section-name="{{(isset($sectionName) && !empty($sectionName)) ? $sectionName : ''}}"
     data-category-id="{{(isset($prod['category_id']) && !empty($prod['category_id'])) ? $prod['category_id'] : ''}}" 
     data-brand-id="{{(isset($prod['brand_id']) && !empty($prod['brand_id'])) ? $prod['brand_id'] : ''}}" 
     data-productid="{{$prod['product_id']}}"  class="wishlist displaypopupboxwishlist   {{ (auth()->user() && (isset($prod['PrdWishArr']) && !empty($prod['PrdWishArr']))) ? ((!empty(auth()->user()->customer_id  === $prod['PrdWishArr']['customer_id'])) && (!empty($prod['PrdWishArr']['products_id'] == $prod['product_id']))) ? 'active':'':''}}
"> --}}

<a href="{{config('const.SITE_URL')}}/#wishlist-{{$prod['product_id']}}" onClick="return false;" tabindex="0" title="Add to wishlist" data-section-name="{{(isset($sectionName) && !empty($sectionName)) ? $sectionName : ''}}"
     data-category-id="{{(isset($prod['category_id']) && !empty($prod['category_id'])) ? $prod['category_id'] : ''}}" 
     data-brand-id="{{(isset($prod['brand_id']) && !empty($prod['brand_id'])) ? $prod['brand_id'] : ''}}" 
     data-productid="{{$prod['product_id']}}"  class="wishlist displaypopupboxwishlist   {{ (auth()->user() && (isset($prod['PrdWishArr']) && !empty($prod['PrdWishArr']))) ? ((!empty(auth()->user()->customer_id  === $prod['PrdWishArr']['customer_id'])) && (!empty($prod['PrdWishArr']['products_id'] == $prod['product_id']))) ? 'active':'':''}}
">

      <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg" loading="lazy">
        <path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokeWidth="1" strokeLinejoin="round" />
      </svg> 
    </a>
    <a href="javascript:void(0);" class="quickview hidden-md-down" tabindex="0" title="Quick View" data-productId="{{$prod['product_id']}}">
        <svg width="21" height="21" viewBox="0 0 469.333 469.333" fill="none" xmlns="http://www.w3.org/2000/svg" loading="lazy">
            <path d="M234.667,170.667c-35.307,0-64,28.693-64,64s28.693,64,64,64s64-28.693,64-64S269.973,170.667,234.667,170.667z" strokeWidth="1"/>
            <path d="M234.667,74.667C128,74.667,36.907,141.013,0,234.667c36.907,93.653,128,160,234.667,160
				c106.773,0,197.76-66.347,234.667-160C432.427,141.013,341.44,74.667,234.667,74.667z M234.667,341.333
				c-58.88,0-106.667-47.787-106.667-106.667S175.787,128,234.667,128s106.667,47.787,106.667,106.667
				S293.547,341.333,234.667,341.333z" strokeWidth="1"/>
        </svg>

    </a>
  </div>
  @if(!empty($prod['sku']))             
    <div class="product_sku" tabindex="-1">{{$prod['sku']}}</div>
  @endif
  <div class="product_name"><a href="{{$prod['product_url']}}" title="{{$prod['product_name']}}" tabindex="0">{{$prod['product_name']}}</a></div>
  <div class="product_price" tabindex="-1">
    @if(($prod['price_arr']['retail_price'] > 0) && !isset($prod['deal_description']) && ($prod['price_arr']['retail_price']!=$prod['price_arr']['our_price']))
      <span class="old-price">{{$prod['price_arr']['retail_price_disp']}}</span>
    @endif
    <span class="regular-price" tabindex="-1">{{$prod['price_arr']['our_price_disp']}}</span>
  </div>
  <div class="product_review" tabindex="-1"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="" width="72" height="13" loading="lazy"/>(110)</div>
</div>
@if(isset($pageType) && !empty($pageType) && $pageType=='Listing')
</li>
@endif
@endforeach