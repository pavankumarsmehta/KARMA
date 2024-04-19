
$( document ).ready(function() {
});

function isObjectNotEmpty(obj) {
  for (const key in obj) {
    if (obj.hasOwnProperty(key)) {
      return true; // At least one property exists
    }
  }
  return false; // Object is empty
}
/*
* Email A Friend
*/
$(document).on("click", '.emailafriend', function () {
	var productId = $(this).data("pid");
	var str = "productId=" + productId;
	$("#page-spinner").show();
	$.ajax({
		type: "POST",
		url: site_url + "/email_friend",
		data: str,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}

	}).done(function (msg) {
		$("#page-spinner").hide();
		$("#tell-friend-modal").html(msg);
		$('#tell-friend-modal').modal('show');
	});
});

/*
*Login Popup Box
*/
$(document).on("click",".loginpopup", function(){
	$("#myModalPopUpLogin").html('');
	$("#page-spinner").show();
	//var str = "isAction=" + 'login_popup' + "&isPopup=" + "Yes";
	var str = "isAction=login_popup&isPopup=Yes";
	$.ajax({
		type: "POST",
		url: site_url + "/popup",
		data: str,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}

	}).done(function (msg) {
	//	console.log(msg);
		$("#page-spinner").hide();		
		$("#myModalPopUpLogin").html(msg);
		$('#myModalPopUpLogin').modal('show');
	});
})

/*
* WishList Popup Box
*/
var open_from_where = "";
$(document).on("click", ".displaypopupboxwishlist", function () {
	const products_id = $(this).attr('data-productId');
	const sectionName = $(this).attr('data-section-name');
	const brandId = $(this).attr('data-brand-id');
	const categoryId = $(this).attr('data-category-id');
	if ($(this).hasClass("active")) {
		  const isConfirmed = confirm("Are you sure you want to remove this product from wishlist?");
  
  if (isConfirmed) {
	var str = "products_id=" + products_id + "&isAction=remove_wish&isPopup=Yes&section_name=" + sectionName + 
	"&brand_id=" + brandId + "&category_id=" + categoryId;
	$.ajax({
	  type: "POST",
	  url: site_url + "/popup",
	  data: str,
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  },
	  success: function (result) {
	  	if(result.wishlistCount){
	  		$('#cart-item-count').addClass('cart-item-count');
	  		$('.cart-item-count').text(result.wishlistCount);
	  	} else {
          $('#cart-item-count').removeClass('cart-item-count');
          $("#cart-item-count").hide();
      } 
	    $(`.displaypopupboxwishlist[data-productId="${products_id}"]`).removeClass('active');
	  },
	  error: function (xhr, textStatus, errorThrown) {
	    console.error('Error:', errorThrown);
	    // Handle the error here
	  }
	});
    /*var str = "products_id=" + products_id + "&isAction=" + 'remove_wish' + "&isPopup=" + "Yes" + "&section_name=" + sectionName;
		console.log('str', str)
		$.ajax({
			type: "POST",
			url: site_url + "/popup",
			data: str,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		}).done((msg) => {
			console.log('msg', msg)


		});*/
    // alert("Confirmed!");
  }
  } else {
	open_from_where = $(this).attr('data-openfrom');
	$("#myModalPopUpLogin").html('');
	$("#page-spinner").show();
	var str = "products_id=" + products_id + "&isAction=" + 'wish_login' + "&isPopup=" + "Yes" + "&section_name=" + sectionName + "&brand_id=" + brandId + "&category_id=" + categoryId;

	$.ajax({
		type: "POST",
		url: site_url + "/popup",
		data: str,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}

	}).done(function (msg) {
		$("#page-spinner").hide();
		//console.log(msg);
		$("#myModalPopUpLogin").html(msg);
		$('#myModalPopUpLogin').modal('show');
	});
  }
});
$(document).on("click", ".quickview", function () {
	$('#page-spinner').show();
	var pid = $(this).attr("data-productId");
	data_str = "products_id=" + pid;
	$.ajax({
	  url: site_url + "/quick-view-popup",
	  type: "GET",
	  data: data_str,
	  success: function (data) {
	   
		if (data != "") {
		  $('#quickview-slider').remove();
		  $('#quickview').remove();
		  $("#quick-view").remove();
		  $("body").append(data);
		  setTimeout(function(){ 
		  quickview_bind_event();
		  },1000);
		  $("#quick-view").modal("show");
		  $('#page-spinner').hide();
		}
	  },
	  complete: function () {},
	  error: function (data) {
		$('#page-spinner').hide();
	  },
	});
  });
  
  $("#quick-view").on("hidden.bs.modal", function () {
	// do something…
	if (open_from_where == "quickview") {
	  $("body").addClass("modal-open");
	}
  });
  
/*
* WishList Popup code
*/
$('#myModalPopUpLogin').on('hidden.bs.modal', function () {
    // do something…
	if(open_from_where == "quickview"){
		$('body').addClass('modal-open');
	}
})

$('#tell-friend-modal').on('hidden.bs.modal', function () {
   // $('body').addClass('modal-open');
})


$("#close_forgotpassword").on('click', function(){
	$('#myModalPopUpLogin').modal('hide');	
})


function DisplayPopupBoxCheckout(action, ispopup) {
	$("#myModalPopUpLogin").html('');
	$("#page-spinner").show();
	var str = "action=" + action + "&isPopup=" + ispopup;

	$.ajax({
		type: "POST",
		url: site_url + "/photo_add",
		data: str,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}

	}).done(function (msg) {
		$("#page-spinner").hide();
		console.log(msg);
		$("#myModalPopUpLogin").html(msg);
		$('#myModalPopUpLogin').modal('show');
	});
}

function showWishCat() {
	var data = "isAction=wish_category";
	$.ajax({
		type: "POST",
		url: site_url + "/popup",
		data: data,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function (response) {
			$("#myModalPopUpLogin").html('');
			$("#myModalPopUpLogin").html(response);
		}
	})
}

/*
* Forgot Password code
*/
function ShowForgetPassword() {
	var str = "isAction=wish_forget" + "&isPopup=" + "Yes";
	$.ajax({
		type: "POST",
		url: site_url + "/popup",
		data: str,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function (response) {
			$("#myModalPopUpLogin").html('');
			$("#myModalPopUpLogin").html(response);
			// $(".svg_forgot_password").on("click", function(){
			// 	$('#myModalPopUpLogin').modal('hide');
			// 	window.stop();
			// })
		}
	})
}

/*
* Return Order Item
*/
$(document).on("click", '#returnOrderItem', function () {
	var order_detail_id = $(this).data("odid");
	var order_id = $(this).data("oid");
	var orderItemQuantity = $(this).data("quantity");
	var str = "order_detail_id=" + order_detail_id + "&order_id=" + order_id + "&orderItemQuantity=" + orderItemQuantity;
	$("#page-spinner").show();
	$.ajax({
		type: "POST",
		url: site_url + "/return_order_item",
		data: str,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}

	}).done(function (msg) {
		$("#page-spinner").hide();
		$("#return-order-item-modal").html(msg);
		$('#return-order-item-modal').modal('show');
	});
});