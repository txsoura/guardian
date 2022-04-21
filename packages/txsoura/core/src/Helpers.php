<?php

namespace Txsoura\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait Helpers
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @return array
     */
    function getParams()
    {
        return $this->params;
    }

    /**
     * Get a subset of the items from the params.
     *
     * @param  array|string  $keys
     * @return array
     */
    public function onlyParams(array $keys)
    {
        return Arr::only($this->getParams(), $keys);
    }

    /**
     * @param array $params
     * @return $this
     */
    function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    function setRequest(Request $request)
    {
        $this->request = $request;
        $this->mergeParam($request->all());
        return $this;
    }

    function getParam($key, $default = null)
    {
        return Arr::get($this->getParams(), $key, $default);
    }

    function setParam($key, $value)
    {
        Arr::set($this->params, $key, $value);
        return $this;
    }

    function hasParam($key)
    {
        return Arr::has($this->params, $key);
    }

    function isEmpty($key)
    {
        $value = $this->getParam($key);
        return empty($value);
    }

    function mergeParam(array $data)
    {
        return $this->params = array_merge($this->getParams(), $data);
    }
}
