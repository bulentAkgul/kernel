<?php

namespace Bakgul\Kernel\Concerns;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

trait HasConfig
{
    public function registerConfigs(string $path)
    {
        foreach ($this->getConfigFiles($path) as $key => $file) {
            Settings::set($key, $this->mergeConfigs($key, $file));
        }
    }

    private function getConfigFiles(string $path)
    {
        $path = Path::glue(["$path", "config"]);

        if (!file_exists($path)) return [];

        $isAppend = $this->isConfigPublished();

        $files = [];

        foreach ($this->files($path, $isAppend) as $file) {
            $files[str_replace('.php', '', $file)] = Path::glue([$path, $file]);
        }

        return $files;
    }

    private function isConfigPublished()
    {
        return file_exists(Path::base(['config', 'packagify.php']));
    }

    private function files($path, $isAppend)
    {
        return array_filter(
            $isAppend ? $this->ignoredFiles($path) : Folder::content($path),
            fn ($x) => $x != '.ignore.php'
        );
    }

    private function ignoredFiles($path)
    {
        return array_map(fn ($x) => "{$x}.php", require Path::glue([$path, '.ignore.php']));
    }

    private function mergeConfigs($key, $file)
    {
        $config = config("packagify.{$key}") ?? [];

        foreach (require $file as $k => $value) {
            $config[$k] = is_array($value)
                ? array_merge(Arry::get($config, $k) ?? [], $value)
                : $value;
        }

        return $config;
    }

    public function getConfigs(string $path)
    {
        $configs = [];

        foreach ($this->getConfigFiles($path) as $key => $file) {
            $configs[$key] = $this->fetch($file);
        }

        return $configs;
    }

    private function fetch($file)
    {
        return array_map(
            fn ($x) => "    {$x}",
            array_slice(Text::split(trim(file_get_contents($file))), 3, -1)
        );
    }
}
