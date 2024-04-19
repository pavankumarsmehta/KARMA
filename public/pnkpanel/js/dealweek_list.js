(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'product_sku', name: 'product_sku'},
			{data: 'start_date', name: 'start_date'},
			{data: 'end_date', name: 'end_date'},
			{data: 'deal_price', name: 'deal_price'},
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
			//{data: 'banner_position', name: 'banner_position', orderable: false, searchable: false, exportable: false, visible: false},
			//{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		]
	};
	
    adminGridDataTableInit();
    
}(jQuery));
// if(typeof individualAdminAfterCompletedProcess == 'function'){
// 	individualAdminAfterCompletedProcess();
// }

 function individualAdminAfterCompletedProcess(ids){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontdealoftheweekscache',
		data: {
			parent_sku: '',
		},
		success: function(data) {
			console.log('deal of the week chache cache clear sucessfully');

		}
	});
 }