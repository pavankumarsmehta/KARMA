(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'country', name: 'country'},
			{data: 'states', name: 'states'},
			{data: 'tax_region_name', name: 'tax_region_name'},
			/*{data: 'zip_from', name: 'zip_from'},
            {data: 'zip_to', name: 'zip_to'},*/
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
			{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		],
	};
    adminGridDataTableInit();
}(jQuery));
