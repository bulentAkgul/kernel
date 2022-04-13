<?php

namespace Bakgul\Kernel\Tests\Tasks;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tests\Services\FileSecurityService;
use Bakgul\Kernel\Tests\Services\TestDataService;

class SetupTest
{
    public function __invoke(?array $standalones = null, bool $isBlank = false)
    {
        $this->standalone($standalones);

        $package = (new ManagePackage)->prepare($isBlank);

        (new CollectFiles)->_($package, 'resources', $isBlank);

        FileSecurityService::backup(TestDataService::files());

        return $package;
    }

    public function standalone($standalones)
    {
        if (!$standalones) return;
        
        config()->set('packagify.main.standalone_package', $standalones[0]);
        config()->set('packagify.main.standalone_laravel', $standalones[1]);
    }
}
