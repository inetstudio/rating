<?php

namespace InetStudio\Rating\Providers;

use Illuminate\Support\ServiceProvider;
use InetStudio\Rating\Models\RatingModel;
use InetStudio\Rating\Services\RatingService;
use InetStudio\Rating\Models\RatingTotalModel;
use InetStudio\Rating\Observers\RatingObserver;
use InetStudio\Rating\Console\Commands\SetupCommand;
use InetStudio\Rating\Contracts\Models\RatingModelContract;
use InetStudio\Rating\Console\Commands\RateableRecountCommand;
use InetStudio\Rating\Contracts\Services\RatingServiceContract;
use InetStudio\Rating\Contracts\Models\RatingTotalModelContract;

class RatingServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerTranslations();
        $this->registerObservers();
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Регистрация команд.
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupCommand::class,
                RateableRecountCommand::class,
            ]);
        }
    }

    /**
     * Регистрация ресурсов.
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../../config/rating.php' => config_path('rating.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateRatingTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_rating_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_rating_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Регистрация путей.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.rating');
    }

    /**
     * Регистрация переводов.
     *
     * @return void
     */
    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'rating');
    }

    /**
     * Регистрация наблюдателей.
     *
     * @return void
     */
    protected function registerObservers(): void
    {
        $this->app->make(RatingModelContract::class)->observe(RatingObserver::class);
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        $this->app->bind(RatingModelContract::class, RatingModel::class);
        $this->app->bind(RatingTotalModelContract::class, RatingTotalModel::class);
        $this->app->singleton(RatingServiceContract::class, RatingService::class);
    }
}
