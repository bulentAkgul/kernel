<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Text;

class MutateStub
{
    public static function set(string $type, ?string $variation)
    {
        return $type . Text::append($variation ?? '', '.') . '.stub';
    }
    
    public static function get($request)
    {
        return str_replace(
            array_map(fn ($search) => "{{ $search }}", array_keys($request['map'])),
            array_values($request['map']),
            self::content($request['attr'])
        );
    }

    public static function content(array $attr): string
    {
        foreach (self::containerOptions($attr) as $container) {
            foreach (self::nameOptions($attr) as $drop) {
                $stub = self::stub($attr['stub'], $container, $drop);

                if (file_exists($stub)) return file_get_contents($stub);
            }
        }

        return '';
    }

    private static function folderOptions($attr)
    {
        return $attr['job'] == 'package' ? ['package', 'file', 'resource'] : [$attr['job']];
    }

    private static function containerOptions($attr)
    {
        $containers = [];

        foreach (self::folderOptions($attr) as $folder) {
            foreach (self::pathOptions($folder) as $path) {
                $containers[] = Path::glue([$path, $folder]);
            }
        }

        return $containers;
    }

    private static function pathOptions($folder)
    {
        return [
            Path::glue(['stubs', 'packagify']),
            Path::glue(['vendor', 'bakgul', self::vendor($folder), 'stubs'])
        ]; 
    }

    private static function vendor($folder)
    {
        return [
            'code' => 'laravel-code-generator',
            'file' => 'laravel-file-creator',
            'package' => 'laravel-package-generator',
            'resource' => 'laravel-resource-creator',
        ][$folder];
    }

    private static function nameOptions($attr)
    {
        return ['', $attr['variation']];
    }

    private static function stub($stub, $container, $drop)
    {
        return Path::realBase(Path::glue([$container, str_replace(Text::append($drop, '.'), '', $stub)]));
    }

    public static function line(array $attr, string $search)
    {
        return Arry::containsOn($search, Text::split(self::content($attr)));
    }
}
