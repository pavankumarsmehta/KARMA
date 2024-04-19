<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class ProductSitemapXmlCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:product_sitemap_xml_cron';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sitemap Product Xml Cron';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */


	public function handle()
	{
		$this->sitemapXMLCron();
	}

	private function sitemapXMLCron($start=1,$limit=25000,$page=1)
	{
		
			$productObj = $this->getAllProduct($start,$limit);
			//dd($productObj);
			if($productObj->count() > 0){
				if($limit==1){
					for($i=1;$i<=10;$i++){
						$xmlfile = public_path() . '/' . 'sitemap_products'.$i.'.xml';
						if(file_exists($xmlfile)){
							unlink($xmlfile);	
						}
					}
				}	
				$xmlfile = public_path() . '/' . 'sitemap_products'.$page.'.xml';
				$fp = fopen($xmlfile, "w+");
				$str = "<?xml version='1.0' encoding='UTF-8'?>
				<urlset
				xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'
				xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
				xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9
				http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'>";	
				
				//$productObj->chunk(1000, function($productsObj) {
					//dd($productsObj);
					$productObj = json_decode(json_encode($productObj),true);  
					foreach($productObj as $item) {
						
						$URL = config('const.SITE_URL').'/'.$item['product_url'];
						
						if ($item['product_url'] == "") {
							$site_url = config('const.SITE_URL').'/';
							$product_name = $item['product_name'];
							$sku = $item['product_url'];
							$catName = $item['category_name'];
							
								$URL = $site_url . $catName . '/product/' . $product_name . "/" . strtolower($sku) . ".html";
								$URL = htmlspecialchars($URL);
							
						}
						$str .= "<url>
							<loc>" . $URL . "</loc>
							<changefreq>weekly</changefreq>
							<priority>0.80</priority>
						</url>";
						
					}
				//});

				$str .= "</urlset>";
				//echo $str; exit;
				fwrite($fp, $str);
				fclose($fp);

				################# send the mail regarding the cron executed############
				$headers  = "From: HBA Store products ".$page."  XML Cron <qqualdev@gmail.com>\n";
				$headers .= "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1";
				$message  = "<BR><strong><U>Sitemap XML Generate</U></strong>";
				$xyz = @mail('gequaldev@gmail.com', "HBA Store products ".$page."  XML Generate Cron Job executed", $message, $headers);
				################# send the mail regarding the cron executed############
				$this->sitemapXMLCron(($start*25000),25000,($page+1));
				
				
				// echo "Cron product1 sitexml run failed.";
				// return true;
		}else{	
			echo "Cron product successfully.";				
			return true;
		}
	}
	
	public function getAllProduct($start,$limit) {
		$table_prefix = "hba_";
		try {
			$productObj = DB::table($table_prefix.'products as po')
			->join($table_prefix.'products_category as pc','po.product_id','=','pc.products_id')
			->join($table_prefix.'category as c','pc.category_id','=','c.category_id')
			->leftJoin($table_prefix.'brand as b','po.brand_id','=','b.brand_id')
			->select('po.product_id','po.on_sale','po.sku','po.size','po.skin_type','po.product_name','po.brand_id','po.is_atomizer',
			'po.image_name','po.current_stock','po.retail_price','po.gender','po.new_arrival','po.featured','po.clearance','po.best_seller','po.product_type','po.wholesale_price','po.our_price','po.sale_price','po.display_rank','po.variant','po.product_description','po.product_url','po.image_name','po.stock','po.product_url','pc.category_id','c.parent_id','c.category_name','po.display_rank','po.brand_id','b.brand_name')
			->where('po.current_stock', '>', '0')
			->where('po.status','=','1')
			->where('c.status','=','1')
			->orderBy('po.display_rank')->offset($start)->take($limit)->get();
			
			return $productObj;
		} catch (Throwable $e) {
			report($e);
			return  $productObj = [];		
		}
	}
}
