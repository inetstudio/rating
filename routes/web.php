<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'InetStudio\Rating\Http\Controllers\Back'], function () {
    Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {
        Route::group(['middleware' => 'back.auth'], function () {
            Route::any('rating/data', 'RatingController@data')->name('back.rating.data');
            Route::resource('rating', 'RatingController', ['only' => [
                'index',
            ], 'as' => 'back']);
        });
    });
});

Route::group(
    [
        'namespace' => 'InetStudio\Rating\Contracts\Http\Controllers\Front',
        'middleware' => ['web'],
    ],
    function () {
        Route::post('/rating/{rate}/{type}/{id}', 'ItemsControllerContract@rate')->name('front.rating.rate');
    }
);
