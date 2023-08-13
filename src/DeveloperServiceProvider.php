<?php

namespace OpenDeveloper\Developer;

use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use OpenDeveloper\Developer\Layout\Content;

class DeveloperServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        Console\DeveloperCommand::class,
        Console\MakeCommand::class,
        Console\ControllerCommand::class,
        Console\MenuCommand::class,
        Console\InstallCommand::class,
        Console\PublishCommand::class,
        Console\UninstallCommand::class,
        Console\ImportCommand::class,
        Console\CreateUserCommand::class,
        Console\ResetPasswordCommand::class,
        Console\ExtendCommand::class,
        Console\ExportSeedCommand::class,
        Console\MinifyCommand::class,
        Console\FormCommand::class,
        Console\PermissionCommand::class,
        Console\ActionCommand::class,
        Console\GenerateMenuCommand::class,
        Console\ConfigCommand::class,
        Console\DevLinksCommand::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'developer.auth'       => Middleware\Authenticate::class,
        'developer.throttle'   => Middleware\Throttle::class,
        'developer.pjax'       => Middleware\Pjax::class,
        'developer.log'        => Middleware\LogOperation::class,
        'developer.permission' => Middleware\Permission::class,
        'developer.bootstrap'  => Middleware\Bootstrap::class,
        'developer.session'    => Middleware\Session::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'developer' => [
            'developer.auth',
            'developer.throttle',
            'developer.pjax',
            'developer.log',
            'developer.bootstrap',
            'developer.permission',
            //            'developer.session',
        ],
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'developer');

        $this->ensureHttps();

        if (file_exists($routes = developer_path('routes.php'))) {
            $this->loadRoutesFrom($routes);
        }

        $this->registerPublishing();
        $this->compatibleBlade();
        $this->bladeDirectives();
    }

    /**
     * Force to set https scheme if https enabled.
     *
     * @return void
     */
    protected function ensureHttps()
    {
        if (config('developer.https') || config('developer.secure')) {
            url()->forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'open-developer_-config');
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'open-developer_-lang');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'open-developer-migrations');
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/open-developer')], 'open-developer-assets');
            $this->publishes([__DIR__.'/../resources/assets/test' => public_path('vendor/open-developer-test')], 'open-developer-test');
        }
    }

    /**
     * Remove default feature of double encoding enable in laravel 5.6 or later.
     *
     * @return void
     */
    protected function compatibleBlade()
    {
        $reflectionClass = new \ReflectionClass('\Illuminate\View\Compilers\BladeCompiler');

        if ($reflectionClass->hasMethod('withoutDoubleEncoding')) {
            Blade::withoutDoubleEncoding();
        }
    }

    /**
     * Extends laravel router.
     */
    protected function macroRouter()
    {
        Router::macro('content', function ($uri, $content, $options = []) {
            return $this->match(['GET', 'HEAD'], $uri, function (Content $layout) use ($content, $options) {
                return $layout
                    ->title(Arr::get($options, 'title', ' '))
                    ->description(Arr::get($options, 'desc', ' '))
                    ->body($content);
            });
        });

        Router::macro('component', function ($uri, $component, $data = [], $options = []) {
            return $this->match(['GET', 'HEAD'], $uri, function (Content $layout) use ($component, $data, $options) {
                return $layout
                    ->title(Arr::get($options, 'title', ' '))
                    ->description(Arr::get($options, 'desc', ' '))
                    ->component($component, $data);
            });
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadDeveloperAuthConfig();

        $this->registerRouteMiddleware();

        $this->commands($this->commands);

        $this->macroRouter();
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function loadDeveloperAuthConfig()
    {
        config(Arr::dot(config('developer.auth', []), 'auth.'));
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }

    /**
     * Register the blade box directive.
     *
     * @return void
     */
    public function bladeDirectives()
    {
        Blade::directive('box', function ($title) {
            return "<?php \$box = new \OpenDeveloper\Developer\Widgets\Box({$title}, '";
        });

        Blade::directive('endbox', function ($expression) {
            return "'); echo \$box->render(); ?>";
        });
    }
}
