@extends('layouts.app')

@section('title', $game->meta_title ?? $game->title . ' - Kimochi Gaming')
@section('meta_description', $game->meta_description ?? Str::limit(strip_tags($game->description), 160))
@section('meta_keywords', $game->meta_keywords ?? '')
@section('og_image', $game->cover_image ? \Illuminate\Support\Facades\Storage::url($game->cover_image) : '')

@section('content')
    <div class="container mx-auto px-4 flex flex-col lg:flex-row gap-8">
        
        <!-- Left Sidebar (Share) -->
        <aside class="hidden lg:flex flex-col w-10 pt-4">
            @if(!empty($siteSettings['social_facebook']) && $siteSettings['social_facebook'] !== '#')
            <a href="{{ $siteSettings['social_facebook'] }}" target="_blank" class="share-btn bg-blue-600"><i class="fa-brands fa-facebook-f"></i></a>
            @endif
            @if(!empty($siteSettings['social_twitter']) && $siteSettings['social_twitter'] !== '#')
            <a href="{{ $siteSettings['social_twitter'] }}" target="_blank" class="share-btn bg-sky-500"><i class="fa-brands fa-twitter"></i></a>
            @endif
            @if(!empty($siteSettings['social_linkedin']) && $siteSettings['social_linkedin'] !== '#')
            <a href="{{ $siteSettings['social_linkedin'] }}" target="_blank" class="share-btn bg-blue-700"><i class="fa-brands fa-linkedin-in"></i></a>
            @endif
            @if(!empty($siteSettings['social_pinterest']) && $siteSettings['social_pinterest'] !== '#')
            <a href="{{ $siteSettings['social_pinterest'] }}" target="_blank" class="share-btn bg-red-600"><i class="fa-brands fa-pinterest"></i></a>
            @endif
            @if(!empty($siteSettings['social_email']) && $siteSettings['social_email'] !== '#')
            <a href="{{ $siteSettings['social_email'] }}" class="share-btn bg-gray-600"><i class="fa-solid fa-envelope"></i></a>
            @endif
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0">
            <div class="content-box p-6 mb-8">
                <!-- Top Tags -->
                <div class="flex flex-wrap gap-1 mb-4">
                    @foreach($game->categories as $category)
                        <a href="{{ route('category', $category->slug) }}" class="tag-pill hover:bg-blue-600 transition-colors">{{ $category->name }}</a>
                    @endforeach
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-white mb-4">{{ $game->title }}</h1>

                <!-- Author Info -->
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                    <img src="https://placehold.co/30/444/fff?text={{ substr($game->author ?? 'A', 0, 1) }}" class="w-6 h-6 rounded-full border border-gray-600">
                    <span class="text-red-400">{{ $game->author ?? 'Admin' }}</span>
                    <span><i class="fa-regular fa-calendar"></i> {{ $game->created_at->diffForHumans() }}</span>
                </div>

                <!-- Thumbnail -->
                <div class="flex justify-center mb-8">
                    <img src="{{ $game->thumbnail_image ? \Illuminate\Support\Facades\Storage::url($game->thumbnail_image) : ($game->cover_image ? \Illuminate\Support\Facades\Storage::url($game->cover_image) : 'https://placehold.co/300x100/333/666?text=' . urlencode($game->title)) }}" alt="Thumbnail" class="rounded shadow-lg max-h-[400px] object-contain">
                </div>

                <!-- Tabs Navigation -->
                <div class="flex gap-6 mb-6 border-b border-gray-800 pb-4">
                    <button onclick="switchTab('info')" id="btn-info" class="tab-btn text-white hover:text-blue-400 transition-colors" title="Game Info">
                        <i class="fa-solid fa-circle-info text-2xl"></i>
                    </button>
                    <button onclick="switchTab('desktop')" id="btn-desktop" class="tab-btn text-gray-500 hover:text-blue-400 transition-colors" title="System Requirements">
                        <i class="fa-solid fa-desktop text-2xl"></i>
                    </button>
                    <button onclick="switchTab('image')" id="btn-image" class="tab-btn text-gray-500 hover:text-blue-400 transition-colors" title="Gallery">
                        <i class="fa-solid fa-image text-2xl"></i>
                    </button>
                    <button onclick="switchTab('download')" id="btn-download" class="tab-btn text-gray-500 hover:text-blue-400 transition-colors" title="Download">
                        <i class="fa-solid fa-download text-2xl"></i>
                    </button>
                    <button onclick="switchTab('question')" id="btn-question" class="tab-btn text-gray-500 hover:text-blue-400 transition-colors" title="Installation Guide">
                        <i class="fa-solid fa-circle-question text-2xl"></i>
                    </button>
                </div>

                <!-- Tab Contents -->
                <div class="mb-8 min-h-[200px]">
                    <!-- Info Tab -->
                    <div id="tab-info" class="tab-content block animate-fade-in">
                        <div class="border-l-4 border-blue-600 pl-4 py-2 text-gray-300 leading-relaxed prose prose-invert max-w-none">
                            {!! $game->description !!}
                        </div>
                    </div>

                    <!-- Desktop Tab (System Reqs) -->
                    <div id="tab-desktop" class="tab-content hidden animate-fade-in">
                        <h3 class="text-xl font-bold text-white mb-4"><i class="fa-solid fa-desktop mr-2"></i> System Requirements</h3>
                        <div class="bg-gray-800/50 p-6 rounded-lg border border-gray-700 text-gray-300 prose prose-invert max-w-none">
                            {!! $game->system_requirements ?? '<p class="text-gray-500 italic">No system requirements specified.</p>' !!}
                        </div>
                    </div>

                    <!-- Image Tab (Gallery) -->
                    <div id="tab-image" class="tab-content hidden animate-fade-in">
                        <h3 class="text-xl font-bold text-white mb-4"><i class="fa-solid fa-images mr-2"></i> Gallery</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @if($game->gallery_images)
                                @foreach($game->gallery_images as $image)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($image) }}" target="_blank" class="block rounded-lg overflow-hidden border border-gray-700 hover:border-blue-500 transition group">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image) }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                @endforeach
                            @else
                                <p class="text-gray-500 italic">No gallery images available.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Download Tab -->
                    <div id="tab-download" class="tab-content hidden animate-fade-in">
                        <h3 class="text-xl font-bold text-white mb-4"><i class="fa-solid fa-download mr-2"></i> Download</h3>
                        <div class="bg-gray-800/50 p-6 rounded-lg border border-gray-700 text-gray-300 space-y-6">
                            @if($game->password)
                            <div class="flex items-center gap-3 bg-gray-900 p-4 rounded border border-gray-600">
                                <span class="text-yellow-500 font-bold text-lg"><i class="fa-solid fa-key"></i> Password:</span>
                                <code class="text-green-400 font-mono text-lg select-all bg-black px-2 py-1 rounded">{{ $game->password }}</code>
                                <span class="text-xs text-gray-500 ml-auto">(Click to select)</span>
                            </div>
                            @endif
                            
                            <div class="prose prose-invert max-w-none">
                                {!! $game->download_content ?? '<p class="text-gray-500 italic">No download information available.</p>' !!}
                            </div>

                            @if($game->download_link)
                            <div class="pt-4 border-t border-gray-700">
                                <a href="{{ $game->download_link }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-lg hover:shadow-blue-500/20">
                                    <i class="fa-solid fa-cloud-arrow-down text-xl"></i> 
                                    <span>Download Main File</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Question Tab (Guide) -->
                    <div id="tab-question" class="tab-content hidden animate-fade-in">
                        <h3 class="text-xl font-bold text-white mb-4"><i class="fa-solid fa-circle-question mr-2"></i> Installation Guide</h3>
                        <div class="bg-gray-800/50 p-6 rounded-lg border border-gray-700 text-gray-300 prose prose-invert max-w-none">
                            {!! $game->installation_guide ?? '<p class="text-gray-500 italic">No installation guide available.</p>' !!}
                        </div>
                    </div>
                </div>

                <script>
                    function switchTab(tabId) {
                        // Hide all contents
                        document.querySelectorAll('.tab-content').forEach(el => {
                            el.classList.add('hidden');
                            el.classList.remove('block');
                        });
                        
                        // Show selected content
                        const selectedTab = document.getElementById('tab-' + tabId);
                        if(selectedTab) {
                            selectedTab.classList.remove('hidden');
                            selectedTab.classList.add('block');
                        }

                        // Reset all buttons
                        document.querySelectorAll('.tab-btn').forEach(el => {
                            el.classList.remove('text-white');
                            el.classList.add('text-gray-500');
                        });

                        // Highlight selected button
                        const selectedBtn = document.getElementById('btn-' + tabId);
                        if(selectedBtn) {
                            selectedBtn.classList.remove('text-gray-500');
                            selectedBtn.classList.add('text-white');
                        }
                    }
                </script>
                <style>
                    @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(5px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    .animate-fade-in {
                        animation: fadeIn 0.3s ease-out forwards;
                    }
                </style>

                <!-- Game Details -->
                <ul class="space-y-2 text-sm mb-8">
                    <li><span class="detail-key">Version:</span> <span class="detail-value">{{ $game->version ?? 'N/A' }}</span></li>
                    <li><span class="detail-key">Censorship:</span> <span class="detail-value">{{ $game->censorship ?? 'N/A' }}</span></li>
                    <li><span class="detail-key">Language:</span> <span class="detail-value">{{ $game->language ?? 'N/A' }}</span></li>
                    <li><span class="detail-key">Developer:</span> <span class="detail-value">{{ $game->developer ?? 'N/A' }}</span></li>
                    <li><span class="detail-key">Release Date:</span> <span class="detail-value">{{ $game->release_date ? $game->release_date->format('d/m/Y') : 'N/A' }}</span></li>
                    <li><span class="detail-key">Platform:</span> <span class="detail-value">{{ $game->platform ?? 'N/A' }}</span></li>
                </ul>

                <div class="text-center mb-8">
                    <p class="text-green-500 font-bold mb-2">Support the software developers. 
                        @if($game->buy_link)
                        <a href="{{ $game->buy_link }}" target="_blank" class="text-yellow-500 hover:underline">BUY IT!</a>
                        @else
                        <span class="text-yellow-500">BUY IT!</span>
                        @endif
                    </p>
                </div>

                <!-- Tag Cloud -->
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($game->categories as $category)
                        <span class="px-3 py-1 bg-gray-800 rounded-full text-xs text-gray-300 border border-gray-700">{{ $category->name }}</span>
                    @endforeach
                </div>

                <!-- Next Link -->
                <div class="flex justify-end">
                    @if($nextGame)
                    <a href="{{ route('detail', $nextGame->slug) }}" class="text-sm text-gray-400 hover:text-white flex items-center gap-2">
                        {{ $nextGame->title }} <i class="fa-solid fa-chevron-right"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- You may also like -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-white mb-4">You may also like</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($relatedGames as $related)
                    <!-- Card -->
                    <div class="relative group overflow-hidden rounded-lg aspect-video cursor-pointer">
                        <a href="{{ route('detail', $related->slug) }}">
                            <img src="{{ $related->cover_image ? \Illuminate\Support\Facades\Storage::url($related->cover_image) : 'https://placehold.co/600x400/333/666?text=' . urlencode($related->title) }}" alt="{{ $related->title }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 card-overlay flex flex-col justify-between p-4">
                                <div class="flex flex-wrap gap-1 content-start">
                                    @foreach($related->categories as $cat)
                                        <span class="tag-pill">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                                <div class="mt-auto">
                                    <h2 class="text-xl font-bold text-white mb-2 drop-shadow-md">{{ $related->title }}</h2>
                                    <div class="flex items-center gap-2 text-xs text-gray-300">
                                        <img src="https://placehold.co/30/444/fff?text={{ substr($related->author ?? 'A', 0, 1) }}" class="w-6 h-6 rounded-full border border-gray-500">
                                        <span>{{ $related->author ?? 'Admin' }}</span>
                                        <span><i class="fa-regular fa-calendar"></i> {{ $related->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <p class="text-gray-500">No related games found.</p>
                    @endforelse
                </div>
            </div>

        </main>

        <!-- Right Sidebar (Topics) -->
        <aside class="w-full lg:w-80">
            <div class="content-box p-4">
                <h3 class="text-xl font-bold mb-4 text-gray-200">Topics</h3>
                <div class="flex flex-col">
                    @foreach($sidebarCategories as $cat)
                    <a href="{{ route('category', $cat->slug) }}" class="topic-item text-gray-400 hover:text-white">
                        <span>{{ $cat->name }}</span> 
                        <span>{{ $cat->games_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Empty box below topics as seen in image -->
            <div class="content-box p-4 mt-4 h-24"></div>
        </aside>
    </div>
@endsection