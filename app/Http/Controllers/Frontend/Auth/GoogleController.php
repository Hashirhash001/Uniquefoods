<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CustomerGroup;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Google OAuth redirect failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Unable to connect to Google. Please try again.');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            Log::info('Google callback started', [
                'has_code'    => request()->has('code'),
                'has_state'   => request()->has('state'),
                'has_error'   => request()->has('error'),
                'query_params'=> request()->query(),
            ]);

            if (request()->has('error')) {
                $errorMsg = request()->get('error');
                Log::error('Google returned error', ['error' => $errorMsg]);
                throw new Exception('Google authentication was declined: ' . $errorMsg);
            }

            $googleUser = Socialite::driver('google')->user();

            Log::info('Google user retrieved successfully', [
                'google_id' => $googleUser->getId(),
                'email'     => $googleUser->getEmail(),
                'name'      => $googleUser->getName(),
            ]);

            if (empty($googleUser->getEmail())) {
                throw new Exception('Google account does not have an email address.');
            }

            DB::beginTransaction();

            $user = User::withTrashed()->where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Restore if soft-deleted
                if ($user->trashed()) {
                    $user->restore();
                }

                // Update Google info
                $user->update([
                    'provider'          => 'google',
                    'provider_id'       => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'is_verified'       => true,
                    'email_verified_at' => now(),
                ]);

                Log::info('Existing user logged in via Google', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]);

            } else {
                // Brand new user
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'provider'          => 'google',
                    'provider_id'       => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'is_verified'       => true,
                    'password'          => null,
                    'email_verified_at' => now(),
                ]);

                // Create cart
                Cart::firstOrCreate(['user_id' => $user->id]);

                // ── Auto-assign to Home Delivery group ────────────────────────
                $homeDelivery = CustomerGroup::where('slug', 'home-delivery')->first();
                if ($homeDelivery) {
                    $user->groups()->syncWithoutDetaching([$homeDelivery->id]);
                }

                // Send welcome email
                try {
                    Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
                } catch (\Exception $mailException) {
                    Log::warning('Welcome email failed for Google user', [
                        'user_id' => $user->id,
                        'error'   => $mailException->getMessage(),
                    ]);
                }

                Log::info('New user created via Google', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]);
            }

            DB::commit();

            Auth::login($user, true);

            Log::info('User logged in successfully', [
                'user_id'    => $user->id,
                'auth_check' => Auth::check(),
            ]);

            $this->mergeGuestCart($user);

            return redirect()->route('home')
                ->with('success', 'Welcome back, ' . $user->name . '!');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Google OAuth callback failed', [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Google login failed. Please try again.');
        }
    }

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
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                        'weight'     => $item['weight'] ?? null,
                    ]);
                }
            }

            session()->forget('cart');

            Log::info('Guest cart merged successfully', [
                'user_id'     => $user->id,
                'items_count' => count($guestCart),
            ]);
        } catch (Exception $e) {
            Log::error('Cart merge failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
