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
    // Login, Logout, Forgot, Refresh Token, Register
    Route::post('/login', ['as' => 'login', 'uses' => 'LoginController@login']);
    Route::post('/refresh-token', 'LoginController@refresh');
    Route::delete('/logout', 'LoginController@logout')->middleware('auth:api');
    Route::post('/register', ['as' => 'register', 'uses' => 'RegisterController@register']);


    Route::post('merchant/login', ['as' => 'merchant.login', 'uses' => 'MerchantTokenController@issueToken'])
        ->middleware('passport.merchant');
    Route::delete('merchant/logout', ['as' => 'merchant.revoke', 'uses' => 'MerchantTokenController@revokeToken']);

    //================= CUSTOMER LOGIN
    Route::post('customer/login', ['as' => 'customer.login', 'uses' => 'CustomerTokenController@issueToken'])
        ->middleware('passport.customer');
    Route::delete('customer/logout', ['as' => 'customer.revoke', 'uses' => 'CustomerTokenController@revokeToken']);
});
