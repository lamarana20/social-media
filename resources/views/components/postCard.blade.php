@props(['post'])
<div 
    class="bg-white rounded-xl shadow-md hover:shadow-xl w-full mx-auto overflow-hidden transition-all duration-300 border border-gray-100 flex flex-col h-full"
    x-data="{
        liked: {{ auth()->user() && $post->jaimes->contains('user_id', auth()->id()) ? 'true' : 'false' }},
        likesCount: {{ $post->jaimes->count() }},
        loading: false,
        showCommentBox: false,
        commentText: '',
        submitting: false,
        
        async toggleLike() {
            if (this.loading) return;
            this.loading = true;
            
            try {
                const response = await fetch('{{ route('posts.jaimer', $post) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) throw new Error('Failed to toggle like');
                
                const data = await response.json();
                this.likesCount = data.likes_count;
                this.liked = data.liked;
                
                // Reload to update names
                if (this.likesCount > 0) {
                    setTimeout(() => window.location.reload(), 500);
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    timer: 2000
                });
            } finally {
                this.loading = false;
            }
        },
        
        async submitComment() {
            if (!this.commentText.trim()) return;
            
            this.submitting = true;
            
            try {
                const response = await fetch('{{ route('comments.store', $post) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        content: this.commentText
                    })
                });
                
                if (!response.ok) throw new Error('Failed to post comment');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Comment posted!',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                this.commentText = '';
                this.showCommentBox = false;
                
                // Reload page to show new comment
                setTimeout(() => window.location.reload(), 1500);
                
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Failed to post comment!',
                    timer: 2000
                });
            } finally {
                this.submitting = false;
            }
        }
    }"
>
    <!-- Content Area -->
    <div class="p-5 flex-grow flex flex-col">
        <!-- User Info -->
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('posts.user', $post->user->id) }}" class="group">
                <img class="w-12 h-12 rounded-full border-2 border-gray-200 group-hover:border-indigo-400 transition-colors shadow-sm" 
                     src="https://picsum.photos/seed/{{ $post->user->id }}/200"
                     alt="{{ $post->user->name }}">
            </a>
            
            <div class="flex-1">
                <a href="{{ route('posts.user', $post->user->id) }}" 
                   class="block font-semibold text-gray-900 hover:text-indigo-600 transition">
                    {{ $post->user->name ?? 'Anonymous Developer' }}
                </a>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <i class="far fa-clock"></i>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex-grow">
            <p class="text-gray-700 text-base leading-relaxed mb-3">
                {{ Str::words($post->body, 30, '...') }}
            </p>
            
            <a href="{{ route('posts.show', $post) }}" 
               class="text-indigo-600 font-semibold text-sm hover:text-indigo-700 inline-flex items-center gap-1 group">
                Continue reading 
                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="mt-auto">
        <!-- Divider -->
        <div class="border-t border-gray-200"></div>
        
        <!-- Stats Bar (Facebook Style) -->
        <div class="px-5 py-3 bg-gray-50">
            <div class="flex items-center justify-between text-sm">
                <!-- Likes with Names -->
                <div class="flex items-center gap-2">
                    @if($post->jaimes->count() > 0)
                        <div class="flex -space-x-2">
                            <div class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center border-2 border-white">
                                <i class="fas fa-heart text-white text-[10px]"></i>
                            </div>
                        </div>
                        
                        <span class="text-gray-600">
                            @php
                                $likers = $post->jaimes->take(3)->pluck('user.name')->filter();
                                $totalLikes = $post->jaimes->count();
                                $remaining = $totalLikes - $likers->count();
                            @endphp
                            
                            @if($totalLikes === 1)
                                <span class="hover:underline cursor-pointer">{{ $likers->first() }}</span>
                            @elseif($totalLikes === 2)
                                <span class="hover:underline cursor-pointer">{{ $likers->first() }}</span> and 
                                <span class="hover:underline cursor-pointer">{{ $likers->last() }}</span>
                            @elseif($totalLikes === 3)
                                <span class="hover:underline cursor-pointer">{{ $likers[0] }}</span>, 
                                <span class="hover:underline cursor-pointer">{{ $likers[1] }}</span> and 
                                <span class="hover:underline cursor-pointer">{{ $likers[2] }}</span>
                            @else
                                <span class="hover:underline cursor-pointer">{{ $likers->first() }}</span>, 
                                <span class="hover:underline cursor-pointer">{{ $likers->get(1) }}</span> and 
                                <span class="font-medium">{{ $remaining }} {{ $remaining === 1 ? 'other' : 'others' }}</span>
                            @endif
                        </span>
                    @endif
                </div>
                
                <!-- Comments Count -->
                @if($post->comments->count() > 0)
                    <a href="{{ route('posts.show', $post) }}#comments" 
                       class="text-gray-600 hover:underline">
                        {{ $post->comments->count() }} {{ $post->comments->count() === 1 ? 'comment' : 'comments' }}
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="border-t border-gray-200 px-2 py-2 flex items-center justify-between">
            <div class="flex items-center gap-2 flex-1">
                <!-- Like Button -->
                <button 
                    type="button" 
                    @click="toggleLike()"
                    :disabled="loading"
                    class="flex-1 flex items-center justify-center gap-2 py-2 rounded-lg hover:bg-gray-100 transition-all font-medium text-sm"
                    :class="liked ? 'text-red-600' : 'text-gray-600'"
                >
                    <i :class="liked ? 'fas fa-heart' : 'far fa-heart'" 
                       class="transition-transform"
                       :class="{ 'scale-110': liked }"
                       x-show="!loading"></i>
                    <i class="fas fa-spinner fa-spin" 
                       x-show="loading" 
                       x-cloak></i>
                    <span>Like</span>
                </button>
                
                <!-- Comment Button -->
                <button 
                    type="button"
                    @click="showCommentBox = !showCommentBox"
                    class="flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-all font-medium text-sm"
                    :class="{ 'bg-blue-50 text-blue-600': showCommentBox }"
                >
                    <i class="far fa-comment"></i>
                    <span>Comment</span>
                </button>
            </div>
            
            <div class="flex items-center gap-2">
                {{ $slot }}
            </div>
        </div>
        
        <!-- Quick Comment Box -->
        <div 
            x-show="showCommentBox" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="border-t border-gray-200 bg-gray-50 px-4 py-3"
            x-cloak
        >
            @auth
                <div class="flex gap-2">
                    <!-- User Avatar -->
                    <img class="w-8 h-8 rounded-full border border-gray-300" 
                         src="https://picsum.photos/seed/{{ auth()->id() }}/200" 
                         alt="{{ auth()->user()->name }}">
                    
                    <!-- Comment Input -->
                    <div class="flex-1">
                        <textarea 
                            x-model="commentText"
                            placeholder="Write a comment..."
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none text-sm"
                            :disabled="submitting"
                            @keydown.ctrl.enter="submitComment()"
                        ></textarea>
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-2 mt-2">
                            <button 
                                type="button"
                                @click="showCommentBox = false; commentText = ''"
                                class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-200 rounded-lg transition"
                                :disabled="submitting"
                            >
                                Cancel
                            </button>
                            <button 
                                type="button"
                                @click="submitComment()"
                                :disabled="!commentText.trim() || submitting"
                                class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                            >
                                <span x-show="!submitting">Post</span>
                                <span x-show="submitting" class="flex items-center gap-2">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Posting...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-3">
                    <p class="text-gray-600 text-sm mb-2">Please login to comment</p>
                    <a href="{{ route('login') }}" 
                       class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                        Login
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>