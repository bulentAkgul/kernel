<?php

namespace Bakgul\Kernel\Concerns;

use Bakgul\Kernel\Functions\SetPipeline;
use Bakgul\Kernel\Helpers\Arry;

trait HasRequest
{
    public function makeFileRequest(array $file, array $queue): array
    {
        return array_merge($this->request, $file, [
            'force' => $this->request['force'] && $file['isForcable'],
            'command' => $this->request,
            'queue' => $queue,
            'signature' => $this->resolveSignature(),
            'pipeline' => $this->setPipeline()
        ]);
    }

    public function makePackageRequest(): array
    {
        return [
            ...$this->request,
            'signature' => $this->resolveSignature(),
            'job' => 'package',
            'variation' => '',
            'family' => 'src',
        ];
    }

    private function setPipeline()
    {
        return Arry::get($this->request, 'pipeline') ?? SetPipeline::_($this->request);
    }
}