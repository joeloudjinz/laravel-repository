<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Base Values
    |--------------------------------------------------------------------------
    | this array give you the ability to define base path & namespace to be used by the package,
    | path is used to create the base directory in which all generated files are stored, on
    | the other hand, namespace is used to define the base namespace for those files.
    |
    | Note that (in the comments) the anti-slash for namespace is required.
    |
     */
    'base' => [
        'path' => app_path(), // 'app' which is the best choice
        'namespace' => app()->getNamespace(), // 'App\' which is the best choice
        'providers' => [
            'path' => app_path(),
            'namespace' => app()->getNamespace(),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    | this array define the namespaces related to each category of the generated classes
    | by the package, you can re-define these values to control under what namespace
    | the these classes should reside.
    |
    | Note that anti-slash in the end is not set so make sure you don't add it.
     */
    'namespaces' => [
        'contracts' => 'Repositories\Contracts',
        'implementations' => 'Repositories\Implementations',
    ],

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    | this array defines the path for each category of the generated classes by the package,
    | you can re-define these values to control where the files should be stored, by
    | default they are stored in the application directory.
    |
    | Note that slash in the end is not set so make sure you don't add it.
     */
    'paths' => [
        'contracts' => 'Repositories/Contracts',
        'implementations' => 'Repositories/Implementations',
    ],
];
