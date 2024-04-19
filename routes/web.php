<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/clear-c', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    //Artisan::call('debugbar:clear');
    //system('composer dump-autoload');
    return "Cache is cleared";
});

Route::get('/sachin', function() {
    return phpinfo();
});

Auth::routes();


Route::get('/', 'HomeController@index')->name('home');

Route::POST('/home/Deals', 'AjaxHomeController@Deals');
Route::POST('/home/SeasonSpecial', 'AjaxHomeController@SeasonSpecial');
Route::POST('/home/Category', 'AjaxHomeController@Category');
Route::POST('/home/Brands', 'AjaxHomeController@Brands');
Route::POST('/home/NewArrival', 'AjaxHomeController@NewArrival');
Route::POST('/home/AboutUs', 'AjaxHomeController@AboutUs');

Route::post('/get_products', 'ProductController@ProductListAjax');
Route::post('/get_searchproducts', 'SearchController@getSearchProductListingAjax');

//Route::post('/get_products11', 'BrandController@ProductListPage');
//----------------------------------
// Customer Module Start 
//-----------------------------------	
Route::match(array('GET','POST'),'/login.html', 'CustomerController@Login')->name('login');
Route::match(array('GET','POST'),'/login', 'CustomerController@Login')->name('login');
Route::match(array('GET','POST'),'/register.html', 'CustomerController@Register')->name('register');
Route::match(array('GET','POST'),'/forgot-password.html', 'CustomerController@ForgotPassword')->name('forgot-password');
//Route::match(array('GET','POST'),'/reset/{token}', 'CustomerController@resetPassword')->name('reset-password');

Route::get('/reset/{token}', 'CustomerController@resetPasswordPage')->name('reset-password-page');
Route::post('/reset/{token}', 'CustomerController@resetPassword')->name('reset-password');

Route::match(array('GET','POST'),'/other-categories.html', 'CategoryController@otherCategory')->name('other-category');

//Route::match(array('GET','POST'),'/brand-perfumes.html', 'BrandController@BrandPerfume')->name('BrandPerfume');
//Route::match(array('GET','POST'),'/{main_catgory}/brand-perfumes.html', 'BrandController@BrandPerfume')->name('BrandPerfume');
Route::match(array('GET','POST'),'/brand-{main_catgory}.html', 'BrandController@BrandPerfume')->name('BrandPerfume');

//Route::match(array('GET','POST'),'/brand/{brand_name}/brid-{brand_id}', 'BrandController@BrandListing')->name('BrandListing');
Route::match(array('GET','POST'),'/brand/{brand_name}/brid/{brand_id}/{filters?}', 'BrandController@BrandProductListing')->where('filters', '(.*)')->name('BrandProductListing');
Route::match(array('GET','POST'),'/{category_name}/{brand_name}/brid/{brand_id}/{filters?}', 'BrandController@BrandProductListing')->where('filters', '(.*)')->name('BrandProductListing');


Route::match(array('GET','POST'),'/new-arrival.html', 'ProductController@NewArriaval')->name('NewArriaval');
Route::match(array('GET','POST'),'/seasonal-specials.html', 'ProductController@SeasonSpecial')->name('SeasonSpecial');
Route::match(array('GET','POST'),'/promotions/dealofweek.html', 'ProductController@Dealofweek')->name('Dealofweek');
Route::match(array('GET','POST'),'/promotions.html', 'HomeController@Promotions')->name('Promotions');
Route::get('/logout', 'CustomerController@Logout')->name('logout');

Route::post('/get_product', 'ProductController@getProductListingAjax')->name('getProductListingAjax');

Route::match(array('GET','POST'),'/{main_catgory}/{child_category}/{sub_childcategory}/cid/{category_id}/{filters?}', 'ProductController@GetCategory')->where('filters', '(.*)')->name('GetCategory');
Route::match(array('GET','POST'),'/{main_catgory}/{child_category}/cid/{category_id}/{filters?}', 'ProductController@GetCategory')->where('filters', '(.*)')->name('GetCategory');
Route::match(array('GET','POST'),'/{main_catgory}/cid/{category_id}/{filters?}', 'ProductController@GetCategory')->where('filters', '(.*)')->name('GetCategory');

Route::get('quick-view-popup', 'PopupController@quickViewPopup')->name('quickviewpopup');

Route::post('checkout-checkemail', 'CheckoutController@checkUserEmail');
Route::post('checkout-usersignup', 'CheckoutController@userSignUp');

