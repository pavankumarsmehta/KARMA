function quickview_bind_event() {
    $(document).ready(function () {
      $('.varint-change-flavour-js').each(function( index,value ) {
        if(index > 5){
          if($(this).hasClass('active')){
            
            if($('.showhide-link-variant-js').attr('data-showhide-variant')=='true'){
              $('.showhide-link-variant-js').click();
            }
            return false;
          }	
        }		
      });
      $(".modal").on("shown.bs.modal", function (e) {
        //     alert('test');
            $(".dtl-thumb-slider-popup").slick("setPosition");
            $(".dtl-ex-slider-popup").slick("setPosition");
            $(".wrap-modal-slider").addClass("open");
          });
      //slider
      $('.dtl-ex-slider-popup').not('.slick-initialized').slick({
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
         //dtl popup slider
		$('.dtl-thumb-slider-popup').not('.slick-initialized').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            infinite: false,
            asNavFor: '.dtl-ex-slider-popup',
            prevArrow: '<div class="slick-prev"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>',
            nextArrow: '<div class="slick-next"><svg class="svg_owl_arrow" width="26px" height="26px" aria-hidden="true" role="img" fill="none"><use href="#svg_owl_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_owl_arrow"></use></svg></div>'
        
            });
            
            $('.showhide-link-variant-js').off("click").on("click", function () {
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
            
               
            });
            
            
            
            $('.varint-change-js').off("click").on("click", function () {
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
            
                        if (data.quickview_right_image_section != "") {
                            if($(".quickview-right-desc-section").length > 0){
                                
            
                            $(".quickview-right-desc-section").html(data.quickview_right_image_section);
                            }
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
                        
                        if (data.quickview_left_image_section != "") {
                            $(".quickview-left-image-section").html(data.quickview_left_image_section);
                        }
                           quickview_bind_event();
                        //    $(".dtl-thumb-slider-popup").not('.slick-initialized').slick("setPosition");
                        //    $(".dtl-ex-slider-popup").slick("setPosition");
                        //    $(".wrap-modal-slider").addClass("open");
                        //  $('#page-spinner').hide();
            
                    },
                    error: function (error) {
                        $('#page-spinner').hide();
                    }
                });	
            });
            
  
    });
  }
  quickview_bind_event();
  
 
   