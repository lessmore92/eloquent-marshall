<?php
/**
 * User: Lessmore92
 * Date: 2/16/2020
 * Time: 11:00 PM
 */

namespace Lessmore92\EloquentMarshall\Foundation;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FilterFactory
{
    /**
     * Namespace of the model filter.
     *
     * @var string
     */
    protected $namespace = '';

    public function make(string $name, Model $model)
    {
        $this->resolveFilter($name, $model);
        $filter = app($this->getNamespace());
        return $filter;
    }

    /**
     * @return string
     */
    private function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * Resolve namespace filters.
     *
     * @param string $filter
     * @param Model $model
     */
    private function resolveFilter(string $filter, Model $model): void
    {
        $namespace = $this->sanitizeNamespace($this->resolveNamespace($filter, $model));
        $this->setNamespace($namespace);
    }

    /**
     * Resolve default or custom namespace.
     *
     * @param string $filter
     * @param Model $model
     *
     * @return string
     */
    private function resolveNamespace(string $filter, Model $model): string
    {
        $class_namespace = 'App\\EloquentMarshall\\';
        return $class_namespace . class_basename($model) . '\\' . $this->resolveFilterName($filter);
    }

    /**
     * Sanitizing a namespace.
     *
     * @param $namespace
     *
     * @return string|string[]
     */
    private function sanitizeNamespace($namespace)
    {
        return str_replace('\\\\', '\\', $namespace);
    }

    /**
     * @param string $filter
     *
     * @return string
     */
    private function resolveFilterName(string $filter): string
    {
        return Str::studly($filter) . 'Search';
    }
}
