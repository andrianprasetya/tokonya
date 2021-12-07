<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api::', 'namespace' => 'Api'], function () {
    Route::group(['middleware' => ['auth:api']], function () {
        // roles
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', ['uses' => 'RoleController@index']);
            Route::post('/create', ['uses' => 'RoleController@create']);
            Route::get('/show/{id}', ['uses' => 'RoleController@show']);
            Route::put('/update', ['uses' => 'RoleController@update']);
            Route::get('/change-status/{id}/{status}', ['uses' => 'RoleController@changeStatus']);
            Route::delete('/delete/{id}', ['uses' => 'RoleController@delete']);
        });
    });
});
