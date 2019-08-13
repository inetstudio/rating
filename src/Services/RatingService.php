<?php

namespace InetStudio\Rating\Services;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use InetStudio\Rating\Contracts\Models\RatingModelContract;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\Rating\Contracts\Models\Traits\RateableContract;
use InetStudio\Rating\Contracts\Services\RatingServiceContract;
use InetStudio\Rating\Contracts\Models\RatingTotalModelContract;

/**
 * Class RatingService
 * @package InetStudio\Rating\Services
 */
class RatingService implements RatingServiceContract
{
    public $availableTypes = [];

    /**
     * RatingService constructor.
     */
    public function __construct()
    {
        $this->availableTypes = config('rating.rateable', []);
    }

    /**
     * Проверяем материал на возможность оценки.
     *
     * @param string $type
     * @param int $id
     * @return array
     *
     * @throws BindingResolutionException
     */
    public function checkIsRateable(string $type, int $id): array
    {
        if (! isset($this->availableTypes[$type])) {
            return [
                'success' => false,
                'message' => trans('rating::errors.materialIncorrectType'),
            ];
        }

        $model = app()->make($this->availableTypes[$type]);

        if (! is_null($id) && $id > 0 && $item = $model::find($id)) {
            $interfaces = class_implements($item);

            if (isset($interfaces[RateableContract::class])) {
                return [
                    'success' => true,
                    'item' => $item,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => trans('rating::errors.notImplementRateable'),
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => trans('rating::errors.materialNotFound'),
            ];
        }
    }

    /**
     * Добавляем оценку пользователя материалу.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param float $rating
     * @param int|null $userId
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function addRateTo(RateableContract $rateable, $rating, $userId): RateableContract
    {
        $userId = $this->getRaterUserId($userId);

        $rate = $rateable->ratings()->where([
            'user_id' => $userId,
        ])->first();

        if (! $rate) {
            $rateable->ratings()->create([
                'user_id' => $userId,
                'rating' => (float) $rating,
            ]);
        }

        return $rateable;
    }

    /**
     * Удаляем оценку пользователя у материала.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param int|null $userId
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function removeRateFrom(RateableContract $rateable, $userId): RateableContract
    {
        $rate = $rateable->ratings()->where([
            'user_id' => $this->getRaterUserId($userId),
        ])->first();

        if ($rate) {
            $rate->delete();
        }

        return $rateable;
    }

    /**
     * Переключаем оценку пользователя у материала.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param float $rating
     * @param int|null $userId
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function toggleRateOf(RateableContract $rateable, $rating, $userId): RateableContract
    {
        $userId = $this->getRaterUserId($userId);

        $like = $rateable->ratings()->where([
            'user_id' => $userId,
        ])->exists();

        if ($like) {
            $this->removeRateFrom($rateable, $userId);
        } else {
            $this->addRateTo($rateable, $rating, $userId);
        }

        return $rateable;
    }

    /**
     * Оценивал ли текущий пользователь материал.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param int|null $userId
     * @return bool
     */
    public function isRated(RateableContract $rateable, $userId): bool
    {
        $userId = $this->getRaterUserId($userId);

        return $rateable->ratings()->where([
            'user_id' => $userId,
        ])->exists();
    }

    /**
     * Получаем оценку материала от пользователя.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param int|null $userId
     * @return float
     */
    public function userRate(RateableContract $rateable, $userId): ?float
    {
        $userId = $this->getRaterUserId($userId);

        $rate = $rateable->ratings()->where([
            'user_id' => $userId,
        ])->first();

        return ($rate) ? $rate->rating : null;
    }

    /**
     * Обновляем счетчик рейтинга.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param float $rating
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function updateRating(RateableContract $rateable, $rating): RateableContract
    {
        $counter = $rateable->ratingTotal()->first();

        if (! $counter) {
            $counter = $rateable->ratingTotal()->create([
                'rating' => 0,
                'raters' => 0,
            ]);
        }

        if ($rating < 0) {
            $counter->decrement('rating', -($rating));
            $counter->decrement('raters');
        } else {
            $counter->increment('rating', $rating);
            $counter->increment('raters');
        }

        return $rateable;
    }

    /**
     * Удаляем оценки у определенного типа материала.
     *
     * @param string $rateableType
     * @return void
     */
    public function removeRatingTotalOfType($rateableType): void
    {
        if (class_exists($rateableType)) {
            $rateable = new $rateableType;
            $rateableType = $rateable->getMorphClass();
        }

        $counters = app(RatingTotalModelContract::class)->where('rateable_type', $rateableType);

        $counters->delete();
    }

    /**
     * Удаляем все оценки у модели.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @return void
     */
    public function removeModelRates(RateableContract $rateable): void
    {
        app(RatingModelContract::class)->where([
            'rateable_id' => $rateable->getKey(),
            'rateable_type' => $rateable->getMorphClass(),
        ])->delete();

        app(RatingTotalModelContract::class)->where([
            'rateable_id' => $rateable->getKey(),
            'rateable_type' => $rateable->getMorphClass(),
        ])->delete();
    }

    /**
     * Получаем всех пользователей, которые оценивали материал.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @return \Illuminate\Support\Collection
     */
    public function collectRatersOf(RateableContract $rateable): Collection
    {
        $userModel = $this->resolveUserModel();

        $ratersIds = $rateable->ratings->pluck('user_id');

        return $userModel::whereKey($ratersIds)->get();
    }

    /**
     * Получаем все материалы, которые оценивал пользователь.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRatedBy(Builder $query, $userId): Builder
    {
        $userId = $this->getRaterUserId($userId);

        return $query->whereHas('ratings', function (Builder $innerQuery) use ($userId) {
            $innerQuery->where('user_id', $userId);
        });
    }

    /**
     * Получаем все материалы, отсортированные по рейтингу.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByRating(Builder $query, $direction = 'desc'): Builder
    {
        $rateable = $query->getModel();

        return $query
            ->select($rateable->getTable() . '.*', 'ratings_total.rating')
            ->leftJoin('ratings_total', function (JoinClause $join) use ($rateable) {
                $join
                    ->on('ratings_total.rateable_id', '=', "{$rateable->getTable()}.{$rateable->getKeyName()}")
                    ->where('ratings_total.rateable_type', '=', $rateable->getMorphClass());
            })
            ->orderBy('ratings_total.rating', $direction);
    }

    /**
     * Получаем счетчики по типу материала.
     *
     * @param string $rateableType
     * @return array
     */
    public function fetchRatesCounters($rateableType): array
    {
        $ratesCount = app(RatingModelContract::class)
            ->select([
                'rateable_id',
                'rateable_type',
                \DB::raw('SUM(rating) as rating'),
                \DB::raw('COUNT(*) AS raters'),
            ])
            ->where('rateable_type', $rateableType);

        $ratesCount->groupBy('rateable_id', 'rateable_type');

        return $ratesCount->get()->toArray();
    }

    /**
     * Получаем id пользователя.
     *
     * @param int $userId
     * @return string
     */
    protected function getRaterUserId($userId)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if (! $userId) {
            $cookieData = request()->cookie('guest_user_hash');

            if ($cookieData) {
                return $cookieData;
            } else {
                $uuid = Uuid::uuid4()->toString();

                Cookie::queue('guest_user_hash', $uuid, 5256000);

                return $uuid;
            }
        }

        return $userId;
    }

    /**
     * Получаем id авторизованного пользователя.
     *
     * @return int
     */
    protected function loggedInUserId(): ?int
    {
        return auth()->id();
    }

    /**
     * Получаем имя класса пользователя.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    private function resolveUserModel()
    {
        return config('auth.providers.users.model');
    }
}
