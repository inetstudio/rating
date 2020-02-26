<?php

namespace InetStudio\RatingsPackage\Ratings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\RatingsPackage\Ratings\Exceptions\ModelInvalidException;
use InetStudio\RatingsPackage\Ratings\Contracts\Services\ItemsServiceContract;

/**
 * Class RecountCommand.
 */
class RecountCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $signature = 'inetstudio:ratings-package:ratings:recount {model?}';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Recount ratings for the models';

    /**
     * RatingsService service.
     *
     * @var ItemsServiceContract
     */
    protected $itemsService;

    /**
     * RecountCommand constructor.
     *
     * @param  ItemsServiceContract  $itemsService
     */
    public function __construct(ItemsServiceContract $itemsService)
    {
        parent::__construct();

        $this->itemsService = $itemsService;
    }

    /**
     * Запуск команды.
     *
     * @throws BindingResolutionException
     * @throws ModelInvalidException
     */
    public function handle(): void
    {
        $model = $this->argument('model');

        if (empty($model)) {
            $this->recountRatingsOfAllModelTypes();
        } else {
            $this->recountRatingsOfModelType($model);
        }
    }

    /**
     * Пересчитать счетчик избранного для всех типов.
     *
     * @throws BindingResolutionException
     * @throws ModelInvalidException
     */
    protected function recountRatingsOfAllModelTypes(): void
    {
        $rateableTypes = $this->itemsService->getModel()
            ->select(['rateable_type'])
            ->groupBy('rateable_type')
            ->get();

        foreach ($rateableTypes as $favorite) {
            $this->recountRatingsOfModelType($favorite->rateable_type);
        }
    }

    /**
     * Пересчитать счетчик избранного для определенного типа.
     *
     * @param string $modelType
     *
     * @throws BindingResolutionException
     * @throws ModelInvalidException
     */
    protected function recountRatingsOfModelType($modelType): void
    {
        $modelType = $this->normalizeModelType($modelType);

        $counters = $this->itemsService->fetchRatingsCounters($modelType);

        $this->itemsService->removeFavoriteTotalOfType($modelType);

        $table = app()->make('InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingTotalModelContract')->getTable();
        foreach ($counters as $counter) {
            DB::table($table)->insert($counter);
        }

        $this->info('All [' . $modelType . '] ratings has been recounted.');
    }

    /**
     * Получить тип модели.
     *
     * @param string $modelType
     *
     * @return string
     *
     * @throws ModelInvalidException
     */
    protected function normalizeModelType($modelType)
    {
        $morphMap = Relation::morphMap();

        if (class_exists($modelType)) {
            $model = new $modelType;
            $modelType = $model->getMorphClass();
        } else {
            if (! isset($morphMap[$modelType])) {
                throw new ModelInvalidException("[$modelType] class and morph map are not found.");
            }

            $modelClass = $morphMap[$modelType];
            $model = new $modelClass;
        }

        return $modelType;
    }
}
