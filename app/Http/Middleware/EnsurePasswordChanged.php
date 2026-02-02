<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->precisaTrocarSenha()) {
            if (!$request->routeIs('admin.trocar-senha*') && !$request->routeIs('logout')) {
                return redirect()->route('admin.trocar-senha')
                    ->with('warning', 'Você precisa trocar sua senha antes de continuar.');
            }
        }

        return $next($request);
    }
}
