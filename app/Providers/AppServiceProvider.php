<?php

namespace App\Providers;
use Session; 
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        // if($this->app->request->input('testmode')){
        //     if($this->app->request->input('testmode')  == 'yes'){
        //         Session::put('testmode','yes');
        //         session()->put(['testmode' => 'yes']);
        //         $request->session()->push('testmode', 'yes');



        //         session()->save();
        //       //  echo Session::get('testmode');
        //     }else{
        //         // Session::forget('testmode');
        //         // echo Session::get('testmode');
        //     }
        // }else{
            
        //     // print_r(Session::all());
        //     //echo Session::get('testmode');
        // }
        //
    }
}
