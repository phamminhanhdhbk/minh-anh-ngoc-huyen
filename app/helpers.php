<?php

if (!function_exists('setting')) {
    /**
     * Get site setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\SiteSetting::get($key, $default);
    }
}
