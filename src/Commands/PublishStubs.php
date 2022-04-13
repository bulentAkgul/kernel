<?php

namespace Bakgul\Kernel\Commands;

use Bakgul\Kernel\Helpers\Arry;
use Illuminate\Console\Command;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Package;
use Bakgul\Kernel\Helpers\Path;

class PublishStubs extends Command
{
    protected $signature = 'packagify:publish-stub {package?} {--f|force}';
    protected $description = '';

    private $paths = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        array_map(
            fn ($package) => $this->publish($package),
            Package::vendor($this->argument('package'))
        );
    }

    private function publish($package)
    {
        foreach ($this->stubs($package) as $folder => $files) {
            $this->copy($files, $this->completeFolders($folder));
        }
    }

    private function stubs($package)
    {
        return Arry::get(Folder::tree(Path::base(["vendor", "bakgul", $package])), 'stubs') ?? [];
    }

    private function completeFolders(string $folder): string
    {
        $target = base_path();

        foreach (['stubs', 'packagify', $folder] as $dir) {
            $target .= DIRECTORY_SEPARATOR . $dir;

            if (!file_exists($target)) mkdir($target);
        }

        return $target;
    }

    private function copy(array $files, string $target)
    {
        foreach ($files as $name => $src) {
            copy($src, Path::glue([$target, $name]));
        }
    }
}
