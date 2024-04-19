(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'first_name', name: 'first_name', visible: false},
			{data: 'last_name', name: 'last_name', visible: false},
			{data: 'name', name: 'name'},
			{data: 'email', name: 'email'},
			{data: 'converted_date', name: 'reg_datetime'},
			{data: 'customer_type', name: 'customer_type'},
			{data: 'status', name: 'status', "render": function(data,type,row,meta) {
				var status_cell_val = data;
				switch (data){
					case "Active":
						status_cell_val = '<span class="badge badge-success p-2">'+data+'</span>';
						break;
					case "Inactive":
						status_cell_val = '<span class="badge badge-danger p-2">'+data+'</span>';
						break;
				}
				return status_cell_val;
			}},
		  {data: 'orders_count', name: 'orders_count', orderable: false},
		  {data: 'city', name: 'city',visible: false},
		  {data: 'state', name: 'state',visible: false},
		  {data: 'country', name: 'country',visible: false},
			// {data: 'orders', name: 'orders', orderable: false},
			{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		],
	};
	
    adminGridDataTableInit();
    
}(jQuery));



/*$("#emailForgotPassword").on("click", function(){
  checkBulkEmailForgotPassword();
});*/

var checkBulkEmailForgotPassword = function() {
	var allVals = [];  
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push($(this).attr('data-id'));
	});  


	if(allVals.length <=0)  
	{  
		alert("Please select record(s).");  
	}
	else
	{  
		var check = confirm("Confirm Forgot password Email ?");  
		if(check == true){  
			var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_email_forgot_password,
				type: 'post',
				data: {'ids': join_selected_values, 'actType': 'Forgotpassword'},
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
	
};
