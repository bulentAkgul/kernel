<?php

namespace Bakgul\Kernel\Helpers;

class Settings
{
    public static function get(string $keys = '', ?callable $callback = null)
    {
        $data = config('packagify' . Text::append($keys, '.'));

        return $callback && $data ? array_filter($data, $callback) : $data;
    }

    public static function default(string $navigate, string $keys = '')
    {
        $data = self::get($navigate . Text::append($keys, '.')) ?? [''];

        return is_array($data) ? array_values($data)[0] : $data;
    }

    public static function apps(string $keys = '', ?callable $callback = null)
    {
        return self::get('apps' . Text::append($keys, '.'), $callback);
    }

    public static function code(string $keys = '', ?callable $callback = null)
    {
        return self::get('code' . Text::append($keys, '.'), $callback);
    }

    public static function evaluator(string $keys = '', ?callable $callback = null)
    {
        return self::get('evaluator' . Text::append($keys, '.'), $callback);
    }

    public static function files(string $keys = '', ?callable $callback = null)
    {
        return self::get('files' . Text::append($keys, '.'), $callback);
    }

    public static function folders(string $keys = '', ?callable $callback = null, ?bool $nullable = false)
    {
        $folder = self::get('folders' . Text::append($keys, '.'), $callback);

        return $folder ?: ($nullable ? null : $keys);
    }

    public static function identity(string $keys = '', ?callable $callback = null)
    {
        return self::get('identity' . Text::append($keys, '.'), $callback);
    }

    public static function main(string $keys = '', ?callable $callback = null)
    {
        return self::get('main' . Text::append($keys, '.'), $callback);
    }

    public static function messages(string $keys = '', ?callable $callback = null)
    {
        return self::get('messages' . Text::append($keys, '.'), $callback);
    }

    public static function npm(string $keys = '', ?callable $callback = null)
    {
        return self::get('npm' . Text::append($keys, '.'), $callback);
    }

    public static function requires(string $keys = '', ?callable $callback = null)
    {
        return array_values(self::get('requires' . Text::append($keys, '.'), $callback));
    }

    public static function resourceOptions(string $keys = '', ?callable $callback = null)
    {
        return self::get('resource_options' . Text::append($keys, '.'), $callback);
    }

    public static function resources(string $keys = '', ?callable $callback = null)
    {
        return self::get('resources' . Text::append($keys, '.'), $callback);
    }

    public static function roots(string $keys = '', ?callable $callback = null)
    {
        return self::get('roots' . Text::append($keys, '.'), $callback);
    }

    public static function prohibitives(string $keys = '', ?callable $callback = null)
    {
        return self::get('prohibitives' . Text::append($keys, '.'), $callback);
    }

    public static function seeders(string $keys = '', ?callable $callback = null)
    {
        return self::get('seeders' . Text::append($keys, '.'), $callback);
    }

    public static function seperators(string $keys = '', ?callable $callback = null)
    {
        return self::get('seperators' . Text::append($keys, '.'), $callback);
    }

    public static function standalone(string $key = '')
    {
        return $key ? self::get("main.standalone_{$key}") : self::standalone('laravel') || self::standalone('package');
    }

    public static function structures(string $keys = '', ?callable $callback = null)
    {
        return self::get('structures' . Text::append($keys, '.'), $callback);
    }

    public static function symbols(string $keys = '', ?callable $callback = null)
    {
        return self::get('symbols' . Text::append($keys, '.'), $callback);
    }
}
