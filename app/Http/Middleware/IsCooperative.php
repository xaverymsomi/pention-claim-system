<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCooperative
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // if (auth()->user()->user_type_id != 4) {
        //     abort(403, 'Unauthorized.');
        // }
        if (!auth()->check() || !in_array(auth()->user()->user_type_id, [1,4])) {
            
            return redirect()->route('login');
            
            // abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}
