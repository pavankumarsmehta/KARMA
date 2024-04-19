@include('layouts.popups')
@include('layouts.sidecart')
@include('layouts.mobilemenu')
<?php 
$CartDetails = Session::get('ShoppingCart'); 
$WishDetails = Session::get('wishList.totalQty'); 
if (isset($_REQUEST['flag']) && $_REQUEST['flag'] === 'true') {
  print_r($WishDetails);
  echo "wishlist count ". $WishDetails;
  exit;
}
?>

<div class="wrapper" id="sb-site">
<div id="header-sticky-anchor"></div>
<div id="header-sticky">
  <header>
	@if(config('Footer')['Top'] != "")
    <section class="header_top">
      <div class="container">
			{!! config('Footer')['Top'] !!}
      </div>
    </section>
	@endif
    <div class="container">      
      <section class="header_mid"> 
        <span class="sb-toggle-left hidden-lg-up" aria-label="Menu icon" title="Menu Icon" tabindex="0"> 
          <svg class="svg_menu" aria-hidden="true" role="img" width="25" height="27">          
          <use href="#svg_menu" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_menu"></use>
          </svg> 
        </span>
        <h1> 
          <a href="{{config('const.SITE_URL')}}" aria-label="{{config('const.SITE_NAME')}}" class="logo" title="{{config('const.SITE_NAME')}}"> 
            <picture><img src="{{config('const.SITE_URL')}}/images/logo.png" alt="{{config('const.SITE_NAME')}}" width="128" height="97" loading="lazy" /></picture>  
            <!--<svg class="svg_logo" width="213px" height="55px" aria-hidden="true" role="img">
              <use href="#svg_logo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_logo"></use>
            </svg> -->
          </a> 
        </h1>
         
        <div class="header_search hidden-md-down">
           <form method="get" action="{{config('const.SITE_URL')}}/search" class="">
          <div class="search_btn"> <svg class="svg_search" aria-hidden="true" role="img" width="23" height="23" fill="none">
            <use href="#svg_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_search"></use>
            </svg> </div>
          
          <input type="text" id="search-box" name="query" class="form-control input-search search-box" placeholder="Search Products" aria-label="Search Products" aria-describedby="button-addon2" />
          <input type="hidden" name="dfParam_rpp" value="90">
          </form>
        </div>

        <ul class="header-link">
           @if(session()->has('testmode'))
          @if(count($currency) > 0)
          @php  
								$getSelectedCurrenctyCode = (Session::has('currency_code'))?Session::get('currency_code'):'USD';
							@endphp
          <li class="hidden-md-down">
          <div class="form-floating">
            <label class="hidden-xs-down" for="currency_desk" tabindex="0">Currency:</label>
            <select class="form-select" id="currency_desk" tabindex="0" aria-label="Floating label select example">
            <option value="united-states" {{ 'united-states'==$getSelectedCurrenctyCode ? 'selected' : '' }} >USD - United States</option>
              @foreach($currency as $currency_key => $currency_value)
                <option value="{{title($currency_value['title'])}}" {{ title($currency_value['title'])==$getSelectedCurrenctyCode && 'united-states'!=$getSelectedCurrenctyCode ? 'selected' : '' }} >{{$currency_value['code']}} - {{$currency_value['title']}}</option>
              @endforeach
            </select>
          </div>
        
          </li>
          @endif
        @endif
          
          <li class="hidden-lg-up">
            <a href="{{config('const.SITE_URL')}}/#search" onClick="return false;" rel="noindex nofollow" id="mob_search" aria-label="Search Icon" title="Search">
              <svg class="svg_search" aria-hidden="true" role="img" width="28" height="28" fill="none">
                <use href="#svg_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_search"></use>
              </svg>
            </a>
          </li>
          <li>
            <div class="userbox">
              <a href="{{config('const.SITE_URL')}}/#search" onClick="return false;" rel="noindex nofollow" class="hidden-lg-up" aria-label="User Icon" title="User">
                <svg class="svg_user" aria-hidden="true" role="img" width="31" height="31">
                  <use href="#svg_user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_user"></use>
                </svg>
              </a>
              <div class="userbox_link hidden-md-down">
                <svg class="svg_user" aria-hidden="true" role="img" width="35" height="35">
                  <use href="#svg_user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_user"></use>
                </svg>
                @guest
                <strong class="dblock">My Account</strong>
                <span class="dblock">
                <a href="{{config('const.SITE_URL')}}/#login" onClick="return false;" rel="noindex nofollow" tabindex="0"  title="Sign In" aria-label="Sign In" class="me-2 loginpopup">Sign In</a>
                  <a href="{{config('const.SITE_URL')}}/#register" rel="noindex nofollow" tabindex="0" onClick="return show_user_register_popup();return false;" title="Create Account" aria-label="Create Account">Create Account</a>
                </span>
                @endguest
                @auth
                <strong class="dblock">My Account</strong>
                @endauth	
              </div>
              <div class="userbox-dropdown">
                <div class="userbox-inner">
                  @guest
                  <div class="ubhead">
                    <div class="ubhead-text">
                      <div class="pe-2"> <span class="circle">Li</span> </div>
                      <div><strong>Hi, Guest</strong> <span></span> </div>
                    </div>                
                    <div class="row row5">
                      <div class="col-xs-6"><button type="button" onclick="window.location='{{route('login')}}'" title="LOG IN" aria-label="LOG IN" class="btn btn-block">LOG IN</button></div>
                      <div class="col-xs-6"><button type="button" onclick="window.location='{{route('register')}}'" title="SIGN UP" aria-label="SIGN UP" class="btn btn-border btn-block">SIGN UP</button></div>
                  
                    </div>
                  </div>
                  @endguest
                  @auth
                  <ul class="ubmiddle">
                    <li> 
                      <a href="{{config('const.SITE_URL')}}/editprofile.html" title="Edit Profile" aria-label="Edit Profile"> 
                        <svg class="svg_edit_profile" aria-hidden="true" role="img" width="24" height="24">
                          <use href="#svg_edit_profile" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_edit_profile"></use>
                        </svg> 
                        <span>Edit Profile</span> 
                      </a> 
                    </li>
                    <li>
                      <a href="{{config('const.SITE_URL')}}/order-history.html"  title="Order History" aria-label="Order History">
                        <svg class="svg_history" aria-hidden="true" role="img" width="24" height="24">
                          <use href="#svg_history" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_history"></use>
                        </svg> 
                        <span>Order History</span> 
                      </a> 
                    </li>
                    <li> 
                      <a href="{{config('const.SITE_URL')}}/changepassword.html"  title="Change Password" aria-label="Change Password"> 
                        <svg class="svg_change_pass" aria-hidden="true" role="img" width="24" height="24">
                          <use href="#svg_change_pass" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_change_pass"></use>
                        </svg> 
                        <span>Change Password</span> 
                      </a> 
                    </li>
                    <li> 
                      <a href="{{config('const.SITE_URL')}}/wish-category.html"  title="My Wishlist" aria-label="My Wishlist"> 
                        <svg class="sv_wishlist vam me-1" aria-hidden="true" role="img" width="24" height="24">
                          <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
                        </svg> 
                        <span>My Wishlist</span> 
                      </a> 
                    </li>
                    <li> 
                      <a href="{{route('logout')}}"  title="Sign Out" aria-label="Sign Out"> 
                        <svg class="svg_logout vam me-1" aria-hidden="true" role="img" width="24" height="24">
                          <use href="#svg_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_logout"></use>
                        </svg> 
                        <span>Sign Out</span> 
                      </a> 
                    </li>
                  </ul>
                  @endauth
                </div>
              </div>
            </div>
          </li>
          <li>
            @guest 
            <a href="{{config('const.SITE_URL')}}/#login" onClick="return false;" rel="noindex nofollow" tabindex="0"  title="Wish List" aria-label="{{ (auth()->user() && ($WishDetails > 0)) ? $WishDetails : ''}}" class="me-2 loginpopup">
              <span id="cart-item-count" class="{{ (auth()->user() && ($WishDetails > 0)) ? 'cart-item-count' : ''}} loginpopup">{{ (auth()->user() && ($WishDetails > 0)) ? $WishDetails : ''}}</span>
              <svg class="svg_heart" aria-hidden="true" role="img" width="31" height="31">
                <use href="#svg_heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_heart"></use>
              </svg>
            </a> 
            @endguest
            @auth
            <a href="{{config('const.SITE_URL')}}/wish-category.html" title="Wish List" aria-label="{{ (auth()->user() && ($WishDetails > 0)) ? $WishDetails : ''}}">
                <span id="cart-item-count" class="{{ (auth()->user() && ($WishDetails > 0)) ? 'cart-item-count' : ''}}">{{ (auth()->user() && ($WishDetails > 0)) ? $WishDetails : ''}}</span>
              <svg class="svg_heart" aria-hidden="true" role="img" width="31" height="31">
                <use href="#svg_heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_heart"></use>
              </svg>
            </a>
             @endauth
          </li>
          <li>
            <a href="{{config('const.SITE_URL')}}/#bag" onClick="return false;" rel="noindex nofollow" title="Bag" class="sb-toggle-right sb-bag-js" aria-label="{{ (isset($CartDetails['TotalItemInCart']) && $CartDetails['TotalItemInCart'] > 0) ? $CartDetails['TotalItemInCart'] : ''}}">
              @if(isset($CartDetails['TotalItemInCart']) && $CartDetails['TotalItemInCart'] > 0)
                <span id="cart-counter" class="cart-item-count">{{$CartDetails['TotalItemInCart']}}</span>
              @endif
              <svg class="svg_cart" aria-hidden="true" role="img" width="31" height="31">
                <use href="#svg_cart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_cart"></use>
              </svg>
            </a>
          </li>
        </ul>        
        <div class="header_search_mob">
          <div class="por">
            <div class="header_search">
              <form method="get" action="{{config('const.SITE_URL')}}/search" class="">
              <div class="search_btn"> <svg class="svg_search" aria-hidden="true" role="img" width="23" height="23" fill="none">
                <use href="#svg_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_search"></use>
                </svg> </div>
              <input type="text" id="search-box-mo" name="query" class="form-control" placeholder="Search Products" aria-label="Search.." aria-describedby="button-addon2" />
              <input type="hidden" name="dfParam_rpp" value="90">
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>
  </header>
  @include('layouts.menu')
</div>