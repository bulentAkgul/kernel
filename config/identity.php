<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Indentity
    |--------------------------------------------------------------------------
    |
    | Some main settings of your repository.
    | 
    | Vendor, Name, and Email: These are the personal pieces of information 
    |                          used in the "composer.json" files.
    | Package: It must be unique throughout the app. It will be used only 
    |          in the Packagified Laravel app.
    | Registrar: It's a shorthand for registering your package files such as 
    |            blade views and config in a standalone package. It'll collect 
    |            the packages' config values in Packagified Laravel.
    | Namespace: The default value of any "null" namespace in "roots" and the 
    |            namespace of the standalone package. 
    |
    */
    'vendor' => 'bakgul',
    'name' => 'Bulent Akgul',
    'email' => 'bulent.akgul@me.com',
    'package' => 'my awesome package',
    'registrar' => 'awesome',
    'namespace' => 'Awesomes'
];