<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;

class MakeFileData
{
    public static function _(array $data): array
    {
        return [
            'type' => $data[0],
            'name' => $data[1],
            'variation' => Arry::get($data, 2) && in_array($data[2], Settings::files("{$data[0]}.variations")) ? $data[2] : '',
            'family' => Arry::get($data, 3) ?? Settings::files("{$data[0]}.family"),
            'tasks' => Arry::get($data, 4) ?? [],
            'subs' => Arry::get($data, 5) ?? '',
            'parent' => Arry::get($data, 6) ?? []
        ];
    }
}
