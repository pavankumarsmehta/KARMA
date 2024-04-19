$(function () {

	let jqValidationOptions = {

		ignore: [],

		rules: {

			'shipping_method': { 

				required:

				function(){	

					//var options = $('#category_id > option:selected');

			         if($('#shipping_method > option:selected').length == 0){

			             return true

			              

			         }else {

					  return false

					   

				   }	

                 }

			},

			'country[]': { 

				required:

				function(){	

					//var options = $('#category_id > option:selected');

			         if($('#country > option:selected').length == 0){

			             return true

			              

			         }else {

					  return false

					   

				   }	

                 }

			},

			'state[]': { 

				required:

				function(){	

					//var options = $('#category_id > option:selected');

			         if($('#country > option:selected').val() == "US"){

			         if($('#state > option:selected').length == 0){

			             return true

			              }

			         }else {

					  return false

					   

				   }	

                 }

			},

			'otherstate': {

				required:

				function(){	

					//var options = $('#category_id > option:selected');

			         if($('#country > option:selected').val() != "US"){

			         if($('#state > option:selected').length == 0){

			             return false

			              }

			         }else {

					  return false

				   }	

                 }

			},

			'order_amount[]':{

				required:

				function(){	

					

			         if($('#no1').val() == "0"){

			         	if($('.order_amount').length==0)

			             return true

			             

			         }else {

					  return false

					   

				   }	

                 }

			},

			'charge[]':{

				required:

				function(){	

					

			         if($('#no1').val() == "0"){

			         	if($('.charge').length==0)

			             return true

			             

			         }else {

					  return false

					   

				   }	

                 }

			}

			/*zipcode_to: {

				required: true,

			},

			zipcode_from: {

				required: true,

			},*/

			

		},

		messages: {

			"shipping_method": {

				required: "Please select Shipping method",

			},

			"country": {

				required: "Please select country",

			},



			"state[]": {

				required: "Please select State",

			},

			"otherstate": {

				required: "Please Enter State",

			},

			"order_amount[]": {

				required: "Please fill at lease one textbox",

			},

			"charge[]": {

				required: "Please fill at lease one charge textbox",

			},

			/*zipcode_to: {

				required: "Please Enter zipcode To value",

			},

			zipcode_from: {

				required: "Please Enter zipcode from value",

			},*/

			

		}

	};

	

	Object.assign(jqValidationOptions, jqValidationGlobalOptions);

	$('#frmshippingrule').validate(jqValidationOptions);

});



$(document).ready(function() {







	





});

