<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

// Backend Routes.
Route::group(['as' => 'backend::'], function () {
    Route::group(['namespace' => 'Backend'], function () {
        // validate customer verification email
        Route::get('/verify-email', ['as' => 'email.customer.verify', 'uses' => 'VerificationCustomerController@verify']);
        // validate merchant verification email
        Route::get('/verify-email-merchant', ['as' => 'email.merchant.verify', 'uses' => 'VerificationMerchantController@verify']);
    });
});
