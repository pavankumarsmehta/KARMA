(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
			
			{data: 'title', name: 'title'},

			{data: 'description', name: 'description'},

			{data: 'date', name: 'date'},

			{data: 'type', name: 'type'},

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

function individualAdminAfterCompletedProcess(ids){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontcachecategorylist',
		data: {
			category_ids: ids,
		},
		success: function(data) {
			console.log('Category cache clear sucessfully');

		}
	});
 }
