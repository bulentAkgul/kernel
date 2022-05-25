<?php

namespace Bakgul\Kernel\Tests\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\CompleteFolders;
use Illuminate\Filesystem\Filesystem;

class CollectFiles
{
    private string $src;
    private array $files;

    public function _(array $package, string $family, bool $isBlank)
    {
        $this->src = Path::glue([Text::dropTail(__DIR__), 'RequiredFiles']);

        $this->copyRootFiles();

        if ($isBlank) return;

        $this->copyMainFiles($package['path']);

        $this->copyFamilyFiles($package, $family);
    }

    private function copyRootFiles()
    {
        foreach (['composer.json', 'phpunit.xml'] as $file) {
            copy(Path::glue([$this->src, $file]), base_path($file));
        }
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
                
                if ($this->noFile()) continue;
                
                $this->copyFiles($this->files, $package, $family, $app, $type);
            }
        }
    }

    private function setFiles($appType, $fileType)
    {
        foreach ($this->listFiles() as $folder => $files) {
            $this->files[$folder] = $this->reduceList($files, $appType, $fileType);
        }
    }

    private function reduceList($files, $appType, $fileType)
    {
        return array_filter($files, fn ($x) => $this->isFileRequired($x, $appType, $fileType));
    }

    private function listFiles()
    {
        $files = [];

        foreach ($this->setSrcFolders() as $folder) {
            $files[$folder] = Folder::files(Path::glue([$this->src, 'resources', $folder]));
        }

        return $files;
    }

    private function noFile(): bool
    {
        return !array_reduce($this->files, fn ($p, $c) => $p + count($c));
    }

    private static function setSrcFolders()
    {
        return match(true) {
            Settings::standalone('laravel') => ['client'],
            Settings::standalone('package') => ['package'],
            default => ['client', 'package']
        };
    }

    private function isFileRequired($path, $appType, $fileType)
    {
        return Text::containsAll($path, [$fileType . DIRECTORY_SEPARATOR , "{$appType},"]);
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
        foreach ($files as $folder => $paths) {
            foreach ($paths as $file) {
                $this->copy($file, $package, $family, $app, $type, $folder);
            }
        }
    }

    private function copy($file, $package, $family, $app, $type, $folder)
    {
        $path = Path::glue(array_filter([
            $folder == 'client' ? base_path() : $package['path'],
            Settings::folders($family),
            $family == 'resources' ? Settings::folders('apps') : '',
            $app['folder'],
            Settings::folders($type),
            $this->subs($file)
        ]));

        CompleteFolders::_($path, false);

        copy($file, Path::glue([$path, Arry::get(explode(',', $file), 'L')]));
    }

    private function subs(string $file)
    {
        $parts = explode(',', $file);

        return count($parts) != 2 ? str_replace('.', DIRECTORY_SEPARATOR, $parts[1]) : '';
    }
}
