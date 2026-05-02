<?php

namespace App\Http\Middleware;

use App\Facades\Settings;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForMaintenanceMode
{
    /**
     * The URIs that should be excluded from maintenance mode.
     *
     * @var array
     */
    protected $except = [
        'dashboard/login',
        'dashboard/logout',
        'dashboard/maintenance',
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
        // Check if current route should be excluded from maintenance check
        foreach ($this->except as $except) {
            if ($request->is($except) || $request->is($except . '/*')) {
                return $next($request);
            }
        }

        // Get system status from settings (default: active)
        $status = Settings::get('system_status', 'open');

        // If system is in maintenance mode
        if ($status === 'maintenance') {
            // ✅ Allow Super Admins to bypass maintenance
            if (Auth::check() && Auth::user()->isSuperAdmin()) {
                // Add a flash message to notify admin
                if (!session()->has('maintenance_mode_active')) {
                    session()->flash('maintenance_mode_active', true);
                }
                return $next($request);
            }

            // ❌ Show maintenance page to all other users
            return response()->view('errors.maintenance', [
                'message' => Settings::get('maintenance_message', 'System is currently under maintenance. Please check back later.')
            ], 503);
        }

        return $next($request);
    }
}
