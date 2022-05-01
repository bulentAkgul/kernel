<?php

namespace Bakgul\Kernel\Tests\Services;

use Bakgul\Kernel\Helpers\Arry;
use Illuminate\Support\Str;

class TestDataService
{
    public static function scenarios()
    {
        return array_keys(self::standalone());
    }
    
    public static function standalone(string $key = '', ?bool $hasRoot = null)
    {
        $options = [
            'pl' => [
                'sl' => false,
                'sp' => false,
                'root' => $hasRoot !== null ? [$hasRoot] : [true, false]
            ],
            'sl' => [
                'sl' => true,
                'sp' => false,
                'root' => $hasRoot !== null ? [$hasRoot] : [true, false]
            ],
            'sp' => [
                'sl' => false,
                'sp' => true,
                'root' => $hasRoot !== null ? [$hasRoot] : [true, false]
            ],
            'conflict' => [
                'sl' => true,
                'sp' => true,
                'root' => $hasRoot !== null ? [$hasRoot] : [true, false]
            ]
        ];

        return $key ? $options[$key] : $options;
    }

    public static function package(string $key = '', bool $standalone = false): array|string
    {
        if ($key == 'path') return '';

        $package = [
            'namespace' => 'CurrentTest',
            'folder' => $f = $standalone ? '' : 'testings',
            'name' => $n = $standalone ? '' : 'testing',
            'path' => implode(DIRECTORY_SEPARATOR, array_filter([$f, $n]))
        ];

        return $key ? $package[$key] : $package;
    }

    public static function defaults()
    {
        return [
            'force' => true,
            'package' => self::package('name'),
        ];
    }

    public static function words()
    {
        return self::WORDS;
    }

    public static function word()
    {
        return Arry::random(self::WORDS);
    }

    public static function files()
    {
        return ['composer.json'];
    }

    public static function getTestSpecs(array $specs)
    {
        return array_merge($specs, [
            'family' => self::setFamily($specs['family']),
        ], self::setTestClasses($specs));
    }

    private static function setFamily(string $type): string
    {
        return in_array($type, ['resource', 'test']) ? Str::plural($type) : $type;
    }

    private static function setTestClasses(array $specs): array
    {
        $classes = [];

        foreach (['Command', 'Expectation', 'Assertion'] as $key) {
            $classes[lcfirst($key)] = "\Packagify\Tests\TestServices\\{$key}Services\\{$specs['create']}{$key}Service";
        }

        return $classes;
    }

    public static function createFileCommandBase()
    {
        return [
            'command' => 'create:file',
            'name' => 'users',
            'type' => 'controller',
            'package' => self::package('name'),
            'app' => 'web',
            'parent' => null,
            'pairs' => null,
            'listener' => null,
            'force' => false,
        ];
    }

    public static function createResourceCommandBase()
    {
        return [
            'command' => 'create:resource',
            'name' => 'posts',
            'type' => 'view:page',
            'package' => self::package('name'),
            'app' => 'admin',
            'parent' => null,
            'class' => false,
            'taskless' => false,
            'force' => false,
        ];
    }

    public static function createPackageCommandBase()
    {
        return [
            'command' => 'create:package',
            'package' => self::package('name'),
            'root' => self::package('folder'),
            'dev' => false,
        ];
    }

    public static function createRelationCommandBase()
    {
        return [
            'command' => 'create:relation',
            'relation' => 'oto',
            'from' => 'user',
            'to' => 'post',
            'mediator' => null,
            'polymorphic' => false,
        ];
    }

    const WORDS = ['user', 'post', 'comment', 'color', 'car', 'manufacturer', 'client', 'product', 'recipe', 'item', 'adress', 'category'];
}
