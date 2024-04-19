(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
            {data: 'amount_from', name: 'amount_from'},
            {data: 'charge_amount', name: 'charge_amount'},
			{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		],
	};
    adminGridDataTableInit();
}(jQuery));
