<?php

namespace Bakgul\Kernel\Tests\Tasks;

use Bakgul\Kernel\Tests\Services\FileSecurityService;
use Bakgul\Kernel\Tests\Services\TestDataService;

class TierDownTest
{
    public function __invoke($package, $isRemovable)
    {
        (new ManagePackage)->delete($package, $isRemovable);
        
        FileSecurityService::restore(TestDataService::files());
    }
}