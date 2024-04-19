$(function () {
	let jqValidationOptions = {
		ignore: [],
		rules: {
			filterByCategory: {
                required: true,
            },
			style_name: {
				required: true,
			},
			status: { 
				required : true
			},
			style_image: {
		      required: function() {
		        //check if the hidden input related to file is empty if yes return true else false..
		        return $(".fileupload-preview").text() == ""
		      },
		    
		    },
			style_image_home: {
				required: function() {
				  //check if the hidden input related to file is empty if yes return true else false..
				  if($("#display_homepage").val() == "Yes"){
					return $(".fileupload-preview.homepageimage").text() == ""
				  } else {
					return false;
				  }				  
				},
			  
			  },
		    /*style_image_home:{
		      required:{
		        depends:function(){
		          if (($("#display_homepage").val()=="Yes") && ($(".fileupload-preview.homepageimage").text()=="")){
		            return true;  
		          }else{
		            return false;
		          }
		        }
		      }
		     },*/
		    position: { 
				required : true
			},
		},
		messages: {
			filterByCategory: {
                required: "Please choose Landing options",
            },
			style_name: {
				required: "Please enter Style Name",
			},
			status: {
				required: "Please Select Status ",
			},
			style_image : {
				required: "Please select Style Image",
			},
			style_image_home: {
			 	required: "Please Select Home page image",
			 },
			position: { 
				required : "Please enter Position"
			},			 
		},
		errorPlacement: function(error, element) {			
			if(element.attr("name") == 'style_image'){
				if($("#style_image-error").length > 0){
					$("#style_image-error").remove();
					element.parent().after(error[0].outerHTML);
				} else {
					element.parent().parent().after(error[0].outerHTML);
				}	
				//element.parent().parent().after(error[0].outerHTML);
				//$('.input-append').after(error[0].outerHTML);
			} else if(element.attr("name") == 'style_image_home'){
				if($("#style_image_home-error").length > 0){
					$("#style_image_home-error").remove();
					element.parent().after(error[0].outerHTML);
				} else {
					element.parent().parent().after(error[0].outerHTML);
				}
				//element.parent().parent().after(error[0].outerHTML);
			} else {
				error.insertAfter(element); 
			}
		}
	};
	Object.assign(jqValidationOptions, jqValidationGlobalOptions);
	$('#frmStyle').validate(jqValidationOptions);
	
});
/*var _URL = window.URL;
$("#style_image").change(function (e) {
	var width=$("#width").val();
	var height=$("#height").val();

    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
           // alert("Width:" + this.width + "   Height: " + this.height);//this will give you image width and height and you can easily validate here....
            if(this.width!=width && this.height!=height)
            { 
               $(".fileupload-preview").text('');
            	$("#style_image").val('');
            	alert("Please Select Proper size");
            	
            }
        };
        img.src = _URL.createObjectURL(file);
    }
});*/
$("#display_homepage").change(function(){
	if($(this).val()=="Yes"){
		$("#style_image_home_required").show();
	} else {
		$("#style_image_home_required").hide();
	}	
})
$("#btnRemoveid").click(function(){
	//alert($("#btnRemoveid").attr('data-subtype'));
	if($("#display_homepage").val()=="Yes"){
		$("#style_image_home_required").show();
	} else {
		$("#style_image_home_required").hide();
	}	
})
/*$("#style_image_home").change(function (e) {
	var width=$("#width_home").val();
	var height=$("#height_home").val();

    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
           // alert("Width:" + this.width + "   Height: " + this.height);//this will give you image width and height and you can easily validate here....
            if(this.width!=width && this.height!=height)
            { 
               $(".fileupload-preview.homepageimage").text('');
            	$("#style_image_home").val('');
            	alert("Please Select Proper size");
            	
            }
        };
        img.src = _URL.createObjectURL(file);
    }
});*/

