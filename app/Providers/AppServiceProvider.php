<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

         Validator::extend('isValidUser', function($attribute, $value, $parameters)
        {
            $user_id = ( ! empty($parameters)) ? (int) $parameters[0] : 0;
            $row = User::where('id',$user_id)->where('status','1')->first();

            if($row && $row->status == '1')
            {
                return true;
            }
            return false;
        });

         
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
