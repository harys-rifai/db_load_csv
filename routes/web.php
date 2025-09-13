<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\ActivityLogDashboard;
use App\Livewire\SystemMetricsGraph;
use App\Livewire\SystemMetricsDashboard;
use App\Livewire\SystemMetricsRam;
use App\Livewire\ServerCloudComponent;
use App\Livewire\UatMetricsTable;
use App\Livewire\UatMetricsGraph;



/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

// Redirect root to login
Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication & Verification)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Server Cloud
    Route::get('/server-cloud', ServerCloudComponent::class)->name('server.cloud.component');

    // Profile & Security
    Route::view('/profile', 'profile')->name('profile');
    Route::view('/security', 'security')->name('security');

    // System Metrics
    Route::prefix('metrics')->group(function () {
        Route::get('/ram', SystemMetricsRam::class)->name('metrics.ram');
        // Uncomment if needed
        // Route::get('/graph', SystemMetricsGraph::class)->name('metrics.graph');
    });

    // Activity Logs
    Route::get('/activity-logs', ActivityLogDashboard::class)->name('activity-logs');
    
// UAT Metrics
Route::get('/uatmetrics', UatMetricsTable::class)->name('uatmetrics.index');
Route::get('/uatmetrics/graph', UatMetricsGraph::class)->name('uatmetrics.graph');

    // Logout (POST only)
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect('/dashboard')->with('error', 'Page not found.');
});
