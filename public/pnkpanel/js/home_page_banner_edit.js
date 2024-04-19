$(function () {

	/*jQuery.validator.addMethod('required_home_image', function (value) { 
			var home_image = $('#home_image').val();
			if(home_image == '') {
				return false;
		    } else {
		    	return true;
		    }
	    }, 'Please upload image');

	jQuery.validator.addMethod('required_home_image_mobile', function (value) { 
			var home_image_mobile = $('#home_image_mobile').val();
			if(home_image_mobile == '') {
				return false;
		    } else {
		    	return true;
		    }
	    }, 'Please upload image');
		*/
	let jqValidationOptions = {
		ignore: [],
		rules: {
			/*title: {
				required: true,
			},*/
			home_image: { 
				//required_home_image: true,
				required: function() {
					//return $(".fileupload-preview").text() == ""
					return $("#home_image_file_preview").text() == ""
				  },
			},
			home_image_mobile: { 				
				required: function() {					
					return $("#home_image_mobile_file_preview").text() == ""
				  },				
			}
		},
		messages: {
			/*title: {
				required: "Please enter title",
			},*/
			display_position: {
				//required: "Please enter a display position",
				number: "Please enter valid numeric value for a display position"
			},
		},
		errorPlacement: function(error, element) {
			if ( element.attr("name") == "home_image" || element.attr("name") == "home_image_mobile" ) {
				error.addClass('w-100');
				element.parent().parent().parent().append(error);
			}
			else { // This is the default behavior of the script
				error.insertAfter( element );
			}
		}
	};
	
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmHomePageBanner').validate(jqValidationOptions);
});

$('select#banner_position').on('change', function() {
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
});
