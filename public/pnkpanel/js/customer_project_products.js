(function($) {
	dtOptions = {
		ajax: {
			url: url_list,
			data: function (data) {
				data.customer_id = $('#customer_id').val();
			}
		},
		columns: [
			// {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'products_id', name: 'products_id', visible: false},
			{data: 'category_id', name: 'category_id', visible: false},
			{data: 'image_name', name: 'image_name', orderable: false, searchable: false},
			{data: 'product_name', name: 'product_name', orderable: false, searchable: false},
			{data: 'name', name: 'name', orderable: false, searchable: false},
			{data: 'description', name: 'description', orderable: false, searchable: false},
			// {data: 'country', name: 'country', visible: false},
		],
	};
	
    adminGridDataTableInit();
    
}(jQuery));
