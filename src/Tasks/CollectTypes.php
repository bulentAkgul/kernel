<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Settings;

class CollectTypes
{
    private static array $types = [];
    private static string $command;

    public static function _(string $type, string $command = 'files', ?string $parent = '')
    {
        self::$command = $command;

        self::set($type);
        
        self::extend($parent);

        return self::$types;
    }

    private static function set($type): void
    {
        self::$types[] = array_merge(
            Arry::combine(['type', 'variation', 'extra'], Isolation::types($type)[0]),
            ['name' => '', 'status' => 'main']
        );
    }

    private static function extend($parent)
    {
        self::addRelatedTypes(self::$types[0], $parent);
    }

    private static function addRelatedTypes($type, $parent)
    {
        foreach (self::getRelatedTypes($type, $parent) as $type) {
            if (self::isNotUnique($type)) continue;

            self::$types[] = $type;

            self::addRelatedTypes($type, $parent);
        }
    }

    private static function isNotUnique($type)
    {
        return !empty(array_filter(
            self::$types,
            fn ($x) => Arry::isEqual(['type', 'status', 'name'], $x, $type, true)
        ));
    }

    private static function getRelatedTypes($type, $parent)
    {
        return array_map(fn ($x) => self::shapeRelatedTypes($x, $type, $parent), self::collectRelatedTypes($type));
    }

    private static function shapeRelatedTypes($type, $mainType, $parent)
    {
        return [
            ...$type,
            'name' => self::name($type, $parent),
            'variation' => self::variation($type, $mainType),
        ];
    }

    private static function name($type, $parent)
    {
        return $type['status'] == 'parent' && $parent
            ? Isolation::name($parent)
            : Arry::get($type, 'name') ?? '';
    }

    private static function variation($type, $mainType)
    {
        return Arry::get($type, 'variation')
            ?? self::setMainVariation($type['type'], $mainType['variation'])
            ?? self::setDefault($type['type']);
    }

    private static function setMainVariation(string $type, string $variation)
    {
        return $variation && in_array($variation, Settings::{self::$command}("{$type}.variations")) ? $variation : null;
    }

    private static function setDefault(string $type): ?string
    {
        return Settings::default(self::$command, "{$type}.variations");
    }

    private static function collectRelatedTypes($type)
    {
        return array_filter(array_merge(
            self::pairs($type),
            self::require($type),
            self::parent($type)
        ), fn ($x) => $x['type']);
    }

    private static function pairs(array $type): array
    {
        return array_map(
            fn ($x) => ['type' => $x, 'status' => 'pair'],
            self::setPairTypes($type)
        );
    }

    private static function setPairTypes(array $type): array
    {
        $types = Settings::files("{$type['type']}.pairs");

        if ($type['type'] == 'controller' && $type['variation'] == 'invokable') {
            $types = array_filter($types, fn ($x) => $x != 'service' && $x != 'request');
        }

        return $types;
    }

    private static function require(array $type): array
    {
        $require = Settings::files("{$type['type']}.require");

        return [array_merge(
            $require ? Arry::combine(['type', 'name', 'variation'], $require, '') : ['type' => ''],
            ['status' => 'require']
        )];
    }

    private static function parent(array $type): array
    {
        $parents = Settings::needs('parent');

        return [[
            'type' => Arry::get($parents, $type['type'])
                ?? Arry::get($parents, $type['variation'])
                ?? '',
            'status' => 'parent'
        ]];
    }
}
