<?php

use App\Models\Game;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

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
