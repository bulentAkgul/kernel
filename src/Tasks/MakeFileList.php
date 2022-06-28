<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\Kernel\Functions\CollectTypes;
use Bakgul\Kernel\Functions\SortList;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Pluralizer;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Illuminate\Support\Arr;

class MakeFileList
{
    public static array $request;
    public static array $files;
    public static array $types;
    public static string $taskKey;
    public static string $subject;
    public static string $package = '';

    public static function _(array $request): array
    {
        self::reset($request);

        self::collectFiles($request, 'main');

        return SortList::_(self::$files);
    }

    private static function reset($request)
    {
        self::$package = '';
        self::$files = [];
        self::$request = $request;
        self::$subject = Isolation::part($request['command'], 1);
        self::$taskKey = str_contains($request['type'], 'api') ? 'api' : 'all';
    }

    public static function collectFiles(array $request, string $called)
    {
        self::$types = CollectTypes::_($request['type'], parent: $request['parent']);

        foreach (ResolveNames::_($request['name']) as $name) {
            foreach (['main', 'pair'] as $status) {
                if (Arry::hasNot($status, self::$types)) continue;

                self::appendFiles(
                    self::addFiles($name, $status, $request['taskless']),
                    $called
                );
            }
        }

        self::addParentFiles();

        if (self::$subject == 'file') self::addRequireFiles();
    }

    private static function appendFiles(array $files, string $called)
    {
        array_map(fn ($x) => self::append($x, $called), Arr::flatten($files, 1));
    }

    private static function append($file, $called): void
    {
        if (self::isNotUnique($file)) return;

        self::$files[] = [...$file, 'order' => $called == 'main' ? $file['status'] : $called];
    }

    private static function addFiles($name, $status, $taskless)
    {
        return array_map(fn ($x) => self::setFiles($name, $x, $taskless), self::$types[$status]);
    }

    private static function setFiles(array $name, array $type, bool $taskless): array
    {
        return array_filter(
            array_map(
                fn ($x) => self::setFile($name, $type, $x),
                self::explodeTasks($name, $type, $taskless)
            ),
            fn ($x) => in_array($x['task'], ['', ...self::setTasks($type)])
        );
    }

    private static function setFile(array $name, array $type, string $task): array
    {
        return array_merge(
            [...self::addBases($name, $type), 'task' => $task],
            self::updateName($name['name'], $type['type'])
        );
    }

    private static function updateName(string $name, string $type)
    {
        return self::$subject == 'file'
            ? ['name' => Pluralizer::make($name, Settings::files("{$type}.name_count"))]
            : [];
    }

    private static function addBases(array $name, array $type = []): array
    {
        return [...$type, ...Arry::drop($name, 'tasks'), ...self::package()];
    }

    private static function package()
    {
        return ['package' => self::$package ?: self::$request['package']];
    }

    private static function explodeTasks($name, $type, $taskless)
    {
        if (self::isNotExplodable($type)) return [''];

        $tasks = self::setTasks($type);

        if (empty(array_filter($tasks))) return $tasks;

        if ($taskless) return $type['status'] == 'main' ? [''] : [];

        return array_filter(
            self::canBeAll($name, $type) ? array_filter($tasks) : $name['tasks'],
            fn ($x) => in_array($x, Settings::tasks(self::$taskKey))
        );
    }

    private static function isNotExplodable($type)
    {
        return $type['variation'] != 'section' && self::$subject == 'resource'
            || $type['type'] == 'test' && self::$types['main'][0]['type'] != 'controller';
    }

    private static function setTasks($type)
    {
        return Settings::files("{$type['type']}.tasks")
            ?: (self::$subject == 'resource' ? Settings::main('tasks_have_views') : ['']);
    }

    private static function canBeAll($name, $type)
    {
        return in_array($type['status'], ['pair', 'main']) && !array_filter($name['tasks']);
    }

    private static function addParentFiles()
    {
        $parent = explode(Settings::seperators('modifier'), self::$request['parent']);

        foreach (self::$types['parent'] as $type) {
            $type = array_merge(self::addBases($type), [
                'name' => $parent[0],
                'variation' => Arry::get($parent, 2) ?? '',
                'task' => ''
            ]);

            if (self::isNotUnique($type)) continue;

            self::collectFiles(self::makeRequest($type), 'parent');
        }
    }

    private static function makeRequest($specs): array
    {
        return [
            ...self::$request,
            'name' => $specs['name'],
            'type' => self::setType($specs),
            'package' => self::extractPackage($specs),
        ];
    }

    private static function setType(array $specs)
    {
        return $specs['type'] . Text::append($specs['variation'], Settings::seperators('modifier'));
    }

    private static function extractPackage(array $specs)
    {
        if (Settings::standalone()) return '';

        if ($specs['status'] != 'require') return self::$request['package'];

        self::$package = Text::getTail(
            explode(DIRECTORY_SEPARATOR . Settings::files("{$specs['type']}.family"), $specs['path'])[0]
        );

        return self::$package;
    }

    private static function addRequireFiles()
    {
        foreach (self::$types['require'] as $type) {
            $specs = self::setRequireFileSpecs($type);

            if (self::isNotAddable($specs)) continue;

            self::collectFiles(self::makeRequest($specs), 'require');
        }
    }

    private static function setRequireFileSpecs($type)
    {
        return array_merge(
            $s = self::getRequireFileSpecs($type),
            ['path' => Path::adapt($s['path']) ?: self::makeRequirePath($s)]
        );
    }

    private static function getRequireFileSpecs($type)
    {
        return [...$type, ...Settings::requires(
            callback: fn ($x) => $x['name'] == $type['name'] && $x['type'] == $type['type']
        )[0], 'task' => ''];
    }

    private static function makeRequirePath($specs): string
    {
        $file = Settings::files($specs['type']);

        return Text::replaceByMap([
            'container' => self::setContainer($specs),
            'name' => Convention::class($specs['name']),
            'suffix' => Convention::affix($specs['type'])
        ], Path::glue([
            Path::head(self::$request['package'], $file['family']), $file['path_schema'], "{$file['name_schema']}.php"
        ]));
    }

    private static function setContainer(array $specs): string
    {
        $folder = Settings::folders($specs['type'], nullable: true);

        return $folder ?? Convention::folder($specs['type'], 'pascal', false);
    }

    private static function isNotUnique($file): bool
    {
        return !empty(array_filter(
            self::$files,
            fn ($x) => Arry::isEqual(['type', 'name', 'task'], $x, $file)
        ));
    }

    private static function isNotAddable($specs)
    {
        return file_exists($specs['path'])
            || self::isNotUnique($specs);
    }
}
