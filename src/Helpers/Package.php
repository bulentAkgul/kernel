<?php

namespace Bakgul\Kernel\Helpers;

use Bakgul\Kernel\Tasks\MutateApp;
use Illuminate\Support\Arr;

class Package
{
    public static function path(string $name, string $path = ''): string
    {
        return Path::package($name, $path);
    }

    public static function container(bool $prepend = true)
    {
        return Text::prepend(
            Settings::standalone() ? '' : Settings::folders('packages'),
            $prepend ? DIRECTORY_SEPARATOR : ''
        );
    }

    public static function root(?string $name): string
    {
        foreach (Arr::pluck(Settings::roots(), 'folder') as $folder) {
            if (in_array($name, Folder::content(
                base_path(Text::prepend(self::container()) . $folder)
            ))) return $folder;
        }

        return '';
    }

    public static function vendor($package)
    {
        return !$package || strtolower($package) == 'all'
            ?  Folder::content(Path::base(['vendor', 'bakgul']))
            : [$package];
    }

    public static function isApp(?string $package): bool
    {
        return !$package ||
            Settings::standalone() ||
            MutateApp::set(['package' => $package, 'app' => null])['app_key'];
    }

    public static function list(string $root = ''): array
    {
        if (Settings::standalone('laravel')) return [];
        
        $packages = [];

        foreach (self::roots($root) as $root) {
            $packages = array_merge($packages, Folder::content(
                Path::base([Settings::folders('packages'), $root])
            ));
        }

        return $packages;
    }

    private static function roots($root)
    {
        return $root ? [$root] : Arr::pluck(Settings::roots(), 'folder');
    }
}
