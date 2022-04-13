<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

class ResolveNames
{
    public static function _(string $name)
    {
        $names = [];

        foreach (self::explode($name, 'chunk') as $chunk) {
            $names = array_merge($names, self::setNames($chunk));
        }

        return $names;
    }

    private static function setNames($chunk)
    {
        $subs = Isolation::subs($chunk);
        $name = str_replace(Text::prepend($subs), '', $chunk);

        return array_map(
            fn ($x) => self::setName($x, $subs),
            self::explode($name, 'part')
        );
    }

    private static function setName($name, $subs = '')
    {
        return [
            'subs' => $subs,
            'name' => Isolation::name($name),
            'tasks' => Isolation::tasks($name)
        ];
    }

    private static function explode(string $value, string $glue)
    {
        return explode(Settings::seperators($glue), $value);
    }
}