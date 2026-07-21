<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class SubdirectoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Do not modify routes during artisan commands
        if (app()->runningInConsole()) {
            return;
        }

        $subdirectory = config('app.livewire_subdirectory');

        // Root installs do nothing
        if (! $subdirectory) {
            return;
        }

        $route = rtrim($subdirectory, '/') . '/livewire/update';

        Livewire::setUpdateRoute(function ($handle) use ($route) {
            return Route::post($route, $handle);
        });
    }
}