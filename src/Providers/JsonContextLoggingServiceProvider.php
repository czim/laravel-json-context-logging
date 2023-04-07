<?php

declare(strict_types=1);

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
        $this->mergeConfigFrom($this->configPath(), 'json-context-logging');
    }

    protected function registerInterfaceBindings(): void
    {
        $this->app->singleton(LoggerFactoryInterface::class, LoggerFactory::class);
    }

    protected function bootConfig(): void
    {
        $this->publishes([
            $this->configPath() => config_path('json-context-logging.php'),
        ]);
    }

    protected function configPath(): string
    {
        return realpath(dirname(__DIR__) . '/../config/json-context-logging.php');
    }
}
