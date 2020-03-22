<?php

namespace InetStudio\RatingsPackage\Ratings\Http\Resources\Back\Resource\Index;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Resources\Back\Resource\Index\ItemResourceContract;

/**
 * Class ItemResource.
 */
class ItemResource extends JsonResource implements ItemResourceContract
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     *
     * @throws Throwable
     */
    public function toArray($request)
    {
        $service = app()->make('InetStudio\RatingsPackage\Ratings\Contracts\Services\ItemsServiceContract');

        return [
            'title' => $this['rateable']->title,
            'rating' => $service->getRatingAverage($this['rateable']),
            'likes' => $this['rateable']->ratings->where('rating', 5)->count(),
            'dislikes' => $this['rateable']->ratings->where('rating', 0)->count(),
            'actions' => view('admin.module.ratings-package.ratings::back.partials.datatables.actions', [
                'href' => $this['rateable']->href,
            ])->render(),
        ];
    }
}
