<?php

namespace InetStudio\RatingsPackage\Ratings\Events\Front;

use InetStudio\RatingsPackage\Ratings\Contracts\Events\Front\ItemWasRatedEventContract;

/**
 * Class ItemWasRatedEvent.
 */
class ItemWasRatedEvent implements ItemWasRatedEventContract
{
    /**
     * @var
     */
    public $item;

    /**
     * @var
     */
    public $userId;

    /**
     * @param $item
     * @param $userId
     */
    public function setPayload($item, $userId)
    {
        $this->item = $item;
        $this->userId = $userId;
    }
}
