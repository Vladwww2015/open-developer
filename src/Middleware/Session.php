<?php

namespace OpenDeveloper\Developer\Middleware;

use Illuminate\Http\Request;

class Session
{
    public function handle(Request $request, \Closure $next)
    {
        $path = '/'.trim(config('developer.route.prefix'), '/');

        config(['session.path' => $path]);

        if ($domain = config('developer.route.domain')) {
            config(['session.domain' => $domain]);
        }

        return $next($request);
    }
}
