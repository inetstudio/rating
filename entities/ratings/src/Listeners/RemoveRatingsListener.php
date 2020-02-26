<?php

namespace InetStudio\RatingsPackage\Ratings\Listeners;

use InetStudio\RatingsPackage\Ratings\Contracts\Services\ItemsServiceContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Listeners\RemoveRatingsListenerContract;

/**
 * Class RemoveRatingsListener.
 */
class RemoveRatingsListener implements RemoveRatingsListenerContract
{
    /**
     * @var ItemsServiceContract
     */
    protected $itemsService;

    /**
     * AddItemListener constructor.
     *
     * @param  ItemsServiceContract  $itemsService
     */
    public function __construct(ItemsServiceContract $itemsService)
    {
        $this->itemsService = $itemsService;
    }

    /**
     * Handle the event.
     *
     * @param $event
     */
    public function handle($event): void
    {
        $item = $event->item;

        $this->itemsService->removeRatings($item);
    }
}
