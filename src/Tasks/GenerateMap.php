<?php

namespace Bakgul\Kernel\Tasks;

class GenerateMap
{
    public static function _(array $attr): array
    {
        return [
            'package' => $attr['package'],
            'family' => $attr['family'],
        ];
    }
}
