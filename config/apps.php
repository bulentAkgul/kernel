<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Apps
    |--------------------------------------------------------------------------
    |
    | The list of the apps that will be stored in the same repository.
    | Feel free to delete the ones you don't need and add more by following
    | the same schema.
    |
    | Type: One of the keys whose category is "view" in the "resources" array.
    | Folder: A name as you wish. It will be used without any modifications.
    | Route Group: If it's admin, the route will be "your-app.com/admin/..."
    |              Subdomains will be supported in future releases.
    | Router: Leave it empty to use the default router of the specified type.
    |         You can set 'inertia' if the type is 'vue.'
    | Medium: If it's "browser," a Blade file will be generated in that app.
    |         The other options have no effect on this release.
    |
    */
    'admin' => [
        'type' => 'vue',
        'folder' => 'admin',
        'route_group' => 'admin',
        'router' =>  '',
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
