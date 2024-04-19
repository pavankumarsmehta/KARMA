/*$('#bl_sh_email').keypress(function( event ){
	if (event.keyCode === 13) {
		// Cancel the default action, if needed
		event.preventDefault();
		check_user_email_account();
		return false;
	}
});

$("#bl_sh_email").on('keydown', function(e) { 
	var keyCode = e.keyCode || e.which; 
	if (keyCode == 9) { 
		e.preventDefault(); 
		check_user_email_account();
		return false;
		// call custom function here
	} 
});*/

/*$(document).ready(function () {
	$("#bl_sh_email").change(function () {
		check_user_email_account();
		return false;
	});
});*/

$(function () {
	$.each($.validator.methods, function (key, value) {
        $.validator.methods[key] = function () {           
            if(arguments.length > 0) {
                arguments[0] = $.trim(arguments[0]);
            }

            return value.apply(this, arguments);
        };
    });
	let jqValidationOptions = {
		ignore: [],
		rules: {
			email: {
				required: true,
				email: true,
			},
			password: {
				required: true,
				minlength: 6,
			},
		},
		messages: {
			email: {
				required: GetMessage('Validate', 'Email'),
				email: GetMessage('Validate', 'ValidEmail'),
			},
			password: {
				required: GetMessage('Validate', 'Password'),
				minlength: GetMessage('Validate', 'ValidPassword'),
			}
		},
		errorPlacement: function (error, element) {
			if (element.attr("name") == "email" || element.attr("name") == "password") {
				error.addClass('');
				element.parent().append(error);
			}
			else { // This is the default behavior of the script
				error.insertAfter(element);
			}
		},
	};
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#formLogin').validate(jqValidationOptions);
});
function check_user_email_account() {
	if ($('#is_user_confirm_guest_checkout').val() == 1) {
		return;
	}

	var email = '';
	var frmObj = document.frmCheckOut;
	var email = frmObj.bl_sh_email.value;
	var URL = site_url + '/checkout-checkemail';

	var STR_POST_VAR = '';
	var STR_POST_VAR = 'email=' + email;
	var STR_POST_VAR = STR_POST_VAR + '&_token=' + $('meta[name="csrf-token"]').attr('content');

	$.ajax({
		type: 'POST',
		url: URL,
		data: STR_POST_VAR,
		cache: false,
		beforeSend: function () { },
		success: function (resultData) {
			if (resultData.trim() == 'yes') {
				show_user_login_popup('yes');
			}
			else {
				show_user_register_popup();
			}
		}
	});
}

function show_user_login_popup(is_member_found) {
	if ($('#is_customer_login').val() == 0) {
		if (is_member_found == 'yes') {

			$("#myModalPopUpLogin").html('');
			$("#page-spinner").show();
			var str = "isAction=login_popup&isPopup=Yes&checkoutVal=Yes";
			$.ajax({
				type: "POST",
				url: site_url + "/login_register",
				data: str,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}

			}).done(function (msg) {
				$("#page-spinner").hide();
				$("#myModalPopUpLogin").html(msg);
				$('#myModalPopUpLogin').modal('show');
				$('#member-email-found-text').removeClass('dnone');
			});
		}
		else {
			$('#member-email-found-text').addClass('dnone');
		}
		$('#c_login_email').val($('#bl_sh_email').val());
	}
}

/*function show_user_register_popup() {
	if ($('#is_customer_login').val() == 0) {
		$("#myModalPopUpLogin").html('');
		$("#page-spinner").show();
		var email_val = '';
		if ($('#bl_sh_email').val() != "") {
			email_val = $('#bl_sh_email').val();
		}
		var str = "isAction=register_popup&action=signup&checkoutVal=Yes&email_val=" + email_val;
		$.ajax({
			type: "POST",
			url: site_url + "/login_register",
			data: str,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}

		}).done(function (msg) {
			$("#page-spinner").hide();
			$("#myModalPopUpLogin").html(msg);
			$('#myModalPopUpLogin').modal('show');
		});
	}
}*/




function checkout_as_guest() {
	//$("#myModalPopUpLogin").modal('hide');
	//$("body").removeClass('modal-open');
	$(".checkout-login").hide();
	$('#is_user_confirm_guest_checkout').val(1);
}

function show_next(val) {
	var valid_status_account = 'false';
	var valid_status = 'false';
	var valid_status_ship = 'false';
	if (val == "create_account") {
		valid_status_account = createacc_validate();
	}
	if (val == "chk-billing") {
		valid_status = billfrm_validate();
	}
	if (val == "chk-shipping") {
		valid_status_ship = shipfrm_validate();
		if (valid_status_ship == true) {
			$(".error-cls").hide();
		}
	}
}

function valid_shipping_detail() {
	// validate signup form on keyup and submit
	$("#frmCheckOut div.error-cls").html('');
	$('#frmCheckOut').data('validator', null);
	$("#frmCheckOut").unbind('validate');

	$("#frmCheckOut").validate({
		ignore: ":not(:visible)",
		rules: {
			bl_sh_email: {
				required: true,
				email: true
			},
			sh_fname: "required",
			sh_lname: "required",
			sh_Addr1: "required",
			sh_city: "required",
			sh_country: "required",
			sh_state: {
				required: function () {
					return $("#sh_country").val() == 'US';
				}
			},
			sh_otherstate: {
				required: function () {
					return $("#sh_country").val() != 'US';
				}
			},
			sh_zip: "required",
			sh_phone: { required: true, minlength: 10, maxlength: 10 }
			//agree: "required"
		},
		messages: {
			bl_sh_email: { required: "Please enter your contact email address", email: "Please enter valid contact email address" },
			sh_fname: "Please enter your firstname",
			sh_lname: "Please enter your lastname",
			sh_Addr1: "Please enter your address",
			sh_city: "Please enter your city",
			sh_state: { required: "Please select your state" },
			sh_country: { required: "Please select your country" },
			sh_otherstate: { required: "Please enter your state" },
			sh_zip: "Please enter your zip code",
			sh_phone: { required: "Please enter phone number", minlength: "Please enter minimum 10 character for phone number.", maxlength: "Please enter maximum 10 character for phone number." },
			//agree: "Please accept our policy"
		},
		onsubmit: false,
		invalidHandler: function (form, validator) {
			var errors = validator.numberOfInvalids();
			console.log(errors);
			//alert(errors);
			if (errors) {
				for (var i = 0; i < errors; i++) {
					var message = validator.errorList[i].message;
					//alert(message);
					var id = $(validator.errorList[i].element).attr('name');
					$("#frmCheckOut div#error_" + id).html(message);
				}
				validator.errorList[0].element.focus();
			}
			else {
				$("#frmCheckOut div.error-cls").html('');
			}
		},
		errorPlacement: function (error, element) {
			// Override error placement to not show error messages beside elements //
		}
	});
	if (!$("#frmCheckOut").valid()) {
		return false;
	}

	var avataxreturnvalue = avaTaxValAddr();

	//console.log(avataxreturnvalue);

	if (avataxreturnvalue) {
		update_filled_section_detail();

		show_next_checkout_step('shipping');

	}

	Ajax_GetOrder_Summery();

	$('html, body').animate({
		scrollTop: $("#billing_form_section").offset().top - 50
	}, 100);

	return avataxreturnvalue;
}

