<?php

namespace InetStudio\RatingsPackage\Ratings\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use InetStudio\RatingsPackage\Ratings\Contracts\Models\RatingModelContract;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Загрузка сервиса.
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerTranslations();
        $this->registerViews();
    }

    /**
     * Регистрация команд.
     */
    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            'InetStudio\RatingsPackage\Ratings\Console\Commands\RecountCommand',
            'InetStudio\RatingsPackage\Ratings\Console\Commands\SetupCommand',
        ]);
    }

    /**
     * Регистрация ресурсов.
     */
    protected function registerPublishes(): void
    {
        $this->publishes(
            [
                __DIR__.'/../../config/ratings_package_ratings.php' => config_path('ratings_package_ratings.php'),
            ],
            'config'
        );

        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Schema::hasTable('ratings')) {
            return;
        }

        $timestamp = date('Y_m_d_His', time());
        $this->publishes(
            [
                __DIR__.'/../../database/migrations/create_ratings_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_ratings_tables.php'),
            ],
            'migrations'
        );
    }

    /**
     * Регистрация путей.
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация переводов.
     */
    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'ratings_package_ratings');
    }

    /**
     * Регистрация представлений.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.ratings-package.ratings');
    }
}
