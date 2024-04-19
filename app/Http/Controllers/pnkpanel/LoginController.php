<?php

namespace App\Http\Controllers\pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pnkpanel;

class LoginController extends Controller
{
    public function __construct()
    {
		$this->middleware('guest:pnkpanel')->except('getLogout');
    }

    public function getLogin()
    {
		//echo "11"; exit;
        return view('pnkpanel.login.login');
    }

    public function postLogin(Request $request)
	{
	    $this->validator($request);
	    if($this->attemptLoginBcrypt($request)) {
			$request->session()->regenerate();
			return redirect()
	            ->intended(route('pnkpanel.dashboard'))
	            ->with('site_common_msg', 'You are Logged in as Admin!');
		} else if($this->attemptLoginMD5($request)) {
			$request->session()->regenerate();
			return redirect()
	            ->intended(route('pnkpanel.dashboard'))
	            ->with('site_common_msg', 'You are Logged in as Admin!');
		}
	    return $this->loginFailed();
	}
	
    private function validator(Request $request)
	{
		$rules = [
			'email'    => 'required|email',
			'password' => 'required|string|min:4|max:255',
		];
		return $request->validate($rules);
	}
	
	private function attemptLoginBcrypt(Request $request)
	{
		//$credentials = $request->only('email', 'password')->with(['status' => '1']);
		$credentials = ['email' => $request->email, 'password' => $request->password, 'status' => '1'];
		if(auth()->guard('pnkpanel')->attempt($credentials, $request->filled('remember'))) {
	        return true;
	    }
		return false;
	}
	
	private function attemptLoginMD5(Request $request)
	{
		$admin = Pnkpanel::where([
			'email' => $request->email,
			'password' => md5($request->password),
			'status' => '1'
		])->first();

		if ($admin) {
			auth()->guard('pnkpanel')->login($admin, $request->filled('remember'));
			$admin->password = bcrypt($request->password);
			$admin->save();
			return true;
		}
		return false;
	}
	
    private function loginFailed()
    {
		return redirect()
			->back()
			->withInput()
			->with('site_common_msg_err', 'The account sign-in was incorrect or your account is disabled.');
	}
	
    public function getLogout()
	{
		request()->session()->forget('locked', 'uri');
		auth()->guard('pnkpanel')->logout();
		return redirect()
			->route('pnkpanel.login')
			->with('site_common_msg', 'You have been logged out!');
	}
}
