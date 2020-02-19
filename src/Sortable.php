<?php
/**
 * User: Lessmore92
 * Date: 2/16/2020
 * Time: 1:11 AM
 */

namespace Lessmore92\EloquentMarshall;

use Illuminate\Database\Eloquent\Builder;
use Lessmore92\EloquentMarshall\Contracts\Sort;
use Lessmore92\EloquentMarshall\Foundation\SortFactory;


class Sortable
{
    /**
     * @var SortFactory
     */
    protected $sortFactory;

    public function __construct()
    {
        $this->sortFactory = new SortFactory();
    }

    /**
     * @param Builder $query
     * @param array $sorts
     * @return Builder
     */
    public function for($query, $sorts)
    {
        $this->applySorts($query, $sorts);

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $sorts
     * @return Builder
     */
    public function applySorts($query, $sorts)
    {
        foreach ($sorts as $sort => $value)
        {
            $_sort = $this->sortFactory->make($sort, $query->/** @scrutinizer ignore-call */ getModel());
            if ($_sort instanceof Sort)
            {
                $query = $_sort->apply($query, $value);
            }
        }
        return $query;
    }
}
