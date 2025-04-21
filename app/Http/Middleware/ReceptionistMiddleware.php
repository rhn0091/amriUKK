<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceptionistMiddleware
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('receptionist')->check()) {
            return redirect()->route('receptionist.auth.index');
        }
        return $next($request);
    }
}
