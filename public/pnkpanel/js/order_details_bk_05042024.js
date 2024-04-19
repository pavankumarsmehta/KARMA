Number.prototype.toFixed = function(decimals) {
	var decimals = decimals || 2 // defaults to two
	var catalyst = Math.pow(10, decimals);
	var fixedNum = Math.round(parseFloat(this) * catalyst) / catalyst;
	if (fixedNum % 1) {
		return fixedNum
	} else {
		var str = "."
		while(decimals--) {
			str += "0"
		}
		return fixedNum + str
	}
}

let roundto = function(num) {
	return Math.round(num * 100)/100;
}

let Ship_StateCheck = function() {
	if(document.frmOrderDetails.ship_country.options[document.frmOrderDetails.ship_country.selectedIndex].value=="US")
	{
		document.getElementById("DIV_SH_US_STATE").style.display="";
		document.getElementById("DIV_SH_OTHER_STATE").style.display="none";
	}
	else 
	{
		document.getElementById("DIV_SH_US_STATE").style.display="none";
		document.getElementById("DIV_SH_OTHER_STATE").style.display="";
		document.getElementById("ship_state_other").focus();
	}
}
Ship_StateCheck();

// To calculate the sub total , sales tax etc
let reCalculate = function() {
	$('#frmOrderDetails').removeData('validator');
	$('#frmOrderDetails').validate();

	var total_items = $('#total_items').val();
	for (var i=0; i < total_items; i++)
	{
		// Check the valid values of Unit Price
		$('#unit_price'+i).rules('add', { required:true, currency: true, messages: { required:'Please enter unit price.', currency: 'Please enter only numeric value for unit price.' } });
		// Check the valid values of Quantity
		$('#quantity'+i).rules('add', { required:true, digits: true, messages: { required:'Please enter quantity.', digits: 'Please enter valid quantity.' } });
	}
	
	$('#shipping_amt').rules('add', { required:true, currency: true, messages: { required:'Please enter shipping charge.', currency: 'Please enter only numeric value for shipping charge.' } });
	$('#tax').rules('add', { required:true, currency: true, messages: { required:'Please enter sales tax.', currency: 'Please enter only numeric value for sales tax.' } });
	$('#auto_discount').rules('add', { required:true, currency: true, messages: { required:'Please enter auto discount.', currency: 'Please enter only numeric value for auto discount.' } });
	$('#quantity_discount').rules('add', { required:true, currency: true, messages: { required:'Please enter quantity discount.', currency: 'Please enter only numeric value for quantity discount.' } });
	$('#coupon_amount').rules('add', { required:true, currency: true, messages: { required:'Please enter coupon discount.', currency: 'Please enter only numeric value for coupon discount.' } });
	
	if(!$('#frmOrderDetails').valid()) {    
		return false;
	}

	// To Calculate the Sub Total of Order Details
	var sub_total = 0;
	var total_items = $('#total_items').val();
	for (var i=0; i < total_items; i++ )
	{
		var unit_price = $('#unit_price'+i).val();
		var qty = $('#quantity'+i).val();
		var total_price   = (unit_price * qty).toFixed();
		$('#total_price'+i).val(total_price);
		sub_total = parseFloat(sub_total) + parseFloat(total_price);
	}
	sub_total = roundto(sub_total);
	$('#sub_total').val(roundto(sub_total).toFixed(2));

	var shipping_amt 			= $('#shipping_amt').val();
	var tax_amt 					= $('#tax').val();
	var gift_charge 				= 0;
	var auto_discount 		= $('#auto_discount').val();
	var quantity_discount 	= $('#quantity_discount').val();
	var coupon_amount 	= $('#coupon_amount').val();
	var gc_amount				= 0;
	var order_total 				= (parseFloat(sub_total) + parseFloat(shipping_amt) + parseFloat(tax_amt) + parseFloat(gift_charge) - parseFloat(auto_discount) - parseFloat(quantity_discount) - parseFloat(coupon_amount) - parseFloat(gc_amount));
	order_total = roundto(order_total);
	order_total = new Number(order_total).toFixed(2);
	$('#order_total').val(order_total);
	return true;		
}

