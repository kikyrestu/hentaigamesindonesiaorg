<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $games = Game::latest()->get();
        $categories = Category::all();

        return response()->view('sitemap', [
            'games' => $games,
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }
}
