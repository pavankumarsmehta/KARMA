var SITE_URL = "<?php echo URL::to('/'); ?>";

Number.prototype.toFixed = function(decimals) {
	var decimals = decimals || 2 // defaults to two
	var catalyst = Math.pow(10, decimals);
	var fixedNum = Math.round(parseFloat(this) * catalyst) / catalyst;
	if (fixedNum % 1) {
		return fixedNum
	} else {
		var str = "."
		while(decimals--) {
			str += "0"
		}
		return fixedNum + str
	}
}

let roundto = function(num) {
	return Math.round(num * 100)/100;
}
$(document).ready(function(){
	$('.bulk_select_return_item').click(function(){
        var $checkbox = $(this);
		var id = $(this).data('id');
        if($checkbox.is(':checked')) {
			$('#acceptRejectReturnItemDiv_'+id).css('display', 'none');
			$('#return_request_accept_reject_reason_'+id).css('display', 'none');
        } else {
			$('#acceptRejectReturnItemDiv_'+id).css('display', 'block');
			$('#return_request_accept_reject_reason_'+id).css('display', 'block');
        }
    });
});

$('body').on('click', '.btnAcceptRejectReturnItem', function (e) {
	e.stopPropagation();
	e.preventDefault();
	var type = $(this).data('type');
	var id = $(this).data('id');
	var order_id = $('#order_id').val();
	
	var selectedValues = [];
	if(type == 'bulk_accept') {
		var msg = "Are you sure you want to bulk Accept return request?";
		var reason = $("#return_request_accept_reject_reason").val();
          $('input[name^="bulk_select_return_items[]"]:checked').each(function() {
              selectedValues.push($(this).val());
          });
	} else if(type == 'bulk_reject') {
		var msg = "Are you sure you want to bulk Reject return request?";
		var reason = $("#return_request_accept_reject_reason").val();
		$('input[name^="bulk_select_return_items[]"]:checked').each(function() {
			selectedValues.push($(this).val());
		});
	}else if(type == 'accept') {
		var msg = "Are you sure you want to Accept this return request?";
		var reason = $("#return_request_accept_reject_reason_"+id).val();
		selectedValues.push(id);
	}else {
		var msg = "Are you sure you want to Reject this return request?";
		var reason = $("#return_request_accept_reject_reason_"+id).val();
		selectedValues.push(id);
	}
	if(selectedValues.length == 0) {
		alert('Please select at least one return request.');
		return false;
	}
	if(reason == '') {
		alert('Please enter reason for accepting or rejecting return request.');
		return false;
	}
	
	if(confirm(msg)) {
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			data: { type: type, selectedValues: selectedValues, reason: reason, order_id: order_id},
			url: 'acceptRejectReturnOrder',
			dataType: 'JSON',
			success: function(response) {
				if(response.success) {
					window.location.reload();
				}
			}
		});
	}
});