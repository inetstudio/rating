<?php

namespace InetStudio\Rating\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use InetStudio\Rating\Observers\ModelObserver;
use InetStudio\Rating\Contracts\Models\RatingModelContract;
use InetStudio\Rating\Contracts\Services\RatingServiceContract;
use InetStudio\Rating\Contracts\Models\RatingTotalModelContract;

/**
 * Trait Rateable
 * @package InetStudio\Rating\Models\Traits
 */
trait Rateable
{
    /**
     * Загрузка трейта.
     *
     * @return void
     */
    public static function bootRateable()
    {
        static::observe(ModelObserver::class);
    }

    /**
     * Получаем все оценки материала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings()
    {
        return $this->morphMany(app(RatingModelContract::class), 'rateable');
    }

    /**
     * Получаем рейтинг материала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function ratingTotal()
    {
        return $this->morphOne(app(RatingTotalModelContract::class), 'rateable');
    }

    /**
     * Получаем всех пользователей, которые оценивали материал.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collectRaters()
    {
        return app(RatingServiceContract::class)->collectRatersOf($this);
    }

    /**
     * Получаем рейтинг материала.
     *
     * @return float
     */
    public function getRatingAttribute()
    {
        return $this->ratingTotal ? $this->ratingTotal->rating : 0;
    }

    /**
     * Получаем рейтинг материала в процентах.
     *
     * @param int $max
     * @return float
     */
    public function getRatingPercent(int $max = 5)
    {
        $average = $this->getRatingAverage();

        return $average * 100 / $max;
    }

    /**
     * Получаем средний рейтинг материала.
     *
     * @return float
     */
    public function getRatingAverage()
    {
        $ratingTotal = $this->ratingTotal;

        $rating = $ratingTotal ? $ratingTotal->rating : 0;
        $rater = $ratingTotal ? $ratingTotal->raters : 0;

        return $rating > 0 ? $rating / $rater : 0;
    }

    /**
     * Оценивал ли текущий пользователь материал.
     *
     * @return bool
     */
    public function getRatedAttribute()
    {
        return $this->rated();
    }

    /**
     * Получаем все материалы, которые оценивал пользователь.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $userId Если пусто, то берется текущий пользователь.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRatedBy(Builder $query, $userId = null)
    {
        return app(RatingServiceContract::class)
            ->scopeWhereRatedBy($query, $userId);
    }

    /**
     * Получаем все материалы, отсортированные по рейтингу.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByRating(Builder $query, $direction = 'desc')
    {
        return app(RatingServiceContract::class)
            ->scopeOrderByRating($query, $direction);
    }

    /**
     * Добавляем оценку пользователя материалу.
     *
     * @param float $rating
     * @param int|null $userId Если пусто, то берется текущий пользователь.
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function rate($rating, $userId = null)
    {
        return app(RatingServiceContract::class)->addRateTo($this, $rating, $userId);
    }

    /**
     * Удаляем оценку пользователя у материала.
     *
     * @param int|null $userId Если пусто, то берется текущий пользователь.
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function unRate($userId = null)
    {
        return app(RatingServiceContract::class)->removeRateFrom($this, $userId);
    }

    /**
     * Переключаем оценку пользователя у материала.
     *
     * @param float $rating
     * @param int|null $userId Если пусто, то берется текущий пользователь.
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function rateToggle($rating, $userId = null)
    {
        return app(RatingServiceContract::class)->toggleRateOf($this, $rating, $userId);
    }

    /**
     * Оценивал ли текущий пользователь материал.
     *
     * @param int|null $userId Если пусто, то берется текущий пользователь.
     * @return bool
     */
    public function rated($userId = null)
    {
        return app(RatingServiceContract::class)->isRated($this, $userId);
    }

    /**
     * Удаляем оценки у материала.
     *
     * @return void
     */
    public function removeRates()
    {
        app(RatingServiceContract::class)->removeModelRates($this);
    }
}
