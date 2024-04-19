@if ($isAction == 'wish_login')
@include('popup.login-popup')
@endif

@if ($isAction == 'wish_forget')
<div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title text-center">FORGOT PASSWORD</h4>
            {{-- <a href="javascript:void(0);" id="close_forgotpassword" class="btn-close svg_close">X</a> --}}
            <svg class="btn-close svg_close" id="close_forgotpassword" width="25px" height="25px" data-openfrom="forgot_password" aria-hidden="true" role="img">
                <use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
            </svg>
            {{-- <svg class="btn-close svg_close" width="25px" height="25px" data-openfrom="forgot_password" aria-hidden="true" role="img" data-dismiss="modal">
                <use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
            </svg> --}}
        </div>
        <div class="modal-body">
            <div id="errorBox" style="margin-bottom:10px;" class="errorBox systemErrorBox"><span>{{$var_msg}}</span></div>
            @if (Session::has('failedfp'))
            <x-message :attr="[
                        'classname' => 'frmerror frmerror_shw', 
                        'message' => Session::get('failedfp')]" />
            @endif
            @if (Session::has('successfp'))

            <x-message :attr="[
                        'classname' => 'frmsuccess mb-3 mt-0', 
                        'message' => Session::get('successfp')]" />
            @endif
            <form class="row row10" method="post" name="frmForgotPwd" id="frmForgotPwd" action="javascript:void(0)">
                <input type="hidden" name="check_value" value="1" id="check_value">
                <div class="col-xs-12 pb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email Address" autofocus />
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                    </div>
                    <x-message :attr="[
                        'classname' => 'frmerror', 
                        'message' => '',
                        'mid' => 'error_email']" />
                </div>
                <div class="col-xs-12 pb-4">
                    <button type="submit" name="" class="btn btn-success btn-block" id="btnsubmit" title="Submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-hidden="true">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>FORGOT PASSWORD</h1>
                    </div>
                    <div class="modal-space">
                        <form method="post" name="frmForgotPwd" id="frmForgotPwd" action="javascript:void(0)">
                            <div class="login">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel">
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                                                    <div id="errorBox" style="margin-bottom:10px;" class="errorBox systemErrorBox"><span>{{$var_msg}}</span></div>
                                                    @if (Session::has('failedfp'))
                                                    <x-message :attr="[
                        										'classname' => 'frmerror frmerror_shw', 
                        										'message' => Session::get('failedfp')]" />
                                                    @endif
                                                    @if (Session::has('successfp'))
                                                    <x-message :attr="[
                        										'classname' => 'frmsuccess mb-3 mt-0', 
                        										'message' => Session::get('successfp')]" />
                                                    @endif

                                                    <div class="form-group">
                                                        <label for="lname" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Address" autofocus="">
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_email']" />
                                                    </div>
                                                </div>
                                                <input class="btn btn-primary" type="submit" name="Submit" value="Submit" bid="btnsubmit" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if ($isAction == 'wish_product')
