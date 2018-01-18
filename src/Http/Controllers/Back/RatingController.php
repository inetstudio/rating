<?php

namespace InetStudio\Rating\Http\Controllers\Back;

use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use InetStudio\Rating\Models\RatingTotalModel;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;

/**
 * Class RatingController
 * @package InetStudio\Rating\Http\Controllers\Back
 */
class RatingController extends Controller
{
    use DatatablesTrait;

    /**
     * Статистика.
     *
     * @param DataTables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(DataTables $dataTable): View
    {
        $table = $this->generateTable($dataTable, 'rating', 'index');

        return view('admin.module.rating::back.pages.index', compact('table'));
    }

    /**
     * DataTables ServerSide.
     *
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $ratings = RatingTotalModel::with([
            'rateable' => function ($query) {
                $query->with(['ratings', 'ratingTotal'])->select(['id', 'title', 'href']);
            }])->get();

        $data = collect();

        foreach ($ratings as $rating) {
            $item = [
                'title' => $rating->rateable->title,
                'href' => $rating->rateable->href,
                'rating' => $rating->rateable->getRatingAverage(),
                'likes' => $rating->rateable->ratings->where('rating', 5)->count(),
                'dislikes' => $rating->rateable->ratings->where('rating', 0)->count(),
            ];

            $data->push($item);
        }

        return DataTables::of($data)->make();
    }
}
