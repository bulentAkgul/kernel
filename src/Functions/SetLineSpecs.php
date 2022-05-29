<?php

namespace Bakgul\Kernel\Functions;

class SetLineSpecs
{
    public static function _(array $specs): array
    {
        return array_merge([
            'start' => ['use', 0],
            'isStrict' => false,
            'part' => '',
            'repeat' => 0,
            'isSortable' => true,
            'isEmpty' => false,
            'jump' => ''
        ], $specs);
    }
}
