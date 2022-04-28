<?php

namespace Bakgul\Kernel\Helpers;

use Bakgul\Kernel\Tasks\ConvertCase;

class Convention
{
    public static function class(string $value, ?bool $isSingular = true)
    {
        return $value ? ConvertCase::pascal($value, $isSingular) : '';
    }

    public static function namespace($value, $isSingular = false)
    {
        return $value ? ConvertCase::pascal($value, $isSingular) : '';
    }

    public static function method($value, $isSingular = null)
    {
        return $value ? ConvertCase::camel($value, $isSingular) : '';
    }

    public static function var($value)
    {
        return $value ? ConvertCase::camel($value, true) : '';
    }

    public static function table($value, ?bool $isSingular = false)
    {
        return $value ? ConvertCase::snake($value, $isSingular) : '';
    }

    public static function affix(string $value, bool $isSingular = true)
    {
        return $value ? ConvertCase::pascal($value, $isSingular) : '';
    }

    public static function folder(string $value, string $case = "pascal", bool $isSingular = null): string
    {
        return $value ? ConvertCase::_($value, $case, $isSingular) : '';
    }
}
