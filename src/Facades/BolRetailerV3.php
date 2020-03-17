<?php

namespace DenizTezcan\BolRetailerV3\Facades;

use Illuminate\Support\Facades\Facade;

class BolRetailerV3 extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bolcom-retailer-v3';
    }
}
