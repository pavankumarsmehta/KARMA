var token = $('meta[name="csrf-token"]').attr('content');
$(document).on('click','.acc_cart_detail',function (){
	var product_id = $(this).attr('data-productid');
	checkCartExists(product_id,this);
});

function checkCartExists(product_id,obj){
	$.ajax({
		type: 'POST',
		async: false,
		url: site_url + '/cart_action',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data: {
			product_id : product_id,
			action : 'check_product_exists'
		},
		success: function (data) {
			console.log(data);
			//if(data.cart_id == ""){
			if(data.check_exists == false){
				//alert("Not Exists");
				if(confirm('Are you sure you want to add this item in cart?')){
					$("#page-spinner").show();
					var prod_qty = 1;
					var btn_from = 'cart_accessories';
					AddToCart(product_id, prod_qty, btn_from);
					$(obj).addClass("active").removeAttr('title').attr("title","Remove from Cart");
					$("#prodid_list").val($("#prodid_list").val()+","+product_id);
					$("#page-spinner").hide();
					$("#quick-view").modal('hide');
				}
			} else {
				if(confirm('Are you sure you want to remove this item from cart?')){
					$("#page-spinner").show();
					$(obj).removeClass("active").removeAttr('title').attr("title","Add to Cart");
					//removeFromCart(prod_index_cartid);
					$("#page-spinner").show();
					var token = $('meta[name="csrf-token"]').attr('content');
					var cart_id = data.cart_id; //$(this).attr('data-index');
					$.ajax({
						type: 'POST',
						url: site_url + '/cart_action',
						headers: {
							'X-CSRF-TOKEN': token
						},
						data: {
							cart_id : cart_id,
							action : 'remove_sidepanel'
						},
						success: function (data) {
							$("#shopcart").html(data.ShoppingCart);
							if(data.TotalItemInCart == 0){
								$(".cart-item-count").remove();
							} else {
								$(".cart-item-count").text(data.TotalItemInCart);
							}
							removeFromProdList(product_id);
							$("#page-spinner").hide();
							GetAccCart();
						}
					});
				}
			}

		}
	});
}

function removeFromProdList(product_id){
	var prod_list = $("#prodid_list").val();
	var prod_array = new Array();
	var prod_list_arr = prod_list.split(",");
	for(var p = 0; p < prod_list_arr.length; p++){
		if(prod_list_arr[p] != product_id ){
			prod_array.push(prod_list_arr[p]);
		}
	}
	$("#prodid_list").val(prod_array.join(","));
}

$(document).on('click', ".addtocart", function () {
	var productID = $(this).attr('data-product');
	var prodqty = $("#prodqty").val();
	if(productID =='' || productID <= 0)
	{
		alert("Oops! it seems invalid Product"); return false;
	}
	else if(prodqty == 0 || prodqty < 0)
	{
		alert("Please add valid product quantity"); return false;
	}
	else
	{
		AddToCart(productID, prodqty);
	}
	if($("#quick-view").length  > 0){
		if($("#quick-view").find('[aria-hidden="false"]').length > 0){
			$("#quick-view").modal("hide");
		}
	}
});

function AddToCart(productID, prodqty, btnFrom = '') {
	$.ajax({
		type: 'POST',
		url: site_url + '/cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			products_id: productID,
			prodqty: prodqty,
			action: 'insert',
		},
		success: function (data) {
			GetCart();
			$('#cart-open').animate({
				//right: '0px'
			});

			$('.overlay').remove();
			$('.cart-right-slid').addClass('sb-slidebar sb-right sb-active');
			$('.cart-right-slid').after('<div class="overlay"></div>');
			$('.cart-right-slid').css('transform','translate(-420px)');

			//$('.cart-right-slid').css('display','block');
			$('html').addClass('sb-active');
			$('html').addClass('sb-active-right');

			if($(".cart-item-count").length > 0){
				$(".cart-item-count").text(parseInt($(".cart-item-count").text()) + prodqty);
			} else {
				var htm = '<span class="cart-item-count">'+prodqty+'</span>';
				$(".svg-clearancs-cart").before(htm);
			}

		}
	});
}

$(document).on('click', ".buynow", function () {
	var productID = $(this).attr('data-product');
	var page = $(this).attr('data-page');
	if(page == "product_detail"){
		var prodqty = $("#prodqty").val();
		//var price = "";
	}else{
		var prodqty = '1';
		//var price = $(this).attr('data-price');
	}

	if(productID =='' || productID <= 0)
	{
		alert("Oops! it seems invalid Product"); return false;
	}
	else if(prodqty == 0 || prodqty < 0)
	{
		alert("Please add valid product quantity"); return false;
	}
	else
	{
		buynow(productID, prodqty, page);
	}
});

function buynow(productID, prodqty, btnFrom = '', page) {
	$('.buy_now_error').addClass('hidden-lg-down');
	$.ajax({
		type: 'POST',
		url: site_url + '/cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			products_id: productID,
			prodqty: prodqty,
			action: 'insert',
			page: page,
			//price: price,
		},
		success: function (data) {
			if (data.hasOwnProperty('CartErrors') && data.CartErrors.length > 0) {
			    var errors = data.CartErrors;
			    errors.forEach(function(error) {
					$('.buy_now_error').removeClass('hidden-lg-down');
					$('.buy_now_error').html(error);
			    });
			} else {
				GetCart();
				if($(".cart-item-count").length > 0){
					$(".cart-item-count").text(parseInt($(".cart-item-count").text()) + prodqty);
				} else {
					var htm = '<span class="cart-item-count">'+prodqty+'</span>';
					$(".svg-clearancs-cart").before(htm);
				}
				console.log(data);
				var url = site_url + '/checkout';
				window.location.href = url;
			}

		}
	});
}
function GetCart() {
	$.ajax({
		type: 'POST',
		url: site_url + '/getcart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		success: function (data) {
			$("#shopcart").html(data.ShoppingCart);
			var TotalItemInCart = data.TotalItemInCart;
			if (TotalItemInCart == 0){
				TotalItemInCart = '';
				$('#shopcart .empty-items').show();
			}
			if($(".cart-item-count").length <= 0  && TotalItemInCart != 0){
				$(".sb-bag-js").prepend('<span id="cart-counter" class="cart-item-count">'+TotalItemInCart+'</span>');
			}
			$(".cart-item-count").html(TotalItemInCart);
		}
	});
}

