<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    App,
    Session
};
use Symfony\Component\HttpFoundation\Response;

class CurrentLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('locale'))
            App::setLocale(Session::get('locale'));

        return $next($request);
    }
}
