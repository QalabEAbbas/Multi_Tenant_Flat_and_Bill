<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantIsolation
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Only apply for house owners and tenants
        if ($user->hasRole('house_owner')) {
            // Set a global scope for models to filter by house_owner_id
            \Illuminate\Database\Eloquent\Builder::macro('tenantScope', function() use ($user) {
                return $this->whereHas('building', function($q) use ($user) {
                    $q->where('house_owner_id', $user->id);
                });
            });
        }

        if ($user->hasRole('tenant')) {
            // Tenant sees only their flat's data
            \Illuminate\Database\Eloquent\Builder::macro('tenantScope', function() use ($user) {
                return $this->where('flat_id', $user->flat_id ?? 0);
            });
        }

        return $next($request);
    }
}

