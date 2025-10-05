<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // <-- Add this import
use App\Models\User; // <-- Add this import

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('view-agent-dashboard', function (User $user) {
        // Only users with the 'admin' or 'agent' role can pass through this gate.
        return $user->isAdmin() || $user->isAgent();
    });

        //
    }
}
