<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Redirect based on user role
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isTeamLeader()) {
        return redirect()->route('team-leader.dashboard');
    } elseif ($user->isAgent()) {
        return redirect()->route('agent.dashboard');
    }
    
    // Fallback to generic dashboard if role is not recognized
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
});

// Agent Routes
Route::middleware(['auth', 'verified'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets', [App\Http\Controllers\Agent\TicketController::class, 'index'])->name('tickets');
    Route::get('/tickets/{id}', [App\Http\Controllers\Agent\TicketDetailController::class, 'show'])->name('ticket.detail');
    Route::post('/tickets/{id}/update', [App\Http\Controllers\Agent\TicketDetailController::class, 'update'])->name('ticket.update');
    Route::post('/tickets/{id}/status', [App\Http\Controllers\Agent\TicketDetailController::class, 'updateStatus'])->name('ticket.status');
    Route::get('/profile', [App\Http\Controllers\Agent\ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [App\Http\Controllers\Agent\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [App\Http\Controllers\Agent\ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Work Session Routes
    Route::get('/work-session/status', [App\Http\Controllers\Agent\WorkSessionController::class, 'status'])->name('work-session.status');
    Route::post('/work-session/start', [App\Http\Controllers\Agent\WorkSessionController::class, 'start'])->name('work-session.start');
    Route::post('/work-session/end', [App\Http\Controllers\Agent\WorkSessionController::class, 'end'])->name('work-session.end');
});

// Team Leader Routes
Route::middleware(['auth', 'verified'])->prefix('team-leader')->name('team-leader.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\TeamLeader\DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
