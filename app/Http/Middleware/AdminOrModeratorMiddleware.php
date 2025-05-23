<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrModeratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        {
            // السماح فقط للمستخدمين الذين لديهم دور "admin" أو "moderator"
            if (!in_array($request->user()->role, ['admin', 'moderator'])) {
                
                return response()->json(['message' => 'Unauthorized. Admins or Moderators only.'], 403);
            }
    
            return $next($request);
        }    }
}

