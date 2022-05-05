<?php

namespace Bakgul\Kernel\Tests;

use Bakgul\Kernel\Concerns\CreatesApplication;
use Bakgul\Kernel\Helpers\Settings;
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

        $this->handleFakeBase();

        $this->evaluate(true);
    }

    private function handleFakeBase()
    {
        app()->setBasePath(base_path(Settings::folders('test_base')));

        if (!file_exists(base_path())) mkdir(base_path());
    }

    protected function evaluate(bool $evaluate)
    {
        Settings::set('evaluator.evaluate_commands', $evaluate);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
