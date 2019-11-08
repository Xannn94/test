<?php

use Illuminate\Container\Container;

if (! function_exists('dd')) {
    function dd($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        exit;
    }
}