(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'coupon_title', name: 'coupon_title'},
			{data: 'coupon_number', name: 'coupon_number'},
			{data: 'orders', name: 'orders'},
			 {data: 'order_amount', name: 'order_amount'},
			 {data: 'discount', name: 'discount'},
			 {data: 'start_date', name: 'start_date'},
			 {data: 'total_sales', name: 'total_sales'},
			 {data: 'total_discount', name: 'total_discount'},
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
