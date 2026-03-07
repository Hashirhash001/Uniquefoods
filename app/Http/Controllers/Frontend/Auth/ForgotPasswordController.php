<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('frontend.auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Block social-only users
        $user = User::where('email', $request->email)->first();
        if ($user->isSocialUser()) {
            return response()->json([
                'success' => false,
                'message' => 'This account uses Google login. Please sign in with Google.'
            ], 422);
        }

        $otp = rand(100000, 999999);
        Cache::put('pwd_otp_' . $request->email, $otp, now()->addMinutes(10));

        Mail::to($request->email)->send(new OtpMail($otp, 'Reset Your Password'));

        return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $cached = Cache::get('pwd_otp_' . $request->email);

        if (!$cached || $cached != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 422);
        }

        Cache::forget('pwd_otp_' . $request->email);
        Cache::put('pwd_verified_' . $request->email, true, now()->addMinutes(15));

        return response()->json(['success' => true]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required|min:6|confirmed',
        ]);

        if (!Cache::get('pwd_verified_' . $request->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please start over.'
            ], 422);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        Cache::forget('pwd_verified_' . $request->email);

        return response()->json([
            'success'  => true,
            'message'  => 'Password reset successfully! Please sign in.',
            'redirect' => route('login')
        ]);
    }
}
