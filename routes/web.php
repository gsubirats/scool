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

Route::domain('{tenant}.' . env('APP_DOMAIN'))->group(function () {

    Route::group(['middleware' => ['tenant','tenancy.enforce']], function () {

        // Authentication Routes...
        Route::get('login', 'Auth\Tenant\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\Tenant\LoginController@login');
        Route::post('logout', 'Auth\Tenant\LoginController@logout')->name('logout');

        // Registration Routes...
        Route::get('register', 'Auth\Tenant\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\Tenant\RegisterController@register');

        // Password Reset Routes...
        Route::get('password/reset', 'Auth\Tenant\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\Tenant\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\Tenant\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\Tenant\ResetPasswordController@reset');

        Route::get('/', function () {
            return view('tenants.welcome');
        });

        Route::group(['middleware' => 'auth'], function () {
            Route::get('/home', function ($tenant) {
                return view('tenants.home');
            });

            Route::get('/users', 'Tenant\UsersController@show');

            Route::get('/staff', 'Tenant\StaffController@show');

            Route::get('/teachers', 'Tenant\TeachersController@show');

            Route::get('/teachers_photos', 'Tenant\TeachersPhotosController@show');

            Route::get('/teacher_photo/{photo}', 'Tenant\TeacherPhotoController@show');

            Route::post('/teacher_photo', 'Tenant\TeacherPhotoController@store');

        });

        Route::get('/add_teacher', 'Tenant\PendingTeachersController@show');
    });

    Route::group(['middleware' => 'tenant'], function () {
        Route::get('prova',function ($tenant) {
            return 'HI!!!!';
        });

        Route::get('test', function ($tenant) {
            return view('tenants.test',['tenant' => get_tenant($tenant)]);
        });
    });


    Route::get('/test_controller', 'TenantTestController@index');

});

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');