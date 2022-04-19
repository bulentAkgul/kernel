<?php

return [
    'admin' => [
        'type' => 'vue',
        'folder' => 'admin',
        'route_group' => 'manage',
        'router' =>  'inertia',
    ],
    'desktop' => [
        'type' => 'electron',
        'folder' => 'desktop',
        'route_group' => '',
        'router' =>  '',
    ],
    'mobile' => [
        'type' => 'ionic',
        'folder' => 'mobile',
        'route_group' => 'm',
        'router' =>  '',
    ],
    'web' => [
        'type' => 'blade',
        'folder' => 'web',
        'route_group' => '',
        'router' =>  '',
    ],
];
