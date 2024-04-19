<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class SitemapXmlCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:sitemap_xml_menu_cron';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sitemap Xml Menu Cron';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */


	public function handle()
	{
		$this->sitemapXMLCron();
	}

	private function sitemapXMLCron()
	{
		
		$xmlfile = public_path() . '/' . 'sitemap.xml';
		$fp = fopen($xmlfile, "w+");
		$str = "<?xml version='1.0' encoding='UTF-8'?>
				<urlset
				xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'
				xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
				xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9
				http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'>
					<url>
						<loc>" . config('const.SITE_URL') . "</loc>
						<changefreq>weekly</changefreq>
						<priority>1.00</priority>
					</url>";
			$menu_array = $this->GetFrontMegaMenu();			
			if(count($menu_array) > 0){
				foreach($menu_array as $menu_key => $menu_value){
					if(isset($menu_value) && !empty($menu_value)){
					$URL =  $menu_value['menu_link'];
					$URL = htmlspecialchars($URL);
					$str .= "<url>
								<loc>" . $URL . "</loc>
								<changefreq>weekly</changefreq>
								<priority>0.80</priority>
							</url>";
					}
					if(isset($menu_value['label']) && !empty($menu_value['label'])){
						foreach($menu_value['label'] as $label_key => $label_value){
							if($label_value['menu_title'] != 'Custom Tag Link - Banner Section'){
								foreach($label_value['childs'] as $child_menu_key =>  $child_menu_value){
									if(isset($child_menu_value['menu_link']) && !empty(($child_menu_value['menu_link']))){
										$URL = htmlspecialchars($child_menu_value['menu_link']);
										$str .= "<url>
											<loc>" . $URL . "</loc>
											<changefreq>weekly</changefreq>
											<priority>0.80</priority>
										</url>";
									}
								}
							}
						}
					}
				}
			}
			
			$brand_list = $this->getAllBrand();
			
			$brand_list = json_decode(json_encode($brand_list),true);  
			
			if(isset($brand_list) && !empty($brand_list)){
				foreach($brand_list as $brand_value){
					$str1 = $brand_value['brand_name'];
					//echo $str1."<br>+++++++++<br>";
					$str1 = preg_replace("/[,^!<>@\/()\"&#$*~`{}'?:;.?%]+/", "", trim($str1));
					$str1 = str_replace("  ", " ", strtolower($str1));
					$str1 = str_replace(" ", "-", strtolower($str1));
					$str1 = str_replace("--", "-", strtolower($str1));
					$str1 = str_replace("--", "-", strtolower($str1));
					$brand_value['brand_name'] =  $str1;
					$brand_url = config('const.SITE_URL').'/brand/'.$brand_value['brand_name'].'/brid/'.$brand_value['brand_id'];
					$str .= "<url>
								<loc>" . $brand_url . "</loc>
								<changefreq>weekly</changefreq>
								<priority>0.80</priority>
							</url>";
				}
			}

		// Common menu links -END
		//Front Static Menu
		$URL = config('const.SITE_URL') . '/sale.html';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";

		// Static Site links - START
		$URL = config('const.SITE_URL') . '/pages/about-us';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";

		$URL = config('const.SITE_URL') . '/pages/key-works-discount';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";

		$URL = config('const.SITE_URL') . '/pages/brand-directory';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/pages/coupon-codes';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/pages/refer-a-friend';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/pages/student-discount';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/pages/join-hbasales-experts';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		
		$URL = config('const.SITE_URL') . '/track-an-order';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
				$URL = config('const.SITE_URL') . '/pages/return-and-exchange';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
				$URL = config('const.SITE_URL') . '/pages/faqs';
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/contact-us';		
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/pages/privacy-policy';		
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/pages/shipping-policy';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/pages/cookie-policy';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/pages/terms-and-condition';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/login.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/register.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/forgot-password.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/myaccount.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/myaccount.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/editprofile.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/order-history.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/changepassword.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";
		$URL = config('const.SITE_URL') . '/wish-category.html';				
		$URL = htmlspecialchars($URL);
		$str .= "<url>
					<loc>" . $URL . "</loc>
					<changefreq>weekly</changefreq>
					<priority>0.80</priority>
				</url>";							

																		

			


		// Static Site links - END

		$str .= "</urlset>";
		//echo $str; exit;
		fwrite($fp, $str);
		fclose($fp);

		################# send the mail regarding the cron executed############
		$headers  = "From: HBA store Sitemap XML Cron <qqualdev@gmail.com>\n";
		$headers .= "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1";
		$message  = "<BR><strong><U>Sitemap XML Generate</U></strong>";
		$xyz = @mail('gequaldev@gmail.com', "HBA store Sitemap XML Generate Cron Job executed", $message, $headers);
		################# send the mail regarding the cron executed############
		echo "Cron run successfully.";
		return true;
	}

		function GetFrontMegaMenu()
		{
			//$menu_array = Cache::remember('menu_array', 3600, function() {
				$parentCategories =  DB::table('hba_menu_front')
									->select('menu_title', 'menu_id', 'menu_link', 'rank', 'status','parent_id')
									->where('parent_id', '=', 0)
									->where('status', '=', '1')
									->orderBy('rank', 'ASC')
									->get()->toArray();
				
				$mainArray = [];
				$level = 1;
				if(count($parentCategories) > 0) {
					foreach($parentCategories as $pcKey => $pcValue) {
						$mainArray[$pcKey]['menu_id'] = $pcValue->menu_id;
						$mainArray[$pcKey]['menu_title'] = $pcValue->menu_title;
						$mainArray[$pcKey]['menu_link'] = $pcValue->menu_link;
						$mainArray[$pcKey]['rank'] = $pcValue->rank;
						$mainArray[$pcKey]['status'] = $pcValue->status;
						$mainArray[$pcKey]['parent_id'] = $pcValue->parent_id;
						$parentCategories[$pcKey]->level = $level;
						$labels =  DB::table('hba_menu_front')
									->select('menu_title', 'menu_id', 'menu_link', 'rank', 'status','parent_id')
									->where('parent_id', '=', $pcValue->menu_id)
									->where('is_label', '=', '1')
									->where('status', '=', '1')
									->orderBy('rank', 'ASC')
									->get()->toArray();
						$cat_labels_count =  DB::table('hba_menu_front')
									->select('menu_title', 'menu_id', 'menu_link', 'rank', 'status')
									->where('parent_id', '=', $pcValue->menu_id)
									->where('is_label', '=', '1')
									->where('menu_title', '!=', 'Custom Tag Link - Banner Section')
									->where('status', '=', '1')
									->count();
						$parentCategories[$pcKey]->label_count = count($labels);
						$mainArray[$pcKey]['label_count'] = count($labels);
						$total_columns = 5;
						$display_banners_count = $total_columns - $cat_labels_count;
						$mainArray[$pcKey]['display_banners_count'] = $display_banners_count;
						$labelArray = [];
						if(count($labels) > 0) {
							foreach($labels as $labelKey => $labelVaue) {
								$labelArray[$labelKey]['menu_id'] = $labelVaue->menu_id;
								$labelArray[$labelKey]['menu_title'] = $labelVaue->menu_title;
								$labelArray[$labelKey]['menu_link'] = $labelVaue->menu_link;
								// $labelArray[$labelKey]['is_below'] = $labelVaue->is_below;
								$labelArray[$labelKey]['rank'] = $labelVaue->rank;
								$labelArray[$labelKey]['status'] = $labelVaue->status;
								$labelArray[$labelKey]['parent_id'] = $labelVaue->parent_id;
								$labelArray[$labelKey]['childs'] = array();
								$this->getSubCats($labelVaue->menu_id, $labelArray[$labelKey]['childs'],$level+1);
							}
						}
						$mainArray[$pcKey]['label'] = $labelArray;
					}
				}
				
				$menu_array = $mainArray;
				return $menu_array;
			//});
		}
		function getSubCats($parent_id = 0, &$categoriesArray = array(),$level=0) {
			$allSubCategories =  DB::table('hba_menu_front')
					->select('menu_title', 'menu_id', 'menu_link', 'rank', 'status', 'menu_image', 'menu_image1', 'menu_image2', 'menu_label', 'menu_label1', 'menu_label2', 'menu_custom_link', 'menu_custom_link1', 'menu_custom_link2')
					->where('parent_id', '=', (int)$parent_id)
					->where('is_label', '=', '0')
					->where('status', '=', '1')
					->orderBy('rank', 'ASC')
					->get()->toArray();

			foreach($allSubCategories as $k => $category) {
				$categoriesArray[$k]['menu_id'] = $category->menu_id;
				$categoriesArray[$k]['menu_title'] = $category->menu_title;
				$categoriesArray[$k]['menu_link'] = $category->menu_link;

				$categoriesArray[$k]['menu_label'] = $category->menu_label;
				$categoriesArray[$k]['menu_label1'] = $category->menu_label1;
				$categoriesArray[$k]['menu_label2'] = $category->menu_label2;
				$categoriesArray[$k]['menu_custom_link'] = $category->menu_custom_link;
				$categoriesArray[$k]['menu_custom_link1'] = $category->menu_custom_link1;
				$categoriesArray[$k]['menu_custom_link2'] = $category->menu_custom_link2;
				
				$categoriesArray[$k]['rank'] = $category->rank;
				$categoriesArray[$k]['status'] = $category->status;
				$categoriesArray[$k]['level'] = $level;
				$categoriesArray[$k]['childs'] = array();
				$this->getSubCats($category->menu_id,$categoriesArray[$k]['childs'],$level+1);
			}
		}
		function getAllBrand() {
			try {
					$brandObj = DB::table('hba_products as po')
					->join('hba_brand as b','po.brand_id','=','b.brand_id')
					->select('po.product_id','po.product_name','b.brand_id','b.brand_name','b.brand_description','b.brand_logo_image')
					->where('po.status','=','1')
					->where('b.status','=','1')
					->groupBy('b.brand_name')
					->orderBy('b.brand_name')->get();
				return $brandObj;
			} catch (Throwable $e) {
				report($e);
				return  $brandObj = [];		
			}
		}
}
