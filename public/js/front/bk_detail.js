$(document).ready(function(){

	// Dtl slider
	$('.dtl-thumb-slider').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: true,
	  infinite: false,
	  asNavFor: '.dtl-ex',
	  prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
	  nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>'

	});
	$('.dtl-ex-slider').slick({
	  slidesToShow:5,
	  slidesToScroll: 1,
	  arrows: true,
	  asNavFor: '.dtl-thumb-slider',
	  vertical: true,
	  verticalSwiping: true,
	  focusOnSelect: true,
	  infinite: false,
	  responsive: [
	      {
	        breakpoint: 1240,
	        settings: { slidesToShow: 4,}
	      },
				{
	        breakpoint: 1104,
	        settings: { slidesToShow: 3,}
	      },
	      {
	        breakpoint: 992,
	        settings: { slidesToShow: 4,}
	      },
	      {
	        breakpoint: 768,
	        settings: { 
	          vertical: false,
	          verticalSwiping: false,
	        }
	      }
	    ],
	  prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
	  nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>'
	  //prevArrow: '<button class="slide-arrow prev-arrow"></button>',
	  //nextArrow: '<button class="slide-arrow next-arrow"></button>'
	});

	//dtl popup slider
	$('.dtl-thumb-slider-popup').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: true,
	  infinite: false,
	  asNavFor: '.dtl-ex',
	  prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
	  nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>'

	});
	$('.dtl-ex-slider-popup').slick({
	  slidesToShow:5,
	  slidesToScroll: 1,
	  arrows: true,
	  asNavFor: '.dtl-thumb-slider-popup',
	  vertical: true,
	  verticalSwiping: true,
	  focusOnSelect: true,
	  infinite: false,
	  responsive: [
	      {
	        breakpoint: 1240,
	        settings: { slidesToShow: 4,}
	      },
				{
	        breakpoint: 1104,
	        settings: { slidesToShow: 3,}
	      },
	      {
	        breakpoint: 992,
	        settings: { slidesToShow: 4,}
	      },
	      {
	        breakpoint: 768,
	        settings: { 
	          vertical: false,
	          verticalSwiping: false,
	        }
	      },
				{
	        breakpoint: 380,
	        settings: { 
						slidesToShow: 4,
						vertical: false,
	          verticalSwiping: false,
					}
	      },
	    ],
	  prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
	  nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>'
	  //prevArrow: '<button class="slide-arrow prev-arrow"></button>',
	  //nextArrow: '<button class="slide-arrow next-arrow"></button>'
	});
	
	//-------touch scrolling mouse?---------//
	const box = document.getElementById('dtl-compare-right');

let isDown = false;
let startX;
let startY;
let scrollLeft;
let scrollTop;

box.addEventListener('mousedown', (e) => {
  isDown = true;
  startX = e.pageX - box.offsetLeft;
  startY = e.pageY - box.offsetTop;
  scrollLeft = box.scrollLeft;
  scrollTop = box.scrollTop;
  box.style.cursor = 'grabbing';
});

box.addEventListener('mouseleave', () => {
  isDown = false;
  box.style.cursor = 'grab';
});

box.addEventListener('mouseup', () => {
  isDown = false;
  box.style.cursor = 'grab';
});

document.addEventListener('mousemove', (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - box.offsetLeft;
  const y = e.pageY - box.offsetTop;
  const walkX = (x - startX) * 1; // Change this number to adjust the scroll speed
  const walkY = (y - startY) * 1; // Change this number to adjust the scroll speed
  box.scrollLeft = scrollLeft - walkX;
  box.scrollTop = scrollTop - walkY;
});
	
});

