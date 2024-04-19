<?php



namespace App\Helpers;



use App\Models\Material;

use App\Models\Shape;

use App\Models\Color;

use App\Models\Style;

use App\Models\Product;

use App\Models\Category;

use App\Models\Brand;

use App\Models\ProductsBrand;

use App\Models\ProductsCategory;

use Session;

use DB;

use Cache;

use View;



class AdminNavigation

{

	public $pushedRouteArr;



	//~ [

	//~ 'rights' => '',

	//~ 'controller' => '',

	//~ 'subController' => [],

	//~ 'label' => '',

	//~ 'icon' => '',

	//~ 'route' => '',

	//~ 'subRoute' => [],

	//~ 'child' => []

	//~ ]



	const NAV_MENU = [

		// Admin Menu

		[

			'rights' => 'admin',

			'controller' => 'AdminController',

			'label' => 'Admin',

			'icon' => 'bx bx-user',

			'route' => 'pnkpanel.admin.list',

			'subRoute' => ['pnkpanel.admin.edit'],

		],

		

		// Customer Menu

		/*[

			'rights' => 'customer',

			'controller' => 'CustomerController',

			'label' => 'Customer',

			'icon' => 'fa fa-users',

			'route' => 'pnkpanel.customer.list',

			'subRoute' => ['pnkpanel.customer.edit'],

		],*/



		// Customer Menu

		/*[

			'label' => 'Customer',

			'icon' => 'fa fa-users',

			'child' => [

				// customer Menu*/

				[

					'rights' => 'customer',

					'controller' => 'CustomerController',

					'label' => 'Customer',

					'icon' => 'fa fa-users',

					'route' => 'pnkpanel.customer.list',

				],

		/*	]

		],*/



		// Store Inventory Menu

		[

			'label' => 'Store Inventory',

			'icon' => 'bx bx-box',

			'child' => [

				// Category & Collection Menu

				/*[

					'label' => 'Category & Brand',

					'child' => [

						// Category Menu

						[

							'rights' => 'category',

							'controller' => 'CategoryController',

							'label' => 'Category',

							'route' => 'pnkpanel.category.list',

						],

						// Brand Menu

						[

							'rights' => 'brand',

							'controller' => 'BrandController',

							'label' => 'Brand',

							'route' => 'pnkpanel.brand.list',

						],

					],

				],*/

				// Front Menu

				/*[

					'rights' => 'frontmenu',

					'controller' => 'FrontmenuController',

					'label' => 'Front menu',

					'route' => 'pnkpanel.frontmenu.list',

				],*/

				// Category Menu

				[

					'rights' => 'category',

					'controller' => 'CategoryController',

					'label' => 'Categories',

					'route' => 'pnkpanel.category.list',
					'subRoute' => ['pnkpanel.category.list','pnkpanel.category.edit']

				],

				// Brand Menu

				[

					'rights' => 'brand',

					'controller' => 'BrandController',

					'label' => 'Brands',

					'route' => 'pnkpanel.brand.list',
					'subRoute' => ['pnkpanel.brand.list','pnkpanel.brand.edit']

				],

				// Site Bottom HTML Menu

				[

					'rights' => 'manufacturer',

					'controller' => 'ManufacturerController',

					'label' => 'Manufacturers',

					'route' => 'pnkpanel.manufacturer.list',
					'subRoute' => ['pnkpanel.manufacturer.list','pnkpanel.manufacturer.edit']

				],

				[

					'rights' => 'product',

					'controller' => 'ProductController',

					'label' => 'Products',

					'route' => 'pnkpanel.product.list',

					'subRoute' => ['pnkpanel.product.edit'],

				],

				// Front Menu

				/*[

					'rights' => 'menulist',

					'controller' => 'FrontmenuController',

					'label' => 'Manage Menu',

					'route' => 'pnkpanel.frontmenu.menulist',

				],*/

				// Site Bottom HTML Menu

				/*[

					'label' => 'Products',

					'child' => [

						[

							'rights' => 'product',

							'controller' => 'ProductController',

							'label' => 'Product List',

							'route' => 'pnkpanel.product.list',

							'subRoute' => ['pnkpanel.product.edit'],

						],

					]

				],*/



				// Import & Export Menu

				[

					'label' => 'Import & Export',

					'child' => [

						// Import Products Menu

						[

							'rights' => 'import',

							'controller' => 'ProductExportImportController',

							'label' => 'Import Products',

							'route' => 'pnkpanel.product.import',

						],

						// Export Products Menu

						[

							'rights' => 'export',

							'controller' => 'ProductExportImportController',

							'label' => 'Export Products',

							'route' => 'pnkpanel.product.export',

						],



						[

							'rights' => 'import',

							'controller' => 'ProductExportImportController',

							'label' => 'Import Stock/Price',

							'route' => 'pnkpanel.product.updateimportproduct_view',

						],



						

						[

							'rights' => 'export',

							'controller' => 'ProductExportImportController',

							'label' => 'Export Stock/Price',

							'route' => 'pnkpanel.product.updateexportproduct_view',

						],

					]

				],



				



			]

		],



		// Store Settings Menu

		[

			'label' => 'Store Settings',

			'icon' => 'bx bx-slider',

			'child' => [

				// Site Global Settings Menu

				[

					'rights' => 'global',

					'controller' => 'SettingsController',

					'label' => 'Site Global Settings',

					'route' => 'pnkpanel.global-setting.index',

				],

				// Currency Setting

				/*[

					'rights' => 'exchange_currency',

					'controller' => 'ExchangeCurrencyController',

					'label' => 'Exchange Currency',

					'route' => 'pnkpanel.exchange_currency.list',

					//'subRoute' => ['pnkpanel.exchange_currency.edit'],

				],*/

				[

					'rights' => 'quotation',

					'controller' => 'QuotationController',

					'label' => 'Request Quotation',

					'route' => 'pnkpanel.manage-quotations.list',

					'subRoute' => ['pnkpanel.manage-quotations.edit'],

					'testmode' => 'yes',

				],

				[

					'rights' => 'newspress',

					'controller' => 'NewsPressController',

					'label' => 'News press',

					'route' => 'pnkpanel.manage-news-press.list',

					'subRoute' => ['pnkpanel.manage-news-press.edit'],

					'testmode' => 'yes',

				],

				// Front Menu

				[

					'rights' => 'menulist',

					'controller' => 'FrontmenuController',

					'label' => 'Manage Menu',

					'route' => 'pnkpanel.frontmenu.menulist',

				],

				// Manage Country Menu

				[

					'label' => 'Manage Country',

					'child' => [

						// Country List Menu

						[

							'rights' => 'country-state',

							'controller' => 'CountryController',

							'label' => 'Country List',

							'route' => 'pnkpanel.country.list',

						],



						// US State List Menu

						[

							'rights' => 'country-state',

							'controller' => 'StateController',

							'label' => 'US State List',

							'route' => 'pnkpanel.state.list',

						],

					]

				],



				// Site Home Page Settings Menu

				[

					'label' => 'Site Home Page Settings',

					'child' => [

						// Home Page Banners Menu

						[

							'rights' => 'home_products',

							'controller' => 'HomePageBannerController',

							'label' => 'Home Page Banners',

							'route' => 'pnkpanel.home-page-banner.list',

							'subRoute' => ['pnkpanel.home-page-banner.edit'],

						],



						// Home Page Products Menu

						[

							'rights' => 'home_products',

							'controller' => 'HomeProductsController',

							'label' => 'Home Page Text & Products',

							'route' => 'pnkpanel.home-products.index',

						],



						// Home Page Categories Menu

						/*[

							'rights' => 'home_products',

							'controller' => 'HomePopularCategoriesController',

							'label' => 'Home Page Categories',

							'route' => 'pnkpanel.home-popular-categories.index',

						],*/



						// Home Page Bottom HTML Menu

						/*[

							'rights' => 'home_products',

							'controller' => 'HomeBottomHtmlController',

							'label' => 'Home Page Text',

							'route' => 'pnkpanel.home-bottom-html.index',

						],*/

						

						// Instagram global settings menu

						[

							'rights' => 'instagram',

							'controller' => 'StoreSettingsController',

							'label' => 'Instagram Global Settings',

							'route' => 'pnkpanel.instagram-settings.edit',

						],

						// Instagram Feed menu

						[

							'rights' => 'instagram',

							'controller' => 'InstagramFeedController',

							'label' => 'Instagram Feeds',

							'route' => 'pnkpanel.instagram-feeds.list',

							'subRoute' => ['pnkpanel.instagram-feeds.edit'],

						],



					]

				],

				//Lookbook Menu

				/*[

					'rights' => 'look_book',

					'controller' => 'LookbookController',

					'label' => 'Lookbook',

					'route' => 'pnkpanel.lookbook.list',

					'subRoute' => ['pnkpanel.lookbook.edit'],

				],*/



				// Site Bottom HTML Menu

				[

					'rights' => 'bottom',

					'controller' => 'BottomHtmlController',

					'label' => 'Site Top Bottom HTML',

					'route' => 'pnkpanel.bottom-html.index',

				],



				// Site Popup HTML Menu

				// [

				// 	'rights' => 'bottom',

				// 	'controller' => '',

				// 	'label' => 'Site Popup HTML',

				// 	'route' => '',

				// ],



				// Email Templates Menu

				[

					'rights' => 'mail',

					'controller' => 'EmailTemplatesController',

					'label' => 'Email Templates',

					'route' => 'pnkpanel.email-templates.list',

					'subRoute' => ['pnkpanel.email-templates.edit'],

				],



				// Site Meta Information Menu

				[

					'rights' => 'meta',

					'controller' => 'MetaInfoController',

					'label' => 'Site Meta Information',

					'route' => 'pnkpanel.meta-info.edit',

				],



				// Static Pages Menu

				[

					'rights' => 'static',

					'controller' => 'StoreSettingsController',

					'label' => 'Static Pages',

					'route' => 'pnkpanel.manage-static-page.list',

					'subRoute' => ['pnkpanel.manage-static-page.edit'],

				],





				// Payment Methods Menu

				[

					'rights' => 'payment',

					'controller' => 'StoreSettingsController',

					'label' => 'Payment Methods',

					'route' => 'pnkpanel.payment-method.list',

					'subRoute' => ['pnkpanel.payment-method.edit'],

				],



				// Shipping Methods Menu

				[

					'rights' => 'shipping',

					'controller' => 'StoreSettingsController',

					'label' => 'Shipping Methods',

					'route' => 'pnkpanel.shipping-method.list',

					'subRoute' => ['pnkpanel.shipping-method.list', 'pnkpanel.shipping-method.edit'],

				],

				// Shipping Methods Menu

				[

					'rights' => 'shipping',

					'controller' => 'StoreSettingsController',

					'label' => 'Shipping Rule',

					'route' => 'pnkpanel.shipping-rule.list',

					'subRoute' => ['pnkpanel.shipping-rule.list', 'pnkpanel.shipping-rule.edit'],

				],

				// Tax Area & Rate Menu

				[

					'rights' => 'tax',

					'controller' => 'StoreSettingsController',

					'label' => 'Tax Area & Rate',

					'route' => 'pnkpanel.tax-area.list',

					'subRoute' => ['pnkpanel.tax-area.edit', 'pnkpanel.tax-area.tax_area_rate_edit'],

				],



				

				// Currency Menu

				// [

				// 	'rights' => 'currency',

				// 	'controller' => 'CurrencyController',

				// 	'label' => 'Currency',

				// 	'route' => 'pnkpanel.manage-currency.list',

				// 	'subRoute' => ['pnkpanel.manage-currency.edit'],

				// ],



				/*[

					'rights' => '',

					'controller' => 'StoreSettingsController',

					'label' => 'Tax Area & Rate',

					'child' => [

						// Tax Area & Rate Menu

						[

							'rights' => 'tax',

							'controller' => 'StoreSettingsController',

							'label' => 'Tax Area & Rate',

							'route' => 'pnkpanel.tax-area.list',

							'subRoute' => ['pnkpanel.tax-area.edit', 'pnkpanel.tax-area.tax_area_rate_edit'],

						],



						// Import Tax Rules & Rates Menu

						[

							'rights' => 'tax',

							'controller' => 'StoreSettingsController',

							'label' => 'Import Tax Rules & Rates',

							'route' => 'pnkpanel.tax-area.import_tax_rules_and_rates',

						]

					]

				],*/



			]

		],



		// Order Management Menu

		[

			'label' => 'Order Management',

			'icon' => 'bx bx-layer',

			'child' => [

				// Order Summary Menu

				[

					'rights' => 'order',

					'controller' => 'OrderSummaryController',

					'label' => 'Order Summary',

					'route' => 'pnkpanel.order-summary',

				],



				// Orders List Menu

				[

					'rights' => 'order_list',

					'controller' => 'OrderController',

					'label' => 'Orders List',

					'route' => 'pnkpanel.order.list',

					'subRoute' => ['pnkpanel.order.details'],

				],

				

				[
					'rights' => 'return_order',
					'controller' => 'OrderController',
					'label' => 'Return Orders',
					'route' => 'pnkpanel.order.return_order',
				],

				

				/*[

					'rights' => 'report',

					'controller' => 'CustomerOrderReportController',

					'label' => 'Customer Orders',

					'route' => 'pnkpanel.customerorder-report.list',

				],*/



			]

		],



		// Reports Menu

		[

			'label' => 'Reports',

			'icon' => 'bx bx-file-blank',

			'child' => [

				// Order Report Menu

				[

					'rights' => 'report',

					'controller' => 'OrderReportController',

					'label' => 'Order Report',

					'route' => 'pnkpanel.order-report.list',

				],



				// Sales Tax Menu

				[

					'rights' => 'report',

					'controller' => 'SalesTaxReportController',

					'label' => 'Sales Tax',

					'route' => 'pnkpanel.salestax-report.list',

				],



				// Shipping Charge Menu

				/*[

					'rights' => 'report',

					'controller' => 'ShippingChargeReportController',

					'label' => 'Shipping Charge',

					'route' => 'pnkpanel.shippingcharge-report.list',

				],*/



				// Customer Orders Menu

				[

					'rights' => 'report',

					'controller' => 'CustomerOrderReportController',

					'label' => 'Customer Orders',

					'route' => 'pnkpanel.customerorder-report.list',

				],



			]

		],



		// Promotions Menu

		[

			'label' => 'Promotions',

			'icon' => 'fa fa-bullhorn',

			'child' => [

				// Deal of the week Menu

				[

					'rights' => 'dealweek',

					'controller' => 'DealWeekController',

					'label' => 'Deal of the week',

					'route' => 'pnkpanel.dealweek.list',

					'subRoute' => ['pnkpanel.dealweek.edit'],

				],

				

				// Discount Coupons Menu

				[

					'rights' => 'coupon',

					'controller' => 'CouponController',

					'label' => 'Discount Coupons ',

					'route' => 'pnkpanel.coupon.list',

					'subRoute' => ['pnkpanel.coupon.edit'],

				],



				// Auto Discount Menu

				[

					'rights' => 'auto_discount',

					'controller' => 'AutoDiscountController',

					'label' => 'Auto Discount',

					'route' => 'pnkpanel.autodiscount.list',

					'subRoute' => ['pnkpanel.autodiscount.edit'],

				],



				// Quantity Discount Menu

				[

					'rights' => 'qty_discount',

					'controller' => 'QuantityDiscountController',

					'label' => 'Quantity Discount',

					'route' => 'pnkpanel.quantitydiscount.list',

					'subRoute' => ['pnkpanel.quantitydiscount.edit'],

				],



				// Bulk Mail Menu

				[

					'rights' => 'bulk_mail',

					'controller' => 'BulkMailController',

					'label' => 'Bulk Mail',

					'route' => 'pnkpanel.bulkmail.index',

				],



				// Newsletter Menu

				[

					'rights' => 'newsletter',

					'controller' => 'NewsLetterController',

					'label' => 'Newsletter',

					'route' => 'pnkpanel.newsletter.list',

					'subRoute' => ['pnkpanel.newsletter.edit'],

				],

			]

		],

		[

			'rights' => 'tradeshow',

			'controller' => 'CustomerController',

			'label' => 'Trade Show',

			'icon' => 'fa fa-bullhorn',

			'testmode' => 'yes',

			'route' => 'pnkpanel.trade-show.list',

		],



	];



