<?php

namespace DenizTezcan\BolRetailerV3;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BolServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bolcom-retailer-v3.php' => config_path('bolcom-retailer-v3.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('bolcom-retailer-v3', function () {
            return new BolRetailerV3();
        });
    }

    public function provides()
    {
        return ['bolcom-retailer-v3'];
    }
}
