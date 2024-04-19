(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			{data: 'email', name: 'email'},
			{data: 'first_name', name: 'first_name'},
			{data: 'last_name', name: 'last_name'},
			{data: 'insert_datetime', name: 'insert_datetime'},
			{data: 'status', name: 'status', "render": function(data, type, row, meta) {
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
			//{data: 'banner_position', name: 'banner_position', orderable: false, searchable: false, exportable: false, visible: false},
			//{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		]
	};
	
    adminGridDataTableInit();
    
}(jQuery));


// (function($) {
// 	dtOptions = {
// 		ajax: url_list,
// 		columns: [
// 			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
// 			{data: 'email', name: 'email'},
// 			{data: 'first_name', name: 'first_name'},
// 			 {data: 'last_name', name: 'last_name'},
// 			 {data: 'insert_datetime', name: 'insert_datetime'},
// 			{data: 'status', name: 'status', "render": function(data,type,row,meta) {
// 				var status_cell_val = data;
// 				switch (data){
// 					case "Active":
// 						status_cell_val = '<span class="badge badge-success p-2">'+data+'</span>';
// 						break;
// 					case "Inactive":
// 						status_cell_val = '<span class="badge badge-danger p-2">'+data+'</span>';
// 						break;
// 				}
// 				return status_cell_val;
// 			}},
// 		],
// 	};
	
//     adminGridDataTableInit();
    
// }(jQuery));
