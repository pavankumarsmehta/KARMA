$(function () {
	$('#loginForm').validate({
		rules: {
			email: {
				required: true,
				email: true,
			},
			password: {
				required: true,
				minlength: 6
			},
		},
		messages: {
			email: {
				required: "Please enter an email address",
				email: "Please enter a vaild email address"
			},
			password: {
				required: "Please enter a password",
				minlength: "Your password must be at least 6 characters long"
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