function valid_billing_detail() {
	$("#frmCheckOut div.error-cls-billing").html('');

	$('#frmCheckOut').data('validator', null);
	$("#frmCheckOut").unbind('validate');

	jQuery.validator.addMethod("ValidPhone", function (phone_number, element) {
		pnum = phone_number.replace(/^\s+|\s+$/g, "");
		var stripped = pnum;
		var isGoodMatch = stripped.match(/^[0-9\s(-.)]*$/);
		if (!isGoodMatch)
			return false;
		else
			return true;
	}, 'The phone number contains invalid characters');

	$('#frmCheckOut').validate({
		ignore: ":hidden:not(#keycode)",
		rules:
		{
			bl_fname: {
				required: function () {
					if ($("#same_asship").is(':checked') == false)
						return true;
					return false;
				}
			},
			bl_lname: {
				required: function () {
					if ($("#same_asship").is(':checked') == false)
						return true;
					return false;
				}
			},
			bl_Addr1: {
				required: function () {
					if ($("#same_asship").is(':checked') == false)
						return true;
					return false;
				}
			},
			bl_city: {
				required: function () {
					if ($("#same_asship").is(':checked') == false)
						return true;
					return false;
				}
			},
			bl_state: {
				required: function () {
					if ($("#same_asship").is(':checked') == false && $("#bl_country").val() == 'US')
						return true;
					return false;
				}
			},
			bl_otherstate: {
				required: function () {
					if ($("#same_asship").is(':checked') == false && $("#bl_country").val() != 'US')
						return true;
					return false;
				}
			},
			bl_country: {
				required: function () {
					if ($("#same_asship").is(':checked') == false)
						return true;
					return false;
				}
			},
			bl_zip: {
				required: function () {
					if ($("#same_asship").is(':checked') == false)
						return true;
					return false;
				}
			},
			bl_phone: {
				required: function () {
					if ($("#same_asship").is(':checked') == false)
						return true;
					return false;
				}, ValidPhone: true
			}
		},
		messages:
		{
			bl_fname: { required: "Please Enter Your First Name" },
			bl_lname: { required: "Please Enter Your Last Name" },
			bl_Addr1: { required: "Please Enter Your Street Address" },
			bl_city: { required: "Please Enter Your City Name" },
			bl_state: { required: "Please Select Your State" },
			bl_otherstate: { required: "Please Enter Your State" },
			bl_country: { required: "Please Select Your Country" },
			bl_zip: { required: "Please Enter Your Zip Code" },
			bl_phone: { required: "Please Enter Your Phone Number" }
		},
		onsubmit: false,
		invalidHandler: function (form, validator) {
			var errors = validator.numberOfInvalids();
			//console.log(errors);
			if (errors) {
				for (var i = 0; i < errors; i++) {
					var message = validator.errorList[i].message;
					var id = $(validator.errorList[i].element).attr('name');
					$("#frmCheckOut div#error_" + id).html(message);
				}
				validator.errorList[0].element.focus();
			}
			else {
				$("#frmCheckOut div.error-cls-billing").html('');
			}
		},
		errorPlacement: function (error, element) {
			// Override error placement to not show error messages beside elements //
		}
	});


	if (!$("#frmCheckOut").valid()) {
		return false;
	}

	update_filled_section_detail();

	show_next_checkout_step('billing');

	Ajax_GetOrder_Summery();

	/*$('html, body').animate({
		scrollTop: $(".checkout-payment").offset().top - 20
	}, 100);*/

	return true;
}
function valid_shipping_method() {
	$('input[name="shippingModeId"]').each(function(){
		if($(this).is(':checked')){
			var shippingName = $(this).attr('data-shipname');
			$('#shipping_method_disp').text(shippingName);
			$('div[data-paymnet-method=""]').removeClass('active');
			$('div[data-paymnet-id="1"]').addClass('active');
			$('input[data-paymnet-id="1"]').prop('checked',true);
			show_next_checkout_step('shipping-method');
		}
})
}
function update_filled_section_detail() {
	var shippping_address_str = '';
	var billing_address_str = '';
	var shipping_method_str = '';
	var frmObj = document.frmCheckOut; // form object
	
	// Shipping address
	shippping_address_str = '<div class="content f14"><strong>' + $("#sh_fname").val() + '</strong><strong> ' + $("#sh_lname").val() + '</strong><br/>';
	shippping_address_str += $("#sh_Addr1").val() + ' ' + $("#sh_Addr2").val() + '<br/>';
	shippping_address_str += $("#sh_city").val() + ', ';

	if ($("#sh_country").val() == 'US') {
		shippping_address_str += $("#sh_state").val() + ' ';
	}
	else {
		shippping_address_str += $("#sh_otherstate").val() + ' ';
	}
	shippping_address_str += $("#sh_zip").val() + '<br/>';
	shippping_address_str += $("#sh_country").val() + '<br/>';
	shippping_address_str += $("#sh_phone").val() + '<br/>';
	shippping_address_str += $("#bl_sh_email").val();
	//shippping_address_str += $("#is_newsletter").val();



	//$("#shipping-address-filled-section").html(shippping_address_str);
	$("#shipping_info_disp").html(shippping_address_str);


	// Shipping method	
	/*shipping_method_str = $('input[name=shippingModeId]:checked').data('shipname');
	if ($('input[name=shippingModeId]:checked').data('charge') == 0) {
		shipping_method_str += '';
	}
	else {
		shipping_method_str += '<span class="ml-5">$' + $('input[name=shippingModeId]:checked').data('charge') + '</span>';
	}
	$("#shipping-method-filled-section").html(shipping_method_str);*/
	// Billing address	
	billing_address_str = shippping_address_str;
	if ($("#same_asship").is(':checked') == false) {
		billing_address_str = '<div class="content f14"><strong>'+ $("#bl_fname").val() + '</strong><strong> ' + $("#bl_lname").val() + '</strong><br/>';
		billing_address_str += $("#bl_Addr1").val() + ' ' + $("#bl_Addr2").val() + '<br/>';
		billing_address_str += $("#bl_city").val() + ', ';
		if ($("#sh_country").val() == 'US') {
			billing_address_str += $("#bl_state").val() + ' ';
		}
		else {
			billing_address_str += $("#bl_otherstate").val() + ' ';
		}

		billing_address_str += $("#bl_zip").val() + '<br/>';
		billing_address_str += $("#bl_country").val() + '<br/>';
		billing_address_str += $("#bl_phone").val();
	}
	$("#billing_info_disp").html(billing_address_str);

	save_stepone();
}

