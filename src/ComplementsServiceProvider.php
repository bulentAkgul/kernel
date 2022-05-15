<?php

namespace Bakgul\Kernel;

use Bakgul\Kernel\Concerns\HasConfig;
use Illuminate\Support\ServiceProvider;

class ComplementsServiceProvider extends ServiceProvider
{
    use HasConfig;

    public function boot()
    {
        $this->commands([
            \Bakgul\Kernel\Commands\CountLinesCommand::class,
            \Bakgul\Kernel\Commands\GetHelpCommand::class,
            \Bakgul\Kernel\Commands\PublishConfig::class,
            \Bakgul\Kernel\Commands\PublishStubs::class,
        ]);
    }

    public function register()
    {
        $this->registerConfigs(__DIR__ . DIRECTORY_SEPARATOR . '..');
    }
}
