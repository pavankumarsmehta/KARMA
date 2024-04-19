 	$(document).ready(function () {
        $('.dealday-slider').removeClass('fxheight');
        $('.beautyquizzes').removeClass('fxheight');
		
    });


$('.dealday-slider').slick({
		infinite: true,
		pauseOnFocus: false,
    pauseOnHover: false,
    pauseOnDotsHover: false,
		slidesToShow:5,
		slidesToScroll:1,
		arrows: true,
		prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
	  nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
		centerMode: true,
		centerPadding: '0px',
    asNavFor: '.dealday_detail_slid',
		responsive: [
			{ breakpoint: 992, settings: { slidesToShow: 3,slidesToScroll:1 } }		]
	});
  $('.dealday_detail_slid').slick({
    infinite: true,
    pauseOnFocus: false,
    pauseOnHover: false,
    pauseOnDotsHover: false,
		slidesToShow:1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
	adaptiveHeight: true,
    asNavFor: '.dealday-slider',
    //focusOnSelect: true    
  });
  /*$(function(){
    const slider = $(".dealday-slider");
    //slider;
    slider.on("wheel", function (e){
        e.preventDefault();
        if (e.originalEvent.deltaY < 0){
            $(this).slick("slickNext");
        } else {
            $(this).slick("slickPrev");
        }
    });
});*/

$(window).on("load", function () {
	
	$('.dealday-slider').removeClass('fxheight');
	 $('.beautyquizzes').removeClass('fxheight');
	
	//////////////Home slider
	$('#hero-slider').slick({
		autoplay: false,
		autoplaySpeed:1000,
		dots: true,
		fade: true,
		infinite: false,
		arrows:false,
		slidesToShow: 1,
		adaptiveHeight: true,
		responsive: [
			{ breakpoint: 768, 
				settings: { 
					dots: false,
					arrows:true,
					prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
					nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
				} 
			}
		]
	});

	

	/////Our Happy Customers
	$(".happyman-slick").slick({
		dots: false,
		infinite: false,
		speed: 300,
		slidesToShow:3,
		slidesToScroll:1,
		//arrows:false,
		prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
		nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
		responsive: [
			{ breakpoint: 1200,
				settings: { slidesToShow: 2,slidesToScroll:1 } 
			},
			{ breakpoint: 575, 
				settings: { slidesToShow: 1,slidesToScroll:1 } 
			}
		]
	});

});


function ShowTab(num) {
    $('.popularbrands-tabs a').removeClass('active');
    $('.anchor-' + num).addClass('active');

    $('.popularbrands-content .inner').removeClass('active');
    $('#tab-' + num).addClass('active');
}
/*$(window).load(function() {
    ShowTab(1);

});*/  


//Deals Display Code Start
var is_deal_loaded=false;
var load_get_lazyload_section_deal = function() { 
	var URL = base_url_new+'/home/Deals';
	var STR_POST_VAR="sectionType=hdeals";
	$.ajax({
		type: 'POST',
		url: URL,
		dataType: "html",
		data: STR_POST_VAR,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		cache: false,
		async: false,
		beforeSend: function()
		{
			$("#hdeals").append("<div class='container'><div class='row row5'><img src='"+base_url_new+"/images/loader.gif'></div></div>");
			
		},
		success: (function(data, status)
		{
			data=JSON.parse(data);
			if(data.success){
				//alert(112221);
				$("#finalhide").hide(); 
				$("#hdeals").html(data.Deals); 
				is_deal_loaded=true;
			}
			else{
				$("#hdeals").html(''); 
			}
		})
	})
}
//Deals Display Code End

//About Display Start
var is_about_loaded=false;
var load_get_lazyload_section_about = function() { 
	var URL = base_url_new+'/home/AboutUs';
	var STR_POST_VAR="sectionType=haboutus";
	$.ajax({
		type: 'POST',
		url: URL,
		dataType: "html",
		data: STR_POST_VAR,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		cache: false,
		async: false,
		beforeSend: function()
		{
			$("#haboutus").append("<div class='container'><div class='row row5'><img src='"+base_url_new+"/images/loader.gif'></div></div>");
			
		},
		success: (function(data, status)
		{
			data=JSON.parse(data);
			if(data.success){
				
				$("#finalhide").hide(); 
				$("#haboutus").html(data.AboutUS); 
				is_about_loaded=true;
			}
			else{
				$("#haboutus").html(''); 
			}
		})
	})
}
//About Display End


const isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

jQuery.fn.extend({
    isInViewport: function () 
	{
		var elementTop = $(this).offset().top;
		var elementBottom = elementTop + $(this).outerHeight();
		var viewportTop = $(window).scrollTop();
		var viewportBottom = viewportTop + $(window).height() + 450;
		return elementBottom > viewportTop && elementTop < viewportBottom;
	}
});

$(document).ready(function (){ 
	$(window).bind('scroll', bindScroll);
});

