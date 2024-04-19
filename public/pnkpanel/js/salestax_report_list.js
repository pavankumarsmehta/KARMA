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
			{data: 'customer_name', name: 'customer_name', orderable: false, searchable: false, exportable: false},
			{data: 'cnt_order', name: 'cnt_order', orderable: false, searchable: false, exportable: false},
			{data: 'cnt', name: 'cnt', orderable: false, searchable: false, exportable: false},
		],
	};
	
    adminGridDataTableInit();

	$(document).on('change', '#d_start_date, #d_end_date', function() {
		$('#adminGridDataTable').DataTable().clear().draw();
	});

    
}(jQuery));
