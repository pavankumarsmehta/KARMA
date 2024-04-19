$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			brand_name: {
				required: true,
			},
			display_position: {
				required: true,
				number: true
			},
		},
		messages: {
			brand_name: {
				required: "Please enter brand name",
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
function individualAdminAfterCompletedProcess(ids){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontbrandcache',
		data: {
			brand_ids: ids,
		},
		success: function(data) {
			console.log('Brand menu  cache clear sucessfully');
		}
	});
 }


$(document).on("click", ".btnSaveRecord", function () {
	$('#frmBrand').submit();
});