$(document).on('click', '.sb-close', function () {
	sb_close_cart();
});


$(document).on('click', '#clear-bag', function () {
	ClearBag();
});

function ClearBag() {
	if(confirm('Are you sure you want to remove all items?'))
	{
		$.ajax({
			type: 'POST',
			url: site_url + '/cart',
			headers: {
				'X-CSRF-TOKEN': token
			},
			datatype: 'JSON',
			data: {
				action: 'clear_bag',
			},
			success: function (data) {
				window.location.reload();
			}
		});
	}
}

// Remove Item from cart
$(document).on('click','.itemremove',function (){
	if(confirm('Are you sure you want to remove the item?')){
		$("#page-spinner").show();
		var token = $('meta[name="csrf-token"]').attr('content');
		var cart_id = $(this).attr('data-index');
		var result = '';
		$.ajax({
			type: 'POST',
			url: site_url + '/cart_action',
			headers: {
				'X-CSRF-TOKEN': token
			},
			data: {
				cart_id : cart_id,
				action : 'remove_sidepanel'
			},
			success: function (data) {
				console.log(data);
				//return false;
				$("#shopcart").html(data.ShoppingCart);
				if(data.TotalItemInCart == 0){
					$(".cart-item-count").remove();
				} else {
					$(".cart-item-count").text(data.TotalItemInCart);
				}
				//$("#order_summary").html(data.cart_summary);
				//$("#total_qty").html(data.total_qty+" item(s)");
				$("#page-spinner").hide();
				if(data.page == "checkout"){
					window.location.reload();
				}
				GetAccCart();
				GetCart();
			}
		});
	}
});

function sb_close_cart(){
	$('.cart-right-slid').removeClass('sb-active');
	$('.cart_right_slid').removeClass('sb-active');
	$('.cart_right_slid').css('display','none');

	$('html').removeClass('sb-active ');
	$('html').removeClass('sb-active-right');
	return false;

}

function show_user_register_popup()
{
	//if($('#is_customer_login').val()==0)
	//{
		$("#myModalPopUpLogin").html('');
		$("#page-spinner").show();
		var email_val = '';
		if($('#bl_sh_email').val() != "")
		{
			email_val = $('#bl_sh_email').val();
		}
		var str = "isAction=register_popup&action=signup&checkoutVal=Yes&email_val="+email_val;
		$.ajax({
			type: "POST",
			url: site_url + "/popup",
			data: str,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}

		}).done(function (msg) 
		{
			$("#page-spinner").hide();		
			$("#myModalPopUpLogin").html(msg);
			$('#myModalPopUpLogin').modal('show');
		});
	//}	
}




function valid_user_sign_up() {
	$('#frmCheckoutRegister').data('validator', null);
	$("#frmCheckoutRegister").unbind('validate');

	$('#frmCheckoutRegister').validate({
		ignore: ":hidden:not(#keycode)",
		rules:
		{
			firstname: { required: true },
			lastname: { required: true },
			phone: { required: true, minlength: 10, maxlength: 10 },
			emailreg: { required: true, email: true },
			cpassword: { required: true, minlength: 6 }
		},
		messages:
		{
			firstname: { required: "Please enter first name" },
			lastname: { required: "Please enter last name" },
			phone: { required: "Please enter phone number", minlength: "Please enter minimum 10 character for phone number.", maxlength: "Please enter maximum 10 character for phone number." },
			emailreg: { required: "Please enter email address", emailreg: "Please enter valid email address" },
			cpassword: { required: "Please enter password", minlength: "Please enter minimum 6 character for password." }
		},
		onsubmit: false
	});
	if (!$("#frmCheckoutRegister").valid()) {
		return false;
	}
	check_user_sign_up();
}

function check_user_sign_up() {
	$("#signup_error").html('');

	var email = '';
	var firstname = '';
	var lastname = '';
	var phone = '';
	var frmObj = document.frmCheckoutRegister;

	var firstname = frmObj.firstname.value;
	var lastname = frmObj.lastname.value;
	var phone = frmObj.phone.value;
	var email = frmObj.emailreg.value;
	var cpassword = frmObj.cpassword.value;

	var URL = site_url + '/checkout-usersignup';

	var STR_POST_VAR = '';
	var STR_POST_VAR = 'email=' + email;
	var STR_POST_VAR = STR_POST_VAR + '&firstname=' + firstname + '&lastname=' + lastname + '&phone=' + phone + '&password=' + cpassword;
	var STR_POST_VAR = STR_POST_VAR + '&_token=' + $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		url: URL,
		data: STR_POST_VAR,
		cache: false,
		beforeSend: function () { },
		success: function (resultData) {
			if (resultData.trim() == 'yes') {
				if (parseInt($("#is_bt_express_checkout").val()) == 1)
					window.location.href = site_url + '/checkout?is_bt_express_checkout=1';
				else
					window.location.href = site_url + '/checkout';
			}
			else if (resultData.trim() == 'exist')
				$("#signup_error").html('There is already account created with enterd email id.');
			else
				$("#signup_error").html('Sorry! Your Registration cannot be completed. Please try again.');
		}
	});
}