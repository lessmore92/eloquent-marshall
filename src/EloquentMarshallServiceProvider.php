<?php

namespace Lessmore92\EloquentMarshall;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Lessmore92\EloquentMarshall\Contracts\SortParameterParserInterface;
use Lessmore92\EloquentMarshall\Foundation\SortParameterParser;

class EloquentMarshallServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            SortParameterParserInterface::class,
            SortParameterParser::class
        );

        Builder::/** @scrutinizer ignore-call */ macro('searchable', function ($inputs = null) {
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

        Builder::/** @scrutinizer ignore-call */ macro('sortable', function ($sorts = null) {
            $sortable = new Sortable();
            if ($sorts === null)
            {
                $sorts = Request::get(/** @scrutinizer ignore-call */ config('eloquent_marshall.sort_param_name', 'sort'));
            }

            $parser = /** @scrutinizer ignore-call */
                resolve(SortParameterParserInterface::class);
            $sorts  = $parser->parse($sorts);

            /**
             * @var Builder $this
             */
            $query = $sortable->for($this, $sorts);

            return $query;
        });
    }
}
