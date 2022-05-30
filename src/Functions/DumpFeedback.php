<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Text;
use Illuminate\Support\Facades\App;

class DumpFeedback
{
    public static function _(string $path, string $type, string $glue = DIRECTORY_SEPARATOR): void
    {
        if (App::runningUnitTests()) return;
        
        $name = Text::getTail($path, $glue);
        $path = Text::dropTail(trim(str_replace(base_path(), '', $path), $glue), $glue);

        dump("A {$type} named {$name} created on ...{$path}");
    }
}
