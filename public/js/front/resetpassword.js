$(function () {
	jQuery.validator.addMethod("noSpace", function(value, element) {
	    return value.indexOf(" ") < 0 && value != "";
	}, "No space please and don't leave it empty");

	jQuery.validator.addMethod("passwordCheck", function(value, element, param) {
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
    }, GetMessage('Validate','UpperCaseAndLetter'));

	let jqValidationOptions = {
		ignore: [],
		rules: {
			new_pass: { required: true, minlength: 8, noSpace: true, passwordCheck: true  },
			confirm_pass: { required: true, equalTo: '#new_pass', noSpace: true, passwordCheck: true },
			"g-recaptcha-response": {
				required: true,
			}
		},
		messages: {
			new_pass: {
				required: 'Please enter new password',
				minlength: 'password minimum length should be 8 character long', 
				noSpace: 'No space please and don\'t leave it empty', 
				passwordCheck: 'New password contains at least one uppercase letter and one number' 
			},
			confirm_pass: {
				required: 'Please enter confirm password',
				equalTo: 'New password does not match with confirm password', 
				noSpace: 'No space please and don\'t leave it empty', 
				passwordCheck:  'Confirm password contains at least one uppercase letter and one number' 
			},
			"g-recaptcha-response": {
				required: GetMessage('Validate', 'GRecaptchaResponse')
			}
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "confirm_pass" || element.attr("name") == "new_pass") {
				error.addClass('w-100');
				element.parent().parent().append(error);
			}
			else { // This is the default behavior of the script
				error.insertAfter( element );
			}
		},
	};
	
	//Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	Object.assign(jqValidationOptions);
	$('#frmResetPassword').validate(jqValidationOptions);

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
