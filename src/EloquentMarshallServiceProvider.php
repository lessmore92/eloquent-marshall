<?php

namespace Lessmore92\EloquentMarshall;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class EloquentMarshallServiceProvider extends ServiceProvider
{
    public function register()
    {
        Builder::macro('searchable', function ($inputs = null) {
            $searchable = new Searchable();
            if ($inputs === null)
            {
                $inputs = Request::all();
            }
            /**
             * @var Builder $this
             */
            $query = $searchable->for($this, $inputs);

            return $query;
        });
    }
}
