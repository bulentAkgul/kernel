<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Path;

class GenerateNamespace
{
    public static function _(array $attr, string $path = ''): string
    {
        return implode('\\', array_filter([
            self::root($attr), self::package($attr), self::family($attr), self::tail($path)
        ]));
    }

    public static function root(array $attr): string
    {
        if (Settings::standalone('package')) return Settings::identity('namespace');
        
        if (Settings::standalone('laravel') || !$attr['root']) return '';

        return Arry::value(Settings::roots(), $attr['root'], 'folder', '=', 'namespace')
            ?: Settings::identity('namespace')
            ?: ucfirst($attr['root']);
    }

    public static function package(array $attr): string
    {
        return Settings::standalone() || !$attr['package'] ? '' : ucfirst($attr['package']);
    }

    public static function family(array $attr): string
    {
        return match (true) {
            Settings::standalone('package') => '',
            Arry::get($attr, 'family') == null => '',
            $attr['family'] != 'src' => ucfirst($attr['family']),
            Settings::standalone('laravel') => 'App',
            default => $attr['package'] ? '' : 'App'
        };
    }

    public static function tail(string $path)
    {
        return Path::toNamespace($path);
    }
}
