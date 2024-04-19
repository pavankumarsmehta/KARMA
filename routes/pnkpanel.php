<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/


Route::get('/', function () {
	return redirect()->route('pnkpanel.dashboard');
});

Route::get('/login', 'LoginController@getLogin')->name('login');
Route::post('/login', 'LoginController@postLogin');
Route::get('/logout', 'LoginController@getLogout')->name('logout');

Route::get('/lockscreen', 'LockScreenController@lockscreen')->name('lockscreen');
Route::post('/lockscreen', 'LockScreenController@unlock');
Route::post('/check-lockout-session', 'LockScreenController@checkLockoutSession')->name('checklockoutsession');
Route::get('/style-switcher', 'StyleSwitcherController@index')->name('style-switcher');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::get('/pnkpanel/list', 'AdminController@list')->name('admin.list');
Route::get('/pnkpanel/edit/{id?}', 'AdminController@edit')->name('admin.edit');
Route::post('/pnkpanel/update', 'AdminController@update')->name('admin.update');
Route::delete('/pnkpanel/delete/{id}', 'AdminController@delete')->name('admin.delete');
Route::post('/pnkpanel/bulk-action', 'AdminController@changeStatus')->name('admin.bulk_action');
Route::delete('/pnkpanel/bulk-action', 'AdminController@bulkDelete')->name('admin.bulk_action');
	
//Currency	
Route::get('/exchange_currency/list', 'ExchangeCurrencyController@list')->name('exchange_currency.list');
Route::get('/exchange_currency/edit/{id?}', 'ExchangeCurrencyController@edit')->name('exchange_currency.edit');
Route::post('/exchange_currency/update', 'ExchangeCurrencyController@update')->name('exchange_currency.update');
Route::delete('/exchange_currency/delete/{id}', 'ExchangeCurrencyController@delete')->name('exchange_currency.delete');
Route::post('/exchange_currency/bulk-action', 'ExchangeCurrencyController@changeStatus')->name('exchange_currency.bulk_action');
Route::delete('/exchange_currency/bulk-action', 'ExchangeCurrencyController@bulkDelete')->name('exchange_currency.bulk_action');

/** Country Module Starts Here **/
Route::get('/country/list', 'CountryController@list')->name('country.list');
Route::post('/country/bulk-action', 'CountryController@changeStatus')->name('country.bulk_action');
/** Country Module Ends Here **/

/** State Module Starts Here **/
Route::get('/state/list', 'StateController@list')->name('state.list');
Route::post('/state/bulk-action', 'StateController@changeStatus')->name('state.bulk_action');
/** State Module Ends Here **/

/** Promotion Module Starts Here **/
Route::get('/coupon/list', 'CouponController@list')->name('coupon.list');
Route::get('/coupon/coupon_order_list/{id?}', 'CouponController@coupon_order_list')->name('coupon.coupon_order_list');
Route::get('/coupon/edit/{id?}', 'CouponController@edit')->name('coupon.edit');
Route::post('/coupon/update', 'CouponController@update')->name('coupon.update');
Route::delete('/coupon/delete/{id}', 'CouponController@delete')->name('coupon.delete');
Route::post('/coupon/bulk-action', 'CouponController@changeStatus')->name('coupon.bulk_action');
Route::delete('/coupon/bulk-action', 'CouponController@bulkDelete')->name('coupon.bulk_action');

Route::get('/autodiscount/list', 'AutoDiscountController@list')->name('autodiscount.list');
Route::get('/autodiscount/edit/{id?}', 'AutoDiscountController@edit')->name('autodiscount.edit');
Route::post('/autodiscount/update', 'AutoDiscountController@update')->name('autodiscount.update');
Route::delete('/autodiscount/delete/{id}', 'AutoDiscountController@delete')->name('autodiscount.delete');
Route::post('/autodiscount/bulk-action', 'AutoDiscountController@changeStatus')->name('autodiscount.bulk_action');
Route::delete('/autodiscount/bulk-action', 'AutoDiscountController@bulkDelete')->name('autodiscount.bulk_action');


