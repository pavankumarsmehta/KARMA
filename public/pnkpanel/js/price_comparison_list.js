(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'pid', name: 'pid'},
			{data: 'your_name', name: 'your_name'},
			{data: 'email', name: 'email'},
			{data: 'phone', name: 'phone'},
            {data: 'insert_date', name: 'insert_date'},
			{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		],
	};
    adminGridDataTableInit();
}(jQuery));