function save_stepone() { 
	//alert($("#bt_payment_method_nonce").val());
	var CSRF_TOKEN = "";
	sh_fname = $("#sh_fname").val();
	sh_lname = $("#sh_lname").val();
	sh_Addr1 = $("#sh_Addr1").val();
	sh_Addr2 = $("#sh_Addr2").val();
	sh_city = $("#sh_city").val();
	sh_zip = $("#sh_zip").val();
	sh_country = $("#sh_country").val();
	sh_phone = $("#sh_phone").val();
	is_newsletter = 'No';

	if ($("#is_newsletter").prop('checked') == true) {
		is_newsletter = 'Yes';
	}

	/*var sh_choose_password		= '';
	var sh_re_password			= '';
	
	sh_choose_password = $("#sh_choose_password").val();
	sh_re_password = $("#sh_re_password").val();*/

	/*if(sh_choose_password != "" && sh_re_password !=)
	{
		shippping_address_str += sh_choose_password;
		shippping_address_str += sh_re_password;
	}*/

	//alert(is_newsletter);
	if ($.trim(sh_country) == 'US') {
		sh_state = $("#sh_state").val();
	} else {
		sh_state = $("#sh_otherstate").val();
	}
	bl_sh_email = $("#bl_sh_email").val();

	bl_fname = $("#bl_fname").val();
	bl_lname = $("#bl_lname").val();
	bl_Addr1 = $("#bl_Addr1").val();
	bl_Addr2 = $("#bl_Addr2").val();
	bl_city = $("#bl_city").val();

	bl_country = $("#bl_country").val();

	/* remove email from shipping as per mail "Fwd: it wont let me select the payment method" on 07-08-2019 */
	//bl_sh_email		= $("#bl_sh_email").val();

	if ($.trim(bl_country) == 'US') {
		bl_state = $("#bl_state").val();
	} else {
		bl_state = $("#bl_otherstate").val();
	}
	bl_zip = $("#bl_zip").val();
	bl_phone = $("#bl_phone").val();

	var is_billing_info = '';
	var BillingAsShipping = 'no';

	if (document.getElementById("same_asship").checked == true) {
		is_billing_info = 'sameasship';
		var BillingAsShipping = 'yes';
	}
	//if(bl_fname != '')
	else if (document.getElementById("same_asship").checked == false) {
		is_billing_info = 'not_sameasship';
	}

	var payment_method = '';
	if ($("#payment_method").val() != "") {
		payment_method = $("#payment_method").val();
	}
	//alert(is_billing_info);
	var SAVE_POST_VAR = 'bl_sh_email=' + bl_sh_email;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_fname=' + sh_fname;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_lname=' + sh_lname;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_Addr1=' + sh_Addr1;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_Addr2=' + sh_Addr2;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_city=' + sh_city;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_zip=' + sh_zip;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_state=' + sh_state;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_country=' + sh_country;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&is_billing_info=' + is_billing_info;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_phone=' + sh_phone;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&is_newsletter=' + is_newsletter;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_fname=' + bl_fname;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_lname=' + bl_lname;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_Addr1=' + bl_Addr1;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_Addr2=' + bl_Addr2;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_city=' + bl_city;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_zip=' + bl_zip;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_state=' + bl_state;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_country=' + bl_country;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&bl_phone=' + bl_phone;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&BillingAsShipping=' + BillingAsShipping;
	var SAVE_POST_VAR = SAVE_POST_VAR + '&payment_method=' + payment_method;
	/*if(sh_choose_password != "" && sh_re_password != "")
	{
		var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_choose_password='+sh_choose_password;
		var SAVE_POST_VAR = SAVE_POST_VAR + '&sh_re_password='+sh_re_password;
		//alert(sh_choose_password+"========"+sh_re_password);
		//return false;
	}*/

	var bt_payment_method_nonce = '';
	bt_payment_method_nonce = $("#bt_payment_method_nonce").val();
	if ($.trim(bt_payment_method_nonce) != '') {
		var SAVE_POST_VAR = SAVE_POST_VAR + '&bt_payment_method_nonce=' + bt_payment_method_nonce;
	}

	var SAVE_POST_VAR = SAVE_POST_VAR + '&_token=' + $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		url: base_url_new + "/save_checkoutstep?",
		dataType: "html",
		data: SAVE_POST_VAR,
	})
		.done(function (msg) {
			//alert(msg);
		});
}

function show_next_checkout_step(current_step) {
	
	var sam_as_shipp_add = $('#sam_as_shipp_add').val();

	if (current_step == 'shipping') {
		$('#shipping_edit_link').removeClass('dnone');
		$('#shipping-acd').removeClass('active');
		$('#billing-acd').addClass('active');
		//$('.shipping_arrow').addClass('dnone');
		$('#shipping_form_section').addClass('dnone');
		$('#shipping_info_section').removeClass('dnone');

		$('#billing_edit_link').addClass('dnone');
		$('#billing_form_section').removeClass('dnone');
		$('#billing_info_section').addClass('dnone');

		if (sam_as_shipp_add == 'yes') {
			$('#billing_adddress_box').show();
		}

	}
	if (current_step == 'billing') {
		$('#shipping_info_section').removeClass('dnone');
		$('#shipping_edit_link').removeClass('dnone');
		$('#shipping_form_section').addClass('dnone');

		$('#billing_edit_link').removeClass('dnone');
		$('#billing_form_section').addClass('dnone');
		$('#billing_info_section').removeClass('dnone');

		$('#billing-acd').removeClass('active');
	

		// $('.shipping-method').addClass("active");
		// $('.shipping-method-content-js').removeClass("dnone");
		show_shipping_method('true');

		Ajax_GetOrder_Summery();

	}
	if (current_step == 'shipping-method') {
			// $('.shipping-method-content-js').addClass('dnone');
			
			// $('.shipping-expected-delivery').removeClass('dnone');
			// $('.shipping-method').removeClass('active');
			// $('#shipping_method_disp').removeClass('dnone');
			// $('#payment-form-section').removeClass('dnone');
			show_shipping_method();
			$('#payment-form-section').removeClass('dnone');
			$('#payment_method').addClass('active');
			//$('.checkout-pmact-loop').addClass('active');
			$('[data-paymnet-id="1"]').addClass('active');
		// $('#shipping_info_section').removeClass('dnone');
		// $('#shipping_edit_link').removeClass('dnone');
		// $('#shipping_form_section').addClass('dnone');

		// $('#billing_edit_link').removeClass('dnone');
		// $('#billing_form_section').addClass('dnone');
		// $('#billing_info_section').removeClass('dnone');

		// $('#billing-acd').removeClass('active');
		// $('#payment-form-section').removeClass('dnone');
		// $('#payment_method').addClass('active');
		// $('.checkout-pmact-loop').addClass('active');

		// $('.shipping-method').addClass("active");
		// $('.shipping-method-content-js').removeClass("dnone");

		// Ajax_GetOrder_Summery();

	}

}
function change_payment_method(event){
	var paymentMethodId = $('input[name="payment_method"]:checked').attr('data-paymnet-id');
	$('input[name="payment_method"]:checked').parents().find('[data-paymnet-method=""]').removeClass('active');
	$(event).parents().find('[data-paymnet-id="'+paymentMethodId+'"]').addClass('active');
}
/*function edit_checkout_step(current_step) {
	if (current_step == 'shipping') {
		$('#shipping_edit_link').addClass('dnone');
		$('.shipping_arrow').removeClass('dnone');
		$('#shipping-acd').addClass('active');
		$('#billing-acd').removeClass('active');
		$('#shipping_info_section').addClass('dnone');
		$('#shipping_form_section').removeClass('dnone');


		$('#billing_edit_link').addClass('dnone');
		$('#billing_form_section').addClass('dnone');
		$('#billing_info_section').addClass('dnone');
		

		show_shipping_method();
		$('#payment-form-section').addClass('dnone');

	}

	if (current_step == 'billing') {
		$('#shipping_edit_link').removeClass('dnone');
		$('.billing_arrow').removeClass('dnone');
		$('#billing-acd').addClass('active');
		$('#shipping-acd').removeClass('active');
		
		$('#shipping_form_section').addClass('dnone');
		$('#shipping_info_section').removeClass('dnone');


		$('#billing_edit_link').addClass('dnone');
		$('#billing_form_section').removeClass('dnone');
		$('#billing_info_section').addClass('dnone');
		
		show_shipping_method();
		$('#payment-form-section').addClass('dnone');
		

		if ($("#same_asship").is(':checked') == true) {
			$("#same_asship").prop('checked', false);
			show_billing_address();
		}
		

	}
	
	if (current_step == 'shipping-method') {
		$('#shipping_form_section').addClass('dnone');
		$('#shipping_info_section').removeClass('dnone');
		$('#shipping_edit_link').removeClass('dnone');
		$('#shipping-acd').removeClass('active');

		$('#billing_edit_link').addClass('dnone');
		$('#billing_form_section').addClass('dnone');
		$('#billing_info_section').addClass('dnone');


		$('.shipping-method-content-js').removeClass('dnone');
		$('.shipping-expected-delivery').addClass('dnone');
		$('.shipping-method').addClass('active');
		$('#shipping_method_disp').addClass('dnone');
		$('#payment-form-section').addClass('dnone');
		$('#payment_method').removeClass('active');
		$('.checkout-pmact-loop').removeClass('active');
	}
	if (current_step == 'payment-method') {

		$('#shipping_form_section').addClass('dnone');
		$('#shipping_info_section').removeClass('dnone');
		$('#shipping_edit_link').removeClass('dnone');
		$('#shipping-acd').removeClass('active');

		$('#billing_edit_link').addClass('dnone');
		$('#billing_form_section').addClass('dnone');
		$('#billing_info_section').addClass('dnone');
		show_shipping_method();
		show_payment_method('true');
	}
}*/

