<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Pnkpanel;

class LockScreenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function lockscreen()
    {
		if (url()->previous() == route('pnkpanel.lockscreen'))
        {
            session(['locked' => 'true']);
        }
        else
        {
            session(['locked' => 'true', 'uri' => url()->previous()]);
        }
        //session(['locked' => 'true', 'uri' => url()->previous()]);
        
        $pageData['meta_title'] = "Locked Session";
        return view('pnkpanel.lockscreen.lockscreen')->with($pageData);
        //return redirect()->route('pnkpanel.locked')->with('site_common_msg', 'Account Locked Successfully!');
        
    }

    public function unlock(Request $request)
    {
        $password = $request->password;
        $this->validate($request, [
            'password' => 'required|string',
        ]);

        if(\Hash::check($password, Pnkpanel::user()->password)){
			$uri = $request->session()->get('uri');
			if ($uri == route('pnkpanel.lockscreen')) {
				$uri = route('pnkpanel.dashboard');
			}
            $request->session()->forget('locked', 'uri', 'lock-expires-at');
            session(['lock-expires-at' => now()->addMinutes(Pnkpanel::getLockoutTime())]);
            //return redirect('/dashboard');
            return redirect($uri)->with('site_common_msg', 'Welcome Back! '); //Pnkpanel::user()->email
        }

        return back()->with('site_common_msg_err', 'Password does not match. Please try again.');
    }
    
    public function checkLockoutSession(Request $request)
    {
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 200;

		if ($lockExpiresAt = session('lock-expires-at')) {
			if ($lockExpiresAt < now()) {
				$success = true;
				$errors = [];
				$messages = ["message" => ['locked session']];
				$response_http_code = 200;
				//$request->session()->flash('site_common_msg_err', 'Session automatically locked after '.Pnkpanel::getLockoutTime().' minutes of inactivity.');
				session()->flash('site_common_msg_err', 'Session automatically locked after '.Pnkpanel::getLockoutTime().' minutes of inactivity.');
			}
		}
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}
}