Route::get('/dealweek/list', 'DealWeekController@list')->name('dealweek.list');
Route::get('/dealweek/edit/{id?}', 'DealWeekController@edit')->name('dealweek.edit');
Route::post('/dealweek/update', 'DealWeekController@update')->name('dealweek.update');
Route::delete('/dealweek/delete/{id}', 'DealWeekController@delete')->name('dealweek.delete');
Route::post('/dealweek/bulk-action', 'DealWeekController@changeStatus')->name('dealweek.bulk_action');
Route::delete('/dealweek/bulk-action', 'DealWeekController@bulkDelete')->name('dealweek.bulk_action');

Route::get('/quantitydiscount/list', 'QuantityDiscountController@list')->name('quantitydiscount.list');
Route::get('/quantitydiscount/edit/{id?}', 'QuantityDiscountController@edit')->name('quantitydiscount.edit');
Route::post('/quantitydiscount/update', 'QuantityDiscountController@update')->name('quantitydiscount.update');
Route::delete('/quantitydiscount/delete/{id}', 'QuantityDiscountController@delete')->name('quantitydiscount.delete');
Route::post('/quantitydiscount/bulk-action', 'QuantityDiscountController@changeStatus')->name('quantitydiscount.bulk_action');
Route::delete('/quantitydiscount/bulk-action', 'QuantityDiscountController@bulkDelete')->name('quantitydiscount.bulk_action');

Route::get('/bulkmail', 'BulkMailController@index')->name('bulkmail.index');
Route::post('/bulkmail/update', 'BulkMailController@update')->name('bulkmail.update');

Route::get('/newsletter/list', 'NewsLetterController@list')->name('newsletter.list');
Route::post('/newsletter/bulk-action', 'NewsLetterController@changeStatus')->name('newsletter.bulk_action');
Route::delete('/newsletter/bulk-action', 'NewsLetterController@bulkDelete')->name('newsletter.bulk_action');
Route::get('/newsletter/export', 'NewsLetterController@newsLetterExport')->name('newsletter.export');

Route::get('/press/list', 'PressController@list')->name('press.list');
Route::get('/press/edit/{id?}', 'PressController@edit')->name('press.edit');
Route::post('/press/update', 'PressController@update')->name('press.update');
Route::delete('/press/delete/{id}', 'PressController@delete')->name('press.delete');
Route::post('/press/bulk-action', 'PressController@changeStatus')->name('press.bulk_action');
Route::delete('/press/bulk-action', 'PressController@bulkDelete')->name('press.bulk_action');
Route::post('/press/bulk-action-update-rank', 'PressController@bulkUpdateRank')->name('press.bulk_action_update_rank');
Route::delete('/press/delete-image', 'PressController@deleteImage')->name('press.delete_image');
Route::delete('/press/delete-pdf', 'PressController@deletePdf')->name('press.delete_pdf');

/** Promotion Module Ends Here **/

Route::get('/category/list', 'CategoryController@list')->name('category.list');
Route::get('/category/edit/{id?}', 'CategoryController@edit')->name('category.edit');
Route::post('/category/update', 'CategoryController@update')->name('category.update');
Route::delete('/category/delete/{id}', 'CategoryController@delete')->name('category.delete');
Route::post('/category/bulk-action', 'CategoryController@changeStatus')->name('category.bulk_action');
Route::delete('/category/bulk-action', 'CategoryController@bulkDelete')->name('category.bulk_action');
Route::post('/category/bulk-action-update-rank', 'CategoryController@bulkUpdateRank')->name('category.bulk_action_update_rank');
Route::delete('/category/delete-image', 'CategoryController@deleteImage')->name('category.delete_image');

Route::get('/brand/list', 'BrandController@list')->name('brand.list');
Route::get('/brand/edit/{id?}', 'BrandController@edit')->name('brand.edit');
Route::post('/brand/update', 'BrandController@update')->name('brand.update');
Route::delete('/brand/delete/{id}', 'BrandController@delete')->name('brand.delete');
Route::post('/brand/bulk-action', 'BrandController@changeStatus')->name('brand.bulk_action');
Route::delete('/brand/bulk-action', 'BrandController@bulkDelete')->name('brand.bulk_action');
Route::post('/brand/bulk-action-update-rank', 'BrandController@bulkUpdateRank')->name('brand.bulk_action_update_rank');
Route::delete('/brand/delete-image', 'BrandController@deleteImage')->name('brand.delete_image');

