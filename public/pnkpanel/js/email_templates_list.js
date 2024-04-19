(function($) {
	dtOptions = {
		ajax: url_list,
		columns: [			
			{data: 'title', name: 'title', orderable: false, searchable: false, exportable: false},
			{data: 'subject', name: 'subject', orderable: false, searchable: false, exportable: false},				
			{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},
		]
	};
	
    adminGridDataTableInit();
    
}(jQuery));


$("#adminGridDataTable_wrapper tbody" ).on( "click", ".btnViewModal", function() {
    event.preventDefault();
    var e = $(this);
    var url = e.attr('href');
    $.ajax({
		url: url,
		type: 'get',
		dataType: 'json',
		success: function (response) {
		    $('.modal-title').html(response.title);
		    $('.modal-body').html(response.body);
		    $('#modalBootstrap').modal('show');
		},
		error: function (jqXHR) {
			var response = jqXHR.responseJSON;
			toastr.error(response.message);
		}
	});
});