<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenancePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (! app()->isDownForMaintenance()) {
            return $next($request);
        }

        // Check if user has valid session
        if (session()->has('maintenance_bypass')) {
            return $next($request);
        }

        // If trying to access maintenance login
        if ($request->is('maintenance/login') || $request->is('maintenance/authenticate')) {
            return $next($request);
        }

        // Redirect to maintenance login
        return redirect()->route('maintenance.login');
    }
}