	public static function getAdminNavigation()

	{

		$nav_html = [];

		$nav_menu_items = AdminNavigation::NAV_MENU;

		if (isset($nav_menu_items) && is_array($nav_menu_items)) {

			AdminNavigation::renderNavItem($nav_menu_items, $nav_html);

		}

		return implode($nav_html);

	}



	public static function renderNavItem($nav_menu_items, &$nav_html)

	{

		$is_super_admin = Pnkpanel::isSuperAdmin();

		$rights_arr = Pnkpanel::rights();

		$controller_name = getControllerName();

		$CurrentRoute = request()->route()->getName();

		

		foreach ($nav_menu_items as $item) {

			if(!isset($item['testmode'])) {

				$childRightsArray = array();

				AdminNavigation::getChildRightsArray($item, $childRightsArray);

				$rights = $childRightsArray;

				if (isset($item['rights']) && $item['rights'] != '') {

					array_unshift($rights, $item['rights']);

				}



				if ($is_super_admin || count(array_intersect($rights, $rights_arr)) > 0) {

					$routes = $item['subRoute'] ?? [];

					if (isset($item['route']) && $item['route'] != '') {

						array_unshift($routes, $item['route']);

					}

					//dd($routes);

					if (!isset($item['child']) || count($item['child']) == 0) {

						$nav_html[] =  '<li class="' . (in_array($CurrentRoute, $routes) ? 'nav-active' : '') . '"> <a class="nav-link" href="' . ((isset($item['route']) && $item['route'] != '') ? route($item['route']) : 'javascript:void(0);') . '">' . (isset($item['icon']) ? ' <i class="' . $item['icon'] . '" aria-hidden="true"></i>' : '') . ' <span>' . $item['label'] . '</span> </a></li>';

					} else {

						$childRoutesArray = array();

						AdminNavigation::getChildRoutesArray($item, $childRoutesArray);

						$nav_html[] =  '<li class="nav-parent ' . (in_array($CurrentRoute, $childRoutesArray) ? 'nav-expanded nav-active' : '') . '"> <a class="nav-link" href="' . (isset($item['route']) ? route($item['route']) : 'javascript:void(0);') . '">' . (isset($item['icon']) ? ' <i class="' . $item['icon'] . '" aria-hidden="true"></i>' : '') . ' <span>' . $item['label'] . '</span> </a>';

						$nav_html[] =  '<ul class="nav nav-children">';

						AdminNavigation::renderNavItem($item['child'], $nav_html);

						$nav_html[] =  '</ul>';

						$nav_html[] =  '</li>';

					}

				}

		 	}elseif(isset($item['testmode']) && Session::has('testmode')){

				$childRightsArray = array();

				AdminNavigation::getChildRightsArray($item, $childRightsArray);

				$rights = $childRightsArray;

				if (isset($item['rights']) && $item['rights'] != '') {

					array_unshift($rights, $item['rights']);

				}



				if ($is_super_admin || count(array_intersect($rights, $rights_arr)) > 0) {

					$routes = $item['subRoute'] ?? [];

					if (isset($item['route']) && $item['route'] != '') {

						array_unshift($routes, $item['route']);

					}

					//dd($routes);

					if (!isset($item['child']) || count($item['child']) == 0) {

						$nav_html[] =  '<li class="' . (in_array($CurrentRoute, $routes) ? 'nav-active' : '') . '"> <a class="nav-link" href="' . ((isset($item['route']) && $item['route'] != '') ? route($item['route']) : 'javascript:void(0);') . '">' . (isset($item['icon']) ? ' <i class="' . $item['icon'] . '" aria-hidden="true"></i>' : '') . ' <span>' . $item['label'] . '</span> </a></li>';

					} else {

						$childRoutesArray = array();

						AdminNavigation::getChildRoutesArray($item, $childRoutesArray);

						$nav_html[] =  '<li class="nav-parent ' . (in_array($CurrentRoute, $childRoutesArray) ? 'nav-expanded nav-active' : '') . '"> <a class="nav-link" href="' . (isset($item['route']) ? route($item['route']) : 'javascript:void(0);') . '">' . (isset($item['icon']) ? ' <i class="' . $item['icon'] . '" aria-hidden="true"></i>' : '') . ' <span>' . $item['label'] . '</span> </a>';

						$nav_html[] =  '<ul class="nav nav-children">';

						AdminNavigation::renderNavItem($item['child'], $nav_html);

						$nav_html[] =  '</ul>';

						$nav_html[] =  '</li>';

					}

				}

		 	}

		}

	}



