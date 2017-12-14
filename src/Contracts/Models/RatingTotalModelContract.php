<?php

namespace InetStudio\Rating\Contracts\Models;

/**
 * Interface RatingTotalModelContract
 * @package InetStudio\Rating\Contracts\Models
 */
interface RatingTotalModelContract
{
    /**
     * Полиморфное отношение с остальными моделями.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function rateable();
}
