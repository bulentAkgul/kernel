<?php

namespace Bakgul\Kernel\Concerns;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    public function createApplication()
    {
        $app = require implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', '..', '..', 'bootstrap', 'app.php']);

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}