(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'state_id', name: 'state_id'},
			{data: 'name', name: 'name'},
			 {data: 'code', name: 'code'},
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
			// {data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		],
	};
	
    adminGridDataTableInit();
    
}(jQuery));