$(function () {
	jQuery.validator.addMethod('required_message_text', function (value) { 

			if ($("#mail_body").hasClass("mceEditor")) {

				for (i=0; i < tinymce.editors.length; i++){
					var content = tinymce.editors[i].getContent(); // get the content
					if(content == '') {
						return false;
					} else {
						return true;
					}
				}

			} else {
				var content = $("#mail_body").val().length;
				if(content == '') {
					return false;
				} else {
					return true;
				}
			}


	    }, 'Please enter email content');
	let jqValidationOptions = {
		ignore: [],
		rules: {
			subject: {
				required: true,
			},
			mail_body: {
	            required_message_text: true,
			},
		},
		messages: {
			subject: {
				required: "Please enter email subject",
			},
			// mail_body: {
			// 	number: "Please enter email content"
			// },
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmEmailTemplate').validate(jqValidationOptions);
});