<?php

namespace Bakgul\Kernel\Tasks;

use Bakgul\FileCreator\Commands\CreateFileCommand;
use Bakgul\FileCreator\Services\FileService;
use Bakgul\Kernel\Concerns\HasRequest;
use Bakgul\Kernel\Tasks\MakeFileList;
use Bakgul\PackageGenerator\Commands\CreatePackageCommand;
use Bakgul\PackageGenerator\Services\PackageService;

class SimulateArtisanCall
{
    use HasRequest;

    public $request;
    public $command;

    public function __invoke($request, $command = 'file')
    {
        $this->request = $request;
        
        match ($command) {
            'file' => self::simulateFileCommand(MakeFileList::_($request)),
            'package' => self::simulatePackageCommand(),
            default => null
        };
    }

    private function simulateFileCommand($queue)
    {
        $this->command = new CreateFileCommand;

        foreach ($queue as $file) {
            (new FileService)->create($this->makeFileRequest($file, $queue));
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