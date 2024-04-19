$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			'arr_category_id[]': { 
				required:
				function(){	
					//var options = $('#category_id > option:selected');
			         if($('#category_id > option:selected').length == 0){
			             return true
			              
			         }else {
					  return false
					   
				   }	
                 }
			},
			sku: {
				required: true,
			},
			product_name: {
				required: true,
			},
			retail_price: {
				required: true,
				greaterThan : 0,
			},
			our_price: {
				required: true,
				greaterThan : 0,
			},
			sale_price: {
				required: false,
				greaterThan : 0,
			},
			
		},
		messages: {
			"arr_category_id[]": {
				required: "Please select category",
			},
			sku: {
				required: "Please fill Product SKU",
			},
			product_name: {
				required: "Please enter product name",
			},
			
			retail_price: {
				required: "Please fill product retail price",
				greaterThan: "Retail price must be greater than zero"
			},
			our_price: {
				required: "Please fill product our price",
				greaterThan: "Our price must be greater than zero"
			},
			sale_price: {
				required: "Please fill product sale price",
				greaterThan: "Sale price must be greater than zero"
			},
			
		},
		invalidHandler: function (e, validator) {
			if (validator.errorList.length) {
				$('#tabs a[href="#' + $(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').trigger('click');
			}
		}
	};

	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmProduct').validate(jqValidationOptions);
	$.validator.addMethod('greaterThan', function(value, element, param) {
		//alert(parseFloat(value).toFixed(2)+'==='+parseFloat(0).toFixed(2));
	if(value != ''){
		return (parseFloat(value).toFixed(2)> parseFloat(0).toFixed(2));
	}else{
		return true;
	}
	}, 'Must be greater than start' );

});

tinymce.init({
	selector: '.mceEditor',
	height: 300,
	menubar: true,
	plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste code help wordcount',
		'pagebreak save contextmenu directionality noneditable visualchars nonbreaking template'
	],
	toolbar: 'undo redo | formatselect | ' +
		'bold italic backcolor | alignleft aligncenter ' +
		'alignright alignjustify | bullist numlist outdent indent | ' +
		'removeformat | help',
	extended_valid_elements: "+@[data-options],a[href|onclick|class|align|style|target=_blank],svg[*],use[*],strong/b,div[id|dir|class|align|style]",
	allow_script_urls: true,
	valid_children: "+body[style]",
	verify_html: false,
	force_br_newlines: false,
	force_p_newlines: false,
	forced_root_block: '',
});



$("#color_family_id").on('change, blur', function (e) {
	color_id = $(this).val().toString();

	$.ajax({
		type: 'POST',
		url: site_url + '/admin/get_color_colorfamily',
		data: {
			color_id: color_id
		},
		success: function (data) {

			$('#color_id').html(data.colorData)
		}
	});
});

function individualAdminAfterCompletedProcess(ids){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontcacheproductlist',
		data: {
			product_ids: ids,
		},
		success: function(data) {
			console.log('Product List cache clear sucessfully');

		}
	});
 }