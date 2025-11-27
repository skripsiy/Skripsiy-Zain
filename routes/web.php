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
    
    // User Management Routes
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.updateRole');
    Route::patch('/users/{user}/status', [App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    
    // Reports Routes
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/user/{user}', [App\Http\Controllers\Admin\ReportController::class, 'getUserTickets'])->name('reports.user');
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

// Notification Routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/notifications/unread', [App\Http\Controllers\NotificationController::class, 'getUnreadNotifications']);
    Route::get('/notifications/count', [App\Http\Controllers\NotificationController::class, 'getCount']);
    Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