// Function to Check the Validation
let validateOrderDetailsForm = function() {
	var ship_status  = $('#ship_status').val();
	var ship_method  = $('#ship_method').val();
	var tracking_no  = $('#tracking_no').val();
	
	let jqValidationOptions = {
		rules: {
			ship_method: {
				required: function(){
					return $('#ship_status').val() != 'Pending' || $('#tracking_no').val() != ''
				},
				normalizer: function( value ) {
					return $.trim( value );
				}
			},
			tracking_no: {
				required: function() {
					return $('#ship_status').val() != 'Pending' || $('#ship_method').val() != '';
				},
				normalizer: function( value ) {
					return $.trim( value );
				}
			},
			ship_status: {
				chkShipStatus: function() {
					return $('#tracking_no').val() != '' || $('#ship_method').val() != '';
				}
			}
		},
		messages: {
			ship_method: { 
				required: "Please Select a Shipping Method for Shipment!" 
			},
			tracking_no: { 
				required: "Please Enter a Tracking # for Shipment!" 
			},
		}
	};
	$('#frmOrderDetails').removeData('validator');
	//$('#frmOrderDetails').validate({});
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmOrderDetails').validate(jqValidationOptions);
	
	if(!$("#frmOrderDetails").valid()) {
		return false;
	}
	
	return true;
}


$(function () {
	
	$.validator.addMethod("currency", function(value, element) {
		//var isValidMoney = /^\d{0,4}(\.\d{0,2})?$/.test(value);
		var isValidMoney = /^\d*\.{0,1}\d+$/.test(value);
        return this.optional(element) || isValidMoney;
	}, "Please enter only positive numeric value.");
	
	$.validator.addMethod("chkShipStatus", function(value, element) {
		return !(value == "Pending");
	}, "Please Select a Shipment Status!");
	
	$('body').on('click', '.btnUpdateShippingAddress', function (e) {
		e.stopPropagation();
		e.preventDefault();
		
		let jqValidationOptions = {
			rules: {
				ship_first_name: {
					required: true,
					normalizer: function( value ) {
						return $.trim( value );
					}
				},
				ship_last_name: {
					required: true
				},
				ship_address1: {
					required: true
				},
				ship_city: {
					required: true
				},
				ship_zip: {
					required: true
				},
				ship_state: { required: 
					function(){
						return $("#ship_country").val() == "US"
					}
				},
				ship_state_other: { required: 
					function(){
						return $("#ship_country").val() != "US"
					}
				},
				ship_country: {
					required: true
				}
			},
			messages: {
				ship_first_name: {
					required: "Please fill customer first name"
				},
				ship_last_name: {
					required: "Please fill customer last name"
				},
				ship_address1: {
					required: "Please fill address 1"
				},
				ship_city: {
					required: "Please fill city"
				},
				ship_zip: {
					required: "Please fill zip code"
				},
				ship_state: { 
					required: "Please select state" 
				},
				ship_state_other: { 
					required: "Please fill state" 
				},
				ship_country: { 
					required: "Please select country" 
				}
			},
			errorPlacement: function(error, element) {
				if ( element.attr("name") == "ship_country" || element.attr("name") == "ship_state" ) {
					error.insertAfter( element.siblings('.select2') );
				} else { // This is the default behavior of the script
					error.insertAfter( element );
				}
			}
		};
		$('#frmOrderDetails').removeData('validator');
		Object.assign(jqValidationOptions, jqValidationGlobalOptions);
		$('#frmOrderDetails').validate(jqValidationOptions);
		
		if(!$("#frmOrderDetails").valid()) {
			return false;
	   }
		
		$('#actType').val('UpdateShippingAddress');;
		$('#frmOrderDetails').submit();
	});
	
	$('body').on('click', '.btnProcessRefund', function (e) {
		e.stopPropagation();
		e.preventDefault();
		
		let jqValidationOptions = {
			rules: {
				refund_amount: {
					required: true,
					currency: true
				}
			},
			messages: {
				refund_amount: {
					required: "Please enter refund amount value.",
					currency: "Please enter only numeric value for refund amount."
				}
			}
		};
		$('#frmOrderDetails').removeData('validator');
		Object.assign(jqValidationOptions, jqValidationGlobalOptions);
		$('#frmOrderDetails').validate(jqValidationOptions);
		
		if(!$("#frmOrderDetails").valid()) {
			return false;
	   }

		$('#actType').val('RefundOrder');;
		$('#frmOrderDetails').submit();
	});

	$('body').on('click', '.btnSendEmailToClient', function (e) {
		e.stopPropagation();
		e.preventDefault();
		$('#actType').val('SendMail');;
		$('#frmOrderDetails').submit();
	});

	$('body').on('click', '.btnRecalculate', function (e) {
		e.stopPropagation();
		e.preventDefault();
		reCalculate();
	});

	$('body').on('click', '.btnSaveRecord', function (e) {
		e.stopPropagation();
		e.preventDefault();
		
		if( ! validateOrderDetailsForm() ) {
			return false;
		}

		if( ! reCalculate() ) {
			return false;
		}

		$('#actType').val('UpdateOrder');;
		$('#frmOrderDetails').submit();
		return true;
	});
	
});
