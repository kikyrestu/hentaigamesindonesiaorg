@extends('layouts.app')

@section('title', 'FAQs - Kimochi Gaming')

@section('content')
    <div class="container mx-auto px-4 flex flex-col lg:flex-row gap-8">
        
        <!-- Main Content -->
        <main class="flex-1 min-w-0">
            <div class="content-box p-8 mb-8 bg-[#1a1a1a]">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-white mb-6 text-center">FAQs</h1>

                <!-- Info Box -->
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 text-sm">
                    <p class="font-bold">FAQs is short for Frequently Asked Questions, in this case it's for downloading games on this website. Read it if you run into any problems, it may help you, or not.</p>
                </div>

                <!-- Tabs -->
                <div class="flex gap-1 mb-8">
                    @foreach($faqs as $type => $items)
                        <button onclick="switchTab('{{ Str::slug($type) }}')" 
                                id="tab-{{ Str::slug($type) }}"
                                class="tab-btn px-4 py-1 text-sm font-medium rounded-sm {{ $loop->first ? 'bg-blue-500 text-white' : 'bg-[#222] text-gray-400 hover:bg-[#333]' }}">
                            {{ ucfirst($type) }}
                        </button>
                    @endforeach
                </div>

                <!-- Content -->
                <div class="space-y-8 text-sm min-h-[300px]">
                    @foreach($faqs as $type => $items)
                        <div id="content-{{ Str::slug($type) }}" class="tab-content {{ $loop->first ? '' : 'hidden' }}">
                            @foreach($items as $faq)
                                <div class="mb-8 border-b border-gray-800 pb-6 last:border-0">
                                    <h3 class="text-xl font-bold text-red-500 mb-2">{!! $faq->question !!}</h3>
                                    <div class="text-gray-400 mb-4 faq-answer prose prose-invert max-w-none">
                                        {!! $faq->answer !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <script>
                    function switchTab(type) {
                        // Hide all contents
                        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                        // Show selected content
                        document.getElementById('content-' + type).classList.remove('hidden');
                        
                        // Reset all buttons
                        document.querySelectorAll('.tab-btn').forEach(el => {
                            el.classList.remove('bg-blue-500', 'text-white');
                            el.classList.add('bg-[#222]', 'text-gray-400');
                        });
                        
                        // Highlight selected button
                        const btn = document.getElementById('tab-' + type);
                        btn.classList.remove('bg-[#222]', 'text-gray-400');
                        btn.classList.add('bg-blue-500', 'text-white');
                    }
                </script>
            </div>
        </main>

        <!-- Right Sidebar (Topics) -->
        <aside class="w-full lg:w-80 hidden lg:block">
            <div class="content-box p-4">
                <h3 class="text-xl font-bold mb-4 text-gray-200">Topics</h3>
                <div class="flex flex-col">
                    @foreach($sidebarCategories as $cat)
                    <a href="{{ route('category', $cat->slug) }}" class="topic-item text-gray-400 hover:text-white flex justify-between items-center py-1">
                        <span>{{ $cat->name }}</span> 
                        <span class="text-xs bg-[#333] px-2 py-0.5 rounded-full">{{ $cat->games_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
@endsection