<x-layout>
    <!-- Header Section -->
    <div class="mb-8">
        <!-- Back Button -->
        <a href="{{ route('posts.index') }}" 
           class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium mb-6 group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Back to Posts</span>
        </a>

        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        Welcome back, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-indigo-100 text-lg">
                        @if($posts->total() > 0)
                            You have created <span class="font-bold text-white">{{ $posts->total() }}</span> 
                            {{ Str::plural('post', $posts->total()) }}
                        @else
                            Start sharing your thoughts with the world
                        @endif
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6 text-center">
                        <div class="text-5xl font-bold">{{ $posts->total() }}</div>
                        <div class="text-sm text-indigo-100 mt-1">Total Posts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Post Section -->
    <x-formePost />

    <!-- Posts Section -->
    <div class="mt-12">
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <i class="fas fa-newspaper text-indigo-600"></i>
                Your Posts
            </h2>
            @if($posts->total() > 0)
                <span class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-full">
                    {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}
                </span>
            @endif
        </div>

        @if($posts->count() > 0)
            <!-- Posts Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-postCard :post="$post">
                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <!-- Edit Button -->
                            <a href="{{ route('posts.edit', $post) }}"
                               class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all text-sm font-medium shadow-sm hover:shadow-md">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </a>
                            
                            <!-- Delete Button -->
                            <button 
                                type="button"
                                onclick="confirmDelete({{ $post->id }})"
                                class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all text-sm font-medium shadow-sm hover:shadow-md">
                                <i class="fas fa-trash-alt"></i>
                                <span>Delete</span>
                            </button>

                            <!-- Hidden Form -->
                            <form action="{{ route('posts.destroy', $post) }}" 
                                  method="POST" 
                                  id="delete-form-{{ $post->id }}"
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </x-postCard>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                <x-pagination :posts="$posts" />
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-newspaper text-4xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">No posts yet</h3>
                    <p class="text-gray-600 mb-6">
                        Start creating amazing content and share it with the community!
                    </p>
                    <button 
                        onclick="document.querySelector('textarea[name=body]').focus()"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all font-medium shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus-circle"></i>
                        Create Your First Post
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- SweetAlert Delete Confirmation -->
    <script>
        function confirmDelete(postId) {
            Swal.fire({
                title: "Delete this post?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#EF4444",
                cancelButtonColor: "#6B7280",
                confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Yes, delete it',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                buttonsStyling: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                },
                customClass: {
                    popup: 'rounded-2xl shadow-2xl',
                    title: 'text-2xl font-bold text-gray-900 mt-4',
                    htmlContainer: 'text-gray-600 my-4',
                    actions: 'gap-3 mt-6',
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg hover:shadow-xl',
                    cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg hover:shadow-xl',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Deleting...',
                        html: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    document.getElementById(`delete-form-${postId}`).submit();
                }
            });
        }
    </script>
</x-layout>