Route::get('/manufacturer/list', 'ManufacturerController@list')->name('manufacturer.list');
Route::get('/manufacturer/edit/{id?}', 'ManufacturerController@edit')->name('manufacturer.edit');
Route::post('/manufacturer/update', 'ManufacturerController@update')->name('manufacturer.update');
Route::delete('/manufacturer/delete/{id}', 'ManufacturerController@delete')->name('manufacturer.delete');
Route::post('/manufacturer/bulk-action', 'ManufacturerController@changeStatus')->name('manufacturer.bulk_action');
Route::delete('/manufacturer/bulk-action', 'ManufacturerController@bulkDelete')->name('manufacturer.bulk_action');
Route::post('/manufacturer/bulk-action-update-rank', 'ManufacturerController@bulkUpdateRank')->name('manufacturer.bulk_action_update_rank');
Route::delete('/manufacturer/delete-image', 'ManufacturerController@deleteImage')->name('manufacturer.delete_image');

Route::get('/frontmenu/menulist', 'MenuListController@menulist')->name('frontmenu.menulist');
Route::get('/frontmenu/edit/{id?}/{parent?}', 'MenuListController@edit')->name('frontmenu.menuedit');
Route::post('/frontmenu/update', 'MenuListController@update')->name('frontmenu.update');
Route::delete('/frontmenu/delete/{id}', 'MenuListController@delete')->name('frontmenu.delete');
Route::post('/frontmenu/bulk-action', 'MenuListController@changeStatus')->name('frontmenu.bulk_action');
Route::delete('/frontmenu/bulk-action', 'MenuListController@bulkDelete')->name('frontmenu.bulk_action');
Route::post('/frontmenu/bulk-action-update-rank', 'MenuListController@bulkUpdateRank')->name('frontmenu.bulk_action_update_rank');
Route::delete('/frontmenu/delete-image', 'MenuListController@deleteImage')->name('frontmenu.delete_image');

//Route::get('/frontmenu/list', 'FrontmenuController@list')->name('frontmenu.list');
//Route::get('/frontmenu/edit/{id?}', 'FrontmenuController@edit')->name('frontmenu.edit');
//Route::post('/frontmenu/update', 'FrontmenuController@update')->name('frontmenu.update');
//Route::delete('/frontmenu/delete/{id}', 'FrontmenuController@delete')->name('frontmenu.delete');
//Route::post('/frontmenu/bulk-action', 'FrontmenuController@changeStatus')->name('frontmenu.bulk_action');
//Route::delete('/frontmenu/bulk-action', 'FrontmenuController@bulkDelete')->name('frontmenu.bulk_action');
//Route::post('/frontmenu/bulk-action-update-rank', 'FrontmenuController@bulkUpdateRank')->name('frontmenu.bulk_action_update_rank');
//Route::delete('/frontmenu/delete-image', 'FrontmenuController@deleteImage')->name('frontmenu.delete_image');

//Route::get('/category/{id}/category-brand/edit', 'CategoryBrandController@edit')->name('category.categorybrand.edit');
//Route::post('/category/{id}/category-brand/update', 'CategoryBrandController@update')->name('category.categorybrand.update');

Route::get('/product/list', 'ProductController@list')->name('product.list');
Route::get('/product/edit/{id?}', 'ProductController@edit')->name('product.edit');
Route::post('/product/update', 'ProductController@update')->name('product.update');
Route::delete('/product/delete/{id}', 'ProductController@delete')->name('product.delete');
Route::post('/get_color_colorfamily', 'ProductController@getColorByColorFamilyId');
Route::post('/product/bulk-action', 'ProductController@changeStatus')->name('product.bulk_action');
Route::post('/product/bulk-action-update-rank', 'ProductController@bulkUpdateRank')->name('product.bulk_action_update_rank');
Route::delete('/product/bulk-action', 'ProductController@bulkDelete')->name('product.bulk_action');
Route::post('/product/bulk-action-update-rank', 'ProductController@bulkUpdateRank')->name('product.bulk_action_update_rank');
Route::post('/product/bulk-action-update-group-rank', 'ProductController@bulkUpdateGroupRank')->name('product.bulk_action_update_group_rank');
Route::post('/product/bulk-action-update-sale-rank', 'ProductController@bulkUpdateSaleRank')->name('product.bulk_action_update_sale_rank');
Route::delete('/product/delete-image', 'ProductController@deleteImage')->name('product.delete_image');
Route::delete('/product/delete-pdf', 'ProductController@deletePdf')->name('product.delete_pdf');
Route::post('/product/bulk-action-create-clone', 'ProductController@bulkCreateProductClone')->name('product.bulk_action_create_clone');
Route::post('/ajax/product/brands/{manufacture_id?}', 'ProductController@getBrandsDropdown')->name('product.getbrands');
Route::get('/product/export', 'ProductExportImportController@export_view')->name('product.export');
Route::post('/product/export', 'ProductExportImportController@export')->name('product.export');

