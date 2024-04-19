<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPages;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Route;
use Session;
use Cookie;
use DB;
use App\Http\Controllers\Traits\generalTrait;
use App\Models\MetaInfo;

class StaticPagesController extends Controller
{

	use generalTrait;
	public function __construct()
	{
		//$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$SITE_URL = config('const.SITE_URL') . "/";
		$Page = Route::currentRouteName();
		$this->PageData['JSFILES'] 			= ['static.js'];
		$recordsCount = StaticPages::where('name', '=', $request->id)->count();

		$this->PageData['CANONICAL_URL']	= route('home') . '/' . Route::current()->uri;

		if (trim($request->id)) {

			if (!empty($request->id) && is_string($request->id)) {
				$request->id = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $request->id);
			}
			
				
				//$Content = str_replace('{$Site_URL}',config('global.SITE_URL'),$Content);
			


			###################### BREADCRUMB START ####################
			/*$breadcrumb = generateBreadcrumbForStaticPages($request->id);
			if ($breadcrumb != false) {
				$this->PageData['breadcrumb'] = $breadcrumb;
			}*/
			###################### BREADCRUMB END ####################

			$StaticPageRes = StaticPages::select('name', 'title', 'content', 'meta_title', 'meta_keywords', 'meta_description')
				->where('name', '=', $request->id)
				->where('status', '=', '1')
				->first();
			//dd($StaticPageRes);

			if (empty($StaticPageRes)) {
				return redirect($SITE_URL);
			}
			$StaticPage = $StaticPageRes;
			//dd($StaticPage);

			## Canonical
			$canonical_url	= '';
			if ((trim($request->id) == 'about-us') || (trim($request->id) == 'site-map')) {
				$canonical_url 	= $SITE_URL . $request->id;
			} else {
				$canonical_url 	= $SITE_URL . "pages/" . $request->id;
			}


			//$view = 'staticPages.comingsoon';

			if (trim($request->id) == 'press')
				$view 		= 'staticPages.press';

			$this->PageData['CSSFILES'] = ['static.css'];

			$PageType = 'NR';
			$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();

			if (!empty($StaticPage->meta_title)) {
				$this->PageData['meta_title'] =  stripslashes($StaticPage->meta_title);
			} else {
				$meta_title = $MetaInfo[0]->meta_title;
				$this->PageData['meta_title'] = $meta_title;
			}


			if (!empty($StaticPage->meta_description)) {
				$this->PageData['meta_description'] =  stripslashes($StaticPage->meta_description);
			} else {
				$meta_description = $MetaInfo[0]->meta_description;
				$this->PageData['meta_description'] = $meta_description;
			}

			if (!empty($StaticPage->meta_keywords)) {
				$this->PageData['meta_keywords'] =  stripslashes($StaticPage->meta_keywords);
			} else {
				$meta_keywords = $MetaInfo[0]->meta_keywords;
				$this->PageData['meta_keywords'] = $meta_keywords;
			}


			if($StaticPage->name=="faqs")
			{
				$content = json_decode($StaticPage->content,true);
				
				$this->PageData['pageContent'] = ($content);
			}
			else{
				$content = str_replace('{$Site_URL}', config('Settings.SITE_URL'), $StaticPage->content);
				$MainCatLink = "";
				if($StaticPage->name=="site-map")
				{
					$menu_array = GetFrontMegaMenu();
					if(isset($menu_array) && !empty($menu_array)){
						foreach($menu_array as $maincat)
						{
							if($maincat['parent_id'] == 0)
								$MainCatLink.='<li><a href="'.$maincat['menu_link'].'">'.ucwords(strtolower($maincat['menu_title'])).'</a></li>';
						}
						$content = str_replace('{$site_map_cat_str}',$MainCatLink,$content);
					}else{
						$content = str_replace('{$site_map_cat_str}',"",$content);
					}
				}
				$this->PageData['pageContent'] = stripcslashes($content);
			}
			$this->PageData['breadcrumb'] = $StaticPage->title;

			###################### BREADCRUMBLIST SCHEMA START ####################
			// $organizationSchemaData = getOrganizationSchema($MetaInfo);
			// if ($organizationSchemaData != false) {
			// 	$this->PageData['organization_schema'] = $organizationSchemaData;
			// }
			$breadcrumbListSchemaData = getBLSchemaForStaticPages($StaticPage->title);
			if ($breadcrumbListSchemaData != false) {
				$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
			}
			###################### BREADCRUMBLIST SCHEMA END ####################		
			
			return view('staticPages.staticpage', compact('StaticPage', 'canonical_url'))->with($this->PageData);
		}
		return redirect(config('const.SITE_URL'));
	}

	public function contactUs(Request $request)
	{
		$this->PageData['JSFILES'] 			= ['contactus.js'];
		$this->PageData['CSSFILES'] = ['static.css'];
		$this->PageData['CANONICAL_URL']	= route('contact-us');
		$SITE_URL = config('const.SITE_URL') . "/";

		$recordsCount = StaticPages::where('name', '=', 'contact-us')->count();

		$left_content = '';
		if ($recordsCount > 0) {
			$records = StaticPages::where('name', '=', 'contact-us')->where('status', '=', '1')->first();
			if (empty($records)) {
				return redirect($SITE_URL);
			}

			$PageType = 'NR';
			$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();

			if (!empty($records->meta_title)) {
				$this->PageData['meta_title'] =  $records->meta_title;
			} else {
				$this->PageData['meta_title'] = stripslashes($MetaInfo[0]->meta_title);
			}

			if (!empty($records->meta_description)) {
				$this->PageData['meta_description'] =  stripslashes($records->meta_description);
			} else {
				$meta_description = $MetaInfo[0]->meta_description;
				$this->PageData['meta_description'] = $meta_description;
			}

			if (!empty($records->meta_keywords)) {
				$this->PageData['meta_keywords'] =  stripslashes($records->meta_keywords);
			} else {
				$meta_keywords = $MetaInfo[0]->meta_keywords;
				$this->PageData['meta_keywords'] = $meta_keywords;
			}


			// $this->PageData['meta_title'] 		= stripslashes($records->meta_title);			
			// $this->PageData['meta_description'] = stripslashes($records->meta_description);			
			// $this->PageData['meta_keywords'] 	= stripslashes($records->meta_keywords);

			$left_content = str_replace('{$SITE_URL}', config('Settings.SITE_URL'), stripslashes($records->content));
			$left_content = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $left_content);
			$left_content = str_replace('{$ADDRESS}', config('Settings.ADDRESS'), $left_content);
			$left_content = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $left_content);
		}

		###################### BREADCRUMBLIST SCHEMA START ####################
		// $organizationSchemaData = getOrganizationSchema($MetaInfo);
		// if ($organizationSchemaData != false) {
		// 	$this->PageData['organization_schema'] = $organizationSchemaData;
		// }
		$breadcrumbListSchemaData = getBLSchemaForStaticPages('Contact Us');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
		//dd($this->PageData);
		return view('staticPages.contactus', compact('left_content'))->with($this->PageData);
	}

	public function AboutUS(Request $request)
	{
		$this->PageData['CSSFILES'] = ['static.css'];
		$this->PageData['CANONICAL_URL']	= route('about-us');
		$SITE_URL = config('const.SITE_URL') . "/";

		$recordsCount = StaticPages::where('name', '=', 'about-us')->count();
		$bottom_content = $left_content = '';
		if ($recordsCount > 0) {
			$records = StaticPages::where('name', '=', 'about-us')->where('status', '=', '1')->first();
			if (empty($records)) {
				return redirect($SITE_URL);
			}

			$PageType = 'NR';
			$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();

			if (!empty($records->meta_title)) {
				$this->PageData['meta_title'] =  $records->meta_title;
			} else {
				$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			}

			if (isset($records->meta_description) && !empty($records->meta_description)) {
				$this->PageData['meta_description'] =  stripslashes($records->meta_description);
			} else {
				$meta_description = $MetaInfo[0]->meta_description;
				$this->PageData['meta_description'] = $meta_description;
			}

			if (isset($records->meta_keywords) && !empty($records->meta_keywords)) {
				$this->PageData['meta_keywords'] =  stripslashes($records->meta_keywords);
			} else {
				$meta_keywords = $MetaInfo[0]->meta_keywords;
				$this->PageData['meta_keywords'] = $meta_keywords;
			}
		}



		###################### BREADCRUMBLIST SCHEMA START ####################
		// $organizationSchemaData = getOrganizationSchema($MetaInfo);
		// if ($organizationSchemaData != false) {
		// 	$this->PageData['organization_schema'] = $organizationSchemaData;
		// }
		$breadcrumbListSchemaData = getBLSchemaForStaticPages('About Us');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	

		return view('staticPages.aboutus', compact('bottom_content', 'left_content'))->with($this->PageData);
	}

	public function postContactUs(Request $request)
	{
		
		
		$request->validate([
			'email'		=> 'required|email',
			'fname'		=> 'required',
			'lname'		=> 'required',
			'note'		=> 'required',
			'customer_phone'		=> 'required',
			'g-recaptcha-response' => 'required|captcha'
		]);


		$to_email 	= config('Settings.ADMIN_MAIL');
		$from_email = $request->email; //CONTACT_MAIL; 

		$comments  = stripslashes(nl2br(strtr($request->note, array('\r' => chr(13), '\n' => chr(10)))));
		$comments  = str_replace("<br />", "", strip_tags($comments));


		//mail code start

		$mail_body = '';
		$Template = GetMailTemplate("CONTACT_US");

		/*$mail_body .= '
		<tr>
			<td height="10">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">
				<span style="font-family:Arial; font-weight:600; color:#000000; font-size:30px;">Contact From ' . ucfirst($request->fname) . ' </span>
			</td>
		</tr>
		<tr>
			<td height="30">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table style="width:100%; margin:0 auto;  font-family: Arial,Helvetica,sans-serif; font-size:14px; line-height:24px;" cellspacing="4" cellpadding="4" border="0" align="center">
					<tbody>
						<tr>
							<td>
								<strong>Customer Name: </strong>' . ucfirst($request->fname) . ' ' . ucfirst($request->lname) . '
							</td>
						</tr>
						<tr>
							<td>
								<strong>Email: </strong>' . $request->email . '
							</td>
						</tr>
						<tr>
							<td>
								<strong>Phone: </strong>' . $request->customer_phone . '
							</td>
						</tr>
						<tr>
							<td>
								<strong>Subject: </strong>' . $request->subject . '
							</td>
						</tr>
						<tr>
							<td>
								<strong>Comments: </strong>' . $comments . '
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>';*/
		$Template = GetMailTemplate("CONTACT_US");
		$Subject = stripslashes($Template[0]["subject"]);
		$mail_body = stripslashes($Template[0]["mail_body"]);
		$EmailBody = $mail_body;
		$EmailBody = str_replace('{$SITE_URL}', config('Settings.SITE_URL'), $EmailBody);
		$EmailBody = str_replace('{$vFirstName}', ucfirst($request->fname), $EmailBody);
		$EmailBody = str_replace('{$vLastName}', ucfirst($request->lname), $EmailBody);
		$EmailBody = str_replace('{$Customer_Email}', $request->email, $EmailBody);
		$EmailBody = str_replace('{$Customer_Phone}', $request->customer_phone, $EmailBody);
		$EmailBody = str_replace('{$Style_Number}', $request->style_number, $EmailBody);
		$EmailBody = str_replace('{$Subject}', $request->subject, $EmailBody);
		$EmailBody = str_replace('{$comments}', $comments, $EmailBody);
		$EmailBody = str_replace('{$CONTACT_MAIL}', config('Settings.CONTACT_MAIL'), $EmailBody);


		$EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
		//echo $EmailBody; exit;
		
		$From = config('Settings.CONTACT_MAIL');
		$To = config('Settings.CONTACT_MAIL');
		
		//$Subject = $Template[0]->subject;
		//echo $EmailBody; exit;
		$sendMailStatus = $this->sendSMTPMail($To, $Subject, $EmailBody, $From);
		$sendMailStatus = $this->sendSMTPMail('sachin.qualdev@gmail.com', $Subject, $EmailBody, $From);
		// $sendMailStatus = $this->sendSMTPMail('janak.qualdev@gmail.com', $Subject, $EmailBody, $From);
		/*$headers  = "From: " . strip_tags($From) . "\r\n";
		$headers .= "Reply-To: " . strip_tags($From) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$sendMailStatus = $this->sendSMTPMail_Normal('sachin.qualdev@gmail.com', $Subject, $EmailBody, $headers);*/
		
		
		return redirect()
			->back()
			->withInput()
			->with('success_msg', 'Your email has been sent succesfully.');
	}

	public function TrackOrder(Request $request)
	{
		
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$this->PageData['active'] = 'orderhistory';
		//$this->PageData['meta_title'] = 'Order Detail';
		$PageType = 'NR';
		$MetaInfo = MetaInfo::where('type', '=', $PageType)->get();
		if ($MetaInfo->count() > 0) {
			$this->PageData['meta_title'] = $MetaInfo[0]->meta_title;
			$this->PageData['meta_description'] = $MetaInfo[0]->meta_description;
			$this->PageData['meta_keywords'] = $MetaInfo[0]->meta_keywords;
		}

		if ($request->action && $request->action == 'ordertrack') {
		
			$order_id = $request->ordernumber;
			$bemail = $request->orderbillingemail;

			if (Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0) {
				$OrdResult = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))
					->where('customer_id', '=', Session::get('sess_icustomerid'))->where('ship_email', '=', trim($bemail))->where('order_id', '=', $order_id)->get();
			} else {
				$OrdResult = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))
					->where('ship_email', '=', trim($bemail))->where('order_id', '=', $order_id)->get();
			}
			
			if ($OrdResult->count() <= 0) {
				return redirect()->back()
					->withInput()
					->withErrors([
						'Failed' => 'Sorry, the order number and billing email provided does not match with details we have. Please try again.',
					]);
			}

			$OrderDetailRs = OrderDetail::select('*')->where('order_id', '=', $OrdResult[0]['order_id'])->orderBy('order_detail_id', 'DESC')->get();
			
			## Set images in order detail arr
			for ($p = 0; $p < count($OrderDetailRs); $p++) {
				$prod_res = Product::select('image_name')->where(DB::raw('lower(sku)'), '=', strtolower(trim($OrderDetailRs[$p]->product_sku)))->limit(1)->get();
				$image_name = $prod_res[0]->image_name;
				
				//dd($prod_res);
				$main_image_thumb = Get_Product_Image_URL($image_name,'THUMB');
				
				if (isset($main_image_thumb))
					$thumb_image = $main_image_thumb;
				else
					$thumb_image =  config('const.NO_IMAGE_300');
				
				//dd($thumb_image);
				$OrderDetailRs[$p]->Image = '<img src="' . $thumb_image . '" alt="' . $OrderDetailRs[$p]['product_name'] . '" border="0" width="170" height="170" />';
			}
			
			$this->PageData['OrderDetailRs'] = $OrderDetailRs;
			$this->PageData['OrderRs'] = $OrdResult;
			$this->PageData['Istrack'] = false;
			return view('myaccount.orderdetail')->with($this->PageData);
		}

		$StaticPageRes_TrackContent = StaticPages::select('static_pages_id','name', 'title', 'content', 'meta_title', 'meta_keywords', 'meta_description')
				->where('status', '=', '1')
				->where('static_pages_id', '=', 35)
				->first();
		
		###################### BREADCRUMBLIST SCHEMA START ####################
		// $organizationSchemaData = getOrganizationSchema($MetaInfo);
		// if ($organizationSchemaData != false) {
		// 	$this->PageData['organization_schema'] = $organizationSchemaData;
		// }
		$breadcrumbListSchemaData = getBLSchemaForStaticPages('Track Your Order');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################

		$this->PageData['JSFILES'] = ['trackorder.js'];
		$this->PageData['TrackContent'] = $StaticPageRes_TrackContent;

		return view('staticPages.trackorder')->with($this->PageData);
	}
	
}

