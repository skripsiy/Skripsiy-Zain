<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\ProfileController as AgentProfileController;
use App\Http\Controllers\Agent\TicketController as AgentTicketController;
use App\Http\Controllers\Agent\TicketDetailController as AgentTicketDetailController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\TeamLeader\DashboardController as TeamLeaderDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard routes with role-based access
Route::middleware(['auth'])->group(function () {
    // Agent Dashboard
    Route::get('/agent/dashboard', [AgentDashboardController::class, 'index'])
        ->middleware('role:agent')
        ->name('agent.dashboard');

    // Agent Profile routes
    Route::get('/agent/profile', [AgentProfileController::class, 'show'])
        ->middleware('role:agent')
        ->name('agent.profile');
    Route::patch('/agent/profile', [AgentProfileController::class, 'update'])
        ->middleware('role:agent')
        ->name('agent.profile.update');
    Route::patch('/agent/profile/password', [AgentProfileController::class, 'updatePassword'])
        ->middleware('role:agent')
        ->name('agent.profile.password');
    
    // Agent Ticket routes
    Route::get('/agent/tickets', [AgentTicketController::class, 'index'])
        ->middleware('role:agent')
        ->name('agent.tickets');
    Route::get('/agent/tickets/{id}', [AgentTicketDetailController::class, 'show'])
        ->middleware('role:agent')
        ->name('agent.ticket.detail');
    Route::post('/agent/tickets/{id}/update', [AgentTicketDetailController::class, 'update'])
        ->middleware('role:agent')
        ->name('agent.ticket.update');
    Route::post('/agent/tickets/{id}/status', [AgentTicketDetailController::class, 'updateStatus'])
        ->middleware('role:agent')
        ->name('agent.ticket.status');
    
    // Team Leader Dashboard
    Route::get('/team-leader/dashboard', [TeamLeaderDashboardController::class, 'index'])
        ->middleware('role:team_leader')
        ->name('team-leader.dashboard');
    
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
