<?php
/**
 * User: Lessmore92
 * Date: 2/16/2020
 * Time: 1:11 AM
 */

namespace Lessmore92\EloquentMarshall;

use Illuminate\Database\Eloquent\Builder;
use Lessmore92\EloquentMarshall\Contracts\Search;
use Lessmore92\EloquentMarshall\Foundation\FilterFactory;
use Lessmore92\EloquentMarshall\Foundation\ParameterStore;


class Searchable
{
    /**
     * @var FilterFactory
     */
    protected $filterFactory;

    /**
     * @var ParameterStore
     */
    protected $parameter_sotre;

    public function __construct()
    {
        $this->filterFactory   = new FilterFactory();
        $this->parameter_sotre = /** @scrutinizer ignore-call */
            resolve('EloquentMarshall\Parameters');
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function for($query, $filters)
    {
        $this->applyFilters($query, $filters);

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function applyFilters($query, $filters)
    {
        foreach ($filters as $filter => $value)
        {
            $_filter = $this->filterFactory->make($filter, $query->/** @scrutinizer ignore-call */ getModel());
            if ($_filter instanceof Search)
            {
                $query = $_filter->apply($query, $value);
                $this->parameter_sotre->add($filter, $value);
            }
        }
        return $query;
    }
}
