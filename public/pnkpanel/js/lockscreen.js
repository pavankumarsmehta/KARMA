$(function () {
	$('#frmLockScreen').validate({
		rules: {
			password: {
				required: true
			},
		},
		messages: {
			password: {
				required: "Please enter a password"
			},
		},
		errorElement: 'label',
		errorPlacement: function (error, element) {
			element.closest('.input-group').after(error);
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
});
