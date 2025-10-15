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
         if (!$user && !$request->is('/') && !$request->is('login') && !$request->is('register')) {
            return redirect()->route('login');
        }
       
        if (!$request->user()->companyProfile) {
            return redirect()->route('company.create')
                   ->with('error', 'You must create a company profile to continue.');
        }

        

        return $next($request);
    }
}
