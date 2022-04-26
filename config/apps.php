<?php

return [
    'admin' => [
        'type' => 'vue',
        'folder' => 'admin',
        'route_group' => 'admin',
        'router' =>  'inertia',
        'medium' => 'browser',
    ],
    'desktop' => [
        'type' => 'electron',
        'folder' => 'desktop',
        'route_group' => '',
        'router' =>  '',
        'medium' => 'os',
    ],
    'mobile' => [
        'type' => 'ionic',
        'folder' => 'mobile',
        'route_group' => 'm',
        'router' =>  '',
        'medium' => 'phone',
    ],
    'web' => [
        'type' => 'blade',
        'folder' => 'web',
        'route_group' => '',
        'router' =>  '',
        'medium' => 'browser',
    ],
];
