var wdth = document.body.clientWidth;
$(window).bind('resize orientationchange', function () {
	wdth = document.body.clientWidth;
});
function ShowTab(num) {
	if (wdth <= 769) {
		if ($('#tab-' + num).hasClass('active')) {
			$('#tab-' + num).removeClass('active');
			$('.anchor-' + num).removeClass('active');
		} else {
			$('#tab-' + num).addClass('active');
			$('.anchor-' + num).addClass('active');
			for (var i = 1; i <= 3; i++) {
				if (i != num) {
					$('#tab-' + i).removeClass('active');
					$('.anchor-' + i).removeClass('active');
				}
			}
		}
	} else {
		$("#tab-1").removeClass("active");
		$(".anchor-1").removeClass("active");

		$("#tab-2").removeClass("active");
		$(".anchor-2").removeClass("active");

		$("#tab-3").removeClass("active");
		$(".anchor-3").removeClass("active");

		$('#tab-' + num).addClass('active');
		$('.anchor-' + num).addClass('active');
	}
	//$('.cart-tabs .hd1 a, .cart-tabs .hd2 a').removeClass('active');
	//$('.anchor-' + num).addClass('active');

	//$('.cart-tabs .content').removeClass('active');
	//$('#tab-' + num).addClass('active');
}
function ShowTab11(num) {
	$('.cart-tabs .hd1 a, .cart-tabs .hd2 a').removeClass('active');
	$('.anchor-' + num).addClass('active');

	$('.cart-tabs .content').removeClass('active');
	$('#tab-' + num).addClass('active');
}
$(document).on('click', '.btn-number', function () {
	var data_field = $(this).attr('data-field');
	var action_type = $(this).attr('data-type');
	var current_val = parseInt($("." + data_field).val());
	$("#page-spinner").show();
	if (!isNaN(current_val)) {
		if (action_type == "plus") {
			current_val = current_val + 1;
			$("." + data_field).val(current_val);
		} else if (action_type == "minus") {
			if (current_val > $("." + data_field).attr('min')) {
				current_val = current_val - 1;
				$("." + data_field).val(current_val).change();
			}
			if (parseInt($("." + data_field).val()) == $("." + data_field).attr('min')) {
				$(this).attr('disabled', true);
			}
		}
	}
	updateCart(data_field, current_val);
	//getCartDetails();
	$("#page-spinner").hide();
})

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
				$(".prod_"+cart_id).val(prod_qty - 1);
				alert(data.CartErrors);
			}
			else if (data.cart_empty != '') {
				$("#cart_main").html(data.cart_empty);
			} else {
				$(".cart_table").html(data.cart_details);
				$("#order_summary").html(data.cart_summary);
				/*$("#total_qty").html(data.total_qty+" item(s)");
				$(".cart-item-count").text(data.total_qty);*/
				$("#total_qty").html(data.TotalQty + " item(s)");
				$("#cart-counter").text(data.TotalQty);
				if (data.cart_empty != '') {
					$(".checkout_left").html(data.cart_empty);
				}
			}
			$("#page-spinner").hide();
		}
	});

}

function getCartDetails() {
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		url: site_url + '/cart_details',
		headers: {
			'X-CSRF-TOKEN': token
		},
		success: function (data) {
			console.log(data);
			$(".cart_table").html(data.cart_details);
		}
	});
}

// function closesidepanel(){
// 	//$("#sb-slidebar").hide();
// 	$(".sb-toggle-right").hide();
// }

// $(document).on('click','.sb-slidebar',function(){
// 	$(".sp-close").on('click',function(){
// 		$(".sb-toggle-right").click();
// 	});
// 	//alert($(".sp-close").html());
// 	//$(".sp-close").attr("onclick",'closesidepanel()')
// 	// alert($(this).find('.sb-cart-close').html());
// 	// $(this).find('.svg_close').on("click", function(){
// 	// 	//alert(123);
// 	// 	$("#sb-slidebar").hide();
// 	// })
// 	//alert(12313);
// 	//$("#sb-slidebar").hide();
// })



