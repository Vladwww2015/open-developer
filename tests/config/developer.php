<?php

return [

    /*
     * Open-developer name.
     */
    'name' => 'Open-developer',

    /*
     * Logo in developer panel header.
     */
    'logo' => '<b>Laravel</b> developer',

    /*
     * Mini-logo in developer panel header.
     */
    'logo-mini' => '<b>La</b>',

    /*
     * Route configuration.
     */
    'route' => [

        'prefix' => 'developer',

        'namespace' => 'App\\Developer\\Controllers',

        'middleware' => ['web', 'developer'],
    ],

    /*
     * Open-developer install directory.
     */
    'directory' => app_path('Developer'),

    /*
     * Open-developer html title.
     */
    'title' => 'Developer',

    /*
     * Use `https`.
     */
    'secure' => false,

    /*
     * Open-developer auth setting.
     */
    'auth' => [
        'guards' => [
            'developer' => [
                'driver'   => 'session',
                'provider' => 'developer',
            ],
        ],

        'providers' => [
            'developer' => [
                'driver' => 'eloquent',
                'model'  => OpenDeveloper\Developer\Auth\Database\Administrator::class,
            ],
        ],
    ],

    /*
     * Open-developer upload setting.
     */
    'upload' => [

        'disk' => 'developer',

        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
     * Open-developer database setting.
     */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'developer_users',
        'users_model' => OpenDeveloper\Developer\Auth\Database\Administrator::class,

        // Role table and model.
        'roles_table' => 'developer_roles',
        'roles_model' => OpenDeveloper\Developer\Auth\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'developer_permissions',
        'permissions_model' => OpenDeveloper\Developer\Auth\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'developer_menu',
        'menu_model' => OpenDeveloper\Developer\Auth\Database\Menu::class,

        // Pivot table for table above.
        'operation_log_table'    => 'developer_operation_log',
        'user_permissions_table' => 'developer_user_permissions',
        'role_users_table'       => 'developer_role_users',
        'role_permissions_table' => 'developer_role_permissions',
        'role_menu_table'        => 'developer_role_menu',
    ],

    /*
     * By setting this option to open or close operation log in open-developer.
     */
    'operation_log' => [

        'enable' => true,

        /*
         * Routes that will not log to database.
         *
         * All method to path like: developer/auth/logs
         * or specific method to path like: get:developer/auth/logs
         */
        'except' => [
            'developer/auth/logs*',
        ],
    ],

    /*
     * @see https://developerlte.io/docs/2.4/layout
     */
    'skin' => 'skin-blue-light',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    /*
     * Version displayed in footer.
     */
    'version' => '1.5.x-dev',

    /*
     * Settings for extensions.
     */
    'extensions' => [

    ],
];
