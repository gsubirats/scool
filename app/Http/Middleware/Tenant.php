<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class Tenant.
 *
 * @package App\Http\Middleware
 */
class Tenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') === 'testing') {
            return $next($request);
        }

        if (! is_null($request->tenant)) {
            if ($tenant = get_tenant($request->tenant)) {
                $tenant->connect();
                $tenant->configure();
            }
        }
        return $next($request);
    }
}
