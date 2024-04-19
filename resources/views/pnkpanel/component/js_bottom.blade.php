<!-- Script File Start -->

<script src="{{ asset('pnkpanel/js/plugins/jquery.js') }}"></script>

<script src="{{ asset('pnkpanel/js/plugins/jquery.validate.js') }}"></script>



@if (in_array($CurrentRoute, [ 'pnkpanel.product.bulk_image_upload']))

<script src="{{ asset('pnkpanel/js/plugins/additional-methods.min.js') }}"></script>

@endif



@if (in_array($CurrentRoute, ['pnkpanel.order-summary', 'pnkpanel.order.list']))

<script src="{{ asset('pnkpanel/js/plugins/jquery-ui.js') }}"></script>

@endif



<script src="{{ asset('pnkpanel/js/plugins/jquery.browser.mobile.js') }}"></script>



@if (!Pnkpanel::isLoginPage() && !Pnkpanel::isLogoutPage() && !Pnkpanel::isLockScreenPage())

{{--<!--<script src="{{ asset('pnkpanel/js/plugins/jquery.cookie.js') }}"></script>

<script src="{{ asset('pnkpanel/js/plugins/style.switcher.js') }}"></script>-->--}}

@endif



<script src="{{ asset('pnkpanel/js/plugins/popper.min.js') }}"></script>

<script src="{{ asset('pnkpanel/js/plugins/bootstrap.js') }}"></script>

<script src="{{ asset('pnkpanel/js/plugins/bootstrap-datepicker.js') }}"></script>

<script src="{{ asset('pnkpanel/js/plugins/common.js') }}"></script>

<script src="{{ asset('pnkpanel/js/plugins/nanoscroller.js') }}"></script>



@if (in_array($CurrentRoute, ['pnkpanel.order.details']))

<script src="{{ asset('pnkpanel/js/plugins/select2.js') }}"></script>

@endif



@if (in_array($CurrentRoute, ['pnkpanel.order-summary', 'pnkpanel.order.list']))

<script src="{{ asset('pnkpanel/js/plugins/jquery.maskedinput.js') }}"></script>

@endif



<script src="{{ asset('pnkpanel/js/plugins/theme.js') }}"></script>

<script src="{{ asset('pnkpanel/js/plugins/theme.init.js') }}"></script>

@if (in_array($CurrentRoute, ['pnkpanel.admin.list','pnkpanel.country.list','pnkpanel.state.list', 'pnkpanel.coupon.list', 'pnkpanel.coupon.coupon_order_list', 'pnkpanel.autodiscount.list', 'pnkpanel.quantitydiscount.list', 'pnkpanel.category.list', 'pnkpanel.brand.list','pnkpanel.manufacturer.list', 'pnkpanel.newsletter.list', 'pnkpanel.dealweek.list', 'pnkpanel.product.list','pnkpanel.tax-area.list','pnkpanel.tax-area.tax_area_rate_edit', 'pnkpanel.shipping-method.list','pnkpanel.customer.list', 'pnkpanel.order-summary', 'pnkpanel.order.list', 'pnkpanel.email-templates.list','pnkpanel.home-page-banner.list','pnkpanel.order-report.list','pnkpanel.manage-static-page.list','pnkpanel.frontmenu.menulist','pnkpanel.manage-currency.list','pnkpanel.manage-quotations.list','pnkpanel.manage-news-press.list','pnkpanel.trade-show.list','pnkpanel.shipping-rule.list','pnkpanel.customerorder-report.list','pnkpanel.salestax-report.list','pnkpanel.instagram-feeds.list', 'pnkpanel.order.return_order']))

<script src="{{ asset('pnkpanel/js/plugins/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('pnkpanel/js/plugins/dataTables.bootstrap4.min.js') }}"></script>

@endif



@if (in_array($CurrentRoute, ['pnkpanel.category.edit','pnkpanel.brand.edit','pnkpanel.manufacturer.edit', 'pnkpanel.product.edit', 'pnkpanel.product.bulk_image_upload', 'pnkpanel.press.edit','pnkpanel.global-setting.edit','pnkpanel.home-page-banner.edit','pnkpanel.tax-area.import_tax_rules_and_rates','pnkpanel.frontmenu.menuedit','pnkpanel.manage-news-press.edit','pnkpanel.trade-show.edit']))

