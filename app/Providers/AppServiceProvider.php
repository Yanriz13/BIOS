<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Broadcast::routes();

        View::composer('components.header', function ($view) {
            $count = 0;
            if (Auth::check()) {
                $count = Auth::user()->chatNotifications()->where('is_read', false)->count();
            }
            $view->with('chatNotificationsCount', $count);
        });
    }
}
