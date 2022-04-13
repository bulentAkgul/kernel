<?php

namespace Bakgul\Kernel\Tests\Concerns;

trait HasTestMethods
{
    public function setRequest(array $modifications = [], string $key = 'file'): array
    {
        return array_merge($this->requests[$key], $modifications);
    }
}