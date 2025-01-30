<?php

namespace App\Http\Middleware;

use App\Models\Binnacle;
use Closure;

class LogViewAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $key = 'last_view_access_' . $request->path() . '_date_' . now()->format('d-m-Y');
        $previous_url = explode('/', url()->previous());
        $request_came_from_login = end($previous_url) === 'login';
        $current_path = explode('/', $request->path());
        $logout_request = end($current_path) === 'logout';

        $action_message = $logout_request
            ? 'Cerró sesión'
            : ($request_came_from_login ? 'Inició sesión' : 'Ingreso en la vista');

        if (!session()->has($key)) {
            Binnacle::create([
                'module' => substr($request->getPathInfo(), 1),
                'user' => auth()->user()->full_name ?? 'Sistema',
                'rol' => auth()->user()?->getRoleNames() !== null
                    ? auth()->user()?->getRoleNames()[0] ?? 'Sistema'
                    : 'Sistema',
                'action' => $action_message,
                'status' => 'successfull',
            ]);

            session([$key => now()]);
        }

        return $next($request);
    }
}
