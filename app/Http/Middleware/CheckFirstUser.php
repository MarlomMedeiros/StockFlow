<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hasAdmin = User::where('role', 'admin')->exists();

        if ($hasAdmin) {
            return redirect()->route('login')->with('error', 'Registro desativado. Um administrador já existe.');
        }
        return $next($request);
    }
}
