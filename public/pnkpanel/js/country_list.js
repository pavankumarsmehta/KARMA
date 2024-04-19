(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'countries_id', name: 'countries_id'},
			{data: 'countries_name', name: 'countries_name'},
			 {data: 'countries_iso_code_2', name: 'countries_iso_code_2'},
			{data: 'status', name: 'status', "render": function(data,type,row,meta) {
				var status_cell_val = data;
				switch (data){
					case "Active":
						// status_cell_val = '<span class="ecommerce-status active">'+data+'</span>';
						status_cell_val = '<span class="badge badge-success p-2">'+data+'</span>';
						break;
					case "Inactive":
						// status_cell_val = '<span class="ecommerce-status no-active">'+data+'</span>';
						status_cell_val = '<span class="badge badge-danger p-2">'+data+'</span>';
						break;
				}
				return status_cell_val;
			}},
			// {data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		],
	};
	
    adminGridDataTableInit();
    
}(jQuery));
