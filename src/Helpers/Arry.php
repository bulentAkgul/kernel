<?php

namespace Bakgul\Kernel\Helpers;

use Bakgul\Kernel\Functions\CompareValues;
use Illuminate\Support\Arr;

class Arry
{
    public static function random(array $array, int $length = 1): array
    {
        return array_slice(Arr::shuffle($array), 0, $length);
    }

    public static function assocMap(array $array, callable $callback = null)
    {
        return array_map($callback, array_keys($array), $array);
    }

    public static function combine(array $keys, array $values = [], $default = null)
    {
        $diff = count($keys) - count($values);

        $values = $diff == 0 ? $values : ($diff > 0
            ? [...$values, ...array_fill(0, $diff, $default)]
            : array_slice($values, 0, count($keys))
        );

        return array_combine($keys, $values);
    }

    public static function contains(string $search, array $array): bool
    {
        return count(array_filter($array, fn ($x) => str_contains($x, $search))) > 0;
    }

    public static function containsSome(array $search, array $array): bool
    {
        return !empty(array_intersect($search, $array));
    }

    public static function containsAt(string $search, array $array): ?int
    {
        foreach ($array as $i => $item) {
            if (str_contains($item, $search)) return $i;
        }

        return null;
    }

    public static function containsOn(string $search, array $array): string
    {
        $i = self::containsAt($search, $array);

        return $i !== null ? $array[$i] : '';
    }

    public static function drop(array $array, string|int $key = 'L')
    {
        if ($key == 'F') array_shift($array);
        if ($key == 'L') array_pop($array);
        if (self::has($key, $array)) unset($array[$key]);

        return $array;
    }

    public static function get(array $array, string|int $key = '')
    {
        if ($key == 0) return array_shift($array);
        if ($key == 'L') return array_pop($array);

        return self::has($key, $array) ? $array[$key] : null;
    }

    public static function isEqual(array $keys, array $src, array $check, bool $and = true): bool
    {
        return array_reduce($keys, fn ($p, $c) => $and ? $p && $src[$c] == $check[$c] : $p || $src[$c] == $check[$c], $and);
    }

    public static function extend(array $items, $separator = ','): array
    {
        return array_unique(array_reduce($items, function ($carry, $current) use ($separator) {
            return array_merge($carry, is_array($current)
                ? $current
                : array_filter(explode($separator, $string ?? '')));
        }, []));
    }

    public static function find(array $array, int|float|string|array $search, string $keys = '', string $operator = '='): ?array
    {
        $segments = array_filter(explode('.', $keys));

        foreach ($array as $key => $value) {
            foreach ($segments as $segment) {
                if (is_array($value) && self::has($segment, $value)) {
                    $value = $value[$segment];
                } else {
                    continue;
                }
            }

            $type = gettype($value);

            foreach (gettype($search) == 'array' ? $search : [$search] as $item) {
                if (match (true) {
                    $type == 'array' => in_array($item, $value),
                    in_array($type, ['integer', 'double']) => CompareValues::_($value, $item, $operator),
                    $type == 'string' => ($operator == '==' && $value == $item) || ($operator == '=' && str_contains($value, $item)),
                    default => false
                }) {
                    return [
                        'key' => $key,
                        'value' => $segments ? $array[$key] : $value
                    ];
                }
            }
        }

        return null;
    }

    public static function has(string $search, array $array, string $lookAt = 'key'): bool
    {
        if ($lookAt == 'key') return array_key_exists($search, $array);

        if ($lookAt == 'value') return in_array($search, array_values($array));

        return array_key_exists($search, $array) || in_array($search, array_values($array));
    }

    public static function hasAll(array $search, array $array, string $lookAt = 'value'): bool
    {
        return array_reduce(
            array_map(fn ($x) => self::has($x, $array, $lookAt), $search),
            fn ($carry, $current) => $carry && $current,
            true
        );
    }

    public static function hasAt(string $search, array $array): ?int
    {
        foreach ($array as $i => $item) {
            if ($item == $search) return $i;
        }

        return null;
    }

    public static function hasNot(string $search, array $array, string $lookAt = 'key'): bool
    {
        return !self::has($search, $array, $lookAt);
    }

    public static function hasSome(array $search, array $array, string $lookAt = 'value'): bool
    {
        return array_reduce(
            array_map(fn ($x) => self::has($x, $array, $lookAt), $search),
            fn ($carry, $current) => $carry || $current,
            false
        );
    }

    public static function map(array $array, callable $callback = null)
    {
        return Arr::isAssoc($array) ? self::assocMap($array, $callback) : array_map($callback, $array);
    }

    public static function sort(array $array, $ascending = true)
    {
        $ascending ? sort($array) : rsort($array);

        return $array;
    }

    public static function ksort(array $array)
    {
        ksort($array);

        return $array;
    }

    public static function purify(array $items, string $characters = '', bool $append = true): array
    {
        return array_map(fn ($x) => Text::purify($x, $characters, $append), $items);
    }

    public static function range (int $max, int $min = 0) {
        return array_filter($min > $max ? range($min, $min) : range($min, rand($min, $max)));
    }

    public static function unique(array $array): array
    {
        return array_values(array_unique(array_values($array)));
    }

    public static function resolve(array $array): array
    {
        return array_values(array_filter($array));
    }

    public static function unset(array $array, string|int $key)
    {
        if (self::has($key, $array)) unset($array[$key]);

        return $array;
    }

    public static function value(array $array, int|float|string $search, string $keys = '', string $operator = '=', string $pluck = '')
    {
        $found = self::find($array, $search, $keys, $operator);

        if (!$found) {
            return 'No item has been found in the given array.';
        }

        return self::has($pluck, $found['value'])
            ? $found['value'][$pluck]
            : "Found value doesn't have '{$pluck}' as a key.";
    }

    public static function stringify(array $value, string $glue = DIRECTORY_SEPARATOR): string
    {
        return implode($glue, $value);
    }
}