<div class="modal-dialog modal-dialog-centered common-popup wishlist-popup">
    <div class="modal-content">
        <div class="modal-body">
            <h4 class="modal-title">WISHLIST</h4>
			<svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal"><use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use></svg>
            <form method="post" name="frmproduct" id="frmproduct" action="javascript:void(0)" class="mb-0">
                @if (Session::has('successwc'))
                <x-message :attr="[
									'classname' => 'frmsuccess text-center mb-3 mt-0', 
									'message' => Session::get('successwc')]" />
                @endif

                
                <input type="hidden" name="isAction" value="AddWishProduct">
                <input type="hidden" name="sku" value="{{ $prod_info[0]->sku ?? '' }}">
                <input type="hidden" name="productsId" id="productId" value="{{ $prod_info[0]->product_id ?? '' }}">
                <input type="hidden" name="section_name" value="{{ $section_name ?? '' }}">
                <input type="hidden" name="brand_id" value="{{ $brand_id ?? '' }}">
                <input type="hidden" name="category_id" value="{{ $category_id ?? '' }}">

                <div class="pb-3">
                    <div class="pb-2">
                        <label for="Category" class="form-label">Category <span class="red-color">*</span></label>
                        <select class="form-select" placeholder="Select Country" name="wishlist_category_id" id="wishlist_category_id" style="color: #30303F!important;font-size: 16px!important;font-weight: 400!important;">
                            <option value="">Select Category</option>
                            @if(isset($WishCatRS))
                            @foreach($WishCatRS as $key => $value)
                            <option value="{{$value['wishlist_category_id']}}">{{$value['name']}}</option>
                            @endforeach
                            @endif
                        </select>
                        <x-message :attr="[
                            'classname' => 'frmerror', 
                            'message' => '',
                            'mid' => 'error_wishlist_category_id']" />
                    </div>
                    <input class="btn btn-primary btn-block" type="button" onclick="showWishCat()" value="Create New Category" />
                </div>
                <div class="pb-1">
                    <div class="pb-2">
                        <label for="questions" class="form-label">Product Name : {{ $prod_info[0]->product_name ?? '' }}</label>
                        <textarea rows="4" cols="50" type="text" placeholder="Description" name="description" id="description" class="form-control"></textarea>
                        <x-message :attr="[
                            'classname' => 'frmerror', 
                            'message' => '',
                            'mid' => 'error_description']" />
                    </div>
                    <button type="submit" name="" class="btn btn-primary btn-block" id="btnsubmit">Submit</button>
                </div>
                <hr />
                <div class="pt-1">
                    <h6>HOW TO SET WISHLIST</h6>
                    <ol class="mb-0 pt-2">
                        <li class="mb-1">First create whatever categories needed to help organize your wishlist items. (i.e. 'My Wishes', 'Family Gifts', etc.)</li>
                        <li>You can add products to your wishlist by finding the product, clicking on the add to wishlist button, and choosing which category you would like to save it to.</li>
                    </ol>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
  /*addtowishlist = window.dataLayer || []; 
  var add_towislist_var = <?= ($GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA!="" ? $GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA : 0)?>;
  {{-- {!! json_encode($GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA) !!}; --}}
  if(add_towislist_var != 0){
    addtowishlist.push({'GA4_GOOGLE_ADD_TO_WISHLIST_EVENT_DATA': add_towislist_var});
  }*/

</script>	

@endif

@if ($isAction == 'wish_category')
<div class="modal-dialog modal-dialog-centered common-popup wishlist-popup">
    <div class="modal-content">
        <div class="modal-body">
        <h4 class="modal-title">WISHLIST</h4>
			<svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal"><use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use></svg>
            <form method="post" name="frmcategory" id="frmcategory" action="javascript:void(0)" style="box-shadow:none;" class="mb-0">
                @if (Session::has('successwc'))
                <x-message :attr="[
                    'classname' => 'frmsuccess text-center mb-3 mt-0', 
                    'message' => Session::get('successwc')]" />
                @endif
                <input type="hidden" name="isAction" value="AddWishCategory">
                <div class="pb-3">
                    <input type="text" class="form-control" placeholder="Category Name" id="category_name" name="category_name">
                    <x-message :attr="[
                        'classname' => 'frmerror', 
                        'message' => '',
                        'mid' => 'error_category_name']" />
                </div>
                <div class="pb-3">
                    <textarea rows="4" cols="50" type="text" placeholder="Description" name="description" id="description" class="form-control"></textarea>
                    <x-message :attr="[
                        'classname' => 'frmerror', 
                        'message' => '',
                        'mid' => 'error_description']" />
                </div>
                <div class="pb-3">
                    <input class="btn btn-success btn-block" type="submit" value="Add Wish Category" name="Add Wish Category" />
                </div>
                <div class="diblock">
                    <a href="javascript:void(0);" class="text_c2 dflex aic displaypopupboxwishlist" style="justify-content: end;" data-productId="{{$Wish_ProductsID}}" title="Back">
                    <svg class="svg_arrow_right me-1" aria-hidden="true" role="img" width="7" height="14">
                        <use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use>
                    </svg>Back
                </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<script src="{{ asset('js/front/login_popup_validate.js') }}"></script>
<script src="{{ asset('js/front/wishlist_add_validate.js') }}"></script>
