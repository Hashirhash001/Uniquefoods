<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        /** -----------------------------
         *  VALIDATION (AJAX SAFE)
         * ----------------------------- */
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        /** -----------------------------
         *  AUTHENTICATION
         * ----------------------------- */
        if (!Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
            'is_admin' => 1,
        ])) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid admin credentials.',
            ], 401);
        }

        /** -----------------------------
         *  SESSION
         * ----------------------------- */
        $request->session()->regenerate();

        return response()->json([
            'status'   => true,
            'redirect' => route('admin.dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
