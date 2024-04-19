$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			email: {
				required: true,
				email: true,
			},
			first_name: {
				required: true,
			},
			last_name: {
				required: true,
			},
			address1: {
				required: true,
			},
			city: {
				required: true,
			},
			zip: {
				required: true,
			},
			phone: {
				required: true,
			},
			state: { required: 
				function(){	
                   if($("#state").val().trim() == "selected"){
					 return false
					  } else {
					  return true
				   }	
                 }
			},
			other_state: { required: 
				function(){
                    return $("#country").val() != "US"
              	}
			},
			password: { 
				required: 
				function(){
					return jsuser_type != 'guest' && actType == 'add'
				}  
			},
			confirm_password: { 
				required: 
				function(){
					return (jsuser_type != 'guest' && actType == 'add') || (jsuser_type != 'guest' && actType == 'update' && $("#password").val() != '')
				}, 
				equalTo: '#password' 
			},
		},
		messages: {
			email: {
				required: "Please Enter Email",
				email: "Please input a valid email address.",
			},
			first_name: {
				required: "Please Enter First Name"
			},
			last_name: {
				required: "Please Enter Last Name"
			},
			address1: {
				required: "Please Enter Address"
			},
			city: {
				required: "Please Enter City"
			},
			zip: {
				required: "Please Enter Zip"
			},
			phone: {
				required: "Please Enter Phone No."
			},
			state: 	{ 
				required: "Please Enter State" 
			},
			other_state: { 
				required: "Please Enter Other State" 
			},
			password: { 
				required: "Please Enter Password" 
			},
			confirm_password: 	{ 
				required: "Please Enter Confirm Password", 
				equalTo: "Please Confirm Your Password" 
			},
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmCustomer').validate(jqValidationOptions);
});

$(document).ready(function() {

	$('#country').on('change', function (e) {
	    var selectedCountry = $(this).val();
	    console.log(selectedCountry);
		if(selectedCountry == 'US') {
	    	$("#divotherstate").hide();
	    	$("#divstate").show();
		} else {
			$("#other_state").val('');
	    	$("#divotherstate").show();
	    	$("#divstate").hide();
		}
	});

});
