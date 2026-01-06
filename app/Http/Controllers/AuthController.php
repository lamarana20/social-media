<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUser;
use App\Events\UserSubscribe;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        // Validate registration data
        $fields = $request->validate([
            'name' => ['required', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:255', 'confirmed']
        ]);

        // Create user account
        $user = User::create($fields);

        // Log the user in
        Auth::login($user);

        // Trigger email verification
        event(new Registered($user));

        // Handle newsletter subscription
        if ($request->subscribe) {
            event(new UserSubscribe($user));
        }

        // Send welcome email
        Mail::to($user->email)->send(new WelcomeUser($user));

        // Redirect to dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Display email verification notice
     */
    public function verifyNotice()
    {
        return view('auth.verify-email');
    }

    /**
     * Handle email verification
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        
        return redirect()->route('dashboard');
    }

    /**
     * Resend email verification link
     */
    public function verifyHandler(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        // Validate login credentials
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        // Attempt to authenticate user
        if (Auth::attempt($fields, $request->remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
        }

        // Authentication failed
        return back()->withErrors([
            'failed' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}