Route::get('/product/updateexportproduct', 'ProductExportImportController@updateexportproduct_view')->name('product.updateexportproduct_view');

Route::post('/product/headerexport', 'ProductExportImportController@headerexport')->name('product.headerexport');
Route::get('/product/headerexport', 'ProductExportImportController@headerexport_view')->name('product.headerexport');

Route::get('/product/import', 'ProductExportImportController@import_view')->name('product.import');
Route::post('/product/import', 'ProductExportImportController@import')->name('product.import');

Route::get('/product/updateimportproduct', 'ProductExportImportController@updateimportproduct_view')->name('product.updateimportproduct_view');
Route::post('/product/updateimportproduct', 'ProductExportImportController@updateimportproduct')->name('product.updateimportproduct');
//Route::post('/product/updateimportproduct', 'ProductExportImportController@import')->name('product.import');
Route::post('/product/import_batch', 'ProductExportImportController@import_batch')->name('product.post_import');
Route::get('/product/import_batch', 'ProductExportImportController@import_batch')->name('product.post_import');

Route::post('/product/import_limited_batch', 'ProductExportImportController@import_limited_batch')->name('product.post_import_limited');
Route::get('/product/import_limited_batch', 'ProductExportImportController@import_limited_batch')->name('product.post_import_limited');

Route::get('/product/bulk-image-upload', 'ProductController@bulkImageUpload')->name('product.bulk_image_upload');
Route::post('/product/bulk-image-upload', 'ProductController@postBulkImageUpload')->name('product.bulk_image_upload');

Route::get('/order-summary', 'OrderSummaryController@list')->name('order-summary');

Route::get('/order/list', 'OrderController@list')->name('order.list');
Route::delete('/order/bulk-action', 'OrderController@bulkDelete')->name('order.bulk_action');
Route::get('/order/details/{id}', 'OrderController@details')->name('order.details');
Route::post('/ajax/auto-suggest-customer-name', 'CustomerController@ajaxAutoSuggestCustomerName')->name('customer.auto_suggest_customer_name');
Route::post('/order/update', 'OrderController@update')->name('order.update');
Route::delete('/order/delete/{id}', 'OrderController@delete')->name('order.delete');
Route::get('/order/order-slip', 'OrderController@orderSlip')->name('order.order_slip');
Route::post('/order/order-slip', 'OrderController@orderSlip')->name('order.order_slip');
Route::get('/order/packing-slip', 'OrderController@packingSlip')->name('order.packing_slip');
Route::post('/order/packing-slip', 'OrderController@packingSlip')->name('order.packing_slip');
Route::get('/order/export', 'OrderController@sampleOrderExport')->name('sampleOrder.export');

Route::get('/order/return-orders', 'ReturnOrderController@returnOrderList')->name('order.return_order');
Route::delete('/order/return-orders/bulk-action', 'ReturnOrderController@returnOrderBulkDelete')->name('order.return_order.bulk_action');
Route::get('/order/return-orders/details/{id}', 'ReturnOrderController@returnOrderDetails')->name('order.return_order.details');
Route::post('/order/return-orders/details/acceptRejectReturnOrder', 'ReturnOrderController@acceptRejectReturnOrder')->name('order.return_order.acceptReject');
/* Tax Area Module Stars Here **/

