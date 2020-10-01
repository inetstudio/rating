<?php

namespace InetStudio\RatingsPackage\Ratings\Contracts\Listeners;

/**
 * Interface RemoveRatingsListenerContract.
 */
interface RemoveRatingsListenerContract
{
    public function handle($event): void;
}
