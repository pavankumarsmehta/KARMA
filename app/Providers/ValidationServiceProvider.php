<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('exists_md5', function ($attribute, $value, $parameters, $validator) {

            // encode md5 value(s)
            if (is_array($value)) {
                $value = array_map(function ($item) {
                    return md5($item);
                }, $value);
            } else {
                $value = md5($value);
            }
        
            // Delegate to `exists:` validator
            return $validator->validateExists($attribute, $value, $parameters);
        });
        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}