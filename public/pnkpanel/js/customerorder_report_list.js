(function($) {
	dtOptions = {
		ajax: url_list,
		ajax: {
			url: url_list,
			data: function (data) {
				data.start_date = $('#d_start_date').val();
				data.end_date = $('#d_end_date').val();
				data.filterByAmountSpent = $('#filterByAmountSpent option').filter(':selected').val();
				data.filterByNoOrders = $('#filterByNoOrders option').filter(':selected').val();
				data.filterByCountry = $('#filterByCountry option').filter(':selected').val();
				data.filterByState = $('#filterByState option').filter(':selected').val();
				data.filterByCategory = $('#filterByCategory option').filter(':selected').val();
			}
		},
		columns: [
			{data: 'customer_name', name: 'customer_name', orderable: true, searchable: false, exportable: false},
			{data: 'total_order', name: 'total_order', orderable: true, searchable: false, exportable: false},
			{data: 'total_amount', name: 'total_amount', orderable: true, searchable: false, exportable: false},
		],
	};
	
    adminGridDataTableInit();

	$(document).on('change', '#d_start_date, #d_end_date, #filterByAmountSpent, #filterByNoOrders, #filterByCategory, #filterByCountry, #filterByState', function() {
		$('#adminGridDataTable').DataTable().clear().draw();
	});

    
}(jQuery));
