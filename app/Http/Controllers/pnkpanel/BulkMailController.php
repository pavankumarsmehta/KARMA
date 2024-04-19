<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\NewsLetter;
use DataTables;
use Mail;

class BulkMailController extends Controller
{
    
	public function index(Request $request) {
		$res_cust = Customer::select('first_name', 'last_name', 'email')->orderBy('email')->get();
		$res_news = Customer::select('email')->orderBy('email')->get();
		$pageData['page_title'] = 'Bulk Mail';
		$pageData['meta_title'] = 'Bulk Mail';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Bulk Mail',
				 'url' =>route('pnkpanel.bulkmail.index')
			 ],
			 [
				 'title' => 'Bulk Mail',
				 'url' =>route('pnkpanel.bulkmail.index')
			 ]
		];		
        return view('pnkpanel.promotion.bulkmail.edit', compact('res_cust', 'res_news'))->with($pageData);
    }
	
	public function update(Request $request) {
		// dd($request->all());
		$this->validate($request, [
			'group'	 		=> 'required',
			'usrgroup'		=> 'required_if:group,1',
            'allusrs'		=> 'array|required_if:group,2',
            'allsubscriber' => 'array|required_if:group,4',
            'message_subject' => 'required:string',
            'message_text' 	=> 'required'
		],
		[
        	'group.required' 		=> 'Please Select A Mail Group to Send Bulk Mail / Reminder',
        	'usrgroup.required_if' 	=> 'Please Select a User Group to Send Mail',
        	'allusrs.required_if' 	=> 'Please Select a User(s) to Send Mail',
        	'allsubscriber.required_if' => 'Please Select a Newsletter Client(s) to Send Mail',
        	'message_subject.required' => 'Please Enter Mail Subject',
        	'message_text.required' => 'Please Enter Mail Text',
		]);

		if($request->has('group') && $request->group == '3' && ($request->email == '' || $request->email == null))
		{

			$this->validate($request, [
	            'email' 		=> 'required_if:group,3|email',
			],
			[
	        	'email.required_if' 	=> "Please Enter a User's Email Address to Send Mail",
	        	'email.email' 			=> 'Please select shipping method',
			]);

			// session()->flash('site_common_msg_err', "Please Enter a User's Email Address to Send Mail");
			// return redirect()->back();
			/*return redirect()->back()
			->withInput()
			->withErrors([
				'email' => "Please Enter a User's Email Address to Send Mail",
			]);*/
		}
		$group 				= $request->group;
		$usrgroup 			= $request->usrgroup;

		$allusrs 			= $request->allusrs;
		$allsubscriber 		= $request->allsubscriber;
		$email 				= $request->email;

		$msg_format			= $request->msg_format;
		$message_text 		= stripcslashes($request->message_text);
		$message_subject 	= stripslashes($request->message_subject);
		if(isset($group) && $group == 1) 
		{
			switch($usrgroup) 
			{
				case 1:	
					$db_result = Customer::select('email')->get()->toArray();
					break;
				case 2:		
					$db_result = Customer::select('email')->where('status', '=', '1')->get()->toArray();
					break;
				case 3:		
					$db_result = Customer::select('email')->where('status', '=', '0')->get()->toArray();
					break;
				case 4:		
					$db_result = NewsLetter::select('email')->get()->toArray();
					break;
				case 5:		
					$db_result = NewsLetter::select('email')->where('status', '=', '1')->get()->toArray();
					break;
				case 6:		
					$db_result = NewsLetter::select('email')->where('status', '=', '0')->get()->toArray();
					break;
				default:
					$db_result = [];
					break;
			}

			$rec_count = count($db_result);
			
			if(count($db_result) > 0)
			{
				for($k=0;$k<$rec_count;$k++)
				{
					$to_email = $db_result[$k]["email"];
					if($to_email != "")
					{
						echo $message_text; exit;
						SendMail($message_subject,$message_text,$to_email, config('Settings.CONTACT_MAIL'));
					}
				}
				session()->flash('site_common_msg', "Mails Successfully Sent to Selected Users");
			}
			else
			{	
				session()->flash('site_common_msg_err', "No User Found To Send Mail Please Choose Other User");
			}
		}
		if(isset($group) && $group == 2) 
		{
			Self::sendMailToUsers($allusrs, $message_subject, $message_text);
		}
		if(isset($group) && $group == 3) 
		{
			$email = explode(",",$email);
			Self::sendMailToUsers($email, $message_subject, $message_text);
		}
		if(isset($group) && $group == 4) 
		{
			// dd($allsubscriber);
			Self::sendMailToUsers($allsubscriber, $message_subject, $message_text);
		}
			
		return redirect()->route('pnkpanel.bulkmail.index');

	}

	public function sendMailToUsers($data, $message_subject, $message_text) {
		$endrec = count($data);
		$start = 0;
		$x = $start + $endrec;
		
		for($k=$start;$k<$x;$k++)
		{
			$to_email = $data[$k];

			if($to_email != "")
			{
					/*echo "2<br>to_email : ".$to_email;
					echo "<br>message_subject : ".$message_subject;
					echo "<br>message_text : ".$message_text;continue;*/
					
					// SMTP_Mail_Send($to_email, $message_subject, $message_text, CONTACT_MAIL);
					SendMail($message_subject, $message_text, $to_email, config('Settings.CONTACT_MAIL'));
			}
		} 
		if($x >= count($data))
		{	
			session()->flash('site_common_msg', "Mails Successfully Sent to Selected Users");
		}
		else
		{
			session()->flash('site_common_msg_err', "No User Found To Send Mail Please Choose Other User!");
		}

	}
	
}
