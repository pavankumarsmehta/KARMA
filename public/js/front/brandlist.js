(() => {
	var a, t = {
			609: a => {
				"use strict";
				a.exports = jQuery
			}
		},
		e = {};

	function s(a) {
		var o = e[a];
		if (void 0 !== o) return o.exports;
		var r = e[a] = {
			exports: {}
		};
		return t[a](r, r.exports, s), r.exports
	}(a = s(609))(document).ready((function () {
		function t() {
			a(window).scrollTop() > a("#alp-sticky-anchor").offset().top ? (a("#alphabets_brands").addClass("alph-fixed"), a("#alp-sticky-anchor").height(a("#alphabets_brands").outerHeight())) : (a("#alphabets_brands").removeClass("alph-fixed"), a("#alp-sticky-anchor").height(0))
		}
		a((function () {
			a(window).scroll(t), t()
		})), a("#alphabets_brands a").on("click", (function (t) {
			a("#alphabets_brands a").removeClass("active"), a(this).addClass("active");
			var e = a("#header-sticky").outerHeight() + (a("#alphabets_brands").outerHeight() - 150);
			if ("" !== this.hash) {
				t.preventDefault();
				var s = this.hash,
					o = parseFloat(a(s).offset().top) - parseFloat(e);
				console.log(a(s).offset().top), console.log(o)
			}
		}))
	}))
})();

var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
if (isMobile) {
	function animatebrand(typeval) {
		var character = typeval.toLowerCase();
		var AlphaHeight = $("#alphabets_brands").outerHeight();
		var headHeight = $("#header-sticky").outerHeight();
		//alert("#" + character);
		$('html, body').animate({
			scrollTop: $("#" + character).offset().top - headHeight - AlphaHeight-50
		}, 1000);
	}
} else {
	function animatebrand(typeval) {
		var character = typeval.toLowerCase();
		var AlphaHeight = $("#alphabets_brands").outerHeight();
		var headHeight = $("#header-sticky").outerHeight();
		$('html, body').animate({
			scrollTop: $("#" + character).offset().top - headHeight - AlphaHeight-50
		}, 1000);
	}
}

$(document).ready(function(){
					var options = window.location.search.slice(1)
                      .split('&')
                      .reduce(function _reduce (/*Object*/ a, /*String*/ b) {
                        b = b.split('=');
                        a[b[0]] = decodeURIComponent(b[1]);
                        return a;
                      }, {});

					  console.log(options.filter);
					  if(options.filter){
						var AlphaHeight = $("#alphabets_brands").outerHeight();
						var headHeight = $("#header-sticky").outerHeight();
						var character = options.filter.toLowerCase();
						$('html, body').animate({
							scrollTop: $("#" + character).offset().top - headHeight - AlphaHeight-50
						}, 1000);
					}
					 
	function sticky_relocate() {
		var window_top = $(window).scrollTop();
		var div_top = $('#alp-sticky-anchor').offset().top;
		if (window_top > div_top) {
			$('#alphabets_brands').addClass('alph-fixed');
			$('#alp-sticky-anchor').height($('#alphabets_brands').outerHeight());
		} else {
			$('#alphabets_brands').removeClass('alph-fixed');
			$('#alp-sticky-anchor').height(0);
		}
	}
	$(function() {
		$(window).scroll(sticky_relocate);
		sticky_relocate();
	});

	$("#alphabets_brands a").on('click', function(event) {
		$("#alphabets_brands a").removeClass('active');
		$(this).addClass('active');
		var headHeight = $("#header-sticky").outerHeight();
		var AlphaHeight = $("#alphabets_brands").outerHeight();
		var stickyHeight = headHeight + AlphaHeight;
		if (this.hash !== "") {
			event.preventDefault();
			var hash = this.hash;
			var scollpos = parseFloat($(hash).offset().top) - parseFloat(stickyHeight);
			console.log($(hash).offset().top);
			console.log(scollpos);
			//$('html, body').stop().animate({scrollTop : scollpos},1000);
		}	
	});
});
	/*$(document).ready(function(){
    $("#search_brands_list").keyup(function () {
    	
        var searchText = $(this).val();
        searchText = searchText.toUpperCase();
       var test =  $('#topbrands_scoller > ul li').each(function(){
            var currentLiText = $(this).text().toUpperCase(),
            showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            $(this).toggle(showCurrentLi);
        });  
       var test =  $('#topbrands > a').each(function(){
            var currentLiText = $(this).text().toUpperCase(),
            showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            $(this).toggle(showCurrentLi);
        });  

    });
});*/

