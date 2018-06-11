<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Model\User;
use App\Model\Farmer;

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

        Validator::extend('isValidFarmerCode', function($attribute, $value, $parameters)
        {
            if(empty($parameters[0]))
            {
                return true;
            }
            else{
                $farmer_code = ( ! empty($parameters)) ? $parameters[0] : 0;
                $row = Farmer::where('farmer_code',$farmer_code)->first();

                if($row)
                {
                    if(isset($row->farmer_code) && !empty($row->farmer_code))
                    {
                        return true;
                    }
                }
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
