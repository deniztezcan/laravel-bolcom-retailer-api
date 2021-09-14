<?php

namespace DenizTezcan\BolRetailer;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BolServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bolcom-retailer.php' => config_path('bolcom-retailer.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('bolcom-retailer', function () {
            return new BolRetailerAPI();
        });
    }

    public function provides()
    {
        return ['bolcom-retailer'];
    }
}
