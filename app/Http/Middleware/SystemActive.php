<?php

namespace App\Http\Middleware;

use App\Facades\Settings;
use Closure;

class SystemActive
{

    public function handle($request, Closure $next)
    {
        $status = Settings::get('system_status');
        if ($status == 'open') {
            return redirect()->intended(route('dashboard.index'));
        }

        return $next($request);
    }
}
