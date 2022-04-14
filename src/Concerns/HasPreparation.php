<?php

namespace Bakgul\Kernel\Concerns;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;

trait HasPreparation
{
    public array $request;
    private $uselessKeys = ["help", "quiet", "verbose", "version", "ansi", "no-interaction", "env"];

    public function prepareRequest(): void
    {
        $this->request = array_filter(
            array_merge($this->setArguments(), $this->setOptions()),
            fn ($x) => !in_array($x, $this->uselessKeys),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function setArguments(): array
    {
        $arguments = $this->arguments();

        if (Settings::standalone()) {
            $arguments['app'] = Arry::get($arguments, 'package');
            $arguments['package'] = null;
        }
        
        return $arguments;
    }

    private function setOptions(): array
    {
        return array_map(fn ($x) => $this->clear($x), $this->options());
    }

    private function clear($option)
    {
        return is_string($option) ? str_replace('=', '', $option) : $option;
    }
}