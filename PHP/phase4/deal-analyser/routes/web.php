<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\DealController;
use App\Http\Middleware\EnsureCompanyProfileExists;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/company/create', [CompanyProfileController::class, 'create'])->name('company.create');
    Route::post('/company', [CompanyProfileController::class, 'store'])->name('company.store');


    Route::middleware(EnsureCompanyProfileExists::class)->group(function () {
        Route::get('/company/{company}', [CompanyProfileController::class, 'show'])->name('company.show');
        Route::get('/company/{company}/edit', [CompanyProfileController::class, 'edit'])->name('company.edit');
        Route::put('/company/{company}', [CompanyProfileController::class, 'update'])->name('company.update');
    });

    Route::middleware(EnsureCompanyProfileExists::class)->group(function () {
        Route::resource('/deals', DealController::class)->only(['show','edit','create','index','store', 'update']);
        Route::post('/deals/ai-analysis', [DealController::class, 'aiAnalysis'])->name('deals.ai-analysis');
    });

});

require __DIR__.'/auth.php';
