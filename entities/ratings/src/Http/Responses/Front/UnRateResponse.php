<?php

namespace InetStudio\RatingsPackage\Ratings\Http\Responses\Front;

use InetStudio\RatingsPackage\Ratings\Contracts\Services\ItemsServiceContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Front\UnRateRequestContract;
use InetStudio\ACL\Users\Contracts\Services\Front\ItemsServiceContract as UsersServiceContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Front\UnRateResponseContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Events\Front\ItemWasUnRatedEventContract;

/**
 * Class UnRateResponse.
 */
class UnRateResponse implements UnRateResponseContract
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
     * @var ItemWasUnRatedEventContract
     */
    protected $itemWasUnRatedEvent;

    /**
     * UnRateResponse constructor.
     *
     * @param  ItemsServiceContract  $itemsService
     * @param  UsersServiceContract  $usersService
     * @param  ItemWasUnRatedEventContract  $itemWasUnRatedEvent
     */
    public function __construct(
        ItemsServiceContract $itemsService,
        UsersServiceContract $usersService,
        ItemWasUnRatedEventContract $itemWasUnRatedEvent
    ) {
        $this->itemsService = $itemsService;
        $this->usersService = $usersService;
        $this->itemWasUnRatedEvent = $itemWasUnRatedEvent;
    }

    /**
     * Возвращаем ответ при получении объектов.
     *
     * @param  UnRateRequestContract  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $type = $request->get('type');

        $this->itemsService->removeFromRatings($request->get('item'), $type);

        $this->itemWasUnRatedEvent->setPayload(
            $request->get('item'),
            $this->usersService->getUserIdOrHash()
        );
        event($this->itemWasUnRatedEvent);

        $data = [
            'success' => 'success',
            'message' => trans('ratings_package_ratings::messages.unrated'),
        ];

        return response()->json($data);
    }
}
