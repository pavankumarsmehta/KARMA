(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			// {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			// {data: 'first_name', name: 'first_name', visible: false},
			// {data: 'last_name', name: 'last_name', visible: false},
			{data: 'service_name', name: 'service_name', orderable: false, searchable: false, exportable: false},
			{data: 'company_name', name: 'company_name', orderable: false, searchable: false, exportable: false},
			{data: 'service_type', name: 'service_type', orderable: false, searchable: false, exportable: false},
			{data: 'error_code', name: 'error_code', orderable: false, searchable: false, exportable: false},
			{data: 'error_message', name: 'error_message', orderable: false, searchable: false, exportable: false},
			{data: 'status', name: 'status', orderable: false, searchable: false, exportable: false}
		],
		paging: false
	};
	
    adminGridDataTableInit();
    
}(jQuery));
