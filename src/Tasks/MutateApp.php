<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Settings;
use Illuminate\Support\Arr;

class MutateApp
{
    public static function get()
    {
        $apps = Settings::apps();

        return [...array_keys($apps), ...self::pluck($apps)];
    }

    public static function set(array $request): array
    {
        return [
            'app_key' => $k = self::getKey($request),
            'app_type' => self::obtainValue($k, 'type'),
            'app_folder' => self::obtainValue($k, 'folder'),
            'router' => self::obtainValue($k, 'router'),
        ];
    }

    private static function getKey($request, bool $default = true): ?string
    {
        $apps = Settings::apps();
        $app = $request['app'] ?? $request['package'];

        if (!$app) return self::getDefaultApp($apps, $default);

        return self::getKeyByName($app, $apps)
            ?? self::getKeyByFolder($app, $apps)
            ?? self::getDefaultApp($apps, $default);
    }

    private static function getKeyByName(string $app, array $apps): ?string
    {
        return self::matchedApp(array_keys($apps), $app);
    }

    private static function getKeyByFolder(string $app, array $apps): ?string
    {
        return self::matchedApp(self::pluck($apps), $app);
    }

    private static function getDefaultApp(array $apps, bool $default): ?string
    {
        return $default ? array_key_first($apps) : null;
    }

    private static function pluck($apps)
    {
        return Arr::pluck(array_values($apps), 'folder');
    }

    private static function matchedApp($apps, $app)
    {
        return $app && in_array($app, $apps) ? $app : null;
    }

    private static function obtainValue(?string $key, string $field): ?string
    {
        return $key ? Settings::apps("{$key}.{$field}") : $key;
    }
}
