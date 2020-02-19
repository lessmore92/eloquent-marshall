<?php
/**
 * User: Lessmore92
 * Date: 2/20/2020
 * Time: 00:31 AM
 */

namespace Lessmore92\EloquentMarshall\Contracts;

interface SortParameterParserInterface
{
    /**
     * @param string $sort_parameter
     * @return array
     */
    public function parse($sort_parameter): array;
}
