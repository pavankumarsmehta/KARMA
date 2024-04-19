<div class="sb-slidebar sb-left sb-width-custom sb-style-overlay" data-sb-width="320px">
	<div class="mm_slidebar">
		<div class="mm_top">Menu 
			<svg class="svg_close sb-close" aria-hidden="true" role="img" width="24" height="24">
				<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
			</svg>
		</div>
		<div class="mm_mid">
			<div class="drilldown">
				<div class="drilldown-container">
					<ul class="drilldown-root">
						@if(count($menu_array) > 0)
							@foreach($menu_array as $menu_key => $menu_value)
							@php  
								$loop_count = 0;
								$next_place = 'beside';
								$in_single = 1;
							@endphp
							<li class="drilldown-rarrow">
								<a href="{{ $menu_value['menu_link'] }}" rel="nofollow" title="{{ $menu_value['menu_title'] }}">{{ $menu_value['menu_title'] }}</a>
								<ul class="drilldown-sub">
									<li class="drilldown-back">
										<a href="{{ $menu_value['menu_link'] }}" rel="nofollow" title="Back" >Back </a>
									</li>
									<li>
										<a href="{{ $menu_value['menu_link'] }}" title="{{ $menu_value['menu_title'] }}" style="text-decoration:underline; font-size: 20px;">{{ $menu_value['menu_title'] }}</a>
									</li>
									@if($menu_value['label_count'] > 0)
										<li class="drilldown-rarrow">
											<div class="mm-acd">
												@foreach($menu_value['label'] as $label_key => $label_value)
													@if($label_value['menu_title'] != 'Custom Tag Link - Banner Section')
														@if(!empty($label_value['childs']))
														<div class="mm-acd-loop @if($label_key == 0)act @endif">
															<input type="checkbox" name="mm-acd" id="mm-acd-1_{{ $label_value['menu_id'] }}">
															<a href="{{config('const.SITE_URL')}}/#{!! title($label_value['menu_title']) !!}" onClick="return false;" title="{{ $label_value['menu_title'] }}" >{{ $label_value['menu_title'] }}</a>
															<label for="mm-acd-1_{{ $label_value['menu_id'] }}" class="mm-acd-icon"></label>
															<div class="mm-acd-con">
																@if(!empty($label_value['childs']) && count($label_value['childs']) > 0)
																	{!! getCategoriesHTMLMob($label_value['childs']) !!} 
																@endif
															</div>
														</div>
														@endif
													@endif
												@endforeach
											</div>
										</li>				
									@endif
								</ul>
							</li>
							@endforeach
						@endif
						@if($other_categories_arr)
						<li class="drilldown-rarrow">
								<a href="{{config('const.SITE_URL')}}/other-categories.html"  tabindex="0" title="Other Categories" aria-label="Other Categories">Other Categories</a>

								<ul class="drilldown-sub">
									<li class="drilldown-back">
										<a href="{{config('const.SITE_URL')}}/other-categories.html"  title="Back" aria-label="Back">Back </a>
									</li>
									<li class="drilldown-rarrow">
										<div class="mm-acd">
																																																				<div class="mm-acd-loop act ">
														<input type="checkbox" name="mm-acd" id="mm-acd-1_118">
														<a href="{{config('const.SITE_URL')}}/other-categories.html"  title="Other Categories" aria-label="Other Categories">Other Categories</a>
														<label for="mm-acd-1_118" class="mm-acd-icon"></label>
														<div class="mm-acd-con">
															@php 
															$other_count = 0;
															@endphp
														@foreach($other_categories_arr as $other_categories_arr_key => $other_categories_arr_value)
														@php 
														$other_count = $other_count + 1;
														$LIMIT_OTHER_CATEGORY_MOBILE = config ('const.LIMIT_OTHER_CATEGORY_MOBILE');
														if($other_count == $LIMIT_OTHER_CATEGORY_MOBILE){
															break;
														}
														@endphp
																<a href="{{config('const.SITE_URL')}}/{{$other_categories_arr_value['url_name']}}/cid/{{$other_categories_arr_value['category_id']}}" title="{{$other_categories_arr_value['category_name']}}" aria-label="{{$other_categories_arr_value['category_name']}}">{{$other_categories_arr_value['category_name']}}</a>
														@endforeach
														@if(count($other_categories_arr) >= $LIMIT_OTHER_CATEGORY_MOBILE)
														<a href="{{config('const.SITE_URL')}}/other-categories.html"  title="View More" aria-label="View Mores">View More</a>
														@endif 
														</div>

											</div>
										</div>
									</li>				
								</ul>
							</li>
							@endif 
						<li><a href="{{config('const.SITE_URL')}}/#{!! title('discover') !!}" onClick="return false;" rel="noindex nofollow" title="Discover">Discover</a></li>
						@include('layouts.shopbybrand_mob')
						<li class="sale"><a href="{{config('const.SITE_URL')}}/sale.html" title="Sale">Sale</a></li>
						@if(Request::get('test') == 1)
						@if(count($currency) > 0)
						@php  
								$getSelectedCurrenctyCode = (Session::has('currency_code'))?Session::get('currency_code'):'USD';
						@endphp
						<li>
						<div class="form-floating">
							<label class="hidden-xs-down" for="currency_mob" tabindex="0">Currency:</label>
							<select class="form-select" id="currency_mob" tabindex="0" aria-label="Floating label select example">
							<option value="united-states" {{ 'united-states'==$getSelectedCurrenctyCode ? 'selected' : '' }} >USD - United States</option>
							@foreach($currency as $currency_key => $currency_value)
								<option value="{{title($currency_value['title'])}}" {{ title($currency_value['title'])==$getSelectedCurrenctyCode && 'united-states'!=$getSelectedCurrenctyCode ? 'selected' : '' }}>{{$currency_value['code']}} - {{$currency_value['title']}}</option>
							@endforeach
							</select>
						</div>
						</li>
						@endif
						@endif
					</ul>
				</div>
			</div>
			<div class="mm_contact">
				<a href="mailto:info@hbastore.com" rel="nofollow" aria-label="Email" title="Email">
					<svg class="svg-email" aria-hidden="true" role="img" width="28" height="28"><use href="#svg-email" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-email"></use></svg>
					<span>Email</span>
				</a>
				<a href="tel:1-000-000-0000" rel="nofollow" aria-label="Phone numbar" title="Phone numbar">
					<svg class="svg-phone" aria-hidden="true" role="img" width="28" height="28"><use href="#svg-phone" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-phone"></use></svg>
					<span>Phone</span>
				</a>
				<a href="#" rel="nofollow" aria-label="Chat" title="Chat">
					<svg class="svg-chat" aria-hidden="true" role="img" width="28" height="28"><use href="#svg-chat" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-chat"></use></svg>
					<span>Chat</span>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="overlay"></div>