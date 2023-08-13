<?php

namespace OpenDeveloper\Developer\Middleware;

use Closure;
use Illuminate\Http\Request;
use OpenDeveloper\Developer\Facades\Developer;

class Bootstrap
{
    public function handle(Request $request, Closure $next)
    {
        Developer::bootstrap();

        return $next($request);
    }
}
