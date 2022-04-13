<?php

namespace Bakgul\Kernel\Tests\Tasks;

use Bakgul\Kernel\Helpers\Settings;
use Illuminate\Support\Arr;

class CreateFileList
{
    public function __invoke(array $command): array
    {
        $files = [];

        foreach ($this->setChunks($command) as $chunk) {
            foreach ($chunk['names'] as $name) {
                foreach ($command['type']['value'] as $type) {
                    foreach ($this->setTasks($chunk['tasks'], $type['type']) as $task) {
                        $files[] = $this->setFiles($name, $task, $chunk['subs'], $type);
                    }
                }
            }
        }

        return Arr::flatten($files, 1);
    }

    private function setChunks(array $command): array
    {
        return array_filter($command['name']['value'], fn ($k) => $k != 'all', ARRAY_FILTER_USE_KEY);
    }

    private function setTasks(array $tasks, string $type): array
    {
        $defaultTasks = Settings::files("{$type}.tasks");

        if (in_array('all', $tasks)) return $defaultTasks;

        $tasks = array_intersect($tasks, $defaultTasks);

        return empty($tasks) ? [''] : $tasks;
    }

    private function setFiles(string $name, string $task, array $subs, array $type): array
    {
        return array_map(
            fn ($x) => $this->setFile($name, $task, $subs, $x),
            $this->extendTypes($type)
        );
    }

    private function setFile(string $name, string $task, array $subs, array $type): array
    {
        return ['name' => $name, 'task' => $task, 'subs' => $subs, ...$type];
    }

    private function extendTypes($type): array
    {
        $specs = Settings::files($type['type']);

        $types = $this->extendByPairs($specs, []);
        $types = $this->extendByParents($specs, $types);
        $types = $this->extendByRequireds($specs, $types);

        return [$type];
    }
    
    private function extendByPairs(array $specs, $types): array
    {
        return $types;
    }

    private function extendByParents(array $specs, $types): array
    {
        return $types;
    }

    private function extendByRequireds(array $specs, $types): array
    {
        return $types;
    }
}
