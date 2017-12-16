<?php

namespace InetStudio\Rating\Contracts\Services;

use Illuminate\Database\Eloquent\Builder;
use InetStudio\Rating\Contracts\Models\Traits\RateableContract;

/**
 * Interface RatingServiceContract
 * @package InetStudio\Rating\Contracts\Services
 */
interface RatingServiceContract
{
    /**
     * Добавляем оценку пользователя материалу.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param float $rating
     * @param int|null $userId
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function addRateTo(RateableContract $rateable, $rating, $userId);

    /**
     * Удаляем оценку пользователя у материала.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param int|null $userId
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function removeRateFrom(RateableContract $rateable, $userId);

    /**
     * Переключаем оценку пользователя у материала.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param float $rating
     * @param int|null $userId
     * @return \InetStudio\Rating\Contracts\Models\Traits\RateableContract
     */
    public function toggleRateOf(RateableContract $rateable, $rating, $userId);

    /**
     * Оценивал ли текущий пользователь материал.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param int|null $userId
     * @return bool
     */
    public function isRated(RateableContract $rateable, $userId);

    /**
     * Получаем оценку материала от пользователя.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param int|null $userId
     * @return float
     */
    public function userRate(RateableContract $rateable, $userId);

    /**
     * Обновляем счетчик рейтинга.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @param float $rating
     * @return void
     */
    public function updateRating(RateableContract $rateable, $rating);

    /**
     * Удаляем оценки у определенного типа материала.
     *
     * @param string $rateableType
     * @return void
     */
    public function removeRatingTotalOfType($rateableType);

    /**
     * Удаляем все оценки у модели.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @return void
     */
    public function removeModelRates(RateableContract $rateable);

    /**
     * Получаем всех пользователей, которые оценивали материал.
     *
     * @param \InetStudio\Rating\Contracts\Models\Traits\RateableContract $rateable
     * @return \Illuminate\Support\Collection
     */
    public function collectRatersOf(RateableContract $rateable);

    /**
     * Получаем все материалы, которые оценивал пользователь.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws \Cog\Likeable\Exceptions\LikerNotDefinedException
     */
    public function scopeWhereRatedBy(Builder $query, $userId);

    /**
     * Получаем все материалы, отсортированные по рейтингу.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByRating(Builder $query, $direction = 'desc');

    /**
     * Получаем счетчики по типу материала.
     *
     * @param string $rateableType
     * @return array
     */
    public function fetchRatesCounters($rateableType);
}