$(document).on('click', '.acc_cart', function () {

	var product_id = $(this).attr('data-productid');
	var prod_qty = 1;
	var btn_from = 'cart_accessories';
	var prod_list = $("#prodid_list").val();
	var prodid_arr = prod_list.split(",");
	var prod_index_cartid = prodid_arr.indexOf(product_id);
	if (prod_index_cartid > -1) {
		if (confirm('Are you sure you want to remove this item from cart?')) {
			$("#page-spinner").show();
			removeFromCart(prod_index_cartid);
			$("#page-spinner").hide();
		}
	} else {
		if (confirm('Are you sure you want to add this item in cart?')) {
			$("#page-spinner").show();
			AddToCart(product_id, prod_qty, btn_from);
			$("#page-spinner").hide();
		}
	}


});

// Accordion
$(document).ready(function(){
	$(".coupon-code .h4").eq(0).addClass("active");
	$(".coupon-code .coupon-content").eq(0).show();
	$(".coupon-code .h4").click(function(){
		$(this).next(".coupon-content").slideToggle("slow").siblings('.coupon-content').slideUp();
		$(this).toggleClass("active").siblings('.h4').removeClass("active");
	});
});

/*function GetAccCart() {
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		url: site_url + '/cart_action',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data: {
			action: 'getcart'
		},
		success: function (data) {
			//console.log(data);
			// $(".cart_table").html(data.cart_details);
			// $("#order_summary").html(data.cart_summary);
			// $("#total_qty").html(data.total_qty+" item(s)");
			// $("#page-spinner").hide();
			//console.log(data);
			$(".cart_table").html(data.cart_details);
			$("#order_summary").html(data.cart_summary);
			$("#total_qty").html(data.TotalQty + " item(s)");
			$(".cart-item-count").text(data.TotalQty);
			if (data.cart_empty != '') {
				$(".checkout_left").html(data.cart_empty);
				$("#total_qty").hide();
				$(".checkout_title p").hide();
			}
			$("#page-spinner").hide();

		}
	});
}*/
$(document).on('click', '.wishlist', function () {
	var product_id = $(this).attr('data-productid');
	var sku = $(this).attr('data-sku');
	var desc = $(this).attr('data-description');
	var token = $('meta[name="csrf-token"]').attr('content');
	if (product_id != '') {
		$.ajax({
			type: 'POST',
			url: site_url + '/cart_action',
			headers: {
				'X-CSRF-TOKEN': token
			},
			data: {
				product_id: product_id,
				sku: sku,
				desc: desc,
				action: 'add_wishlist'
			},
			success: function (data) {
				//alert(data);
				//console.log(data);
				/*$(".cart_table").html(data.cart_details);
				$("#order_summary").html(data.cart_summary);
				$("#total_qty").html(data.total_qty+" item(s)");
				$("#page-spinner").hide();*/
			}
		});
	}
})

$(document).on('click', '.removeCartItem', function () {
	if (confirm('Are you sure you want to remove the item?')) {
		$("#page-spinner").show();
		var token = $('meta[name="csrf-token"]').attr('content');
		var cart_id = $(this).attr('data-index');
		removeFromCart(cart_id);
	}
})

function removeFromCart(cart_id) {
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		url: site_url + '/cart_action',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data: {
			cart_id: cart_id,
			action: 'remove'
		},
		success: function (data) {
			$(".cart_table").html(data.cart_details);
			$("#order_summary").html(data.cart_summary);
			$("#total_qty").html(data.TotalQty + " item(s)");
			if (data.TotalQty == "0") {
				$("#cart-counter").remove();
			} else {
				$("#cart-counter").text(data.TotalQty);
			}
			if (data.cart_empty != '') {
				$(".checkout_left").html(data.cart_empty);
				$("#total_qty").hide();
				$(".checkout_title p").hide();
			}
			$("#page-spinner").hide();
			GetAccCart();
				GetCart();

		}
	});
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
					$("#cart-counter").text(data.TotalQty);

					$("#coupon").val("").removeAttr("readonly");//	.attr("readonly","readonly");
					$("#coupon_remove").hide();
					$("#coupon_apply").show();
					$("#coupon_alert").html("Coupon removed successfully").css("color", "red").show();
					$("#page-spinner").hide();
				}
			})
		}
	}
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
				$("#cart-counter").text(data.TotalQty);
				if (data.CouponDiscount == 0) {
					if(coupon_code != '')
					{	
						$("#coupon_alert").css("color", "red").show();
					}
				} else {
					$("#coupon").val(coupon_code).attr("readonly", "readonly");
					$("#coupon_apply").hide();
					$("#coupon_remove").show();
					$("#coupon_alert").html("Coupon applied successfully, Got discount of $" + data.CouponDiscount).css("color", "green").show();
				}
				$("#page-spinner").hide();
			}
		});
	} else {
		$("#coupon_alert").show();
	}
}

