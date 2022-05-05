<?php

namespace Bakgul\Kernel\Tests\Tasks;

use Bakgul\Kernel\Helpers\Settings;

class SetupTest
{
    public function __invoke(?array $standalone = null, bool $isBlank = false)
    {
        $this->standalone($standalone);

        $package = (new ManagePackage)->prepare($isBlank);

        (new CollectFiles)->_($package, 'resources', $isBlank);

        return $package;
    }

    public function standalone($standalone)
    {
        if (!$standalone) return;
        
        Settings::set('main.standalone_package', $standalone['sp']);
        Settings::set('main.standalone_laravel', $standalone['sl']);
    }
}
