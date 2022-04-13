<?php

namespace Bakgul\Kernel\Tests\Services;

use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;

class FileSecurityService
{
    const SUFFIX = '_backup';

    public static function backup($files)
    {
        foreach ($files as $file) {
            $file = Path::realBase($file);

            array_map(
                fn ($f) => self::copy($f),
                is_file($file) ? [$file] : Folder::files($file)
            );
        }
    }

    public static function copy(string $file): void
    {
        copy($file, $file . self::SUFFIX);
    }

    public static function restore($files)
    {
        $base = Path::realBase();

        foreach ($files as $file) {
            $file = Path::glue([$base, $file]);

            array_map(
                fn ($f) => self::revert($f),
                is_file($file) ? [$file] : Folder::files($file)
            );
        }
    }

    public static function revert(string $file)
    {
        unlink($file);
        rename($file . self::SUFFIX, $file);
    }
}
