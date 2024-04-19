
$(function () {
	
	let jqValidationOptions = {
		ignore: [],
		rules: {
			currency_code: {
				required: true,
			},
			currency_name: {
				required: true,
			},
			exchange_rate: {
				required: true,
			},
			currency_symbol: {
				required: true,
			},
		},
		messages: {
			currency_code: {
				required: "Please enter an Currency Code",
				
			},
			currency_name: {
				required: "Please enter an Currency Name",
			},
			exchange_rate: {
				required: "Please enter an Exchange Rate",
			},
			currency_symbol: {
				required: "Please enter an Currency Symbol",
			},
			
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmexchange_currency').validate(jqValidationOptions);
	
});
