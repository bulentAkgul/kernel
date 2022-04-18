<?php

namespace Bakgul\Kernel\Tasks\RequestTasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Package;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Request;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Tasks\MutateApp;

class GenerateAttr
{
    public static function _(array $request): array
    {
        return array_merge(
            $request,
            $a = MutateApp::set($request),
            $s = self::setSpecs($request, $a),
            self::setFields($request, $s)
        );
    }

    private static function setFields($request, $specs)
    {
        return [
            'package' => $p = self::setPackage($request['package']),
            'root' => self::setRoot($p),
            'name' => self::setName($request['name']),
            'variation' => $v = Request::variation($request, $specs),
            'stub' => self::setStub($request['type'], $v),
            'path' => self::setPath($p, $specs['family'], $specs['path_schema']),
            'parent' => self::setParent($request),
            ...self::setFolders($request['subs']),
        ];
    }

    private static function setSpecs(array $request): array
    {
        $specs = Settings::files("{$request['type']}") ?? [];

        return array_merge($specs, Arry::get($specs, 'family') != 'resources'
            ? self::addConvention($specs)
            : self::extendSpecs($request['type'])
        );
    }

    private static function addConvention(array $specs): array
    {
        return ['convention' => Arry::get($specs, 'convention') ?? 'pascal'];
    }

    private static function extendSpecs(string $type)
    {
        return Settings::resources(
            $type == 'css' ? Settings::resourceOptions('css') : $type
        ) ?? [];
    }

    private static function setPackage(?string $package): string
    {
        return Settings::standalone() || !$package ? '' : $package;
    }

    private static function setRoot(?string $package): string
    {
        return Settings::standalone() || !$package ? '' : ConvertCase::kebab(Package::root($package));
    }

    private static function setName(string $name): string
    {
        return ConvertCase::kebab(Isolation::name($name));
    }

    private static function setStub(string $type, string $variation = '')
    {
        return $type . Text::append($variation ?? '', '.') . '.stub';
    }

    private static function setFolders(string $subs)
    {
        if (!$subs) return ['subs' => [], 'page_hierarchy' => []];

        $parts = explode(Settings::seperators('addition'), $subs);

        return [
            'subs' => explode(Settings::seperators('folder'), Arry::get($parts, 1) ?? $parts[0]),
            'page_hierarchy' => Arry::has(1, $parts) ? explode(Settings::seperators('folder'), $parts[0]) : []
        ];
    }

    private static function setPath(string $package, string $family, string $tail = ''): string
    {
        return Path::head($package, $family) . $tail;
    }

    private static function setParent(array $request): array
    {
        $parts = $request['parent'] ? explode(Settings::seperators('modifier'), $request['parent']) : [];

        return [
            'name' => Arry::get($parts, 0) ?? $request['name'],
            'type' => Arry::get($parts, 1) ?? $request['type'],
            'variation' => Arry::get($parts, 2) ?? $request['variation'] == 'section' ? 'page' : '',
            'grandparent' => Arry::get($parts, 3) ?? '',
        ];
    }
}
