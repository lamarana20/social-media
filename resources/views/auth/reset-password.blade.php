{{-- Reset Password Form Layout --}}
<x-layout>
    {{-- Page Title --}}
    <h1 class="title">Reset Your Password</h1>

    {{-- Flash Messages --}}
    @if(session('status'))
        <x-flashMessage msg="{{ session('status') }}" />
    @endif

    @if($errors->any())
        <x-flashMessage msg="{{ $errors->first() }}" bg="bg-red-500" />
    @endif

    {{-- Reset Password Form Container --}}
    <div class="mx-auto max-w-sm card">
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            {{-- Hidden Token Field --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email Field --}}
            <div class="mb-4">
                <label for="email" class="label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="input @error('email') ring-red-500 @enderror" 
                    value="{{ old('email', $email ?? '') }}"
                >
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div class="mb-4">
                <label for="password" class="label">New Password</label>
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
                <label for="password_confirmation" class="label">Confirm New Password</label>
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

            {{-- Submit Button --}}
            <button type="submit" class="primary-btn">
                Reset Password
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