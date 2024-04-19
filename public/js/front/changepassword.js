$(function () {
	jQuery.validator.addMethod("noSpace", function(value, element) {
	    return value.indexOf(" ") < 0 && value != "";
	}, "No space please and don't leave it empty");

	jQuery.validator.addMethod("passwordCheck", function(value, element, param) {
	    if (this.optional(element)) {
	        return true;
	    } else if (!/[A-Z]/.test(value)) {
	        return false;
	    } else if (!/[a-z]/.test(value)) {
	        return false;
	    } else if (!/[0-9]/.test(value)) {
	        return false;
	    }

	    return true;
    }, GetMessage('Validate','UpperCaseAndLetter'));

	let jqValidationOptions = {
		ignore: [],
		rules: {
			old_pass: { required: true,minlength: 6, noSpace: true },
			new_pass: { required: true, minlength: 6, noSpace: true, passwordCheck: true  },
			confirm_pass: { required: true, equalTo: '#new_pass', noSpace: true, passwordCheck: true }
		},
		messages: {
			old_pass: {
				required: GetMessage('Validate', 'OldPassword'),
				minlength: GetMessage('Validate','OldPassword'), 
				noSpace: GetMessage('Validate','OldPassword'), 
			},
			new_pass: {
				required: GetMessage('Validate', 'NewPassword'),
				minlength: GetMessage('Validate','NewPassword'), 
				noSpace: GetMessage('Validate','NewPassword'), 
				passwordCheck: GetMessage('Validate','UpperCaseAndLetter') 
			},
			confirm_pass: {
				required: GetMessage('Validate', 'ReTypeNewPassword'),
				equalTo: GetMessage('Validate','ReTypeNewPassword'), 
				noSpace: GetMessage('Validate','ReTypeNewPassword'), 
				passwordCheck: GetMessage('Validate','UpperCaseAndLetter')
			},
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "old_pass" || element.attr("name") == "confirm_pass" || element.attr("name") == "new_pass") {
				error.addClass('w-100');
				element.parent().parent().append(error);
			}
			else { // This is the default behavior of the script
				error.insertAfter( element );
			}
		},
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmChangePassword').validate(jqValidationOptions);
});
$(".svg_eye").on('click', function(event){
	$('#old_pass').attr('type', 'password');
	$('.svg_eye').addClass('dnone');
	$('.svg_eye_slash').removeClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});
$(".svg_eye_slash").on('click', function(event){
	$('#old_pass').attr('type', 'text');
	$('.svg_eye').removeClass('dnone');
	$('.svg_eye_slash').addClass('dnone');
	event.stopPropagation();
	event.stopImmediatePropagation();
});
