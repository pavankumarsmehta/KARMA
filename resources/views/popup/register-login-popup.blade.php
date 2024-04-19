<div class="modal-dialog modal-dialog-centered reg-login-popup">
    <div class="modal-content">
        <div class="modal-body">
			<svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal">
                <use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
            </svg>
            <div class="row row-no-gutters">
                <div class="col-sm-6 separator">
                    <div class="login-text tac">
                        <p>Not a HBA Sales yet?</p>
                    </div>
                    <form method="post" name="frmCheckoutRegister" id="frmCheckoutRegister">
                        <input type="hidden" name="check_value" value="1" id="check_value"> 
                        @csrf
                        <div class="pb-3">
                            
                                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name" />
                                <!--<span class="input-group-text" id="inputGroupPrepend">@</span>-->
                            
                            <x-message :attr="[
                                'classname' => 'frmerror', 
                                'message' => '',
                                'mid' => 'error_firstname']" />
                        </div>
                        <div class="pb-3">
                            
                                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name" />
                                <!--<span class="input-group-text" id="inputGroupPrepend">@</span>-->
                            
                            <x-message :attr="[
                                'classname' => 'frmerror', 
                                'message' => '',
                                'mid' => 'error_lastname']" />
                        </div>
                        <div class="pb-3">
                            
                                <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone Numbar" />
                                <!--<span class="input-group-text" id="inputGroupPrepend">@</span>-->
                            
                            <x-message :attr="[
                                'classname' => 'frmerror', 
                                'message' => '',
                                'mid' => 'error_phone']" />
                        </div>
                        <div class="pb-3">
                                <!--<div class="input-group">-->
                                    <input type="text" class="form-control" name="emailreg" id="emailreg" value="" placeholder="Email" autofocus />
                                    <!--<span class="input-group-text">@</span>
                                </div>-->
                                
                                <!--<span class="input-group-text" id="inputGroupPrepend">@</span>-->
                            
                            <x-message :attr="[
                                'classname' => 'frmerror', 
                                'message' => '',
                                'mid' => 'error_emailreg']" />
                        </div>
                        <div class="pb-3">
                            <div class="">
                                <!--<div class="input-group">-->
                                    <input type="password" class="form-control password-input" name="cpassword" aria-describedby="inputGroupPrepend" id="cpassword" placeholder="Password" />
                                    <!--<span class="input-group-text pass-visible svg_eye1" style="cursor:pointer; padding:10px;" id="inputGroupPrepend">
                                        <img src="{{config('const.SITE_URL')}}/images/svg_eye_slash.png" alt="Hide" class="svg_eye svg_eyer">
                                        <img src="{{config('const.SITE_URL')}}/images/svg_eye.png" alt="Show" class="svg_eye_slash svg_eyes dnone">
                                    </span>
                                </div>-->
                                <x-message :attr="[
                                    'classname' => 'frmerror', 
                                    'message' => '',
                                    'mid' => 'error_password']" />
                                <div class="valid-feedback">Looks good! </div>
                            </div>
                            <!--<div class="tar"><a href="{{route('forgot-password')}}" class="f14 ">Forgot Password?</a></div>-->
                        </div>
                        <div class="pb-3">
                            {{--<a href="javascript:void(0);" onclick="valid_user_sign_up();" class="btn  btn-block mb-2" title="Create Account">Create Account</a><!--btn-border-->--}}
                            <button type="button" class="btn btn-block mb-2" onclick="valid_user_sign_up();" title="Create Account" aria-label="Create Account">Create Account</button>
                            {{--<a href="javascript:void(0);" onclick="checkout_as_guest();" class="btn btn-border btn-block" title="Continue as Guest">Continue as Guest</a>--}}
                            <button type="button" class="btn btn-border btn-block" onclick="checkout_as_guest();" title="Continue as Guest" aria-label="Continue as Guest">Continue as Guest</button>
                        </div>                        
                        <div class="btn-cont">
                            <!--<div class="pb-3"><button type="submit" name="" class="btn btn-success btn-block" id="btnsubmit">Sign in</button></div>-->
                            <div class="error-cls pb-1" id="signup_error"></div>                            
                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    <div id="member-email-found-text" class="dnone great-news">Great news! You already have an account with this email address. Log in below to track this order in your account. We do not save any payment information.</div>
                    <div class="login-text tac"><p>Already have an account? <br /> <span class="text_c3">Sign-in</span> to your HBA Sales account.</p></div>
                    @if(Session::has('failed'))
                        <div class="error">{{Session::get('failed');}}</div>
                    @endif
                    <form method="post" name="frmLogin" id="frmLogin" action="javascript:void(0)">
                        <input type="hidden" name="check_value" value="1" id="check_value">
                        <input type="hidden" name="isAction" id="isAction" value="@if(isset($is_loginpopup_action)){{$is_loginpopup_action}}@endif">
                        <div class="pb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" autofocus />
                                <span class="input-group-text">@</span>
                            </div>
                            <!--<span class="input-group-text" id="inputGroupPrepend">@</span>-->
                            <x-message :attr="[
                                'classname' => 'frmerror', 
                                'message' => '',
                                'mid' => 'error_email']" />
                        </div>
                        <div class="pb-3">
                            <div class="pb-1">
                                <div class="input-group password-textbox">
                                    <input type="password" class="form-control password-input" name="password" aria-describedby="inputGroupPrepend" id="password" placeholder="Password" />
                                    <span class="input-group-text pass-visible svg_eye1" style="cursor:pointer; padding:10px;" id="inputGroupPrepend">
                                        <img src="{{config('const.SITE_URL')}}/images/svg_eye_slash.png" alt="Hide" class="svg_eye">
                                        <img src="{{config('const.SITE_URL')}}/images/svg_eye.png" alt="Show" class="svg_eye_slash dnone">
                                    </span>
                                </div>
                                <x-message :attr="[
                                    'classname' => 'frmerror', 
                                    'message' => '',
                                    'mid' => 'error_password']" />
                                <div class="valid-feedback">Looks good! </div>
                            </div>
                            <div class="tar"><a href="{{route('forgot-password')}}" title="Forgot Password?" class="f14 text_lgray">Forgot Password?</a></div>
                        </div>
                        <div class="pb-3"><button type="submit" name="" class="btn btn-block" id="btnsubmit" title="Sign in" aria-label="Sign in">Sign in</button></div><!--btn-border-->
                        <div class="btn-cont">                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/front/register_login_popup_validate.js') }}"></script>
<script>
/*$(".svg_eye1").hover(
  function functionName() {
    //Change the attribute to text
    $("#cpassword").attr("type", "text");
  },
  function() {
    //Change the attribute back to password
    $("#cpassword").attr("type", "password");
  }
);

$(".svg_eye1").hover(
  function functionName() {
    //Change the attribute to text
    $("#password").attr("type", "text");
  },
  function() {
    //Change the attribute back to password
    $("#password").attr("type", "password");
  }
);*/
$(".svg_eye").on('click', function(event){
	$('#password').attr('type', 'text');
	$('.svg_eye').addClass('dnone');
	$('.svg_eye_slash').removeClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});
$(".svg_eye_slash").on('click', function(event){
	$('#password').attr('type', 'password');
	$('.svg_eye').removeClass('dnone');
	$('.svg_eye_slash').addClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});

$(".svg_eyer").on('click', function(event){
	$('#cpassword').attr('type', 'text');
	$('.svg_eyer').addClass('dnone');
	$('.svg_eyes').removeClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});
$(".svg_eyes").on('click', function(event){
	$('#cpassword').attr('type', 'password');
	$('.svg_eyer').removeClass('dnone');
	$('.svg_eyes').addClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});
</script>