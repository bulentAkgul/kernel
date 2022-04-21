<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Tasks\GetClass;

class CallClass
{
    public static function _(array $request, string $method, string $namespace, string $variation = null): bool
    {
        $class = GetClass::{$method}($request['attr'], $namespace, $variation);

        if (!class_exists($class)) return false;

        (new $class)($request);

        return true;
    }
}
