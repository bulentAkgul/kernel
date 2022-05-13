<?php

namespace Bakgul\Kernel\Commands;

use Bakgul\Kernel\Concerns\HasConfig;
use Bakgul\Kernel\Helpers\Arry;
use Illuminate\Console\Command;
use Bakgul\Kernel\Helpers\Package;
use Bakgul\Kernel\Helpers\Path;

class PublishConfig extends Command
{
    use HasConfig;

    protected $signature = 'packagify:publish-config {--f|force}';
    protected $description = '';

    private $configs = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $target = config_path('packagify.php');

        if (file_exists($target)) {
            if (!$this->option('force')) return;

            unlink($target);
        }

        $this->collect();
        
        $this->combine();

        $this->write($target);
    }

    private function collect()
    {
        $src = Path::base(['vendor', 'bakgul']);

        foreach (Package::vendor('all') as $package) {
            foreach ($this->collectConfig($src, $package) as $key => $config) {
                $this->configs[$key] = array_merge(Arry::get($this->configs, $key) ?? [], $config);
            }
        }

        ksort($this->configs);
    }

    private function collectConfig($src, $package)
    {
        $path = Path::glue([$src, $package]);
        $ignore = require Path::glue([$path, 'config', '.ignore.php']);

        return array_filter(
            $this->getConfigs($path),
            fn ($x) => !in_array($x, $ignore),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function combine()
    {
        $configs = [];

        foreach ($this->configs as $key => $config) {
            array_unshift($config, "    '{$key}' => [");
            array_push($config, "    ],");
            $configs = array_merge($configs, $config);
        }

        array_splice($configs, 0, 0, ['<?php', '', ...$this->message(), '', 'return [']);
        array_push($configs, '];');

        $this->configs = $configs;
    }

    private function message(): array
    {
        return [
            "/*",
            "|--------------------------------------------------------------------------",
            "| Packagified Laravel Settings",
            "|--------------------------------------------------------------------------",
            "|",
            "| This config file can be seen overwelmingly long, but don't worry, you",
            "| won't need to deal with most part probably. But before everything else,",
            "| there are some settings that should be set:",
            "| • apps, ",
            "| • identity,",
            "| • repository,",
            "| • requires,",
            "| • roots,",
            "|",
            "| You can find more details about those settings in the comment blocks.",
            "|",
            "*/",
        ];
    }

    private function write($target): void
    {
        file_put_contents($target, implode(PHP_EOL, $this->configs));
    }
}
