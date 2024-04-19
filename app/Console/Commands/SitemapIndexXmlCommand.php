<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class SitemapIndexXmlCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:sitemap_xml_index_cron';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sitemap Index Xml Cron';

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
		$site_url = config('const.SITE_URL');
		$xmlfile = public_path() . '/' . 'sitemap_index.xml';
		$fp = fopen($xmlfile, "w+");
		$str = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$str .= '<sitemap><loc>'.$site_url.'/sitemap.xml</loc></sitemap>';
		for($i=1;$i<=10;$i++){
			$xmlfile = public_path() . '/' . 'sitemap_products'.$i.'.xml';
			if(file_exists($xmlfile)){
				$str .= '<sitemap><loc>'.$site_url.'/sitemap_products'.$i.'.xml</loc></sitemap>';
			}
		}
		$str .= "</sitemapindex>";
		//echo $str; exit;
		fwrite($fp, $str);
		fclose($fp);
		
		echo "Sitexml Index Cron run successfully.";
		return true;
	}
}
