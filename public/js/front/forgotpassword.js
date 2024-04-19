$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			email: {
				required: true,
				email: true,
			},
			"g-recaptcha-response": {
				required: true,
			},
		},
		messages: {
			email: {
				required: "Please enter an email address",
				email: "Please enter a vaild email address",
			},
			"g-recaptcha-response": {
				required: GetMessage('Validate', 'GRecaptchaResponse')
			}
		},
		errorPlacement: function(error, element) {

			console.log(element.attr("name"));
			console.log("errorerror", error);
			if ( element.attr("name") == "email") {
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
	$('#formForgotPassword').validate(jqValidationOptions);

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


