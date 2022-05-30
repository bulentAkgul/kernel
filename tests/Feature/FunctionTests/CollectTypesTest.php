<?php

namespace Bakgul\Kernel\Tests\Feature\FunctionTests;

use Bakgul\Kernel\Functions\CollectTypes;
use Bakgul\Kernel\Tests\TestCase;

class CollectTypesTest extends TestCase
{
    /** @test */
    public function collect_type_will_work_only_for_single_type()
    {
        $types = CollectTypes::_('controller:api,listener');

        $this->assertEmpty(array_filter($types['main'], fn ($x) => $x['type'] == 'listener'));
    }
}