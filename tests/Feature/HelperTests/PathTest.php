<?php

namespace Bakgul\Kernel\Tests\Feature\HelperTests;

use Bakgul\Kernel\Tests\Concerns\HasTestMethods;
use Bakgul\Kernel\Helpers\Package;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\Kernel\Tests\TestCase;

class PathTest extends TestCase
{
    use HasTestMethods;

    private $families = ['src', 'database', 'tests'];

    /** @test */
    public function path_head()
    {
        config()->set('packagify.apps.admin.folder', 'xxx');

        foreach ($this->families as $family) {
            $this->testPackage = (new SetupTest)([false, false]);

            $this->assertEquals(
                $this->path($family),
                Path::head(null, $family)
            );

            $this->assertEquals(
                $this->path($family, true),
                Path::head($this->testPackage['name'], $family)
            );

            $this->assertEquals(
                $this->path($family),
                Path::head('admin', $family)
            );

            $this->assertEquals(
                $this->path($family),
                Path::head('xxx', $family)
            );

            $this->assertEquals(
                $this->path($family),
                Path::head('unknown_package_name', $family)
            );

            $this->testPackage = (new SetupTest)([false, true]);

            $this->assertEquals(
                $this->path($family),
                Path::head(null, $family)
            );

            $this->assertEquals(
                $this->path($family),
                Path::head($this->testPackage['name'], $family)
            );

            $this->assertEquals(
                $this->path($family),
                Path::head('admin', $family)
            );

            $this->assertEquals(
                $this->path($family),
                Path::head('xxx', $family)
            );

            $this->assertEquals(
                $this->path($family),
                Path::head('unknown_package_name', $family)
            );

            $this->testPackage = (new SetupTest)([true, false]);

            $this->assertEquals(
                base_path($family),
                Path::head(null, $family)
            );

            $this->assertEquals(
                base_path($family),
                Path::head($this->testPackage['name'], $family)
            );

            $this->assertEquals(
                base_path($family),
                Path::head('admin', $family)
            );

            $this->assertEquals(
                base_path($family),
                Path::head('xxx', $family)
            );

            $this->assertEquals(
                base_path($family),
                Path::head('unknown_package_name', $family)
            );

            $this->testPackage = (new SetupTest)([true, true]);

            $this->assertEquals(
                base_path($family),
                Path::head(null, $family)
            );

            $this->assertEquals(
                base_path($family),
                Path::head($this->testPackage['name'], $family)
            );

            $this->assertEquals(
                base_path($family),
                Path::head('admin', $family)
            );

            $this->assertEquals(
                base_path($family),
                Path::head('xxx', $family)
            );

            $this->assertEquals(
                base_path($family),
                Path::head('unknown_package_name', $family)
            );
        }
    }

    private function path($family, $isPackage = false)
    {
        return $isPackage
            ? Path::base([Package::container(), $this->testPackage['folder'], $this->testPackage['name'], $family])
            : base_path($family == 'src' ? 'app' : $family);
    }
}
