<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tasks\CollectTypes as Collector;

class CollectTypes
{
    public static function _($type, $command = 'files', $parent = '')
    {
        $types = Arry::combine(Settings::get('status'), default: []);

        foreach (Collector::_($type, $command, $parent) as $type) {
            if (Arry::hasNot($type['status'], $types))
                $types[$type['status']] = [];

            $types[$type['status']][] = $type;

            if ($type['variation'] == 'page')
                $types[$type['status']][] = [...$type, 'variation' => 'section'];
        }

        return $types;
    }
}
