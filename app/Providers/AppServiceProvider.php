<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.app', function ($view) {
            $pendingCount = 0;

            if (auth()->check() && auth()->user()->role === 'katekis') {
                $pendingCount = DB::table('batch_participants')
                    ->where('status', 'pending')
                    ->count();
            }

            $view->with('pendingEnrollmentCount', $pendingCount);
        });
    }
}
