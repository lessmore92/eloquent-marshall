<?php
/**
 * User: Lessmore92
 * Date: 2/16/2020
 * Time: 1:26 AM
 */

namespace Lessmore92\EloquentMarshall\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Search
{
    public function apply(Builder $builder, $value): Builder;
}
