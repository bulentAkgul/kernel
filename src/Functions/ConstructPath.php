<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\PurifySchema;

class ConstructPath
{
    public static function _(array $request, string $glue = DIRECTORY_SEPARATOR): string
    {
        return Text::replaceByMap($request['map'], PurifySchema::_(
            array_keys($request['map']), $request['attr']['path']
        ), true, $glue);
    }
}
