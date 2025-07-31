<?php

namespace Varunazad\QueryCache;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as Config;

class QueryCacheServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('querycache', function ($app) {
            return new QueryCache(
                $app['cache.store'],
                $app->make(Config::class),
                $app
            );
        });

        $this->mergeConfigFromIfExists();
    }

    public function boot()
    {
        $this->publishConfigIfConsole();
    }

    protected function mergeConfigFromIfExists()
    {
        if (method_exists($this, 'mergeConfigFrom')) {
            $this->mergeConfigFrom(
                __DIR__.'/../config/querycache.php',
                'querycache'
            );
        }
    }

    protected function publishConfigIfConsole()
    {
        if ($this->app->runningInConsole() && method_exists($this, 'publishes')) {
            $this->publishes([
                __DIR__.'/../config/querycache.php' => $this->app->configPath('querycache.php'),
            ], 'config');
        }
    }
}