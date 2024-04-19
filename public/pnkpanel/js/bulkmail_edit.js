$(function () {

	jQuery.validator.addMethod('selectMailGroup', function (value) { 

		if ($("#group1").is(":checked") || $("#group2").is(":checked") || $("#group3").is(":checked") || $("#group4").is(":checked")) {
		   return true;
		} else {
			return false;
		}

	    }, 'Please Select A Mail Group to Send Bulk Mail / Reminder');

	jQuery.validator.addMethod('required_message_text', function (value) { 

			if ($("#message_text").hasClass("mceEditor")) {

				for (i=0; i < tinymce.editors.length; i++){
					var content = tinymce.editors[i].getContent(); // get the content
					if(content == '') {
						return false;
					} else {
						return true;
					}
				}

			} else {
				var content = $("#message_text").val().length;
				if(content == '') {
					return false;
				} else {
					return true;
				}
			}


	    }, 'Please Enter Mail Text');

	let jqValidationOptions = {
		ignore: [],
		rules: {
			group: {
				selectMailGroup: true,
			},
			usrgroup: {
	            required: "#group1:checked",
			},
			'allusrs[]': {
	            required: "#group2:checked",
			},
			'allsubscriber[]': {
	            required: "#group3:checked",
			},
			email: {
	            required: "#group4:checked",
	            email: true
			},
			message_subject: {
	            required: true,
			},
			message_text: {
	            required_message_text: true,
			},
			display_position: {
				number:true
			},
		},
		messages: {
			usrgroup: {
				required: "Please Select a User Group to Send Mail",
			},
			'allusrs[]': {
				required: "Please Select a User(s) to Send Mail",
			},
			'allsubscriber[]': {
				required: "Please Select a Newsletter Client(s) to Send Mail",
			},
			email: {
				required: "Please Enter a User's Email Address to Send Mail"
			},
			message_subject: {
	            required: "Please Enter Mail Subject",
			},
			display_position: {
				//required: "Please enter a display position",
				number: "Please enter valid numeric for a display position"
			},
		},
		errorPlacement: function(error, element) {
		   if ( element.is(":radio") ) {
		       error.insertBefore(element.parent().parent().parent());
		   }
		   else {
		       error.insertAfter( element );
		   }
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmBulkMail').validate(jqValidationOptions);
});

function setGroup(x)
{ 	
	var plusOne = x + 1;
	$('#group'+plusOne).prop('checked', true);
}

function resetform(frmname) {
    $('#' + frmname)[0].reset();
}