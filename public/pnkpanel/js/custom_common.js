let Toast = undefined;
let dtOptions = {};
if (typeof $adminGridDataTable == 'undefined') {
	$adminGridDataTable = $('#adminGridDataTable');
}
	
let dtGlobalOptions = {
	processing: true,
	serverSide: true,
	dom: '<"row justify-content-between"<"col-auto"><"col-auto">><"table-responsive"t>rip',
	columnDefs: [{
		targets: 0,
		orderable: false
	}],
	pageLength: 25,
	order: [],
	language: {
		paginate: {
			previous: '<i class="fas fa-chevron-left"></i>',
			next: '<i class="fas fa-chevron-right"></i>'
		},
		info : "Page (_PAGE_) : Showing _START_ to _END_ of _TOTAL_ records",
		infoEmpty: "Showing 0 to 0 of 0 records",
		infoFiltered: "(filtered from _MAX_ total records)",
		//processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span> ',
		processing: '<span class="fa-stack fa-lg"><i class="fa fa-spinner fa-spin fa-stack-2x fa-fw"></i></span>&emsp;Processing ...',
	},
	drawCallback: function(settings) {
		$adminGridDataTable.closest('.dataTables_wrapper').find('.dataTables_info').appendTo($adminGridDataTable.closest('.datatables-header-footer-wrapper').find('.results-info-wrapper'));
		$adminGridDataTable.closest('.dataTables_wrapper').find('.dataTables_paginate').appendTo($adminGridDataTable.closest('.datatables-header-footer-wrapper').find('.pagination-wrapper'));
		$adminGridDataTable.closest('.datatables-header-footer-wrapper').find('.pagination').addClass('pagination-modern pagination-modern-spacing justify-content-center');
		
		var datatable_footer = $adminGridDataTable.closest('.datatables-header-footer-wrapper').find('.datatable-footer');
		datatable_footer.toggle(this.api().page.info().pages > 0);
	}
};

var adminGridDataTableInit = function() {
	//Object.assign(dtOptions, dtGlobalOptions);
	dtOptions = Object.assign({}, dtGlobalOptions, dtOptions);
	$adminGridDataTable.dataTable(dtOptions);
	
	$(document).on('change', '.results-per-page', function() {
		var $this = $(this),
			$dataTable = $this.closest('.datatables-header-footer-wrapper').find('.dataTable').DataTable();
		$dataTable.page.len($this.val()).draw();
	});
	$(document).on('keyup', '.search-term', function() {
		var $this = $(this),
			$searchBy = $this.closest('.datatables-header-footer-wrapper').find('.search-by'),
			$dataTable = $this.closest('.datatables-header-footer-wrapper').find('.dataTable').DataTable();
		if ($searchBy.val() == 'all') {
			$dataTable.search($this.val()).draw();
		} else {
			$dataTable.columns().search('').column(parseInt($searchBy.val())).search($this.val()).draw();
		}
	});
	$(document).on('change', '.search-by', function() {
		var $this = $(this),
			$searchTerm = $this.closest('.datatables-header-footer-wrapper').find('.search-term');
		if($searchTerm.val().trim() != '') {
			var $searchField = $this.closest('.datatables-header-footer-wrapper').find('.search-term');
			$searchField.trigger('keyup');
		}
	});
	$adminGridDataTable.find('.select-all').on('change', function() {
		if (this.checked) {
			$adminGridDataTable.find('input[type="checkbox"]:not(.select-all)').prop('checked', true);
		} else {
			$adminGridDataTable.find('input[type="checkbox"]:not(.select-all)').prop('checked', false);
		}
	});
};

var reloadGrid = function() {
	$adminGridDataTable.DataTable().ajax.reload();
};

let jqValidationGlobalOptions = {
	errorElement: 'label',
	onfocusout: false,
	invalidHandler: function(form, validator) {
		var errors = validator.numberOfInvalids();
		if (errors) {                    
			validator.errorList[0].element.focus();
		}
	}
	/*errorElement: 'span',
	errorPlacement: function (error, element) {
		error.addClass('invalid-feedback');
		element.closest('.form-group').append(error);
	},
	highlight: function (element, errorClass, validClass) {
		$(element).addClass('is-invalid');
	},
	unhighlight: function (element, errorClass, validClass) {
		$(element).removeClass('is-invalid');
	}*/
};


var checkBulkYes = function() {
	var allVals = [];  
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push($(this).attr('data-id'));
	});  


	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to make it Yes.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to make it Yes?");  
		if(check == true){  
			var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action,
				type: 'post',
				data: {'ids': join_selected_values, 'actType': 'yes'},
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
	
};

