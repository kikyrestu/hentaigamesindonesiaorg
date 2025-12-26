@extends('layouts.app')

@section('title', 'Report Dead Link - Kimochi Gaming')

@section('content')
    <main class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="content-box p-8 bg-[#1a1a1a]">
            <h1 class="text-3xl font-bold text-white mb-6 text-center">Report Dead Link</h1>
            
            <div class="bg-yellow-900/30 border-l-4 border-yellow-500 text-yellow-200 p-4 mb-8">
                <p>Please provide the URL of the game page where you found the dead link. We will fix it as soon as possible.</p>
            </div>

            <form action="#" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="game_url" class="block text-sm font-medium text-gray-400 mb-2">Game URL</label>
                    <input type="url" id="game_url" name="game_url" required class="w-full bg-[#222] border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-400 mb-2">Additional Message (Optional)</label>
                    <textarea id="message" name="message" rows="4" class="w-full bg-[#222] border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded transition">
                    Submit Report
                </button>
            </form>
        </div>
    </main>
@endsection
