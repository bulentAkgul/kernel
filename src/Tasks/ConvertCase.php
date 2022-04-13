<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Pluralizer;
use Bakgul\Kernel\Helpers\Text;

class ConvertCase
{
    public static function _(string $value, string $case = null, bool $isSingular = null): string
    {
        if (!$value) return $value;

        $case = $case ?? Text::case($value);

        return self::$case($value, $isSingular, false);
    }

    public static function kebab(string $value, bool $isSingular = null, bool $returnArray = false): array|string
    {
        return self::output(Text::toLower(self::prepare($value, $isSingular)), 'kebab', $returnArray);
    }

    public static function snake(string $value, bool $isSingular = null, bool $returnArray = false): array|string
    {
        return self::output(Text::toLower(self::prepare($value, $isSingular)), 'snake', $returnArray);
    }

    public static function pascal(string $value, bool $isSingular = null, bool $returnArray = false): array|string
    {
        return self::output(Text::capitalize(self::prepare($value, $isSingular)), 'pascal', $returnArray);
    }

    public static function camel(string $value, bool $isSingular = null, bool $returnArray = false): array|string
    {
        $name = Text::capitalize(self::prepare($value, $isSingular));

        $name[0] = strtolower($name[0]);

        return self::output($name, 'camel', $returnArray);
    }

    public static function prepare(array|string $value, ?bool $isSingular)
    {
        return Pluralizer::run(Text::seperate(Text::replaceByGlue($value)), $isSingular);
    }

    private static function output(array $name, string $case, bool $returnArray)
    {
        return $returnArray ? $name : implode(self::glue($case), $name);
    }

    private static function glue(string $case): string
    {
        return Arry::get(['kebab' => '-', 'snake' => '_'], $case) ?? '';
    }
}