/* Below routes are accessible only if the user is logged in, otherwise it will be redirected to the login page. */
Route::middleware(['auth'])->group(function () {
    Route::get('/myaccount.html', 'CustomerController@Myaccount')->name('myaccount');
    Route::get('/sendmail', 'CustomerController@SendMails')->name('sendmail');
    Route::get('/editprofile.html', 'CustomerController@EditProfile')->name('editprofile');
    Route::post('/editprofile.html', 'CustomerController@EditProfile')->name('editprofile');
    Route::get('/changepassword.html', 'CustomerController@ChangePassword')->name('changepassword');
    Route::post('/changepassword.html', 'CustomerController@ChangePassword')->name('changepassword');
    Route::get('/order-history.html', 'CustomerController@OrderHistory')->name('order-history');
    Route::post('/order-history.html', 'CustomerController@OrderHistory')->name('order-history');
    Route::get('/my-project.html', 'CustomerController@MyProject')->name('my-project');
    Route::post('/my-project.html', 'CustomerController@MyProject')->name('my-project');
    Route::get('/order-detail/{id}', 'CustomerController@OrderDetail')->name('order-detail');
    Route::get('/order-detail-print/{id}', 'CustomerController@OrderDetailPrint')->name('order-detail-print');

    Route::get('write-a-testimonial.html', 'TestimonialController@addTestimonial')->name('addTestimonial');
    Route::post('write-a-testimonial.html', 'TestimonialController@postAddTestimonial');
    #Route::post('/email_friend', 'PopupController@EmailFriend')->name('email_friend');

    Route::post('/wishlist_add', 'PopupController@wishlistAdd');
    Route::get('/wish-category.html', 'CustomerController@wishCategory');
    Route::delete('/wish-category.html', 'CustomerController@wishCategory');
    Route::get('/wish-category/{category_id}.html', 'CustomerController@WishCategoryEdit');
    Route::post('/wish-category/{category_id}.html', 'CustomerController@WishCategoryEdit');
    Route::get('/wish-product/{category_id}.html', 'CustomerController@WishProduct');
    Route::delete('/wish-product/{category_id}.html', 'CustomerController@WishProduct');
});


Route::match(array('GET','POST'),'/email_friend', 'PopupController@EmailFriend')->name('email_friend');
Route::match(array('GET','POST'),'/return_order_item', 'PopupController@return_order_item')->name('return_order_item');
Route::get('{category_name}/product/{product_name}/{product_sku}.html', 'ProductDetailController@index')->name('product-detail');

Route::get('{category_name}/product/{product_name}/{product_sku}.html', 'ProductDetailController@index')->name('product-detail');

/** Popup Controller Start **/
Route::post('/popup', 'PopupController@wishlistAdd');
Route::post('/login_register', 'PopupController@LoginRegister');

Route::match(array('GET','POST'),'/newsletter', 'NewsLetterController@newsletter');
Route::match(array('GET','POST'),'/unsubscribe.html', 'NewsLetterController@unSubscribe');

Route::get('about-us', 'StaticPagesController@AboutUS')->name('about-us');
//Route::match(array('GET','POST'),'/contact-us', 'StaticPagesController@contactUs')->name('contact-us');

Route::get('contact-us', 'StaticPagesController@contactUs')->name('contact-us');
Route::post('contact-us', 'StaticPagesController@postContactUs');

Route::match(array('GET','POST'),'/search', 'SearchController@index')->name('Search');

Route::match(array('GET','POST'),'/pages/{id}', 'StaticPagesController@index');
Route::match(array('GET','POST'),'/track-an-order', 'StaticPagesController@TrackOrder')->name('trackorder');

//Route::post('/checkout', 'CheckoutController@Checkout');

Route::get('/cart', 'ShoppingcartController@index');
Route::post('/cart_action', 'ShoppingcartController@cart_action');
Route::match(array('GET','POST'),'checkout', 'CheckoutController@Checkout')->name('checkout');

//Route::get('/calculation', 'ProductDetailController@calculation');
//Route::post('/calculation', 'ProductDetailController@calculation');
Route::post('/cart', 'ShoppingcartController@SetCart');
Route::post('/getcart', 'ShoppingcartController@GetCartHTML');
Route::post('/shopcart', 'ShoppingcartController@cart');

//Route::get('/order-confirm', 'CheckoutController@OrderConfirm');
//Route::post('/order-confirm', 'CheckoutController@OrderConfirm');

Route::get('order-receipt', 'OrderReceiptController@index');
Route::get('order-receipt-print/{orderId}/{customer_id}', 'OrderReceiptController@printOrderReceipt');

Route::get('get-shipping-methods-ajax', 'CheckoutController@getShippingMethods');
Route::post('get-shipping-methods-ajax', 'CheckoutController@getShippingMethods');
Route::post('save_checkoutstep', 'CheckoutController@save_checkoutstep');


