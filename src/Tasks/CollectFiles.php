<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Package;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Path;

class CollectFiles
{
    public static function _(string $type, string $package = '')
    {
        return array_reduce(
            self::makePathList(Convention::namespace($type), $package),
            fn ($p, $c) => array_merge($p, Folder::files($c)),
            []
        );
    }

    private static function makePathList(string $folder, string $package = ''): array
    {
        return array_map(
            fn ($x) => Path::base([$x, $folder]),
            self::setPackages(Settings::standalone() ? '' : $package)
        );
    }

    private static function setPackages($package)
    {
        return $package ? [self::setPackageBase($package)] : ['app', 'src', ...self::collectPackages()];
    }

    private static function collectPackages(): array
    {
        return Settings::standalone() ? [] : array_map(fn ($x) => self::setPackageBase($x), Package::list());
    }

    private static function setPackageBase($package): string
    {
        return Path::glue([Settings::main('packages_root'), Package::root($package), $package, "src"]);
    }
}