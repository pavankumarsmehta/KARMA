$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			friend_email1: {
				required: true,
				email: true,
			},
			your_email: {
				required: true,
				email: true,
			},
			"g-recaptcha-response": {
				required: true,
			},
		},
		messages: {
			friend_email1: {
				required: GetMessage('Validate', 'Email'),
				email: GetMessage('Validate', 'ValidEmail'),
			},
			your_email: {
				required: GetMessage('Validate', 'Email'),
				email: GetMessage('Validate', 'ValidEmail'),
			},
			"g-recaptcha-response": {
				required: GetMessage('Validate', 'GRecaptchaResponse')
			}
		},
		errorPlacement: function (error, element) {
			console.log(element);

			//return false;
			if (element.attr("name") == "friend_email1" || element.attr("name") == "your_email") {
				error.addClass('w-100');
				//element.parent().parent().append(error);
				element.parent().append(error);
			} else { // This is the default behavior of the script
				error.insertAfter(element);
			}
		},
		submitHandler: function (form) {
			var data = $("#frmFriend").serialize();
			
			$("#cover-spin").show();
			$.ajax({
				type: "POST",
				url: site_url + "/email_friend",
				data: data,
				cache: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
        success: function (response) {
					if(response){
					//$("#cover-spin").hide();
					//$("#EmailFriendPopup").html(response);
					//$('#EmailFriendPopup').modal('show');
					 //$("#tell-friend-modal").html(response);
          
            if (response.action == true) {              
              setTimeout($('#email_friend_popup_message').html(response.message).show(), 2000);
            }

            setTimeout(function () {
              $('#tell-friend-modal').modal('hide');
            }, 3000);
					 
					}
				}
			});
		},
	};

	// Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	Object.assign(jqValidationOptions);
	$('#frmFriend').validate(jqValidationOptions);

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


