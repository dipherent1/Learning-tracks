<?php

use App\Http\Controllers\ReplyController;
use App\Http\Controllers\TicketController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AgentDashboardController; // <-- Add this import


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::resource('/tickets', TicketController::class)->only([
        'index','create','store','show', 'update'
    ]);

    Route::post('/tickets/{ticket}/replies',[ReplyController::class, 'store'])->name('tickets.replies.store');

    Route::get('/agent/dashboard', [AgentDashboardController::class, 'index'])
    ->name('agent.dashboard')
    ->middleware('can:view-agent-dashboard'); // <-- Protect with our Gate

    Route::post('/tickets/{ticket}/summarize', [TicketController::class, 'summarize'])
    ->name('tickets.summarize');
    Route::get('/agent/my-tickets', [AgentDashboardController::class, 'myTickets'])
    ->name('agent.tickets.my')
    ->middleware('can:view-agent-dashboard'); // Reuse the same gate for protection

});
