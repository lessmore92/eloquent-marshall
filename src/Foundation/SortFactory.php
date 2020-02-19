<?php
/**
 * User: Lessmore92
 * Date: 2/16/2020
 * Time: 11:00 PM
 */

namespace Lessmore92\EloquentMarshall\Foundation;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SortFactory
{
    /**
     * Namespace of the model sort.
     *
     * @var string
     */
    protected $namespace = '';

    public function make(string $name, Model $model)
    {
        $this->resolveSort($name, $model);
        if (class_exists($this->getNamespace()))
        {
            $sort = app($this->getNamespace());
            return $sort;
        }
        return false;
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
     * Resolve namespace sorts.
     *
     * @param string $sort
     * @param Model $model
     */
    private function resolveSort(string $sort, Model $model): void
    {
        $namespace = $this->sanitizeNamespace($this->resolveNamespace($sort, $model));
        $this->setNamespace($namespace);
    }

    /**
     * Resolve default or custom namespace.
     *
     * @param string $sort
     * @param Model $model
     *
     * @return string
     */
    private function resolveNamespace(string $sort, Model $model): string
    {
        $class_namespace = 'App\\EloquentMarshall\\';
        return $class_namespace . class_basename($model) . '\\' . $this->resolveSortName($sort);
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
     * @param string $sort
     *
     * @return string
     */
    private function resolveSortName(string $sort): string
    {
        return Str::studly($sort) . 'Sort';
    }
}
