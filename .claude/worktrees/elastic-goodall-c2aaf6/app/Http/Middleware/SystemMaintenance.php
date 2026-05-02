<?php

namespace App\Http\Middleware;

use App\Facades\Settings;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class SystemMaintenance
{
    public function handle($request, Closure $next)
    {
        $status = Settings::get('system_status');
        if ($status == 'maintenance' &&  (!Auth::check() || Auth::user()->id !== User::first()->id)) {
            return redirect()->route('dashboard.maintenance');
        }

        return $next($request);
    }
}
