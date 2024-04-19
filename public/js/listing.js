$(function(){
	 var my_currency_symbol = '$';
	$(".filter-options-title").on('click', function() {
		$(this).parent().find(".filter-options-content").slideToggle();
		$(this).parent().toggleClass('active');
	   return false;
	});
	$('.filter_mob_btn').click(function(){
		$('.filter_wp').parent().removeClass('hidden-sm-down');
		$( 'html' ).addClass( 'sb-active sb-active-left' );
		$('.overlay').remove();
		$('.filter_wp').after('<div class="overlay"></div>');
		$('.filter-title').css({"padding": "17px"});
		$('.filter_wp').addClass('sb-slidebar sb-left sb-width-custom sb-style-overlay sb-active');
		$('.filter_wp').css({"width": "300px","margin-left": "-300px"});
		var wid = $('.filter_wp').width();
		var properties = {};
				properties['left'] = wid;
				$('.filter_wp').stop().animate( properties, 150 );
	});
	$( '.filter-close, .overlay' ).on( 'touchend click', function ( event ) {
		$( 'html' ).removeClass( 'sb-active sb-active-left' );
		$('.filter_wp').removeClass( 'sb-active' );
		$('.filter_wp').removeAttr( 'style' );
	});

});

$('#living_room_gs, #bb_box_rugs').owlCarousel({
    loop:false,
	navRewind:false,
    margin:4,
    responsiveClass:false,
	nav:true,
	dots:false,
	navText : ['<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg>','<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg>'],
    responsive:{ 
	 0:{items:1}, 
	 480:{items:2},
	 768:{items:2,margin:8,},
	 992:{items:3,margin:8,}, 
	 1200:{items:4,margin:8,},
	 1499:{items:4,margin:12,}
	 }
 })
  $(".color3d-slider").owlCarousel({
    loop:true,
    items:5,
    nav:true,
    dots:false,
    center:true,
     navText : ['<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg>','<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg>'],
      responsive:{ 0:{ items:3}, 768:{ items:7}, 992:{ items:7}},
    });
 $('#frc_styles').owlCarousel({
    loop:false,
	navRewind:false,
    margin:12,
    responsiveClass:false,
	nav:true,
	dots:false,
	navText : ['<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg>','<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg>'],
    responsive:{ 
	 0:{items:2, margin:6}, 
	 576:{items:3},
	 768:{items:4},
	 992:{items:4}, 
	 1200:{items:5}
	 }
 })