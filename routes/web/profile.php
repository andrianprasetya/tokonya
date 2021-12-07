<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'backend::'], function () {
    Route::group(['namespace' => 'Backend', 'middleware' => 'auth:web', 'prefix' => 'admin'], function () {
        // # USER PROFILE
        Route::group(['as' => 'profile.', 'prefix' => 'profile'], function () {
            Route::get('/{profileId}', ['as' => 'show', 'uses' => 'ProfileController@showProfile']);
            Route::post('/update/{profileId}', ['as' => 'update', 'uses' => 'ProfileController@update']);
            Route::post('/password', ['as' => 'password', 'uses' => 'ProfileController@updatePassword']);
            Route::post('/remove-media/{id}', ['as' => 'remove.media', 'uses' => 'ProfileController@removeMedia']);
        });
    });
});
