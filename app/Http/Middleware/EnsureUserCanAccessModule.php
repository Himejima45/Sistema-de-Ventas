<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanAccessModule
{
    protected array $clientOnlyRoutes = ['catalog', 'historial'];
    protected array $employeeOrAdminRoutes = [
        'categories',
        'carts',
        'purchases',
        'products',
        'budgets',
        'pos',
        'providers',
        'clients',
        'currencies',
        'cashout',
        'reports',
        'logs',
        'backups',
        'home'
    ];
    protected array $adminOnlyRoutes = ['roles', 'user'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $path = $request->segment(1);
        $user_role = $user->roles()->first()->reference;

        // Client-only routes: only clients should access
        if (!in_array($path, $this->clientOnlyRoutes) && $user_role === 'client') {
            return redirect('/catalog');
        }

        // Employee or admin routes
        if (!in_array($path, $this->employeeOrAdminRoutes) && $user_role === 'employee') {
            return redirect()->route('home');
        }

        // Admin-only routes
        if (!in_array($path, $this->adminOnlyRoutes) && !in_array($path, $this->employeeOrAdminRoutes) && $user_role === 'admin') {
            return redirect()->route('home');
        }

        // Optional: allow unknown routes (e.g., home, ping, etc.)
        return $next($request);
    }
}