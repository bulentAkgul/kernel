<?php

namespace Bakgul\Kernel\Tests\Feature\TaskTests;

use Bakgul\Kernel\Tasks\MakeFileList;
use Bakgul\Kernel\Tests\TestCase;

class MakeFileListTest extends TestCase
{
    /** @test */
    public function first_one()
    {
        $list = MakeFileList::_($this->request());
        
        $this->assertCount(2, $list);
    }

    private function request($modifications = [])
    {
        return [
            "command" => "create:file",
            "name" => "dog",
            "type" => "action",
            "package" => "testing",
            "app" => "admin",
            "parent" => "animal",
            "taskless" => false,
            "force" => false,
            ...$modifications
        ];
    }
}
