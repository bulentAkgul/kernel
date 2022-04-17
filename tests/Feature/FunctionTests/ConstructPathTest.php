<?php

namespace Bakgul\Kernel\Tests\Feature\FunctionTests;

use Bakgul\Kernel\Functions\ConstructPath;
use Bakgul\Kernel\Tests\TestCase;

class ConstructPathTest extends TestCase
{
    private $schema = '{{ apps }}{{ app }}{{ container }}{{ role }}{{ variation }}{{ folder }}{{ subs }}';
    /** @test */
    public function construct_path()
    {
        $this->assertEquals(base_path('clients/admin/views/pages'), ConstructPath::_([
            'attr' => [
                'path' => base_path() . $this->schema
            ],
            'map' => [
                'apps' => 'clients',
                'app' => 'admin',
                'container' => 'views',
                'folder' => 'pages'
            ]
        ]));
    }
}