Route::get('/tax-area/list', 'StoreSettingsController@tax_area_list')->name('tax-area.list');
Route::get('/tax-area/edit/{id?}', 'StoreSettingsController@tax_area_edit')->name('tax-area.edit');
Route::get('/tax-area/tax-rate-edit/{id}/{editid?}', 'StoreSettingsController@tax_area_rate_edit')->name('tax-area.tax_area_rate_edit');
Route::get('/tax-area/tax-area-rate-list/{id}','StoreSettingsController@tax_area_rate_list')->name('tax-area.tax_area_rate_list');
Route::post('/tax-area/tax-area-rate-update','StoreSettingsController@tax_area_rate_update')->name('tax-area.tax_area_rate_update');
Route::post('/tax-area/update', 'StoreSettingsController@tax_area_update')->name('tax-area.update');
Route::post('/tax-area/bulk-action', 'StoreSettingsController@changeStatus')->name('tax-area.bulk_action');
Route::delete('/tax-area/delete/{id}', 'StoreSettingsController@tax_area_delete')->name('tax-area.delete');
Route::delete('/tax-area/tax-area-rate-delete/{id}', 'StoreSettingsController@tax_area_rate_delete')->name('tax-area.tax_area_rate_delete');
Route::delete('/tax-area/bulk-action', 'StoreSettingsController@bulkDelete')->name('tax-area.bulk_action');
Route::post('/tax-area/import-csv', 'StoreSettingsController@import_tax_csv_files')->name('tax-area.csv_import');
Route::get('/tax-area/import-tax-rules-and-rates','StoreSettingsController@import_tax_rules_and_rates')->name('tax-area.import_tax_rules_and_rates');

/* Tax Area Modules Ends Here **/

/* Shipping Methods Module Stars Here **/

Route::get('/shipping-method/list', 'StoreSettingsController@shipping_method_list')->name('shipping-method.list');
Route::get('/shipping-method/edit/{id?}', 'StoreSettingsController@shipping_method_edit')->name('shipping-method.edit');
Route::post('/shipping-method/update', 'StoreSettingsController@shipping_method_update')->name('shipping-method.update');
Route::delete('/shipping-method/delete/{id}', 'StoreSettingsController@shipping_method_delete')->name('shipping-method.delete');
Route::delete('/shipping-method/bulk-action', 'StoreSettingsController@bulkDelete')->name('shipping-method.bulk_action');
Route::post('/shipping-method/bulk-action', 'StoreSettingsController@changeStatus')->name('shipping-method.bulk_action');

Route::post('/shipping-method/bulk-action-update-rank', 'StoreSettingsController@bulkUpdateRank')->name('shipping-method.bulk_action_update_rank');
Route::post('/shipping-method/bulk-action-update-lift-gate-settings', 'StoreSettingsController@bulkUpdateliftGateSettings')->name('shipping-method.bulk_action_update_lift_gate_settings');


Route::get('/shipping-method-charge/{id?}', 'StoreSettingsController@shipping_method_charge_list')->name('shipping-method-charge.list');
Route::post('/shipping-method-charge/update', 'StoreSettingsController@shipping_method_charge_update')->name('shipping-method-charge.update');
Route::get('/shipping-method-charge/edit/{id?}/{shipping_id?}', 'StoreSettingsController@shipping_method_charge_edit')->name('shipping-method-charge.edit');
Route::delete('/shipping-method-charge/delete/{id}', 'StoreSettingsController@shipping_method_charge_delete')->name('shipping-method-charge.delete');
Route::delete('/shipping-method-charge/bulk-action', 'StoreSettingsController@bulkDelete')->name('shipping-method-charge.bulk_action');


/* Shipping Methods Modules Ends Here **/
/* Shipping Rule Modules Start Here **/

Route::get('/shipping-rule/list', 'StoreSettingsController@shipping_rule_list')->name('shipping-rule.list');
Route::get('/shipping-rule/edit/{id?}', 'StoreSettingsController@shipping_rule_edit')->name('shipping-rule.edit');
Route::post('/shipping-rule/update', 'StoreSettingsController@shipping_rule_update')->name('shipping-rule.update');
Route::delete('/shipping-rule/delete/{id}', 'StoreSettingsController@shipping_rule_delete')->name('shipping-rule.delete');
Route::delete('/shipping-rule/bulk-action', 'StoreSettingsController@bulkDelete')->name('shipping-rule.bulk_action');

/* Shipping Rule Modules Ends Here **/


/* Customer Module Stars Here **/

