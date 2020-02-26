<?php

namespace InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Front;

use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Front\RateRequestContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Front\RateResponseContract;

/**
 * Interface ItemsControllerContract.
 */
interface ItemsControllerContract
{
    /**
     * Добавляем материал в избранное.
     *
     * @param  RateRequestContract  $request
     * @param  RateResponseContract  $response
     *
     * @return RateResponseContract
     */
    public function rate(RateRequestContract $request, RateResponseContract $response): RateResponseContract;
}
