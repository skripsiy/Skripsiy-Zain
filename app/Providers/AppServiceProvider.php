<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Observers\TicketObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind TicketRoutingService sebagai singleton
        $this->app->singleton(\App\Services\TicketRoutingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom Blade components
        \Illuminate\Support\Facades\Blade::component('layouts.agent', 'agent-layout');

        // Register TicketObserver untuk routing otomatis saat tiket dibuat/diupdate
        Ticket::observe(TicketObserver::class);
    }
}
