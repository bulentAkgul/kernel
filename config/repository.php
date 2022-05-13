<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Repository
    |--------------------------------------------------------------------------
    |
    | Whit Packagified Laravel, you can work with 3 types of repositories,
    | and you will tell which type it's by changing the settings here.
    | 
    | Standalone Laravel: Meke it "true" if it's a very standard Laravel app.
    | Standalone Package: Make it "true" if you are developing a package that
    |                     will be installed through composer.
    |
    | If both standalone settings are set to "false", then your repository
    | will be a packagified Laravel. This means you will have a normal
    | Laravel repository, but the functionalities will be splitted into
    | the packages instead of "app" and "resources" folders.
    |
    */
    'standalone_laravel' => false,
    'standalone_package' => false,
];
