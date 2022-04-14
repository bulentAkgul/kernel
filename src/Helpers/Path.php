<?php

namespace Bakgul\Kernel\Helpers;

use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Helpers\Convention;

class Path
{
    public static function head($package = '', $family = '')
    {
        $family = $family ? Settings::folders($family) : $family;

        if (Settings::standalone('package')) return base_path($family);

        if (Settings::standalone('laravel')) return base_path($family == 'src' ? 'app' : $family);

        $root = Package::root($package ?? '');

        return $root
            ? Path::base([Package::container(false), ...array_filter([$root, $package, $family])])
            : base_path($family == 'src' ? 'app' : $family);
    }

    public static function root(string $path = ''): string
    {
        return Settings::standalone()
            ? base_path($path)
            : base_path(Package::container(false) . Text::append($path));
    }

    public static function base(array $parts, string $glue = DIRECTORY_SEPARATOR): string
    {
        return base_path(self::glue($parts, $glue));
    }

    public static function glue(array $parts, string $glue = DIRECTORY_SEPARATOR): string
    {
        return Arry::stringify($parts, $glue);
    }

    public static function package(string $name, string $path = ''): string
    {
        return self::head($name) . Text::append($path);
    }

    public static function make(string|array $path, string $case = 'pascal', string $glue = DIRECTORY_SEPARATOR): array
    {
        return array_map(
            fn ($x) => ConvertCase::_($x, $case),
            is_string($path) ? array_filter(explode($glue, $path)) : $path
        );
    }

    public static function stringify(array $path, bool $isFull = false): string
    {
        return array_reduce(
            array_values($path),
            fn ($p, $n) =>  $p . Text::append(is_string($n) ? $n : self::glue([$isFull ? $n : []]))
        );
    }

    public static function toNamespace($path)
    {
        return implode('\\', array_map(function ($folder) {
            return Arry::has($folder, Settings::folders(), 'value')
                || Text::case($folder) == 'pascal'
                ? $folder
                : Convention::namespace($folder, null);
        }, array_filter(Text::serialize($path))));
    }

    public static function realBase(string $path = '')
    {
        return Text::getTail(base_path()) == Settings::folders('test_base')
            ? Text::dropTail(base_path()) . Text::append($path)
            : base_path($path);
    }
}
