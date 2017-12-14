<?php

namespace InetStudio\Rating\Observers;

use InetStudio\Rating\Events\ModelWasRated;
use InetStudio\Rating\Events\ModelWasUnRated;
use InetStudio\Rating\Contracts\Models\RatingModelContract;
use InetStudio\Rating\Contracts\Services\RatingServiceContract;

class RatingObserver
{
    /**
     * Handle the created event for the model.
     *
     * @param RatingModelContract $rating
     */
    public function created(RatingModelContract $rating)
    {
        event(new ModelWasRated($rating->rateable, $rating->user_id));
        app(RatingServiceContract::class)->updateRating($rating->rateable, $rating->rating);
    }

    /**
     * Handle the deleted event for the model.
     *
     * @param RatingModelContract $rating
     */
    public function deleted(RatingModelContract $rating)
    {
        event(new ModelWasUnRated($rating->rateable, $rating->user_id));
        app(RatingServiceContract::class)->updateRating($rating->rateable, -($rating->rating));
    }
}
