(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'image_url', name: 'image_url', orderable: false, searchable: false},
			{data: 'caption', name: 'caption', className: 'align-top', orderable: false, searchable: false},
			{data: 'display_position', name: 'display_position'},
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
		]
	};
    adminGridDataTableInit();
}(jQuery));
