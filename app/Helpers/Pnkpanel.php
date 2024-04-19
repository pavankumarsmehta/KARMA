<?php

namespace App\Helpers;
use App\Models\PaymentMethod; 

class Pnkpanel
{
	public static function isLoggedIn()
	{
		return ! is_null(Pnkpanel::user());
	}
	
	public static function user()
    {
        return auth()->guard('pnkpanel')->user();
    }
    
    public static function isSuperAdmin()
    {
        return Pnkpanel::user()->admin_type == 'super admin';
    }
    
    public static function rights()
    {
		return Pnkpanel::user()->rights != '' ? explode(",", Pnkpanel::user()->rights) : [];
	}
	
	public static function hasContollerAccess($controller_name)
	{
		$rights = AdminNavigation::getControllerRights($controller_name);
		if(isset($controller_name) && count($rights) > 0  && count(array_intersect($rights, Pnkpanel::rights())) == 0) {
			return false;
		}
		return true;
	}
	
	public static function hasRouteAccess($route_name)
	{
		$rights = AdminNavigation::getRouteRights($route_name);
		if(isset($route_name) && count($rights) > 0  && count(array_intersect($rights, Pnkpanel::rights())) == 0) {
			return false;
		}
		return true;
	}
    
    public static function isLoginPage()
    {
        //return (request()->route()->getName() == 'pnkpanel.login');
        return request()->routeIs('pnkpanel.login');
    }

    public static function isLogoutPage()
    {
        //return (request()->route()->getName() == 'pnkpanel.logout');
        return request()->routeIs('pnkpanel.logout');
    }
    
	public static function isLockScreenPage()
    {
        //return (request()->route()->getName() == 'pnkpanel.lockscreen');
        return request()->routeIs('pnkpanel.lockscreen');
    }
	
	public static function getLockoutTime()
    {
        return Pnkpanel::user()->lockout_time;
    }

    public static function hasLockoutTime()
    {
        return Pnkpanel::getLockoutTime() > 0;
    }
    
    public static function getAdminNavigation()
    {
		return AdminNavigation::getAdminNavigation();
	}

    public static function getFrontNavigation()
	{
		return FrontNavigation::getFrontNavigation();
	}

    public static function getFrontMegaMenu()
	{
		return FrontNavigation::getFrontMegaMenu();
	}

    public static function payment_method_production_mode() 
    {
        $payment_method_standard = PaymentMethod::where('pm_status', 'Active')->where('pm_group_name', 'PAYMENT_BRAINTREECC')->get();
        $production_mode = 'production';
        if ($payment_method_standard->isEmpty()) {
            $production_mode = '';
        }else{
        foreach($payment_method_standard as $payment_method_key => $payment_method_value){

            $pm_details = $payment_method_value->pm_details;
            if(strpos($pm_details,'sandbox') != false)
             {
                $production_mode = 'sandbox';
                break;
             }   
        }
    }
        return $production_mode;
    }
}
