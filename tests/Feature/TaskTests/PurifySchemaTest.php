<?php

namespace Bakgul\Kernel\Tests\Feature\TaskTests;

use Bakgul\Kernel\Tasks\PurifySchema;
use Bakgul\Kernel\Tests\TestCase;

class PurifySchemaTest extends TestCase
{
    private $schema = '{{ apps }}{{ app }}{{ container }}{{ role }}{{ variation }}{{ folder }}{{ subs }}';
    
    /** @test */
    public function purify_schema_only()
    {
        $this->assertEquals('', PurifySchema::_([], $this->schema));

        $this->assertEquals(
            '{{ apps }}{{ app }}{{ role }}{{ folder }}',
            PurifySchema::_(['apps', 'app', 'role', 'folder'], $this->schema)
        );

        $this->assertEquals(
            $this->schema,
            PurifySchema::_(['apps', 'app', 'container', 'role', 'variation', 'folder', 'subs'], $this->schema)
        );
    }

    /** @test */
    public function purify_schema_as_tail()
    {
        $this->assertEquals(
            base_path() . '{{ apps }}{{ app }}{{ role }}{{ folder }}',
            PurifySchema::_(['apps', 'app', 'role', 'folder'], base_path() . $this->schema)
        );

        $this->assertEquals(
            'use{{ apps }}{{ app }}{{ role }}',
            PurifySchema::_(['apps', 'app', 'role'], "use{$this->schema}")
        );
    }

    /** @test */
    public function purify_schema_as_head()
    {
        $this->assertEquals(
            '{{ apps }}{{ app }}{{ variation }}tail',
            PurifySchema::_(['apps', 'app', 'variation'], "{$this->schema}tail")
        );

        $this->assertEquals(
            '{{ apps }}{{ app }}{{ variation }}/tail',
            PurifySchema::_(['apps', 'app', 'variation'], "{$this->schema}/tail")
        );
    }

    /** @test */
    public function purify_schema_in_between()
    {
        $this->assertEquals(
            'use{{ apps }}{{ app }}{{ subs }}/tail',
            PurifySchema::_(['apps', 'app', 'subs'], "use{$this->schema}/tail")
        );
    }
}