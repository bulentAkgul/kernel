<?php

namespace Bakgul\Kernel\Tests\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileContent\Tasks\CompleteFolders;
use Illuminate\Filesystem\Filesystem;

class CollectFiles
{
    private string $src;
    private array $files;

    public function _(array $package, string $family, bool $isBlank)
    {
        $this->src = Path::glue([Text::dropTail(__DIR__), 'RequiredFiles']);

        $this->copyComposer();

        if ($isBlank) return;

        $this->copyMainFiles($package['path']);

        $this->copyFamilyFiles($package, $family);
    }
    
    private function copyComposer()
    {
        copy(
            Path::glue([$this->src, 'composer.json']),
            base_path('composer.json')
        );
    }

    private function copyMainFiles(string $path)
    {
        (new Filesystem)->copyDirectory(
            Path::glue([$this->src, 'routes']),
            Path::glue([$path, 'routes'])
        );
    }

    private function copyFamilyFiles($package, $family)
    {
        foreach (Settings::apps() as $app) {
            foreach (['view', 'js', 'css'] as $type) {
                $this->setFiles($app['type'], $type);

                if (empty($this->files)) continue;

                $resource = Settings::resources($app['type']);

                $this->copyOptionalFiles($resource, $package, $family, $app, $type);

                $this->copyFiles($this->files, $package, $family, $app, $type);
            }
        }
    }

    private function setFiles($appType, $fileType)
    {
        $this->files = array_values(array_filter(
            Folder::content(Path::glue([$this->src, 'resources', $fileType])),
            fn ($x) => str_contains($x, "{$appType},")
        ));
    }

    private function copyOptionalFiles($resource, $package, $family, $app, $type)
    {
        foreach ($resource['options'] as $option => $value) {
            if (!$value) continue;

            $this->copyFiles($this->getFiles($option), $package, $family, $app, $type);
        }
    }

    private function getFiles($option)
    {
        $optionFiles = array_filter($this->files, fn ($x) => str_contains($x, $option));
        $this->files = array_diff($this->files, $optionFiles);

        return $optionFiles;
    }

    private function copyFiles($files, $package, $family, $app, $type)
    {
        array_map(fn ($x) => $this->copy($x, $package, $family, $app, $type), $files);
    }

    private function copy($file, $package, $family, $app, $type)
    {
        $path = Path::glue(array_filter([
            $package['path'],
            Settings::folders($family),
            $family == 'resources' ? Settings::folders('apps') : '',
            $app['folder'],
            Settings::folders($type),
            $this->subs($file)
        ]));

        CompleteFolders::_($path, false);

        copy(
            Path::glue([$this->src, $family, $type, $file]),
            Path::glue([$path, Arry::get(explode(',', $file), 'L')])
        );
    }

    private function subs(string $file)
    {
        $parts = explode(',', $file);

        return count($parts) != 2 ? str_replace('.', DIRECTORY_SEPARATOR, $parts[1]) : '';
    }
}
