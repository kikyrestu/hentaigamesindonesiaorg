<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', $siteSettings['site_meta_title'] ?? 'Kimochi Gaming')</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['site_meta_description'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $siteSettings['site_meta_keywords'] ?? '')">
    @if(!empty($siteSettings['google_site_verification']))
    <meta name="google-site-verification" content="{{ $siteSettings['google_site_verification'] }}">
    @endif
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', $siteSettings['site_meta_title'] ?? 'Kimochi Gaming')">
    <meta property="og:description" content="@yield('meta_description', $siteSettings['site_meta_description'] ?? '')">
    <meta property="og:image" content="@yield('og_image', !empty($siteSettings['site_logo']) ? asset('storage/' . $siteSettings['site_logo']) : '')">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', $siteSettings['site_meta_title'] ?? 'Kimochi Gaming')">
    <meta property="twitter:description" content="@yield('meta_description', $siteSettings['site_meta_description'] ?? '')">
    <meta property="twitter:image" content="@yield('og_image', !empty($siteSettings['site_logo']) ? asset('storage/' . $siteSettings['site_logo']) : '')">

    @if(!empty($siteSettings['site_favicon']))
        <link rel="icon" href="{{ asset('storage/' . $siteSettings['site_favicon']) }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('storage/' . $siteSettings['site_favicon']) }}" type="image/x-icon">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0f0f0f; /* Very dark background */
            color: #cccccc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .logo-text {
            font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif;
            font-weight: bold;
        }
        .card-overlay {
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.6) 100%);
        }
        .tag {
            font-size: 0.65rem;
            padding: 2px 10px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .tag-pill {
            font-size: 0.65rem;
            padding: 2px 10px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            display: inline-block;
            margin-bottom: 0.25rem;
        }
        .content-box {
            background-color: #111;
            border-radius: 0.25rem;
        }
        .topic-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #222;
            font-size: 0.9rem;
        }
        .topic-item:last-child {
            border-bottom: none;
        }
        .share-btn {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.25rem;
            color: white;
            margin-bottom: 0.5rem;
            transition: opacity 0.2s;
        }
        .share-btn:hover {
            opacity: 0.8;
        }
        .detail-key {
            color: #3b82f6; /* Blue-400 */
            font-weight: 600;
        }
        .detail-value {
            color: #ccc;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="container mx-auto px-4 py-6 flex flex-col items-center border-b border-gray-900 mb-8">
        <!-- Logo -->
        <div class="mb-6 flex items-center gap-2">
            @if(!empty($siteSettings['site_logo']))
                <a href="{{ route('home') }}">
                    <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}" alt="{{ $siteSettings['site_name'] ?? 'Kimochi Gaming' }}" class="h-16 object-contain">
                </a>
            @else
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <i class="fa-solid fa-cat text-3xl text-gray-300"></i>
                    <h1 class="text-3xl logo-text">
                        <span class="text-blue-400">{{ explode(' ', $siteSettings['site_name'] ?? 'Kimochi Gaming')[0] }}</span> 
                        <span class="text-yellow-500">{{ substr(strstr($siteSettings['site_name'] ?? 'Kimochi Gaming', ' '), 1) ?: '' }}</span>
                    </h1>
                </a>
            @endif
        </div>

        <!-- Navigation -->
        <nav class="flex flex-wrap justify-center gap-6 text-sm font-medium text-gray-300">
            @forelse($navigationItems as $item)
                <a href="{{ \Illuminate\Support\Facades\Route::has($item->url) ? route($item->url) : $item->url }}" 
                   class="hover:text-white transition"
                   @if($item->is_external) target="_blank" @endif>
                   {{ $item->label }}
                </a>
            @empty
                <!-- Default Links if no DB items -->
                <a href="{{ route('home') }}" class="hover:text-white transition">Games</a>
                <a href="{{ route('faqs') }}" class="hover:text-white transition">FAQs</a>
                <a href="{{ route('advance_search') }}" class="hover:text-white transition">Advance Search</a>
            @endforelse
            <a href="{{ route('search', ['q' => '']) }}" class="hover:text-white transition"><i class="fa-solid fa-magnifying-glass"></i></a>
        </nav>
    </header>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-[#0a0a0a] py-10 mt-8 border-t border-gray-900">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Contact Us -->
            <div>
                <h3 class="text-lg font-bold text-white mb-4">Contact Us</h3>
                <div class="text-sm text-gray-400 space-y-2">
                    <p><span class="font-bold text-white">Email:</span> <a href="mailto:{{ $siteSettings['contact_email'] ?? '#' }}" class="text-blue-400 hover:underline">{{ $siteSettings['contact_email'] ?? 'admin@kimochi.info' }}</a></p>
                    <p><span class="font-bold text-white">Discord:</span> <a href="{{ $siteSettings['discord_link'] ?? '#' }}" class="text-blue-400 hover:underline">Join Discord</a></p>
                    <p><span class="font-bold text-white">DMCA:</span> <a href="{{ $siteSettings['dmca_link'] ?? '#' }}" class="text-blue-400 hover:underline">Click Here</a></p>
                </div>
                <div class="mt-6 text-xs text-gray-400">
                    <p class="mb-1">Finally, please read <a href="{{ route('faqs') }}" class="text-blue-400">FAQs</a> carefully before asking any problem.</p>
                    <p>Thank you and have a nice day!</p>
                </div>
            </div>

            <!-- HOT Games -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-white">HOT Games</h3>
                    <div class="flex gap-2">
                        <!-- Simple JS slider could be added here, for now just list -->
                    </div>
                </div>
                <div class="space-y-4">
                    @forelse($hotGames as $hotGame)
                    <div class="flex gap-3">
                        <img src="{{ $hotGame->cover_image ? asset('storage/' . $hotGame->cover_image) : 'https://placehold.co/80x50/333/666?text=' . urlencode($hotGame->title) }}" class="rounded object-cover w-20 h-12">
                        <div>
                            <a href="{{ route('detail', $hotGame->slug) }}" class="text-sm text-gray-300 hover:text-white font-medium line-clamp-2">{{ $hotGame->title }}</a>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No hot games yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-12 text-center border-t border-gray-900 pt-8">
            <div class="mb-2 flex justify-center items-center gap-2">
                @if(!empty($siteSettings['site_logo']))
                    <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}" alt="{{ $siteSettings['site_name'] ?? 'Kimochi Gaming' }}" class="h-12 object-contain opacity-80">
                @else
                    <i class="fa-solid fa-cat text-2xl text-gray-500"></i>
                    <h2 class="text-2xl logo-text">
                        <span class="text-blue-400">{{ explode(' ', $siteSettings['site_name'] ?? 'Kimochi Gaming')[0] }}</span> 
                        <span class="text-yellow-500">{{ substr(strstr($siteSettings['site_name'] ?? 'Kimochi Gaming', ' '), 1) ?: '' }}</span>
                    </h2>
                @endif
            </div>
            <p class="text-gray-600 text-xs">{{ $siteSettings['footer_text'] ?? 'Copyright © 2025 Kimochi Gaming' }}</p>
        </div>
    </footer>

</body>
</html>