<?php

namespace Bakgul\Kernel\Helpers;

class Settings
{
    public static function get(string $key = '', string $keys = '', ?callable $callback = null)
    {
        $data = self::raw($key, $keys);

        return $callback && $data ? array_filter($data, $callback) : $data;
    }

    public static function raw(string $key, string $keys = '')
    {
        return $keys
            ? config("packagify.essentials.{$keys}") ?? config("packagify.{$key}.{$keys}")
            : config('packagify' . Text::append($key, '.'));
    }

    public static function default(string $navigate, string $keys = '')
    {
        $data = self::get($navigate, $keys) ?? [''];

        return is_array($data) ? array_values($data)[0] : $data;
    }

    public static function essentials(string $keys = '')
    {
        return config('packagify.essentials' . Text::append($keys, '.'));
    }

    public static function apps(string $keys = '', ?callable $callback = null)
    {
        return self::get('apps', $keys, $callback);
    }

    public static function code(string $keys = '', ?callable $callback = null)
    {
        return self::get('code', $keys, $callback);
    }

    public static function evaluator(string $keys = '', ?callable $callback = null)
    {
        return self::get('evaluator', $keys, $callback);
    }

    public static function files(string $keys = '', ?callable $callback = null)
    {
        return self::get('files', $keys, $callback);
    }

    public static function folders(string $keys = '', ?callable $callback = null, ?bool $nullable = false)
    {
        $folder = self::get('folders', $keys, $callback);

        return $folder ?: ($nullable ? null : $keys);
    }

    public static function identity(string $keys = '', ?callable $callback = null)
    {
        return self::get('identity', $keys, $callback);
    }

    public static function main(string $keys = '', ?callable $callback = null)
    {
        return self::get('main', $keys, $callback);
    }

    public static function messages(string $keys = '', ?callable $callback = null)
    {
        return self::get('messages', $keys, $callback);
    }

    public static function prefixes(string $keys = '', ?callable $callback = null)
    {
        return self::get('prefixes', $keys, $callback);
    }

    public static function npm(string $keys = '', ?callable $callback = null)
    {
        return self::get('npm', $keys, $callback);
    }

    public static function requires(string $keys = '', ?callable $callback = null)
    {
        return array_values(self::get('requires', $keys, $callback));
    }

    public static function resourceOptions(string $keys = '', ?callable $callback = null)
    {
        return self::get('resource_options', $keys, $callback);
    }

    public static function resources(string $keys = '', ?callable $callback = null)
    {
        return self::get('resources', $keys, $callback);
    }

    public static function roots(string $keys = '', ?callable $callback = null)
    {
        return self::get('roots', $keys, $callback);
    }

    public static function prohibitives(string $keys = '', ?callable $callback = null)
    {
        return self::get('prohibitives', $keys, $callback);
    }

    public static function seeders(string $keys = '', ?callable $callback = null)
    {
        return self::get('seeders', $keys, $callback);
    }

    public static function seperators(string $keys = '', ?callable $callback = null)
    {
        return self::get('seperators', $keys, $callback);
    }

    public static function standalone(string $key = '')
    {
        return $key ? self::get('main', "standalone_{$key}") : self::standalone('laravel') || self::standalone('package');
    }

    public static function structures(string $keys = '', ?callable $callback = null)
    {
        return self::get('structures', $keys, $callback);
    }

    public static function symbols(string $keys = '', ?callable $callback = null)
    {
        return self::get('symbols', $keys, $callback);
    }
}
