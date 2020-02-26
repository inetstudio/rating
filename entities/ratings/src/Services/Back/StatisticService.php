<?php

namespace InetStudio\RatingsPackage\Ratings\Services\Back;

use Illuminate\Support\Facades\DB;
use InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingModelContract;
use InetStudio\RatingsPackage\Ratings\Contracts\Services\Back\StatisticServiceContract;

/**
 * Class StatisticService.
 */
class StatisticService implements StatisticServiceContract
{
    protected $colors = [
        0 => 'danger',
        5 => 'primary',
    ];

    protected $titles = [
        0 => 'Дизлайки',
        5 => 'Лайки',
    ];

    /**
     * @var RatingModelContract
     */
    protected $model;

    /**
     * StatisticService constructor.
     *
     * @param  RatingModelContract  $model
     */
    public function __construct(RatingModelContract $model)
    {
        $this->model = $model;
    }

    /**
     * Возвращаем статистику рейтинга.
     *
     * @return mixed
     */
    public function getRatingStatistic()
    {
        return $this->model::select(['rating', DB::raw('count(*) as total')])
            ->groupBy('rating')
            ->get();
    }

    /**
     * Возвращаем цвета статусов подписок.
     *
     * @return array
     */
    public function getRatingsColors(): array
    {
        return $this->colors;
    }

    /**
     * Возвращаем заголовки статусов подписок.
     *
     * @return array
     */
    public function getRatingsTitles(): array
    {
        return $this->titles;
    }
}
