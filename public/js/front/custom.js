/*$('#mob_search').hover(function () {
    $(".mobhead_search").show();
}, function () {
    $(".mobhead_search").hide();
});*/

$("#mob_search").click(function(){
  $(".header_search_mob").toggle();
  $("body").toggleClass("searchCls");
});

$(document).ready(function () {
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
    $(function () {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });
});
/*
 $(".cart-link").click(function() {
        $('#cart-open').animate({
            right: '0px'
        });
        $('body').toggleClass('slide-open');

    });
    $(".sidepanel > .close").click(function() {
        $('#cart-open').animate({
            right: '-320px'
        });
        $('body').removeClass('slide-open');

    }); */



(function ($) {
    //$('.mm_nav').slyder();
    $(document).ready(function () {
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
$(function () {
    var w = $(document).width();
    $(function () {
        footeraccordion();
        //adjustMenu();
    });
    $(window).bind('orientationchange', function () {
        w = document.body.clientWidth;
        footeraccordion();
        //adjustMenu();
    });



    /*var $leftmenu = $('.mm_mid');
    var adjustMenu = function () {
        if (w >= 992) {
            $($leftmenu).empty();

        }
        else {
            $($leftmenu).empty();
            $leftmenu.html($(".mm_desktop").html());
            $('.drilldown').drilldown();
        }
    } */
    $('.drilldown').drilldown();


    var footeraccordion = function () {
        if (w >= 768) {
            $('.ft_acd .ft_acd_hd').unbind('click');
            $('.ft_acd .ft_acd_con').show();
            $('.ft_acd .ft_acd_hd').removeClass('active');
            $('.drilldown-rarrow a').unbind('click');
        } else {
            $('.ft_acd .ft_acd_con').hide();
            $('.ft_acd .ft_acd_hd').removeClass('active');
            $('.drilldown-rarrow a').bind('click');
            $('.ft_acd .ft_acd_hd').unbind('click').bind('click', function (event) {
                if ($(this).next("ul.ft_acd_con").css("display") == "none") {
                    $('.ft_acd .ft_acd_con').slideUp();
                    $('.ft_acd .ft_acd_hd').removeClass('active');
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

$('.moreless-button').click(function () {
    $('.morelesstext').slideToggle();
    if ($('.moreless-button').text() == "Read More") {
        $(this).text("Read Less")
    } else {
        $(this).text("Read More")
    }
});

/////// My account toggle Start
$(document).ready(function () {
    $('.myacc-toggle').click(function () {
        $(this).toggleClass('active');
        $('.alu_inner').toggleClass("open");
    });
});

/////// My account toggle End
/*
$(function() {
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
jQuery(document).on('click', '[data-section="shop-by-featured-items"] .fi-category:not(.fi-active)', function () {
    jQuery(this).addClass('fi-active').siblings('.fi-category').removeClass('fi-active');
    let itemIndex = jQuery(this).index();

    jQuery('[data-section="shop-by-featured-items"] .fi-tab-content .fi-item-holder:eq(' + itemIndex + ')').addClass('fi-active').siblings('.fi-item-holder').removeClass('fi-active');
    jQuery('[data-section="shop-by-featured-items"] .view-all-links .fi-link:eq(' + itemIndex + ')').addClass('fi-active').siblings('.fi-link').removeClass('fi-active');
});
/* ---- featured-items slider ---- */

/* ---- Input Password js ---- */
$(document).on('click', '.toggle-password', function () {
    $(this).toggleClass("active");
    var input = $(".password-input");
    input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
});
/* ---- Input Password js ---- */

/* ---- Sq. Ft. Calc js ---- */
function calculate_area() {
    var total_feet = 0;
    for (i = 1; i < 5; i++) {
        var a = document.getElementById("section" + i + "_f_l").value;
        var b = document.getElementById("section" + i + "_i_l").value;
        var c = document.getElementById("section" + i + "_f_r").value;
        var d = document.getElementById("section" + i + "_i_r").value;
        var add_extra = 0;
        var add_extra_1 = 0;
        var new_a = a * 12;
        new_a = parseFloat(new_a) + parseFloat(b);
        new_a = new_a / 12;
        var new_c = c * 12;
        new_c = parseFloat(new_c) + parseFloat(d);
        new_c = new_c / 12;
        total_feet = parseFloat(total_feet) + parseFloat(new_a * new_c)
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
    total_feet = parseInt(total_feet);
    document.getElementById('subtotal').innerHTML = total_feet;
    if (document.getElementById('waste_per').value == "") {
        document.getElementById('waste_per').value = 0;
    }
    if (parseFloat(document.getElementById('waste_per').value) > parseFloat(25)) {
        document.getElementById('waste_per').value = 25;
    }
    var waste_per_total = ((parseFloat(total_feet) * parseFloat(document.getElementById('waste_per').value)) / 100);
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

/* Dev JS starts here */

function submitform(frmname) {
    $('#' + frmname).submit();
}

function resetform(frmname) {
    $('#' + frmname)[0].reset();
}
/* Dev JS ends here */



$(document).ready(function () {
    $('.page-qty .btn-number').click(function (e) {
        e.preventDefault();
        fieldName = $(this).attr('data-field');
        type = $(this).attr('data-type');
        var input = $(".page-qty input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type == 'minus') {
                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }
            } else if (type == 'plus') {
                input.val(currentVal + 1).change();
            }
        } else {
            input.val(0);
        }
    });

    $('.sticky-qty .btn-number').click(function (e) {
        e.preventDefault();
        fieldName = $(this).attr('data-field');
        type = $(this).attr('data-type');
        var input = $(".sticky-qty input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type == 'minus') {
                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }
            } else if (type == 'plus') {
                input.val(currentVal + 1).change();
            }
        } else {
            input.val(0);
        }
    });

    $('.input-number').focusin(function () {
        $(this).data('oldValue', $(this).val());
    });
    $('.input-number').change(function () {
        minValue = parseInt($(this).attr('min'));
        maxValue = parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());
        name = $(this).attr('name');
        if (valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
    });
    $(".input-number").keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});

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

    $('.bname-move-alpha-desk-js').off('click').on('click',function(){
        var bnameMoveAlphaId = $(this).attr('data-bname-move-alpha-id');
        $('.menu-brand-scrollbar.topbrands_scoller_desk_js').animate({
            scrollTop: $(".topbrands_scoller_desk_js li."+bnameMoveAlphaId+"_top").position().top - $('.topbrands_scoller_desk_js li:first').position().top
        }, 1000);
    });
    $('.bname-move-alpha-mob-js').off('click').on('click',function(){

        var bnameMoveAlphaId = $(this).attr('data-bname-mob-move-alpha-id');
        
        $('.menu-brand-scrollbar.topbrands_scoller_mob_js').animate({
            scrollTop: $(".topbrands_scoller_mob_js li."+bnameMoveAlphaId+"_top").position().top - $('.topbrands_scoller_mob_js li:first').position().top
        }, 1000);
    });
	if($slider.length > 0){
        $slider.slick({
            speed: 300,
            infinite: true,
            slidesToShow:4,
            slidesToScroll:1,
            dots: false,
            arrows:true,
            prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
		    nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
            responsive: [
                {breakpoint: 992, settings: { slidesToShow: 3,slidesToScroll:1 } },
                {breakpoint: 768, settings: { slidesToShow: 2,slidesToScroll:1 } }
                ]
        });  
    }
});

function search_branc_desk(val) {
   var searchText = val;
    
        searchText = searchText.toUpperCase();
       var test =  $('#topbrands_scoller_desk > li').each(function(){
            var currentLiText = $(this).text().toUpperCase(),
            showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            $(this).toggle(showCurrentLi);
        });  

    }
    function search_branc_mob(val) {
   var searchText = val;
    
        searchText = searchText.toUpperCase();
       var test =  $('#topbrands_scoller_mob > li').each(function(){
            var currentLiText = $(this).text().toUpperCase(),
            showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            $(this).toggle(showCurrentLi);
        });  

    }


///Accordion
$(document).ready(function(){
$("#accordion .accordion_title").eq(0).addClass("active");
$("#accordion .accordion_content").eq(0).show();
$("#accordion .accordion_title").click(function(){
    $(this).next(".accordion_content").slideToggle("slow").siblings('.accordion_content').slideUp();
    $(this).toggleClass("active").siblings('.accordion_title').removeClass("active");
});
});

// Option 1: Use native lazy loading
const hasSupport = 'loading' in HTMLImageElement.prototype;
document.documentElement.className = hasSupport ? 'pass' : 'fail';

/* this code has comment because true show in slide cart and phone and email show true in  order detail show true  - 27-3-2024 start */
//document.querySelector('span').textContent = hasSupport;
/* this code has comment because true show in slide cart and phone and email show true in  order detail show true  - 27-3-2024 end */

// Load images of megamenu on hover instead of on page load

    $('ul.menu li').hover(
        function () {
            // Mouse enter - set data-src as src for each image within menu-thumb
            $(this).find('.menu-col-thumb img').each(function () {
                var originalSrc = $(this).data('src');
                $(this).attr('src', originalSrc);
            });
        },
    );

    $('ul.menu li.active1').hover(
        function () {
            $(this).find('.menu-brand-right .menu-brand-logo img').each(function () {
                var originalSrc = $(this).data('src');
                $(this).attr('src', originalSrc);
            });
        },
    );

    // $('.menu-link').click(function (e) {
    //     e.preventDefault();

    //     // Set data-src as src for each image within menu-thumb
    //     $(this).find('.menu-thumb img').each(function () {
    //         var originalSrc = $(this).data('src');
    //         $(this).attr('src', originalSrc);
    //     });
    // });

    $('.mm_slidebar ul.drilldown-root .drilldown-rarrow').click(
        function () {
            $(this).find('.menu-brand-right .menu-brand-logo img').each(function () {
                var originalSrc = $(this).data('src');
                $(this).attr('src', originalSrc);
            });
        },
    );

    // Save default src on document load for each image within menu-thumb
    $('ul.menu li').each(function () {
        $(this).find('.menu-col-thumb img').each(function () {
            var defaultSrc = $(this).attr('src');
            $(this).data('default-src', defaultSrc);
        });
    });

    window.addEventListener( "pageshow", function ( event ) {
        var historyTraversal = event.persisted || 
                               ( typeof window.performance != "undefined" && 
                                    window.performance.navigation.type === 2 );
        if ( historyTraversal ) {
          // Handle page restore.
          window.location.reload();
        }
      });