<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Packages' Roots
    |--------------------------------------------------------------------------
    |
    | The roots will be used if you work on a Packagified Laravel app.
    | The packages will be stored in different root folders. Here, we
    | have two of them, but you can add more.

    | "Essential" is the root for the general-purpose packages used in
    | different apps without requiring lots of modifications.
    |
    | "Specific" is the root for packages that are developed probably
    | only for the current app.
    | 
    */
    'essential' => ['folder' => 'core', 'namespace' => 'Core'],
    'specific' => ['folder' => 'features', 'namespace' => null],
];