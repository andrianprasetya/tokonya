<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'backend::'], function () {
    Route::group(['namespace' => 'Backend', 'middleware' => 'auth:web', 'prefix' => 'admin'], function () {
        Route::group(['as' => 'sidebars.', 'prefix' => 'sidebars'], function () {
            // # SIDEBAR
            Route::get('/', ['as' => 'index', 'uses' => 'SidebarController@index']);
            Route::post('menu/{id}/tree', 'SidebarController@tree');
            Route::get('show/{id}', 'SidebarController@show');
            Route::get('showChildForm/{id}', 'SidebarController@showChildForm');
            Route::post('updateChildNode', ['as' => 'updateChildNode', 'uses' => 'SidebarController@updateChildNode']);
            Route::post('updateNode', ['as' => 'updateNode', 'uses' => 'SidebarController@updateNode']);
            Route::post('delete/{id}', ['as' => 'delete', 'uses' => 'SidebarController@delete']);
            //
            Route::get('showCreateRoot/{id}', ['as' => 'showCreateRoot', 'uses' => 'SidebarController@showCreateRoot']);
            Route::post('store', ['as' => 'store', 'uses' => 'SidebarController@store']);
        });
    });
});
