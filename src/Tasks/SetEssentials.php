<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Illuminate\Support\Arr;

class SetEssentials
{
    private static $essentials = [];

    public static function _()
    {
        foreach (self::keys() as $key) {
            self::merge(Arr::undot([$key => Settings::get($key)]));
        }
        ray(self::$essentials);
        return self::$essentials;
    }

    private static function keys()
    {
        return array_merge(
            self::$keys,
            file_exists(Path::glue([base_path(), 'vendor', 'bakgul', 'laravel-resource-creator']))
                ? self::$resource_keys
                : []
        );
    }

    private static function merge($essential)
    {
        $key = array_keys($essential)[0];

        if (!Arr::has(self::$essentials, $key)) {
            self::$essentials[$key] = $essential[$key];
            return;
        }

        self::$essentials[$key] = array_merge(self::$essentials[$key], $essential[$key]);
    }

    private static $keys = [
        'main.standalone_laravel',
        'main.standalone_package',
        'main.each_controller_has_route',
        'main.packages_root',
        'main.expand_http_in_packages',
        'roots',
        'requires',
    ];

    private static $resource_keys = [
        'resource_options.each_page_has_controller',
        'resource_options.tasks_as_sections',
        'resource_options.css',
    ];
}
