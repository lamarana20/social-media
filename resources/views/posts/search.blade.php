<x-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Search Results
            </h1>
            <p class="text-gray-600">
                @if($posts->count() > 0)
                    Found {{ $posts->total() }} {{ Str::plural('result', $posts->total()) }} for 
                    <span class="font-semibold">"{{ $query }}"</span>
                @else
                    No results found for <span class="font-semibold">"{{ $query }}"</span>
                @endif
            </p>
        </div>

        <!-- Results Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($posts as $post)
                <x-postCard :post="$post" />
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-md p-12 text-center">
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No results found</h3>
                        <p class="text-gray-500 mb-4">Try different keywords or check your spelling</p>
                        <a href="{{ route('posts.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-arrow-left"></i>
                            Back to Posts
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="mt-8">
                {{ $posts->appends(['q' => $query])->links() }}
            </div>
        @endif
    </div>
</x-layout>