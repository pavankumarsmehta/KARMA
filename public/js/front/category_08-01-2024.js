$(document).ready(function() {
	//////Beauty Tips
	$('.slick-beauty').slick({
		dots: false,
		infinite: false,
		speed: 300,
		slidesToShow:3,
		slidesToScroll:1,
		prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
	nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="20px" height="20px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
		responsive: [
			{ breakpoint: 992, settings: { slidesToShow: 2, slidesToScroll:1 } },
			{ breakpoint: 576, settings: { slidesToShow: 2, slidesToScroll:1 } }
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
});