function bindScroll()
{
	/*if($('#hdeals').isInViewport() && is_deal_loaded==false)
    { 
        load_get_lazyload_section_deal(); 
	}*/
	//if($('#haboutus').isInViewport() && is_about_loaded==false && !isMobile.any())
	if($('#haboutus').isInViewport() && is_about_loaded==false)
    { 
        load_get_lazyload_section_about(); 
    }
	/*if($('#hseasons').isInViewport() && is_season_loaded==false)
    { 
        load_get_lazyload_section_season(); 
	}
	if($('#hcategory').isInViewport() && is_category_loaded==false)
    { 
        load_get_lazyload_section_category(); 
	}
	if($('#hbrand').isInViewport() && is_brand_loaded==false)
    { 
        load_get_lazyload_section_brand(); 
	}
	if($('#hnewarrival').isInViewport() && is_newarrival_loaded==false)
    { 
        load_get_lazyload_section_newarrival(); 
	}
	*/	
}
$(document).ready(function() {
		
		/////slick-scroll
		$(document).ready(function() {
			var $slider = $('.slick-scroll');
			var $progressBar = $('.slick-progress');
			var $progressBarLabel = $( '.slick-label' );
			
			$slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {   
				var calc = ( (nextSlide) / (slick.slideCount-1) ) * 100;
				
				$progressBar
					.css('background-size', calc + '% 100%')
					.attr('aria-valuenow', calc );
				
				$progressBarLabel.text( calc + '% completed' );
			});
			// $slider.slick({
			// 	speed: 300,
			// 	infinite: false,
			// 	slidesToShow:4,
			// 	slidesToScroll:1,
			// 	dots: false,
			// 	arrows:false,
			// 	responsive: [
			// 		{breakpoint: 992, settings: { slidesToShow: 3,slidesToScroll:1 } },
			// 		{breakpoint: 768, settings: { slidesToShow: 2,slidesToScroll:1 } }
			// 		]
			// });  
		});

		//////Beauty Tips
		$('.slick-beauty').slick({
			dots: false,
			infinite: false,
			speed: 300,
			slidesToShow:4,
			slidesToScroll:1,
			prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
	  	    nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
			responsive: [
				{ breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll:1 } },
				{ breakpoint: 992, settings: { slidesToShow: 2, slidesToScroll:1 } },
				{ breakpoint: 576, settings: { slidesToShow: 2, slidesToScroll:1 } }
			]
		});
	
		/////HBASales on Instagram
		$(".instagramowl").slick({
			dots: false,
			infinite: false,
			speed: 300,
			slidesToShow:4,
			slidesToScroll:1,
			//arrows:false,
			prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
			nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
			responsive: [
				{ breakpoint: 992, 
					settings: { slidesToShow: 3,slidesToScroll:1 } 
				},
				{ breakpoint: 768, 
					settings: { slidesToShow: 2,slidesToScroll:1 } 
				}
			]
		});

		//Category & Barand page slider
		$('.slick-brand').slick({
			dots: false,
			infinite: false,
			speed: 300,
			slidesToShow:7,
			slidesToScroll:1,
			prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
	  	nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
			responsive: [
				{ breakpoint: 1200, settings: { slidesToShow: 5, slidesToScroll:1 } },
				{ breakpoint: 992, settings: { slidesToShow: 4, slidesToScroll:1 } },
				{ breakpoint: 768, settings: { slidesToShow: 3, slidesToScroll:1 } },
				{ breakpoint: 576, settings: { slidesToShow: 2, slidesToScroll:1 } }
			]
		});
		var deal_end_date = $('#deal_end_date').val();
	var deal_start_date = $('#deal_start_date').val();
	if(deal_end_date != '')
	{
		var $clock = $('#counter_1'),
		eventTime = moment(deal_end_date, 'DD-MM-YYYY HH:mm:ss').unix(),
		currentTime = moment(deal_start_date, 'DD-MM-YYYY HH:mm:ss').unix(),
		diffTime = eventTime - currentTime,
		duration = moment.duration(diffTime * 1000, 'milliseconds'),
		interval = 1000;
		if(diffTime > 0) {
			/*var $d = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$h = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$m = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$s = $('<i style="font-style: normal;"></i>').appendTo($clock);
			*/
			setInterval(function(){

				duration = moment.duration(duration.asMilliseconds() - interval, 'milliseconds');
				var d = moment.duration(duration).days(),
					h = moment.duration(duration).hours(),
					m = moment.duration(duration).minutes(),
					s = moment.duration(duration).seconds();

				d = $.trim(d).length === 1 ? '0' + d : d;
				h = $.trim(h).length === 1 ? '0' + h : h;
				m = $.trim(m).length === 1 ? '0' + m : m;
				s = $.trim(s).length === 1 ? '0' + s : s;

				// show how many hours, minutes and seconds are left
				
				var clockdata = '';
				// clockdata+='<li><span class="count" id="days">'+d+'</span><span class="count_name">Days</span></li>';
				// clockdata+='<li><span class="count" id="hrs">'+h+'</span><span class="count_name">Hours</span></li>';
				// clockdata+='<li><span class="count" id="min">'+m+'</span><span class="count_name">Mins</span></li>';
				// clockdata+='<li><span class="count" id="sec">'+s+'</span><span class="count_name">Secs</span></li>';
				clockdata+=''+d+' : '+h+' : '+m+' : '+s+'';
				$clock.html(clockdata);
				//$d.html('<span class="countdown-section"><span class="countdown-amount" id="days">'+d+'</span><span class="countdown-period">Days</span></span>');
				//$h.html('<span class="countdown-section"><span class="countdown-amount" id="hrs">'+h+'</span><span class="countdown-period">Hours</span></span>');
				//$m.html('<span class="countdown-section"><span class="countdown-amount" id="min">'+m+'</span><span class="countdown-period">Mins</span></span>');
				//$s.html('<span class="countdown-section"><span class="countdown-amount" id="sec">'+s+'</span><span class="countdown-period">Secs</span></span>');

			}, interval);
		}
	}

});
$(document).ready(function() {
	var $this = $(this).find('.slick-initialized');
        $this.addClass('finally-loaded');
});