<?php

namespace InetStudio\RatingsPackage\Ratings\Http\Controllers\Back;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Back\Resource\IndexRequestContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Back\ResourceControllerContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Back\Resource\IndexResponseContract;

/**
 * Class ResourceController.
 */
class ResourceController extends Controller implements ResourceControllerContract
{
    /**
     * Список объектов.
     *
     * @param  IndexRequestContract  $request
     * @param  IndexResponseContract  $response
     *
     * @return IndexResponseContract
     */
    public function index(IndexRequestContract $request, IndexResponseContract $response): IndexResponseContract
    {
        return $response;
    }
}