function edit_checkout_step(current_step) {
	if (current_step == 'shipping') {
		$('#shipping_edit_link').addClass('dnone');
		$('.shipping_arrow').removeClass('dnone');
		$('#shipping-acd').addClass('active');
		$('#billing-acd').removeClass('active');
		$('#shipping_info_section').addClass('dnone');
		$('#shipping_form_section').removeClass('dnone');


		$('#billing_edit_link').addClass('dnone');
		$('#billing_form_section').addClass('dnone');
		$('#billing_info_section').addClass('dnone');
		

		show_shipping_method();
		$('#payment-form-section').addClass('dnone');

	}

	if (current_step == 'billing') {
		$('#billing_adddress_box').show();
		if(valid_shipping_detail()){
			
		$('#shipping_edit_link').removeClass('dnone');
		$('.billing_arrow').removeClass('dnone');
		$('#billing-acd').addClass('active');
		$('#shipping-acd').removeClass('active');
		
		$('#shipping_form_section').addClass('dnone');
		$('#shipping_info_section').removeClass('dnone');


		$('#billing_edit_link').addClass('dnone');
		$('#billing_form_section').removeClass('dnone');
		$('#billing_info_section').addClass('dnone');
		
		show_shipping_method();
		$('#payment-form-section').addClass('dnone');
		

		if ($("#same_asship").is(':checked') == true) {
			//$("#same_asship").prop('checked', false);
			show_billing_address();
		}else{}
	}else{
		valid_shipping_detail();
	}

	}
	
	if (current_step == 'shipping-method') {
		if(valid_shipping_detail() == false){
			valid_shipping_detail();
			return false;
		}
		if ($("#same_asship").is(':checked') == false) {
			if(!valid_billing_detail()){
				valid_billing_detail();
				return false;
		
			}
		}else{
			if(valid_billing_detail() == false){
				valid_billing_detail();
				return false;
		
			}
		}
		
		$('#billing_info_section').removeClass('dnone');
			$('#shipping_form_section').addClass('dnone');
			$('#shipping_info_section').removeClass('dnone');
			$('#shipping_edit_link').removeClass('dnone');
			$('#shipping-acd').removeClass('active');

			$('#billing_edit_link').addClass('dnone');
			$('#billing_form_section').addClass('dnone');
			//$('#billing_info_section').addClass('dnone');


			$('.shipping-method-content-js').removeClass('dnone');
			$('.shipping-expected-delivery').addClass('dnone');
			$('.shipping-method').addClass('active');
			$('#shipping_method_disp').addClass('dnone');
			$('#payment-form-section').addClass('dnone');
			$('#payment_method').removeClass('active');
			$('.checkout-pmact-loop').removeClass('active');

	}
	if (current_step == 'payment-method') {
		if(valid_shipping_detail() == false){
			valid_shipping_detail();
			return false;
		}
		if ($("#same_asship").is(':checked') == false) {
			if(!valid_billing_detail()){
				valid_billing_detail();
				return false;
		
			}
		}else{
			if(valid_billing_detail() == false){
				valid_billing_detail();
				return false;
		
			}
		}
		valid_shipping_method();
		$('#shipping_form_section').addClass('dnone');
		$('#shipping_info_section').removeClass('dnone');
		$('#shipping_edit_link').removeClass('dnone');
		$('#shipping-acd').removeClass('active');
		$('#shipping_method_disp').removeClass('dnone');
		$('#billing_edit_link').addClass('dnone');
		$('#billing_form_section').addClass('dnone');
		//$('#billing_info_section').addClass('dnone');
		$('#billing_info_section').removeClass('dnone');
		show_shipping_method();
		show_payment_method('true');
	}
}

function ship_statecheck() {

	//console.log('sachin',$('#sh_country option:selected').val());
	if ($('#sh_country option:selected').val() == "US") {
		$('#sh_state').show();
		$('#sh_otherstate').hide();
		$('#sh_otherstate').val("");
		$("#error_sh_otherstate").html('');
		$("#error_sh_country").html('');
	}
	else {
		$('#sh_state').hide();
		$('#sh_otherstate').show();
		$('#sh_otherstate').focus();
		$("#error_sh_state").html('');
	}
}

function bill_statecheck() {
	if ($('#bl_country option:selected').val() == "US") {
		$('#bl_state').show();
		$('#bl_otherstate').hide();
		$('#bl_otherstate').val("");
		$("#error_bl_otherstate").html('');
		$("#error_bl_country").html('');
	}
	else {
		$('#bl_state').hide();
		$('#bl_otherstate').show();
		$('#bl_otherstate').focus();
		$("#error_bl_state").html('');
	}
}

$(document).ready(function () {
	$("#same_asship").change(function () {
		var frmObj = document.frmCheckOut; // form object
		if (frmObj.same_asship.checked == true) {
			valid_billing_detail();
			return false;
		}
	});

	window.setTimeout(function () {
		$(".alert").fadeTo(500, 0).slideUp(500, function () {
			$(this).remove();
		});
	}, 4000);

$(document).on('click', '.arrow_down', function () {
	$('#all-product-add').toggleClass('dnone');
	$('#all-product-remove').toggleClass('dnone');
	$('.title').toggleClass('active');
});

	$(document).on('click', '.btn-number', function () {
		var data_field = $(this).attr('data-field');
		var action_type = $(this).attr('data-type');
		var current_val = parseInt($("#" + data_field).val());
		$("#page-spinner").show();
		if (!isNaN(current_val)) {
			if (action_type == "plus") {
				current_val = current_val + 1;
				$("#" + data_field).val(current_val);
			} else if (action_type == "minus") {
				if (current_val > $("#" + data_field).attr('min')) {
					current_val = current_val - 1;
					$("#" + data_field).val(current_val).change();
				}
				if (parseInt($("#" + data_field).val()) == $("#" + data_field).attr('min')) {
					$(this).attr('disabled', true);
				}
			}
		}
		updateCart(data_field, current_val);
		//getCartDetails();
		$("#page-spinner").hide();
	})

	ship_statecheck();
	bill_statecheck();
	show_billing_address();
	Ajax_GetShipMethod();
});

function updateCart(data_field, prod_qty) {
	var cart_id = data_field.replace("prod_", "");
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		url: site_url + '/cart_action',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data: {
			cart_id: cart_id,
			prod_qty: prod_qty,
			action: 'update_quantity'
		},
		success: function (data) {
			//alert(data);
			console.log(data);
			//alert(data.CartErrors);
			if (typeof data.CartErrors !== "undefined") {
				alert(data.CartErrors);
			}
			else if (data.cart_empty != '') {
				$("#cart_main").html(data.cart_empty);
			} else {
				//Ajax_GetOrder_Summery();
				location.reload();
			}

		}
	});

}

