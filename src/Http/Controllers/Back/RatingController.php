<?php

namespace InetStudio\Rating\Http\Controllers\Back;

use Illuminate\View\View;
use League\Fractal\Manager;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use InetStudio\Rating\Models\RatingTotalModel;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Rating\Transformers\Back\RatingTransformer;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(): View
    {
        $table = $this->generateTable('rating', 'index');

        return view('admin.module.rating::back.pages.index', compact('table'));
    }

    /**
     * DataTables ServerSide.
     *
     * @return mixed
     *
     * @throws \Exception|\Throwable
     */
    public function data()
    {
        $ratings = RatingTotalModel::with([
            'rateable' => function ($query) {
                $query->with(['ratings', 'ratingTotal'])->select(['id', 'title', 'href']);
            }])->get();

        $resource = (new RatingTransformer())->transformCollection($ratings);

        return DataTables::of(collect($this->serializeToArray($resource)))
            ->rawColumns(['actions'])
            ->make();
    }

    /**
     * Преобразовываем данные в массив.
     *
     * @param $resource
     *
     * @return array
     */
    private function serializeToArray($resource): array
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}
