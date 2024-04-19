$('#mob_search').hover(function () {
    $(".mobhead_search").show();
}, function(){
    $(".mobhead_search").hide();
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
		
		
		
		var $leftmenu = $('.mm_mid');
        var adjustMenu = function () {
            if (w >= 992) {
                $($leftmenu).empty();
				
            }
            else {
                $($leftmenu).empty();
                $leftmenu.html($(".mm_desktop").html());
				$('.drilldown').drilldown();
            }
        }
		
		
		var footeraccordion = function() {
			if (w >= 992) {
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

$('.moreless-button').click(function() {
    $('.morelesstext').slideToggle();
    if ($('.moreless-button').text() == "Read More") {
        $(this).text("Read Less")
    } else {
        $(this).text("Read More")
    }
});

/////// My account toggle Start
$(document).ready(function() {
    $('.myacc-toggle').click(function() {
			$(this).toggleClass('active');
			$('.alu_inner').toggleClass("open");
    });
});

/////// My account toggle End

/*$(function() {
    $(document).on('click', '.cp-toggle', function() {
        $(this).next().toggleClass('active');
    });

    $('.cust-choose').on("change", function() {
        less.modifyVars({
            '@c3': $('input[name="color3"]').val(),
            '@c4': $('input[name="color4"]').val()
        });
    });
    less.refresh();
})*/
// owlCarousel slider

/* ---- featured-items slider ---- */
    jQuery(document).on('click', '[data-section="shop-by-featured-items"] .fi-category:not(.fi-active)', function(){
        jQuery(this).addClass('fi-active').siblings('.fi-category').removeClass('fi-active');
        let itemIndex = jQuery(this).index();

        jQuery('[data-section="shop-by-featured-items"] .fi-tab-content .fi-item-holder:eq('+itemIndex+')').addClass('fi-active').siblings('.fi-item-holder').removeClass('fi-active');
        jQuery('[data-section="shop-by-featured-items"] .view-all-links .fi-link:eq('+itemIndex+')').addClass('fi-active').siblings('.fi-link').removeClass('fi-active');
    });
/* ---- featured-items slider ---- */

/* ---- Input Password js ---- */
$(document).on('click', '.toggle-password', function() {
    $(this).toggleClass("active");
    var input = $(".password-input");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});
/* ---- Input Password js ---- */

/* ---- Sq. Ft. Calc js ---- */
function calculate_area()
{
    var total_feet = 0;
    for(i=1;i<5;i++)
    {
        var a = document.getElementById("section"+i+"_f_l").value;      
        var b = document.getElementById("section"+i+"_i_l").value;  
        var c = document.getElementById("section"+i+"_f_r").value;  
        var d = document.getElementById("section"+i+"_i_r").value;
        var add_extra = 0;
        var add_extra_1 = 0;
        var new_a = a*12;
        new_a = parseFloat(new_a) + parseFloat(b);
        new_a = new_a/12;
        var new_c = c*12;
        new_c= parseFloat(new_c) + parseFloat(d);
        new_c = new_c/12;
        total_feet = parseFloat(total_feet) + parseFloat(new_a*new_c)
        /*if(b%12 != b || b > 0)
        {
                var new_b = parseInt(b%12);
                a = parseFloat(a) + parseInt(b/12)
                document.getElementById("section"+i+"_f_l").value = a;
                document.getElementById("section"+i+"_i_l").value = new_b;
        }
        if(d%12 != d || d > 0)
        {
                var new_d = parseInt(d%12);
                c = parseFloat(c) + parseInt(d/12)
                document.getElementById("section"+i+"_f_r").value = c;
                document.getElementById("section"+i+"_i_r").value = new_d;
        }
        if((a > 0 ) || (c > 0))
        { 
            total_feet = total_feet + (parseFloat(a) * parseFloat(c)) + parseFloat(add_extra_1)  + parseFloat(add_extra);
        }*/
    }
    total_feet =parseInt(total_feet);
    document.getElementById('subtotal').innerHTML = total_feet;
    if(document.getElementById('waste_per').value == "" )
    {
        document.getElementById('waste_per').value = 0;
    }
    if(parseFloat(document.getElementById('waste_per').value) > parseFloat(25) )
    {
        document.getElementById('waste_per').value = 25;
    }
    var waste_per_total = ((parseFloat(total_feet) * parseFloat(document.getElementById('waste_per').value))/100);
    document.getElementById('waste_per_total_value').innerHTML = waste_per_total;
    document.getElementById('grandTotalDisplay').innerHTML = parseFloat(parseFloat(waste_per_total) + parseFloat(total_feet)).toFixed(2);  
}

// function ins_to_product()
// {
//     document.getElementById('calc_quantity').value = document.getElementById('grandTotalDisplay').innerHTML;
//     call_coverage();
//     $(".btn-close").trigger("click");
// }
/* ---- Sq. Ft. Calc js ---- */