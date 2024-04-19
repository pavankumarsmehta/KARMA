$(document).ready(function(){
	$("#formOrderTrack").submit(function(){
		$("div.trackOrderError").html('');
		$('#formOrderTrack').validate({
			rules: 
			{
				bill_email: { required: true,email: true },
				orders_id: { required: true, number: true  }
			},
			messages: 
			{
				bill_email: { required: "Please enter order billing email",email: "Please enter valid email address" },
				orders_id: { required: "Please enter order number",email: "Please enter valid order number" }
			},
			onsubmit: false,
			invalidHandler: function(form, validator) 
			{
				var errors = validator.numberOfInvalids();
				if (errors) 
				{
					for(var i=0;i<errors;i++)
					{
						var message = validator.errorList[i].message;
						var id = $(validator.errorList[i].element).attr('name');
						console.log(id);
						$("div#error_"+id).addClass("error-cls");
						$("div#error_"+id).html(message);
					}
				} 
			   else 
			   {
				   $("div.trackOrderError").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		

		if(!$('#frmOrderTracking').valid()) 
		{
			return false;
		}	
		
    });
    
});