$(document).on('input propertychange paste', '.input-number', function () {
	$("#page-spinner").show();
	var data_field = $(this).attr('data-field');
	var current_val = $(this).val();
	updateCart(data_field, current_val);
})

/*$(window).load(function () {
	ShowTab(1);
});*/


$('.btn-number').click(function (e) {

	e.preventDefault();

	fieldName = $(this).attr('data-field');

	type = $(this).attr('data-type');

	var input = $("input[name='" + fieldName + "']");

	var currentVal = parseInt(input.val());

	if (!isNaN(currentVal)) {

		if (type == 'minus') {

			if (currentVal > input.attr('min')) {

				input.val(currentVal - 1).change();

			}

			if (parseInt(input.val()) == input.attr('min')) {

				$(this).attr('disabled', true);

			}

			$(this).siblings('.btn-number').attr('disabled', false);

		} else if (type == 'plus') {

			if (currentVal < input.attr('max')) {

				input.val(currentVal + 1).change();

			}

			if (parseInt(input.val()) == input.attr('max')) {

				$(this).attr('disabled', true);

			}

			$(this).siblings('.btn-number').attr('disabled', false);

		}

	} else {

		input.val(0);

	}

	console.log(input.data('index'));
	// Update_Cart_Ind(input.data('index'));

});

/* Braintree Functions Start */

