<?php

if (! function_exists('setting')) {
    function setting($key, $default = null)
    {
        static $cache = [];

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        return $cache[$key] = \App\Models\Setting::get($key, $default);
    }
}
