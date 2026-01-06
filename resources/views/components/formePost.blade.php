<div class="card mb-4 bg-white shadow-md rounded-lg p-6 sticky">
    <h2 class="font-bold mb-4 text-xl text-gray-800">Create a Post</h2>

    {{-- Flash Messages --}}
    @if(session('success'))
        <x-flashMessage msg="{{ session('success') }}" />
    @endif

    @if(session('delete'))
        <x-flashMessage msg="{{ session('delete') }}" bg="bg-red-500" />
    @endif

    @if(session('update'))
        <x-flashMessage msg="{{ session('update') }}" bg="bg-blue-500" />
    @endif

    @if($errors->any())
        <x-flashMessage msg="{{ $errors->first() }}" bg="bg-red-500" />
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">    
        @csrf

        {{-- Title --}}
        <div class="mb-4">
            <label for="title" class="label">Title</label>
            <input 
                type="text" 
                name="title" 
                id="title" 
                class="input @error('title') ring-red-500 @enderror" 
                value="{{ old('title') }}"
                placeholder="Enter post title"
            >
            @error('title')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Body --}}
        <div class="mb-4">
            <label for="body" class="label">Post Content</label>
            <textarea 
                name="body" 
                id="body" 
                rows="4" 
                class="input @error('body') ring-red-500 @enderror"
                placeholder="What's on your mind?"
            >{{ old('body') }}</textarea>
            @error('body')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image --}}
        <div class="mb-4">
            <label for="image" class="label">Image</label>
            <p class="text-sm text-gray-600 mb-2">
                <i class="fas fa-info-circle text-blue-500"></i>
                Choose an image or a default one will be used
            </p>
            <input 
                type="file" 
                name="image" 
                id="image" 
                accept="image/*"
                class="input @error('image') ring-red-500 @enderror"
            >
            @error('image')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="primary-btn">
            <i class="fas fa-plus-circle mr-2"></i>
            Create Post
        </button>
    </form>
</div>