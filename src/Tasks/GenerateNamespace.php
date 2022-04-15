<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Path;

class GenerateNamespace
{
    public static function _(array $attr, string $path = ''): string
    {
        return implode('\\', array_filter([
            self::root(Arry::get($attr, 'root')),
            self::package(Arry::get($attr, 'package')),
            self::family(Arry::get($attr, 'family'), Arry::get($attr, 'package')),
            self::tail($path)
        ]));
    }

    public static function root(?string $root): string
    {
        if (Settings::standalone('laravel')) return '';

        if (Settings::standalone('package')) return Settings::identity('namespace');

        return $root ? Arry::value(Settings::roots(), $root, 'folder', '=', 'namespace') : '';
    }

    public static function package(?string $package): string
    {
        return !Settings::standalone() && $package ? ucfirst($package) : '';
    }

    public static function family(?string $family, ?string $package): string
    {
        return match (true) {
            !$family => '',
            $family != 'src' => ucfirst($family),
            Settings::standalone('laravel') => 'App',
            !Settings::standalone() && !$package => 'App',
            default => ''
        };
    }

    public static function tail(string $path)
    {
        return Path::toNamespace($path);
    }

    /**
     * standalone laravel => [
     *      src => App,
     *      tests => Tests,
     *      database => Database 
     * standalone package => [
     *      src => IdentitiyNamespace,
     *      tests => IdentitiyNamespace\Tests,
     *      database => IdentitiyNamespace\Database 
     * packagified laravel to package => [
     *      src => PackageRootsNamespace\Package
     *      tests => PackageRootsNamespace\Package\Tests,
     *      database => PackageRootsNamespace\Package\Database 
     * packagified laravel to app => [
     *      src => App,
     *      tests => Tests,
     *      database => Database 
     */
}
