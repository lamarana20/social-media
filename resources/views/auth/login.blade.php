<x-layout>
    {{-- Page Title --}}
    <h1 class="title">Welcome Back</h1>

    {{-- Flash Messages --}}
    @if(session('status'))
        <x-flashMessage msg="{{ session('status') }}" />
    @endif

    @if($errors->any())
        <x-flashMessage msg="{{ $errors->first() }}" bg="bg-red-500" />
    @endif

    {{-- Login Form Container --}}
    <div class="mx-auto max-w-sm card">
        <form action="{{ route('login') }}" method="POST">
            @csrf

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

            {{-- Remember Me & Forgot Password --}}
            <div class="mb-6 flex justify-between items-center">
                <label for="remember" class="flex items-center gap-2">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember"
                    >
                    <span class="text-sm text-gray-700">Remember Me</span>
                </label>
                <a 
                    href="{{ route('password.request') }}" 
                    class="text-sm text-blue-600 hover:text-blue-700 hover:underline"
                >
                    Forgot Password?
                </a>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="primary-btn">
                Login
            </button>
        </form>

        {{-- Register Link --}}
        <p class="text-center text-sm text-gray-600 mt-6">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                Register here
            </a>
        </p>
    </div>
</x-layout>