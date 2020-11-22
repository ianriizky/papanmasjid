<?php

namespace Ianrizky\MoslemPray;

use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/moslempray.php' => config_path('moslempray.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Manager::class, function (ContainerContract $app) {
            return new Manager($app);
        });
    }
}
