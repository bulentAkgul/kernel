<?php

namespace Bakgul\Kernel\Tasks\RequestTasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tasks\ConvertCase;

class ExtendRequestMap
{
    public static function _(array $request): array
    {
        return array_merge($request['map'], [
            'container' => self::setContainer($request['attr']),
            'name' => self::setName($request['attr']),
            'suffix' => self::setSuffix($request['attr']),
            'task' => self::setTask($request['attr']),
            'variation' => self::setVariation($request['attr']),
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

    private static function setContainer(array $attr)
    {
        $folder = Folder::get($attr['category']);

        return strtoupper($folder) == $folder ? $folder : ConvertCase::{$attr['convention']}($folder);
    }

    private static function setVariation(array $attr): string
    {
        return ConvertCase::{$attr['convention']}(Settings::folders($attr['variation']), false);
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