<script src="{{ asset('pnkpanel/js/plugins/bootstrap-fileupload.min.js') }}"></script>

@endif



@if (in_array($CurrentRoute, ['pnkpanel.category.edit', 'pnkpanel.brand.edit','pnkpanel.manufacturer.edit','pnkpanel.product.edit', 'pnkpanel.bulkmail.index','pnkpanel.payment-method.edit','pnkpanel.manage-article.edit','pnkpanel.manage-static-page.edit','pnkpanel.email-templates.edit', 'pnkpanel.home-bottom-html.index', 'pnkpanel.bottom-html.index','pnkpanel.home-products.index','pnkpanel.home-page-banner.edit','pnkpanel.frontmenu.menuedit','pnkpanel.manage-news-press.edit','pnkpanel.trade-show.edit']))

<script src="{{ asset('pnkpanel/js/plugins/tinymce/tinymce.min.js') }}"></script>

@endif



@if (in_array($CurrentRoute, ['pnkpanel.category.edit', 'pnkpanel.brand.edit','pnkpanel.manufacturer.edit', 'pnkpanel.product.edit', 'pnkpanel.press.edit' ,'pnkpanel.email-templates.list','pnkpanel.home-page-banner.list','pnkpanel.home-page-banner.edit', 'pnkpanel.home-popular-categories.index', 'pnkpanel.home-products.index','pnkpanel.frontmenu.menuedit','pnkpanel.manage-news-press.edit','pnkpanel.trade-show.edit']))

<script src="{{ asset('pnkpanel/js/plugins/jquery.magnific-popup.js') }}"></script>

@endif





@if (!Pnkpanel::isLoginPage() && !Pnkpanel::isLogoutPage() && !Pnkpanel::isLockScreenPage())

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@endif



<script src="{{ asset('pnkpanel/js/custom_common.js') }}"></script>



<script>

@if (!Pnkpanel::isLoginPage() && !Pnkpanel::isLogoutPage() && !Pnkpanel::isLockScreenPage())

	@if(Session::has('site_common_msg'))

		toastr.options =

		{

			"closeButton" : true,

			"progressBar" : true

		}

		toastr.success("{{ session('site_common_msg') }}");

	@endif



	@if(Session::has('site_common_msg_err'))

		toastr.options =

		{

			"closeButton" : true,

			"progressBar" : true

		}

		toastr.error("{{ session('site_common_msg_err') }}");

	@endif



	@if(Session::has('message'))

		toastr.options =

		{

			"closeButton" : true,

			"progressBar" : true

		}

		toastr.success("{{ session('message') }}");

	@endif



	@if(Session::has('error'))

		toastr.options =

		{

			"closeButton" : true,

			"progressBar" : true

		}

		toastr.error("{{ session('error') }}");

	@endif



	@if(Session::has('info'))

		toastr.options =

		{

			"closeButton" : true,

			"progressBar" : true

		}

		toastr.info("{{ session('info') }}");

	@endif



	@if(Session::has('warning'))

		toastr.options =

		{

			"closeButton" : true,

			"progressBar" : true

		}

		toastr.warning("{{ session('warning') }}");

	@endif

@endif





// store sidebar left toggle state

$('[data-fire-event="sidebar-left-toggle"]').click(function() {

	if (typeof localStorage !== 'undefined') {

		event.preventDefault();

		var htmlElement = document.getElementsByTagName("html")[0];

		if(htmlElement.classList.contains('sidebar-left-collapsed')) { 

			localStorage.removeItem('style-sidebar-left-toggle-collapsed');

		} else {

			localStorage.setItem("style-sidebar-left-toggle-collapsed", true);

		}

	}

});

</script>



@if (!Pnkpanel::isLoginPage() && !Pnkpanel::isLogoutPage() && !Pnkpanel::isLockScreenPage() && Pnkpanel::hasLockoutTime())

<script>let checkLockoutSessionURL = '{{ route("pnkpanel.checklockoutsession") }}'; let lockScreenURL = '{{ route("pnkpanel.lockscreen") }}';</script>

<script src="{{ asset('pnkpanel/js/check_admin_session.js') }}"></script>

@endif

