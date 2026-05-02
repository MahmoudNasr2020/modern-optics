<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBranchAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Super admin can access all
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Get branch_id from route parameter
        $branchId = $request->route('branch')
            ? $request->route('branch')->id
            : $request->route('branch_id');

        // Check if user can access this branch
        if ($branchId && !$user->canAccessBranch($branchId)) {
            abort(403, 'You do not have access to this branch.');
        }

        return $next($request);
    }
}
