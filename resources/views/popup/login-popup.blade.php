<div class="modal-dialog modal-dialog-centered common-popup login-popup">
    <div class="modal-content">
        <div class="modal-body">
        <h4 class="modal-title">Sign in To {{ config('const.SITE_NAME') }}</h4>
			<svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal"><use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use></svg>

            @if(Session::has('failed'))
            <div class="error">{{Session::get('failed');}}</div>
            @endif
            <form class="mb-0" method="post" name="frmLogin" id="frmLogin" action="javascript:void(0)">
                <input type="hidden" name="check_value" value="1" id="check_value">
                <input type="hidden" name="isAction" id="isAction" value="@if(isset($is_loginpopup_action)){{$is_loginpopup_action}}@endif">
				<div class="pb-3"><div class="input-group"><input type="text" class="form-control" name="email" id="email" placeholder="Email" autofocus /><span class="input-group-text" id="inputGroupPrepend">@</span></div>
				<x-message :attr="[
                        'classname' => 'frmerror error', 
                        'message' => '',
                        'mid' => 'error_email']" /></div><div class="pb-3">
                    <div class="pb-1">
                        <div class="input-group password-textbox">
                            <input type="password" class="form-control password-input" name="password" aria-describedby="inputGroupPrepend" id="password" placeholder="Password" />
							<span class="input-group-text pass-visible svg_eye1" style="cursor:pointer; padding:10px;" id="inputGroupPrepend">
								<img src="{{config('const.SITE_URL')}}/images/svg_eye_slash.png" alt="Hide" class="svg_eye">
								<img src="{{config('const.SITE_URL')}}/images/svg_eye.png" alt="Show" class="svg_eye_slash dnone">
							</span>
                        </div>
                        <x-message :attr="[
                            'classname' => 'frmerror error', 
                            'message' => '',
                            'mid' => 'error_password']" />
                        <div class="valid-feedback">Looks good! </div>
                    </div>
                </div>
                <div class="btn-cont">
                    <button type="submit" name="" class="btn btn-success btn-block mb-3" id="btnsubmit" title="Sign in">Sign in</button>
					<a href="{{route('forgot-password')}}" class="f16 tdu">Forgot Password?</a>
					<div class="separator"><span>OR</span></div>
					@if(isset($isAction) && $isAction == 'login_popup')
                        <a href="javascript:void(0);" onclick="return show_user_register_popup();" class="btn btn-border btn-block" title="Create Account">Register Now</a>
                    @else
                        <a href="{{config('const.SITE_URL')}}/register.html" class="btn btn-border btn-block" title="Create Account">Register Now</a>
                    @endif
					@if(isset($checkoutVal) && $checkoutVal == 'Yes')
						<div class="pb-3"><a href="javascript:void(0);" onclick="checkout_as_guest();" class="btn btn-border btn-block" title="Continue as Guest">Continue as Guest</a></div>
					@endif
				</div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/front/login_popup_validate.js') }}"></script>
<script>
    /*$(".svg_eye1").hover(
        function functionName() {
            //Change the attribute to text
            $("#password").attr("type", "text");
			$(".svg_eye_slash").removeClass("dnone");
			$(".svg_eye").addClass("dnone");
        },
        function() {
            //Change the attribute back to password
            $("#password").attr("type", "password");
			$(".svg_eye_slash").addClass("dnone");
			$(".svg_eye").removeClass("dnone");
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
</script>