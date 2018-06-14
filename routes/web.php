<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
   
    return redirect()->route('admin.dashboard');
});*/


//Route::get('/', function () {
//    //return view('welcome');
//    return view('front.home');
//
//});

$this->group(['middleware'=>'FrontData'], function () {

	$this->get('/terms-conditions',function () {
		return View("front.page.front-template");
	});
	$this->get('/privacy-policy',function () {
		return View("front.page.front-template");
	});
});

$this->group(['namespace' => 'Front','middleware'=>'FarmerLoggedIn'], function () {
	
	$this->get('/settings', 'FrontController@editSettings')->name('front.page.settings');
	$this->post('/settings', 'FrontController@updateSettings')->name('front.update.settings');
	$this->post('/settings-amenity', 'FrontController@amenitySettings')->name('front.amenity.settings');
	$this->post('/calendar-days', 'FrontController@updateNonAvailibilityDays')->name('front.calendar');
	$this->post('/settings-banner', 'FrontController@bannerSettings')->name('front.banner.settings');

	$this->get('/logout', 'FrontController@logout')->name('front.logout');

	$this->get('/my-profile', 'FrontController@profile')->name('front.page.profile');
	$this->post('/my-profile', 'FrontController@updateprofile')->name('front.post.profile');

	$this->get('/change-password', 'FrontController@changePassword')->name('front.login.changePassword');
	$this->post('/change-password', 'FrontController@postchangePassword')->name('front.post.changePassword');

});

// Route::post('/get-states-list', 'UserController@getStatesList')->name('front.get.statesList');
$this->group(['namespace' => 'Front'], function () {
	$this->post('/contact-form', 'FrontController@contactForm')->name('front.post.contactForm');
	$this->get('/', 'FrontController@home')->name('front.home');
	$this->get('/home', 'FrontController@home')->name('front.home');
	$this->get('/signin', 'FrontController@signin')->name('front.login.signin');
	$this->post('/signin', 'FrontController@makelogin')->name('front.post.signin');
	$this->get('/signup', 'FrontController@signup')->name('front.login.signup');
	$this->get('/forgot-password', 'FrontController@forgotPassword')->name('front.login.forgotPassword');
});


Route::post('/forgot-password', 'Auth\ForgotPasswordController@forgot')->name('front.post.forgotPassword');
Route::post('/signup', 'Auth\LoginController@makeLoginFromSignup')->name('front.post.signup');
Route::get('/email-confirmation/verify/{token}', 'Auth\RegisterController@VerifyUser');
Auth::routes();



