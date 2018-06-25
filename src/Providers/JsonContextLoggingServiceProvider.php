<?php

namespace Czim\LaravelJsonContextLogging\Providers;

use Czim\LaravelJsonContextLogging\Contracts\LoggerFactoryInterface;
use Czim\LaravelJsonContextLogging\Factories\LoggerFactory;
use Illuminate\Support\ServiceProvider;

class JsonContextLoggingServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->bootConfig();
    }

    public function register()
    {
        $this
            ->registerConfig()
            ->registerInterfaceBindings();
    }

    /**
     * @return $this
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            realpath(dirname(__DIR__) . '/../config/json-context-logging.php'),
            'json-context-logging'
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerInterfaceBindings()
    {
        $this->app->singleton(LoggerFactoryInterface::class, LoggerFactory::class);

        return $this;
    }

    /**
     * @return $this
     */
    protected function bootConfig()
    {
        $this->publishes([
            realpath(dirname(__DIR__) . '/../config/json-context-logging.php') => config_path('json-context-logging.php'),
        ]);

        return $this;
    }

}
