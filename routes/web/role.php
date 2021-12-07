<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'backend::'], function () {
    Route::group(['namespace' => 'Backend', 'middleware' => 'auth:web', 'prefix' => 'admin'], function () {
        // # ROLE
        Route::group(['as' => 'roles.', 'prefix' => 'roles'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'RoleController@index']);
            Route::get('/datatables', ['as' => 'datatables', 'uses' => 'RoleController@getDatatable']);
            Route::get('/show/{id}', ['as' => 'showDetail', 'uses' => 'RoleController@showDetail']);
            Route::get('/create', ['as' => 'showCreate', 'uses' => 'RoleController@showCreate']);

            Route::get('/change-status', ['as' => 'changeStatus', 'uses' => 'RoleController@changeStatus']);
            Route::post('/delete', ['as' => 'destroy', 'uses' => 'RoleController@destroy']);
            Route::post('/store', ['as' => 'store', 'uses' => 'RoleController@store']);
            Route::post('/update/{id}', ['as' => 'update', 'uses' => 'RoleController@update']);
        });
    });
});
