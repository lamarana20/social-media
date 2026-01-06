<x-layout>
    {{-- Flash Messages --}}
    @if(session('message'))
        <x-flashMessage msg="{{ session('message') }}" />
    @endif

    @if(session('status'))
        <x-flashMessage msg="{{ session('status') }}" />
    @endif

    <div class="max-w-lg mx-auto text-center p-6 bg-white shadow-lg rounded-lg">
        <h1 class="mb-4 text-2xl font-bold text-gray-800">
             Verify Your Email Address
        </h1>
        
        <p class="mb-4 text-gray-600">
            We've sent you a verification email. Please check your inbox and click the link to confirm your email address.
        </p>
        
        <p class="mb-6 text-gray-600">
            Didn't receive the email? No worries! You can request a new one below. 📬
        </p>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="primary-btn">
                 Resend Verification Email
            </button>
        </form>

        {{-- Logout Option --}}
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-600 mb-3">
                Wrong email address?
            </p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                    Logout and use a different account
                </button>
            </form>
        </div>
    </div>
</x-layout>