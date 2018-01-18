<?php

namespace InetStudio\Rating\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Rating\Models\RatingTotalModel;
use League\Fractal\Resource\Collection as FractalCollection;

class RatingTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param RatingTotalModel $rating
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(RatingTotalModel $rating): array
    {
        return [
            'title' => $rating->rateable->title,
            'rating' => $rating->rateable->getRatingAverage(),
            'likes' => $rating->rateable->ratings->where('rating', 5)->count(),
            'dislikes' => $rating->rateable->ratings->where('rating', 0)->count(),
            'actions' => view('admin.module.rating::back.partials.datatables.actions', [
                'href' => $rating->rateable->href,
            ])->render(),
        ];
    }

    /**
     * Обработка коллекции рейтингов.
     *
     * @param $ratings
     *
     * @return FractalCollection
     */
    public function transformCollection($ratings): FractalCollection
    {
        return new FractalCollection($ratings, $this);
    }
}
