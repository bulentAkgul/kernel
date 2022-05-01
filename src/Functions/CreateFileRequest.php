<?php

namespace Bakgul\Kernel\Functions;

class CreateFileRequest
{
    public static function _(array $modifications): array
    {
        return [
            'command' => 'create:file',
            'name' => 'dummy-name',
            'type' => 'dummy-type',
            'package' => null,
            'app' => null,
            'parent' => null,
            'taskless' => false,
            'force' => false,
            ...$modifications
        ];
    }
}