<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        // Force JSON response for API routes
        $request->headers->set('Accept', 'application/json');
        
        return $next($request);
    }
} 