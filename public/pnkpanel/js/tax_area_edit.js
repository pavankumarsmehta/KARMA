
$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
            state: {
                required: true,
            },
		},
		messages: {
			state: {
				required: "Please select state.",
			},
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmRepresentative').validate(jqValidationOptions);
	
});
