<?php

namespace Bakgul\Kernel\Helpers;

class Settings
{
    public static function set($key, $value, $isAppend = false)
    {
        $keys = implode('.', array_filter(["packagify", $key]));
        $config = $isAppend ? config($keys) : null;
        
        config()->set($keys, is_array($config) && $isAppend
            ? array_merge($config, (array) $value)
            : $value
        );
    }

    public static function get(string $key = '', string $keys = '', ?callable $callback = null)
    {
        $data = self::raw($key, $keys);

        return $callback && $data ? array_filter($data, $callback) : $data;
    }

    public static function raw(string $key = '', string $keys = '')
    {
        if ($keys) {
            return config("packagify.essentials.{$key}.{$keys}")
                ?? config("packagify.{$key}.{$keys}");
        }

        if ($key) {
            return config("packagify.essentils.{$key}")
                ?? config("packagify.{$key}");
        }

        return config('packagify');
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

    public static function dependencies(string $keys = '', ?string $type = '')
    {
        $d = self::get('dependencies', $keys);
        
        return $type
            ? Arry::get($d, $type)
            : array_reduce(array_values($d), fn ($p, $c) => array_merge($p, $c), []);
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

    public static function logs(string $keys = '', ?callable $callback = null)
    {
        return self::get('logs', $keys, $callback);
    }

    public static function main(string $keys = '', ?callable $callback = null)
    {
        return self::get('main', $keys, $callback);
    }

    public static function messages(string $keys = '', ?callable $callback = null)
    {
        return self::get('messages', $keys, $callback);
    }

    public static function needs(string $keys = '', ?callable $callback = null)
    {
        return self::get('needs', $keys, $callback);
    }

    public static function prefixes(string $keys = '', ?callable $callback = null)
    {
        return self::get('prefixes', $keys, $callback);
    }

    public static function npm(string $keys = '', ?callable $callback = null)
    {
        return self::get('npm', $keys, $callback);
    }

    public static function prohibitives(string $keys = '', ?callable $callback = null)
    {
        return self::get('prohibitives', $keys, $callback);
    }

    public static function repo(): string
    {
        return match (true) {
            self::standalone('laravel') => 'sl',
            self::standalone('package') => 'sp',
            default => 'pl'
        };
    }

    public static function requires(string $keys = '', ?callable $callback = null)
    {
        return array_values(self::get('requires', $keys, $callback));
    }

    public static function resources(string $keys = '', ?callable $callback = null)
    {
        return self::get('resources', $keys, $callback);
    }

    public static function roots(string $keys = '', ?callable $callback = null)
    {
        return self::get('roots', $keys, $callback);
    }

    public static function routes(string $keys = '', ?callable $callback = null)
    {
        return self::get('routes', $keys, $callback);
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
        return $key ? self::get('repository', "standalone_{$key}") : self::standalone('laravel') || self::standalone('package');
    }

    public static function structures(string $keys = '', ?callable $callback = null)
    {
        return self::get('structures', $keys, $callback);
    }

    public static function tasks(string $keys = '', ?callable $callback = null)
    {
        return self::get('tasks', $keys, $callback);
    }
}
