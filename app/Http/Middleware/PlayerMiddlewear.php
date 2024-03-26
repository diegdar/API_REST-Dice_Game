<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlayerMiddlewear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->hasRole('Player')) {
            return response()->json(['error' => 'Access denied. Only users with the Player role can access this route.'], 403);
        }

        // Verifica si el user_id en la ruta coincide con el usuario autenticado
        if ($request->route('id') != Auth::id()) {
            return response()->json(['error' => 'Access denied. You are not authorized to use this user_id'], 403);
        }

        return $next($request);
    }
}
