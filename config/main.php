<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Main From Kernel
    |--------------------------------------------------------------------------
    |
    | Whit Packagified Laravel, you can work with 3 types of repositories,
    | and you will tell which type it's by changing the settings here.
    | 
    | Standalone Laravel: Meke it "true" if it's a very standard Laravel app.
    | Standalone Package: Make it "true" if you are developing a package.
    |
    | If both standalone settings are "false", then your repository will be
    | a packagified Laravel. This means you will create the functionalities
    | in the packages instead of "app" or "resources" folder, but those
    | default folders will be in use.
    |
    */
    'standalone_laravel' => false,
    'standalone_package' => false,
];