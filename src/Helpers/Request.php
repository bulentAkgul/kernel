<?php

namespace Bakgul\Kernel\Helpers;

class Request
{
    public static function variation(array $request, array $specs)
    {
        return $request['variation'] ?: Arry::get($specs['variations'], 0) ?? '';
    }
}
