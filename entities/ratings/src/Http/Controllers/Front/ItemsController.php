<?php

namespace InetStudio\RatingsPackage\Ratings\Http\Controllers\Front;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Front\RateRequestContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Front\RateResponseContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Front\ItemsControllerContract;

/**
 * Class ItemsController.
 */
class ItemsController extends Controller implements ItemsControllerContract
{
    /**
     * Добавляем материал в избранное.
     *
     * @param  RateRequestContract  $request
     * @param  RateResponseContract  $response
     *
     * @return RateResponseContract
     */
    public function rate(RateRequestContract $request, RateResponseContract $response): RateResponseContract
    {
        return $response;
    }
}
