<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api::', 'namespace' => 'Api'], function () {
    Route::group(['middleware' => ['auth:customers']], function () {
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', ['uses' => 'CategoryController@index']);
        });
    });
});
