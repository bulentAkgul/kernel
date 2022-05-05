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
    | Essential packages are the ones that can be used in different apps.
    | Specific packages are the ones that are used probably only in the current app.
    | 
    */
    'essential' => ['folder' => 'core', 'namespace' => 'Core'],
    'specific' => ['folder' => 'features', 'namespace' => null],
];