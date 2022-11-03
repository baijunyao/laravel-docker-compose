<?php

declare(strict_types=1);

namespace Baijunyao\LaravelDockerCompose;

use Baijunyao\LaravelDockerCompose\Console\PublishCommand;
use Illuminate\Support\ServiceProvider;

class LaravelDockerComposeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/laravel-docker-compose.php' => $this->app->configPath('laravel-docker-compose.php'),
            ],
            [
                'laravel-docker-compose',
            ]
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-docker-compose.php', 'laravel-docker-compose'
        );
    }
}
