$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
            sourcing_product: {
				required: true,
			},
			quantity: {
                required: true,	
				number: true,	    
		    },
            unit:{
                required: true,
            }
		},
		messages: {
			sourcing_product: {
				required: "Please enter sourcing product",
			},
			quantity : {
				required: "Please enter quantity",
				number : "Please enter only numbers."
			},
            unit:{
                required: "Please enter unit",
            }
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmRepresentative').validate(jqValidationOptions);
	
});