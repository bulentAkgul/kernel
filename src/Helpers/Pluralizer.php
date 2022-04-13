<?php

namespace Bakgul\Kernel\Helpers;

use Illuminate\Support\Str;

class Pluralizer
{
    const IS_SINGULAR = ['S' => true, 'P' => false, 'X' => null];

    public static function make(array|string $value, array|string $data = '', ?bool $isSingular = null)
    {
        return self::run($value, $isSingular ?? self::set($data));
    }

    public static function run(array|string $value, ?bool $isSingular = null): array|string
    {
        $last = is_array($value) ? array_pop($value) : $value;

        $last = $isSingular === null ? $last : ($isSingular ? Str::singular($last) : Str::plural($last));

        return is_array($value) ? array_merge($value, [$last]) : $last;
    }

    public static function set(array|string $data): ?bool
    {
        if (is_string($data)) return Arry::get(self::IS_SINGULAR, $data);

        $value = Arry::get($data, 'name_count');

        return $value == null ? $value : Arry::get(self::IS_SINGULAR, $value);
    }
}
