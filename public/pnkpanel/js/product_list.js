
var createProductClone = function() {
	var allVals = [];  
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push($(this).attr('data-id'));
	});  


	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to Create Clone Of Product(s).");  
	}
	else
	{  
		var check = confirm("Are you sure to Create Clone Of Selected Product(s) ?");  
		if(check == true){  
			var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action_create_clone,
				type: 'post',
				data: {'ids': join_selected_values, 'actType': 'create_clone'},
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

(function($) {
	dtOptions = {
		ajax: {
			url: url_list,
			data: function (data) {
				data.category_id = $('#category_id option').filter(':selected').val();
				data.clone = $('#clone option').filter(':selected').val();
			}
		},
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'sku', name: 'sku'},
			{data: 'product_name', name: 'product_name'},
			{data: 'retail_price', name: 'retail_price'},
			{data: 'our_price', name: 'our_price'},
			{data: 'sale_price', name: 'sale_price'},
			{data: 'display_rank', name: 'display_rank'},
			//{data: 'group_rank', name: 'group_rank'},
			//{data: 'is_sale', name: 'is_sale'},
			{data: 'status', name: 'status', "render": function(data, type, row, meta) {
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
			{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		]
	};
	
    adminGridDataTableInit();

	$(document).on('change', '#category_id', function() {
		$('#adminGridDataTable').DataTable().clear().draw();
	});

	$(document).on('change', '#clone', function() {
		$('#adminGridDataTable').DataTable().clear().draw();
	});
    
}(jQuery));

function individualAdminAfterCompletedProcess(ids){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontcacheproductlist',
		data: {
			product_ids: ids,
		},
		success: function(data) {
			console.log('Product List cache clear sucessfully');

		}
	});
 }