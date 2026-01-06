<x-layout>
    {{-- Page Title --}}
    <h1 class="title">Register Account</h1>

    {{-- Flash Messages --}}
    @if(session('status'))
        <x-flashMessage msg="{{ session('status') }}" />
    @endif

    @if($errors->any())
        <x-flashMessage msg="{{ $errors->first() }}" bg="bg-red-500" />
    @endif

    {{-- Registration Form Container --}}
    <div class="mx-auto max-w-sm card">
        <form action="{{ route('register') }}" method="POST">
            @csrf

            {{-- Name Field --}}
            <div class="mb-4">
                <label for="name" class="label">Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="input @error('name') ring-red-500 @enderror" 
                    value="{{ old('name') }}"
                >
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Field --}}
            <div class="mb-4">
                <label for="email" class="label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="input @error('email') ring-red-500 @enderror" 
                    value="{{ old('email') }}"
                >
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div class="mb-4">
                <label for="password" class="label">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="input @error('password') ring-red-500 @enderror"
                >
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Confirmation Field --}}
            <div class="mb-4">
                <label for="password_confirmation" class="label">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="input @error('password_confirmation') ring-red-500 @enderror"
                >
                @error('password_confirmation')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Subscribe Checkbox --}}
            <div class="mb-6 flex items-center gap-2">
                <input 
                    type="checkbox" 
                    name="subscribe" 
                    id="subscribe"
                >
                <label for="subscribe" class="text-sm text-gray-700">
                    Subscribe to our newsletter
                </label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="primary-btn">
                Register
            </button>
        </form>

        {{-- Login Link --}}
        <p class="text-center text-sm text-gray-600 mt-6">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                Login here
            </a>
        </p>
    </div>
</x-layout>