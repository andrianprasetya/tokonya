<?php

use Illuminate\Support\Facades\Route;

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
Route::group(['as' => 'api::', 'namespace' => 'Api'], function () {

    Route::post('/register', ['as' => 'register', 'uses' => 'RegisterCustomerController@register']);

    Route::group(['namespace' => 'Auth'], function () {
        //================== SUPERADMIN LOGIN
        Route::post('/login', ['as' => 'login', 'uses' => 'SuperAdminTokenController@issueToken']);
        Route::delete('logout', ['as' => 'revoke', 'uses' => 'SuperAdminTokenController@revokeToken'])
            ->middleware('auth:super');

        //================== MERCHANT LOGIN
        Route::post('merchant/login', ['as' => 'merchant.login', 'uses' => 'MerchantTokenController@issueToken'])
            ->middleware('passport.merchant');

        Route::delete('merchant/logout', ['as' => 'merchant.revoke', 'uses' => 'MerchantTokenController@revokeToken'])
            ->middleware('passport.merchant');

        //================= CUSTOMER LOGIN
        Route::post('customer/login', ['as' => 'customer.login', 'uses' => 'CustomerTokenController@issueToken'])
            ->middleware('passport.customer');

        Route::delete('customer/logout', ['as' => 'customer.revoke', 'uses' => 'CustomerTokenController@revokeToken'])
            ->middleware('passport.customer');
    });

});
