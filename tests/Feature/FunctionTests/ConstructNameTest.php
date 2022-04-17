<?php

namespace Bakgul\Kernel\Tests\Feature\FunctionTests;

use Bakgul\Kernel\Functions\ConstructName;
use Bakgul\Kernel\Tests\TestCase;

class ConstructNameTest extends TestCase
{
    private $schema = '{{ prefix }}{{ name }}{{ task }}{{ suffix }}';

    /** @test */
    public function contruct_name()
    {
        $this->assertEquals('UsersIndex', ConstructName::_(
            ['convention' => 'pascal', 'name_schema' => $this->schema],
            ['prefix' => '', 'name' => 'users', 'task' => 'index', 'suffix' => '']
        ));

        $this->assertEquals('users_index', ConstructName::_(
            ['convention' => 'snake', 'name_schema' => $this->schema],
            ['prefix' => '', 'name' => 'users', 'task' => 'index', 'suffix' => '']
        ));

        $this->assertEquals('vipUsersService', ConstructName::_(
            ['convention' => 'camel', 'name_schema' => $this->schema],
            ['prefix' => 'vip', 'name' => 'users', 'task' => '', 'suffix' => 'service']
        ));

        $this->assertEquals('users-service', ConstructName::_(
            ['convention' => 'kebab', 'name_schema' => $this->schema],
            ['prefix' => '', 'name' => 'users', 'task' => '', 'suffix' => 'service']
        ));
    }
}