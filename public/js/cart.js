/*Cart Page Slider*/
$(document).ready(function(){
$('.customers_products').owlCarousel({
	    loop:false,
		items:3,
		navRewind:false,
	    margin:15,
	    responsiveClass:false,
		nav:true,
		dots:false,
		navText : ['<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg>','<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg>'],
	    responsive:{ 
		 0:{items:2, margin:5}, 
		 805:{items:3},
		 1200:{items:4}
		 }
 	});
	});
/*Cart Page tabs*/
function ShowTab(num) {
    $('.cart-tabs .hd1 a, .cart-tabs .hd2 a').removeClass('active');
    $('.anchor-' + num).addClass('active');

    $('.cart-tabs .content').removeClass('active');
    $('#tab-' + num).addClass('active');
}
$(window).load(function() {
    ShowTab(1);

});





