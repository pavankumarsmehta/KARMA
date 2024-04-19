$( "#meta-info" ).on( "click", ".nav-link", function() {
	let new_html = $( "#tabContent" ).find( ".active.show" ).html();
	let type = $( this ).data('type');
	let href = $( this ).attr('href');
	let tabId = href.slice(1);

	$.ajax({
		url: url_get_html,
		type: 'post',
		data: {'type': type},
		success: function (response) {
			if(response.success == true) {
				console.log(response);
				console.log(href);
				$('.tab-pane').html('');
				$(href).html(response.html);
			}
		},
		error: function (jqXHR) {
			console.log(response);
		}
	});

	$("#type").val(type);
});

 function individualAdminAfterCompletedProcess(){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontmetainfocache',
		data: {
			parent_sku: '',
		},
		success: function(data) {
			console.log('Meta info cache clear sucessfully');

		}
	});
 }