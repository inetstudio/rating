<?php

Route::group(['namespace' => 'InetStudio\Rating\Http\Controllers\Back'], function () {
    Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {
        Route::group(['middleware' => 'back.auth'], function () {
            Route::any('rating/data', 'PagesController@data')->name('back.rating.data');
            Route::resource('rating', 'PagesController', ['only' => [
                'index',
            ], 'as' => 'back']);
        });
    });
});
