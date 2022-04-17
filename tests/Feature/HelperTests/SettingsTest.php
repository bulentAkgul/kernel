<?php

namespace Bakgul\Kernel\Tests\Feature\HelperTests;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tests\TestCase;

class SettingsTest extends TestCase
{
    /** @test */
    public function settings_get_simple_value()
    {
        ray(Settings::apps());
        $this->assertNotNull(Settings::apps());
    }
}