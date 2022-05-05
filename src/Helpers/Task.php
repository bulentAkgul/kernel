<?php

namespace Bakgul\Kernel\Helpers;

class Task
{
    public static function get(string $fileType, string $key)
    {
        return array_values(array_intersect(
            Settings::files("{$fileType}.tasks") ?? [],
            Settings::tasks(str_contains($key, 'api') ? 'api' : 'all')
        ));
    }

    public static function drop(string $task, string $variation = ''): string
    {
        return  self::droppable($task, $variation) ? '' : $task;
    }

    public static function droppable(string $task, string $variation = ''): bool
    {
        return str_contains($variation, 'api') && !in_array($task, Settings::tasks('api'));
    }
}
