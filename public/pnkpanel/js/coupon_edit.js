$(document).ready(function() {
    $('input:radio[name=orders]').change(function() {
    	/*$('.ordersF').hide();
    	$('#orders_field_'+this.value).show();*/

		$("#discount").prop("disabled",false);
		if(this.value == "0")
		{
			$("#order_amount").prop("disabled",false);
			$("#sku").prop("disabled",true);
			$("#category_id").prop("disabled",true);
			$("#brand_id").prop("disabled",true);
			$("#shipping_mode_id").prop("disabled",true);
		}
		else if(this.value == "1")
		{
			$("#order_amount").prop("disabled",true);
			$("#sku").prop("disabled",false);
			$("#category_id").prop("disabled",true);
			$("#brand_id").prop("disabled",true);
			$("#shipping_mode_id").prop("disabled",true);
		}
		else if(this.value == "3")
		{
			$("#order_amount").prop("disabled",true);
			$("#sku").prop("disabled",true);
			$("#category_id").prop("disabled",false);
			$("#brand_id").prop("disabled",true);
			$("#shipping_mode_id").prop("disabled",true);
		}
		else if(this.value == "4")
		{
			$("#discount").prop("disabled",true);
			$("#order_amount").prop("disabled",true);
			$("#sku").prop("disabled",true);
			$("#category_id").prop("disabled",true);
			$("#brand_id").prop("disabled",true);
			$("#shipping_mode_id").prop("disabled",false);
		}
		else if(this.value == "5")
		{
			$("#order_amount").prop("disabled",true);
			$("#sku").prop("disabled",true);
			$("#category_id").prop("disabled",true);
			$("#brand_id").prop("disabled",false);
			$("#shipping_mode_id").prop("disabled",true);
		}

    });

    $('#gcn').click(function() {
    	access_code();
    });

	/*$('.dtp').datepicker({
	    format: 'yyyy-mm-dd'
	});*/

	// $.datepicker.setDefaults({ dateFormat: 'yy-mm-dd' });

    /*$(".demoDate").on("change", function () {
        var fromdate = $(this).val();
        alert(fromdate);
    });*/

});


function access_code() 
{
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 10;
	var randomstring = '';
	for (var i=0; i<string_length; i++) 
	{
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	// document.frmcoupon.coupon_number.value = randomstring;
	$('#coupon_number').val(randomstring);
}


$(function () {
	
	jQuery.validator.addMethod('positiveNumber', function (value) { 
	        // return Number(value) > 0;
	        if(Number(value) > 0) {
	        	return true;
	        } else {
	        	return false;
	        }
	    }, 'Please enter only positive numeric value');

	jQuery.validator.addMethod('discountRequired', function (value) { 
	        if($('#discount').is(':disabled')){
			    return false;
			} else {
				if($('#discount').val() == '') {
					return false;
				}
				return true;
			}
	    }, 'Please Enter Coupon Discount');

    /*jQuery.validator.addMethod("smallerSatrtDate", function(value, element) {
    	let start_date = $('#start_date').val();
    	let end_date = $('#end_date').val();
    	if(end_date < start_date) {
    		return false;
    	} else {
    		return true;
    	}
    }, 'Start Date should be smaller than the End Date');*/

	let jqValidationOptions = {
		ignore: [],
		rules: {
			'coupon_number': {
				required: true,
			},
			order_amount: {
	            required: "#orders0:checked",
	            positiveNumber: true
			},
			'category_id[]': {
	            required: "#orders3:checked"
			},
			discount: {
	            discountRequired: true,
	            positiveNumber: true
			},
			start_date: {
	            required: true
			},
			end_date: {
	            required: true,
	            // smallerSatrtDate: true
			},
			shipping_mode_id: {
	            required: "#orders4:checked"
			},
			sku: {
	            required: "#orders1:checked"
			},
			'brand_id[]': {
	            required: "#orders5:checked"
			},
		},
		messages: {
			'coupon_number': {
				required: "Please Enter Coupon Code",
			},
			order_amount: {
				required: "Please Enter Order Amount",
			},
			'category_id[]': {
				required: "Please Select Category",
			},
			start_date: {
				required: "Please select the Coupon Start Date",
			},
			end_date: {
				required: "Please select the Coupon End Date",
			},
			shipping_mode_id: {
				required: "Please select shipping method",
			},
			sku: {
				required: "Please Enter Product SKU",
			},
			'brand_id[]': {
				required: "Please Select Brand",
			},
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
	$('#frmCoupon').validate(jqValidationOptions);
	
});

