<?php

namespace InetStudio\Rating\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RatingBindingsServiceProvider.
 */
class RatingBindingsServiceProvider extends ServiceProvider
{
    /**
    * @var  bool
    */
    protected $defer = true;

    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\Rating\Contracts\Models\RatingModelContract' => 'InetStudio\Rating\Models\RatingModel',
        'InetStudio\Rating\Contracts\Models\RatingTotalModelContract' => 'InetStudio\Rating\Models\RatingTotalModel',
        'InetStudio\Rating\Contracts\Models\Traits\RateableContract' => 'InetStudio\Rating\Models\Traits\Rateable',
        'InetStudio\Rating\Contracts\Services\RatingServiceContract' => 'InetStudio\Rating\Services\RatingService',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return  array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
