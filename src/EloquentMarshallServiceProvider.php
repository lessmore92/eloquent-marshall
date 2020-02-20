<?php

namespace Lessmore92\EloquentMarshall;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Lessmore92\EloquentMarshall\Contracts\SortParameterParserInterface;
use Lessmore92\EloquentMarshall\Foundation\ParameterStore;
use Lessmore92\EloquentMarshall\Foundation\SortParameterParser;

class EloquentMarshallServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            SortParameterParserInterface::class,
            SortParameterParser::class
        );

        $this->app->singleton('EloquentMarshall\Parameters', function () {
            return new ParameterStore();
        });

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

        Builder::/** @scrutinizer ignore-call */ macro('sortable', function ($sort_param_name = null) {
            $sortable = new Sortable();
            if ($sort_param_name === null)
            {
                $sort_param_name = /** @scrutinizer ignore-call */
                    config('eloquent_marshall.sort_param_name', 'sort');
            }
            $sorts = Request::get($sort_param_name);
            /**
             * @var ParameterStore $store
             */
            $store = /** @scrutinizer ignore-call */
                resolve('EloquentMarshall\Parameters');
            $store->add($sort_param_name, $sorts);

            $parser = /** @scrutinizer ignore-call */
                resolve(SortParameterParserInterface::class);
            $sorts  = $parser->parse($sorts);

            /**
             * @var Builder $this
             */
            $query = $sortable->for($this, $sorts);

            return $query;
        });

        Builder::/** @scrutinizer ignore-call */ macro('getAppendableParameters', function () {
            /**
             * @var ParameterStore $store
             */
            $store = resolve('EloquentMarshall\Parameters');
            return $store->get();
        });
    }
}
