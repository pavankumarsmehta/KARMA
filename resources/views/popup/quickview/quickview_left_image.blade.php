 <div class="dtl-top hidden-md-up">
              <h4 class="dtl-top-name" tabindex="0">{{$Product['product_name']}}</h4>
              <div class="dtl-top-rs">
                <!-- <div class="dtl-top-rview"><a href="#"><img src="images/star.png" alt="" class="vam" width="72" height="13"> 901 Reviews</a></div> -->
                <div class="yotpo bottomLine dtl-top-rview" data-yotpo-product-id="{{$Product['sku']}}"></div>
                <a href="javascript:void(0)" tabindex="0" rel="nofollow" class="dtl-top-share emailafriend" id="emailafriend_share" data-pid="2970" title="Email a Friend">
				<svg class="svg_share" width="14px" height="15px" aria-hidden="true" role="img" loading="lazy">
					<use href="#svg_share" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_share"></use>
				</svg>
				Share
			</a>
              </div>
              {{-- <div class="dtl-top-rs">
			<div class="dtl-top-rview"><a href="javascript:void(0)" rel="nofollow" title="Reviews" tabindex="0"><img src="{{ config('const.SITE_URL') }}/images/star.png" alt="" class="vam" width="72" height="13" loading="lazy" /> 901 Reviews</a></div>
			<a href="javascript:void(0)" rel="nofollow" class="dtl-top-share emailafriend" id="emailafriend" data-pid="2970" title="Email a Friend" tabindex="0">
				<svg class="svg_share" width="14px" height="15px" aria-hidden="true" role="img" loading="lazy">
					<use href="#svg_share" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_share"></use>
				</svg>
				Share
			</a>
		</div> --}}
            </div>        
            <div class="dtl-lv">
    <div class="por">
			@if(isset($Product['badge']) && $Product['badge'] != "")
				<span class="p-label p-topseller">{{$Product['badge']}}</span>
			@endif
			{{-- <a href="javascript:void(0)" tabindex="0" data-productid="{{$Product['product_id']}}" rel="nofollow" title="Add to wishlist" class="displaypopupboxwishlist wishlist @if($Product['product_id'] == $Wishproducts_id) active @endif">
				<svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg" loading="lazy">
					<path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokeWidth="1" strokeLinejoin="round" />
				</svg>
			</a> --}}
			{{--
			<a class="link-zoom" title="Zoom" tabindex="0">
				<svg class="svg-zoom" aria-hidden="true" role="img" width="37" height="37" loading="lazy">
					<use href="#svg-zoom" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-zoom"></use>
				</svg>
			</a>
			--}}
     {{-- <div class="dtl-thumb dtl-thumb-slider-popup slick-initialized slick-slider">
        <div class="slick-prev slick-arrow slick-disabled" aria-disabled="true" style="">
          <svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none">
            <use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow">
            </use>
          </svg>
        </div>
        <div class="slick-list draggable">
          <div class="slick-track" style="opacity: 1; width: 0px; transform: translate3d(0px, 0px, 0px);">
            <div class="slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 0px;">
              <div>
                <div class="dtl-thumb-item" style="width: 100%; display: inline-block;">
                  <span class="p-label p-topseller">Top Seller</span>
                  <a href="javascript:void(0)" class="wishlist" tabindex="0">
                    <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokewidth="1" strokelinejoin="round"></path>
                    </svg>
                  </a>
                  <a href="#" class="thumb" tabindex="0">
                    <picture>
                      <img src="images/750x795.webp" alt="" width="750" height="795" loading="lazy">
                    </picture>
                  </a>
                </div>
              </div>
            </div>
            <div class="slick-slide" data-slick-index="1" aria-hidden="true" style="width: 0px;" tabindex="-1">
              <div>
                <div class="dtl-thumb-item" style="width: 100%; display: inline-block;">
                  <span class="p-label p-topseller">Top Seller</span>
                  <a href="javascript:void(0)" class="wishlist" tabindex="-1">
                    <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokewidth="1" strokelinejoin="round"></path>
                    </svg>
                  </a>
                  <a href="#" class="thumb" tabindex="-1">
                    <picture>
                      <img src="images/750x795.webp" alt="" width="750" height="795" loading="lazy">
                    </picture>
                  </a>
                </div>
              </div>
            </div>
            <div class="slick-slide" data-slick-index="2" aria-hidden="true" style="width: 0px;" tabindex="-1">
              <div>
                <div class="dtl-thumb-item" style="width: 100%; display: inline-block;">
                  <span class="p-label p-topseller">Top Seller</span>
                  <a href="javascript:void(0)" class="wishlist" tabindex="-1">
                    <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokewidth="1" strokelinejoin="round"></path>
                    </svg>
                  </a>
                  <a href="#" class="thumb" tabindex="-1">
                    <picture>
                      <img src="images/750x795.webp" alt="" width="750" height="795" loading="lazy">
                    </picture>
                  </a>
                </div>
              </div>
            </div>
            <div class="slick-slide" data-slick-index="3" aria-hidden="true" style="width: 0px;" tabindex="-1">
              <div>
                <div class="dtl-thumb-item" style="width: 100%; display: inline-block;">
                  <span class="p-label p-topseller">Top Seller</span>
                  <a href="javascript:void(0)" class="wishlist" tabindex="-1">
                    <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokewidth="1" strokelinejoin="round"></path>
                    </svg>
                  </a>
                  <a href="#" class="thumb" tabindex="-1">
                    <picture>
                      <img src="images/750x795.webp" alt="" width="750" height="795" loading="lazy">
                    </picture>
                  </a>
                </div>
              </div>
            </div>
            <div class="slick-slide" data-slick-index="4" aria-hidden="true" style="width: 0px;" tabindex="-1">
              <div>
                <div class="dtl-thumb-item" style="width: 100%; display: inline-block;">
                  <span class="p-label p-topseller">Top Seller</span>
                  <a href="javascript:void(0)" class="wishlist" tabindex="-1">
                    <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokewidth="1" strokelinejoin="round"></path>
                    </svg>
                  </a>
                  <a href="#" class="thumb" tabindex="-1">
                    <picture>
                      <img src="images/750x795.webp" alt="" width="750" height="795" loading="lazy">
                    </picture>
                  </a>
                </div>
              </div>
            </div>
            <div class="slick-slide" data-slick-index="5" aria-hidden="true" style="width: 0px;" tabindex="-1">
              <div>
                <div class="dtl-thumb-item" style="width: 100%; display: inline-block;">
                  <span class="p-label p-topseller">Top Seller</span>
                  <a href="javascript:void(0)" class="wishlist" tabindex="-1">
                    <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.82693 3.24001C10.2086 3.5878 10.7923 3.5878 11.174 3.24001C12.2616 2.24895 13.4219 1.50391 14.4965 1.50391C15.8043 1.50391 17.0455 2.01805 17.9985 2.96516C19.9655 4.94321 19.9621 8.00504 18.0004 9.9668L10.5854 17.3818C10.551 17.4162 10.5191 17.453 10.4901 17.492C10.4888 17.4938 10.4875 17.4954 10.4863 17.4967C10.4862 17.4967 10.4862 17.4967 10.4861 17.4967C10.4434 17.4246 10.3919 17.3581 10.3326 17.2988L3.00058 9.9668L3.00043 9.96665C1.03829 8.00535 1.03502 4.94267 2.99839 2.9692C3.95576 2.01771 5.19709 1.50391 6.50447 1.50391C7.57992 1.50391 8.73921 2.24884 9.82693 3.24001Z" strokewidth="1" strokelinejoin="round"></path>
                    </svg>
                  </a>
                  <a href="#" class="thumb" tabindex="-1">
                    <picture>
                      <img src="images/750x795.webp" alt="" width="750" height="795" loading="lazy">
                    </picture>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="slick-next slick-arrow" style="" aria-disabled="false">
          <svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none">
            <use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow">
            </use>
          </svg>
        </div>
      </div> --}}
      @if(!empty($arr_extra_image))
			<div class="dtl-thumb pswp-gallery my-gallery  dtl-thumb-slider-popup" id="">
				@foreach($arr_extra_image as $eiKey => $eiValue)
				<?php 
				//dd($eiValue['extra_zoom_url']);
				//$data = @getimagesize($eiValue['extra_zoom_url']); 
				//dd($eiValue);
				?>
				@if($eiValue['video_image_type'] == 'mp4')
					@if (isset($eiValue['extra_large_url']) && strpos($eiValue['extra_large_url'], 'vimeo') !== false)
						<div class="embed-wrapper"><div class="embed-wrapper-inner"><div class="embed-responsive embed-responsive-16by9"> <iframe class="embed-responsive-item" src="{{$eiValue['extra_large_url']}}" frameborder="0" data-video-url="" allow="autoplay; fullscreen"></iframe></div></div></div>
					@else
						<div class="embed-wrapper"><div class="embed-wrapper-inner"><div class="embed-responsive embed-responsive-16by9"> <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$eiValue['extra_large_url']}}?autoplay=1&rel=0" allowfullscreen></iframe> </div></div></div>
					@endif
				@else
					<div class="dtl-thumb-item">
						<a href="{{ config('const.SITE_URL') }}/#quickview-image" onClick="return false;" rel="noindex nofollow" tabindex="0"  title="{{$Product['product_name']}}" class="thumb img-wrapper" data-pswp-width="{{$data[0] ?? 1500}}" data-pswp-height="{{$data[1] ?? 1500}}" target="_blank" aria-label="Product images" style="cursor: unset;">
							<picture><img src="{{ $eiValue['extra_large_url'] }}" alt="{{$Product['product_name']}}" title="{{$Product['product_name']}}" width="750" height="795" loading="lazy" /></picture>
						</a>					
					</div>
				@endif
				@endforeach
			</div>
			@endif
    </div>
   {{--  <div class="dtl-ex dtl-ex-slider-popup slick-initialized slick-slider slick-vertical">
      <div class="slick-prev slick-arrow slick-disabled" aria-disabled="true" style="">
        <svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none">
          <use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow">
          </use>
        </svg>
      </div>
      <div class="slick-list draggable" style="height: 40px;">
        <div class="slick-track" style="opacity: 1; height: 48px; transform: translate3d(0px, 0px, 0px);">
          <div class="slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 0px;">
            <div>
              <div class="dtl-ex-item" style="width: 100%; display: inline-block;">
                <picture>
                  <img src="images/75x75.webp" alt="" width="75" height="75" loading="lazy">
                </picture>
              </div>
            </div>
          </div>
          <div class="slick-slide slick-active" data-slick-index="1" aria-hidden="false" style="width: 0px;">
            <div>
              <div class="dtl-ex-item" style="width: 100%; display: inline-block;">
                <picture>
                  <img src="images/75x75.webp" alt="" width="75" height="75" loading="lazy">
                </picture>
              </div>
            </div>
          </div>
          <div class="slick-slide slick-active" data-slick-index="2" aria-hidden="false" style="width: 0px;">
            <div>
              <div class="dtl-ex-item" style="width: 100%; display: inline-block;">
                <picture>
                  <img src="images/75x75.webp" alt="" width="75" height="75" loading="lazy">
                </picture>
              </div>
            </div>
          </div>
          <div class="slick-slide slick-active" data-slick-index="3" aria-hidden="false" style="width: 0px;">
            <div>
              <div class="dtl-ex-item" style="width: 100%; display: inline-block;">
                <picture>
                  <img src="images/75x75.webp" alt="" width="75" height="75" loading="lazy">
                </picture>
              </div>
            </div>
          </div>
          <div class="slick-slide slick-active" data-slick-index="4" aria-hidden="false" style="width: 0px;">
            <div>
              <div class="dtl-ex-item dtl-ex-play" style="width: 100%; display: inline-block;">
                <svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img">
                  <use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play">
                  </use>
                </svg>
              </div>
            </div>
          </div>
          <div class="slick-slide" data-slick-index="5" aria-hidden="true" style="width: 0px;" tabindex="-1">
            <div>
              <div class="dtl-ex-item" style="width: 100%; display: inline-block;">
                <picture>
                  <img src="images/75x75.webp" alt="" width="75" height="75" loading="lazy">
                </picture>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="slick-next slick-arrow" style="" aria-disabled="false">
        <svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none">
          <use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow">
          </use>
        </svg>
      </div>
    </div> --}}
    <div class="dtl-ex dtl-ex-slider-popup">
			@if(!empty($arr_extra_image))
				@foreach($arr_extra_image as $eiKey => $eiValue)
					@if($eiValue['video_image_type'] == 'mp4')
						<div class="dtl-ex-item" tabindex="0">
							<svg class="svg-play" width="22px" height="22px" aria-hidden="true" role="img" loading="lazy">
								<use href="#svg-play" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-play"></use>
							</svg>
						</div>
					@else
						<div class="dtl-ex-item" tabindex="0">
							<picture><img src="{{ $eiValue['extra_small_url'] }}" alt="{{$Product['product_name']}}" title="{{$Product['product_name']}}" width="75" height="75" loading="lazy"/></picture>
						</div>
					@endif
				@endforeach	
			
			@endif
		</div>
  </div>