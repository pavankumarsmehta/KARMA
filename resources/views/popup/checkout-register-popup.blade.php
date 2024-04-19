<div class="modal-dialog modal-md modal-dialog-centered common-popup login-popup">
    <div class="modal-content">
        <div class="modal-body">
        <h4 class="modal-title">Register with {{config('const.SITE_NAME')}}</h4>
			<svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal"><use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use></svg>
            <form method="post" name="frmCheckoutRegister" id="frmCheckoutRegister" class="crpopup-form">
                <input type="hidden" name="check_value" value="1" id="check_value">
                @csrf
                <div class="pb-3 por">
                  <label class="form-label" data-type="firstname" id="firstnamelabel">First Name <span style="color: var(--red);">*</span></label>
                  <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name *" />
                  <x-message :attr="[
                        'classname' => 'frmerror', 
                        'message' => '',
                        'mid' => 'error_firstname']" />
                </div>

                <div class="pb-3 por">
                  <label class="form-label" data-type="lastname" id="lastnamelabel">Last Name <span style="color: var(--red);">*</span></label>
                  <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name *" />
                  <x-message :attr="[
                        'classname' => 'frmerror', 
                        'message' => '',
                        'mid' => 'error_lastname']" />
                </div>

                <div class="pb-3 por">
                  <label class="form-label" data-type="mobile" id="mobilelabel">Phone Number <span style="color: var(--red);">*</span></label>
                  <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone Number *" />
                  <x-message :attr="[
                        'classname' => 'frmerror', 
                        'message' => '',
                        'mid' => 'error_phone']" />
                </div>

                <div class="pb-3 por">
                  <label class="form-label" data-type="email" id="emaillabel">Email Address<span style="color: var(--red);">*</span></label>
                  <input type="text" class="form-control" name="emailreg" id="emailreg" placeholder="Email Address *" />
                  <x-message :attr="[
                        'classname' => 'frmerror', 
                        'message' => '',
                        'mid' => 'error_email']" />
                </div>

                <div class="pb-3">
                  <div class="pb-1 por">
                    <label class="form-label" data-type="password" id="passwordlabel">Password <span style="color: var(--red);">*</span></label>
                    <input type="password" class="form-control password-input" name="cpassword" aria-describedby="inputGroupPrepend" id="cpassword" placeholder="Password *"/>
                    <x-message :attr="[
                            'classname' => 'frmerror', 
                            'message' => '',
                            'mid' => 'error_password']" />
                        <div class="valid-feedback">Looks good! </div>
                  </div>
                </div>
                <div class="error-cls pb-1" id="signup_error"></div>
                <a href="javascript:void(0);" onclick="valid_user_sign_up();" class="btn btn-border btn-block" title="Create Account">Create Account</a>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/front/login_popup_validate.js') }}"></script>
<script>
$(".svg_eye").on('click', function(event){
	$('#cpassword').attr('type', 'text');
	$('.svg_eye').addClass('dnone');
	$('.svg_eye_slash').removeClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});
$(".svg_eye_slash").on('click', function(event){
	$('#cpassword').attr('type', 'password');
	$('.svg_eye').removeClass('dnone');
	$('.svg_eye_slash').addClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});

// $('.input-container').on("click", function (e) {
//   $('#'+$(this).data('type')+'label').hide();
//   // $(this).find('label:first').hide();
//   // var label = this.querySelector('.checkout_label');
//   // label.style.display = 'none';
// });

// $('.input-container').on("onmouseout", function () {
//   var input = this.querySelector('.form-control');
//   if(input.value == ''){
//     var label = this.querySelector('.checkout_label');
//     label.style.display = 'flex';
//   }
// });

$(".form-control").focus(function() {
$(this).prev("label").hide(); //hide label of clicked item
}).blur(function() {
$(this).prev("label").show();
});

/*$(".checkout_label").mouseover(function() {
$(this).("label").hide(); //hide label of clicked item
}).mouseleave(function() {
$(this).("label").show();
});*/
// $(".checkout_label").click(function() {
// $('#'+$(this).data('type')+'label').hide();
// }).blur(function() {
// $('#'+$(this).data('type')+'label').show();
// });
</script>
