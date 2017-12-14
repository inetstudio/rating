<?php

namespace InetStudio\Rating\Contracts\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface RateableContract
 * @package InetStudio\Rating\Contracts\Models\Traits
 */
interface RateableContract
{
    /**
     * Получаем значение первичного ключа.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Получаем имя класса для полиморфного отношения.
     *
     * @return string
     */
    public function getMorphClass();

    /**
     * Получаем все оценки материала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings();

    /**
     * Получаем рейтинг материала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function ratingTotal();

    /**
     * Получаем всех пользователей, которые оценивали материал.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collectRaters();

    /**
     * Получаем рейтинг материала.
     *
     * @return float
     */
    public function getRatingAttribute();

    /**
     * Получаем рейтинг материала в процентах.
     *
     * @param int $max
     * @return float
     */
    public function getRatingPercent(int $max);

    /**
     * Получаем средний рейтинг материала.
     *
     * @return float
     */
    public function getRatingAverage();

    /**
     * Оценивал ли текущий пользователь материал.
     *
     * @return bool
     */
    public function getRatedAttribute();

    /**
     * Получаем все материалы, которые оценивал пользователь.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $userId Если пусто, то берется текущий пользователь.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRatedBy(Builder $query, $userId = null);

    /**
     * Получаем все материалы, отсортированные по рейтингу.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByRating(Builder $query, $direction = 'desc');

    /**
     * Добавляем оценку пользователя материалу.
     *
     * @param float $rating
     * @param mixed $userId Если пусто, то берется текущий пользователь.
     * @return void
     */
    public function rate($rating, $userId = null);

    /**
     * Удаляем оценку пользователя у материала.
     *
     * @param int|null $userId Если пусто, то берется текущий пользователь.
     * @return void
     */
    public function unRate($userId = null);

    /**
     * Переключаем оценку пользователя у материала.
     *
     * @param float $rating
     * @param mixed $userId Если пусто, то берется текущий пользователь.
     * @return void
     */
    public function rateToggle($rating, $userId = null);

    /**
     * Оценивал ли текущий пользователь материал.
     *
     * @param int|null $userId Если пусто, то берется текущий пользователь.
     * @return bool
     */
    public function rated($userId = null);

    /**
     * Удаляем оценки у материала.
     *
     * @return void
     */
    public function removeRates();
}
