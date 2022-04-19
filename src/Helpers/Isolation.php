<?php

namespace Bakgul\Kernel\Helpers;

class Isolation
{
    public static function chunk(string $chunks)
    {
        return explode(Settings::seperators('chunk'), $chunks);
    }

    public static function package(?string $name): string
    {
        return explode(Settings::seperators('folder'), $name ?? '')[0];
    }

    public static function file(string $chunk)
    {
        return explode(Settings::seperators('part'), $chunk);
    }

    public static function name(string $path): string
    {
        return str_replace('.' . self::extension($path), '', self::part(
            Text::getTail($path, Settings::seperators('folder')), 0
        ));
    }

    public static function subs(string $path): string
    {
        return Path::glue(array_slice(Text::serialize($path, Settings::seperators('folder')), 0, -1));
    }

    public static function tasks(string $name, string $type = ''): array
    {
        $tasks = explode(Settings::seperators('addition'), self::part($name, 1));

        return $type && !array_filter($tasks)
            ? Settings::files(self::type($type) . ".tasks")
            : $tasks;
    }

    public static function types(string $type): array
    {
        return array_map(
            fn ($x) => [self::type($x), self::variation($x), self::extra($x)],
            explode(Settings::seperators('part'), $type)
        );
    }

    public static function type(string $type): string
    {
        return self::part($type, 0);
    }

    public static function extra(string $type): string
    {
        return self::part($type, 2);
    }

    public static function key(string $value)
    {
        return self::type($value);
    }

    public static function variation(string $type): string
    {
        return self::part($type, 1);
    }

    public static function extension(string $path): string
    {
        return implode('.', array_slice(explode('.', $path), 1));
    }

    public static function section(string $page, string $section): string
    {
        foreach ([' ', '-', '_', ''] as $seperator) {
            if (str_contains($section, "{$page}{$seperator}")) return str_replace("{$page}{$seperator}", '', $section);
        }

        return $section;
    }

    public static function edges(array $object)
    {
        return [array_shift($object), array_pop($object), $object];
    }

    public static function option(string $name): string
    {
        return str_replace(['}', '='], '', array_reverse(explode('|', $name))[0]);
    }

    public static function part($value, $index, $seperator = 'modifier'): string
    {
        return Arry::get(explode(Settings::seperators($seperator), $value), $index) ?? '';
    }
}
