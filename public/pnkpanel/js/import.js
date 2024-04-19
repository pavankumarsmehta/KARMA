$(function () {
	$('#frmRepresentative').validate({
		rules: {
			import_product_file: {
				required: true,
			},
		},
		messages: {
			import_product_file: {
				required: "Please Choose file",
			
			},
			
		},
		errorElement: 'label',
		errorPlacement: function (error, element) {
			//error.addClass('invalid-feedback');
			//element.closest('.input-group').append(error);
			//error.addClass('text-danger');
			element.closest('.input-group').after(error);
		},
		/*highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
			//$(element).closest('.form-group').addClass('has-danger');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
			//$(element).closest('.form-group').removeClass('has-danger');
		},*/
		submitHandler: function(form) {
			form.submit();
		}
	});
});
