$('#home_slider').owlCarousel({
    lazyLoad:true,
    loop:false,
    items:1,
    nav:false,
		navText : ['<span><svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg></span>','<span><svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none"><use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use></svg></span>'],
		dots:true
 });

 $('.ctbox-slider').owlCarousel({
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

$('.dealday-slider').owlCarousel({
	centerMode: true,
	items:6,
	dots:false, 
	loop:true,
	margin:10,
	responsive:{
		0: {items: 2,},
		768:{items:2},
		991:{items:4},
		1200:{items:5}
	}
});

$(document).ready(function() {
	$(".galleryowl").owlCarousel({
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
		var Element = $('.galleryowl');
		Element.next('.galleryowl-scroll').find(".galleryowl-bar").width('15%');
		Element.next('.galleryowl-scroll').find(".galleryowl-spacing").css('transition', '1000ms').width(sldPercent + '%');
	}
	
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
				clockdata+='<li><span class="count" id="days">'+d+'</span><span class="count_name">Days</span></li>';
				clockdata+='<li><span class="count" id="hrs">'+h+'</span><span class="count_name">Hours</span></li>';
				clockdata+='<li><span class="count" id="min">'+m+'</span><span class="count_name">Mins</span></li>';
				clockdata+='<li><span class="count" id="sec">'+s+'</span><span class="count_name">Secs</span></li>';
				$clock.html(clockdata);
				//$d.html('<span class="countdown-section"><span class="countdown-amount" id="days">'+d+'</span><span class="countdown-period">Days</span></span>');
				//$h.html('<span class="countdown-section"><span class="countdown-amount" id="hrs">'+h+'</span><span class="countdown-period">Hours</span></span>');
				//$m.html('<span class="countdown-section"><span class="countdown-amount" id="min">'+m+'</span><span class="countdown-period">Mins</span></span>');
				//$s.html('<span class="countdown-section"><span class="countdown-amount" id="sec">'+s+'</span><span class="countdown-period">Secs</span></span>');

			}, interval);
		}
	}
});