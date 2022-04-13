<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Settings;

class SortList
{
    public static function _(array $files)
    {
        $ref = array_reverse(Settings::main('status'));

        usort($files, function ($a, $b) use ($ref) {
            return array_search($a['order'], $ref) <=> array_search($b['order'], $ref);
        });

        return $files;
    }
}