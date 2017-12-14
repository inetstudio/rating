<?php

namespace InetStudio\Rating\Contracts\Models;

/**
 * Interface RatingModelContract
 * @package InetStudio\Rating\Contracts\Models
 */
interface RatingModelContract
{
    /**
     * Полиморфное отношение с остальными моделями.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function rateable();
}
