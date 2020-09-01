<?php

namespace HashyooFast\Facade;

use Illuminate\Support\Facades\Facade;

class LaravelRepository extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'LaravelRepository';
    }
}