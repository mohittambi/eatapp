<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::group(['namespace'=>'Api'], function () 
    {
    	Route::post('auth/get-api-key', 'UserController@getApiKey');
    });


    Route::group(['middleware' => 'jwt.auth','namespace'=>'Api'], function () 
    {

        //common api's
        Route::post('forgot-password', 'UserController@forgotPassword');
        Route::post('change-password', 'UserController@changePassword');
        Route::post('logout', 'UserController@logout'); 
        //user api's
        Route::get('get-countries', 'UserController@getCountries');
        Route::get('get-categories', 'UserController@getCategories');
        Route::post('get-banners', 'UserController@getBanners');
        Route::post('verify-for-signup-and-get-otp', 'UserController@verifyForSignupAndGetOtp');
        Route::post('sign-up', 'UserController@signup');
        Route::post('login', 'UserController@login');
        Route::post('social-user-check', 'UserController@socialUserCheck');
        Route::post('update-profile', 'UserController@updateProfile');
        Route::post('get-user-profile', 'UserController@getUserProfile');
        Route::post('checkuser', 'UserController@checkuser');
        Route::post('update-password-by-phone-number', 'UserController@updatePasswordByPhoneNumber');
        Route::post('add-business-details', 'UserController@addBusinessDetails');
        Route::post('add-customer-details', 'UserController@addCustomerDetails');
        Route::post('getCategoryId', 'UserController@getCategoryId');
        Route::post('switch-role', 'UserController@switchRole');

        Route::post('add-business-extra-details', 'UserController@addBusinessExtraDetails');


         
    });
