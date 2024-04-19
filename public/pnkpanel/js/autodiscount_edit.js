
$(function () {
	
	jQuery.validator.addMethod('positiveNumber', function (value) { 
	        if(Number(value) > 0) {
	        	return true;
	        } else {
	        	return false;
	        }
	    }, 'Please enter only positive numeric value');

	jQuery.validator.addMethod('between', function (value) { 
	        if($('#type').val() == '1') {
				if ($('#auto_discount_amount').val() > 100 || $('#auto_discount_amount').val() <=0){
					return false;
				} else {
					return true;
				}
	        } else {
	        	return true;
	        }
	    }, 'Auto Discount Percent value must be between 0 and 100.');

	let jqValidationOptions = {
		ignore: [],
		rules: {
			order_amount: {
	            required: true,
	            positiveNumber: true
			},
			auto_discount_amount: {
	            required: true,
	            positiveNumber: true,
	            between: true
			},
			start_date: {
	            required: true
			},
			end_date: {
	            required: true,
			}
		},
		messages: {
			order_amount: {
				required: "Please Enter Order Amount",
			},
			auto_discount_amount: {
				required: "Please Enter Discount Amount",
			},
			start_date: {
				required: "Please select the Coupon Start Date",
			},
			end_date: {
				required: "Please select the Coupon End Date",
			}
		},
		errorPlacement: function(error, element) {
			if ( element.attr("name") == "start_date" || element.attr("name") == "end_date" ) {
				error.addClass('w-100');
				//error.insertAfter(element.parent());
				element.parent().parent().append(error);
			}
			else { // This is the default behavior of the script
				error.insertAfter( element );
			}
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmAutoDiscount').validate(jqValidationOptions);
	
});

