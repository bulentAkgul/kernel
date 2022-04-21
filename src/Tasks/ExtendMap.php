<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Tasks\ConvertCase;

class ExtendMap
{
    public static function _(array $request): array
    {
        return array_merge(Arry::get($request, 'map') ?? [], [
            'name' => self::setName($request['attr']),
            'subs' => self::setSubs($request['attr']),
            'suffix' => self::setSuffix($request['attr']),
            'task' => self::setTask($request['attr']),
            'prefix' => self::setPrefix($request['attr']),
            'wrapper' => self::setWrapper($request['attr'])
        ]);
    }

    private static function setName(array $attr)
    {
        return ConvertCase::{$attr['convention']}($attr['name']);
    }

    private static function setSuffix(array $attr)
    {
        return ConvertCase::{$attr['convention']}($attr['type']);
    }

    private static function setTask(array $attr)
    {
        return ConvertCase::{$attr['convention']}(Arry::get($attr, 'task') ?: '');
    }

    private static function setSubs(array $attr): string
    {
        return Path::glue(Path::make($attr['subs'], $attr['convention']));
    }

    private static function setPrefix(array $attr): string
    {
        return ConvertCase::{$attr['convention']}(Arry::get($attr, 'prefix') ?? '');
    }

    private static function setWrapper(array $attr): string
    {
        return $attr['job'] == 'file'
            ? 'Http'
            : ConvertCase::_(
                Arry::get($attr, 'wrapper') ?? '',
                Arry::get($attr, 'convention')
            );
    }
}