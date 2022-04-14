<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Settings;

class CollectTypes
{
    private static array $types = [];
    private static string $command;

    public static function _(array|string $type, string $command = 'files', ?string $parent = '')
    {
        self::$command = $command;

        self::setTypes($type);

        array_map(fn ($x) => self::addRelatedTypes($x, $parent), self::$types);

        return self::$types;
    }

    private static function setTypes($type): void
    {
        self::$types = self::addTypes($type) ?? self::shapeTypes($type);
    }

    private static function addTypes($type)
    {
        return is_array($type) && Arry::has('type', $type) ? $type : null;
    }

    private static function shapeTypes($type)
    {
        return array_map(fn ($x) => self::makeType($x), self::getTypes($type));
    }

    private static function makeType($type)
    {
        return array_merge(
            Arry::combine(['type', 'variation', 'extra'], $type),
            ['name' => '', 'status' => 'main']
        );
    }

    private static function getTypes($type): array
    {
        return is_string($type) ? Isolation::types($type) : (is_string($type[0]) ? [$type] : $type);
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
        $parents = Settings::main('need_parent');

        return [[
            'type' => Arry::get($parents, $type['type'])
                ?? Arry::get($parents, $type['variation'])
                ?? '',
            'status' => 'parent'
        ]];
    }
}
