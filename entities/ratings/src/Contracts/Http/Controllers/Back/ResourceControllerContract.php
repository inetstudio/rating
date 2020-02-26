<?php

namespace InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Back;

use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Back\Resource\IndexRequestContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Back\Resource\IndexResponseContract;;

/**
 * Interface ResourceControllerContract.
 */
interface ResourceControllerContract
{
    /**
     * Список объектов.
     *
     * @param  IndexRequestContract  $request
     * @param  IndexResponseContract  $response
     *
     * @return IndexResponseContract
     */
    public function index(IndexRequestContract $request, IndexResponseContract $response): IndexResponseContract;
}