Route::post('checkout-order-summary-ajax', 'CheckoutController@checkoutOrderSummaryAjax');
Route::post('avatax-validate-addr-ajax', 'CheckoutController@AvaTaxValidateAddr');

Route::post('checkout-actiononcart', 'CheckoutController@actiononcart');

Route::post('checkout-action', 'OrderProcessController@index'); 
Route::get('order-process', 'OrderProcessController@orderProcess'); 
Route::get('braintree-payment-cc', 'PaymentGatewayController@braintreePaymentCredidCard');
Route::get('sale.html', 'ProductController@ProductSale');
// Route::get('best-seller.html', 'ProductController@ProductBestseller');
Route::get('{main_catgory}/best-seller', 'ProductController@ProductBestseller');
Route::get('{main_catgory}/new-arrival', 'ProductController@ProductCatNewArrivals');
Route::get('{main_catgory}/featured-items', 'ProductController@ProductCatFeaturedItems');


/** Cache Clear Start **/
Route::post('/clearfrontcache', 'FrontCacheController@ClearFrontCache');
Route::post('/clearfrontcurrencycache', 'FrontCacheController@ClearfrontCurrencyCache');
//Route::post('/clearfrontplpcache', 'FrontCacheController@ClearProductListingCache');
Route::get('/clear-clearencel', 'FrontCacheController@ClearClearenceListingCache');
Route::post('/clearfrontmetainfocache', 'FrontCacheController@ClearCacheMetaInfo');
Route::post('/clearfrontcachehomepagebanner', 'FrontCacheController@ClearCacheHomePageBanner');
Route::post('/clearfrontcategoryfocache', 'FrontCacheController@ClearCacheAllCategory');


Route::post('/clearfrontcachebyparentsku', 'FrontCacheController@ClearCacheByParentSku');
Route::post('/clearfrontdealoftheweekscache', 'FrontCacheController@ClearCacheDealOfWeeks');
//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023
Route::post('/clearfrontcacheglobalsettings', 'FrontCacheController@ClearCacheGlobalSetting');
/** Cache Clear End **/
Route::post('/clearfrontcacheinstasettings', 'FrontCacheController@ClearCacheInstagramSetting');
Route::post('/clearfrontcachemenu', 'FrontCacheController@ClearCacheFrontMenu');
Route::post('/clearfrontcachebrandmenu', 'FrontCacheController@ClearCacheFrontBrandMenu');
Route::post('/clearfrontbrandcache', 'FrontCacheController@ClearCacheFrontBrandList');
Route::post('/clearfrontcachecategory', 'FrontCacheController@ClearCacheFrontCategory');
Route::post('/clearfrontcachecategorylist', 'FrontCacheController@ClearCacheFrontCategoryList');
Route::post('/clearfrontcacheproductlist', 'FrontCacheController@ClearCacheFrontProductList');

Route::get('/{category_name}.html', 'CategoryController@index')->name('index');

Route::get('/404', 'ErrorManageController@index');
Route::fallback(function () {
    return redirect('/404');
});
Route::post('/currency', 'CustomerController@ChangeCurrency');

Route::post('getdetailviewproduct', 'ProductDetailController@GetProductDetailView');

/*Route::get('pages/{id}', 'StaticPagesController@index');
Route::post('pages/{id}', 'StaticPagesController@index');
Route::get('contact-us.html', 'StaticPagesController@contactUs')->name('contact-us');
Route::post('contact-us.html', 'StaticPagesController@postContactUs');
Route::get('/track-your-order.html', 'StaticPagesController@TrackOrder')->name('trackorder');
Route::post('/track-your-order.html', 'StaticPagesController@TrackOrder')->name('trackorder');*/



/*Route::get('/register.html', 'App\Http\Controllers\CustomerController@Register')->name('register');
Route::post('/register.html', 'App\Http\Controllers\CustomerController@Register')->name('register');

Route::get('/login.html', 'App\Http\Controllers\CustomerController@Login')->name('login');
Route::post('/login.html', 'App\Http\Controllers\CustomerController@Login')->name('login');

Route::get('/forgot-password.html', 'App\Http\Controllers\CustomerController@ForgotPassword')->name('forgot-password');
Route::post('/forgot-password.html', 'App\Http\Controllers\CustomerController@ForgotPassword')->name('forgot-password');

Route::get('/reset/{token}', 'App\Http\Controllers\CustomerController@resetPasswordPage')->name('reset-password-page');
Route::post('/reset/{token}', 'App\Http\Controllers\CustomerController@resetPassword')->name('reset-password');*/

//Route::get('/', function () {return view('welcome');});
