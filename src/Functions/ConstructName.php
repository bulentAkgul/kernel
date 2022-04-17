<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;

class ConstructName
{
    public static function _(array $attr, array $map): string
    {
        return ConvertCase::{$attr['convention']}(
            Text::replaceByMap($map, $attr['name_schema'], true, '-')
        );
    }
}