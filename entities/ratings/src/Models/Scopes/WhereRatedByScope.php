<?php

namespace InetStudio\RatingsPackage\Ratings\Models\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use InetStudio\RatingsPackage\Ratings\Contracts\Models\Scopes\WhereRatedByScopeContract;

/**
 * Class WhereRatedByScope.
 */
Class WhereRatedByScope implements WhereRatedByScopeContract
{
    /**
     * @var
     */
    protected $userId;

    /**
     * WhereRatedByScope constructor.
     *
     * @param  null  $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param  Builder  $builder
     * @param  Model  $model
     *
     * @return Builder|void
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->whereHas('ratings', function (Builder $innerQuery) {
            $innerQuery->where('user_id', $this->userId);
        });
    }
}
