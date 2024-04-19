 $('.category-slider').owlCarousel({
	items:6,
	dots:false, 
	loop:true,
	margin:20,
	responsive:{
		0: {items: 2,},
		768:{items:3},
		991:{items:4},
		1200:{items:5}
	}
});

$(document).ready(function() {
	$(".cat-galleryowl").owlCarousel({
		items: 4,
		autoplay: false,
		margin:20,  
		nav: false,
		dots:false,  
		loop: false,
		autoHeight: true,
		responsive: {
			0: {items: 2,margin:10,},
			768: {items: 3,},
			992: {items: 4,}
		},
		onInitialized: progressOwl,
		onTranslate: progressOwl,
		onTranslated: progressOwl
	});

	function progressOwl(event) {
		var element = event.target;
		var items = event.item.count;

		if ($(window).width() >= 768) {
			var item = event.item.index + 4;
		} else {
			var item = event.item.index + 2;
		}

		var sldPercent = Math.floor(100 / (items - item + 1));

		if (event.item.index <= 0) {
			sldPercent = 0;
		}
		var Element = $('.cat-galleryowl');
		Element.next('.galleryowl-scroll').find(".galleryowl-bar").width('15%');
		Element.next('.galleryowl-scroll').find(".galleryowl-spacing").css('transition', '1000ms').width(sldPercent + '%');
	}
});