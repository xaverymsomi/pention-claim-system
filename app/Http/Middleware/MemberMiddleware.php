<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the authenticated user is a member
        if (auth()->check() && auth()->user()->user_type_id == 2) {
            return $next($request);
        }

        // Return a 403 response if not authorized
        abort(403, 'Unauthorized.');
    }
}
