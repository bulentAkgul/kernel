<?php

namespace Bakgul\Kernel\Concerns;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Text;

trait HasConfig
{
    public function getConfigs(string $path)
    {
        $configs = [];

        foreach ($this->getConfigFiles($path) as $key => $file) {
            $configs[$key] = $this->fetch($file);
        }

        return $configs;
    }

    public function registerConfigs(string $path)
    {
        if (file_exists(Path::base(['config', 'packagify.php']))) return;

        foreach ($this->getConfigFiles($path) as $key => $file) {
            config()->set("packagify.{$key}", $this->mergeConfigs($key, $file));
        }
    }

    private function getConfigFiles(string $path)
    {
        $path = Path::glue(["$path", "config"]);

        if (!file_exists($path)) return [];

        $files = [];

        foreach (Folder::content($path) as $file) {
            $files[str_replace('.php', '', $file)] = Path::glue([$path, $file]);
        }

        return $files;
    }

    private function fetch($file) {
        return array_map(
            fn ($x) => "    {$x}",
            array_slice(Text::split(trim(file_get_contents($file))), 3, -1)
        );
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
}
