<?php

namespace Neurony\QueryCache;

use Illuminate\Contracts\Foundation\Application;
use Neurony\QueryCache\Services\QueryCacheService;
use Neurony\QueryCache\Contracts\QueryCacheServiceContract;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Create a new service provider instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfigs();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigs();
        $this->registerBindings();
    }

    /**
     * @return void
     */
    protected function publishConfigs()
    {
        $this->publishes([
            __DIR__.'/../config/query-cache.php' => config_path('query-cache.php'),
        ], 'config');
    }

    /**
     * @return void
     */
    protected function mergeConfigs()
    {
        //$this->mergeConfigFrom(__DIR__ . '/../config/cache.php', 'query-cache');
    }

    /**
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->singleton(QueryCacheServiceContract::class, QueryCacheService::class);
        $this->app->alias(QueryCacheServiceContract::class, 'cache.query');
    }
}
