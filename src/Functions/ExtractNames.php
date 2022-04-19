<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Isolation;
use Illuminate\Support\Arr;

class ExtractNames
{
    public static function _(string $name): array
    {
        return Arr::flatten(array_map(
            fn ($chunk) => array_map(
                fn ($file) => Isolation::name($file),
                Isolation::file($chunk)
            ),
            Isolation::chunk($name)
        ));
    }
}