<?php

namespace DenizTezcan\BolRetailer\Facades;

use Illuminate\Support\Facades\Facade;

class BolRetailerAPI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bolcom-retailer';
    }
}
