<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Open-developer name
    |--------------------------------------------------------------------------
    |
    | This value is the name of Open-developer, This setting is displayed on the
    | login page.
    |
    */
    'name' => 'Open Developer',

    /*
    |--------------------------------------------------------------------------
    | Open-developer logo
    |--------------------------------------------------------------------------
    |
    | The logo of all developer pages. You can also set it as an image by using a
    | `img` tag, eg '<img src="http://logo-url" alt="Developer logo">'.
    |
    */
    'logo' => '<b>Open</b> Developer',

    /*
    |--------------------------------------------------------------------------
    | Open-developer mini logo
    |--------------------------------------------------------------------------
    |
    | The logo of all developer pages when the sidebar menu is collapsed. You can
    | also set it as an image by using a `img` tag, eg
    | '<img src="http://logo-url" alt="Developer logo">'.
    |
    */
    'logo-mini' => '<b>OA</b>',

    /*
    |--------------------------------------------------------------------------
    | Open-developer bootstrap setting
    |--------------------------------------------------------------------------
    |
    | This value is the path of open-developer bootstrap file.
    |
    */
    'bootstrap' => app_path('Developer/bootstrap.php'),

    /*
    |--------------------------------------------------------------------------
    | Open-developer route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the developer page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [

        'prefix' => env('DEVELOPER_ROUTE_PREFIX', 'developer'),

        'namespace' => 'App\\Developer\\Controllers',

        'middleware' => ['web', 'developer'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Open-developer install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the developeristration page. The default is `app/Developer`, which must
    | be set before running `artisan developer::install` to take effect.
    |
    */
    'directory' => app_path('Developer'),

    /*
    |--------------------------------------------------------------------------
    | Open-developer html title
    |--------------------------------------------------------------------------
    |
    | Html title for all pages.
    |
    */
    'title' => 'Developer',

    /*
    |--------------------------------------------------------------------------
    | Access via `https`
    |--------------------------------------------------------------------------
    |
    | If your page is going to be accessed via https, set it to `true`.
    |
    */
    'https' => env('DEVELOPER_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | Open-developer auth setting
    |--------------------------------------------------------------------------
    |
    | Authentication settings for all developer pages. Include an authentication
    | guard and a user provider setting of authentication driver.
    |
    | You can specify a controller for `login` `logout` and other auth routes.
    |
    */
    'auth' => [

        'controller' => App\Developer\Controllers\AuthController::class,

        'guard' => 'developer',

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

        // Add "remember me" to login form
        'remember' => true,

        // Redirect to the specified URI when user is not authorized.
        'redirect_to' => 'auth/login',

        // Protect agaist brute force attacks
        'throttle_logins'   => true,
        'throttle_attempts' => 5,
        'throttle_timeout'  => 900, // in seconds

        // The URIs that should be excluded from authorization.
        'excepts' => [
            'auth/login',
            'auth/logout',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Open-developer upload setting
    |--------------------------------------------------------------------------
    |
    | File system configuration for form upload files and images, including
    | disk and upload path.
    |
    */
    'upload' => [

        // Disk in `config/filesystem.php`.
        'disk' => 'developer',

        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Open-developer database settings
    |--------------------------------------------------------------------------
    |
    | Here are database settings for open-developer builtin model & tables.
    |
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
    |--------------------------------------------------------------------------
    | User operation log setting
    |--------------------------------------------------------------------------
    |
    | By setting this option to open or close operation log in open-developer.
    |
    */
    'operation_log' => [

        'enable' => true,

        /*
         * Only logging allowed methods in the list
         */
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

        /*
         * Routes that will not log to database.
         *
         * All method to path like: developer/auth/logs
         * or specific method to path like: get:developer/auth/logs.
         */
        'except' => [
            env('DEVELOPER_ROUTE_PREFIX', 'developer').'/auth/logs*',
        ],

        /*
         * Replace input fields that should not be logged
         */
        'filter_input' => [
            'token'             => '*****-filtered-out-*****',
            'password'          => '*****-filtered-out-*****',
            'password_remember' => '*****-filtered-out-*****',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Indicates whether to check route permission.
    |--------------------------------------------------------------------------
    */
    'check_route_permission' => true,

    /*
    |--------------------------------------------------------------------------
    | Indicates whether to check menu roles.
    |--------------------------------------------------------------------------
    */
    'check_menu_roles' => true,

    /*
    |--------------------------------------------------------------------------
    | User default avatar
    |--------------------------------------------------------------------------
    |
    | Set a default avatar for newly created users.
    |
    */
    'default_avatar' => '/vendor/open-developer/open-developer/gfx/user.svg',

    /*
    |--------------------------------------------------------------------------
    | Developer map field provider
    |--------------------------------------------------------------------------
    |
    | Supported: "openstreetmaps", "tencent", "google", "yandex".
    |
    */
    'map_provider' => 'openstreetmaps',

    /*
    |--------------------------------------------------------------------------
    | Application Skin
    |--------------------------------------------------------------------------
    |
    | A custom class to overwrite your developer panel looks.
    | The orginal developerlte theme is not used anymore.
    |
    */
    'skin' => 'your-custom-skin-class',

    /*
    |--------------------------------------------------------------------------
    | Application layout
    |--------------------------------------------------------------------------
    |
    | This value is the layout of developer pages.
    |
    | Supported: "fixed", "layout-boxed", "layout-top-nav", "sidebar-collapse",
    | "sidebar-mini".
    |
    */
    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    /*
    |--------------------------------------------------------------------------
    | Login page background image
    |--------------------------------------------------------------------------
    |
    | This value is used to set the background image of login page.
    |
    */
    'login_background_image' => '',

    /*
    |--------------------------------------------------------------------------
    | Show version at footer
    |--------------------------------------------------------------------------
    |
    | Whether to display the version number of open-developer at the footer of
    | each page
    |
    */
    'show_version' => true,

    /*
    |--------------------------------------------------------------------------
    | Show environment at footer
    |--------------------------------------------------------------------------
    |
    | Whether to display the environment at the footer of each page
    |
    */
    'show_environment' => true,

    /*
    |--------------------------------------------------------------------------
    | Menu bind to permission
    |--------------------------------------------------------------------------
    |
    | whether enable menu bind to a permission
    */
    'menu_bind_permission' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable default breadcrumb
    |--------------------------------------------------------------------------
    |
    | Whether enable default breadcrumb for every page content.
    */
    'enable_default_breadcrumb' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable assets minify
    |--------------------------------------------------------------------------
    */
    'minify_assets' => [

        // Assets will not be minified.
        'excepts' => [

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable sidebar menu search
    |--------------------------------------------------------------------------
    */
    'enable_menu_search' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable user_panel in sidebar
    |--------------------------------------------------------------------------
    */
    'enable_user_panel' => false,

    /*
    |--------------------------------------------------------------------------
    | Alert message that will displayed on top of the page.
    |--------------------------------------------------------------------------
    */
    'top_alert' => '',

    /*
    |--------------------------------------------------------------------------
    | The global Grid action display class. (Actions::class, DropdownActions:class or ContextMenuActions::class)
    |--------------------------------------------------------------------------
    */
    'grid_action_class' => \OpenDeveloper\Developer\Grid\Displayers\Actions\Actions::class,

    /*
    |--------------------------------------------------------------------------
    | Extension Directory
    |--------------------------------------------------------------------------
    |
    | When you use command `php artisan developer:extend` to generate extensions,
    | the extension files will be generated in this directory.
    */
    'extension_dir' => app_path('Developer/Extensions'),

    /*
    |--------------------------------------------------------------------------
    | Settings for extensions.
    |--------------------------------------------------------------------------
    |
    | You can find all available extensions here
    | https://github.com/open-developer-extensions.
    |
    */
    'extensions' => [

    ],
];
