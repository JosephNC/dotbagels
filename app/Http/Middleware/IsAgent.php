<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle(Request $request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() || ( !$request->user()->isAgent && !$request->user()->isAdmin ) ) {
            return $request->expectsJson()
                ? abort(403, 'You are not authorized to use this route.')
                : redirect(route($redirectToRoute ?: 'dashboard'))->withError('You are not authorized to use this route.');
        }

        return $next($request);
    }
}
