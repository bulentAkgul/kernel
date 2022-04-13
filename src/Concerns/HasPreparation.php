<?php

namespace Bakgul\Kernel\Concerns;

trait HasPreparation
{
    public array $request;
    private $uselessKeys = ["help", "quiet", "verbose", "version", "ansi", "no-interaction", "env"];

    public function prepareRequest(): void
    {
        $this->request = array_filter(
            array_merge($this->arguments(), $this->setOptions()),
            fn ($x) => !in_array($x, $this->uselessKeys),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function setOptions()
    {
        return array_map(fn ($x) => $this->clear($x), $this->options());
    }

    private function clear($option)
    {
        return is_string($option) ? str_replace('=', '', $option) : $option;
    }
}