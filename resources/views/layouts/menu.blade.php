 <nav class="hidden-md-down">
    <div class="container">
      <ul class="menu" aria-label="Navigation bar">
         @if(count($menu_array) > 0)
         @foreach($menu_array as $menu_key => $menu_value)
              @php 
                $colourClass =  ($menu_value['menu_title'] == 'SALES & OFFERS') ? 'class="sale-menu"' : '';
              @endphp
              @php 
                $alignClass =  ($menu_value['menu_title'] == 'Skincare' || $menu_value['menu_title'] == 'Candles' || $menu_value['menu_title'] == 'Bath & Body') ? 'mm-last-img' : '';
              @endphp
        <li>
           <a href="{{ $menu_value['menu_link'] }}" {!! $colourClass !!} rel="nofollow" tabindex="0" title="{{ $menu_value['menu_title'] }}" aria-label="{{ $menu_value['menu_title'] }}">{{ $menu_value['menu_title'] }}</a>
            @if($menu_value['label_count'] > 0)
           <div class="menu-sub">
            <div class="container">
              <div class="menu-inner">
                @php  
                  $loop_count = 0;
                  $next_place = 'beside';
                  $in_single = 1;
                @endphp
                @foreach($menu_value['label'] as $label_key => $label_value)
                  @if($label_value['menu_title'] == 'Custom Tag Link - Banner Section')
                    @if(!empty($label_value['childs']))
                      @if($menu_value['display_banners_count'] == 1)
                         @if($label_value['childs'][0]['menu_image'] != '')
                            <div class="menu-col-thumb {!! $alignClass !!}">
                             
                              @php 
                                $banner_link = ($label_value['childs'][0]['menu_custom_link'] != '') ? $label_value['childs'][0]['menu_custom_link'] : 'javascript:void(0);';  
                                $menu_link = ($label_value['menu_link'] != '') ? $label_value['menu_link'] : config('const.SITE_URL').'#'. title($label_value['menu_title']);
                                $on_click = ($label_value['menu_link'] != '') ? '' : 'onClick="return false;"';
                              @endphp
                              <a href="{{ $banner_link }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label'] }}" aria-label="{{ $label_value['childs'][0]['menu_label'] }}">
                                    <img src="{{ asset('public/images/spacer.gif') }}" width="250" height="250" alt="{{ $label_value['childs'][0]['menu_label'] }}" title="{{ $label_value['childs'][0]['menu_label'] }}" data-src="{{ $label_value['childs'][0]['menu_image'] }}"/>
                                 </a>
                                  @if($label_value['childs'][0]['menu_label'] != '')
                                 <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label'] }}</div>
                                @endif
                              
                            </div>
                         @elseif($label_value['childs'][0]['menu_image1'] != '')
                            <div class="col-lg-2 n-1">
                             
                              @php 
                               $banner_link1 = ($label_value['childs'][0]['menu_custom_link1'] != '') ? $label_value['childs'][0]['menu_custom_link1'] : 'javascript:void(0);';  
                              @endphp
                              <a href="{{ $banner_link1 }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label1'] }}" aria-label="{{ $label_value['childs'][0]['menu_label1'] }}">
                                 <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $label_value['childs'][0]['menu_label1'] }}" title="{{ $label_value['childs'][0]['menu_label1'] }}" width="250" height="250" data-src="{{ $label_value['childs'][0]['menu_image1'] }}"/>
                                </a>
                                 @if($label_value['childs'][0]['menu_label1'] != '')
                                <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label1'] }}</div>
                                 @endif
                              
                            </div>            
                        @elseif($label_value['childs'][0]['menu_image2'] != '')
                              <div class="col-lg-2 n-2">
                                 
                                  @php 
                                    $banner_link2 = ($label_value['childs'][0]['menu_custom_link2'] != '') ? $label_value['childs'][0]['menu_custom_link2'] : 'javascript:void(0);';  
                                  @endphp
                                  <a href="{{ $banner_link2 }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label2'] }}" aria-label="{{ $label_value['childs'][0]['menu_label2'] }}">
                                             <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $label_value['childs'][0]['menu_label2'] }}" title="{{ $label_value['childs'][0]['menu_label2'] }}" width="250" height="250" data-src="{{ $label_value['childs'][0]['menu_image2'] }}"/>
                                             </a>
                                              @if($label_value['childs'][0]['menu_label2'] != '')
                                     <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label2'] }}</div>
                                    @endif
                                          
                                        </div>
                                  @endif
                        @elseif($menu_value['display_banners_count'] == 2)
                                    @php 
                                      $a = 0;
                                    @endphp
                                    @if($label_value['childs'][0]['menu_image'] != '')
                                      <div class="menu-col-thumb {!! $alignClass !!}">
                                       
                                        @php $banner_link = ($label_value['childs'][0]['menu_custom_link'] != '') ? $label_value['childs'][0]['menu_custom_link'] : 'javascript:void(0);';  @endphp
                                          <a href="{{ $banner_link }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label'] }}" aria-label="{{ $label_value['childs'][0]['menu_label'] }}">
                                           <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $label_value['childs'][0]['menu_label'] }}" title="{{ $label_value['childs'][0]['menu_label'] }}" width="250" height="250" data-src="{{ $label_value['childs'][0]['menu_image'] }}"/>
                                           </a>
                                             @if($label_value['childs'][0]['menu_label'] != '')
                                          <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label'] }}</div>
                                          @endif
                                        
                                      </div>
                                      @php $a++; @endphp
                         @endif
                                    @if($a < 2 && $label_value['childs'][0]['menu_image1'] != '')
                                      <div class="menu-col-thumb">
                                       
                                        @php $banner_link1 = ($label_value['childs'][0]['menu_custom_link1'] != '') ? $label_value['childs'][0]['menu_custom_link1'] : 'javascript:void(0);';  @endphp
                                        <a href="{{ $banner_link1 }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label1'] }}" aria-label="{{ $label_value['childs'][0]['menu_label1'] }}">
                                           <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $label_value['childs'][0]['menu_label1'] }}" width="250" height="250" data-src="{{ $label_value['childs'][0]['menu_image1'] }}"/>
                                           </a>
                                            @if($label_value['childs'][0]['menu_label1'] != '')
                                         <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label1'] }}</div>
                                          @endif
                                        
                                      </div>
                                      @php $a++; @endphp
                                    @endif
                                    @if($a < 2 && $label_value['childs'][0]['menu_image2'] != '')
                                      <div class="col-lg-2 n-3">
                                       
                                        @php $banner_link2 = ($label_value['childs'][0]['menu_custom_link2'] != '') ? $label_value['childs'][0]['menu_custom_link2'] : 'javascript:void(0);';  @endphp
                                          <a href="{{ $banner_link2 }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label2'] }}" aria-label="{{ $label_value['childs'][0]['menu_label2'] }}">
                                           <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $label_value['childs'][0]['menu_label2'] }}" width="250" height="250" data-src="{{ $label_value['childs'][0]['menu_image2'] }}"/>
                                           </a>
                                           @if($label_value['childs'][0]['menu_label2'] != '')
                                            <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label2'] }}</div>
                                            @endif
                                        
                                      </div>
                                      @php $a++; @endphp
                                    @endif
                                  @elseif($menu_value['display_banners_count'] >= 3)
                                    @if($label_value['childs'][0]['menu_image'] != '')
                                      <div class="menu-col-thumb {!! $alignClass !!}">
                                       
                                        @php $banner_link = ($label_value['childs'][0]['menu_custom_link'] != '') ? $label_value['childs'][0]['menu_custom_link'] : 'javascript:void(0);';  @endphp
                                         <a href="{{ $banner_link }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label'] }}" aria-label="{{ $label_value['childs'][0]['menu_label'] }}">
                                           <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $label_value['childs'][0]['menu_label'] }}" width="250" height="250"  data-src="{{ $label_value['childs'][0]['menu_image'] }}"/>
                                           </a>
                                             @if($label_value['childs'][0]['menu_label'] != '')
                                          <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label'] }}</div>
                                          @endif
                                        
                                      </div>
                                    @endif
                                    @if($label_value['childs'][0]['menu_image1'] != '')
                                      <div class="menu-col-thumb">
                                        
                                        @php $banner_link1 = ($label_value['childs'][0]['menu_custom_link1'] != '') ? $label_value['childs'][0]['menu_custom_link1'] : 'javascript:void(0);';  @endphp
                                          <a href="{{ $banner_link1 }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label1'] }}" aria-label="{{ $label_value['childs'][0]['menu_label1'] }}">
                                           <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $label_value['childs'][0]['menu_label1'] }}" width="250" height="250"  data-src="{{ $label_value['childs'][0]['menu_image1'] }}"/>
                                           </a>
                                            @if($label_value['childs'][0]['menu_label1'] != '')
                                         <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label1'] }}</div>
                                          @endif
                                        
                                      </div>
                                    @endif
                                    @if($label_value['childs'][0]['menu_image2'] != '')
                                      <div class="menu-col-thumb">
                                       
                                        @php $banner_link2 = ($label_value['childs'][0]['menu_custom_link2'] != '') ? $label_value['childs'][0]['menu_custom_link2'] : 'javascript:void(0);';  @endphp
                                        
                                           <a href="{{ $banner_link2 }}" tabindex="0" title="{{ $label_value['childs'][0]['menu_label2'] }}" aria-label="{{ $label_value['childs'][0]['menu_label2'] }}">
                                           <img src="{{ asset('public/images/spacer.gif') }}" alt="{{ $label_value['childs'][0]['menu_label2'] }}" width="250" height="250"  data-src="{{ $label_value['childs'][0]['menu_image2'] }}"/>
                                           </a>
                                            @if($label_value['childs'][0]['menu_label2'] != '')
                                           <div class="menu_title" tabindex="0">{{ $label_value['childs'][0]['menu_label2'] }}</div>
                                            @endif
                                        
                                      </div>
                                    @endif
                                  @else
                            @endif
                          @endif
                              @else
                          @php 
                            $child_count = count($label_value['childs']);
                          @endphp
                          @if($next_place == 'beside' || $in_single > 2)
                          <div class="menu-col">
                          @endif
                              @php
                                if($next_place == 'below' && $in_single <= 2) {
                                  $h5_class = 'class="de_spacetp"';
                                } else {
                                  $h5_class = '';
                                }
                                //$menu_link = ($label_value['menu_link'] != '') ? $label_value['menu_link'] : 'javascript:void(0);';
                                $menu_link = ($label_value['menu_link'] != '') ? $label_value['menu_link'] : config('const.SITE_URL').'#'. title($label_value['menu_title']);
                                $on_click = ($label_value['menu_link'] != '') ? '' : 'onClick="return false;"';
                              @endphp
                              @if($menu_value['menu_title'] != 'Discovery Bundles' && (($menu_value['menu_title'] != 'Fragrance' || $label_value['menu_title'] != 'Categories') || config('typevalofcriteo') == 'd'))
                              <h5 {!! $h5_class !!}><a href="{{ $menu_link }}" {!! $on_click !!}  tabindex="0" title="{{ $label_value['menu_title'] }}" aria-label="{{ $label_value['menu_title'] }}">{{ $label_value['menu_title'] }}</a></h5>
                              @endif
                              @if(!empty($label_value['childs']) && count($label_value['childs']) > 0)
                              <ul @if($menu_key != 0 && (($menu_value['menu_title'] != 'Fragrance' || $label_value['menu_title'] != 'Categories') || config('typevalofcriteo') == 'd')) class="mm-sub" @endif class="mm-sub">
                                {!! getCategoriesHTML($label_value['childs']) !!} 
                              </ul>
                              @endif
                              @php 
                                  if($child_count <= 4 && $in_single <= 2 && false) {
                                    $next_place = 'below';
                                    $in_single++;
                                  } else {
                                    $next_place = 'beside';
                                  }
                                  if(array_key_exists($label_key+1,$menu_value['label'])) {
                                    $next_section = $menu_value['label'][$label_key+1]['menu_title'];
                                  } else {
                                    $next_section = 'Not Exists';
                                  }
                                @endphp
                                @if($next_place == 'beside' || $next_section == 'Custom Tag Link - Banner Section' || $in_single > 2)
                                </div>
                                @endif

                              @endif
                              @php $loop_count++; @endphp
                            @endforeach
                   
              </div>
            </div>
          </div>
          @endif
        </li>
        
        @endforeach
        @endif
        @php
       // dd($other_categories_arr)
        @endphp
        @if($other_categories_arr)
        
        <li>
          <a href="{{config('const.SITE_URL')}}/other-categories.html"  tabindex="0" title="Other Categories" aria-label="Other Categories">Other Categories</a>
          <div class="menu-sub">
            <div class="container">
              <div class="menu-inner">
           
              <div class="menu-col">
                                  
                  <ul class="mm-sub">
                    @php 
                    $other_cat_count = 0;
                    $LIMIT_OTHER_CATEGORY = config ('const.LIMIT_OTHER_CATEGORY');
                    @endphp
                  @foreach($other_categories_arr as $other_categories_arr_key => $other_categories_arr_value)
                  @php 
                    $other_cat_count = $other_cat_count+1;
                    @endphp
                    <li>
                      {{-- <a href="{{config('const.SITE_URL')}}/{{$other_categories_arr_value['url_name']}}/cid/{{$other_categories_arr_value['category_id']}}" title="{{$other_categories_arr_value['category_name']}}" aria-label="{{$other_categories_arr_value['category_name']}}">{{$other_categories_arr_value['category_name']}}</a> --}}
                      <a href="{{$other_categories_arr_value['category_full_url']}}" title="{{$other_categories_arr_value['category_name']}}" aria-label="{{$other_categories_arr_value['category_name']}}">{{$other_categories_arr_value['category_name']}}</a>
                    </li>
                    @if($other_cat_count % 8 == 0)
                    </ul></div> <div class="menu-col"><ul class="mm-sub">
                    @endif
                    @endforeach
                    @if(count($other_categories_arr) >= $LIMIT_OTHER_CATEGORY)
                    <li  >
							        <a href="{{config('const.SITE_URL')}}/other-categories.html" title="View More" aria-label="View More" >View More</a>
						        </li>
                    @endif
                  </ul>
                  
                </div>
              
              </div>
            </div>
          </div>
      </li>  
      @endif      
        <li class="divider">|</li>
        <!-- <li><a href="{{config('const.SITE_URL')}}/#{!! title('discover') !!}" onClick="return false;" rel="noindex nofollow" tabindex="0" title="Discover" aria-label="Discover">Discover</a></li> -->
        @include('layouts.shopbybrand')
        
        <li class="sale"><a href="{{config('const.SITE_URL')}}/sale.html" tabindex="0" title="Sale" aria-label="Sale">Sale</a></li> 
      </ul>
    </div>
  </nav>

<!--  <nav class="hidden-md-down">
    <div class="container">
      <ul class="menu">
        <li class="active1">
          <a href="javascript:void(0)" rel="nofollow">Skincare</a>
          <div class="menu-sub">
            <div class="container">
              <div class="menu-inner">
                <div class="menu-left">1</div>
                <div class="menu-right">2</div>
              </div>
            </div>
          </div>
        </li>
        <li><a href="javascript:void(0)" rel="nofollow">Haircare</a></li>
        <li><a href="javascript:void(0)" rel="nofollow">Perfume</a></li>
        <li><a href="javascript:void(0)" rel="nofollow">Body Care</a></li>
        <li><a href="javascript:void(0)" rel="nofollow">Makeup</a></li>
        <li><a href="javascript:void(0)" rel="nofollow">Health & Wellness</a></li>
        <li class="divider">|</li>
        <li><a href="javascript:void(0)" rel="nofollow">Discover</a></li>
        <li><a href="javascript:void(0)" rel="nofollow">Shop by Brand</a></li>
        <li><a href="javascript:void(0)" rel="nofollow">Other</a></li>
        <li class="sale"><a href="javascript:void(0)" rel="nofollow">Sale</a></li>
      </ul>
    </div>
  </nav> -->