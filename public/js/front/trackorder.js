$(document).ready(function(){
	$("#formOrderTrack").submit(function(){
		$("div.trackOrderError").html('');
		$('#formOrderTrack').validate({
			rules: 
			{
				ordernumber: { required: true, number: true  },
				orderbillingemail: { required: true,email: true }
			},
			messages: 
			{
				ordernumber: { required: GetMessage('TrackOrder', 'RequiredOrderNo'),number: GetMessage('TrackOrder', 'ValidOrderNo') },
				orderbillingemail: { required: GetMessage('TrackOrder', 'ReqiedOrderBillingEmail'),email: GetMessage('TrackOrder', 'ValidOrderBillingEmail') }
				
			},
			onsubmit: false,
			
			errorPlacement: function(error, element) 
			{
				if ( element.attr("name") == "orderbillingemail" || element.attr("name") == "ordernumber") {
					error.addClass('w-100');
					element.parent().parent().append(error);
				}
				else { // This is the default behavior of the script
					error.insertAfter( element );
				}
			},
				// Override error placement to not show error messages beside elements //			}     
		});
		

		if(!$('#formOrderTrack').valid()) 
		{
			return false;
		}	
		
    });
    
});