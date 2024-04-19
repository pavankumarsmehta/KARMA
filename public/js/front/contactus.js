$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			fname: {
				required: true,
			},
			lname: {
				required: true,
			},
			email: {
				required: true,
				email: true,
			},
			customer_phone: {
				required: true,
			},
			note: {
				required: true,
			},
			"g-recaptcha-response": {
				required: true,
			}
		},
		messages: {
			fname: {
				required: "Please enter your first name"
			},
			lname: {
				required: "Please enter your last name"
			},
			email: {
				required: "Please enter an email address",
				email: "Please enter a vaild email address"
			},
			customer_phone: {
				required: "Please enter phone number"
			},
			note: {
				required: "Please enter your note"
			},
			"g-recaptcha-response": {
				required: GetMessage('Validate', 'GRecaptchaResponse')
			},
		},
		errorPlacement: function(error, element) {
			if ( element.attr("name") == "note" ) {
				error.insertAfter( element );
			}
			else { // This is the default behavior of the script
				error.addClass('w-100');
				element.parent().parent().append(error);
			}
		},
	};

	Object.assign(jqValidationOptions);
	$('#ContactUsForm').validate(jqValidationOptions);

});


$(document).ready(function () {

	/*
	* Recaptcha callback.
	* */
	window.verifyRecaptchaCallback = function (response) {
		$('#g_recaptcha_error_div').text('');
		$('#g_recaptcha_error_div').hide();
	}

	/*
	* Expired recaptcha callback.
	* */
	window.expiredRecaptchaCallback = function () {
		grecaptcha.reset();
		$('#g_recaptcha_error_div').text("Please verify the captcha to proceed");
		$('#g_recaptcha_error_div').show();

	}

});