Route::get('/customer/list', 'CustomerController@list')->name('customer.list');
Route::post('/customer/update', 'CustomerController@update')->name('customer.update');
Route::get('/customer/edit/{id?}', 'CustomerController@edit')->name('customer.edit');
Route::delete('/customer/delete/{id}', 'CustomerController@delete')->name('customer.delete');
Route::delete('/customer/bulk-action', 'CustomerController@bulkDelete')->name('customer.bulk_action');
Route::post('/customer/bulk-action', 'CustomerController@changeStatus')->name('customer.bulk_action'); 
Route::get('/customer/export', 'CustomerController@customerExport')->name('customer.export');
Route::post('/customer/email-forgot-password', 'CustomerController@emailForgotPassword')->name('customer.email_forgot_password');
Route::get('/customer/project/list/{id?}', 'CustomerController@projectList')->name('customer-project.list');
Route::get('/customer/project-products/list', 'CustomerController@projectProductList')->name('customer-project-products.list');

/* Customer Modules Ends Here **/

/* Manage Payment Methods Module Stars Here **/

Route::get('/payment-method/list', 'StoreSettingsController@payment_method_list')->name('payment-method.list');
Route::get('/payment-method/edit/{id?}', 'StoreSettingsController@payment_method_edit')->name('payment-method.edit');
Route::post('/payment-method/update', 'StoreSettingsController@payment_method_update')->name('payment-method.update');
Route::delete('/payment-method/delete/{id}', 'StoreSettingsController@payment_method_delete')->name('payment-method.delete');
Route::delete('/payment-method/bulk-action', 'StoreSettingsController@bulkDelete')->name('payment-method.bulk_action');

/* Manage Payment Methods Modules Ends Here **/

/* Meta Info Module Stars Here **/

Route::get('/meta-info/edit/{type?}', 'MetaInfoController@edit')->name('meta-info.edit');
Route::post('/meta-info/update', 'MetaInfoController@update')->name('meta-info.update');
Route::post('/meta-info/get-html', 'MetaInfoController@getHtml')->name('meta-info.get-html');

/* Meta Info Module Ends Here **/


/* Global Setting Module Stars Here **/
Route::get('/global-setting', 'GlobalSettingController@index')->name('global-setting.index');
Route::post('/global-setting/update', 'GlobalSettingController@update')->name('global-setting.update');
/* Global Setting Module Ends Here **/

/* Instagram Setting Module Stars Here **/
Route::get('/instagram_settings', 'StoreSettingsController@instagram_settings_edit')->name('instagram-settings.edit');
Route::post('/instagram_settings/update', 'StoreSettingsController@instagram_settings_update')->name('instagram-settings.update');
/* Instagram Setting Module Ends Here **/

/* Manage Static Page Module Stars Here **/

Route::get('/manage-static-page/list', 'StoreSettingsController@manage_static_page_list')->name('manage-static-page.list');
Route::get('/manage-static-page/edit/{id?}', 'StoreSettingsController@manage_static_page_edit')->name('manage-static-page.edit');
Route::post('/manage-static-page/update', 'StoreSettingsController@manage_static_page_update')->name('manage-static-page.update');
Route::delete('/manage-static-page/delete/{id}', 'StoreSettingsController@manage_static_page_delete')->name('manage-static-page.delete');
Route::delete('/manage-static-page/bulk-action', 'StoreSettingsController@bulkDelete')->name('manage-static-page.bulk_action');
Route::post('/manage-static-page/bulk-action', 'StoreSettingsController@changeStatus')->name('manage-static-page.bulk_action');

Route::delete('/manage-static-page/delete-image', 'StoreSettingsController@manage_static_pagedeleteImage')->name('manage-static-page.delete_image');

/* Manage Static Page Ends Here **/

/* Email Templates Module */
Route::get('/email-templates/list', 'EmailTemplatesController@list')->name('email-templates.list');
Route::get('/email-templates/edit/{id?}', 'EmailTemplatesController@edit')->name('email-templates.edit');
Route::post('/email-templates/update', 'EmailTemplatesController@update')->name('email-templates.update');
Route::delete('/email-templates/delete/{id}', 'EmailTemplatesController@delete')->name('email-templates.delete');
Route::post('/email-templates/bulk-action', 'EmailTemplatesController@changeStatus')->name('email-templates.bulk_action');
Route::delete('/email-templates/bulk-action', 'EmailTemplatesController@bulkDelete')->name('email-templates.bulk_action');
Route::get('/ajax/email-templates/get-email-content/{id?}', 'EmailTemplatesController@getEmailContent')->name('email-templates.get_content');

