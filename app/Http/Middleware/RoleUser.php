<?php

namespace Travelestt\Http\Middleware;

use Closure;
use Session;

class RoleUser
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
        if(!Session::has('authenticated') || !Session::has('permissions')) {
            return redirect(app()->getLocale().'/'.__('routes.access_panel'));
        }   
        $permissions = unserialize(Session::get('permissions'));
        if(count($permissions) == 0) {
            return redirect(app()->getLocale());
        }
        $panel = false;
        foreach($permissions as $permission) {
            if ($permission['range'] == 2 || $permission['range'] == 3) {
                $panel = true;
                break;
            }
        }
        if (!$panel) {
            return redirect(app()->getLocale());
        }
        return $next($request);
        
    }
}
