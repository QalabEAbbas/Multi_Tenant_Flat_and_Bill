<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantIsolation
{
    /**
     * Handle an incoming request.
     * Ensures tenants only see their own flat, bills, and bill categories.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->role === 'tenant') {
            // Attach tenant_flat_id to request for filtering
            $request->attributes->add(['tenant_flat_id' => $user->flat_id]);
        }

        return $next($request);
    }
}
