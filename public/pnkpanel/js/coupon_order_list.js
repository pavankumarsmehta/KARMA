(function($) {
	dtOptions = {
		ajax: {
			url: url_list,
			data: function (data) {
				data.coupon_id = $('#coupon_id').val();
			}
		},
		order: [[ 1, "desc" ]],
		columns: [
			{data: 'order_id', name: 'order_id'},
			{data: 'order_datetime', name: 'order_datetime'},
			//{data: 'order_date', name: 'order_date'},
			//{data: 'order_time', name: 'order_time', orderable: false},
			{data: 'customer', name: 'customer', orderable: false},
			{data: 'bill_email', name: 'bill_email', visible: false},
			{data: 'sub_total', name: 'sub_total'},
			{data: 'tax', name: 'tax'},
			{data: 'shipping_amt', name: 'shipping_amt'},
			{data: 'order_total', name: 'order_total'},
			{data: 'status', name: 'status', "render": function(data, type, row, meta) {
				var status_bg = '#0102D0';
				switch (data){
					case "Pending":
						status_bg = '#0102D0';
						break;
					case "Completed":
						status_bg = '#006300';
						break;
					case "Canceled":
						status_bg = '#E78442';
						break;
					case "Declined":
						status_bg = '#AA0000';
						break;
					case "Refunded":
						status_bg = '#EC2E15';
						break;
					case "Partial Refund":
						status_bg = '#EC2E15';
						break;
					case "Admin Review":
						status_bg = '#9E996B';
						break;
				}
				return '<span class="badge p-2" style="color:#FFFFFF;background-color:'+status_bg+'">'+data+'</span>';
			}},
		],
	};
	
    adminGridDataTableInit();
    
}(jQuery));
