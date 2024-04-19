function re_bind_event() {
	$(document).ready(function(){
		$('.varint-change-flavour-js').each(function( index,value ) {
			if(index > 8){
				if($(this).hasClass('active')){
					
					if($('.showhide-link-variant-js').attr('data-showhide-variant')=='true'){
						$('.showhide-link-variant-js').click();
					}
					return false;
				}	
			}		
		});
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

}
function custom_function_rebind(){
	$(document).ready(function(){
			$("#accordion .accordion_title").eq(0).addClass("active");
			$("#accordion .accordion_content").eq(0).show();
		$("#accordion .accordion_title").click(function(){
			$(this).next(".accordion_content").slideToggle("slow").siblings('.accordion_content').slideUp();
			$(this).toggleClass("active").siblings('.accordion_title').removeClass("active");
		});
	});
	// $.getScript('//staticw2.yotpo.com/ftjanVWD3XCtYEFeeMsmLS0NiX6v29Y3hREHN9mC/widget.js', function() {
		
	//  });
	// $('#yotpojs').remove();
	// (function e(){var e=document.createElement("script");e.type="text/javascript", e.id="yotpojs", e.async=true,e.src="//staticw2.yotpo.com/ftjanVWD3XCtYEFeeMsmLS0NiX6v29Y3hREHN9mC/widget.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();
	var api = new Yotpo.API(yotpo);
api.refreshWidgets();
// 	var api = new Yotpo.API(yotpo);
// api.refreshWidgets();
	 //$.loadScript('//staticw2.yotpo.com/ftjanVWD3XCtYEFeeMsmLS0NiX6v29Y3hREHN9mC/widget.js');
}
re_bind_event();
$(document).on("click", '.showhide-link-variant-js', function () {
	let dataShowhideVariant = $(this).attr('data-showhide-variant');
	let dataShowVariantText = $(this).attr('data-show-variant-text');
	let dataHideVariantText = $(this).attr('data-hide-variant-text');
	dataHideVariantText = dataHideVariantText+'<span class="arrpw"></span>';
	dataShowVariantText = dataShowVariantText+'<span class="arrpw"></span>';
	if(dataShowhideVariant == 'true'){
		$(this).attr('data-showhide-variant','false');
		$(this).html(dataHideVariantText);
		$('.showhide-variant-box-js').removeClass('hidden-lg-down');
		$(this).addClass('swatch-show-less');
		$('#flag-show-hide-varinat').val('false');
	}else{
		$(this).attr('data-showhide-variant','true');
		$(this).html(dataShowVariantText);
		$('.showhide-variant-box-js').addClass('hidden-lg-down');
		$(this).removeClass('swatch-show-less');
		$('#flag-show-hide-varinat').val('true');
	}

	$('html, body').animate({
		
        'scrollTop' : $(".swatch-attribute-flavour-js").position().top - $('.header_mid').height()-100
    });
});
$(document).on("click", '.varint-change-js', function () {
	$('#page-spinner').show();
	let currentVariantName = $(this).attr('data-variant-name');
	let currentVariantValue = $(this).data('variant-value');
	let productGroupCode = $(this).data('product-group-code');
	
	let variantSizeValue = $('.varint-change-size-js.active').attr('data-variant-value');
	let variantPackSizeValue = $('.varint-change-pack-size-js.active').attr('data-variant-value');
	let variantFlavourValue = $('.varint-change-flavour-js.active').attr('data-variant-value');

	let flagShowHideVarinat = $('#flag-show-hide-varinat').val();
	$.ajax({
		type: 'POST',
		url: site_url + '/getdetailviewproduct',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		datatype: 'JSON',
		data: {
			variant_acitve_size_Value: variantSizeValue,
			variant_acitve_pack_size_Value: variantPackSizeValue,
			variant_acitve_flavour_Value: variantFlavourValue,
			current_variant_name: currentVariantName,
			current_variant_value: currentVariantValue,
			product_group_code: productGroupCode,
			flag_show_hide_varinat : flagShowHideVarinat	
		},

		success: function (data) {
			 
			$('#page-spinner').hide();
			if (data.productdetail_right_desc_section != "") {
					$(".product-right-desc-section").html(data.productdetail_right_desc_section);
			}
			// if (data.productdetail_right_variant_flavour != "") {
			// 	//console.log(data.productdetail_right_variant_flavour);
			// 	$(".swatch-attribute-flavour-js").html(data.productdetail_right_variant_flavour);
			// }
			// if (data.productdetail_right_variant_pack_size != "") {
			// 	$(".swatch-attribute-pack-size-js").html(data.productdetail_right_variant_pack_size);
			// }
			// if (data.productdetail_right_variant_size != "") {
			//    $(".swatch-attribute-size-js").html(data.productdetail_right_variant_size);
			// }
			
			if (data.product_review_section != "") {
				$(".product-review-section").html(data.product_review_section);
			}
			
			if (data.product_left_image_section != "") {
				$(".product-left-image-section").html(data.product_left_image_section);
			 }
			 if (data.meta_title != "") {
				$('title').html(data.meta_title);
			 }
			 if (data.meta_description != "") {
				$('meta[name=description]').html(data.meta_description);
			 }
			 if (data.meta_keywords != "") {
				$('meta[name=keywords]').html(data.meta_keywords);
			 }
			 if (data.bredCrumb_Detail != "") {
				$('.breadcrumb').html($(data.bredCrumb_Detail).closest('.breadcrumb')[0].innerHTML);
			 }
			 if (data.product_url != "") {
				history.pushState({}, null, data.product_url);
			}
			 re_bind_event();
			 custom_function_rebind();
			 $('#page-spinner').hide();

		},
		error: function (error) {
			$('#page-spinner').hide();
		}
	});	
});

function toggleContent() {
	var content = document.getElementById('content');
	var btn = document.getElementById('read-more-btn');

	if (content.style.maxHeight) {
		content.style.maxHeight = null;
		btn.innerText = 'Read Less';
	} else {
		content.style.maxHeight = '100px'; // Adjust this height as needed
		btn.innerText = 'Read More';
	}
}