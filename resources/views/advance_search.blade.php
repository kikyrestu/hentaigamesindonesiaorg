@extends('layouts.app')

@section('title', 'Advance Search - Kimochi Gaming')

@section('content')
    <main class="container mx-auto px-4 py-8 min-h-[60vh] flex flex-col justify-center">
        
        <div class="bg-[#111] p-10 rounded-sm shadow-lg max-w-5xl mx-auto w-full">
            <h1 class="text-4xl font-bold text-white mb-10">Advance Search</h1>

            <form action="{{ route('search') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-8">
                    
                    <!-- Dlsite Tags -->
                    <div>
                        <label class="block text-white font-bold mb-2 text-lg">Dlsite Tags</label>
                        <input type="text" name="q" placeholder="All Tags" class="w-full bg-white text-black px-4 py-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- Site Tags -->
                    <div class="relative">
                        <label class="block text-white font-bold mb-2 text-lg">Site Tags</label>
                        <div class="flex gap-4">
                            <select name="category" class="w-full bg-white text-black px-4 py-2 rounded-sm focus:outline-none appearance-none">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            
                            <!-- Submit Button (positioned next to Site Tags as per screenshot roughly, or I can use absolute) -->
                            <!-- Actually screenshot shows SUBMIT to the right of Site Tags input? No, it looks like a grid. -->
                        </div>
                        <button type="submit" class="absolute right-0 -top-1 bg-[#1a1a1a] text-white px-6 py-1 text-sm font-bold border border-gray-700 hover:bg-gray-800 transition mt-10 md:mt-0 md:static md:ml-4 hidden">SUBMIT</button>
                    </div>
                </div>

                <div class="flex justify-between items-end">
                    <!-- Reset Button -->
                    <button type="reset" class="bg-[#1a1a1a] text-white px-8 py-2 text-sm font-bold border border-gray-700 hover:bg-gray-800 transition rounded-sm">
                        RESET
                    </button>

                    <!-- Submit Button -->
                    <button type="submit" class="bg-[#1a1a1a] text-white px-8 py-2 text-sm font-bold border border-gray-700 hover:bg-gray-800 transition rounded-sm">
                        SUBMIT
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
