<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

class SerializeSignature
{
    public static function _(string $signature): array
    {
        $parts = [];

        foreach (self::setParts($signature) as $part) {
            $parts[self::purify($part[0])] = self::setDetails($part);
        }

        return $parts;
    }

    private static function setParts(string $signature): array
    {
        return array_filter(array_map(
            fn ($x) => array_map('trim', explode(' : ', $x)),
            array_slice(Text::split($signature), 1)
        ), fn ($x) => $x[0] != '');
    }

    public static function setDetails(array $part): array
    {
        return [
            'role' => $r = self::setRole($part),
            'type' => self::setType($r, $part),
            'schema' => self::setSchema($part)
        ];
    }

    private static function setRole($part): string
    {
        return str_contains($part[0], '--') ? 'options' : 'arguments';
    }

    private static function setType($role, $part): string
    {
        return $role == 'options'
            ? (str_contains($part[0], '=') ? 'value' : 'bool')
            : (str_contains($part[0], '?') ? 'optional' : 'required');
    }

    private static function setSchema($part): array
    {
        return array_reduce(
            explode(':', str_replace('}', '', Arry::get($part, 1) ?? self::purify($part[0]))),
            fn ($p, $c) => array_merge($p, explode(Settings::seperators('folder'), $c)),
            []
        );
    }

    public static function purify($part)
    {
        return str_replace(['{', '}', '=', '?'], '', array_reverse(explode('|', $part))[0]);
    }
}