<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api::', 'namespace' => 'Api'], function () {
    Route::group(['middleware' => ['auth:customers']], function () {
        Route::group(['prefix' => 'customer'], function () {
            Route::get('/profile', ['as' => 'customer.profile', 'uses' => 'CustomerController@showProfile']);
            Route::post('/update', ['as' => 'customer.update', 'uses' => 'CustomerController@updateProfile']);
        });
    });
});
