 <li class="drilldown-rarrow">
                <a href="{{config('const.SITE_URL')}}/brand-all.html" title="Shop by Brand" aria-label="Shop by Brand" >Shop by Brand</a>
                <ul class="drilldown-sub" >
                  <li class="drilldown-back"><a href="Shop by Brand" >Back </a></li>
                 
                  <li class="drilldown-rarrow">
                    <div class="menu-brand">
                      <div class="menu-brand-left">
                        <form>
                          @if(count($brandlist) > 0)
                          <div class="menu-brand-search">
                            <div class="por">
                              
                              <svg class="svg_search" aria-hidden="true" role="img">
                                <use href="#svg_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_search"></use>
                              </svg>
                              <input type="text" id="search_brands_mob" onkeyup="search_branc_mob(this.value)" name="search_brands" placeholder="Search Brands" class="form-control">
                            </div>
                          </div>            
                          <ul class="menu-brand-alp">
                            @foreach($brandlist as $brand_alpha_key => $brand_alpha_value)
                            <li>
                             <!-- <a href="javascript:void(0);" data-bname-mob-move-alpha-id="{{ $brand_alpha_key }}" class="bname-move-alpha-mob-js" title="{{ $brand_alpha_key }}" >{{ $brand_alpha_key }}</a> -->
                             <a href="{{config('const.SITE_URL')}}/#{{ $brand_alpha_key }}" data-bname-mob-move-alpha-id="{{ $brand_alpha_key }}" class="bname-move-alpha-mob-js" title="{{ $brand_alpha_key }}" >{{ $brand_alpha_key }}</a>
                             
                            </li>
                            @endforeach
                          </ul>
                          @endif
                          @if(count($brandlist) > 0)
                            <h5>Top Brands</h5>
                              <ul id="topbrands_scoller_mob" class="topbrands_scoller_mob_js mm-sub menu-brand-scrollbar" style="height:550px;overflow-y:scroll;">
                                @foreach($brandlist as $brand_alpha_key => $brand_alpha_value)
                                  @if(count($brand_alpha_value) > 0)
                                    @foreach($brand_alpha_value as $brand_key => $brand_value)
                                    <li @if($brand_key == 0) class="{{ $brand_alpha_key }}_top" @endif >
                                      <a href="{{ $brand_value['Link'] }}" title="{{ $brand_value['Name'] }}">{{ $brand_value['Name'] }}</a>
                                    </li>
                                    @endforeach
                                  @endif
                                @endforeach
                              </ul>
                          @endif
                        </form>
                      </div>
                      <div class="menu-brand-right">
                        <h5>POPULAR BRANDS</h5>
                        <ul class="menu-brand-logo">
                          @if(count($popular_brands) > 0)
                            @foreach($popular_brands as $popular_brand_key => $popular_brand_value)
                            <li>
                              <a href="{{ $popular_brand_value['Link'] }}">
                                <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $popular_brand_value['Name'] }}" width="108" height="73"  data-src="{{ $popular_brand_value['ImageLogo'] }}"  loading="lazy"/>
                              </a>
                            </li>
                            @endforeach
                          @endif
                          <li class="view-brands">
                            <a href="{{config('const.SITE_URL')}}/brand-all.html" class="linksbb">View More Brands</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </li>
                </ul>
            </li>