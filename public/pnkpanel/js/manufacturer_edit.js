$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			manufacturer_name: {
				required: true,
			},
			display_position: {
				required: true,
				number: true
			},
		},
		messages: {
			manufacturer_name: {
				required: "Please enter manufacturer name",
			},
			display_position: {
				required: "Please enter a display position",
				number: "Please enter valid numeric for a display position"
			},
		}
	};
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmBrand').validate(jqValidationOptions);
});


$(document).on("click", ".btnSaveRecord", function () {
	$('#frmBrand').submit();
});