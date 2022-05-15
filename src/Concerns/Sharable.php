<?php

namespace Bakgul\Kernel\Concerns;

use Bakgul\Kernel\Tasks\SerializeSignature;

trait Sharable
{
    public function getSignature(): array
    {
        return array_filter(array_map('trim', explode("\n", $this->signature)));
    }

    public function resolveSignature()
    {
        return SerializeSignature::_($this->signature);
    }

    public function getCommandHelp(): array
    {
        return [
            'signature' => $this->getSignature(),
            'description' => $this->description,
            'examples' => $this->examples,
            'arguments' => $this->arguments,
            'options' => $this->options,
        ];
    }
}