var checkBulkNo = function() {
	var allVals = [];  
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push($(this).attr('data-id'));
	});  


	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to make it No.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to make it No?");  
		if(check == true){  
			var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action,
				type: 'post',
				data: {'ids': join_selected_values, 'actType': 'no'},
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
	
};

var checkBulkActive = function() {
	var allVals = [];  
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push($(this).attr('data-id'));
	});  


	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to make it Active.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to make it Active?"); 
		var is_custom_status = false;
		if(typeof(custom_status_variable) != "undefined" && custom_status_variable !== null) {
			is_custom_status = custom_status_variable;
		}
		if(check == true){  
			var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action,
				type: 'post',
				data: {'ids': join_selected_values, 'actType': 'active','is_custom_status': is_custom_status},
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
	
};

var checkBulkInActive = function() {
	var allVals = [];  
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push($(this).attr('data-id'));
	});  


	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to make it Inactive.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to make it Inactive?");  
		var is_custom_status = false;
		if(typeof(custom_status_variable) != "undefined" && custom_status_variable !== null) {
			is_custom_status = custom_status_variable;
		}
		if(check == true){  
			var join_selected_values = allVals.join(","); 
			//alert(url_bulk_action);
			$.ajax({
				url: url_bulk_action,
				type: 'post',
				data: {'ids': join_selected_values, 'actType': 'inactive','is_custom_status': is_custom_status},
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
}

var checkBulkDelete = function() {
	var allVals = [];  
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push($(this).attr('data-id'));
	});  


	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to Delete.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to Delete?");  
		if(check == true){  
			var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action,
				type: 'post',
				data: {'_method': 'delete', 'ids': join_selected_values, 'actType': 'delete'},
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}
};

var checkBulkUpdateRank = function() {
	var allVals = [];
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push( { id: $(this).attr('data-id'), display_position: $('#display_position_' + $(this).attr('data-id')).val() } );
	});  

	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to Update Rank.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to Update Rank?");  
		if(check == true){  
			//var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action_update_rank,
				type: 'post',
				dataType: 'json',
				contentType: 'application/json',
				data: JSON.stringify({ids: allVals, actType: 'update_rank'}),
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
}

var checkBulkUpdateGroupRank = function() {
	var allVals = [];
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push( { id: $(this).attr('data-id'), display_group_position: $('#display_group_position_' + $(this).attr('data-id')).val() } );
	});  

	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to Update Group Rank.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to Update Group Rank?");  
		if(check == true){  
			//var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action_update_group_rank,
				type: 'post',
				dataType: 'json',
				contentType: 'application/json',
				data: JSON.stringify({ids: allVals, actType: 'update_group_rank'}),
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
}


var checkBulkUpdateSaleRank = function() {
	var allVals = [];
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push( { id: $(this).attr('data-id'), display_group_position: $('#display_sale_position_' + $(this).attr('data-id')).val() } );
	});  

	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to Update Sale Rank.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to Update Sale Rank?");  
		if(check == true){  
			//var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action_update_sale_rank,
				type: 'post',
				dataType: 'json',
				contentType: 'application/json',
				data: JSON.stringify({ids: allVals, actType: 'update_sale_rank'}),
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
}

var checkBulkUpdateLiftGateSettings = function() {
	var allVals = [];
	$("input.subChk[type=checkbox]:checked").each(function() {  
		allVals.push( { id: $(this).attr('data-id'), lift_gate_charge: $('#lift_gate_charge_' + $(this).attr('data-id')).val(), is_lift_gate : $('#is_lift_gate_' + $(this).attr('data-id')).val() } );
	});  

	if(allVals.length <=0)  
	{  
		alert("Please select record(s) to Update Rank.");  
	}
	else
	{  
		var check = confirm("Are you sure, You want to Update Lift gate settings?");  
		if(check == true){  
			//var join_selected_values = allVals.join(","); 
			$.ajax({
				url: url_bulk_action_update_lift_gate_settings,
				type: 'post',
				dataType: 'json',
				contentType: 'application/json',
				data: JSON.stringify({ids: allVals, actType: 'update_lift_gate_settings'}),
				success: function (response) {
					parseBukActionSuccessResponse(response);
				},
				error: function (jqXHR) {
					parseBukActionErrorResponse(jqXHR);
				}
			});

		}  
	}  
}
var parseBukActionSuccessResponse = function(response) {
	var msg = "";
	$.each(response.messages,function(index, value){
		if(index != '' && value != '') {
			msg += value+"<br/>";
		}
	});
	if(response.success) {
		toastr.options = {
			"closeButton" : true,
			"progressBar" : true
		}
		toastr.success(msg);
		$('#bulk_action option:first').prop('selected',true);
		$('#mainChk').prop('checked', false);
		reloadGrid();
		if(typeof individualAdminAfterCompletedProcess == 'function'){
			individualAdminAfterCompletedProcess(response.ids);
		}
	} else {
		toastr.options = {
			"closeButton" : true,
			"progressBar" : true
		}
		toastr.error(msg);
	}
}

