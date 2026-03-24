<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Mail\OtpMail;
use App\Mail\WelcomeMail;
use App\Models\CustomerGroup;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // ⚡ MERGE GUEST CART & WISHLIST TO USER ACCOUNT
            CartController::mergeSessionCartToDatabase(Auth::id());
            WishlistController::mergeSessionWishlistToDatabase(Auth::id());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Welcome back, ' . Auth::user()->name . '!',
                    'redirect' => route('home'),
                ]);
            }

            return redirect()->intended(route('home'))
                             ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
                'errors'  => [
                    'email' => ['The provided credentials do not match our records.'],
                ],
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'mobile'   => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Ensure OTP was actually verified before reaching this step
        if (! Cache::get('email_verified_' . $request->email)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['email' => ['Email verification has expired. Please restart registration.']],
                ], 422);
            }
            return back()->withErrors(['email' => 'Email verification expired. Please try again.']);
        }

        // Clean up any lingering soft-deleted record for this email
        \App\Models\User::onlyTrashed()->where('email', $request->email)->forceDelete();

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'mobile'            => $request->filled('mobile') ? $request->mobile : null,
            'password'          => Hash::make($request->password),
            'is_verified'       => true,
            'email_verified_at' => now(),
        ]);

        // ── Auto-assign to Home Delivery group ────────────────────────────────
        $homeDelivery = CustomerGroup::where('slug', 'home-delivery')->first();
        if ($homeDelivery) {
            $user->groups()->syncWithoutDetaching([$homeDelivery->id]);
        }

        // Clear OTP verification flag
        Cache::forget('email_verified_' . $request->email);

        Mail::to($user->email)->send(new WelcomeMail($user));

        Auth::login($user);

        CartController::mergeSessionCartToDatabase($user->id);
        WishlistController::mergeSessionWishlistToDatabase($user->id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Account created! Welcome to Unique Foods.',
                'redirect' => route('home'),
            ]);
        }

        return redirect()->route('home')
            ->with('success', 'Welcome to Unique Foods, ' . $user->name . '!');
    }

    // Called after step 1 (email entered)
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
        ]);

        // If a soft-deleted account exists, permanently remove it so the new
        // registration gets a clean slate
        \App\Models\User::onlyTrashed()->where('email', $request->email)->forceDelete();

        $otp = rand(100000, 999999);
        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));
        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
    }

    // Called after step 2 (OTP entered)
    public function verifyOtp(Request $request)
    {
        $request->validate(['email' => 'required|email', 'otp' => 'required|digits:6']);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if (! $cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 422);
        }

        Cache::forget('otp_' . $request->email);

        // Store verified flag for final registration
        Cache::put('email_verified_' . $request->email, true, now()->addMinutes(15));

        return response()->json(['success' => true, 'message' => 'Email verified!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'You have been logged out successfully.');
    }

    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }
}
