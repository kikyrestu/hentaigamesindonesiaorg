<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

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
    public function boot(): void
    {
        // Only force HTTPS if we are NOT using an IP address (basic check)
        if ($this->app->environment('production') && !preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', request()->getHost())) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Sidebar Categories
        \Illuminate\Support\Facades\View::composer(['detail', 'faqs', 'category'], function ($view) {
            $view->with('sidebarCategories', \App\Models\Category::withCount('games')->orderBy('games_count', 'desc')->get());
        });

        // Global Layout Data (Header/Footer)
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $view->with('navigationItems', \App\Models\NavigationItem::orderBy('sort_order')->get());
            
            // Fetch settings as key-value pair
            $settings = \App\Models\SiteSetting::pluck('value', 'key')->toArray();
            $view->with('siteSettings', $settings);

            // Hot Games
            $view->with('hotGames', \App\Models\Game::where('is_hot', true)->inRandomOrder()->take(5)->get());
        });
    }
}