function Ajax_GetShipMethod() {
	var frmObj = document.frmCheckOut; // form object

	var ship_country = frmObj.sh_country.value;
	var ship_zip = frmObj.sh_zip.value;
	var ship_city = frmObj.sh_city.value;

	if (ship_country == "US")
		var ship_state = frmObj.sh_state.value;
	else
		var ship_state = frmObj.sh_otherstate.value;

	var STR_POST_VAR = 'ship_country=' + ship_country;
	var STR_POST_VAR = STR_POST_VAR + '&ship_state=' + ship_state;
	var STR_POST_VAR = STR_POST_VAR + '&ship_zip=' + ship_zip;
	var STR_POST_VAR = STR_POST_VAR + '&ship_city=' + ship_city;
	var STR_POST_VAR = STR_POST_VAR + '&_token=' + $('meta[name="csrf-token"]').attr('content');

	$.ajax({
		type: 'POST',
		url: base_url_new + "/get-shipping-methods-ajax?",
		dataType: "html",
		data: STR_POST_VAR,
		beforeSend: function () {
			var html_loader = '<img src="' + base_url_new + '/images/ajax-loader.gif"><br><span class="error">Please wait while shipping methods are loading....<br>if it takes more than 10-15 seconds then please refresh the page.</span>';
			$('#shipping-methods').html(html_loader);

		},
		success: (function (data, status) {
			$("#frmCheckOut div#error_shippingMethod").html('');
			$('#shipping-methods').html(data);
		}),
		complete: (function () {
			var prev_selected_shipping = $('input[name=shippingModeId]:checked').val();
			if (!prev_selected_shipping) {
				$('input:radio[name=shippingModeId]:first').prop('checked', true);
			}
			Ajax_GetOrder_Summery();
		})
	});
}

// Added code for Avalara Tax as on 10-10-2022 Start

function Ajax_GetOrder_Summery() {
	var frmObj = document.frmCheckOut; // form object
	var total_shipMethod = parseInt($("#count_shipmethod").val());
	var total_paymentMethod = parseInt($("#count_paymentmethod").val());

	if (total_shipMethod <= 0 || total_paymentMethod <= 0) {
		//$('#OrderSummaryDiv').html('');
		//return false;
	}


	var ship_country = frmObj.sh_country.value;
	var ship_zip = frmObj.sh_zip.value;
	var ship_city = frmObj.sh_city.value;
	var ship_addr_1 = frmObj.sh_Addr1.value;
	var ship_addr_2 = frmObj.sh_Addr2.value;

	if (ship_country == "US")
		var ship_state = frmObj.sh_state.value;
	else
		var ship_state = frmObj.sh_otherstate.value;

	var shipping_mode_id = $('input[name=shippingModeId]:checked').val();
	var ShippingCharge = $('input[name=shippingModeId]:checked').data('charge');
	var ShippingMethodName = $('input[name=shippingModeId]:checked').data('shipname');
	var PaymentMethod = $('input[name=paymentMethod]:checked').val();

	var checking = $('#checking').val();


	var STR_POST_VAR = 'ship_country=' + ship_country;
	var STR_POST_VAR = STR_POST_VAR + '&ship_addr_1=' + ship_addr_1;
	var STR_POST_VAR = STR_POST_VAR + '&ship_addr_2=' + ship_addr_2;
	var STR_POST_VAR = STR_POST_VAR + '&ship_state=' + ship_state;
	var STR_POST_VAR = STR_POST_VAR + '&ship_zip=' + ship_zip;
	var STR_POST_VAR = STR_POST_VAR + '&ship_city=' + ship_city;
	var STR_POST_VAR = STR_POST_VAR + '&shipping_mode_id=' + shipping_mode_id;
	var STR_POST_VAR = STR_POST_VAR + '&ShippingCharge=' + ShippingCharge;
	var STR_POST_VAR = STR_POST_VAR + '&ShippingMethodName=' + ShippingMethodName;
	var STR_POST_VAR = STR_POST_VAR + '&paymentMethod=' + PaymentMethod;
	var STR_POST_VAR = STR_POST_VAR + '&checking=' + checking;
	var STR_POST_VAR = STR_POST_VAR + '&_token=' + $('meta[name="csrf-token"]').attr('content');

	$.ajax({
		type: 'POST',
		url: base_url_new + '/checkout-order-summary-ajax',
		dataType: "html",
		data: STR_POST_VAR,
		beforeSend: function () {
			var html_loader = '<img src="' + base_url_new + '/images/ajax-loader.gif"><br><span class="error">Please wait while data are Calulating....<br>if it takes more than 10-15 seconds then please refresh the page.</span>';
			$('#OrderSummaryDiv').html(html_loader);
		},
		success: (function (data, status) {
			var result = data;
			$('#OrderSummaryDiv').html('');
			$('#OrderSummaryDiv').html(result);
		}),
		complete: (function () {
			$('#OrderSummaryDiv').show();
		})
	});
}

function avaTaxValAddr() {
	return true;
	
	var avalara_valid_cnt = parseInt($("#avalara_valid_cnt").val());

	if (avalara_valid_cnt == 1) {
		var URL = base_url_new + '/avatax-validate-addr-ajax';

		var frmObj = document.frmCheckOut;
		var ship_country = frmObj.sh_country.value;
		var ship_zip = frmObj.sh_zip.value;
		var ship_city = frmObj.sh_city.value;
		var ship_addr_1 = frmObj.sh_Addr1.value;
		var ship_addr_2 = frmObj.sh_Addr2.value;

		if (ship_country == "US")
			var ship_state = frmObj.sh_state.value;
		else
			var ship_state = frmObj.sh_otherstate.value;

		var STR_POST_VAR = '';
		var STR_POST_VAR = 'ship_country=' + ship_country;
		var STR_POST_VAR = STR_POST_VAR + '&ship_addr_1=' + ship_addr_1;
		var STR_POST_VAR = STR_POST_VAR + '&ship_addr_2=' + ship_addr_2;
		var STR_POST_VAR = STR_POST_VAR + '&ship_state=' + ship_state;
		var STR_POST_VAR = STR_POST_VAR + '&ship_zip=' + ship_zip;
		var STR_POST_VAR = STR_POST_VAR + '&ship_city=' + ship_city;
		var STR_POST_VAR = STR_POST_VAR + '&_token=' + $('meta[name="csrf-token"]').attr('content');
		var is_valid_addr = 'yes';
		return $.ajax({
			type: 'POST',
			url: URL,
			data: STR_POST_VAR,
			cache: false,
			beforeSend: function () { },
			success: function (result) {
				var error_str = JSON.stringify(result);
				if (result.includes('Invalid##')) {
					var result_arr = result.split("##");
					var dis_html = "Your address is Invalid." + result_arr[1];
					$("#av_tax_error").html(dis_html);
					is_valid_addr = 'no';

					$('html, body').animate({
						scrollTop: $("#av_tax_error").offset().top - 200
					}, 2000);
				}

				if (result.includes('ValidZip##')) {
					var result_arr = result.split("##");
					if (result_arr[2] != '') {
						$("#poperror_bl_Addr2").parent('div').show();
					}

					if (result_arr.length > 0) {
						$("#suggestShipping-popup").modal('show');
						$("#poperror_bl_Addr1").html(result_arr[1]);
						$("#poperror_bl_Addr2").html(result_arr[2]);
						$("#poperror_bl_city").html(result_arr[3]);
						$("#poperror_bl_state").html(result_arr[4]);
						$("#poperror_bl_country").html(result_arr[5]);
						$("#poperror_bl_zip").html(result_arr[6]);
						if (ship_addr_2 != '') {
							$("#addr2").parent('div').show();
						}
						$("#addr1").html(ship_addr_1);
						$("#addr2").html(ship_addr_2);
						$("#addr_city").html(ship_city);
						$("#addr_state").html(ship_state);
						$("#addr_country").html(ship_country);
						$("#addr_zip").html(ship_zip);

						is_valid_addr = 'zip';
					}
				}

				//var avalara_valid_cnt = 2;
				//$("#avalara_valid_cnt").val(avalara_valid_cnt);

				if (is_valid_addr == 'no') {
					/*
					$('html, body').animate({
						scrollTop: $("#billingAddrSection").offset().top - 200
					}, 2000);
					*/
					return false;
				}
				else if (is_valid_addr == 'zip') {
					var avalara_valid_cnt = 2;
					$("#avalara_valid_cnt").val(avalara_valid_cnt);
					/*
					$('html, body').animate({
						scrollTop: $("#billingAddrSection").offset().top - 200
					}, 2000);
					*/
					return false;
				}
				else {
					var avalara_valid_cnt = 2;
					$("#avalara_valid_cnt").val(avalara_valid_cnt);
				}

				/*
				$('html, body').animate({
					scrollTop: $("#reviewSection").offset().top - 200
				}, 2000);
				*/

				return false;
			}
		});
	}
	else {
		return true;
	}
}

