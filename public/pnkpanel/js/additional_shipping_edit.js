$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			country: {
				required: true,
			},
			state: {
                required: true,		    
		    },
            additonal_charge:{
                required: true,
            }
		},
		messages: {		
			country: {
				required: "Please select Country",
			},
			state : {
				required: "Please select State",
			},
            additonal_charge:{
                required: "Please enter Additional Charges",
            }
		}
	};
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmRepresentative').validate(jqValidationOptions);	
});
/*$(function () {
	
	jQuery.validator.addMethod('required_lookbook_image', function (value) { 
			alert(value);
			var lookbook_image = $('#lookbook_image').val();
			if(lookbook_image == '') {
				alert(456456);
				return false;
		    } else {
		    	return true;
		    }
	    }, 'Please upload image');
	
	jQuery.validator.addMethod('required_lookbook_title', function(value) {
        var title2 = $('#title2').val();
        var disp_options = $('#disp_options').val();
        if (title2 == '' && disp_options == 'two_image_content') {
            return false;
        } else {
            return true;
        }
    }, 'Please enter title');
	/*jQuery.validator.addMethod('required_home_image_mobile', function (value) { 
			var home_image_mobile = $('#lookbook_image').val();
			if(home_image_mobile == '') {
				return false;
		    } else {
		    	return true;
		    }
	    }, 'Please upload image');//////

	let jqValidationOptions = {
		ignore: [],
		rules: {
			title: {
				required: true,
			}
			lookbook_image: {
                required_lookbook_image: true,
            },
		},
		messages: {
			title: {
				required: "Please enter title",
			}
		},
		errorPlacement: function(error, element) {
			//error.insertAfter( element ); 
			if ( element.attr("name") == "lookbook_image") {
				error.addClass('w-100');
				element.parent().parent().parent().append(error);
			}
			else { // This is the default behavior of the script
				error.insertAfter( element );
			}
		}
	};	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmLookBook').validate(jqValidationOptions);
});*/

/*$('select#banner_position').on('change', function() {
  if(this.value == 'HOME_MIDDLE' || this.value == 'HOME_BOTTOM')
  {
	  $(".home_main").hide();
	  $(".home_middle_bottom").show();
  }
  else
  {
	  $(".home_middle_bottom").hide();
  	  $(".home_main").show();
  }
});*/
