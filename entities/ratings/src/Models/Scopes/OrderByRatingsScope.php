<?php

namespace InetStudio\RatingsPackage\Ratings\Models\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use InetStudio\RatingsPackage\Ratings\Contracts\Models\Scopes\OrderByRatingsScopeContract;

/**
 * Class OrderByRatingsScope.
 */
Class OrderByRatingsScope implements OrderByRatingsScopeContract
{
    /**
     * @var string
     */
    protected $direction;

    /**
     * WhereRatedByScope constructor.
     *
     * @param  string  $direction
     */
    public function __construct(string $direction = 'desc')
    {
        $this->direction = $direction;
    }

    /**
     * @param  Builder  $builder
     * @param  Model  $model
     *
     * @return Builder|\Illuminate\Database\Query\Builder|void
     */
    public function apply(Builder $builder, Model $model)
    {
        $item = $builder->getModel();

        return $builder
            ->select($item->getTable() . '.*', 'ratings_total.rating')
            ->leftJoin('ratings_total', function (JoinClause $join) use ($item) {
                $join
                    ->on('ratings_total.rateable_id', '=', "{$item->getTable()}.{$item->getKeyName()}")
                    ->where('ratings_total.rateable_type', '=', $item->getMorphClass());
            })
            ->orderBy('ratings_total.rating', $this->direction);
    }
}
