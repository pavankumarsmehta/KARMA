<?php
namespace App\Providers;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;
use App\Models\MetaInfo;
use App\Models\HomeProducts;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\shoppingcartTrait;
use App\Models\Category;
use App\Models\Product;
use Cache;
use Cookie;
use Crypt;
class GlobalServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	use generalTrait;
	//use shoppingcartTrait; 
	public function register()
	{
		// Temporary code
		$request = app(\Illuminate\Http\Request::class);
		$debugbar = $request->get('debugbar');
		if (isset($debugbar) && $debugbar != '') {
			Cookie::queue(cookie('tmp_debugbar', $debugbar, $minute = 60));
		} else {
			$debugbar = Cookie::get('tmp_debugbar');
			if (isset($debugbar) && $debugbar != '') {
				$debugbar = Crypt::decryptString($debugbar);
				$debugbar = explode("|", $debugbar)[1];
			}
		}
		if (isset($debugbar) && $debugbar == '1') {
			config(['debugbar.enabled' => 1]);
		}
		/*elseif(isset($debugbar) && $debugbar == '0') {
			config(['debugbar.enabled' => 0]);
		}*/
		view()->composer('*', function ($view) {
			$CurrentRoute = Route::getCurrentRoute();
			if (isset($CurrentRoute->action['as']))
				$view->with('CurrentRoute', $CurrentRoute->action['as']);
			else
				$view->with('CurrentRoute', '');
			
			if (isset($CurrentRoute->action['controller'])) {
				$controllerAction = (explode("\\", $CurrentRoute->action['controller']));
				$controllerAction = end($controllerAction);
				list($CurrentController, $CurrentMethod) = explode('@', $controllerAction);
				$view->with('CurrentController', $CurrentController);
			}
			$request = app(\Illuminate\Http\Request::class);
			$controller = $request->get('controller');
			if (isset($controller) && $controller != '') {
				//dd($view);
				echo $CurrentController . "<br>";
			}
		});
	}
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//Set Dynamic Constants of site_settings table	
		if (Cache::has('settingvars_cache')) {
			$SiteSetting = Cache::get('settingvars_cache');
		} else {
			$Settings = SiteSetting::selectRaw('var_name,setting')->where('status', '=', '1')->get();
			$SiteSetting = array();
			foreach ($Settings as $Setting) {
				$SiteSetting[$Setting->var_name] = $Setting->setting;
			}
			Cache::put('settingvars_cache', $SiteSetting);
		}
		//dd($SiteSetting);
		if (isset($SiteSetting['SHOW_COMING_SOON_PAGE']) && $SiteSetting['SHOW_COMING_SOON_PAGE'] == 'Yes') {
			echo '<link rel="stylesheet" type="text/css" media="all" href="css/bootstrap.css" />
			<main>
			  <div class="tac p-4">
			  <table border="0" align="center" class="tac diblock" cellpadding="0" cellspacing="0">
				<tbody>
				  <tr>
					<td class="pt-5 diblock">
					  <svg class="svg_logo" width="272px" height="74px" aria-hidden="true" role="img"><use href="#svg_logo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_logo"></use></svg>
					</td>
				  </tr>
				  <tr>
					<td valign="top">
					  <table width="100%" class="tac" border="0" cellpadding="0" cellspacing="5">     
						<tbody>
						  <tr>
							<td class="pt-5 diblock"><h1>Site under maintenance !!</h1></td>
						  </tr>      
						  <tr>
							<td class="pt-5 diblock">We will be back soon with our new website.</td>
						  </tr>      
						  <tr>
							<td class="pt-5 diblock">Feel free to contact us with any questions or inquiries.</td>
						  </tr>
						  <tr>
							<td class="pt-5 diblock">Call us at: <b>' . $SiteSetting['TOLL_FREE_NO'] . '</b></td>
						  </tr>
						  <tr>
							<td class="pt-5 diblock">Email us at: <a href="mailto:' . $SiteSetting['CONTACT_MAIL'] . '"><b>' . $SiteSetting['CONTACT_MAIL'] . '</b></a></td>
						  </tr>      
						</tbody>
					  </table>
					</td>
				  </tr>  
				</tbody>
			  </table>
			  </div>
			</main>';
			exit;
		}
		config(['Settings' => $SiteSetting]);
		// General Meta Settings
		if (Cache::has('metainfo_cache')) {
			$MetaInfo = Cache::get('metainfo_cache');
		} else {
			$MetaInfo = MetaInfo::select('meta_title', 'meta_keywords', 'meta_description')->where('type', '=', 'NR')->get();
			Cache::put('metainfo_cache', $MetaInfo);
		}
		
		if (isset($MetaInfo[0]['meta_title']) && !empty($MetaInfo[0]['meta_title']))
			config(["global.META_TITLE" => stripcslashes($MetaInfo[0]['meta_title'])]);
		else
			config(["global.META_TITLE" => $SiteSetting['SITE_TITLE']]);
		
		if (isset($MetaInfo[0]['meta_keywords']) && !empty($MetaInfo[0]['meta_keywords']))
			config(["global.META_KEYWORDS" => stripcslashes($MetaInfo[0]['meta_keywords'])]);
		else
			config(["global.META_KEYWORDS" => $SiteSetting['SITE_TITLE']]);
		
		if (isset($MetaInfo[0]['meta_description']) && !empty($MetaInfo[0]['meta_description']))
			config(["global.META_DESCRIPTION" => stripcslashes($MetaInfo[0]['meta_description'])]);
		else
			config(["global.META_DESCRIPTION" => $SiteSetting['SITE_TITLE']]);
		
	
		if (!Cache::has('metainfo_pagetype_ct')) {
			$MetaInfo = MetaInfo::where('type', '=', 'CT')->get();
			Cache::put('metainfo_pagetype_ct', $MetaInfo);
		}
		
		config(['Footer' => $this->getFooter()]);
		config(['MobileInfo' => 7895988567]);
		config(['EmailInfo' => 'test125@gmail.com']);
		
		// Price Filter Value Dynemic for Listing page and Landing Page..
		$priceArrayFilter = array(
			'0_75' => 'Under $75',
			'75_125' => '$75 to $125',
			'125_175' => '$125 to $175',
			'175_250' => '$175 to $250',
			'250_400' => '$250 to $400',
			'400' => '$400 & Above'
		);
		//config(['priceArrayFilter' => $priceArrayFilter]);
		View::share('priceArrayFilter', getPriceCategoryWise());
		//Front Main menu
		 if (!Cache::has('menu_array')) {
				$menu_array = GetFrontMegaMenu();
				Cache::put('menu_array', $menu_array);
			}else {
				
				$menu_array = Cache::get('menu_array');
			}
			
		View::share('menu_array', Cache::get('menu_array'));


		//Other Categories 
		$LIMIT_OTHER_CATEGORY = config('const.LIMIT_OTHER_CATEGORY');
		//$other_categories_arr = getOtherCategories($LIMIT_OTHER_CATEGORY);
		//View::share('other_categories_arr', $other_categories_arr);
		
		$other_categories_arr = getOtherCategories($LIMIT_OTHER_CATEGORY);
		View::share('other_categories_arr', $other_categories_arr);
		//dd($other_categories_arr_new);

		//Front Brand menu
		if (!Cache::has('brandlist')) {
				$brandlist = BrandsList();
				Cache::put('brandlist', $brandlist);
			} else {
				$brandlist = Cache::get('brandlist');
			}

		View::share('brandlist', Cache::get('brandlist'));

		//Popular brand
		if (!Cache::has('popular_brands')) {
				$popular_brands = getPopularBrands();
				Cache::put('popular_brands', $popular_brands);
			}
		else {
				$popular_brands = Cache::get('popular_brands');
			}
		View::share('popular_brands', Cache::get('popular_brands'));

		
		//Main Category List
		//return count($data->get_all_wishlist());
		if (Cache::has('otherMainCategoryList_cache')) 
		{
			$CategoryObj = Cache::get('otherMainCategoryList_cache');
		} else {
			$CategoryObj = show_other_page_categoryList();
			Cache::put('otherMainCategoryList_cache', $CategoryObj);
		}
		$CategoryArr = json_decode(json_encode($CategoryObj),true);  

		// $test = get_wishlist_count();
		View::share('get_otherMainCategoryList', $CategoryArr);

		$popularBrand  = show_other_page_PopularBrandList();
		View::share('otherCategoryPopularBrandst', $popularBrand);

		if (!Cache::has('currency')) {
			$currency = getCurrencyArray();
			Cache::put('currency', $currency);
		}else {
			$menu_array = Cache::get('currency');
		}
		
		View::share('currency', Cache::get('currency'));
		
	}
}