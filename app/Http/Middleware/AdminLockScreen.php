<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use app\Helpers\Pnkpanel;

class AdminLockScreen
{
    
	/**
	* Handle an incoming request.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, Closure $next, $guard = null)
	{
		if (Auth::guard('pnkpanel')->user() && !$this->shouldPassThrough($request)) {
			if (!Pnkpanel::hasLockoutTime()) {
				if (session('lock-expires-at')) {
					session()->forget('lock-expires-at');
				}
			}
			
			if (Pnkpanel::hasLockoutTime()) {
				if ($lockExpiresAt = session('lock-expires-at')) {
					if ($lockExpiresAt < now()) {
						return redirect()->route('pnkpanel.lockscreen');
					}
				}
			}
			
			if ($request->session()->has('locked'))
			{
				return redirect()->route('pnkpanel.lockscreen');
			}
		}
		
		if (Auth::guard('pnkpanel')->user() && Pnkpanel::hasLockoutTime() && url()->current() != route('pnkpanel.checklockoutsession'))
		{
			session(['lock-expires-at' => now()->addMinutes(Pnkpanel::getLockoutTime())]);
		}
		
		return $next($request);
	}
	
	/**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        //~ $routeName = $request->path();
        //~ $excepts = [
             //~ 'pnkpanel/logout',
              //~ 'pnkpanel/lockscreen',
        //~ ];
        //~ return in_array($routeName, $excepts);
        
       $routeName =  url()->current();
       $excepts = [
			route('pnkpanel.login'),
            route('pnkpanel.logout'),
            route('pnkpanel.lockscreen'),
            route('pnkpanel.checklockoutsession'),
        ];
        return in_array($routeName, $excepts);
    }
}