function bt_dropin_payment_setup()
{
	if(parseInt($("#ga_order_total_amount").val())<=0 || 
	   parseInt($("#ga_order_total_amount").length) <=0)
	{
		return;
	}

	var BT_FORM = 	document.querySelector('#frmCheckOut');
	var BT_DROPIN_SUBMIT_BUTTON = document.querySelector('#bt-dropin-submit-button');
	

	braintree.dropin.create({
		//authorization: 'sandbox_gphg6ryj_5pbrbdyrbgxzbw83',
		authorization: $('#BRAINTREE_TOKENIZATION_KEY').val(),
		container: '#bt-dropin-container',
		paymentOptionPriority: ['paypal','googlePay','applePay','card'],
		card: false,
		paypal: {
			flow: 'checkout',
			amount: $('#ga_order_total_amount').val(),
			currency: 'USD',
			enableShippingAddress: true,
			shippingAddressEditable: true,
		//buttonStyle: {layout: 'vertical',color:  'blue',shape:  'rect', label: 'paypal'}
		},
		applePay: {
			displayName: 'Grownbrilliance.com',
			paymentRequest: {
				total: {
					label: 'Estimated Order Total',
					amount: $('#ga_order_total_amount').val()
				},
				requiredBillingContactFields: ["postalAddress"],
				requiredShippingContactFields:["postalAddress", "name", "phone", "email"]
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
			}],
			emailRequired:true,
			shippingAddressRequired:true,
			shippingAddressParameters: 
			{
				phoneNumberRequired: true
			}
			//button: {buttonColor:'default',buttonType:'checkout'}
		}
	}, 
	function (err, dropinInstance) 
	{
		 if (err) 
		  {
			 console.error(err);
			 return;
		  }
		  
		  BT_DROPIN_SUBMIT_BUTTON.addEventListener('click', sendNonceToServer);
		  
		  function sendNonceToServer() 
		  {
			dropinInstance.requestPaymentMethod(function (err, payload) 
			{
			  if (err) 
			  {
				console.error(err);
				 return;
			  }
			  
			  // Submit payload.nonce to your server
			  console.log(payload);
			  console.log(payload.nonce);
			  console.log(payload.type);	
			  console.log(JSON.stringify(payload));
			  
			    $('#bt_express_payment_method_nonce').val(payload.nonce);
				
				if(payload.type == 'PayPalAccount')
				{	
					$('#bt_express_payment_method_type').val('PAYMENT_BRAINTREEPAYPAL');
					
					$('#bl_sh_email').val(payload.details.email);
					$('#sh_fname').val(payload.details.firstName);
					$('#sh_lname').val(payload.details.lastName);
					
					$('#sh_Addr1').val(payload.details.shippingAddress.line1);
					$('#sh_Addr2').val('');
					$('#sh_city').val(payload.details.shippingAddress.city);
					$('#sh_state').val(payload.details.shippingAddress.state);
					$('#sh_otherstate').val(payload.details.shippingAddress.state);
					$('#sh_zip').val(payload.details.shippingAddress.postalCode);
					$('#sh_country').val(payload.details.shippingAddress.countryCode);
				}	
				//alert(payload.details.email);
				
				
				if(payload.type == 'AndroidPayCard')
				{	
					
					$('#bt_express_payment_method_type').val('PAYMENT_BRAINTREEGOOGLEPAY');
					
					$('#bl_sh_email').val(payload.details.rawPaymentData.email);
					
					var g_full_name = payload.details.rawPaymentData.shippingAddress.name;
					
					var g_full_name = g_full_name.replace("  ", " ");
					
					var g_first_name = g_full_name.substring(0, g_full_name.indexOf(' '));
					var g_last_name = g_full_name.substring(g_full_name.indexOf(' ') + 1);
					
					if(g_first_name =='' && g_last_name !='')
					{
						var g_first_name = g_last_name;
					}
					
					$('#sh_fname').val(g_first_name);
					$('#sh_lname').val(g_last_name);
					
					$('#sh_Addr1').val(payload.details.rawPaymentData.shippingAddress.address1);
					$('#sh_Addr2').val(payload.details.rawPaymentData.shippingAddress.address2);
					$('#sh_city').val(payload.details.rawPaymentData.shippingAddress.locality);
					$('#sh_state').val(payload.details.rawPaymentData.shippingAddress.administrativeArea);
					$('#sh_otherstate').val(payload.details.rawPaymentData.shippingAddress.administrativeArea);
					$('#sh_zip').val(payload.details.rawPaymentData.shippingAddress.postalCode);
					$('#sh_country').val(payload.details.rawPaymentData.shippingAddress.countryCode);
					
					$('#sh_phone').val(payload.details.rawPaymentData.shippingAddress.phoneNumber);
				}

				if(payload.type == 'ApplePayCard')
				{	
					
					$('#bt_express_payment_method_type').val('PAYMENT_BRAINTREEAPPLEPAY');
					
					$('#bl_sh_email').val(payload.details.rawPaymentData.shippingContact.emailAddress);
					$('#sh_fname').val(payload.details.rawPaymentData.shippingContact.givenName);
					$('#sh_lname').val(payload.details.rawPaymentData.shippingContact.familyName);
					
					$('#sh_Addr1').val(payload.details.rawPaymentData.shippingContact.addressLines.toString());
					$('#sh_Addr2').val('');
					$('#sh_city').val(payload.details.rawPaymentData.shippingContact.locality);
					$('#sh_state').val(payload.details.rawPaymentData.shippingContact.administrativeArea);
					$('#sh_otherstate').val(payload.details.rawPaymentData.shippingContact.administrativeArea);
					$('#sh_zip').val(payload.details.rawPaymentData.shippingContact.postalCode);
					$('#sh_country').val(payload.details.rawPaymentData.shippingContact.countryCode);
					
					$('#sh_phone').val(payload.details.rawPaymentData.shippingContact.phoneNumber);
				}		
				
			
				$("#bt-dropin-wrapper").addClass('d-none');
				
				setTimeout(function()
				{
					$("#bt-dropin-wrapper").removeClass('d-none');
				}, 30000);
				
				
				setTimeout(function()
				{
					BT_FORM.submit();
					
				}, 500);
			  
			  
			});
		  }
		  
		  dropinInstance.on('paymentMethodRequestable', function (event) 
		  {
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
			if (event.paymentMethodIsSelected) 
			{
			  sendNonceToServer();
			}
			
		  });
		  
		 dropinInstance.on('paymentOptionSelected', function(event) 
		 {
			//Console log the value, confirm it's correct before setting the config.
			console.log($('#ga_order_total_amount').val());
			
			if(event.type == 'PayPalAccount')
			{	
				//dropinInstance.updateConfiguration('paypal','amount', $('#ga_order_total_amount').val());
			}
			
			if(event.type == 'AndroidPayCard')
			{	
				//dropinInstance.updateConfiguration('googlePay','amount', $('#ga_order_total_amount').val());
			}
			
			if(event.type == 'ApplePayCard')
			{	
				//dropinInstance.updateConfiguration('applePay','amount', $('#ga_order_total_amount').val());
			}
			
		 });
		  
	});
}	

