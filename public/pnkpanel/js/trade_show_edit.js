$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			treadeshow_name: {
				required: true,
			},
			address1: {
				required: true,
			},
			city: {
				required: true,
			},
			state: {
				required: true,
			},
			country: {
				required: true,
			},
			zip: {
				required: true,
				number: true
			},
			appointment_url: {
				url : true,
			},
			display_position: {
				required: true,
				number: true
			},
		},
		messages: {
			treadeshow_name: {
				required: "Please enter tradeshow name",
			},
			address1: {
				required: "Please enter address1",
			},
			city: {
				required: "Please enter city",
			},
			state: {
				required: "Please enter state",
			},
			country: {
				required: "Please enter country",
			},
			zip: {
				required: "Please enter zip",
				number: "Please enter valid numeric for a zipcode"
			},
			appointment_url: {
				url : "Please enter valid appointment url"
			},
			display_position: {
				required: "Please enter a display position",
				number: "Please enter valid numeric for a display position"
			},
		}
	};
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmTradeShow').validate(jqValidationOptions);
});
function individualAdminAfterCompletedProcess(ids){
	// $.ajax({
	// 	type: 'POST',
	// 	url: site_url + '/clearfrontbrandcache',
	// 	data: {
	// 		brand_ids: ids,
	// 	},
	// 	success: function(data) {
	// 		console.log('Brand menu  cache clear sucessfully');
	// 	}
	// });
 }


$(document).on("click", ".btnSaveRecord", function () {
	//$('#frmTradeShow').submit();
});