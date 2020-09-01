<?php

namespace HashyooFast\Providers;

use HashyooFast\LaravelRepository;
use Illuminate\Support\ServiceProvider;

class LaravelRepositoryProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
//        $path = realpath(__DIR__ . '/../../config/config.php');
//        $this->publishes(array($path => config_path('hashyoo-des3.php')), 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 在容器中注册
        $this->app->singleton('LaravelRepository', function () {
            return new LaravelRepository();
        });
    }
}
