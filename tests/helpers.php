<?php

if (!function_exists('create')) {
    function create($class, $attributes = [], $times = null, $states = null)
    {
        if (is_null($states)) {
            return factory($class, $times)->create($attributes);
        }

        return factory($class, $times)->states($states)->create($attributes);
    }
}
if (!function_exists('make')) {
    function make($class, $attributes = [], $times = null, $states = null)
    {
        if (is_null($states)) {
            return factory($class, $times)->make($attributes);
        }

        return factory($class, $times)->states($states)->make($attributes);
    }
}
if (!function_exists('raw')) {
    function raw($class, $attributes = [], $times = null, $states = null)
    {
        if (is_null($states)) {
            return factory($class, $times)->raw($attributes);
        }

        return factory($class, $times)->states($states)->raw($attributes);
    }
}
