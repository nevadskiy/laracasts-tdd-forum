<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfEmailNotVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->hasVerifiedEmail()) {
            return redirect()->back()->withInput()->with('flash', 'You must first verify email');
        }

        return $next($request);
    }
}

