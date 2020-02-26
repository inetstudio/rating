<?php

namespace InetStudio\RatingsPackage\Ratings\Events\Front;

use InetStudio\RatingsPackage\Ratings\Contracts\Events\Front\ItemWasUnRatedEventContract;

/**
 * Class ItemWasUnRatedEvent.
 */
class ItemWasUnRatedEvent implements ItemWasUnRatedEventContract
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
