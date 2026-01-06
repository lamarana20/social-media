<x-layout>
    <!-- Flash Messages -->
    @if (session('success'))
        <x-flashMessage msg="{{ session('success') }}" />
    @endif

    @if(session('delete'))
        <x-flashMessage msg="{{ session('delete') }}" bg="bg-red-500" />
    @endif

    @if(session('update'))
        <x-flashMessage msg="{{ session('update') }}" bg="bg-blue-500" />
    @endif

    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Navigation -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('posts.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Posts</span>
            </a>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Main Post Card -->
        <article class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <!-- Post Header -->
            <div class="p-8 border-b border-gray-100">
                <div class="flex items-center gap-4 mb-6">
                    <a href="{{ route('posts.user', $post->user) }}">
                        <img class="w-16 h-16 rounded-full border-2 border-indigo-200" 
                             src="https://picsum.photos/seed/{{ $post->user->id }}/200"
                             alt="{{ $post->user->name }}">
                    </a>
                    <div class="flex-1">
                        <a href="{{ route('posts.user', $post->user) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition">
                            {{ $post->user->name ?? 'Unknown' }}
                        </a>
                        <div class="flex items-center gap-3 text-sm text-gray-500 mt-1">
                            <span class="flex items-center gap-1">
                                <i class="far fa-calendar"></i>
                                {{ $post->created_at->format('M d, Y') }}
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <i class="far fa-clock"></i>
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Author Actions -->
                    @auth
                        @if (auth()->id() === $post->user_id)
                            <div class="flex gap-2">
                                <a href="{{ route('posts.edit', $post) }}"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                    onsubmit="return confirm('Delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Post Title -->
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

                <!-- Post Image -->
                @if ($post->image && Storage::disk('public')->exists($post->image))
                    <div class="mt-6 rounded-xl overflow-hidden cursor-pointer group"
                         onclick="openImageModal('{{ asset('storage/' . $post->image) }}')">
                        <img src="{{ asset('storage/' . $post->image) }}" 
                             alt="{{ $post->title }}"
                             class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                @endif
            </div>

            <!-- Post Content -->
            <div class="p-8">
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($post->body)) !!}
                </div>
            </div>

            <!-- Post Stats & Actions -->
            <div class="border-t border-gray-100">
                <div class="px-8 py-4 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-6 text-sm text-gray-600">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-heart text-red-500"></i>
                            <span class="font-medium">{{ $post->jaimes->count() }} likes</span>
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-comment text-blue-500"></i>
                            <span class="font-medium">{{ $comments->count() }} comments</span>
                        </span>
                    </div>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-comments text-indigo-600"></i>
                    Comments ({{ $comments->count() }})
                </h2>
            </div>

            <!-- Add Comment (Top) -->
            @auth
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <div class="flex gap-3">
                            <img class="w-10 h-10 rounded-full border border-gray-300" 
                                 src="https://picsum.photos/seed/{{ auth()->id() }}/200"
                                 alt="{{ auth()->user()->name }}">
                            
                            <div class="flex-1">
                                <textarea name="content" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"
                                    placeholder="Write a comment..." required>{{ old('content') }}</textarea>
                                
                                @error('content')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                                
                                <div class="flex justify-end mt-2">
                                    <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                                        <i class="far fa-paper-plane"></i>
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-6 bg-gray-50 border-b border-gray-100 text-center">
                    <p class="text-gray-600 mb-3">Please login to comment</p>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                </div>
            @endauth

            <!-- Comments List -->
            <div class="divide-y divide-gray-100">
                @forelse ($comments as $comment)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <img class="w-12 h-12 rounded-full border-2 border-gray-200" 
                                 src="https://picsum.photos/seed/{{ $comment->user->id }}/200"
                                 alt="{{ $comment->user->name }}">
                            
                            <!-- Comment Content -->
                            <div class="flex-1">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-gray-900">
                                            {{ $comment->user->name ?? 'Unknown User' }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <!-- Edit/Delete (Author only) -->
                                    @auth
                                        @if (auth()->id() === $comment->user_id)
                                            <div class="flex gap-2">
                                                <a href="{{ route('comments.edit', $comment) }}"
                                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                                    onsubmit="return confirm('Delete comment?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>

                                <!-- Comment Text -->
                                <p class="text-gray-700 leading-relaxed mb-3">{{ $comment->content }}</p>

                                <!-- Like Button -->
                                <form action="{{ route('comments.like', $comment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                        class="inline-flex items-center gap-2 text-sm transition-colors
                                        {{ auth()->check() && $comment->likes->where('user_id', auth()->id())->count() 
                                            ? 'text-blue-600 font-medium' 
                                            : 'text-gray-600 hover:text-blue-600' }}">
                                        <i class="{{ auth()->check() && $comment->likes->where('user_id', auth()->id())->count() 
                                            ? 'fas' 
                                            : 'far' }} fa-thumbs-up"></i>
                                        <span>{{ $comment->likes->count() }}</span>
                                        @if(auth()->check() && $comment->likes->where('user_id', auth()->id())->count())
                                            <span class="text-xs">• You liked this</span>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-comment-slash text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No comments yet</p>
                        <p class="text-gray-400 text-sm">Be the first to comment!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" 
         class="fixed inset-0 bg-black bg-opacity-95 hidden items-center justify-center z-50"
         onclick="closeImageModal()">
        <button onclick="closeImageModal()" 
                class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition">
            &times;
        </button>
        <img id="modalImageContent" src="" alt="Full size image"
             class="max-w-[95%] max-h-[95vh] object-contain rounded-lg shadow-2xl">
    </div>

    <script>
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImageContent');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeImageModal();
        });
    </script>
</x-layout>