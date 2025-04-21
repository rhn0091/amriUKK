<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('admin')->check()) {
            return redirect()->route('admin.auth.index');
        }

        return $next($request);
    }
    
}
