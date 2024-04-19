<div id="checkout-login-popup" class="modal-dialog modal-md modal-dialog-centered modal fade login-popup in" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title text-center">Welcome To HBAsales</h4>
            <svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal">
                <use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
            </svg>
			<div id="member-email-found-text"></div>
        </div>
        <div class="modal-body">
            <form class="row row10" method="post" name="frmLogin" id="frmLogin" action="javascript:void(0)">
                <input type="hidden" name="check_value" value="1" id="check_value">
                <input type="hidden" name="isAction" id="isAction" value="@if(isset($is_loginpopup_action)){{$is_loginpopup_action}}@endif">
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
                    <input type="password" class="form-control password-input" name="password" aria-describedby="inputGroupPrepend" id="password" placeholder="Password" />
                    <x-message :attr="[
                        'classname' => 'frmerror', 
                        'message' => '',
                        'mid' => 'error_password']" />
                    <div class="valid-feedback">Looks good! </div>
                </div>
                <div class="col-xs-12 pb-4">
                    <button type="submit" name="" class="btn btn-success btn-block" id="btnsubmit">Join</button>
                </div>
               
                <div class="col-xs-12"><a href="{{route('forgot-password')}}" class="f16 text_c1 tdu" title="Forgot Password?">Forgot Password?</a></div>
                <div class="col-xs-12">
                    <div class="separator"><span>OR</span></div>
                </div>
                <div class="col-xs-12"><a href="{{config('const.SITE_URL')}}/register.html" class="btn btn-border btn-block ttu" title="Create account">Create account</a></div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('public/js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}" ></script>
<script src="{{ asset('public/js/front/login_popup_validate.js') }}"></script>