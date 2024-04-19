$(function () {
	$.validator.addMethod('filesize', function (value, element, param) {
		return this.optional(element) || (element.files[0].size <= param * 1000000)
	}, 'File size must be less than {0} MB');

	let jqValidationOptions = {
		ignore: [],
		rules: {
			zipfile: {
				required: true,
				extension: "zip",
				filesize: 200, //200 MB
			}
		},
		messages: {
			zipfile: {
				required: "Please browse Products Images ZIP file",
				extension: "Please upload only the ZIP file"
			}
		},
		errorPlacement: function (error, element) {
			if (element.is(":file")) {
				element.closest('.input-append').after(error);
			}
		},
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmProductBulkImageUpload').validate(jqValidationOptions);
	
});
