function fetchNewPosts() {
	$('#fetch-new-post-loader').show();
	$('.btnFetchNewPosts').addClass("disabled");
	//$('.btnFetchNewPosts').attr("disabled", true);
    $.ajax({
		type: 'POST',
		url: ajax_instagram_url,
		data: { actType: 'fetchPosts' },
		dataType: 'json',
		//contentType: 'application/json; charset=utf-8',
		//cache: false,
		//async: true,
		success: function(response) {
			try {
				//console.log(JSON.parse(JSON.stringify(response)));
				//response = JSON.parse(response);
				
				if(response.success)
				{
					location.reload();
					return false;
				}
				else
				{
					var msg = "";
					$.each(response.messages,function(index, value){
						if(index != '' && value != '') {
							msg += value+"\n";
						}
					});
					$('#fetch-new-post-loader').hide();
					$('.btnFetchNewPosts').removeClass("disabled");
					//$('.btnFetchNewPosts').attr("disabled", false);
					alert(msg);
					return false;
				}
			} catch(err) {
				$('#fetch-new-post-loader').hide();
				//$('.btnFetchNewPosts').attr("disabled", false);
				$('.btnFetchNewPosts').removeClass("disabled");
				alert("Something went wrong.");
				return false;
			}
			
		},
		error: function (jqXHR) {
			try {
				var response = $.parseJSON(jqXHR.responseText);
				var errMsgStr = '';
				$.each(response.errors,function(index, value){
					if(index != '' && value != '') {
						errMsgStr += value+"<br/>";
					}
				});
				$('#fetch-new-post-loader').hide();
				//$('.btnFetchNewPosts').attr("disabled", false);
				$('.btnFetchNewPosts').removeClass("disabled");
				if(errMsgStr != '') {
					$('#fetch-new-post-loader').hide();
					$('.btnFetchNewPosts').removeClass("disabled");
					alert(errMsgStr.slice(0,-5));
					return false;
				} else {
					$('#fetch-new-post-loader').hide();
					$('.btnFetchNewPosts').removeClass("disabled");
					alert("Something went wrong.");
					return false;
				}
			} catch(err) {
				$('#fetch-new-post-loader').hide();
				$('.btnFetchNewPosts').removeClass("disabled");
				alert("Something went wrong.");
				return false;
			}
		}
	});
}

function isJson(obj) {
    var t = typeof obj;
    return ['boolean', 'number', 'string', 'symbol', 'function'].indexOf(t) == -1;
}

(function($) {
	
	$('body').on('click', '.btnFetchNewPosts', function (e) {
		e.stopPropagation();
		e.preventDefault();
		fetchNewPosts();
	});

   $(".insta-action").click(function (){
	  $('#fetch-new-post-loader').show();
	  $('.btnFetchNewPosts').addClass("disabled");
      var id = $(this).attr('data-id');
      //console.log(elmid);
      //console.log("#"+"tbl"+elmid);
      //$("#tbl_"+elmid).hide();
      var action = $(this).attr('data-action');
      var actType = '';
      if(action == 'approve')
      {
		  actType = 'approve';
	  }
      else if(action == 'disApprove')
      {
		  actType = 'disApprove';
	  }
      $.ajax({ 
			type: 'POST',
			url: ajax_instagram_url,
			data: { actType: actType, 'id': id },
			dataType: 'json',
		   //cache: true,
		   //async: true,
		   success: function(response) { 
			   try {
					if(response.success) {
						location.reload();
						return false;
					} else {
						var msg = "";
						$.each(response.messages,function(index, value){
							if(index != '' && value != '') {
								msg += value+"\n";
							}
						});
						$('#fetch-new-post-loader').hide();
						//$('.btnFetchNewPosts').attr("disabled", false);
						$('.btnFetchNewPosts').removeClass("disabled");
						alert(msg);
						return false;
					}
				} catch(err) {
					$('#fetch-new-post-loader').hide();
					//$('.btnFetchNewPosts').attr("disabled", false);
					$('.btnFetchNewPosts').removeClass("disabled");
					alert("Something went wrong.");
					return false;
				}
		   },
		   error: function (jqXHR) {
				try {
					var response = $.parseJSON(jqXHR.responseText);
					var errMsgStr = '';
					$.each(response.errors,function(index, value){
						if(index != '' && value != '') {
							errMsgStr += value+"<br/>";
						}
					});
					$('#fetch-new-post-loader').hide();
					//$('.btnFetchNewPosts').attr("disabled", false);
					$('.btnFetchNewPosts').removeClass("disabled");
					if(errMsgStr != '') {
						alert(errMsgStr.slice(0,-5));
					} else {
						alert("Something went wrong.");
					}
				} catch(err) {
					alert("Something went wrong.");
				}
			}
	  });
      
      
      
      
   }); 

}(jQuery));

