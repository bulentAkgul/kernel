<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Helpers\Settings;

class ResolveTasks
{
    public static function _(array $tasks, string $type, string $taskKey, bool $fillable = false): array
    {
        $tasks = array_intersect(
            self::getTasks($tasks, $taskKey, $fillable),
            Settings::files("{$type}.tasks")
        );
        
        return empty($tasks) ? [''] : $tasks;
    }

    private static function getTasks(array $tasks, string $taskKey, bool $fillable): array
    {
        return self::mustBeAll($tasks, $fillable) ? Settings::main('tasks.' . $taskKey) : $tasks;
    }

    private static function mustBeAll(array $tasks, bool $fillable): bool
    {
        return !array_filter($tasks) && $fillable;
    }
}
