<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Settings;

class SetPipeline
{
    public static function _(array $request)
    {
        $type = Isolation::type($request['type']);

        if ($type == 'view') {
            $type = Isolation::extra($request['type'])
                ?: Settings::apps("{$request['app']}.type")
                ?: $type;
        }

        return ['type' => $type, ...(Settings::resources($type) ?? [])];
    }
}