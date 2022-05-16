<?php

namespace Bakgul\Kernel\Tests\Tasks;

use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Package;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tasks\CompleteFolders;
use Bakgul\Kernel\Tests\Services\TestDataService;
use Illuminate\Filesystem\Filesystem;

class ManagePackage
{
    public $package;

    public function prepare($isBlank)
    {
        $this->wipeOut();

        $this->setTestPackage();

        $this->makeFolders($isBlank);

        $this->extendPackageTypes();

        return $this->package;
    }

    private function wipeOut()
    {
        (new Filesystem)->deleteDirectory(Path::realBase(Settings::folders('test_base')));
    }

    public function setTestPackage()
    {
        $this->package = TestDataService::package(standalone: Settings::standalone());

        $this->package['path'] = Path::root($this->package['path']);
    }

    public function makeFolders($isBlank): void
    {
        $path = base_path(Package::container(false));

        CompleteFolders::_($path, false);

        Folder::refresh($path);

        if ($isBlank) return;

        $this->addUsersPackage($path);

        $this->makeFolder($this->package, $path);
    }

    private function addUsersPackage(string $path): void
    {
        if (Settings::standalone()) return;

        $this->makeFolder([
            'folder' => Settings::roots('essential.folder'),
            'name' => 'users'
        ], $path);
    }

    public function makeFolder(array $package, string $path)
    {
        foreach ([$package['folder'], $package['name']] as $folder) {
            if (!$folder) continue;
            $path .= DIRECTORY_SEPARATOR . $folder;
            if (!file_exists($path)) mkdir($path);
        }

        return $path;
    }

    public function extendPackageTypes(): void
    {
        if (Settings::standalone()) return;

        Settings::set(
            "roots.{$this->package['folder']}",
            array_filter($this->package, fn ($x) => $x != 'name', ARRAY_FILTER_USE_KEY)
        );
    }

    public static function delete(?array $package, bool $isRemovable)
    {
        if ($isRemovable && $package) (new Filesystem)->deleteDirectory(
            Path::base([Package::container(), $package['folder'], $package['name']])
        );
    }
}
