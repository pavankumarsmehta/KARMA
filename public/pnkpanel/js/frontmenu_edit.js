$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			menu_title: {
				required: true,
			},
			rank: {
				required: true,
				number: true
			},
		},
		messages: {
			menu_title: {
				required: "Please enter Menu name",
			},
			rank: {
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


$(document).on("change", "#category_id", function () {
	
	let cat_parent_id = '';
	cat_parent_id = $(this).find(':selected').attr('data-parent_id');
	let cat_url = $(this).find(':selected').attr('data-url');
	if(cat_parent_id != 0){
		$('#brand_id').val("");
	}else{
		let cat_short_url = $('#category_id').find(':selected').attr('data-cat_url');
		let brand_url = $("#brand_id").find(':selected').attr('data-url');

		cat_url = brand_url.replace('brand', cat_short_url);
	}
	if(cat_url){
		$('#menu_link').val(cat_url);
		$('#menu_link').addClass('disabled');
		$('#menu_link').attr('readonly',true);
	}else{
		$('#menu_link').removeClass('disabled');
		$('#menu_link').val('');
		$('#menu_link').attr('readonly',false);
	}
	
});

$(document).on("change", "#brand_id", function () {
	
	let cat_parent_id = '';
	cat_parent_id = $('#category_id').find(':selected').attr('data-parent_id');
	let brand_url = $(this).find(':selected').attr('data-url');
	if(cat_parent_id != 0){
		$('#category_id').val("");
	}else{
		let cat_url = $('#category_id').find(':selected').attr('data-cat_url');
		brand_url = brand_url.replace('brand', cat_url);
	}
	if(brand_url){
		$('#menu_link').val(brand_url);
		$('#menu_link').addClass('disabled');
		$('#menu_link').attr('readonly',true);
	}else{
		$('#menu_link').removeClass('disabled');
		$('#menu_link').val('');
		$('#menu_link').attr('readonly',false);
	}
});