	/***** FUNCTION TO HIGHLIGHT ACTIVE NAV MENU ITEM START  *****/

	public static function getChildRoutesArray($item, &$result)

	{

		if (isset($item['child']) && is_array($item['child'])) {

			foreach ($item['child'] as $child) {

				if (isset($child['child']) && count($child['child']) > 0) {

					AdminNavigation::getChildRoutesArray($child, $result);

				} else {

					$routes = $child['subRoute'] ?? [];

					if (isset($child['route']) && $child['route'] != '') {

						array_unshift($routes, $child['route']);

					}

					$result = array_merge($result, $routes);

				}

			}

		}

	}

	/***** FUNCTION TO HIGHLIGHT ACTIVE NAV MENU ITEM END  *****/



	/***** FUNCTION TO SHOW NAV MENU ITEMS BASED ON ACCESS RIGHT START *****/

	public static function getChildRightsArray($item, &$result)

	{

		if (isset($item['child']) && is_array($item['child'])) {

			foreach ($item['child'] as $child) {

				if (isset($child['child']) && count($child['child']) > 0) {

					AdminNavigation::getChildRightsArray($child, $result);

				} else {

					array_push($result, $child['rights']);

				}

			}

		}

	}

	/***** FUNCTION TO SHOW NAV MENU ITEMS BASED ON ACCESS RIGHT END *****/



