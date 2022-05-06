<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Packages' Roots
    |--------------------------------------------------------------------------
    |
    | The roots will be used if you work on a packagified app. The packages
    | will be stored in different root folders. Here, we have two of them,
    | but you can add as many as you need.
    |
    | Essential are the general purpose packages that can be used in
    | different apps.
    | Specific packages are the ones that are developed probably only
    | for the current app.
    | 
    */
    'essential' => ['folder' => 'core', 'namespace' => 'Core'],
    'specific' => ['folder' => 'features', 'namespace' => null],
];