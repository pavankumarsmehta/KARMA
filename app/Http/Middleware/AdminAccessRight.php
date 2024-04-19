<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Helpers\Pnkpanel;

class AdminAccessRight
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
		if (Pnkpanel::isLoggedIn()) {
			if(Pnkpanel::isSuperAdmin()) {
				return $next($request);
			}

			/*if(!Pnkpanel::hasContollerAccess(getControllerName())) {
				 return redirect()->route('pnkpanel.dashboard')->with('site_common_msg_err', config('messages.msg_rights'));
			}*/
			
			if(!Pnkpanel::hasRouteAccess(Route::currentRouteName())) {
				 return redirect()->route('pnkpanel.dashboard')->with('site_common_msg_err', config('messages.msg_rights'));
			}
		}
		
		return $next($request);
	}
}
