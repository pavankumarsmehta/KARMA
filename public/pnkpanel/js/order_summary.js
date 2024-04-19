(function($) {
	if ( $.isFunction($.fn[ 'mask' ]) ) {

		$(function() {
			$('[data-plugin-masked-input]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginMaskedInput(opts);
			});
		});

	}
	
	dtOptions = {
		ajax: {
			url: url_list,
			data: function (data) {
				data.filterCustomer = $('#filterCustomer').val();
				data.filterStatus = $('#filterStatus option').filter(':selected').val();
				data.filterStartDate = $('#filterStartDate').val();
				data.filterEndDate = $('#filterEndDate').val();
			}
		},
		order: [[ 0, "desc" ]],
		columnDefs: [],
		columns: [
			{data: 'orderDate', name: 'orderDate'},
			{data: 'orderCount', name: 'orderCount'},
			{data: 'pendingCount', name: 'pendingCount', orderable: false},
			{data: 'closedCount', name: 'closedCount', orderable: false},
			{data: 'cancelCount', name: 'cancelCount', orderable: false},
			{data: 'declinedCount', name: 'declinedCount', orderable: false},
			{data: 'totalAmount', name: 'totalAmount'},
			{data: 'action', name: 'action', orderable: false},
		]
	};
	
    adminGridDataTableInit();

	$(document).on('click', '#btnSerach', function() {
		$dataTable = $('#adminGridDataTable').DataTable();
		$dataTable.clear().draw();
	});

}(jQuery));

$(function() {
    $( "#customer_ID" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				type: 'post',
				url: url_auto_suggest_customer_name,
				dataType: "json",
				data: JSON.stringify({search_keyword: $('#customer_ID').val()}),
				contentType: 'application/json',
				cache: false,
				beforeSend: function() 
				{	
					$('#filterCustomer').val(0);
				},
				success: function(data, status) {
					if (!data.length) {
						var result = [{ label: "no results", value: response.term, customer_id: 0 }];
						response(result);
					}
					else {
						//response( data );
						response($.map(data, function (item) {
							return {
								label: item.first_name+' '+item.last_name,
								value: item.first_name+' '+item.last_name,
								customer_id: item.customer_id,
							};
						}));
					}
				},
				error: function(jqXHR, exception) {
					  console.log('Error: ' + jqXHR.responseText);
				}
			});
		},
		minLength: 3,
		select: function( event, ui ) {
			var label = ui.item.label;
            if (label === "no results") {
				event.preventDefault();
				$('#filterCustomer').val(0);
			}
			else {
				$('#filterCustomer').val(ui.item.customer_id);
			}
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		change: function( event, ui ) {
			$( "#filterCustomer" ).val( ui.item ? ui.item.customer_id : 0 );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
    });
});


$(function () {
	$('body').on('click', '.btnSubmitPrintOrders', function (e) {
		e.stopPropagation();
		e.preventDefault();

		let jqValidationOptions = {
			rules: {
				start_id: {
					required: true,
					number: true
				},
				end_id: {
					required: true,
					number: true
				}
			},
			messages: {
				start_id: {
					required: "Please enter Start Order ID.",
					number: "Please enter valid Start Order ID."
				},
				end_id: {
					required: "Please enter End Order ID.",
					number: "Please enter valid End Order ID."
				}
			}
		};
		$('#frmPrintOrders').removeData('validator');
		Object.assign(jqValidationOptions, jqValidationGlobalOptions);
		$('#frmPrintOrders').validate(jqValidationOptions);
		
		if(!$("#frmPrintOrders").valid()) {
			return false;
	   }
		
		$('#frmPrintOrders').submit();
	});

	$('body').on('click', '.btnSubmitPrintPackagingSlip', function (e) {
		e.stopPropagation();
		e.preventDefault();
		
		let jqValidationOptions = {
			rules: {
				start_id: {
					required: true,
					number: true
				},
				end_id: {
					required: true,
					number: true
				}
			},
			messages: {
				start_id: {
					required: "Please enter Start Order ID.",
					number: "Please enter valid Start Order ID."
				},
				end_id: {
					required: "Please enter End Order ID.",
					number: "Please enter valid End Order ID."
				}
			}
		};
		$('#frmPrintPackagingSlip').removeData('validator');
		Object.assign(jqValidationOptions, jqValidationGlobalOptions);
		$('#frmPrintPackagingSlip').validate(jqValidationOptions);
		
		if(!$("#frmPrintPackagingSlip").valid()) {
			return false;
	   }
		
		$('#frmPrintPackagingSlip').submit();
	});

});
