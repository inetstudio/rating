<?php

namespace InetStudio\Rating\Events\Front;

use Illuminate\Queue\SerializesModels;
use InetStudio\Rating\Contracts\Events\Front\ItemRateChangedContract;

/**
 * Class ItemRateChanged.
 */
class ItemRateChanged implements ItemRateChangedContract
{
    use SerializesModels;

    /**
     * @var
     */
    public $item;

    /**
     * ItemRateChanged constructor.
     *
     * @param   $item
     */
    public function __construct($item)
    {
        $this->item = $item;
    }
}
