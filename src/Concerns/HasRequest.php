<?php

namespace Bakgul\Kernel\Concerns;

use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Settings;

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

    private function setPipeline()
    {
        $type = Isolation::type($this->request['type']);

        if ($type == 'view') {
            $type = Isolation::extra($this->request['type'])
                ?: Settings::apps("{$this->request['app']}.type")
                ?: $type;
        }

        return ['type' => $type, ...(Settings::resources($type) ?? [])];
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
}