<?php

namespace Bakgul\Kernel\Tests\Feature\HelperTests;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tests\TestCase;

class TextTest extends TestCase
{
    /** @test */
    public function text_wrap()
    {
        $this->assertEquals(
            '', Text::wrap('', '/')
        );

        $this->assertEquals(
            'placeholder', Text::wrap('placeholder', '')
        );

        $this->assertEquals(
            DIRECTORY_SEPARATOR . 'placeholder' . DIRECTORY_SEPARATOR, Text::wrap('placeholder')
        );

        $this->assertEquals(
            '/placeholder/', Text::wrap('placeholder', '/')
        );

        $this->assertEquals(
            'xplaceholderx', Text::wrap('placeholder', 'x')
        );

        $this->assertEquals(
            "'placeholder'", Text::wrap('placeholder', 'sq')
        );

        $this->assertEquals(
            '"placeholder"', Text::wrap('placeholder', 'dq')
        );

        $this->assertEquals(
            '{placeholder}', Text::wrap('placeholder', '{')
        );

        $this->assertEquals(
            '(placeholder)', Text::wrap('placeholder', '(')
        );

        $this->assertEquals(
            '[placeholder]', Text::wrap('placeholder', '[')
        );
    }

    /** @test */
    public function text_inject()
    {
        

        $this->assertEquals(
            "{'placeholder'}", Text::inject('placeholder', ['{', 'sq'])
        );

        $this->assertEquals(
            '["placeholder"]', Text::inject('placeholder', ['[', 'dq'])
        );

        $this->assertEquals(
            "('" . 'x"{yAy}"x' . "')", Text::inject('A', ['(', 'sq', 'x', 'dq', '{', 'y'])
        );
    }
}