/* Manage currency Module Stars Here **/
Route::get('/manage-currency/list', 'CurrencyController@manage_currency_list')->name('manage-currency.list');
Route::get('/manage-currency/edit/{id?}', 'CurrencyController@manage_currency_edit')->name('manage-currency.edit');
Route::post('/manage-currency/update', 'CurrencyController@manage_currency_update')->name('manage-currency.update');
Route::delete('/manage-currency/delete/{id}', 'CurrencyController@manage_currency_delete')->name('manage-currency.delete');
Route::delete('/manage-currency/bulk-action', 'CurrencyController@bulkDelete')->name('manage-currency.bulk_action');
Route::post('/manage-currency/bulk-action', 'CurrencyController@changeStatus')->name('manage-currency.bulk_action');

/* Manage Quotation Module Stars Here **/
Route::get('/manage-quotations/list', 'QuotationController@manage_quotations_list')->name('manage-quotations.list');
Route::get('/manage-quotations/edit/{id?}', 'QuotationController@manage_quotations_edit')->name('manage-quotations.edit');
Route::post('/manage-quotations/update', 'QuotationController@manage_quotations_update')->name('manage-quotations.update');
Route::delete('/manage-quotations/delete/{id}', 'QuotationController@manage_quotations_delete')->name('manage-quotations.delete');
Route::delete('/manage-quotations/bulk-action', 'QuotationController@bulkDelete')->name('manage-quotations.bulk_action');
Route::post('/manage-quotations/bulk-action', 'QuotationController@changeStatus')->name('manage-quotations.bulk_action');

/* Manage news and press Module Stars Here **/
Route::get('/manage-news-press/list', 'NewsPressController@manage_news_press_list')->name('manage-news-press.list');
Route::get('/manage-news-press/edit/{id?}', 'NewsPressController@manage_news_press_edit')->name('manage-news-press.edit');
Route::post('/manage-news-press/update', 'NewsPressController@manage_news_press_update')->name('manage-news-press.update');
Route::delete('/manage-news-press/delete/{id}', 'NewsPressController@manage_news_press_delete')->name('manage-news-press.delete');
Route::delete('/manage-news-press/bulk-action', 'NewsPressController@bulkDelete')->name('manage-news-press.bulk_action');
Route::post('/manage-news-press/bulk-action', 'NewsPressController@changeStatus')->name('manage-news-press.bulk_action');
Route::delete('/manage-news-press/delete-image', 'NewsPressController@deleteImage')->name('manage-news-press.delete-image');

/* Brand Module Stars Here **/

Route::get('/product-feature/list', 'ProductFeatureController@list')->name('product-feature.list');
Route::post('/product-feature/update', 'ProductFeatureController@update')->name('product-feature.update');
Route::get('/product-feature/edit/{id?}', 'ProductFeatureController@edit')->name('product-feature.edit');
Route::delete('/product-feature/delete/{id}', 'ProductFeatureController@delete')->name('product-feature.delete');
Route::delete('/product-feature/bulk-action', 'ProductFeatureController@bulkDelete')->name('product-feature.bulk_action');
Route::post('/product-feature/bulk-action', 'ProductFeatureController@changeStatus')->name('product-feature.bulk_action');
Route::delete('/product-feature/delete-image', 'ProductFeatureController@deleteImage')->name('product-feature.delete_image');

/* Product Feature Modules Ends Here **/
/* Home Products Stars Here **/

Route::get('/home-products', 'HomeProductsController@index')->name('home-products.index');
Route::post('/home-products/update', 'HomeProductsController@update')->name('home-products.update');
Route::delete('/home-products/delete-image', 'HomeProductsController@deleteImage')->name('home-products.delete_image');

/* Home Products Ends Here **/


/* Home Page Banner Stars Here **/

