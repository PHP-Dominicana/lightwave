<?php

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
