<?php
/**
 * User: Lessmore92
 * Date: 7/9/2020
 * Time: 3:14 AM
 */

namespace Lessmore92\EloquentMarshall\Facades;

use Illuminate\Support\Facades\Facade;

class EloquentMarshall extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'eloquent-marshall';
    }
}
