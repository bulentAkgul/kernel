<?php

namespace Bakgul\Kernel\Concerns;

use Bakgul\Kernel\Tasks\SerializeSignature;

trait Sharable
{
    public function getSignature(): string
    {
        return $this->signature;
    }

    public function resolveSignature()
    {
        return SerializeSignature::_($this->getSignature());
    }

    public function getCommandHelp(): array
    {
        return [
            'signature' => $this->getSignature(),
            'description' => $this->description
        ];
    }
}