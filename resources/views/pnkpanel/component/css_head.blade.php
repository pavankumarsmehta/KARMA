<!-- Font Icon -->

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('pnkpanel/css/fonts_icon.css') }}" />



<!-- Stylesheet File Start -->

<link rel="stylesheet" href="{{ asset('pnkpanel/css/bootstrap.css') }}" />

<link rel="stylesheet" href="{{ asset('pnkpanel/css/bootstrap-datepicker3.css') }}" />

<link rel="stylesheet" href="{{ asset('pnkpanel/css/bootstrap-multiselect.css') }}" />



@if (in_array($CurrentRoute, ['pnkpanel.admin.list', 'pnkpanel.trade-show.edit','pnkpanel.country.list', 'pnkpanel.state.list', 'pnkpanel.coupon.list','pnkpanel.coupon.coupon_order_list', 'pnkpanel.autodiscount.list',  'pnkpanel.quantitydiscount.list', 'pnkpanel.category.list', 'pnkpanel.brand.list','pnkpanel.manufacturer.list','pnkpanel.dealweek.list','pnkpanel.newsletter.list', 'pnkpanel.product.list', 'pnkpanel.tax-area.list','pnkpanel.tax-area.tax_area_rate_edit','pnkpanel.shipping-method.list','pnkpanel.customer.list','pnkpanel.shipping-method-charge.list','pnkpanel.manage-static-page.list','pnkpanel.order-summary','pnkpanel.order.list','pnkpanel.email-templates.list','pnkpanel.home-page-banner.list','pnkpanel.order-report.list','pnkpanel.frontmenu.menulist','pnkpanel.manage-currency.list','pnkpanel.manage-quotations.list','pnkpanel.manage-news-press.list','pnkpanel.trade-show.list','pnkpanel.shipping-rule.list','pnkpanel.instagram-feeds.list', 'pnkpanel.order.return_order']))

<link rel="stylesheet" href="{{ asset('pnkpanel/css/dataTables.bootstrap4.css') }}" />

@endif



@if (in_array($CurrentRoute, ['pnkpanel.order-summary', 'pnkpanel.order.list']))

<link rel="stylesheet" href="{{ asset('pnkpanel/css/jquery-ui.css') }}" />

<link rel="stylesheet" href="{{ asset('pnkpanel/css/jquery-ui.theme.css') }}" />

@endif



@if (in_array($CurrentRoute, ['pnkpanel.category.edit','pnkpanel.trade-show.edit','pnkpanel.brand.edit','pnkpanel.manufacturer.edit', 'pnkpanel.product.edit', 'pnkpanel.product.bulk_image_upload', 'pnkpanel.global-setting.edit','pnkpanel.home-page-banner.edit','pnkpanel.home-popular-categories.index','pnkpanel.home-products.index','pnkpanel.tax-area.import_tax_rules_and_rates','pnkpanel.manage-static-page.edit','pnkpanel.frontmenu.menuedit','pnkpanel.manage-news-press.edit']))

<link rel="stylesheet" href="{{ asset('pnkpanel/css/bootstrap-fileupload.min.css') }}" />

@endif



@if (in_array($CurrentRoute, ['pnkpanel.category.edit','pnkpanel.trade-show.edit','pnkpanel.brand.edit','pnkpanel.manufacturer.edit', 'pnkpanel.product.edit', 'pnkpanel.press.edit','pnkpanel.email-templates.list','pnkpanel.home-page-banner.list','pnkpanel.home-page-banner.edit','pnkpanel.home-popular-categories.index','pnkpanel.home-products.index','pnkpanel.frontmenu.menuedit','pnkpanel.manage-news-press.edit']))

<link rel="stylesheet" href="{{ asset('pnkpanel/css/magnific-popup.css') }}" />

@endif



@if (!Pnkpanel::isLoginPage() && !Pnkpanel::isLogoutPage() && !Pnkpanel::isLockScreenPage())

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

@endif



@if (in_array($CurrentRoute, ['pnkpanel.order.details']))

<link rel="stylesheet" href="{{ asset('pnkpanel/css/select2.css') }}" />

<link rel="stylesheet" href="{{ asset('pnkpanel/css/select2-bootstrap.min.css') }}" />

@endif



<link rel="stylesheet" href="{{ asset('pnkpanel/css/theme.css') }}" />

<link rel="stylesheet" href="{{ asset('pnkpanel/css/custom.css') }}" />

