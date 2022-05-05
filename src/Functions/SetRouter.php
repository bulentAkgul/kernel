<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\Kernel\Helpers\Settings;

class SetRouter
{
    public static function _(?string $key): string
    {
        if (!$key && Settings::apps("{$key}.router")) return '';

        $router = Settings::resources(Settings::apps("{$key}.type") . '.options.router') ?? '';

        Settings::set("apps.{$key}.router", $router);

        return $router;
    }
}