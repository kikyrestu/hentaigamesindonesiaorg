@extends('layouts.app')

@section('title', 'Search Results - Kimochi Gaming')

@section('content')
    <main class="container mx-auto px-4 py-8">
        
        <!-- Search Title -->
        <h2 class="text-2xl font-bold text-gray-200 mb-6">
            @if($query)
                Search Results for "{{ $query }}"
            @else
                Search Results
            @endif
        </h2>

        <!-- Search Form -->
        <div class="mb-8">
            <form action="{{ route('search') }}" method="GET" class="flex gap-2 max-w-md">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search games..." class="flex-1 bg-[#1a1a1a] border border-gray-700 text-white px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Search</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @forelse($games as $game)
            <!-- Card -->
            <div class="relative group overflow-hidden rounded-lg aspect-video cursor-pointer">
                <a href="{{ route('detail', $game->slug) }}">
                    <img src="{{ $game->cover_image ? \Illuminate\Support\Facades\Storage::disk('imagekit')->url($game->cover_image) : 'https://placehold.co/600x400/333/666?text=' . urlencode($game->title) }}" alt="{{ $game->title }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 card-overlay flex flex-col justify-between p-4">
                        <!-- Tags -->
                        <div class="flex flex-wrap gap-1 content-start">
                            @foreach($game->categories as $cat)
                                <span class="tag">{{ $cat->name }}</span>
                            @endforeach
                        </div>
                        
                        <!-- Content -->
                        <div class="mt-auto">
                            <h2 class="text-xl font-bold text-white mb-2 drop-shadow-md">{{ $game->title }}</h2>
                            <div class="flex items-center gap-2 text-xs text-gray-300">
                                <img src="https://placehold.co/30/444/fff?text={{ substr($game->author ?? 'A', 0, 1) }}" class="w-6 h-6 rounded-full border border-gray-500">
                                <span>{{ $game->author ?? 'Admin' }}</span>
                                <span><i class="fa-regular fa-calendar"></i> {{ $game->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-400 py-12">
                <p>No games found matching your search.</p>
            </div>
            @endforelse

        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $games->appends(['q' => $query])->links('vendor.pagination.custom') }}
        </div>
    </main>
@endsection
