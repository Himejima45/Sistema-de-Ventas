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

        if (!session()->has($key)) {
            Binnacle::create([
                'module' => substr($request->getPathInfo(), 1),
                'user' => auth()->user()->full_name ?? 'Sistema',
                'rol' => auth()->user()?->getRoleNames() !== null
                    ? auth()->user()?->getRoleNames()[0] ?? 'Sistema'
                    : 'Sistema',
                'action' => "Ingreso en la vista",
                'status' => 'successfull',
            ]);

            session([$key => now()]);
        }

        return $next($request);
    }
}