$this->group(['prefix' => 'admin', 'namespace' => 'Admin','middleware'=>'AdminLoggedIn'], function () {

	$this->get('/', 'PagesController@dashboard')->name('admin.dashboard');
	$this->get('/dashboard', 'PagesController@dashboard')->name('admin.dashboard');
	$this->get('/logout', 'UserController@logout')->name('admin.logout');
	$this->get('/my-setting', 'UserController@userGetChangePassword')->name('admin.my-settings');
	$this->PATCH('user/change-user-profile-password', 'UserController@userPostChangePassword')->name('users.post.change.profile.password');
	$this->get('/my-profile', "UserController@myProfile")->name('admin.profile');
	$this->PATCH('/my-profile/{slug}', "UserController@updateMyProfile")->name('admin.update.profile');

	$this->resource('settings', 'SettingsController');

	$this->get('/customers', 'UserController@index')->name('users.index');
	$this->post('/customers/datatables', 'UserController@userListWithDatatable')->name('admin.users.datatables');


	$this->get('/customers/{slug}', 'UserController@userView')->name('admin.users.view');
	$this->get('/customers/edit/{slug}', 'UserController@userEdit')->name('admin.users.edit');
	$this->patch('/customers/edit/{slug}', 'UserController@userUpdate')->name('admin.users.update');
	$this->post('/customers/delete/{slug}', 'UserController@userDelete')->name('admin.users.delete');

	$this->post('/users/status-update', 'UserController@userStatusUpdate')->name('admin.users.status.update');	


	$this->get('/farmers', 'FarmerController@index')->name('admin.farmers.index');
	$this->get('/farmers/add', 'FarmerController@create')->name('admin.farmers.add');
	$this->post('/farmers/datatables', 'FarmerController@FarmerListWithDatatable')->name('admin.farmers.datatables');
	$this->post('/farmers/status-update', 'FarmerController@FarmerStatusUpdate')->name('admin.farmers.status.update');
	$this->get('/farmers/{id}', 'FarmerController@show')->name('admin.farmers.view');
	$this->get('/farmers/edit/{id}', 'FarmerController@edit')->name('admin.farmers.edit');
	$this->post('/farmers/edit/{id}', 'FarmerController@update')->name('admin.farmers.update');
	$this->post('/farmers/delete/{id}', 'FarmerController@destroy')->name('admin.farmers.delete');

	$this->get('/amenities', 'AmenitiesController@index')->name('admin.amenities.index');
	$this->post('/amenities/datatables', 'AmenitiesController@AmenityListWithDatatable')->name('admin.amenities.datatables');
	$this->post('/amenities/status-update', 'AmenitiesController@AmenityStatusUpdate')->name('admin.amenity.status.update');
	$this->get('/amenities/add', 'AmenitiesController@create')->name('admin.amenities.add');
	$this->post('/amenities/add', 'AmenitiesController@store')->name('admin.amenities.store');
	$this->get('/amenities/{id}', 'AmenitiesController@show')->name('admin.amenities.view');
	$this->get('/amenities/edit/{id}', 'AmenitiesController@edit')->name('admin.amenities.edit');
	$this->post('/amenities/edit/{id}', 'AmenitiesController@update')->name('admin.amenities.update');
	$this->post('/amenities/delete/{id}', 'AmenitiesController@destroy')->name('admin.amenities.delete');
	
	$this->get('/frontPages', 'FrontPagesController@index')->name('admin.frontPages.index');
	$this->post('/frontPages/datatables', 'FrontPagesController@FrontPageListWithDatatable')->name('admin.frontPage.datatables');
	$this->post('/frontPages/status-update', 'FrontPagesController@FrontPageStatusUpdate')->name('admin.frontPage.status.update');
	$this->get('/frontPages/add', 'FrontPagesController@create')->name('admin.frontPages.add');
	$this->post('/frontPages/add', 'FrontPagesController@store')->name('admin.frontPages.store');
	$this->get('/frontPages/{id}', 'FrontPagesController@show')->name('admin.frontPages.view');
	$this->get('/frontPages/edit/{id}', 'FrontPagesController@edit')->name('admin.frontPages.edit');
	$this->post('/frontPages/edit/{id}', 'FrontPagesController@update')->name('admin.frontPages.update');
	$this->post('/frontPages/delete/{id}', 'FrontPagesController@destroy')->name('admin.frontPages.delete');

	$this->get('/categories', 'CategoryController@index')->name('admin.categories.index');
	$this->get('/categories/add', 'CategoryController@create')->name('admin.categories.add');
	$this->post('/categories/add', 'CategoryController@store')->name('admin.categories.store');
	$this->post('/categories/datatables', 'CategoryController@CategoryListWithDatatable')->name('admin.categories.datatables');
	$this->post('/categories/status-update', 'CategoryController@CategoryStatusUpdate')->name('admin.category.status.update');
	$this->get('/categories/{id}', 'CategoryController@show')->name('admin.categories.view');
	$this->get('/categories/edit/{id}', 'CategoryController@edit')->name('admin.categories.edit');
	$this->post('/categories/edit/{id}', 'CategoryController@update')->name('admin.categories.update');
	$this->post('/categories/delete/{id}', 'CategoryController@destroy')->name('admin.categories.delete');


	$this->get('/banners', 'BannerController@index')->name('admin.banner.index');
	$this->get('/banners/add', 'BannerController@create')->name('admin.banner.add');
	$this->post('/banners/add', 'BannerController@store')->name('admin.banner.store');
	$this->post('/banners/datatables', 'BannerController@BannerListWithDatatable')->name('admin.banner.datatables');
	$this->post('/banners/status-update', 'BannerController@BannerStatusUpdate')->name('admin.banner.status.update');


	$this->get('/banners/{id}', 'BannerController@show')->name('admin.banner.view');
	$this->get('/banners/edit/{id}', 'BannerController@edit')->name('admin.banner.edit');
	$this->post('/banners/edit/{id}', 'BannerController@update')->name('admin.banner.update');
	$this->post('/banners/delete/{id}', 'BannerController@destroy')->name('admin.banner.delete');


	$this->get('/emailTemplates', 'EmailTemplateController@index')->name('admin.emailTemplates.index');
	$this->get('/emailTemplates/add', 'EmailTemplateController@create')->name('admin.emailTemplates.add');
	$this->post('/emailTemplates/add', 'EmailTemplateController@store')->name('admin.emailTemplates.store');
	$this->post('/emailTemplates/datatables', 'EmailTemplateController@emailTemplateListWithDatatable')->name('admin.emailTemplates.datatables');
	$this->post('/emailTemplates/status-update', 'EmailTemplateController@emailTemplateStatusUpdate')->name('admin.emailTemplates.status.update');
	$this->get('/emailTemplates/{id}', 'EmailTemplateController@show')->name('admin.emailTemplates.view');
	$this->get('/emailTemplates/edit/{id}', 'EmailTemplateController@edit')->name('admin.emailTemplates.edit');
	$this->post('/emailTemplates/edit/{id}', 'EmailTemplateController@update')->name('admin.emailTemplates.update');
	$this->post('/emailTemplates/delete/{id}', 'EmailTemplateController@destroy')->name('admin.emailTemplates.delete');


	$this->get('/feedback', 'SubscriberController@index')->name('admin.subscribers.index');
	// $this->get('/subscribers/add', 'SubscriberController@create')->name('admin.subscribers.add');
	// $this->post('/subscribers/add', 'SubscriberController@store')->name('admin.subscribers.store');
	$this->post('/feedback/datatables', 'SubscriberController@SubscribersListWithDatatable')->name('admin.subscribers.datatables');
	$this->post('/feedback/status-update', 'SubscriberController@SubscribersStatusUpdate')->name('admin.subscribers.status.update');
	$this->get('/feedback/{id}', 'SubscriberController@show')->name('admin.subscribers.view');
	$this->get('/subscribers/edit/{id}', 'SubscriberController@edit')->name('admin.subscribers.edit');
	$this->post('/subscribers/edit/{id}', 'SubscriberController@update')->name('admin.subscribers.update');
	$this->post('/subscribers/delete/{id}', 'SubscriberController@destroy')->name('admin.subscribers.delete');

	$this->get('/subscribers/sendEmail/{user_id}/{email_template_sulg}', 'SubscriberController@sendEmail')->name('subscribers.sendEmail');
	
});

$this->group(['prefix' => 'admin', 'namespace' => 'Admin','middleware'=>'AdminBeforeLoggedIn'], function () {

	$this->get('/login', 'UserController@login')->name('admin.login');
	$this->post('/login', 'UserController@makelogin')->name('admin.make.login');

});

$this->group([ 'namespace' => 'Front'], function () {

	$this->get('/user-forgot-password', 'UserController@changePassword')->name('front.forgot.password');
	$this->post('/user-forgot-password', 'UserController@updateChangePassword')->name('front.user.post.updated.password');

});
