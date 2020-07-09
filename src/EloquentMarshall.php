<?php
/**
 * User: Lessmore92
 * Date: 7/9/2020
 * Time: 3:16 AM
 */

namespace Lessmore92\EloquentMarshall;


class EloquentMarshall
{
    private $config;

    public function __construct()
    {
        $this->config = /** @scrutinizer ignore-call */
            config('eloquent_marshall');
    }

    /**
     * @param string $key
     * @return string
     */
    public function getSortingUrl($key)
    {
        $sorts     = $this->getSortsParameters();
        $new_order = $this->config['default_sort_order'];
        $new_sort  = [];
        foreach ($sorts as $sort)
        {
            list($order_by, $order) = array_pad(explode(':', $sort, 2), 2, null);
            if (is_null($order))
            {
                $order = $this->config['default_sort_order'];
            }
            if ($order_by == $key)
            {
                $new_order = 'asc';
                if (strtolower($order) == 'asc')
                {
                    $new_order = 'desc';
                }
                $new_sort[$this->config['sort_param_name']][$order_by] = $order_by . ':' . $new_order;
            }
            else
            {
                $new_sort[$this->config['sort_param_name']][$order_by] = $order_by . ':' . $order;
            }
        }
        if (!isset($new_sort[$this->config['sort_param_name']][$key]))
        {
            $new_sort[$this->config['sort_param_name']][$key] = $key . ':' . $new_order;
        }

        $new_sort[$this->config['sort_param_name']] = array_values($new_sort[$this->config['sort_param_name']]);

        $clean_url = $this->getFullUrlWithoutSortParameter();

        return $this->addSortParameterToUrl($clean_url, $new_sort);
    }

    /**
     * @param string $key
     * @param string $no_order_class
     * @param string $asc_class
     * @param string $desc_class
     * @return string
     */
    public function getSortingClass($key, $no_order_class, $asc_class, $desc_class)
    {
        $sorts = $this->getSortsParameters();
        $out   = $no_order_class;
        foreach ($sorts as $sort)
        {
            list($order_by, $order) = array_pad(explode(':', $sort, 2), 2, null);

            if (is_null($order))
            {
                $order = $this->config['default_sort_order'];
            }
            if ($order_by == $key)
            {
                if (strtolower($order) == 'asc')
                {
                    $out = $asc_class;
                }
                else
                {
                    $out = $desc_class;
                }
                break;
            }
        }
        return $out;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function haveSorting($key = null)
    {
        $sorts = $this->getSortsParameters();
        if ($key)
        {
            $found = false;
            foreach ($sorts as $sort)
            {
                list($order_by) = array_pad(explode(':', $sort, 2), 2, null);

                if ($order_by == $key)
                {
                    $found = true;
                }
            }
            return $found;
        }
        return count($sorts) > 0;
    }

    /**
     * @return array
     */
    private function getSortsParameters()
    {
        $sorts = request($this->config['sort_param_name'], []);
        if (!is_array($sorts))
        {
            $sorts = [$sorts];
        }
        return $sorts;
    }

    /**
     * @return string
     */
    private function getFullUrlWithoutSortParameter()
    {
        $url   = url()->current();
        $query = request()->query();
        unset($query[$this->config['sort_param_name']]);
        return $query ? $url . '?' . http_build_query($query) : $url;
    }

    /**
     * @param string $url
     * @param array $sorts
     * @return string
     */
    private function addSortParameterToUrl($url, $sorts)
    {
        return strpos($url, '?') !== false ? $url . '&' . http_build_query($sorts) : $url . '?' . http_build_query($sorts);
    }
}
