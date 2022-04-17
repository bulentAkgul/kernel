<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Text;

class PurifySchema
{
    public static function _(array $keys, string $schema, array $caps = ['{{ ', ' }}']): string
    {
        $glue = self::glue($caps);

        $parts = self::split($schema, $caps, $glue, $keys);

        return self::output(self::build($parts, $caps, $glue), $caps);
    }

    private static function glue(array $caps): string
    {
        return implode('', array_reverse($caps));
    }

    private static function split(string $schema, array $caps, string $glue, array $keys): array
    {
        return [
            'before' => $b = self::get($schema, $caps, 0),
            'after' => $a = self::get($schema, $caps, 1),
            'placeholders' => self::placeholders($schema, [$b, $a], $caps, $glue, $keys)
        ];
    }

    private static function get($schema, $caps, $key)
    {
        return Arry::get(explode($caps[$key], $schema), $key == 1 ? 'L' : 0);
    }

    private static function placeholders(string $schema, array $remove, array  $caps, string $glue, array $keys): array
    {
        return array_filter(array_map(
            fn ($x) => str_replace($caps, '', $x),
            explode($glue, str_replace($remove, '', $schema))
        ), fn ($x) => in_array($x, $keys));
    }

    private static function build(array $parts, array $caps, string $glue): string
    {
        return $parts['before'] . self::schema($parts, $caps, $glue) . $parts['after'];
    }

    private static function schema(array $parts, array $caps, string $glue): string
    {
        return $caps[0] . implode($glue, $parts['placeholders']) . $caps[1];
    }

    private static function output(string $path, array $caps): string
    {
        return str_replace(implode('', $caps), '', $path);
    }
}
