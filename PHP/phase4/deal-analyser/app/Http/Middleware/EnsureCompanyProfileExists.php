<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyProfileExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user){
            return redirect()->route('login');
        }

        // Allow access to '/', 'login', and 'register' endpoints without company profile
        if (
            !$user->companyProfile &&
            !$request->is('/') &&
            !$request->is('login') &&
            !$request->is('register') &&
            !$request->is('company/create')
        ) {
            return redirect()->route('company.create');
        }
        return $next($request);
    }
}
