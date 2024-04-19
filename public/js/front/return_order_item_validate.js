$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			return_request_quantity: {
				required: true,
			},
			message: {
				required: true,
			}
		},
		messages: {
			return_request_quantity: {
				required: 'Please select Quantity for return.',
			},
			message: {
				required: 'Please Enter Message',
			}
		},
		errorPlacement: function (error, element) {
			if (element.attr("name") == "return_request_quantity" || element.attr("name") == "message") {
				error.addClass('w-100');
				element.parent().append(error);
			} else { // This is the default behavior of the script
				error.insertAfter(element);
			}
		},
		submitHandler: function (form) {
			var data = $("#frmReturnItem").serialize();
			
			$("#cover-spin").show();
			$.ajax({
				type: "POST",
				url: site_url + "/return_order_item",
				data: data,
				cache: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function (msg) {
					if(msg){
					 setTimeout(function () {
					  $('#return-order-item-modal').modal('hide');
					}, 700);
					location.reload(true);
					 
					}
				}
			});
		},
	};

	// Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	Object.assign(jqValidationOptions);
	$('#frmReturnItem').validate(jqValidationOptions);
});


