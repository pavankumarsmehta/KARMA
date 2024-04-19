$(function () {

	jQuery.validator.addMethod("selectstate", function(value, element, param) {
	    if (value == 'selected') {
	        return false;
	    }
	    return true;
    }, 'Please select state');

	jQuery.validator.addMethod("email", function(value, element) {
		return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
  	}, GetMessage('Register', 'ValidEmail'));

	let jqValidationOptions = {
		ignore: [],
		rules: {
			email: {
				required: true,
				email: true,
			},
			first_name: {
				required: true,
			},
			last_name: {
				required: true,
			},
			address1: {
				required: true,
			},
			/*address2: {
				required: true,
			},*/
			city: {
				required: true,
			},
			zip: {
				required: true,
			},
			phone: { required: true,minlength: 10,maxlength: 10 },
			agree: {
				required: true,
			},
			state: { required: 
				function(){
                    return $("#country").val() == "US"
              	},
				selectstate: true
			},
			other_state: { required: 
				function(){
                    return $("#country").val() != "US"
              	}
			},
			password: { 
				required: true 
			},
			confirmpassword: { 
				required: true,
				equalTo: '#password' 
			},
			"g-recaptcha-response": {
				required: true,
			},
		},
		messages: {
			email: {
				required: GetMessage('Validate', 'Email'),
				email: GetMessage('Register', 'ValidEmail'),
			},
			first_name: {
				required: GetMessage('Validate', 'FirstName')
			},
			last_name: {
				required: GetMessage('Validate', 'LastName')
			},
			address1: {
				required: GetMessage('Validate', 'Address')
			},
			/*address2: {
				required: GetMessage('Validate', 'Address')
			},*/
			city: {
				required: GetMessage('Validate', 'City')
			},
			zip: {
				required: GetMessage('Validate', 'ZipCode')
			},
			phone:{ required: "Please enter phone number",minlength:"Please enter minimum 10 character for phone number.",maxlength:"Please enter maximum 10 character for phone number."},
			state: 	{ 
				required: GetMessage('Validate', 'State'),
				selectstate: 'Please select state', 
			},
			other_state: { 
				required: GetMessage('Validate', 'OtherState')
			},
			password: { 
				required: GetMessage('Validate', 'Password') 
			},
			confirmpassword: 	{ 
				required: GetMessage('Validate', 'ConfirmPassword') , 
				equalTo: GetMessage('Validate', 'DoesNotMatch')
			},
			agree: {
				required: GetMessage('Validate', 'Agree')
			},
			"g-recaptcha-response": {
				required: GetMessage('Validate', 'GRecaptchaResponse')
			},
		},
		errorPlacement: function(error, element) {
			if ( element.attr("name") == "email" || element.attr("name") == "password" || element.attr("name") == "confirmpassword" || element.attr("name") == "agree" ) {
				error.addClass('w-100');
				element.parent().parent().append(error);
			}
			else { // This is the default behavior of the script
				error.insertAfter( element );
			}
		},
	};
	
	// Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	Object.assign(jqValidationOptions);
	$('#frmRegister').validate(jqValidationOptions);
});

$(document).ready(function() {

	$('#country').on('change', function (e) {
	    var selectedCountry = $(this).val();
	    console.log(selectedCountry);
		if(selectedCountry == 'US') {
	    	$("#divotherstate").hide();
	    	$("#divstate").show();
		} else {
			$("#other_state").val('');
	    	$("#divotherstate").show();
	    	$("#divstate").hide();
		}
	});

	/*
	* Recaptcha callback.
	* */
	window.verifyRecaptchaCallback = function (response) {
		$('#g-recaptcha-response-error').text('');
		$('#g-recaptcha-response-error').hide();
	}

	/*
	* Expired recaptcha callback.
	* */
	window.expiredRecaptchaCallback = function () {
		grecaptcha.reset();
		$('#g-recaptcha-response-error').text("Please verify the captcha to proceed");
		$('#g-recaptcha-response-error').show();

	}

});

$(".svg_eye_slash").on('click', function(event){
	$('#password').attr('type', 'text');
	$('#confirmpassword').attr('type', 'text');
	$('.svg_eye_slash').addClass('dnone');
	$('.svg_eye').removeClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});
$(".svg_eye").on('click', function(event){
	$('#password').attr('type', 'password');
	$('#confirmpassword').attr('type', 'password');
	$('.svg_eye_slash').removeClass('dnone');
	$('.svg_eye').addClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});


jQuery.validator.addMethod("passwordCheck",
function(value, element, param) 
{
	if (this.optional(element)) {
		return true;
	} else if (!/[A-Z]/.test(value)) {
		return false;
	} else if (!/[a-z]/.test(value)) {
		return false;
	} else if (!/[0-9]/.test(value)) {
		return false;
	}

	return true;
},
"Password must contain at least 1 upper case letter and 1 number");