function suggest_addr() {
	var addr1 = $("#poperror_bl_Addr1").html();
	var addr2 = $("#poperror_bl_Addr2").html();
	var city = $("#poperror_bl_city").html();
	var state = $("#poperror_bl_state").html();
	var country = $("#poperror_bl_country").html();
	var zip = $("#poperror_bl_zip").html();

	var frmObj = document.frmCheckOut;

	$("#sh_Addr1").val(addr1);
	$("#sh_Addr2").val(addr2);
	$("#sh_city").val(city);
	$("#sh_state").val(state);
	$("#sh_zip").val(zip);
	$("#sh_country").val(country);

	$("#suggestShipping-popup").modal('hide');

	edit_checkout_step('shipping');
	valid_shipping_detail();

	$('html, body').animate({
		scrollTop: $("#c-step-2").offset().top - 20
	}, 100);
}

function entered_addr() {
	$("#suggestShipping-popup").modal('hide');
	edit_checkout_step('shipping');
	valid_shipping_detail();

	$('html, body').animate({
		scrollTop: $("#c-step-2").offset().top - 20
	}, 100);
}

// Added code for Avalara Tax as on 10-10-2022 End

function show_billing_address() {
	var frmObj = document.frmCheckOut;
	if (frmObj.same_asship.checked == false) {
		$('#billing_adddress_box').show();
	}
	else {
		$('#billing_adddress_box').hide();
	}
	// GET AVAILABE SHIP METHOD ON PAGE LOAD

	//Ajax_GetPaymentMethod();

	return;
}function show_payment_method(flagShow='false') {
	if(flagShow == 'true'){
		$('#payment_method').addClass('active');
		$('#payment-form-section').removeClass('dnone');
		$('div[data-paymnet-id="1"]').addClass('active');
		$('input[data-paymnet-id="1"]').prop('checked',true);
		$event = 'input[data-paymnet-id="1"]';
		change_payment_method($event);
	}else{
		$('#payment_method').removeClass('active');
		$('#payment-form-section').addClass('dnone');
		$('div[data-paymnet-method=""]').removeClass('active');
	}
}
function show_shipping_method(flagShow='false') {
	if(flagShow == 'true'){
		$('.shipping-method').addClass('active');
		if($('#shipping_method_disp').text() != ""){
			$('.shipping-expected-delivery').removeClass('dnone');
			$('#shipping_method_disp').removeClass('dnone');
		}else{
			$('.shipping-expected-delivery').addClass('dnone');
			$('#shipping_method_disp').addClass('dnone');
		}
		$('#shipping_method_disp').removeClass('dnone');
		$('.shipping-method-content-js').removeClass('dnone');

	}else{
		$('.shipping-method').removeClass('active');
		if($('#shipping_method_disp').text() != ""){
			$('.shipping-expected-delivery').removeClass('dnone');
			$('#shipping_method_disp').removeClass('dnone');
		}else{
			$('.shipping-expected-delivery').addClass('dnone');
			$('#shipping_method_disp').addClass('dnone');
		}
		
		$('.shipping-method-content-js').addClass('dnone');
	}
}


function valid_payment_detail() 
{
	var pFlg = false;  // Payment method checked flag
	var sel_paymentMethod = '';

	if (!$("#frmCheckOut").valid()) {
		alert('Please check billing & shipping Information');
		return false;
	}

	var total_paymentMethod = parseInt($("#count_paymentmethod").val());
	for (i = 0; i < total_paymentMethod; i++) 
	{
		var varnam = 'paymentMethod' + i;
		if (document.getElementById(varnam)) 
		{
			if (document.getElementById(varnam).checked == true) 
			{
				$("#frmCheckOut div#error_paymentMethod").html('');
				var sel_paymentMethod = document.getElementById(varnam).value;
				var payment_method = ''
				if (sel_paymentMethod != "") {
					payment_method = sel_paymentMethod
					$("#payment_method").val(payment_method);
					save_stepone();
				}
				pFlg = true;
				break;
			}
		}
	}

	if (pFlg == false) {
		$("#frmCheckOut div#error_paymentMethod").html('Please select payment method');
		return false;
	}

	// Do not submit if braintree cc selected as it submited via separate function - bt_creditcard_setup()
	if ($('input[name=paymentMethod]:checked').val() == 'PAYMENT_BRAINTREECC') {
		return false;
	}

	var frmObj = document.frmCheckOut; // form object
	frmObj.action 	= base_url_new+'/checkout-action';
	//frmObj.action = base_url_new + '/order-confirm';
	Ajax_GetOrder_Summery();
	frmObj.submit();
}

function check_newsletter() {
	var news_mail = $("#bottom_email").val();

	if (news_mail == '') {
		$("#success_bottom_email").html('');
		$("#error_bottom_email").addClass("error");
		$("#error_bottom_email").html("Please enter email address.");
	} else {
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (!regex.test(news_mail)) {
			$("#success_bottom_email").html('');
			$("#error_bottom_email").addClass("error");
			$("#error_bottom_email").html("Please enter valid email address.");
		} else {
			$("#error_bottom_email").html("");
			$.ajax({
				type: 'POST',
				url: base_url_new + "/newsletter",
				data: "newsletter_email=" + news_mail,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function (data) {
					$("#success_bottom_email").addClass("success");
					$('#bottom_email').val('');
					$("#success_bottom_email").html(data.msg);
				},
				error: function (err) {
					console.log(err);
				},
			});
		}
	}
}



/* Braintree Functions Start */

$(document).ready(function () {
	bt_creditcard_setup();
});


