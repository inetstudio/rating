<?php

namespace InetStudio\RatingsPackage\Ratings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingTotalModelContract;

/**
 * Class RatingTotalModel.
 */
class RatingTotalModel extends Model implements RatingTotalModelContract
{
    /**
     * Тип сущности.
     */
    const ENTITY_TYPE = 'rating_total';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
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
        'rateable_id',
        'rateable_type',
        'count',
        'rating',
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
     * Сеттер атрибута collection.
     *
     * @param $value
     */
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = (float) trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута count.
     *
     * @param $value
     */
    public function setCountAttribute($value)
    {
        $this->attributes['count'] = (int) trim(strip_tags($value));
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