Route::get('/home-page-banner/list', 'HomePageBannerController@list')->name('home-page-banner.list');
Route::post('/home-page-banner/update', 'HomePageBannerController@update')->name('home-page-banner.update');
Route::get('/home-page-banner/edit/{id?}', 'HomePageBannerController@edit')->name('home-page-banner.edit');
Route::delete('/home-page-banner/delete/{id}', 'HomePageBannerController@delete')->name('home-page-banner.delete');
Route::delete('/home-page-banner/bulk-action', 'HomePageBannerController@bulkDelete')->name('home-page-banner.bulk_action');
Route::post('/home-page-banner/bulk-action', 'HomePageBannerController@changeStatus')->name('home-page-banner.bulk_action');
Route::delete('/home-page-banner/delete-image', 'HomePageBannerController@deleteImage')->name('home-page-banner.delete_image');
Route::post('/home-page-banner/bulk-action-update-rank', 'HomePageBannerController@bulkUpdateRank')->name('home-page-banner.bulk_action_update_rank');

/* Home Page Banner Ends Here **/

/* Home Page Category Stars Here **/

Route::get('/home-popular-categories', 'HomePopularCategoriesController@index')->name('home-popular-categories.index');
Route::post('/home-popular-categories/update', 'HomePopularCategoriesController@update')->name('home-popular-categories.update');
Route::delete('/home-popular-categories/delete-image', 'HomePopularCategoriesController@deleteImage')->name('home-popular-categories.delete_image');

/* Home page Category Ends Here **/

/* Home Bottom HTML Stars Here **/

Route::get('/home-bottom-html', 'HomeBottomHtmlController@index')->name('home-bottom-html.index');
Route::post('/home-bottom-html/update', 'HomeBottomHtmlController@update')->name('home-bottom-html.update');

/* Home Bottom HTML Ends Here **/

/* Home Bottom HTML Stars Here **/

Route::get('/bottom-html', 'BottomHtmlController@index')->name('bottom-html.index');
Route::post('/bottom-html/update', 'BottomHtmlController@update')->name('bottom-html.update');

/* Home Bottom HTML Ends Here **/

/* Order Reports Module Stars Here **/

Route::get('/order-report', 'OrderReportController@list')->name('order-report.list');
Route::get('/order/list/{filters?}', 'OrderController@list')->where('filters', '(.*)')->name('order.listfilter'); 

/* Order Reports Module Ends Here **/

/* Sales Tax Reports Module Stars Here **/

Route::get('/salestax-report', 'SalesTaxReportController@list')->name('salestax-report.list');

/* Sales Tax Reports Module Ends Here **/

/* Shipping Charge Reports Module Stars Here **/

Route::get('/shippingcharge-report', 'ShippingChargeReportController@list')->name('shippingcharge-report.list');

/* Shipping Charge Reports Module Ends Here **/

/* Customer Reports Module Stars Here **/

Route::get('/customerorder-report', 'CustomerOrderReportController@list')->name('customerorder-report.list');

/* Instagram feed Module Stars Here **/

Route::get('/instagram-feeds/list', 'InstagramFeedController@list')->name('instagram-feeds.list');
Route::post('/instagram-feeds/bulk-action', 'InstagramFeedController@changeStatus')->name('instagram-feeds.bulk_action');
Route::delete('/instagram-feeds/bulk-action', 'InstagramFeedController@bulkDelete')->name('instagram-feeds.bulk_action');

Route::get('/instagram-feeds/fetch', 'InstagramFeedController@fetch')->name('instagram-feeds.fetch');
Route::post('/instagram-feeds/accept', 'InstagramFeedController@instagram_feed_accept')->name('instagram-feeds.accept');

/* Instagram feed Modules Ends Here **/

/* Customer Reports Module Ends Here **/
Route::get('/trade-show/list', 'TradeShowController@list')->name('trade-show.list');
Route::get('/trade-show/edit/{id?}', 'TradeShowController@edit')->name('trade-show.edit');
Route::post('/trade-show/update', 'TradeShowController@update')->name('trade-show.update');
Route::delete('/trade-show/delete/{id}', 'TradeShowController@delete')->name('trade-show.delete');
Route::post('/trade-show/bulk-action', 'TradeShowController@changeStatus')->name('trade-show.bulk_action');
Route::delete('/trade-show/bulk-action', 'TradeShowController@bulkDelete')->name('trade-show.bulk_action');
Route::post('/trade-show/bulk-action-update-rank', 'TradeShowController@bulkUpdateRank')->name('trade-show.bulk_action_update_rank');
Route::delete('/trade-show/delete-image', 'TradeShowController@deleteImage')->name('trade-show.delete_image');
