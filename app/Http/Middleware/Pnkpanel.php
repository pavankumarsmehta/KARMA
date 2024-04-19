<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Pnkpanel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$redirectTo = route('pnkpanel.login');
        if (Auth::guard('pnkpanel')->guest() && !$this->shouldPassThrough($request)) {
            return redirect()->guest($redirectTo);
        }
          //check if authenticate && second is condition when we need to redirect i.e, 
          if(Auth::guard('pnkpanel')->check() && $request->route()->named('pnkpanel.login') ) {
            return redirect()->route('pnkpanel.dashboard');
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

        $routeName = $request->path();
        $excepts = [
             'pnkpanel/login',
             'pnkpanel/logout',
        ];
        return in_array($routeName, $excepts);
    }
}
