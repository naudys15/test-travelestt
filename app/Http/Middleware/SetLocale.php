<?php

namespace Travelestt\Http\Middleware;

use Illuminate\Support\Facades\Config;
use Closure;

class SetLocale
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
        if (in_array($request->segment(1), Config::get('app.available_language'))) {
            app()->setLocale($request->segment(1));
        } else {
            return redirect(app()->getLocale());
        }
        return $next($request);
    }
}