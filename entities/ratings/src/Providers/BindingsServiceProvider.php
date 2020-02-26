<?php

namespace InetStudio\RatingsPackage\Ratings\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\RatingsPackage\Ratings\Contracts\Events\Front\ItemWasRatedEventContract' => 'InetStudio\RatingsPackage\Ratings\Events\Front\ItemWasRatedEvent',
        'InetStudio\RatingsPackage\Ratings\Contracts\Events\Front\ItemWasUnRatedEventContract' => 'InetStudio\RatingsPackage\Ratings\Events\Front\ItemWasUnRatedEvent',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Back\DataControllerContract' => 'InetStudio\RatingsPackage\Ratings\Http\Controllers\Back\DataController',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Back\ResourceControllerContract' => 'InetStudio\RatingsPackage\Ratings\Http\Controllers\Back\ResourceController',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Controllers\Front\ItemsControllerContract' => 'InetStudio\RatingsPackage\Ratings\Http\Controllers\Front\ItemsController',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Back\Data\GetIndexDataRequestContract' => 'InetStudio\RatingsPackage\Ratings\Http\Requests\Back\Data\GetIndexDataRequest',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Back\Resource\IndexRequestContract' => 'InetStudio\RatingsPackage\Ratings\Http\Requests\Back\Resource\IndexRequest',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Front\RateRequestContract' => 'InetStudio\RatingsPackage\Ratings\Http\Requests\Front\RateRequest',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Front\UnRateRequestContract' => 'InetStudio\RatingsPackage\Ratings\Http\Requests\Front\UnRateRequest',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Resources\Back\Resource\Index\ItemResourceContract' => 'InetStudio\RatingsPackage\Ratings\Http\Resources\Back\Resource\Index\ItemResource',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Back\Data\GetIndexDataResponseContract' => 'InetStudio\RatingsPackage\Ratings\Http\Responses\Back\Data\GetIndexDataResponse',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Back\Resource\IndexResponseContract' => 'InetStudio\RatingsPackage\Ratings\Http\Responses\Back\Resource\IndexResponse',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Front\RateResponseContract' => 'InetStudio\RatingsPackage\Ratings\Http\Responses\Front\RateResponse',
        'InetStudio\RatingsPackage\Ratings\Contracts\Http\Responses\Front\UnRateResponseContract' => 'InetStudio\RatingsPackage\Ratings\Http\Responses\Front\UnRateResponse',
        'InetStudio\RatingsPackage\Ratings\Contracts\Listeners\RemoveRatingsListenerContract' => 'InetStudio\RatingsPackage\Ratings\Listeners\RemoveRatingsListener',
        'InetStudio\RatingsPackage\Ratings\Contracts\Models\Scopes\OrderByRatingsScopeContract' => 'InetStudio\RatingsPackage\Ratings\Models\Scopes\OrderByRatingsScope',
        'InetStudio\RatingsPackage\Ratings\Contracts\Models\Scopes\WhereRatedByScopeContract' => 'InetStudio\RatingsPackage\Ratings\Models\Scopes\WhereRatedByScope',
        'InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingModelContract' => 'InetStudio\RatingsPackage\Ratings\Models\RatingModel',
        'InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingTotalModelContract' => 'InetStudio\RatingsPackage\Ratings\Models\RatingTotalModel',
        'InetStudio\RatingsPackage\Ratings\Contracts\Services\Back\DataTables\IndexServiceContract' => 'InetStudio\RatingsPackage\Ratings\Services\Back\DataTables\IndexService',
        'InetStudio\RatingsPackage\Ratings\Contracts\Services\Back\StatisticServiceContract' => 'InetStudio\RatingsPackage\Ratings\Services\Back\StatisticService',
        'InetStudio\RatingsPackage\Ratings\Contracts\Services\ItemsServiceContract' => 'InetStudio\RatingsPackage\Ratings\Services\ItemsService',
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
