<?php

namespace InetStudio\RatingsPackage\Ratings\Http\Responses\Front;

use InetStudio\RatingsPackage\Ratings\Contracts\Services\ItemsServiceContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Front\RateRequestContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Front\RateResponseContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Events\Front\ItemWasRatedEventContract;
use InetStudio\ACL\Users\Contracts\Services\Front\ItemsServiceContract as UsersServiceContract;

/**
 * Class RateResponse.
 */
class RateResponse implements RateResponseContract
{
    /**
     * @var ItemsServiceContract
     */
    protected $itemsService;

    /**
     * @var UsersServiceContract
     */
    protected $usersService;

    /**
     * @var ItemWasRatedEventContract
     */
    protected $itemWasRatedEvent;

    /**
     * RateResponse constructor.
     *
     * @param  ItemsServiceContract  $itemsService
     * @param  UsersServiceContract  $usersService
     * @param  ItemWasRatedEventContract  $itemWasRatedEvent
     */
    public function __construct(
        ItemsServiceContract $itemsService,
        UsersServiceContract $usersService,
        ItemWasRatedEventContract $itemWasRatedEvent
    ) {
        $this->itemsService = $itemsService;
        $this->usersService = $usersService;
        $this->itemWasRatedEvent = $itemWasRatedEvent;
    }

    /**
     * Возвращаем ответ при получении объектов.
     *
     * @param  RateRequestContract  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $rating = $request->get('rating');

        $this->itemsService->addToRatings($request->get('item'), $rating);

        $this->itemWasRatedEvent->setPayload(
            $request->get('item'),
            $this->usersService->getUserIdOrHash()
        );
        event($this->itemWasRatedEvent);

        $data = [
            'success' => 'success',
            'message' => trans('ratings_package_ratings::messages.rated'),
        ];

        return response()->json($data);
    }
}
