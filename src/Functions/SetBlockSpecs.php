<?php

namespace Bakgul\Kernel\Functions;

class SetBlockSpecs
{
    public static function _(array $specs)
    {
        return array_merge([
            'end' => ['}', 0],
            'isStrict' => true,
            'repeat' => 1,
            'part' => '',
            'isSortable' => false
        ], $specs);
    }
}
