(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
            {data: 'country', name: 'country'},
            {data: 'state', name: 'state'},
            {data: 'additonal_charge', render: function ( data, type, row ) {
				return '$'+row.additonal_charge;
		   } , name: 'additonal_charge'},
			{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		],
	};
    adminGridDataTableInit();
}(jQuery));
