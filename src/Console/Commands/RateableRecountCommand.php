<?php

namespace InetStudio\Rating\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use InetStudio\Rating\Exceptions\ModelInvalidException;
use InetStudio\Rating\Contracts\Models\RatingModelContract;
use InetStudio\Rating\Contracts\Models\Traits\RateableContract;
use InetStudio\Rating\Contracts\Services\RatingServiceContract;
use InetStudio\Rating\Contracts\Models\RatingTotalModelContract;

class RateableRecountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inetstudio:rating:recount {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recount rating for the models';

    /**
     * RatingService service.
     *
     * @var \InetStudio\Rating\Contracts\Services\RatingServiceContract
     */
    protected $service;

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     *
     * @throws \InetStudio\Rating\Exceptions\ModelInvalidException
     */
    public function handle(Dispatcher $events)
    {
        $model = $this->argument('model');
        $this->service = app(RatingServiceContract::class);

        if (empty($model)) {
            $this->recountRatingOfAllModelTypes();
        } else {
            $this->recountRatingOfModelType($model);
        }
    }

    /**
     * Recount rating of all model types.
     *
     * @return void
     *
     * @throws \InetStudio\Rating\Exceptions\ModelInvalidException
     */
    protected function recountRatingOfAllModelTypes()
    {
        $rateableTypes = app(RatingModelContract::class)->select(['rateable_type'])->groupBy('rateable_type')->get();

        foreach ($rateableTypes as $rating) {
            $this->recountRatingOfModelType($rating->rateable_type);
        }
    }

    /**
     * Recount rating of model type.
     *
     * @param string $modelType
     * @return void
     *
     * @throws \InetStudio\Rating\Exceptions\ModelInvalidException
     */
    protected function recountRatingOfModelType($modelType)
    {
        $modelType = $this->normalizeModelType($modelType);

        $counters = $this->service->fetchRatesCounters($modelType);

        $this->service->removeRatingTotalOfType($modelType);

        DB::table(app(RatingTotalModelContract::class)->getTable())->insert($counters);

        $this->info('All [' . $modelType . '] ratings has been recounted.');
    }

    /**
     * Normalize rateable model type.
     *
     * @param string $modelType
     * @return string
     *
     * @throws \InetStudio\Rating\Exceptions\ModelInvalidException
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

        if (!$model instanceof RateableContract) {
            throw new ModelInvalidException("[$modelType] not implements Rateable contract.");
        }

        return $modelType;
    }
}
