$(document).ready(function() {
	//////Best Seller Tips
	$besellerSlider = $('.slick-seller').slick({
		rows:2,
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

	var $progressBar = $('.slick-progress-bestseller');
	var $progressBarLabel = $( '.slick-progress-bestseller .slick-label' );
	$besellerSlider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {   
		var calc = ( (nextSlide) / (slick.slideCount-1) ) * 100;
		
		$progressBar
			.css('background-size', calc + '% 100%')
			.attr('aria-valuenow', calc );
		
		$progressBarLabel.text( calc + '% completed' );
	});

	//////New Arrival
	$newArrivalSlider = $('.slick-seller-newarrival').slick({
		rows:1,
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

	var $progressBarnewArrvial = $('.slick-progress-newarrival');
	var $progressBarLabelnewArrvial = $( '.slick-progress-newarrival .slick-label' );
	$newArrivalSlider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {   
		var calc = ( (nextSlide) / (slick.slideCount-1) ) * 100;
		
		$progressBarnewArrvial
			.css('background-size', calc + '% 100%')
			.attr('aria-valuenow', calc );
		
		$progressBarLabelnewArrvial.text( calc + '% completed' );
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
