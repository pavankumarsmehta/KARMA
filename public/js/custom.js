$('#mob_search').hover(function () {
    $(".header_search_mob").show();
}, function(){
    $(".header_search_mob").hide();
});

$(document).ready(function() {
    function sticky_relocate() {
        var window_top = $(window).scrollTop();
        var div_top = $('#header-sticky-anchor').offset().top;
        if (window_top > div_top) {
            $('#header-sticky').addClass('header-fixed');
            $('#header-sticky-anchor').height($('#header-sticky').outerHeight());
        } else {
            $('#header-sticky').removeClass('header-fixed');
            $('#header-sticky-anchor').height(0);
        }
    }
    $(function() {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });
});

(function($) {	  
    //$('.mm_nav').slyder();
    $(document).ready(function() {
        $.slidebars(); 
    });
})(jQuery);

/////// Footer Go Top Arrow
	$(document).ready(function () {
        // Show or hide the sticky footer button
        $(window).scroll(function () {
            if ($(this).scrollTop() > 200) {
                $('.go-top').fadeIn(200);
            }
            else {
                $('.go-top').fadeOut(200);
            }
        });
        // Animate the scroll to top
        $('.go-top').click(function (event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 300);
        })
    });
/////// Footer Go Top Arrow End

/////// Footer Toggle Section
	$(function() {              
		var w = $(document).width();
		$(function() {
			footeraccordion();
			adjustMenu();
		});
		$(window).bind('resize orientationchange', function() {
			w = document.body.clientWidth;
			footeraccordion();
			adjustMenu();
		});
		
		
		
		/*var $leftmenu = $('.mm_mid');
        var adjustMenu = function () {
            if (w >= 1200) {
                $($leftmenu).empty();
				
            }
            else {
                $($leftmenu).empty();
                $leftmenu.html($(".mm_desktop").html());
				$('.drilldown').drilldown();
            }
        } */
        
		
		
		var footeraccordion = function() {
			if (w >= 768) {
				$('.ft_acd h5').unbind('click');
				$('.ft_acd .ft_acd_con').show();
				$('.ft_acd h5').removeClass('active');
				$('.drilldown-rarrow a').unbind('click');
			} else {
				$('.ft_acd .ft_acd_con').hide();
				$('.ft_acd h5').removeClass('active');
				$('.drilldown-rarrow a').bind('click');
				$('.ft_acd h5').unbind('click').bind('click', function(event) {
					if ($(this).next("ul.ft_acd_con").css("display") == "none") {
						$('.ft_acd .ft_acd_con').slideUp();
						$('.ft_acd h5').removeClass('active');
						$(this).addClass('active');
						$(this).next("ul.ft_acd_con").slideDown();
					} else {
						$(this).next("ul.ft_acd_con").slideUp();
						$(this).removeClass('active');
					}
				});
			}
		};		
	});
/////// Footer Toggle Section



/////// My account toggle Start
$(document).ready(function() {
    $('.myacc-toggle').click(function() {
			$(this).toggleClass('active');
			$('.alu_inner').toggleClass("open");
    });
});