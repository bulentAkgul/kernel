<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;

class DropTask
{
    public static function _(string $value): string
    {
        return implode('-', array_diff(
            Text::seperate(ConvertCase::kebab($value)),
            Settings::tasks('all')
        ));
    }
}