function applyCouponNewCustomer() {
	
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
			$("#cart-counter").text(data.TotalQty);
			if (data.CouponDiscount == 0) {
				if(coupon_code != '')
				{
					$("#coupon_alert").css("color", "red").show();
				}
			} else {
				$("#coupon").val(coupon_code).attr("readonly", "readonly");
				if(data.CouponDiscount > 0 && coupon_code == '')
				{
					//alert('dfdf');
					$("#coupon").val('10%OFF').attr("readonly", "readonly");
				}
				$("#coupon_apply").hide();
				$("#coupon_remove").show();
				$("#coupon_alert").html("Coupon applied successfully, Got discount of $" + data.CouponDiscount).css("color", "green").show();
			}
			$("#page-spinner").hide();
		}
	});
	
}

function show_user_register_popup()
{
	//if($('#is_customer_login').val()==0)
	//{
		$("#myModalPopUpLogin").html('');
		$("#page-spinner").show();
		var email_val = '';
		if($('#bl_sh_email').val() != "")
		{
			email_val = $('#bl_sh_email').val();
		}
		var str = "isAction=register_popup&action=signup&checkoutVal=Yes&email_val="+email_val;
		$.ajax({
			type: "POST",
			url: site_url + "/popup",
			data: str,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}

		}).done(function (msg) 
		{
			$("#page-spinner").hide();		
			$("#myModalPopUpLogin").html(msg);
			$('#myModalPopUpLogin').modal('show');
		});
	//}	
}

function valid_user_sign_up()
{
	$('#frmCheckoutRegister').data('validator', null);
	$("#frmCheckoutRegister").unbind('validate');

	$('#frmCheckoutRegister').validate({
		ignore: ":hidden:not(#keycode)",
		rules: 
		{
			firstname: { required: true },
			lastname: { required: true },
			phone: { required: true,minlength: 10,maxlength: 10 },
			emailreg: { required: true,email: true },
			cpassword: { required: true,minlength: 6  }
		},
		messages: 
		{
			firstname:{ required: "Please enter first name"},
			lastname:{ required: "Please enter last name"},
			phone:{ required: "Please enter phone number",minlength:"Please enter minimum 10 character for phone number.",maxlength:"Please enter maximum 10 character for phone number."},
			emailreg:{ required: "Please enter email address", email: "Please enter valid email address"},
			cpassword: { required: "Please enter password",minlength:"Please enter minimum 6 character for password." }
		},
		onsubmit: false
	});
	if(!$("#frmCheckoutRegister").valid()) 
	{
		return false;
	}
	check_user_sign_up();
}

function check_user_sign_up()
{
	$("#signup_error").html(''); 
	
	var email  	= '';
	var firstname  	= '';
	var lastname  	= '';
	var phone  		= '';
	var frmObj 	= document.frmCheckoutRegister;
	
	var firstname  		= frmObj.firstname.value;
	var lastname  		= frmObj.lastname.value;
	var phone  			= frmObj.phone.value;
	var email  			= frmObj.emailreg.value;
	var cpassword  		= frmObj.cpassword.value;
	
	var URL 	= site_url +'/checkout-usersignup';    
		
	var STR_POST_VAR = '';
	var STR_POST_VAR = 'email='+email;
	var STR_POST_VAR = STR_POST_VAR + '&firstname='+firstname+'&lastname='+lastname+'&phone='+phone+'&password='+cpassword;
	var STR_POST_VAR = STR_POST_VAR + '&_token='+$('meta[name="csrf-token"]').attr('content');   	
	$.ajax({
        type: 'POST',
        url: URL,
		data: STR_POST_VAR,
        cache: false,
        beforeSend: function()
        {},
        success: function(resultData)
        {
			if(resultData.trim() == 'yes')
			{
				if(parseInt($("#is_bt_express_checkout").val()) ==1)
					window.location.href = site_url+'/checkout?is_bt_express_checkout=1';
				else
					window.location.href = site_url+'/checkout';
			}
			else if(resultData.trim() == 'exist')
				$("#signup_error").html('There is already account created with enterd email id.');
			else
				$("#signup_error").html('Sorry! Your Registration cannot be completed. Please try again.');
        }
    });
}

function checkout_as_guest()
{
	$("#myModalPopUpLogin").modal('hide');
	$("body").removeClass('modal-open');
	$('#is_user_confirm_guest_checkout').val(1);
}

$(document).ready(function()
{
	bt_dropin_payment_setup();
	applyCouponNewCustomer();
});


/* Braintree Functions End */