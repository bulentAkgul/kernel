<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Path;

class GetClass
{
    public static function resource(array $attr, string $namespace): string
    {
        $category = $attr['category'] == $attr['type'] ? '' : $attr['category'];

        return Path::glue([
            $namespace,
            ucfirst($attr['category']) . 'ResourceServices',
            ucfirst($attr['type']) . ucfirst($category) . 'ResourceService'
        ], '\\');
    }

    public static function css(array $attr, string $namespace): string
    {
        return Path::glue([
            $namespace,
            "CssResourceServices",
            ucfirst($attr['type']) . 'CssResourceService'
        ], '\\');
    }

    public static function view(array $attr, string $namespace, ?string $variation = null): string
    {
        return Path::glue([
            '',
            $namespace,
            'ViewResourceSubServices',
            ucfirst($variation ?: $attr['variation']) . ucfirst($attr['type']) . 'ViewResourceService'
        ], '\\');
    }

    public static function src(array $attr, string $namespace): string
    {
        return Path::glue([
            '',
            $namespace,
            'SrcFilesServices',
            ucfirst($attr['type']) . 'FileService'
        ], '\\');
    }

    public static function database(array $attr, string $namespace): string
    {
        return Path::glue([
            '',
            $namespace,
            'DatabaseFilesServices',
            ucfirst($attr['type']) . 'FileService'
        ], '\\');
    }

    public static function test(array $attr, string $namespace): string
    {
        return Path::glue([
            '',
            $namespace,
            'TestFilesServices',
            ucfirst($attr['type']) . 'FileService'
        ], '\\');
    }
}
