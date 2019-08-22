<?php

namespace InetStudio\Rating\Http\Controllers\Front;

use Illuminate\Http\JsonResponse;
use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\Rating\Contracts\Services\RatingServiceContract;
use InetStudio\Rating\Contracts\Http\Controllers\Front\ItemsControllerContract;

/**
 * Class ItemsController.
 */
class ItemsController extends Controller implements ItemsControllerContract
{
    protected $rates = [
        'dislike' => 0.0,
        'like' => 5.0,
    ];

    /**
     * Оцениваем материал.
     *
     * @param  RatingServiceContract  $ratingService
     * @param  string  $rate
     * @param  string  $type
     * @param  int  $id
     *
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function rate(RatingServiceContract $ratingService, string $rate, string $type, int $id): JsonResponse
    {
        if (! isset($this->rates[mb_strtolower($rate)])) {
            return response()->json([
                'success' => false,
                'rating' => 0,
            ]);
        } else {
            $check = $ratingService->checkIsRateable(mb_strtolower($type), (int) $id);

            if (! $check['success']) {
                $check['rating'] = 0;
                return response()->json($check);
            }
        }

        $item = $check['item']->rate($this->rates[mb_strtolower($rate)]);

        event(
            app()->make(
                'InetStudio\Rating\Contracts\Events\Front\ItemRateChangedContract',
                compact('item')
            )
        );

        return response()->json([
            'success' => 'success',
            'message' => trans('rating::messages.voted'),
            'rating' => number_format($item->getRatingAverage(), 2),
        ]);
    }
}
