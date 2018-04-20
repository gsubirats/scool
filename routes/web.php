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
        Auth::routes();

        Route::get('/', function () {
            return view('tenants.welcome');
        });

        Route::get('/home', function ($tenant) {
            return view('tenants.home');
        });
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

Route::group(['middleware' => 'auth'], function () {
    Route::post('tenant','TenantController@store');
});

Route::auth();

Route::get('/home', 'HomeController@index');
