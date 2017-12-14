<?php

namespace InetStudio\Rating\Models;

use Illuminate\Database\Eloquent\Model;
use InetStudio\Rating\Contracts\Models\RatingTotalModelContract;

class RatingTotalModel extends Model implements RatingTotalModelContract
{
    public $timestamps = false;

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'ratings_total';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'rating',
        'raters',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы к базовым типам.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'float',
    ];

    /**
     * Полиморфное отношение с остальными моделями.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function rateable()
    {
        return $this->morphTo();
    }
}
