$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			csv_file: { 
				required: true, 
				extension: 'csv', 
				accept : 'text/csv,text/comma-separated-value,application/vnd.ms-excel,application/vnd.msexcel,application/csv' 
			}
		},
		messages: {
			csv_file: {
				required: "Please browse CSV file",
				extension: "Please upload only the CSV file", 
				accept: "Please upload only the CSV file"
			}
		},
		errorPlacement: function (error, element) {
			if (element.is(":file")) {
				element.closest('.input-append').after(error);
			}
		},
	};
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmImportTaxRules').validate(jqValidationOptions);
});
