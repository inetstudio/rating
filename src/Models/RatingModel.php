<?php

namespace InetStudio\Rating\Models;

use Illuminate\Database\Eloquent\Model;
use InetStudio\ACL\Users\Models\Traits\HasUser;
use InetStudio\Rating\Contracts\Models\RatingModelContract;

class RatingModel extends Model implements RatingModelContract
{
    use HasUser;

    const UPDATED_AT = null;

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'ratings';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'rating',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
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
