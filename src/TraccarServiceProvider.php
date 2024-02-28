<?php
/*
 * Author: WOLF
 * Name: TraccarServiceProvider.php
 * Modified : mar., 27 févr. 2024 09:24
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar;

use Composer\InstalledVersions;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use MrWolfGb\Traccar\Middleware\TraccarSessionMiddleware;
use MrWolfGb\Traccar\Services\TraccarService;

class TraccarServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'traccar');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'traccar');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->app->singleton(TraccarSessionMiddleware::class, function (Application $app) {
            return new TraccarSessionMiddleware(app(TraccarService::class));
        });
        /** @var Router $router */
        $router = $this->app->make('router');
        $router->aliasMiddleware('TraccarSession', TraccarSessionMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('traccar.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/traccar'),
            ], 'views');*/

            // Publishing assets.
//            $this->publishes([
//                __DIR__ . '/../resources/assets' => public_path('vendor/traccar'),
//            ], 'assets');

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/traccar'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                Commands\SyncDevicesCommand::class,
                //Commands\WsListenCommand::class
            ]);

            AboutCommand::add('Traccar', fn() => [
                'Version' => InstalledVersions::getPrettyVersion('mr-wolf-gb/traccar'),
                'Base Url' => AboutCommand::format(config('traccar.base_url'), console: fn($value) => "<fg=yellow;options=bold>$value</>"), // @phpstan-ignore-line
                'Sync Devices' => AboutCommand::format(config('traccar.devices.store_in_database'), console: fn($value) => $value ? '<fg=yellow;options=bold>ENABLED</>' : 'OFF'),
            ]);

            //$method = method_exists($this, 'publishesMigrations') ? 'publishesMigrations' : 'publishes';

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'traccar');
        // Register the main class to use with the facade
        $this->app->singleton(
            abstract: TraccarService::class,
            concrete: fn() => new TraccarService(
                baseUrl: config('traccar.base_url'), // @phpstan-ignore-line
                email: config('traccar.auth.username'), // @phpstan-ignore-line
                password: config('traccar.auth.password'), // @phpstan-ignore-line
                token: config('traccar.auth.token'), // @phpstan-ignore-line
                headers: [
                    'Accept' => 'application/json'
                ]
            )
        );
    }

}
