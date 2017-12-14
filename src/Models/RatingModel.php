<?php

namespace InetStudio\Rating\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use InetStudio\Rating\Contracts\Models\RatingModelContract;

class RatingModel extends Model implements RatingModelContract
{
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
     * Обратное отношение с моделью пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        $userClassName = Config::get('auth.model');

        if (is_null($userClassName)) {
            $userClassName = Config::get('auth.providers.users.model');
        }

        return $this->belongsTo($userClassName);
    }

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
