<!-- Script File Start -->
<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/jquery.min.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
@if($CurrentController == 'ProductDetailController')
  {{-- <script type="module" src="{{ asset('js/front/photoswipe.js') }}" ></script> --}}
@endif
@if(config('const.ENV') == 'Dev')
	<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/jquery.validate.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
	<?php 
		if(count($JSFILES) > 0) {
			foreach($JSFILES as $jsfile){
	?>
		<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/front/{{ $jsfile }}?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
		<?php
		}?>
	<?php }?>

@else 	
<?php if(count($JSFILES) > 0) {?>
<script>
	<?php
		foreach($JSFILES as $jsfile)
		{
			require_once(public_path('/js/'.$jsfile));	
		}
	?>
</script>
<?php } ?>
@endif 

<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/slidebars.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/jquery.drilldown.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
@if($CurrentController != 'CheckoutController')
	<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/front/custom.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
@endif
<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/front/jquery.slimscroll.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/common.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
<!-- Script File End -->
<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/modal.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>
<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/front/popup.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer></script>



<script type="text/javascript" src="{{config('const.SITE_URL_CDN')}}/js/front/common_shoppingcart.js?ver={{config('Settings.FILE_JSCSS_VER')}}" defer ></script>
<script type="text/javascript">
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation, .needs-validation_login')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
            }, false)
        })
    })()

	

</script>
