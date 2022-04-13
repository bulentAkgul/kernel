<?php

namespace Bakgul\Kernel\Helpers;

class Requirement
{
    public static function route($router)
    {
        return self::check($router, 'route');
    }

    public static function store($type)
    {
        return self::check(Settings::resources("{$type}.options.store"), 'store');
    }

    public static function css()
    {
        return self::check(Settings::resourceOptions('css'), 'css');
    }

    public static function view($view)
    {
        return self::check($view, 'view');
    }

    public static function check($value, $key)
    {
        return $value && !in_array($value, Settings::prohibitives($key));
    }
}