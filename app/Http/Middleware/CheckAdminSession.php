<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckAdminSession
{
  public function handle($request, Closure $next)
  {
    $user = Auth::user();

    if ($user && $user->hasRole('Admin')) {
      $key = 'user-ping-' . $user->id;

      if (!Cache::has($key)) {
        $user->update(['session_id' => null]);
        Auth::logout();
        Session::invalidate();

        return redirect('/login')->withErrors([
          'email' => 'Su sesión ha expirado.'
        ]);
      }
    }

    return $next($request);
  }
}