	/***** FUNCTIONS FOR PREVENT CONTROLLER/ROUTE ACCESS BASED ON ACCESS RIGHT START *****/



	public static function getRouteRights($route_name)

	{

		$nav_menu_items = AdminNavigation::NAV_MENU;

		$result = array();

		if (isset($nav_menu_items) && is_array($nav_menu_items)) {

			foreach ($nav_menu_items as $item) {

				$rights = array();

				AdminNavigation::getChildItemRouteRights($item, $route_name, $rights);

				$result = array_merge($result, $rights);

			}

			return $result;

		}

		return null;

	}



	public static function getChildItemRouteRights($item, $route_name, &$result)

	{

		if ((isset($item['route']) && $item['route'] == $route_name) || (isset($item['subRoute']) && in_array($route_name, $item['subRoute']))) {

			array_push($result, $item['rights']);

		}

		if (isset($item['child']) && count($item['child']) > 0) {

			foreach ($item['child'] as $child) {

				AdminNavigation::getChildItemRouteRights($child, $route_name, $result);

			}

		}

	}



	public static function getControllerRights($controller_name)

	{

		$nav_menu_items = AdminNavigation::NAV_MENU;

		$result = array();

		if (isset($nav_menu_items) && is_array($nav_menu_items)) {

			foreach ($nav_menu_items as $item) {

				$rights = array();

				AdminNavigation::getChildItemControllerRights($item, $controller_name, $rights);

				$result = array_merge($result, $rights);

			}

			return $result;

		}

		return null;

	}



	public static function getChildItemControllerRights($item, $controller_name, &$result)

	{

		if ((isset($item['controller']) && $item['controller'] == $controller_name) || (isset($item['subController']) && in_array($controller_name, $item['subController']))) {

			array_push($result, $item['rights']);

		}

		if (isset($item['child']) && count($item['child']) > 0) {

			foreach ($item['child'] as $child) {

				AdminNavigation::getChildItemControllerRights($child, $controller_name, $result);

			}

		}

	}



	/***** FUNCTIONS FOR PREVENT CONTROLLER/ROUTE ACCESS BASED ON ACCESS RIGHT END *****/

}

