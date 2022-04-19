<?php

namespace Bakgul\Kernel\Tests\Feature\HelperTests;

use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Tests\TestCase;

class IsolationTest extends TestCase
{
    /** @test */
    public function isolation_tasks()
    {
        $this->assertEquals(['index', 'store'], Isolation::tasks('user:index.store'));
        $this->assertEquals([''], Isolation::tasks('user'));
        $this->assertEquals(['store', 'update'], Isolation::tasks('user', 'request'));
    }
}