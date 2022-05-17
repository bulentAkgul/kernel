<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Repository
    |--------------------------------------------------------------------------
    |
    | With Packagified Laravel, you can work with 3 types of repositories.
    | You will tell your app type by changing the settings here. These
    | values shouldn't be changed in the future. I didn't test what happens
    | if you do so, but I bet it will ruin your app.
    | 
    | Standalone Laravel: Make it "true" if it's a standard Laravel app.
    | Standalone Package: Make it "true" if you are developing a package
    |                     that will be installed through composer.
    | 
    | If both standalone settings are "false," your repository will be
    | a Packagified Laravel. This means you will have a normal Laravel
    | repository. But, the functionalities will be split into the packages
    | instead of being coded in the "app" and "resources" folders.
    |
    */
    'standalone_laravel' => false,
    'standalone_package' => false,
];
