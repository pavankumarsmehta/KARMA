$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			category_name: {
				required: true,
			},
			display_position: {
				//required: true,
				number: true
			},
			amount: {
				greaterThanZero: true
			}
		},
		messages: {
			category_name: {
				required: "Please enter category name",
			},
			display_position: {
				//required: "Please enter a display position",
				number: "Please enter valid numeric for a display position"
			},
		}
	};

	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmCategory').validate(jqValidationOptions);
});


$(document).on("click", ".btnSaveRecord", function () {
	let valuesAlreadySeen = [];
	var isTrue = true;
	$(".shop_row").each(function (index, ele) {
		value = $(this).val();
		if (valuesAlreadySeen.indexOf(value) !== -1) {
			alert('Please Enter Category Tile Images Rank value Unique ');
			isTrue = false;
		}
		valuesAlreadySeen.push(value);
	});

	if (isTrue) {
		$('#frmCategory').submit();
	}
});


// function submitForm() {
// 	let valuesAlreadySeen = [];
// 	var isTrue = true;
// 	$(".shop_row").each(function (index, ele) {
// 		value = $(this).val();
// 		if (valuesAlreadySeen.indexOf(value) !== -1) {
// 			alert('Please Enter Category Tile Images Rank value Unique ');
// 			isTrue = false;
// 		}
// 		valuesAlreadySeen.push(value);
// 	});

// 	if (isTrue) {
// 		$('#frmCategory').submit();
// 	}
// };

function individualAdminAfterCompletedProcess(ids){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontcachecategorylist',
		data: {
			category_ids: ids,
		},
		success: function(data) {
			console.log('Category cache clear sucessfully');

		}
	});
 }