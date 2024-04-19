function checkLockoutSession() {
	var userInSession = true;
	$.ajax({
		url: checkLockoutSessionURL,
		type: 'POST',
		dataType: 'json',
		contentType: 'application/json; charset=utf-8',
		async: false,
		success: function (response) {
			var msg = "";
			$.each(response.messages,function(index, value){
				if(index != '' && value != '') {
					msg += value+"\n";
				}
			});
			if(response.success) {
				console.log(msg);
				//location.reload();
				window.location = lockScreenURL;
			}
		},
		error: function (jqXHR) {
			var response = $.parseJSON(jqXHR.responseText);
			var errMsgStr = '';
			$.each(response.errors,function(index, value){
				if(index != '' && value != '') {
					errMsgStr += value+"<br/>";
				}
			});
			if(errMsgStr != '') {
				console.log(errMsgStr.slice(0,-5));
			} else {
				console.log("Something went wrong.");
			}
		}
	});
	return userInSession;
}

(function($) {
	setInterval(function(){
		checkLockoutSession();
	}, (59 * 1000));
}(jQuery));
