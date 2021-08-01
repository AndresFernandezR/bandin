<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // info('Hey');
        // dd(\Auth::guard());

        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handles auth:api to avoid redirect to login
     * 
     * @param $request, \Closure $next, $guard = null
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        if (\Auth::guard($guard)->guest()) {
            if ($guard === 'api') {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
        return $next($request);
    }
}
