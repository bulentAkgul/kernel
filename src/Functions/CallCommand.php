<?php

namespace Bakgul\Kernel\Functions;

use Illuminate\Support\Facades\Artisan;

class CallCommand
{
    public static function _(array|string $commands): void
    {
        array_map(
            fn ($x) => Artisan::call($x),
            is_array($commands) ? $commands : [$commands]
        );
    }
}
