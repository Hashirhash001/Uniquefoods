<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // ⚡ MERGE GUEST CART & WISHLIST TO USER ACCOUNT
            CartController::mergeSessionCartToDatabase(Auth::id());
            WishlistController::mergeSessionWishlistToDatabase(Auth::id());

            // Check if AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome back, ' . Auth::user()->name . '!',
                    'redirect' => route('home')
                ]);
            }

            return redirect()->intended(route('home'))
                           ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        // Failed login
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
                'errors' => [
                    'email' => ['The provided credentials do not match our records.']
                ]
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
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

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'is_verified' => false,
        ]);

        Auth::login($user);

        // ⚡ MERGE GUEST CART & WISHLIST TO NEW USER ACCOUNT
        CartController::mergeSessionCartToDatabase($user->id);
        WishlistController::mergeSessionWishlistToDatabase($user->id);

        // Check if AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Welcome to Unique Foods.',
                'redirect' => route('home')
            ]);
        }

        return redirect()->route('home')
                       ->with('success', 'Registration successful! Welcome to Unique Foods.');
    }
}
