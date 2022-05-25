<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\FileCreator\Commands\CreateFileCommand;
use Bakgul\FileCreator\Services\FileService;
use Bakgul\Kernel\Concerns\HasRequest;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Tasks\MakeFileList;
use Bakgul\PackageGenerator\Commands\CreatePackageCommand;
use Bakgul\PackageGenerator\Services\PackageService;
use Bakgul\ResourceCreator\Commands\CreateResourceCommand;
use Bakgul\ResourceCreator\Services\ResourceService;

class SimulateArtisanCall
{
    use HasRequest;

    public $request;
    public $command;

    public function __invoke($request, $command = 'file')
    {
        $this->request = $request;

        match ($command) {
            'file' => self::simulateFileCommand($this->queue($request)),
            'resource' => self::simulateResourceCommand($this->queue($request)),
            'package' => self::simulatePackageCommand(),
            default => null
        };
    }

    private function queue($request)
    {
        return Arry::get($request, 'queue') ?? MakeFileList::_($request);
    }

    private function simulateFileCommand($queue)
    {
        $this->command = new CreateFileCommand;

        foreach ($queue as $file) {
            (new FileService)->create($this->makeFileRequest($file, $queue));
        }
    }

    private function simulateResourceCommand($queue)
    {
        $this->command = new CreateResourceCommand;

        foreach ($queue as $file) {
            (new ResourceService)->create($this->makeFileRequest($file, $queue));
        }
    }

    private function simulatePackageCommand()
    {
        $this->command = new CreatePackageCommand;

        (new PackageService)->handle($this->makePackageRequest());
    }

    private function resolveSignature()
    {
        return $this->command->resolveSignature();
    }
}
