<?php
/**
 * User: Lessmore92
 * Date: 2/21/2020
 * Time: 00:48 PM
 */

namespace Lessmore92\EloquentMarshall\Foundation;

use Illuminate\Support\Collection;

class ParameterStore
{
    /**
     * @var Collection
     */
    protected $store;

    public function __construct()
    {
        $this->store = collect();
    }

    public function add($name, $value)
    {
        $this->store[$name] = $value;
    }

    public function get()
    {
        return $this->store->toArray();
    }
}
