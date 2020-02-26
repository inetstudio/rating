<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Back',
        'middleware' => ['web', 'back.auth'],
        'prefix' => 'back/ratings-package',
    ],
    function () {
        Route::any('ratings/data/index', 'DataControllerContract@getIndexData')
            ->name('back.ratings-package.ratings.data.index');

        Route::resource(
            'ratings',
            'ResourceControllerContract',
            [
                'only' => [
                    'index',
                ],
                'as' => 'back.ratings-package',
            ]
        );
    }
);

Route::group(
    [
        'namespace' => 'InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Front',
        'middleware' => ['web'],
    ],
    function () {
        Route::post('/ratings-package/ratings/rate/{rate}/{type}/{id}', 'ItemsControllerContract@rate')->name('front.ratings-package.ratings.rate');
    }
);
