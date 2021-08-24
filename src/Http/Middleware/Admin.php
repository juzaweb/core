<?php

namespace Juzaweb\Core\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class Admin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        
        if (!Auth::user()->is_admin) {
            return abort(404);
        }
        
        return $next($request);
    }
}
