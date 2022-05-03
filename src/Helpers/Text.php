<?php

namespace Bakgul\Kernel\Helpers;

use Illuminate\Support\Arr;

class Text
{
    public static function append(string $str = '', string $glue = DIRECTORY_SEPARATOR): string
    {
        return $str ? "{$glue}{$str}" : $str;
    }

    public static function prepend(string $str = '', string $glue = DIRECTORY_SEPARATOR): string
    {
        return $str ? "{$str}{$glue}" : $str;
    }

    public static function inject(string $str = '', array $glue = []): string
    {
        if (!$str || !$glue) return $str;

        foreach (is_array($glue) ? array_reverse($glue) : [$glue] as $wrap) {
            $str = self::wrap($str, $wrap);
        }

        return $str;
    }

    public static function wrap(string $str, string $glue = DIRECTORY_SEPARATOR): string
    {
        if (!$str || !$glue) return $str;

        $glue = Arry::get([
            'sq' => ["'", "'"],
            'dq' => ['"', '"'],
            '{' => ['{', '}'],
            '(' => ['(', ')'],
            '[' => ['[', ']']
        ], $glue) ?? [$glue, $glue];

        return "{$glue[0]}{$str}{$glue[1]}";
    }

    public static function containsSome(string $str, string|array $search): bool
    {
        return array_reduce(
            is_array($search) ? $search : [$search],
            fn ($p, $c) => $p || str_contains($str, $c),
            false
        );
    }

    public static function containsNone(string $str, string|array $search): bool
    {
        return !self::containsSome($str, $search);
    }

    public static function containsAll(string $str, string|array $search): bool
    {
        return array_reduce(
            is_array($search) ? $search : [$search],
            fn ($p, $c) => $p && str_contains($str, $c),
            true
        );
    }

    public static function getTail(string $value = '', string $seperator = DIRECTORY_SEPARATOR)
    {
        return array_reverse(explode($seperator, $value))[0];
    }

    public static function changeTail(string $str, string $add, string $glue = DIRECTORY_SEPARATOR): string
    {
        return self::prepend(self::dropTail($str, $glue), $glue) . $add;
    }

    public static function dropTail(string $value = '', string $seperator = DIRECTORY_SEPARATOR, int $length = 1)
    {
        return implode($seperator, array_slice(explode($seperator, $value), 0, -1 * $length));
    }

    public static function capitalize(array|string $words, string $glue = '-')
    {
        return self::format('ucfirst', $words, $glue);
    }

    public static function toLower(array|string $words, string $glue = '-')
    {
        return self::format('strtolower', $words, $glue);
    }

    public static function toUpper(array|string $words, string $glue = '-')
    {
        return self::format('strtoupper', $words, $glue);
    }

    public static function format(string $method, array|string $words, string $glue = '-'): array
    {
        return array_map(fn ($x) => $method($x), is_string($words) ? explode($glue, $words) : $words);
    }

    public static function case(string $value)
    {
        if (str_contains($value, '-')) return 'kebab';
        if (str_contains($value, '_')) return 'snake';
        if (ctype_upper($value[0])) return 'pascal';
        return 'camel';
    }

    public static function replaceByGlue(array|string $value, string $glue = '-'): string
    {
        return is_array($value) ? $value : preg_replace('/[^A-Za-z0-9\-]/', $glue, $value);
    }

    public static function purify(string $string, string $characters = '', bool $append = true): string
    {
        return trim($string, $characters . ($append ? " ,;\t\n\r" : ""));
    }

    public static function replaceByMap(array $map, string $string, bool $append = false, string $glue = DIRECTORY_SEPARATOR): string
    {
        return str_replace(
            array_map(fn ($x) => "{{ {$x} }}", array_keys($map)),
            array_map(fn ($x) => $append ? self::append($x, $glue) : $x, array_values($map)),
            $string
        );
    }

    public static function seperate(array|string $value): array
    {
        if (is_array($value)) return $value;

        $words = explode('-', $value);

        if (count($words) > 1) return Arry::resolve($words);

        $words = explode('_', $value);

        if (count($words) > 1) return Arry::resolve($words);

        return ctype_upper($value) ? [strtolower($value)] : Arry::resolve(self::split($value, '/(?=[A-Z])/'));
    }

    public static function allBetweens(string $str, string $from, string $to, string $encapsulate = '', string $transform = null): array
    {
        return array_values(array_filter(
            array_map('trim', Arr::flatten(
                array_map(fn ($x) => explode($to, $x), explode($from, $str))
            )),
            fn ($x) => str_contains($str, "{{ $x }}")
        ));
    }

    public static function serialize(string $value, string $glue = DIRECTORY_SEPARATOR): array
    {
        return explode($glue, $value);
    }

    public static function split(string $text, string $pattern = '/\R/'): array
    {
        return preg_split($pattern, $text);
    }
}
