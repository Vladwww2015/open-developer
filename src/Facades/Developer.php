<?php

namespace OpenDeveloper\Developer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Developer.
 *
 * @method static \OpenDeveloper\Developer\Grid                                                     grid($model, \Closure $callable)
 * @method static \OpenDeveloper\Developer\Form                                                     form($model, \Closure $callable)
 * @method static \OpenDeveloper\Developer\Show                                                     show($model, $callable = null)
 * @method static \OpenDeveloper\Developer\Tree                                                     tree($model, \Closure $callable = null)
 * @method static \OpenDeveloper\Developer\Layout\Content                                           content(\Closure $callable = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void             css($css = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void             js($js = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void             headerJs($js = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void             script($script = '')
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void             style($style = '')
 * @method static \Illuminate\Contracts\Auth\Authenticatable|null                           user()
 * @method static \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard guard()
 * @method static string                                                                    title()
 * @method static void                                                                      navbar(\Closure $builder = null)
 * @method static void                                                                      registerAuthRoutes()
 * @method static void                                                                      extend($name, $class)
 * @method static void                                                                      disablePjax()
 * @method static void                                                                      booting(\Closure $builder)
 * @method static void                                                                      booted(\Closure $builder)
 * @method static void                                                                      bootstrap()
 * @method static void                                                                      routes()
 *
 * @see \OpenDeveloper\Developer\Developer
 */
class Developer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OpenDeveloper\Developer\Developer::class;
    }
}