function bt_creditcard_setup() {
	if (parseInt($("input[type=radio][name=paymentMethod][value=PAYMENT_BRAINTREECC]").length) <= 0) {
		return;
	}
	if (parseInt($("#is_bt_express_checkout").val()) == 1 && $("#bt_payment_method_nonce").val() != '') {
		return;
	}

	var BT_FORM = $('#frmCheckOut');

	var BT_SUBMIT_BUTTON = $('#btnPlaceOrder-cc');

	braintree.client.create({ authorization: $('#BRAINTREE_TOKENIZATION_KEY').val() },
		//console.log(clientInstance);
		//return false;
		function (err, clientInstance) {
			if (err) {
				console.error(err);
				return;
			}


			braintree.hostedFields.create({
				client: clientInstance,
				styles: {
					input: {
						// change input styles to match
						// bootstrap styles
						'font-size': '1rem',
						'color': '#495057'
					}
				},
				fields: {
					cardholderName: {
						selector: '#cc-name',
						placeholder: 'Name as it appears on your card'
					},
					number: {
						selector: '#cc-number',
						placeholder: '4111 1111 1111 1111'
					},
					cvv: {
						selector: '#cc-cvv',
						placeholder: '123'
					},
					expirationDate: {
						selector: '#cc-expiration',
						placeholder: 'MM / YY'
					}
				}
			},
				function (err, hostedFieldsInstance) {
					if (err) {
						console.error(err);
						return;
					}

					function createInputChangeEventListener(element) {
						return function () {
							validateInput(element);
						}
					}

					function setValidityClasses(element, validity) {
						if (validity) {
							element.removeClass('is-invalid');
							element.addClass('is-valid');
						}
						else {
							element.addClass('is-invalid');
							element.removeClass('is-valid');
						}
					}

					function validateInput(element) {
						// very basic validation, if the
						// fields are empty, mark them
						// as invalid, if not, mark them
						// as valid

						if (!element.val().trim()) {
							setValidityClasses(element, false);

							return false;
						}

						setValidityClasses(element, true);

						return true;
					}

					function validateEmail() {
						var baseValidity = validateInput(email);

						if (!baseValidity) {
							return false;
						}

						if (email.val().indexOf('@') === -1) {
							setValidityClasses(email, false);
							return false;
						}

						setValidityClasses(email, true);
						return true;
					}

					var ccName = $('#cc-name');
					ccName.on('change', function () {
						validateInput(ccName);
					});

					//var email = $('#email');
					//email.on('change', validateEmail);


					hostedFieldsInstance.on('validityChange', function (event) {
						var field = event.fields[event.emittedBy];

						// Remove any previously applied error or warning classes
						$(field.container).removeClass('is-valid');
						$(field.container).removeClass('is-invalid');

						if (field.isValid) {
							$(field.container).addClass('is-valid');
						}
						else if (field.isPotentiallyValid) {
							// skip adding classes if the field is
							// not valid, but is potentially valid
						}
						else {
							$(field.container).addClass('is-invalid');
						}
					});

					hostedFieldsInstance.on('cardTypeChange', function (event) {
						//var cardBrand = $('#card-brand');
						var cvvLabel = $('[for="cc-cvv"]');

						if (event.cards.length === 1) {
							var card = event.cards[0];

							// change pay button to specify the type of card
							// being used
							//cardBrand.text(card.niceType);
							// update the security code label
							cvvLabel.text(card.code.name);
						}
						else {
							// reset to defaults
							//cardBrand.text('Card');
							cvvLabel.text('CVV');
						}
					});

					//BT_FORM.submit(function(event) 
					BT_SUBMIT_BUTTON.click(function (event) {
						event.preventDefault();

						if ($('input[name=paymentMethod]:checked').val() != 'PAYMENT_BRAINTREECC') {
							//console.log('no cc method');
							return;
						}


						var formIsInvalid = false;
						var state = hostedFieldsInstance.getState();

						// perform validations on the non-Hosted Fields
						// inputs
						/*
						if (!validateEmail()) 
						{
						  formIsInvalid = true;
						}
						*/

						// Loop through the Hosted Fields and check
						// for validity, apply the is-invalid class
						// to the field container if invalid
						Object.keys(state.fields).forEach(function (field) {
							if (!state.fields[field].isValid) {
								$(state.fields[field].container).addClass('is-invalid');
								formIsInvalid = true;
							}
						});

						if (formIsInvalid) {
							// skip tokenization request if any fields are invalid
							return;
						}

						hostedFieldsInstance.tokenize(function (err, payload) {
							if (err) {
								console.error(err);
								return;
							}

							// This is where you would submit payload.nonce to your server

							// you can either send the form values with the payment
							// method nonce via an ajax request to your server,
							// or add the payment method nonce to a hidden inpiut
							// on your form and submit the form programatically
							// $('#payment-method-nonce').val(payload.nonce);

							//console.log(payload.nonce);
							//console.log(payload.type);	
							//console.log(JSON.stringify(payload));

							//return false;
							
							$('#bt_payment_method_nonce').val(payload.nonce);
							
							//return false;
							$("#order-process-msg").removeClass('dnone');
							$("#btnPlaceOrder").addClass('dnone');
							$("div.placeorder-btn").addClass('dnone');
							$("#bt-dropin-wrapper").addClass('dnone');


							setTimeout(function () {
								$("#order-process-msg").addClass('dnone');
								$("#btnPlaceOrder").removeClass('dnone');
								$("div.placeorder-btn").removeClass('dnone');
								$("#bt-dropin-wrapper").removeClass('dnone');
							}, 30000);


							setTimeout(function () {
								//BT_FORM.attr('action', SITE_URL+'checkout-action');
								//BT_FORM.submit();

								var frmObj = document.frmCheckOut; // form object
								frmObj.action 	= base_url_new+'/checkout-action';
								//frmObj.action = base_url_new + '/order-confirm';
								save_stepone();
								frmObj.submit();

							}, 500);

						});
					});
				});
		});

}

