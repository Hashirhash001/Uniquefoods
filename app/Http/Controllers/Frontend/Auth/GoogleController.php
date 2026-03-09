<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Google OAuth redirect failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('error', 'Unable to connect to Google. Please try again.');
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Log the incoming request
            Log::info('Google callback started', [
                'has_code' => request()->has('code'),
                'has_state' => request()->has('state'),
                'has_error' => request()->has('error'),
                'query_params' => request()->query()
            ]);

            // Check for errors from Google
            if (request()->has('error')) {
                $errorMsg = request()->get('error');
                Log::error('Google returned error', ['error' => $errorMsg]);
                throw new Exception('Google authentication was declined: ' . $errorMsg);
            }

            // Get user from Google
            $googleUser = Socialite::driver('google')->user();

            Log::info('Google user retrieved successfully', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);

            // Validate email
            if (empty($googleUser->getEmail())) {
                throw new Exception('Google account does not have an email address.');
            }

            DB::beginTransaction();

            // Find or create user
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists - update Google info
                $user->update([
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'is_verified' => true,
                    'email_verified_at' => now(),
                ]);

                Log::info('Existing user logged in via Google', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'is_verified' => true,
                    'password' => null,
                    'email_verified_at' => now(),
                ]);

                // Create cart
                Cart::create(['user_id' => $user->id]);

                // ✅ Send welcome email just like normal registration
                try {
                    Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
                } catch (\Exception $mailException) {
                    Log::warning('Welcome email failed for Google user', [
                        'user_id' => $user->id,
                        'error'   => $mailException->getMessage()
                    ]);
                }

                Log::info('New user created via Google', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }

            DB::commit();

            // Login user
            Auth::login($user, true);

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'auth_check' => Auth::check()
            ]);

            // Merge guest cart
            $this->mergeGuestCart($user);

            return redirect()->route('home')
                ->with('success', 'Welcome back, ' . $user->name . '!');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Google OAuth callback failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('login')
                ->with('error', 'Google login failed. Please try again.');
        }
    }

    /**
     * Merge guest cart
     */
    private function mergeGuestCart($user)
    {
        try {
            $guestCart = session()->get('cart', []);

            if (empty($guestCart)) {
                return;
            }

            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

            foreach ($guestCart as $item) {
                $existingItem = $userCart->items()
                    ->where('product_id', $item['id'])
                    ->first();

                if ($existingItem) {
                    $existingItem->increment('quantity', $item['quantity']);
                } else {
                    $userCart->items()->create([
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'weight' => $item['weight'] ?? null,
                    ]);
                }
            }

            session()->forget('cart');

            Log::info('Guest cart merged successfully', [
                'user_id' => $user->id,
                'items_count' => count($guestCart)
            ]);
        } catch (Exception $e) {
            Log::error('Cart merge failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
