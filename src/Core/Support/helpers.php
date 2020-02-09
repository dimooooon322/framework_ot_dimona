<?php
if(!function_exists("escapeValues"))
{
    function escapeValues(&$value, $key)
    {
        $value = htmlspecialchars($value);
    }
}
