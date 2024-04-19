(function($) {
	dtOptions = {
		ajax: url_list,
		ajax: {
			url: url_list,
			data: function (data) {
				data.start_date = $('#d_start_date').val();
				data.end_date = $('#d_end_date').val();
			}
		},
		columns: [
			{data: 'status', name: 'status', orderable: false, searchable: false, exportable: false},
			{data: 'order', name: 'order', orderable: false, searchable: false, exportable: false},
			{data: 'sub_total', name: 'sub_total', orderable: false, searchable: false, exportable: false},
			{data: 'tax', name: 'tax', orderable: false, searchable: false, exportable: false},
			{data: 'shipping', name: 'shipping', orderable: false, searchable: false, exportable: false},
			{data: 'total_amount', name: 'total_amount', orderable: false, searchable: false, exportable: false},
		],
		paging: false
	};
	
    adminGridDataTableInit();

	$(document).on('change', '#d_start_date, #d_end_date', function() {
		$('#adminGridDataTable').DataTable().clear().draw();
	});

    
}(jQuery));
