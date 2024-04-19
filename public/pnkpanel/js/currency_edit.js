$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
            title: {
				required: true,
			},
			code: {
                required: true,	
				maxlength:3,	    
		    },
            decimal_places:{
                required: true,
            },
            value:{
                required: true,
            }
		},
		messages: {
			title: {
				required: "Please enter country name",
			},
			code : {
				required: "Please enter Code",
				maxlength : "Please do not enter more than 3 character."
			},
            decimal_places:{
                required: "Please enter decimal places",
            },
            value:{
                required: "Please enter value",
            }
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmRepresentative').validate(jqValidationOptions);
	
});

function individualAdminAfterCompletedProcess(ids){

	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontcurrencycache',
		data: {},
		cache: false,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(data) {
			console.log('currency edit chache cache clear sucessfully');

		}
	});
 }	