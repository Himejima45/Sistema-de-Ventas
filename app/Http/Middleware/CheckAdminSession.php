<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckAdminSession
{
  public function handle($request, Closure $next)
  {
    $user = Auth::user();

    if ($user && $user->hasRole('Admin')) {
      $currentSessionId = Session::getId();

      // Check if the stored session_id matches the current session
      if ($user->session_id == null) {
        // Session is no longer valid - destroy it
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