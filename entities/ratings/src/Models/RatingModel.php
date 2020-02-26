<?php

namespace InetStudio\RatingsPackage\Ratings\Models;

use Illuminate\Database\Eloquent\Model;
use InetStudio\ACL\Users\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingModelContract;

/**
 * Class RatingModel.
 */
class RatingModel extends Model implements RatingModelContract
{
    /**
     * Тип сущности.
     */
    const ENTITY_TYPE = 'rating';

    /**
     * Имя "updated at" колонки.
     */
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
        'rateable_id',
        'rateable_type',
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
     * Сеттер атрибута rateable_type.
     *
     * @param $value
     */
    public function setRateableTypeAttribute($value)
    {
        $this->attributes['rateable_type'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута rateable_id.
     *
     * @param $value
     */
    public function setRateableIdAttribute($value)
    {
        $this->attributes['rateable_id'] = (int) trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута user_id.
     *
     * @param $value
     */
    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута collection.
     *
     * @param $value
     */
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = (float) trim(strip_tags($value));
    }

    /**
     * Геттер атрибута type.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    use HasUser;

    /**
     * Полиморфное отношение с остальными моделями.
     *
     * @return MorphTo
     */
    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }
}
