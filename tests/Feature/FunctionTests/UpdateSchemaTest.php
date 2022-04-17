<?php

namespace Bakgul\Kernel\Tests\Feature\FunctionTests;

use Bakgul\Kernel\Tests\TestCase;

class UpdateSchemaTest extends TestCase
{
    public static function _(string $schema, string|array $search, string|array $replace): string
    {
        return str_replace(
            array_map(
                fn ($x) => "{{ {$x} }}",
                is_array($search) ? $search : [$search]
            ),
            array_map(
                fn ($x) => str_replace(
                    ['{{ {{', '}} }}'],
                    ['{{', '}}'],
                    $x ? "{{ $x }}" : ''
                ),
                is_array($replace) ? $replace : [$replace]
            ),
            $schema
        );
    }
}