var parseBukActionErrorResponse = function( jqXHR) {
	var response = $.parseJSON(jqXHR.responseText);
	var errMsgStr = '';
	$.each(response.errors,function(index, value){
		if(index != '' && value != '')
		{
			errMsgStr += value+"<br/>";
		}
	});
	if(errMsgStr != '')
	{
		toastr.options = {
			"closeButton" : true,
			"progressBar" : true
		}
		toastr.error(errMsgStr.slice(0,-5));
		if(typeof individualAdminAfterCompletedProcess == 'function'){
			individualAdminAfterCompletedProcess(response.ids);
		}
	}
	else
	{
		toastr.options = {
			"closeButton" : true,
			"progressBar" : true
		}
		toastr.error("Something went wrong, try again later.");
	}
}


$(function () {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	if ($("#frmAdmin").length > 0) {
		jQuery.validator.addMethod("phoneno", function(phone_number, element) {
			phone_number = phone_number.replace(/\s+/g, "");
			return this.optional(element) || phone_number.length > 9 && 
			phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
		}, "<br />Please specify a valid phone number");
	}
	
	$('body').on('click', '.btnCancelSaveRecord', function (e) {
		e.stopPropagation();
		e.preventDefault();
		window.location = url_list;
		return;
	});
	
	$('body').on('click', '.btnEditRecord', function (e) {
		e.stopPropagation();
		e.preventDefault();
		var id = $(this).data('id');
		url_edit = url_edit.replace(':id', id);
		window.location = url_edit;
		return;
	});

	$('body').on('click', '.btnEditTaxAreaRateRecord', function (e) {
		e.stopPropagation();
		e.preventDefault();
		var id = $(this).data('id');
		url_edit_tax_area_rate = url_edit_tax_area_rate.replace(':id', id);
		window.location = url_edit_tax_area_rate;
		return;
	});
	
	$('body').on('click', '.btnDeleteRecord', function (e) {
		e.stopPropagation();
		e.preventDefault();
		var id = $(this).data('id');
		url_delete = url_delete.replace(':id', id);
        var check = confirm("Are you sure, You want to Delete?");  
		if(check == true){ 
			if(typeof individualAdminAfterCompletedProcess == 'function'){
				individualAdminAfterCompletedProcess(id);
			} 
			setTimeout(function () { $('<form action="'+url_delete+'" method="post"><input type="hidden" name="_method" value="delete"><input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'"></form>').appendTo('body').submit();
			return;},1000);
		}
    }); 
    
    $('body').on('click', '.btnBulkAction', function (e) {
		e.stopPropagation();
		e.preventDefault();
		
		var opt = $('#bulk_action option:selected').val();
		if(opt == '') {
			$('#bulk_action').focus();
			alert("Please select which bulk action do you want to perform.");
		}
		
		if(opt=='delete')
			checkBulkDelete();
		else if(opt=='active')
			checkBulkActive();
		else if(opt=='inactive')
			checkBulkInActive();
		else if(opt=='update_rank')
			checkBulkUpdateRank();
		else if(opt=='update_group_rank')
			checkBulkUpdateGroupRank();
		else if(opt=='update_sale_rank')
			checkBulkUpdateSaleRank();
		else if(opt=='update_lift_gate_settings')
			checkBulkUpdateLiftGateSettings();
		else if(opt=='create_clone')
			createProductClone();
		else if(opt=='yes')
			checkBulkYes();
		else if(opt=='no')
			checkBulkNo();
		else if(opt=='email_forgot_password')
			checkBulkEmailForgotPassword();

		return false;
    });
    
    $('body').on('click', '.btnViewImage', function (e) {
		e.stopPropagation();
		e.preventDefault();
		
		var type = $(this).data('type');
		var src = $(this).data('src');
		var caption = $(this).data('caption');

		var items = [];
		items.push( {
			src: src,
			titleSrc: caption
		} );
		var index = 0;
		
		$.magnificPopup.open({
			type: 'image',
			gallery: {
			  enabled: true
			},
			items: items,
			image: {
				titleSrc: function(item) {
					return item.data.titleSrc;
				}
			}
		}, index);
    });

    $('body').on('click', '.btnViewPopup', function (e) {
    	
		e.stopPropagation();
		e.preventDefault();

		$('.simple-ajax-modal').magnificPopup({
			type: 'ajax',
			modal: true
		});
		
		/*$.magnificPopup.open({
			type: 'ajax',
			ajax: {
		        settings: {
		          url: './ajax/page.html',
		          type: 'POST'
		        }
      		},
      		modal: true
		});*/
    });

	$('body').on('click', '.btnDeleteImage', function (e) {
		var check = confirm("Are you sure, You want to Delete Image?");  
		if(check == true) {
			var type = $(this).data('type');
			var subtype = $(this).data('subtype');
			var id = $(this).data('id');
			var image_name = $(this).data('image-name');
			
			if(image_name != '' && image_name != undefined)
			{
				e.stopPropagation();
				e.preventDefault();
			
				var that = $(this);
				$.ajax({
					url: url_delete_image,
					type: 'post',
					dataType: 'json',
					contentType: 'application/json',
					data: JSON.stringify({'_method': 'delete', actType: 'delete_image', type: type, subtype: subtype, id: id, image_name: image_name}),
					success: function (response) {
						var msg = "";
						//console.log(index);
						//console.log(value);
						
						//return false;
						$.each(response.messages,function(index, value){
							if(index != '' && value != '') {
								msg += value+"<br/>";
							}
						});
						if(typeof individualAdminAfterCompletedProcess == 'function'){
							individualAdminAfterCompletedProcess(id);
						}
						if(response.success) {
							toastr.options = {
								"closeButton" : true,
								"progressBar" : true
							}
							toastr.success(msg);
							that.closest('.fileupload').removeClass('fileupload-exists').addClass('fileupload-new');
							that.closest('.fileupload').find('.fileupload-view').hide();
							that.closest('.fileupload').find('.fileupload-preview').html('');
							$(this).data('image-name', '')
						} else {
							toastr.options = {
								"closeButton" : true,
								"progressBar" : true
							}
							toastr.error(msg);
						}
					},
					error: function (jqXHR) {
						var response = $.parseJSON(jqXHR.responseText);
						var errMsgStr = '';
						$.each(response.errors,function(index, value){
							if(index != '' && value != '')
							{
								errMsgStr += value+"<br/>";
							}
						});
						if(errMsgStr != '')
						{
							toastr.options = {
								"closeButton" : true,
								"progressBar" : true
							}
							toastr.error(errMsgStr.slice(0,-5));
						}
						else
						{
							toastr.options = {
								"closeButton" : true,
								"progressBar" : true
							}
							toastr.error("Something went wrong, try again later.");
						}
					}
				});
			}
		} else {
			e.stopPropagation();
            e.preventDefault();
		}
    });


    $('body').on('click', '.btnDeletePdf', function (e) {
		var check = confirm("Are you sure, You want to Delete PDF?");  
		
		if(check == true) {
			var type = $(this).data('type');
			var subtype = $(this).data('subtype');
			var id = $(this).data('id');
			var image_name = $(this).data('image-name');
			
			if(image_name != '' && image_name != undefined)
			{
				e.stopPropagation();
				e.preventDefault();
			
				var that = $(this);
				$.ajax({
					url: url_delete_pdf,
					type: 'post',
					dataType: 'json',
					contentType: 'application/json',
					data: JSON.stringify({'_method': 'delete', actType: 'delete_pdf', type: type, subtype: subtype, id: id, image_name: image_name}),
					success: function (response) {
						var msg = "";
						//console.log(index);
						//console.log(value);
						
						//return false;
						$.each(response.messages,function(index, value){
							if(index != '' && value != '') {
								msg += value+"<br/>";
							}
						});
						if(typeof individualAdminAfterCompletedProcess == 'function'){
							individualAdminAfterCompletedProcess(id);
						}
						if(response.success) {
							toastr.options = {
								"closeButton" : true,
								"progressBar" : true
							}
							toastr.success(msg);
							that.closest('.fileupload').removeClass('fileupload-exists').addClass('fileupload-new');
							that.closest('.fileupload').find('.fileupload-view').hide();
							that.closest('.fileupload').find('.fileupload-preview').html('');
							$(this).data('image-name', '')
						} else {
							toastr.options = {
								"closeButton" : true,
								"progressBar" : true
							}
							toastr.error(msg);
						}
					},
					error: function (jqXHR) {
						var response = $.parseJSON(jqXHR.responseText);
						var errMsgStr = '';
						$.each(response.errors,function(index, value){
							if(index != '' && value != '')
							{
								errMsgStr += value+"<br/>";
							}
						});
						if(errMsgStr != '')
						{
							toastr.options = {
								"closeButton" : true,
								"progressBar" : true
							}
							toastr.error(errMsgStr.slice(0,-5));
						}
						else
						{
							toastr.options = {
								"closeButton" : true,
								"progressBar" : true
							}
							toastr.error("Something went wrong, try again later.");
						}
					}
				});
			}
		} else {
			e.stopPropagation();
            e.preventDefault();
		}
    });

    
});


$(function () {
	

});

