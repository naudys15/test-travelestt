<?php

namespace Travelestt\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class SetLanguageApi
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
        if ($request->header('Accept-Language') != null) {
            if (in_array($request->header('Accept-Language'), Config::get('app.available_language'))) {
                app()->setLocale($request->header('Accept-Language'));
            }
        }
        return $next($request);
    }
}
