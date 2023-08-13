<?php

namespace OpenDeveloper\Developer\Middleware;

use Closure;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\RateLimiter;
use OpenDeveloper\Developer\Facades\Developer;

class Throttle
{
    protected $loginView = 'developer::login';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // throttle this
        if (Developer::guard()->guest() && config('developer.auth.throttle_logins')) {
            $throttle_attempts = config('developer.auth.throttle_attempts', 5);
            if (RateLimiter::tooManyAttempts('login-tries-'.Developer::guardName(), $throttle_attempts)) {
                $errors = new \Illuminate\Support\MessageBag();
                $errors->add('attempts', $this->getToManyAttemptsMessage());

                return response()->view($this->loginView, ['errors'=>$errors], 429);
            }
        }

        return $next($request);
    }

    protected function getToManyAttemptsMessage()
    {
        return Lang::has('auth.to_many_attempts')
            ? trans('auth.to_many_attempts')
            : 'To many attempts!';
    }
}
