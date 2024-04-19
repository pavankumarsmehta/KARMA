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
		order: [[ 1, "desc" ]],
		columns: [
			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},
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
		]
	};
	
    adminGridDataTableInit();

	/*$(document).on('change', '#filterStatus', function() {
		$('#adminGridDataTable').DataTable().clear().draw();
	});*/

//------- order export use checkbox--------------
$(document).on('click', '#mainChk', function(e) {

if($(this).is(':checked',true))  
{
$(".list_checkbox").prop('checked', true);  
} else {  
$(".list_checkbox").prop('checked',false);  
}  
});
$('.list_checkbox').on('click',function(){
if($('.list_checkbox:checked').length == $('.list_checkbox').length){
$('#mainChk').prop('checked',true);
}else{
$('#mainChk').prop('checked',false);
}
});
$('#exportOrder').on('click', function(e) {
var idsArr = [];  
$(".list_checkbox:checked").each(function() {  
idsArr.push($(this).attr('data-id'));
});  
// if(idsArr.length ==0)  
// {  
// alert("Please select atleast one record to delete.");  
// }  else {  
if(confirm("Do You Want to Export Orders ?")){  
var strIds = idsArr.join(",");

$.ajax({
url: sampleOrder_list,
type: 'GET',
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
data: 'ids='+strIds,
success: function (data) {
if (data) {
var a = document.createElement("a");
            a.href = data.file;
            a.download = data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
        }else {

        }
    },
error: function (data) {
//alert(data.responseText);
}
});
}  
//}  
});

	$(document).on('click', '#btnSerach', function() {
		var $searchBy = $('.search-by'), $searchTerm = $('.search-term'), $dataTable = $('#adminGridDataTable').DataTable();
		var $searchByValue = $searchBy.val(), $searchTermValue = $searchTerm.val();
		if($searchByValue.trim() != '') {
			if ($searchByValue == 'all') {
				$dataTable.search($searchTermValue).draw();
			} else {
				$dataTable.columns().search('').column(parseInt($searchByValue)).search($searchTermValue).draw();
			}
		} else {
			$dataTable.clear().draw();
		}
	});
	
	$(document).off('keyup', '.search-term');
	$(document).off('change', '.search-by');
	/*$(document).off('keyup', '.search-term').on('keyup', '.search-term', function() {
		var $this = $(this),
			$searchBy = $this.closest('.card-body').find('.search-by'),
			$dataTable = $('#adminGridDataTable').DataTable();
		if ($searchBy.val() == 'all') {
			$dataTable.search($this.val()).draw();
		} else {
			$dataTable.columns().search('').column(parseInt($searchBy.val())).search($this.val()).draw();
		}
	});
	
	$(document).off('change', '.search-by').on('change', '.search-by', function() {
		console.log('change');
		var $this = $(this),
			$searchTerm = $this.closest('.card-body').find('.search-term');
		if($searchTerm.val().trim() != '') {
			var $searchField = $this.closest('.card-body').find('.search-term');
			$searchField.trigger('keyup');
		}
	});*/
    
}(jQuery));

/*
// Ajax Customer
var xhr = null;
function Show_SearchTerms()
{
	$('#filterCustomer').val(0);
	if($('#customer_ID').val() == '')
	{
		$('#SearchTerms_List').fadeOut();
		$("#SearchTerms_List").html('');
		return false;
	}
	
	// Use For Kill Previuos Ajax Rquest
	if( xhr != null ) 
	{
           xhr.abort();
           var xhr = null;
    }

	xhr = $.ajax({
		type: 'post',
		url: url_auto_suggest_customer_name,
		data: JSON.stringify({search_keyword: $('#customer_ID').val()}),
		dataType: "json",
		contentType: 'application/json',
		cache: false,
		beforeSend: function() 
		{	
			//$('#SearchTerms_List').fadeOut();
			$("#SearchTerms_List").html('<img src="'+url_ajax_loader+'" hspace="70" />');
		},
		success: (function(data, status) 
		{
			var SearchTerms_List = '';
			$.each(data, function (i, item) {
				SearchTerms_List +=  '<li class="Terms-LIHighLight">'+item.first_name+' '+item.last_name+' <span style="display:none;">'+item.customer_id+'</span></li>';
			});
			
			$('#SearchTerms_List').fadeIn(1000);
			$("#SearchTerms_List").html(SearchTerms_List);
			$(".Terms-LIHighLight").click(function () 
			{
					// Assign Selected value to search box 
					$('#filterCustomer').val($(this).find('span:first').text());
					var c_name = $(this).find('span:first').remove();
					$('#customer_ID').val($(this).text());
					
					$('#SearchTerms_List').fadeOut();
					$("#SearchTerms_List").html('');
					return false;
			 });
		})
	});
	return false;
}
*/

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

	
