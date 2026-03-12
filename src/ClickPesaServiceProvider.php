<?php

namespace FeedTan\ClickPesa;

use Illuminate\Support\ServiceProvider;

class ClickPesaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/clickpesa.php',
            'clickpesa'
        );

        $this->app->singleton(ClickPesaClient::class, function ($app) {
            return new ClickPesaClient($app['config']['clickpesa']);
        });

        $this->app->bind(ClickPesa::class, function ($app) {
            return new ClickPesa($app[ClickPesaClient::class]);
        });

        // Register command
        $this->commands([
            Commands\InstallCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/clickpesa.php' => config_path('clickpesa.php'),
            ], 'clickpesa-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'clickpesa-migrations');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'clickpesa');
    }
}
