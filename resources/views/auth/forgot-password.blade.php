<x-layout>
    {{-- Page Title --}}
    <h1 class="title">Request Password Reset Email</h1>

    {{-- Flash Messages --}}
    @if(session('status'))
        <x-flashMessage msg="{{ session('status') }}" />
    @endif

    @if($errors->any())
        <x-flashMessage msg="{{ $errors->first() }}" bg="bg-red-500" />
    @endif

    {{-- Password Reset Form Container --}}
    <div class="mx-auto max-w-sm card">
        <form action="{{ route('password.email') }}" method="POST">
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
                    placeholder="Enter your email address"
                >
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="primary-btn">
                Send Reset Link
            </button>
        </form>

        {{-- Back to Login Link --}}
        <p class="text-center text-sm text-gray-600 mt-6">
            Remember your password? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                Back to Login
            </a>
        </p>
    </div>
</x-layout>