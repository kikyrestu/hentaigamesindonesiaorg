@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 rounded bg-gray-800 text-gray-500 cursor-not-allowed">
                <i class="fa-solid fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 rounded bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white transition">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-2 text-gray-500">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="px-3 py-2 rounded bg-blue-600 text-white font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 rounded bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white transition">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 rounded bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white transition">
                NEXT
            </a>
        @else
            <span class="px-3 py-2 rounded bg-gray-800 text-gray-500 cursor-not-allowed">
                NEXT
            </span>
        @endif
    </nav>
@endif