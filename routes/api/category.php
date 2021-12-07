<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api::', 'namespace' => 'Api'], function () {
    Route::group(['middleware' => ['auth:super']], function () {
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', ['as' => 'category.index', 'uses' => 'CategoryController@index']);
            Route::post('/create', ['as' => 'category.store', 'uses' => 'CategoryController@store']);
        });
    });
});
