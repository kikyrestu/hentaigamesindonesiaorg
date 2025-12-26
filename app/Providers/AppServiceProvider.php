<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ImageKit\ImageKit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use App\Extensions\ImageKitAdapter;

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
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Storage::extend('imagekit', function ($app, $config) {
            $client = new ImageKit(
                $config['public_key'],
                $config['private_key'],
                $config['url_endpoint']
            );

            $adapter = new ImageKitAdapter($client);

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });

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
