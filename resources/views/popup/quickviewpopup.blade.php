<div class="modal fade qv-popup" id="quick-view" >
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content"><svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal">
    <div class="modal-body">
    <svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal"><use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use></svg>
          <section class="dtl-left quickview-left-image-section">
          @include('popup.quickview.quickview_left_image')
          </section>
          <section class="dtl-right quickview-right-desc-section">        
            @include('popup.quickview.quickview_right_desc')
          </section>
          <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="{{ asset('public/css/detail.css') }}" />
<link rel="stylesheet" href="{{ asset('public/css/slick.css') }}" />
<script id="quickview-slider" src="{{ asset('js/front/slick.js') }}?{{ date('hsi') }}"></script>
<script id="quickview" src="{{ asset('js/front/quickview.js') }}?{{ date('hsi') }}"></script>
