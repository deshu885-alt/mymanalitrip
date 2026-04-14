<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Add an `is_admin` column to your users table,
        // or check by email for simple single-admin setup:
        $adminEmails = explode(',', env('ADMIN_EMAILS', 'admin@mymanalitrip.com'));

        if (!in_array(auth()->user()->email, $adminEmails)
            && !auth()->user()->is_admin) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
