<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function ($schedule) {
        $schedule->command('plans:expire')->daily();
        $schedule->command('listings:expire')->daily();
        $schedule->command('notifications:plan-expiry')->daily();
        $schedule->command('notifications:low-balance')->daily();
        $schedule->command('notifications:listing-expiry')->daily();
        $schedule->command('auctions:process')->everyMinute();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.banned' => \App\Http\Middleware\CheckBannedUser::class,
            'check.listing' => \App\Http\Middleware\CheckUserCanCreateListing::class,
            'maintenance' => \App\Http\Middleware\MaintenanceMode::class,
        ]);
        
        // Add global middleware to web requests
        $middleware->web(append: [
            \App\Http\Middleware\FirewallMiddleware::class,
            \App\Http\Middleware\CheckBannedUser::class,
            \App\Http\Middleware\MaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