function bt_dropin_payment_setup() {
	$('#dropin-container').empty();
	if (parseInt($("#is_bt_express_checkout").val()) == 1 && $("#bt_payment_method_nonce").val() != '') {
		return;
	}
	if(parseInt($("#ga_order_total_amount").val())<=0 || 
	   parseInt($("#ga_order_total_amount").length) <=0)
	{
		return;
	}
	/*if ($('#paypalec').val() == 1) {
		return;
	}*/
	//alert('11');
	var BT_DROPIN_SUBMIT_BUTTON = document.querySelector('#bt-dropin-submit-button');
	
	braintree.dropin.create({
		authorization: $('#BRAINTREE_TOKENIZATION_KEY').val(),
		container: '#dropin-container',
		paymentOptionPriority: ['card', 'paypal', 'googlePay', 'applePay'],
		card: false,
		/*card: {cardholderName: true,cvv:true},*/
		paypal: {
			flow: 'checkout',
			amount: $('#ga_order_total_amount').val(),
			currency: 'USD'
		},
		applePay: {
			displayName: 'hbasales.com',
			paymentRequest: {
				total: {
					label: 'Final Order Total',
					amount: $('#ga_order_total_amount').val()
				},
				requiredBillingContactFields: ["postalAddress"]
			}
		},
		googlePay: {
			googlePayVersion: 2,
			merchantId: $('#BRAINTREE_GOOGLE_MERCHANT_ID').val(),
			transactionInfo: {
				totalPriceStatus: 'ESTIMATED', //'FINAL',
				totalPrice: $('#ga_order_total_amount').val(),
				currencyCode: 'USD'
			},
			allowedPaymentMethods: [{
				type: 'CARD',
				parameters: {
					billingAddressRequired: true,
					billingAddressParameters: {
						format: 'FULL',
						phoneNumberRequired: false
					}
				}
			}]
		}
	},
		function (err, dropinInstance) {
			if (err) {
				console.error(err);
				return;
			}
			BT_DROPIN_SUBMIT_BUTTON.addEventListener('click', sendNonceToServer);

			function sendNonceToServer() {
				dropinInstance.requestPaymentMethod(function (err, payload) {
					if (err) {
						console.error(err);
						return;
					}
					// Submit payload.nonce to your server
					console.log('submit');
					console.log(payload.nonce);
					console.log(payload.type);
					console.log(JSON.stringify(payload));
					//return false;
					$('#bt_payment_method_nonce').val(payload.nonce);
					if (payload.type == 'PayPalAccount') {
						//$('#action_paypal').val('bt_express_checkout');
						$("input[type=radio][name=paymentMethod][value=PAYMENT_BRAINTREEPAYPAL]").prop('checked', true);
						sh_cc_row();
					}
					if (payload.type == 'AndroidPayCard') {
						$("input[type=radio][name=paymentMethod][value=PAYMENT_BRAINTREEGOOGLEPAY]").prop('checked', true);
						sh_cc_row();
					}
					if (payload.type == 'ApplePayCard') {
						$("input[type=radio][name=paymentMethod][value=PAYMENT_BRAINTREEAPPLEPAY]").prop('checked', true);
						sh_cc_row();
					}

					if (payload.type == 'CreditCard') {
						$("input[type=radio][name=paymentMethod][value=PAYMENT_BRAINTREECC]").prop('checked', true);
						sh_cc_row();
					}
					$("#order-process-msg").removeClass('dnone');
					$("#btnPlaceOrder").addClass('dnone');
					$("div.placeorder-btn").addClass('dnone');
					$("#bt-dropin-wrapper").addClass('dnone');

					setTimeout(function () {
						$("#order-process-msg").addClass('dnone');
						$("#btnPlaceOrder").removeClass('dnone');
						$("div.placeorder-btn").removeClass('dnone');
						$("#bt-dropin-wrapper").removeClass('dnone');
					}, 30000);

					setTimeout(function () {
						//BT_FORM.attr('action', SITE_URL+'checkout-action');
						//BT_FORM.submit();

						var frmObj = document.frmCheckOut; // form object
						frmObj.action = SITE_URL + 'checkout-action';
						frmObj.submit();

					}, 500);
				});
			}

			dropinInstance.on('paymentMethodRequestable', function (event) {
				console.log(event.type); // The type of Payment Method, e.g 'CreditCard', 'PayPalAccount'.
				console.log(event.paymentMethodIsSelected); // true if a customer has selected a payment method when paymentMethodRequestable fires
				// if the nonce is already available (via PayPal authentication
				// or by using a stored payment method), we can request the
				// nonce right away. Otherwise, we wait for the customer to
				// request the nonce by pressing the submit button once they
				// are finished entering their credit card details. This is
				// particularly important if your credit card form includes a
				// postal code input. The `paymentMethodRequestable` event
				// could fire before the customer has finished entering their
				// postal code. (International postal codes can be as few as 3
				// characters in length)
				if (event.paymentMethodIsSelected) {
					sendNonceToServer();
				}
			});
			dropinInstance.on('paymentOptionSelected', function (event) {
				//Console log the value, confirm it's correct before setting the config.
				console.log($('#ga_order_total_amount').val());

				console.log('paymentOptionSelected');
				console.log(JSON.stringify(event));

				var selectedpayemtType = null;
				if (event.type != null) {
					selectedpayemtType = event.type;
				}
				else if (event.paymentOption != null) {
					selectedpayemtType = event.paymentOption;
				}

				if (selectedpayemtType != null) {
					if (selectedpayemtType == 'PayPalAccount' || selectedpayemtType == 'paypal') {
						
						dropinInstance.updateConfiguration('paypal', 'amount', $('#ga_order_total_amount').val());
					}

					if (selectedpayemtType == 'AndroidPayCard' || selectedpayemtType == 'googlePay') {
						//dropinInstance.updateConfiguration('googlePay', 'amount', $('#ga_order_total_amount').val());
						dropinInstance.updateConfiguration('googlePay',
							'transactionInfo', {
							totalPriceStatus: 'ESTIMATED', //'FINAL',
							totalPrice: $('#ga_order_total_amount').val(),
							currencyCode: 'USD'
						});
					}
					if (selectedpayemtType == 'ApplePayCard' || selectedpayemtType == 'applePay') {
						//dropinInstance.updateConfiguration('applePay','amount', $('#ga_order_total_amount').val());
						dropinInstance.updateConfiguration('applePay',
							'paymentRequest', {
							total: {
								label: 'Final Order Total',
								amount: $('#ga_order_total_amount').val()
							},
							requiredBillingContactFields: ["postalAddress"]
						});
					}
				}
			});
			dropinInstance.on('changeActiveView', function (event) {
				console.log(event.previousViewId);
				console.log(event.newViewId);
				if (event.newViewId == 'card') {
					$('#bt-dropin-submit-button').removeClass('dnone');
				}
				else {
					$('#bt-dropin-submit-button').addClass('dnone');
				}
			});
		});
}

function sh_cc_row() 
{
	//$('.ind-paymethod-button').addClass('dnone');
	$('.checkout-acd').addClass('dnone');
	$('input[name=paymentMethod]:checked').parents('.ind-paymethod-wrapper').find('.ind-paymethod-button').removeClass('dnone');
}

function applyCoupon() {
	//alert('sdsd');
	if ($("#coupon").val()) {
		$("#coupon_alert").hide();
		var token = $('meta[name="csrf-token"]').attr('content');
		var coupon_code = $("#coupon").val().trim();
		$.ajax({
			type: 'POST',
			url: site_url + '/cart_action',
			headers: {
				'X-CSRF-TOKEN': token
			},
			data: {
				coupon_code: coupon_code,
				action: 'apply_coupon'
			},
			success: function (data) {
				//console.log(data);				
				$(".cart_table").html(data.cart_details);
				$("#order_summary").html(data.cart_summary);
				$("#total_qty").html(data.TotalQty + " item(s)");
				$(".cart-item-count").text(data.TotalQty);
				if (data.CouponDiscount == 0) {
					if (coupon_code != '') {
						$("#coupon_alert").css("color", "red").show();
					}
				} else {
					$("#coupon").val(coupon_code).attr("readonly", "readonly");
					$("#coupon_apply").hide();
					$("#coupon_remove").show();
					$("#coupon_alert").html("Coupon applied successfully, Got discount of $" + data.CouponDiscount).css("color", "green").show();
				}
				Ajax_GetOrder_Summery();
				$("#page-spinner").hide();
			}
		});
	} else {
		$("#coupon_alert").show();
	}
}
function removeCoupon() {
	var token = $('meta[name="csrf-token"]').attr('content');
	var coupon_code = $("#coupon").val().trim();
	if (coupon_code != '') {
		if (confirm("Are you sure, you want to remove coupon code?")) {
			$("#page-spinner").show();
			$.ajax({
				type: 'POST',
				url: site_url + '/cart_action',
				headers: {
					'X-CSRF-TOKEN': token
				},
				data: {
					coupon_code: coupon_code,
					action: 'remove_coupon'
				},
				success: function (data) {
					$(".cart_table").html(data.cart_details);
					$("#order_summary").html(data.cart_summary);
					$("#total_qty").html(data.TotalQty + " item(s)");
					$(".cart-item-count").text(data.TotalQty);

					$("#coupon").val("").removeAttr("readonly");//	.attr("readonly","readonly");
					$("#coupon_remove").hide();
					$("#coupon_apply").show();
					$("#coupon_alert").html("Coupon removed successfully").css("color", "red").show();
					$("#page-spinner").hide();
					Ajax_GetOrder_Summery();
				}
			})
		}
	}

}


$(document).ready(function () {
	//bt_dropin_payment_setup();
});

/* Braintree Functions End */