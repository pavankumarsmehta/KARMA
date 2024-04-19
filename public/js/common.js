let jqValidationGlobalOptions = {
	errorElement: 'label',
	onfocusout: false,
	invalidHandler: function(form, validator) {
		var errors = validator.numberOfInvalids();
		if (errors) {                    
			validator.errorList[0].element.focus();
		}
	}
};
//Newletter Bottom Start
$('#bottom_email').keypress(function( event ){
	if (event.keyCode === 13) {
		// Cancel the default action, if needed
		event.preventDefault();
		check_newsletter();
		return false;
	}
});
$('#currency_desk').off('change').on('change',function( event ){
	var get_currency_code = $(this).val();
	$.ajax({
		type:'POST',
		url:base_url_new+"/currency",
		data:"currency_code="+get_currency_code,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success:function(data){
			if(data=='true'){
				window.location.reload();
			}
		}, 
		error:function(err){
			console.log(err);
		},
	});
});
$('#currency_mob').off('change').on('change',function( event ){
	var get_currency_code = $(this).val();
	$.ajax({
		type:'POST',
		url:base_url_new+"/currency",
		data:"currency_code="+get_currency_code,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success:function(data){
			if(data=='true'){
				window.location.reload();
			}
		}, 
		error:function(err){
			console.log(err);
		},
	});
});

function check_newsletter()
{
	var news_mail = $("#bottom_email").val();
	
	if(news_mail == ''){
		$("#success_bottom_email").html('');
		$("#error_bottom_email").addClass("error");
		$("#error_bottom_email").html("Please enter email address.");
	}else{
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!regex.test(news_mail)) {
		   $("#success_bottom_email").html('');
		   $("#error_bottom_email").addClass("error");
		   $("#error_bottom_email").html("Please enter valid email address.");
		}else{
			$("#error_bottom_email").html("");
			$.ajax({
				type:'POST',
				url:base_url_new+"/newsletter",
				data:"newsletter_email="+news_mail,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success:function(data){
					$("#success_bottom_email").addClass("success");
					$('#bottom_email').val('');
					$("#success_bottom_email").html(data.msg);
				}, 
				error:function(err){
					console.log(err);
				},
			});
		}
	}
}
//Newletter Bottom End



$('.sb_toggle_right').click(function () {
	//$('.filter_wp').parent().removeClass('hidden-sm-down');
	$('html').addClass('sb-active sb-active-right');
	$('.overlay').remove();
	$('.cart_right_slid').after('<div class="overlay"></div>');
	$('.cart_right_slid').addClass('sb-slidebar sb-right sb-width-wide sb-style-overlay sb-active'); 
	$('.cart_right_slid').css({ "width": "320px", "margin-right": "-320px" });
	$('.cart_right_slid').css('display','block');
	
	var wid = $('.cart_right_slid').width();
	var properties = {};
	properties['right'] = wid;
	$('.cart_right_slid').stop().animate(properties, 150);
});
$('.sb_toggle_right_clearance').click(function () {
	//$('.filter_wp').parent().removeClass('hidden-sm-down');
	$('html').addClass('sb-active sb-active-right');
	$('.overlay').remove();
	$('.cart-right-slid').after('<div class="overlay"></div>');
	$('.cart-right-slid').addClass('sb-slidebar sb-right sb-width-wide sb-style-overlay sb-active'); 
	$('.cart-right-slid').css({ "width": "320px", "margin-right": "-320px" });
	$('.cart-right-slid').css('display','block');
	
	var wid = $('.cart-right-slid').width();
	var properties = {};
	properties['right'] = wid;
	$('.cart-right-slid').stop().animate(properties, 150);
});

