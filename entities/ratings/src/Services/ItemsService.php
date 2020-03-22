<?php

namespace InetStudio\RatingsPackage\Ratings\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use InetStudio\AdminPanel\Base\Services\BaseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingModelContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Services\ItemsServiceContract;
use InetStudio\ACL\Users\Contracts\Services\Front\ItemsServiceContract as UsersServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    /**
     * @var UsersServiceContract
     */
    protected $usersService;

    /**
     * ItemsService constructor.
     *
     * @param  UsersServiceContract  $usersService
     * @param  RatingModelContract  $model
     */
    public function __construct(UsersServiceContract $usersService, RatingModelContract $model)
    {
        parent::__construct($model);

        $this->usersService = $usersService;
    }

    /**
     * Добавляем оценку пользователя материалу.
     *
     * @param $item
     * @param $rating
     * @param  null  $userId
     *
     * @return mixed
     */
    public function addToRatings($item, $rating, $userId = null)
    {
        $this->addRatingsRelations($item);
        $userId = $this->usersService->getUserIdOrHash($userId);

        $rate = $item->ratings()
            ->where(
                [
                    'user_id' => $userId,
                ]
            )
            ->first();

        if (! $rate) {
            $item->ratings()
                ->create(
                    [
                        'user_id' => $userId,
                        'rating' => (float) $rating,
                    ]
                );

            $this->updateRating($item, $rating);
        }

        return $item;
    }

    /**
     * Удаляем оценку пользователя у материала.
     *
     * @param $item
     * @param int|null $userId
     *
     * @return mixed
     */
    public function removeFromRatings($item, $userId = null)
    {
        $this->addRatingsRelations($item);
        $userId = $this->usersService->getUserIdOrHash($userId);

        $rate = $item->ratings()
            ->where(
                [
                    'user_id' => $userId,
                ]
            )
            ->first();

        if ($rate) {
            $rating = $rate->rating;
            $rate->delete();

            $this->updateRating($item, -$rating);
        }

        return $item;
    }

    /**
     * Получаем рейтинг материала в процентах.
     *
     * @param $item
     * @param int $max
     *
     * @return float
     */
    public function getRatingPercent($item, int $max = 5)
    {
        $this->addRatingsRelations($item);
        $average = $this->getRatingAverage($item);

        return $average * 100 / $max;
    }

    /**
     * Получаем средний рейтинг материала.
     *
     * @param $item
     *
     * @return float
     */
    public function getRatingAverage($item)
    {
        $this->addRatingsRelations($item);
        $ratingsTotal = $item->ratingsTotal;

        $rating = $ratingsTotal ? $ratingsTotal->rating : 0;
        $rater = $ratingsTotal ? $ratingsTotal->raters : 0;

        return $rating > 0 ? $rating / $rater : 0;
    }

    /**
     * Получаем количество проголосовавших.
     *
     * @param $item
     *
     * @return int
     */
    public function getRatersCount($item): int
    {
        $this->addRatingsRelations($item);

        return $item->ratingsTotal->raters ?? 0;
    }

    /**
     * Добавил ли текущий пользователь материал в избранное.
     *
     * @param $item
     * @param int|null $userId
     *
     * @return bool
     */
    public function isRated($item, $userId): bool
    {
        $this->addRatingsRelations($item);
        $userId = $this->usersService->getUserIdOrHash($userId);

        return $item->ratings()
            ->where(
                [
                    'user_id' => $userId,
                ]
            )
            ->exists();
    }

    /**
     * Получаем оценку материала от пользователя.
     *
     * @param $item
     * @param int|null $userId
     *
     * @return float
     */
    public function userRate($item, $userId = null): ?float
    {
        $this->addRatingsRelations($item);
        $userId = $this->usersService->getUserIdOrHash($userId);

        $rate = $item->ratings()
            ->where(
                [
                    'user_id' => $userId,
                ]
            )->first();

        return ($rate) ? $rate->rating : null;
    }

    /**
     * Обновляем счетчик избранного.
     *
     * @param $item
     * @param $rating
     *
     * @return mixed
     */
    public function updateRating($item, $rating)
    {
        $this->addRatingsRelations($item);
        $counter = $item->ratingsTotal()->first();

        if (! $counter) {
            $counter = $item->ratingsTotal()
                ->create(
                    [
                        'rating' => 0,
                        'raters' => 0,
                    ]
                );
        }

        if ($rating < 0) {
            $counter->decrement('rating', -($rating));
            $counter->decrement('raters');
        } else {
            $counter->increment('rating', $rating);
            $counter->increment('raters');
        }

        return $item;
    }

    /**
     * Удаляем оценки у определенного типа материала.
     *
     * @param string $itemType
     *
     * @throws BindingResolutionException
     */
    public function removeRatingTotalOfType(string $itemType): void
    {
        if (class_exists($itemType)) {
            $item = new $itemType;
            $itemType = $item->getMorphClass();
        }

        $counters = app()
            ->make('InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingTotalModelContract')
            ->where('rateable_type', $itemType);

        $counters->delete();
    }

    /**
     * Удаляем материал из избранного всех пользователей.
     *
     * @param $item
     *
     * @throws BindingResolutionException
     */
    public function removeModelRatings($item): void
    {
        $this->model::where(
            [
                'rateable_id' => $item->getKey(),
                'rateable_type' => $item->getMorphClass(),
            ]
        )->delete();

        app()->make('InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingTotalModelContract')
            ->where(
                [
                    'rateable_id' => $item->getKey(),
                    'rateable_type' => $item->getMorphClass(),
                ]
            )->delete();
    }

    /**
     * Получаем всех пользователей, которые добавили материал в избранное.
     *
     * @param $item
     *
     * @return Collection
     */
    public function collectRatersOf($item): Collection
    {
        $this->addRatingsRelations($item);
        $userModel = $this->usersService->resolveUserModel();

        $ratersIds = $item->ratings->pluck('user_id');

        return $userModel::whereKey($ratersIds)->get();
    }

    /**
     * Получаем счетчики по типу материала.
     *
     * @param string $itemType
     *
     * @return array
     */
    public function fetchRatingsCounters(string $itemType): array
    {
        if (class_exists($itemType)) {
            $item = new $itemType;
            $itemType = $item->getMorphClass();
        }

        $ratingsCount = $this->model
            ->select(
                [
                    'rateable_id',
                    'rateable_type',
                    'collection',
                    DB::raw('SUM(rating) as rating'),
                    DB::raw('COUNT(*) AS raters'),
                ]
            )
            ->where('rateable_type', $itemType)
            ->groupBy('rateable_id', 'rateable_type');

        return $ratingsCount->get()->toArray();
    }

    /**
     * Применяем scope к запросу.
     *
     * @param  Builder  $builder
     * @param $scopeContract
     * @param $arguments
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function applyScope(Builder $builder, $scopeContract, $arguments): Builder
    {
        $this->addRatingsRelations($builder->getModel());

        $scope = app()->make($scopeContract, $arguments);
        $scopeIdentifier = Str::replaceLast('Contract', '', Str::afterLast($scopeContract, '\\'));

        $builder->withGlobalScope($scopeIdentifier, $scope);

        return $builder;
    }

    /**
     * Добавляем объекту связи с избранным.
     *
     * @param $item
     */
    protected function addRatingsRelations($item): void
    {
        $item::addDynamicRelation(
            'ratings',
            function () use ($item) {
                $ratingModel = app()->make('InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingModelContract');

                return $item->morphMany(get_class($ratingModel), 'rateable');
            }
        );

        $item::addDynamicRelation(
            'ratingsTotal',
            function () use ($item) {
                $ratingTotalModel = app()->make('InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingTotalModelContract');

                return $item->morphOne(get_class($ratingTotalModel), 'rateable');
            }
        );
    }
}
