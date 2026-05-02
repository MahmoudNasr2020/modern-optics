<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckActiveUser
{
    /**
     * The URIs that should be excluded from active user check.
     *
     * @var array
     */
    protected $except = [
        'dashboard/login',
        'dashboard/logout',
        'dashboard/deactivated',
        'login',
        'logout',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if current route should be excluded from active user check
        foreach ($this->except as $except) {
            if ($request->is($except) || $request->is($except . '/*')) {
                return $next($request);
            }
        }

        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            if (Auth::check() && Auth::user()->isSuperAdmin()) {
                // Add a flash message to notify admin
                return $next($request);
            }

            // Check if user account is deactivated
            if (!$user->is_active) {
                // Log the deactivation attempt for security
                \Log::warning('Deactivated user attempted to access system', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                    'route' => $request->fullUrl(),
                    'timestamp' => now()
                ]);

                // Logout the user
                Auth::logout();

                // Invalidate session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Show deactivated page with user info
                return response()->view('errors.deactivated', [
                    'user' => $user,
                    'message' => 'Your account has been deactivated. Please contact your system administrator for assistance.'
                ], 403);
            }
        }

        return $next($request);
    }
}
