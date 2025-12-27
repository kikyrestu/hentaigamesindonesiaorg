<?php

use App\Models\Game;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/debug-logs', function () {
    $logFile = storage_path('logs/laravel.log');
    
    // Test URL Generation
    $testUrl = \Illuminate\Support\Facades\Storage::disk('imagekit')->url('test-image.jpg');
    $configUrl = config('filesystems.disks.imagekit.url');
    $envUrl = env('IMAGEKIT_URL_ENDPOINT');
    
    $debugInfo = "<h3>Debug Info</h3>";
    $debugInfo .= "Generated URL: " . $testUrl . "<br>";
    $debugInfo .= "Config URL: " . $configUrl . "<br>";
    $debugInfo .= "Env URL: " . $envUrl . "<br>";
    $debugInfo .= "<hr>";

    if (!file_exists($logFile)) {
        return $debugInfo . 'No log file found at ' . $logFile;
    }
    $content = file_get_contents($logFile);
    // Get last 200 lines
    $lines = explode("\n", $content);
    $lastLines = array_slice($lines, -200);
    return $debugInfo . '<pre>' . implode("\n", $lastLines) . '</pre>';
});

Route::get('/', function () {
    $games = Game::with('categories')->latest()->paginate(12);
    return view('home', compact('games'));
})->name('home');

Route::get('/search', function (\Illuminate\Http\Request $request) {
    $query = $request->input('q');
    $categorySlug = $request->input('category');

    $games = Game::query();

    if ($query) {
        $games->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        });
    }

    if ($categorySlug) {
        $games->whereHas('categories', function($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    $games = $games->with('categories')->latest()->paginate(12);
    
    return view('search', compact('games', 'query'));
})->name('search');

Route::get('/advance-search', function () {
    $categories = \App\Models\Category::orderBy('name')->get();
    return view('advance_search', compact('categories'));
})->name('advance_search');

Route::get('/report-dead-link', function () {
    return view('report');
})->name('report');

Route::get('/detail/{slug}', function ($slug) {
    $game = Game::where('slug', $slug)->with('categories')->firstOrFail();
    
    // Related games: same category, excluding current game, take 4
    $relatedGames = Game::whereHas('categories', function ($query) use ($game) {
        $query->whereIn('categories.id', $game->categories->pluck('id'));
    })->where('id', '!=', $game->id)->inRandomOrder()->take(4)->get();

    // Next game (simple logic: next id)
    $nextGame = Game::where('id', '>', $game->id)->first();

    return view('detail', compact('game', 'relatedGames', 'nextGame'));
})->name('detail');

Route::get('/faqs', function () {
    $faqs = \App\Models\Faq::orderBy('sort_order')->get()->groupBy('type');
    return view('faqs', compact('faqs'));
})->name('faqs');

Route::get('/category/{slug}', function ($slug) {
    $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
    $games = $category->games()->with('categories')->latest()->paginate(12);
    return view('category', compact('category', 'games'));
})->name('category');

Route::get('/monitor/database-keep-alive', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return response()->json(['status' => 'ok', 'message' => 'Database connection is active.']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()], 500);
    }
});
