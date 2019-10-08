<?php

namespace Czim\LaravelJsonContextLogging\Providers;

use Czim\LaravelJsonContextLogging\Contracts\LoggerFactoryInterface;
use Czim\LaravelJsonContextLogging\Factories\LoggerFactory;
use Illuminate\Support\ServiceProvider;

class JsonContextLoggingServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->bootConfig();
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerInterfaceBindings();
    }


    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            realpath(dirname(__DIR__) . '/../config/json-context-logging.php'),
            'json-context-logging'
        );
    }

    protected function registerInterfaceBindings(): void
    {
        $this->app->singleton(LoggerFactoryInterface::class, LoggerFactory::class);
    }

    protected function bootConfig(): void
    {
        $this->publishes([
            realpath(dirname(__DIR__) . '/../config/json-context-logging.php') => config_path('json-context-logging.php'),
        ]);
    }

}
