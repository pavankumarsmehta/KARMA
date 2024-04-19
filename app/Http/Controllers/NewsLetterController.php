<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsLetter;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Route;


class NewsLetterController extends Controller
{
	public $successStatus = 200;
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function newsletter(Request $request)
	{	
		//dd($_SERVER); exit;
		//echo config('global.MAILCHIMPALLOW'); exit; 
		//echo "<pre>";print_r($request);echo "</pre>";exit;
		$ChkEmail = NewsLetter::where('email','=',$request['newsletter_email'])->get();
		//echo "<pre>";print_r($ChkEmail);echo "</pre>";exit;
		if($ChkEmail && $ChkEmail->count() > 0)
		{
			//echo 'Your email address is already subscribed.';
			return response()->json(['msg' => 'Your email address is already subscribed.'], $this->successStatus);
		}else{
			$this->validate($request, [
			'newsletter_email'		=> 'required|email',
			]);

			$dataArray = array(
				'email'				=> $request['newsletter_email'],
				'customer_ip'				=> $_SERVER['REMOTE_ADDR'],
				'insert_datetime'	=> date("Y-m-d")
			);
			
			$res = NewsLetter::create($dataArray); 
			if($res)
			{
				//echo 'Thank You! Your email address has been subscribed.';
				return response()->json(['msg' => 'Thank You! Your email address has been subscribed.'], $this->successStatus);
			}
		}
	}
    public function unSubscribe(Request $request)
	{
		
		$this->PageData['META_TITLE']		= "Unsubscribe Newsletter :: ".config('Settings.SITE_TITLE');
		$this->PageData['META_KEYWORDS']	= "Unsubscribe Newsletter";
		$this->PageData['META_DESCRIPTION']	= "Unsubscribe Newsletter";
		$this->PageData['CANONICAL_URL']	= \Request::url();
		//dd($this->PageData['CANONICAL_URL']);
		$this->PageData['JSFILES']			= ['unsubscribe.js'];
		$your_email = $request->your_email;// comming from emails unsubscribe link
		//echo $your_email; exit;
		return view('newsletter.unsubscribe', compact('your_email'))->with($this->PageData);
	}
	
	public function postUnSubscribe(Request $request)
	{
		//echo "11"; exit;
		$your_email = trim($request->your_email);
			
		$result = NewsLetter::select('news_letter_id', 'status')->whereRaw('trim(email) = ?', [trim($your_email)])->first();
		
		if(!empty($result))
		{	
			if($result->status=='1')
			{
				$arrUpdate = array( 'status'	=> '0');
				
				NewsLetter::where('news_letter_id', '=', $result->news_letter_id)				  
						->update($arrUpdate);
				
				$page_msg ="Thank you. Your e-mail address, ".$your_email.", has been successfully removed from our mailing list.";
				session()->flash('success_msg', $page_msg);			
				return redirect()->back();
			
			}
			else
			{
				$page_msg ="Thank you. Your e-mail address, ".$your_email.", has been successfully removed from our mailing list.";				
				session()->flash('success_msg', $page_msg);			
				return redirect()->back();
			}	
				
		}
		else
		{
			$page_msg = "The email address you have entered does not exits in our mailing list, Please enter a correct email address.";
			session()->flash('error_msg', $page_msg);			
			return redirect()->back();
		}
			
	}
}
