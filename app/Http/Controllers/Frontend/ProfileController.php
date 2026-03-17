<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use App\Mail\OtpMail;

class ProfileController extends Controller
{
    /**
     * @return \App\Models\User
     */
    private function authUser(): \App\Models\User
    {
        /** @var \App\Models\User */
        return Auth::user();
    }

    public function index()
    {
        $user   = Auth::user();
        $orders = \App\Models\Order::where('user_id', $user->id)
            ->selectRaw('COUNT(*) as total, SUM(total) as spent,
                         SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered')
            ->first();

        return view('frontend.account.profile', compact('user', 'orders'));
    }

    // ── SEND OTP for email change ──────────────────────────────
    public function sendEmailChangeOtp(Request $request)
    {
        $user = $this->authUser();

        try {
            $request->validate([
                'new_email' => 'required|email|max:255|unique:users,email,' . $user->id,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        if ($request->new_email === $user->email) {
            return response()->json([
                'success' => false,
                'message' => 'This is already your current email address.',
            ], 422);
        }

        $otp = rand(100000, 999999);
        $key = 'email_change_otp_' . $user->id;

        Cache::put($key, [
            'otp'       => $otp,
            'new_email' => $request->new_email,
        ], now()->addMinutes(10));

        Mail::to($request->new_email)->send(new OtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to ' . $request->new_email,
        ]);
    }

    // ── VERIFY OTP and update email ────────────────────────────
    public function verifyEmailChangeOtp(Request $request)
    {
        $user = $this->authUser();

        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $key    = 'email_change_otp_' . $user->id;
        $cached = Cache::get($key);

        if (!$cached || $cached['otp'] != $request->otp) {
            return response()->json([
                'success' => false,
                'errors'  => ['otp' => ['Invalid or expired code. Please request a new one.']],
            ], 422);
        }

        Cache::forget($key);

        $user->update([
            'email'             => $cached['new_email'],
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email address updated successfully.',
            'email'   => $user->fresh()->email,
        ]);
    }

    // ── UPDATE profile (name + mobile only, email via OTP) ────
    public function update(Request $request)
    {
        $user = $this->authUser();

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'mobile' => 'nullable|string|max:20|regex:/^[0-9+\s\-()]+$/',
            ], [
                'name.regex' => 'Name can only contain letters and spaces.',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        $user->update([
            'name'   => strip_tags($validated['name']),
            'mobile' => isset($validated['mobile']) ? strip_tags($validated['mobile']) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'name'    => $user->name,
        ]);
    }

    // ── UPDATE password ────────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $user = $this->authUser();

        if (method_exists($user, 'isSocialUser') && $user->isSocialUser()) {
            return response()->json(['success' => false, 'message' => 'Social login accounts cannot set a password here.'], 422);
        }

        try {
            $request->validate([
                'current_password'      => 'required|string',
                'password'              => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
                'password_confirmation' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors'  => ['current_password' => ['Current password is incorrect.']],
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
    }

    // ── AVATAR upload ──────────────────────────────────────────
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = $this->authUser();

        if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete(ltrim($user->avatar, '/storage/'));
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $url  = Storage::url($path);
        $user->update(['avatar' => $url]);

        return response()->json(['success' => true, 'message' => 'Profile photo updated.', 'avatar' => $url]);
    }

    // ── AVATAR remove ──────────────────────────────────────────
    public function removeAvatar()
    {
        $user = $this->authUser();

        if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete(ltrim($user->avatar, '/storage/'));
        }

        $user->update(['avatar' => null]);

        return response()->json(['success' => true, 'message' => 'Profile photo removed.']);
    }

    // ── DELETE account ─────────────────────────────────────────
    public function deleteAccount(Request $request)
    {
        $user     = $this->authUser();
        $isSocial = $user->isSocialUser();

        if (!$isSocial) {
            // Explicitly read from JSON body — safe for DELETE requests
            $password = $request->input('password') ?? $request->json('password');

            if (empty($password)) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['delete_password' => ['Please enter your password to confirm deletion.']],
                ], 422);
            }

            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['delete_password' => ['Incorrect password. Account not deleted.']],
                ], 422);
            }
        }

        // Store id before logout
        $userId = $user->id;

        // Delete related data BEFORE logout (session still valid)
        $user->orders()->delete();
        $user->addresses()->delete();
        $user->wishlistItems()->delete();
        $user->cartItems()->delete();
        $user->delete();

        // Logout AFTER deletion
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success'  => true,
            'message'  => 'Your account has been permanently deleted.',
            'redirect' => route('home'),
        ]);
    }

}