/*$('.sb-toggle-right').click(function () {
	
	//$('.cart-right-slid').after('<div class="overlay"></div>');
	$('html').addClass('sb-active sb-active-right');
	$('.overlay').remove();
	$('.cart-right-slid').after('<div class="overlay"></div>');
	$('.cart-right-slid').addClass('sb-slidebar sb-right sb-width-wide sb-style-overlay sb-active'); 
	//$('.cart-right-slid').css({ "width": "320px", "margin-right": "-320px" , "right": "0px !important","transform":"translate(0px) !important"});
	$('.cart-right-slid').css( "width", "320px");
	$('.cart-right-slid').css( "margin-right", "-320px");
	$('.cart-right-slid').css( "right", "0px !important");
	$('.cart-right-slid').css( "transform","translate(0px) !important");
	$('.cart-right-slid').css('display','block');
	var wid = $('.cart-right-slid').width();
	var properties = {};
	properties['right'] = wid;
	$('.cart-right-slid').stop().animate(properties, 150);
});*/
$('.sb_cart_close, .overlay').on('touchend click', function (event) {
	$('html').removeClass('sb-active sb-active-right');
	$('.cart_right_slid').removeClass('sb-active');
	$('.cart_right_slid').removeAttr('style');
});
	
	
$(document).ready(function($){
	$('.imageload').hover(function()
	{
		var elem = $(this).find('img');
		for(var i=0;i<elem.length;i++)
		{
			//console.log(elem[i]);
			$(elem[i]).attr('src',$(elem[i]).attr('data-original'));
		}
	});
});	

$(function() {
	// Custom fadeIn Duration
	$('img.lazyload').loadScroll(3500);
});

(function($) {
    $.fn.loadScroll = function(duration) {
        var $window = $(window),
            images = this,
            inview,
            loaded;
        images.one('loadScroll', function() {
            if (this.getAttribute('data-src')) {
                this.setAttribute('src',
                this.getAttribute('data-src'));
                this.removeAttribute('data-src');
                if (duration) {
                    $(this).hide().fadeIn(duration).add('img').removeAttr('style');
                } else return false;
            }
        });
        $window.scroll(function() {
            inview = images.filter(function() {
                var a = $window.scrollTop(),
                    b = $window.height(),
                    c = $(this).offset().top,
                    d = $(this).height();
                return c + d >= a && c <= a + b;
            });
            loaded = inview.trigger('loadScroll');
            images = images.not(loaded);
        });
    };
})(jQuery);

function GetAccCart() {
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		url: site_url + '/cart_action',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data: {
			action: 'getcart'
		},
		success: function (data) {
			//console.log(data);
			// $(".cart_table").html(data.cart_details);
			// $("#order_summary").html(data.cart_summary);
			// $("#total_qty").html(data.total_qty+" item(s)");
			// $("#page-spinner").hide();
			//console.log(data);
			$(".cart_table").html(data.cart_details);
			$("#order_summary").html(data.cart_summary);
			$("#total_qty").html(data.TotalQty + " item(s)");
			$(".cart-item-count").text(data.TotalQty);
			if (data.cart_empty != '') {
				$(".checkout_left").html(data.cart_empty);
				$("#total_qty").hide();
				$(".checkout_title p").hide();
			}
			$("#page-spinner").hide();

		}
	});
}



$(document).on('click','.pc-toggle',function (){
	if ($(this).hasClass('show')) {
		$('.pc-inner').hide();
		$(this).removeClass('show');
		$(this).addClass('hide');
		$(this).text('Show Retailers');
	}
	else
	{
		$('.pc-inner').hide();
		$(this).next('.pc-inner').show();
		$(".pc-toggle").removeClass('show');
		$(this).removeClass('hide');
		$(this).addClass('show');
		$(this).text('Hide Retailers');
		
	}
});

$(function () {
	$('.sp-close, .overlay').on('touchend click', function (event) {
		var $right = '-'+$( '.sb-right' ).css("width");
		$('html').removeClass('sb-active sb-active-right');
		$('.cart_right_slid').removeClass('sb-active');
		$('.cart_right_slid').removeAttr('style');
		$('.cart_right_slid').css('right','0');
		$('.cart-right-slid').removeClass('sb-active');
		$('.cart-right-slid').removeAttr('style');
		$('.cart-right-slid').css('right',$right);
	});
});

$(document).on("click", ".overlay", function () {
	$(".sp-close").trigger("click");
});

