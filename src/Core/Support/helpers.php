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
