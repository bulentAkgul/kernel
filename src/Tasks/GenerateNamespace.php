<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Package;
use Bakgul\Kernel\Helpers\Path;

class GenerateNamespace
{
    public static function _(array $attr, string|array $path = ''): string
    {
        return implode('\\', array_filter([
            self::root(Arry::get($attr, 'root')),
            $p = self::package(Arry::get($attr, 'package')),
            self::family(Arry::get($attr, 'family'), $p),
            self::tail($path)
        ]));
    }

    public static function root(?string $root): string
    {
        if (Settings::standalone('laravel')) return '';

        if (Settings::standalone('package')) return Settings::identity('namespace');

        return $root ? Arry::value(Settings::roots(), $root, 'folder', '=', 'namespace') : '';
    }

    public static function package(?string $package): string
    {
        return !Settings::standalone() && Package::root($package) ? ConvertCase::pascal($package) : '';
    }

    public static function family(?string $family, ?string $package): string
    {
        return match (true) {
            !$family => '',
            $family != 'src' => ConvertCase::pascal($family),
            Settings::standalone('laravel') => 'App',
            !Settings::standalone() && !$package => 'App',
            default => ''
        };
    }

    public static function tail(string|array $path)
    {
        return Path::toNamespace($path);
    }
}
