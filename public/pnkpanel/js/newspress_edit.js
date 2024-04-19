$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
            title: {
				required: true,
			},
			description: {
                required: true,	    
		    },
            date:{
                required: true,
            },
            type:{
                required: true,
            }
		},
		messages: {
			title: {
				required: "Please enter Title",
			},
			description : {
				required: "Please enter description",
			},
            date:{
                required: "Please select date",
            },
            type:{
                required: "Please select type",
            }
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmRepresentative').validate(jqValidationOptions);
	
});