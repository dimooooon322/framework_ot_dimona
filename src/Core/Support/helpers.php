<?php

use Core\Support\Collection;

if (!function_exists("escapeValues")) {
    function escapeValues(&$value, $key)
    {
        $value = htmlspecialchars($value);
    }
}

if (!function_exists("collect")) {
    function collect(array $array)
    {
        return new Collection($array);
    }
}

if (!function_exists("database")) {
    function database()
    {
        foreach ($GLOBALS as $var) {
            if ($var instanceof \Core\App) {
                $app = $var;
                break;
            }
        }
        return isset($app) ? $app->getDatabase() : null;
    }
}

