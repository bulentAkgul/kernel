<?php

namespace Bakgul\Kernel\Tests;

use Bakgul\Kernel\Concerns\CreatesApplication;
use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\Kernel\Tests\Tasks\TierDownTest;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, LazilyRefreshDatabase;

    public $testPackage;
    public $removables = ['before' => true, 'after' => false];

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        (new TierDownTest)($this->testPackage, $this->removables['after']);

        parent::tearDown();
    }
}
