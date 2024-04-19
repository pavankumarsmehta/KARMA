$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
            title: {
				required: true,
			},
			name: {
                required: true,		    
		    },
            content:{
                required: true,
            }
		},
		messages: {
			title: {
				required: "Please enter Title",
			},
			name : {
				required: "Please enter Name",
			},
            content:{
                required: "Please enter Page HTML",
            }
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmRepresentative').validate(jqValidationOptions);
	
});