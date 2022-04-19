<?php

namespace Bakgul\Kernel\Tests\Tasks;

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
        
        config()->set('packagify.main.standalone_package', $standalone['sp']);
        config()->set('packagify.main.standalone_laravel', $standalone['sl']);
    }
}
