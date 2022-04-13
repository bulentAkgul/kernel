<?php

namespace Bakgul\Kernel\Helpers;

use Bakgul\Kernel\Helpers\Convention;
use Illuminate\Filesystem\Filesystem;

class Folder
{
    public static function get(string $key, bool $isSingular = false)
    {
        $folder = Settings::folders($key);

        return Pluralizer::run($folder, $folder == $key ? $isSingular : null);
    }

    public static function name(string $value, string $suffix = '', string $prefix = '', bool $isSingular = false)
    {
        return Convention::affix($prefix)
             . Convention::class($value)
             . Convention::affix($suffix, $isSingular);
    }
    
    public static function content(string $path, array $exclude = []): array
    {
        return file_exists($path) ? array_diff(scandir($path), array_merge(['.', '..'], $exclude)) : [];
    }

    public static function contains(string $path, string $name): bool
    {
        return in_array($name, self::content($path));
    }

    public static function files(string $path): array
    {
        $paths = [];

        foreach (self::content($path) as $item) {
            $itemPath = self::removeExtraSeperator(Path::glue([$path, $item]));
            
            if (is_dir($itemPath)) {
                $paths = array_merge($paths, self::files($itemPath));
            } else {
                $paths[] = $itemPath;
            }
        }

        return $paths;
    }

    public static function tree(string $path): array
    {
        $tree = [];

        foreach (self::content($path) as $item) {
            $itemPath = self::removeExtraSeperator(Path::glue([$path, $item]));
            $tree[$item] = is_dir($itemPath) ? self::tree($itemPath) : $itemPath;
        }

        return $tree;
    }

    public static function refresh($path)
    {
        return file_exists($path) ? (new Filesystem)->deleteDirectories($path) : mkdir($path);
    }

    private static function removeExtraSeperator(string $path): string
    {
        return str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
    }
}
