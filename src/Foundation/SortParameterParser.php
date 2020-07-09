<?php
/**
 * User: Lessmore92
 * Date: 2/20/2020
 * Time: 00:37 PM
 */

namespace Lessmore92\EloquentMarshall\Foundation;


use Lessmore92\EloquentMarshall\Contracts\SortParameterParserInterface;

class SortParameterParser implements SortParameterParserInterface
{
    public function parse($sort_parameter): array
    {
        $_sorts         = [];
        $_default_order = /** @scrutinizer ignore-call */
            config('eloquent_marshall.default_sort_order', 'desc');

        if (!$this->sort_is_parsable($sort_parameter))
        {
            return $_sorts;
        }

        if ($this->sort_is_array($sort_parameter))
        {
            $sorts = $sort_parameter;
        }
        else
        {
            $sorts   = array();
            $sorts[] = $sort_parameter;
        }

        foreach ($sorts as $sort)
        {
            if ($this->sort_item_have_order($sort))
            {
                list($col, $order) = @explode(':', $sort);
            }
            else
            {
                $col   = $sort;
                $order = $_default_order;
            }
            $_sorts[$col] = $order;
        }

        return $_sorts;
    }

    /**
     * @param string $sort
     * @return bool
     */
    private function sort_is_parsable($sort)
    {
        return is_array($sort) || (is_string($sort) && strlen(trim($sort))) > 0;
    }

    /**
     * @param string $sort
     * @return bool
     */
    private function sort_is_array($sort)
    {
        return is_array($sort);
    }

    /**
     * @param string $sort
     * @return bool
     */
    private function sort_item_have_order($sort)
    {
        return strpos($sort, ':') !== false;
    }
}
