<?php

namespace YayInnovations\FailedJobsMonitor;

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FailedJobsMonitorProvider extends ServiceProvider
{
    const PACKAGE = 'failed-jobs-monitor';
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleConfigs();
        $this->handleViews();
        $this->handleRoutes();
        $this->defineAssetPublishing();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    private function handleConfigs()
    {
        $configPath = __DIR__.'/../config/'.self::PACKAGE.'.php';

        $this->publishes([
            $configPath => $this->app->configPath(self::PACKAGE.'.php'),
        ]);
        $this->mergeConfigFrom($configPath, self::PACKAGE);
    }

    private function handleViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', self::PACKAGE);
    }

    private function handleRoutes()
    {
        /** @var Repository $config */
        $config = $this->app->make('config');

        Route::group([
            'prefix' => $config->get(self::PACKAGE.'.path'),
            'namespace' => 'YayInnovations\FailedJobsMonitor\Http\Controllers',
            'middleware' => $config->get(self::PACKAGE.'.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    private function defineAssetPublishing()
    {
        $publicPath = $this->app->make('path.public');

        $this->publishes([
            __DIR__.'/../public' => $publicPath.'/vendor/'.self::PACKAGE,
        ], self::PACKAGE.'-assets');
    }
}
