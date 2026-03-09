@extends('frontend.layouts.app')

@section('title', 'Register - Unique Foods')

@section('content')

<div class="unique-auth-wrapper">
    <div class="unique-auth-container">

        {{-- Left Side --}}
        <div class="unique-auth-left">
            <div class="unique-auth-branding">
                <div class="unique-auth-logo">
                    <i class="fa-solid fa-leaf"></i>
                    <h1>Unique Foods</h1>
                </div>
                <h2>Join Us Today!</h2>
                <p>Create your account and start enjoying fresh & organic products delivered to your doorstep.</p>
                <div class="unique-auth-features">
                    <div class="unique-feature-item">
                        <i class="fa-regular fa-circle-check"></i>
                        <span>Fresh & Organic Products</span>
                    </div>
                    <div class="unique-feature-item">
                        <i class="fa-regular fa-circle-check"></i>
                        <span>Fast Delivery</span>
                    </div>
                    <div class="unique-feature-item">
                        <i class="fa-regular fa-circle-check"></i>
                        <span>Exclusive Discounts</span>
                    </div>
                    <div class="unique-feature-item">
                        <i class="fa-regular fa-circle-check"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="unique-auth-right">
            <div class="unique-auth-form-wrapper">

                <div class="unique-form-header">
                    <h3>Create Account</h3>
                    <p id="stepSubtitle">Enter your email to get started</p>
                </div>

                <div id="alertContainer"></div>

                {{-- Step Indicator --}}
                <div class="step-indicator">
                    <div class="step active" id="dot1"><span>1</span></div>
                    <div class="step-line" id="line1"></div>
                    <div class="step" id="dot2"><span>2</span></div>
                    <div class="step-line" id="line2"></div>
                    <div class="step" id="dot3"><span>3</span></div>
                </div>

                {{-- ─────────────────────────────────────────
                     STEP 1 — Email
                ───────────────────────────────────────── --}}
                <div id="step1">
                    <a href="{{ route('auth.google') }}" class="unique-btn-google">
                        <svg width="18" height="18" viewBox="0 0 18 18">
                            <path fill="#4285F4" d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 0 0 2.38-5.88c0-.57-.05-.66-.15-1.18z"/>
                            <path fill="#34A853" d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2a4.8 4.8 0 0 1-7.18-2.54H1.83v2.07A8 8 0 0 0 8.98 17z"/>
                            <path fill="#FBBC05" d="M4.5 10.52a4.8 4.8 0 0 1 0-3.04V5.41H1.83a8 8 0 0 0 0 7.18l2.67-2.07z"/>
                            <path fill="#EA4335" d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 0 0 1.83 5.4L4.5 7.49a4.77 4.77 0 0 1 4.48-3.3z"/>
                        </svg>
                        <span>Continue with Google</span>
                    </a>

                    <div class="unique-divider-text"><span>or continue with email</span></div>

                    <div class="unique-form-group">
                        <label class="unique-label">
                            <i class="fa-regular fa-envelope"></i>
                            <span>Email Address</span>
                        </label>
                        <input type="email" id="emailInput" class="unique-input"
                               placeholder="you@example.com" required autofocus>
                        <span class="unique-error-text" id="error-email-step1"></span>
                    </div>

                    <button type="button" class="unique-btn-submit" id="continueBtn">
                        <span>Send Verification Code</span>
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>

                    <div class="unique-form-footer" style="margin-top:20px;">
                        <p>Already have an account? <a href="{{ route('login') }}" class="unique-link-green">Sign In</a></p>
                    </div>
                </div>

                {{-- ─────────────────────────────────────────
                     STEP 2 — OTP Verification
                ───────────────────────────────────────── --}}
                <div id="step2" style="display:none;">

                    <div class="email-pill">
                        <i class="fa-regular fa-envelope"></i>
                        <span id="emailDisplay"></span>
                        <button type="button" id="editEmail">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    </div>

                    <p class="otp-hint">
                        <i class="fa-regular fa-circle-info"></i>
                        We sent a 6-digit code to your email. Enter it below.
                    </p>

                    {{-- OTP Boxes --}}
                    <div class="otp-input-group">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                    </div>
                    <span class="unique-error-text" id="error-otp" style="text-align:center;display:block;margin-bottom:12px;"></span>

                    {{-- Resend Timer --}}
                    <div class="resend-wrapper">
                        <span id="timerText">Resend code in <strong id="countdown">60</strong>s</span>
                        <button type="button" id="resendBtn" style="display:none;">
                            <i class="fa-regular fa-rotate-right"></i> Resend Code
                        </button>
                    </div>

                    <button type="button" class="unique-btn-submit" id="verifyOtpBtn">
                        <span>Verify Email</span>
                        <i class="fa-regular fa-shield-check"></i>
                    </button>
                </div>

                {{-- ─────────────────────────────────────────
                     STEP 3 — Name + Password
                ───────────────────────────────────────── --}}
                <div id="step3" style="display:none;">
                    <form id="registerForm">
                        @csrf
                        <input type="hidden" id="emailHidden" name="email">

                        <div class="email-pill">
                            <i class="fa-regular fa-envelope"></i>
                            <span id="emailDisplay3"></span>
                            <span class="verified-badge">
                                <i class="fa-solid fa-circle-check"></i> Verified
                            </span>
                        </div>

                        <div class="unique-form-group">
                            <label class="unique-label">
                                <i class="fa-regular fa-user"></i>
                                <span>Full Name</span>
                            </label>
                            <input type="text" id="name" name="name"
                                   class="unique-input" placeholder="Your full name" required>
                            <span class="unique-error-text" id="error-name"></span>
                        </div>

                        <div class="unique-form-group">
                            <label class="unique-label">
                                <i class="fa-regular fa-lock"></i>
                                <span>Password</span>
                            </label>
                            <div class="unique-password-wrapper">
                                <input type="password" id="password" name="password"
                                       class="unique-input" placeholder="Create a password (min 6 chars)" required>
                                <button type="button" class="unique-password-toggle" data-target="password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            {{-- Password Strength Bar --}}
                            <div class="strength-bar-wrapper">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                            <span class="strength-label" id="strengthLabel"></span>
                            <span class="unique-error-text" id="error-password"></span>
                        </div>

                        <div class="unique-form-group">
                            <label class="unique-label">
                                <i class="fa-regular fa-lock"></i>
                                <span>Confirm Password</span>
                            </label>
                            <div class="unique-password-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="unique-input" placeholder="Repeat your password" required>
                                <button type="button" class="unique-password-toggle" data-target="password_confirmation">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <span class="unique-error-text" id="error-password_confirmation"></span>
                        </div>

                        <div class="unique-form-group">
                            <label class="unique-checkbox">
                                <input type="checkbox" name="terms" id="terms" required>
                                <span class="checkmark"></span>
                                <span class="label-text">I agree to the <a href="#" class="unique-link-green">Terms & Conditions</a></span>
                            </label>
                        </div>

                        <button type="submit" class="unique-btn-submit" id="registerBtn">
                            <span>Create Account</span>
                            <i class="fa-regular fa-arrow-right"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    :root {
        --unique-green: #08437b;
        --unique-green-dark: #518219;
        --unique-black: #1a1a1a;
        --unique-gray: #666666;
        --unique-light-gray: #f8f9fa;
        --unique-border: #e5e7eb;
        --unique-danger: #ef4444;
        --unique-success: #10b981;
    }

    .unique-auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 40px 20px;
    }

    .unique-auth-container {
        max-width: 1100px;
        width: 100%;
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        display: grid;
        grid-template-columns: 45% 55%;
    }

    /* Left */
    .unique-auth-left {
        background: linear-gradient(135deg, var(--unique-green) 0%, var(--unique-green-dark) 100%);
        padding: 60px 50px;
        color: white;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .unique-auth-left::before {
        content: '';
        position: absolute;
        top: -50%; right: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }
    @keyframes rotate { 100% { transform: rotate(360deg); } }

    .unique-auth-branding { position: relative; z-index: 2; }
    .unique-auth-logo { display: flex; align-items: center; gap: 16px; margin-bottom: 32px; }
    .unique-auth-logo i { font-size: 48px; color: white; }
    .unique-auth-logo h1 { font-size: 32px; font-weight: 700; margin: 0; color: white; }
    .unique-auth-branding h2 { font-size: 36px; font-weight: 700; margin-bottom: 16px; color: white; }
    .unique-auth-branding > p { font-size: 16px; line-height: 1.6; opacity: 0.9; margin-bottom: 40px; }
    .unique-auth-features { display: grid; gap: 16px; }
    .unique-feature-item { display: flex; align-items: center; gap: 12px; font-size: 15px; }
    .unique-feature-item i { font-size: 20px; color: rgba(255,255,255,0.9); }

    /* Right */
    .unique-auth-right { padding: 60px 50px; display: flex; align-items: center; }
    .unique-auth-form-wrapper { width: 100%; }
    .unique-form-header { margin-bottom: 24px; }
    .unique-form-header h3 { font-size: 28px; font-weight: 700; color: var(--unique-black); margin-bottom: 8px; }
    .unique-form-header p { font-size: 15px; color: var(--unique-gray); margin: 0; }

    /* Step Indicator */
    .step-indicator { display: flex; align-items: center; margin-bottom: 32px; }
    .step {
        width: 36px; height: 36px; border-radius: 50%;
        border: 2px solid var(--unique-border);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; color: var(--unique-gray);
        transition: all 0.3s; flex-shrink: 0;
    }
    .step.active { background: var(--unique-green); border-color: var(--unique-green); color: white; }
    .step.completed { background: var(--unique-success); border-color: var(--unique-success); color: white; }
    .step-line { flex: 1; height: 2px; background: var(--unique-border); margin: 0 8px; transition: background 0.4s; }
    .step-line.active { background: var(--unique-success); }

    /* Alerts */
    .unique-alert { display: flex; gap: 12px; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; }
    .unique-alert i { font-size: 20px; flex-shrink: 0; }
    .unique-alert p { margin: 0; line-height: 1.5; }
    .unique-alert-danger { background: #fef2f2; color: var(--unique-danger); border: 1px solid #fecaca; }
    .unique-alert-success { background: #f0fdf4; color: var(--unique-success); border: 1px solid #bbf7d0; }

    /* Google Button */
    .unique-btn-google {
        display: flex; align-items: center; justify-content: center; gap: 12px;
        width: 100%; padding: 14px 24px; background: white;
        border: 2px solid var(--unique-border); border-radius: 12px;
        font-size: 15px; font-weight: 600; color: var(--unique-black);
        text-decoration: none; transition: all 0.3s; margin-bottom: 24px;
    }
    .unique-btn-google:hover { background: var(--unique-light-gray); border-color: #d1d5db; }

    /* Divider */
    .unique-divider-text { position: relative; text-align: center; margin: 24px 0; }
    .unique-divider-text::before, .unique-divider-text::after {
        content: ''; position: absolute; top: 50%; width: 40%; height: 1px; background: var(--unique-border);
    }
    .unique-divider-text::before { left: 0; }
    .unique-divider-text::after { right: 0; }
    .unique-divider-text span { background: white; padding: 0 16px; font-size: 14px; color: var(--unique-gray); }

    /* Form */
    .unique-form-group { margin-bottom: 20px; }
    .unique-label { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: var(--unique-black); margin-bottom: 8px; }
    .unique-label i { color: var(--unique-green); }
    .unique-input {
        width: 100% !important; padding: 14px 16px !important;
        border: 2px solid var(--unique-border) !important;
        border-radius: 12px !important; font-size: 15px !important;
        transition: all 0.3s !important; outline: none !important;
    }
    .unique-input:focus { border-color: var(--unique-green) !important; box-shadow: 0 0 0 4px rgba(8,67,123,0.1) !important; }
    .unique-input-error { border-color: var(--unique-danger) !important; }
    .unique-error-text { display: block; font-size: 13px; color: var(--unique-danger); margin-top: 6px; }

    /* Password Toggle */
    .unique-password-wrapper { position: relative; }
    .unique-password-toggle {
        position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: var(--unique-gray);
        cursor: pointer; padding: 8px; font-size: 16px; width: unset;
    }

    /* Submit Button */
    .unique-btn-submit {
        width: 100%; padding: 16px 24px; background: var(--unique-green);
        color: white; border: none; border-radius: 12px; font-size: 16px;
        font-weight: 600; cursor: pointer; display: flex; align-items: center;
        justify-content: center; gap: 12px; transition: all 0.3s; position: relative;overflow: hidden; min-height: 54px;
    }
    .unique-btn-submit:hover { background: var(--unique-green-dark); box-shadow: 0 8px 20px rgba(8,67,123,0.3); }
    .unique-btn-submit.loading { pointer-events: none; opacity: 0.7; }
    /* Hide all children when loading */
    .unique-btn-submit.loading > * {
        visibility: hidden;   /* Hides content but keeps space — no layout jump */
    }
    .unique-btn-submit.loading span { opacity: 0; }
    /* Centered spinner */
    .unique-btn-submit.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);   /* ✅ Perfect center every time */
        width: 22px;
        height: 22px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin { to { transform: translate(-50%, -50%) rotate(360deg); } }


    /* Email Pill */
    .email-pill {
        display: flex; align-items: center; gap: 10px;
        background: var(--unique-light-gray); border: 1px solid var(--unique-border);
        border-radius: 12px; padding: 12px 16px; margin-bottom: 20px;
        font-size: 14px; color: var(--unique-black); font-weight: 500;
    }
    .email-pill i:first-child { color: var(--unique-green); }
    .email-pill span { flex: 1; }
    .email-pill button { background: none; border: none; color: var(--unique-gray); cursor: pointer; padding: 0; font-size: 15px; width: unset; }
    .email-pill button:hover { color: var(--unique-green); }

    .verified-badge { font-size: 13px; color: var(--unique-success); font-weight: 600; display: flex; align-items: center; gap: 4px; }

    /* OTP Hint */
    .otp-hint { font-size: 14px; color: var(--unique-gray); margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
    .otp-hint i { color: var(--unique-green); }

    /* OTP Input Boxes */
    .otp-input-group { display: flex; gap: 10px; justify-content: center; margin-bottom: 12px; }
    .otp-box {
        width: 52px !important; height: 56px; text-align: center;
        font-size: 22px; font-weight: 700; border: 2px solid var(--unique-border) !important;
        border-radius: 12px !important; outline: none !important;
        transition: all 0.2s !important; padding: 0 !important;
        color: var(--unique-black);
    }
    .otp-box:focus { border-color: var(--unique-green) !important; box-shadow: 0 0 0 4px rgba(8,67,123,0.1) !important; }
    .otp-box.filled { border-color: var(--unique-green) !important; background: rgba(8,67,123,0.04); }
    .otp-box.error { border-color: var(--unique-danger) !important; background: #fef2f2; }

    /* Resend */
    .resend-wrapper { text-align: center; margin-bottom: 20px; font-size: 14px; color: var(--unique-gray); }
    #resendBtn { background: none; border: none; color: var(--unique-green); font-size: 14px; font-weight: 600; cursor: pointer; padding: 0; width: unset; }
    #resendBtn:hover { text-decoration: underline; }

    /* Password Strength */
    .strength-bar-wrapper { height: 4px; background: var(--unique-border); border-radius: 4px; margin-top: 8px; overflow: hidden; }
    .strength-bar { height: 100%; width: 0%; border-radius: 4px; transition: all 0.3s; }
    .strength-label { font-size: 12px; margin-top: 4px; font-weight: 600; display: block; }

    /* Checkbox */
    .unique-checkbox { display: flex; align-items: center; gap: 8px; cursor: pointer; position: relative; }
    .unique-checkbox input[type="checkbox"] { position: absolute; opacity: 0; cursor: pointer; }
    .checkmark { width: 20px; height: 20px; border: 2px solid var(--unique-border); border-radius: 6px; transition: all 0.3s; flex-shrink: 0; }
    .unique-checkbox input[type="checkbox"]:checked ~ .checkmark { background: var(--unique-green); border-color: var(--unique-green); }
    .label-text { font-size: 14px; color: var(--unique-gray); }

    /* Footer */
    .unique-form-footer { text-align: center; }
    .unique-form-footer p { font-size: 14px; color: var(--unique-gray); margin: 0; }
    .unique-link-green { color: var(--unique-green); font-weight: 600; text-decoration: none; }
    .unique-link-green:hover { text-decoration: underline; }

    /* ===== REGISTER MOBILE RESPONSIVE ===== */
    @media (max-width: 991px) {
        .unique-auth-container {
            grid-template-columns: 1fr !important;
            border-radius: 16px !important;
            max-width: 480px !important;
        }

        .unique-auth-left {
            display: none !important;
        }

        .unique-auth-right {
            padding: 40px 36px !important;
        }
    }

    @media (max-width: 767px) {

        .unique-auth-wrapper {
            padding: 16px !important;
            padding-top: 24px !important;
            padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px)) !important;
            align-items: flex-start !important;
        }

        .unique-auth-container {
            border-radius: 16px !important;
            max-width: 100% !important;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08) !important;
        }

        .unique-auth-right {
            padding: 28px 20px !important;
        }

        /* Header */
        .unique-form-header {
            margin-bottom: 16px !important;
            text-align: center !important;
        }

        .unique-form-header h3 {
            font-size: 22px !important;
            margin-bottom: 6px !important;
        }

        .unique-form-header p {
            font-size: 13px !important;
        }

        /* Step indicator */
        .step-indicator {
            margin-bottom: 24px !important;
        }

        .step {
            width: 30px !important;
            height: 30px !important;
            font-size: 12px !important;
        }

        .step-line {
            margin: 0 5px !important;
        }

        /* Alerts */
        .unique-alert {
            padding: 12px 14px !important;
            font-size: 13px !important;
            border-radius: 10px !important;
            margin-bottom: 16px !important;
        }

        .unique-alert i {
            font-size: 16px !important;
        }

        /* Google button */
        .unique-btn-google {
            padding: 12px 16px !important;
            font-size: 14px !important;
            border-radius: 10px !important;
            margin-bottom: 16px !important;
        }

        /* Divider */
        .unique-divider-text {
            margin: 16px 0 !important;
        }

        .unique-divider-text span {
            font-size: 12px !important;
        }

        /* Form inputs */
        .unique-form-group {
            margin-bottom: 14px !important;
        }

        .unique-label {
            font-size: 13px !important;
            margin-bottom: 6px !important;
        }

        .unique-input {
            padding: 12px 14px !important;
            font-size: 16px !important; /* prevents iOS zoom */
            border-radius: 10px !important;
        }

        .unique-error-text {
            font-size: 12px !important;
        }

        /* Submit button */
        .unique-btn-submit {
            padding: 14px 20px !important;
            font-size: 15px !important;
            border-radius: 10px !important;
            min-height: 48px !important;
        }

        .unique-btn-submit:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        /* Email pill */
        .email-pill {
            padding: 10px 12px !important;
            font-size: 13px !important;
            border-radius: 10px !important;
            margin-bottom: 14px !important;
            gap: 8px !important;
        }

        /* OTP hint */
        .otp-hint {
            font-size: 12px !important;
            margin-bottom: 16px !important;
        }

        /* OTP boxes — fit 6 boxes in a row on all phones */
        .otp-input-group {
            gap: 6px !important;
            margin-bottom: 10px !important;
        }

        .otp-box {
            width: 42px !important;
            height: 48px !important;
            font-size: 18px !important;
            border-radius: 8px !important;
        }

        /* Resend */
        .resend-wrapper {
            font-size: 13px !important;
            margin-bottom: 16px !important;
        }

        #resendBtn {
            font-size: 13px !important;
        }

        /* Password strength */
        .strength-label {
            font-size: 11px !important;
        }

        /* Checkbox */
        .label-text {
            font-size: 13px !important;
        }

        /* Footer */
        .unique-form-footer p {
            font-size: 13px !important;
        }

        /* Verified badge */
        .verified-badge {
            font-size: 12px !important;
        }
    }

    @media (max-width: 360px) {

        /* Very small phones — shrink OTP boxes further */
        .otp-box {
            width: 36px !important;
            height: 42px !important;
            font-size: 16px !important;
            border-radius: 6px !important;
        }

        .otp-input-group {
            gap: 4px !important;
        }

        .unique-auth-right {
            padding: 24px 14px !important;
        }

        .unique-form-header h3 {
            font-size: 19px !important;
        }
    }

</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {

    let currentEmail = '';
    let countdownTimer = null;

    // ── Helpers ───────────────────────────────────────────────
    function showAlert(type, message) {
        const cls  = type === 'success' ? 'unique-alert-success' : 'unique-alert-danger';
        const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
        $('#alertContainer').html(`
            <div class="unique-alert ${cls}">
                <i class="fa-regular ${icon}"></i>
                <p>${message}</p>
            </div>
        `);
    }

    function goToStep(from, to) {
        $(`#step${from}`).fadeOut(200, function () {
            $(`#step${to}`).fadeIn(200);
        });
    }

    function markCompleted(dotId, lineId) {
        $(`#${dotId}`).removeClass('active').addClass('completed')
            .html('<i class="fa-solid fa-check" style="font-size:12px"></i>');
        if (lineId) $(`#${lineId}`).addClass('active');
    }

    function startCountdown() {
        let seconds = 60;
        $('#timerText').show();
        $('#resendBtn').hide();
        $('#countdown').text(seconds);

        clearInterval(countdownTimer);
        countdownTimer = setInterval(function () {
            seconds--;
            $('#countdown').text(seconds);
            if (seconds <= 0) {
                clearInterval(countdownTimer);
                $('#timerText').hide();
                $('#resendBtn').show();
            }
        }, 1000);
    }

    // ── Password Toggle ───────────────────────────────────────
    $(document).on('click', '.unique-password-toggle', function () {
        const id = $(this).data('target');
        const $input = $(`#${id}`);
        $input.attr('type', $input.attr('type') === 'password' ? 'text' : 'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // ── Password Strength ─────────────────────────────────────
    $('#password').on('input', function () {
        const val = $(this).val();
        let strength = 0;
        if (val.length >= 6)  strength++;
        if (val.length >= 10) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;

        const levels = [
            { width: '0%',   color: '',                  label: '',          labelColor: '' },
            { width: '25%',  color: '#ef4444',           label: 'Weak',      labelColor: '#ef4444' },
            { width: '50%',  color: '#f97316',           label: 'Fair',      labelColor: '#f97316' },
            { width: '75%',  color: '#eab308',           label: 'Good',      labelColor: '#eab308' },
            { width: '90%',  color: '#10b981',           label: 'Strong',    labelColor: '#10b981' },
            { width: '100%', color: '#059669',           label: 'Very Strong', labelColor: '#059669' },
        ];

        const l = levels[Math.min(strength, 5)];
        $('#strengthBar').css({ width: l.width, background: l.color });
        $('#strengthLabel').text(l.label).css('color', l.labelColor);
    });

    // ── OTP Box Behaviour ─────────────────────────────────────
    $(document).on('input', '.otp-box', function () {
        const val = $(this).val().replace(/\D/g, '');
        $(this).val(val);
        $(this).toggleClass('filled', val !== '');

        // Auto-advance
        if (val && $(this).next('.otp-box').length) {
            $(this).next('.otp-box').focus();
        }
    });

    $(document).on('keydown', '.otp-box', function (e) {
        if (e.key === 'Backspace' && !$(this).val() && $(this).prev('.otp-box').length) {
            $(this).prev('.otp-box').focus().val('').removeClass('filled');
        }
        // Allow paste
        if (e.key === 'v' && (e.ctrlKey || e.metaKey)) return;
    });

    // Handle paste into first OTP box
    $(document).on('paste', '.otp-box', function (e) {
        e.preventDefault();
        const pasted = (e.originalEvent.clipboardData || window.clipboardData)
            .getData('text').replace(/\D/g, '').slice(0, 6);
        $('.otp-box').each(function (i) {
            $(this).val(pasted[i] || '').toggleClass('filled', !!pasted[i]);
        });
        $('.otp-box').last().focus();
    });

    function getOtpValue() {
        return $('.otp-box').map(function () { return $(this).val(); }).get().join('');
    }

    // ── STEP 1: Send OTP ──────────────────────────────────────
    $('#continueBtn').on('click', function () {
        const email = $('#emailInput').val().trim();
        $('#error-email-step1').text('');
        $('#emailInput').removeClass('unique-input-error');
        $('#alertContainer').empty();

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#emailInput').addClass('unique-input-error');
            $('#error-email-step1').text('Please enter a valid email address.');
            return;
        }

        const $btn = $(this);
        $btn.addClass('loading').prop('disabled', true);

        $.ajax({
            url: '{{ route("register.send-otp") }}',
            method: 'POST',
            data: { email: email, _token: '{{ csrf_token() }}' },
            success: function () {
                currentEmail = email;
                $('#emailDisplay').text(email);

                markCompleted('dot1', 'line1');
                $('#dot2').addClass('active');
                $('#stepSubtitle').text('Check your email for the code');

                goToStep(1, 2);
                startCountdown();
                $('.otp-box').first().focus();
            },
            error: function (xhr) {
                $btn.removeClass('loading').prop('disabled', false);
                const msg = xhr.responseJSON?.errors?.email?.[0]
                    || xhr.responseJSON?.message
                    || 'Failed to send OTP. Please try again.';
                $('#emailInput').addClass('unique-input-error');
                $('#error-email-step1').text(msg);
            }
        }).always(function () {
            $btn.removeClass('loading').prop('disabled', false);
        });
    });

    // ── Edit Email (back to step 1) ───────────────────────────
    $('#editEmail').on('click', function () {
        clearInterval(countdownTimer);
        $('.otp-box').val('').removeClass('filled error');
        $('#error-otp').text('');
        $('#alertContainer').empty();

        goToStep(2, 1);
        $('#dot1').removeClass('completed').addClass('active').html('<span>1</span>');
        $('#dot2').removeClass('active');
        $('#line1').removeClass('active');
        $('#stepSubtitle').text('Enter your email to get started');
    });

    // ── Resend OTP ────────────────────────────────────────────
    $('#resendBtn').on('click', function () {
        $('.otp-box').val('').removeClass('filled error');
        $('#error-otp').text('');

        $.post('{{ route("register.send-otp") }}', {
            email: currentEmail,
            _token: '{{ csrf_token() }}'
        }, function () {
            startCountdown();
            showAlert('success', 'New code sent! Check your inbox.');
            $('.otp-box').first().focus();
        }).fail(function () {
            showAlert('danger', 'Failed to resend. Please try again.');
        });
    });

    // ── STEP 2: Verify OTP ────────────────────────────────────
    $('#verifyOtpBtn').on('click', function () {
        const otp = getOtpValue();
        $('.otp-box').removeClass('error');
        $('#error-otp').text('');

        if (otp.length < 6) {
            $('.otp-box').addClass('error');
            $('#error-otp').text('Please enter the complete 6-digit code.');
            return;
        }

        const $btn = $(this);
        $btn.addClass('loading').prop('disabled', true);

        $.ajax({
            url: '{{ route("register.verify-otp") }}',
            method: 'POST',
            data: { email: currentEmail, otp: otp, _token: '{{ csrf_token() }}' },
            success: function () {
                clearInterval(countdownTimer);
                $('#emailHidden').val(currentEmail);
                $('#emailDisplay3').text(currentEmail);

                markCompleted('dot2', 'line2');
                $('#dot3').addClass('active');
                $('#stepSubtitle').text('Almost done! Set your password');

                goToStep(2, 3);
                $('#name').focus();
            },
            error: function (xhr) {
                $('.otp-box').addClass('error');
                $('#error-otp').text(xhr.responseJSON?.message || 'Invalid or expired code.');
            }
        }).always(function () {
            $btn.removeClass('loading').prop('disabled', false);
        });
    });

    // ── STEP 3: Register ──────────────────────────────────────
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        $('.unique-error-text').text('');
        $('.unique-input').removeClass('unique-input-error');
        $('#alertContainer').empty();

        const $btn = $('#registerBtn');
        $btn.addClass('loading').prop('disabled', true);

        $.ajax({
            url: '{{ route("register") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                showAlert('success', res.message || 'Account created! Redirecting...');
                setTimeout(() => {
                    window.location.href = res.redirect || '{{ route("home") }}';
                }, 1500);
            },
            error: function (xhr) {
                $btn.removeClass('loading').prop('disabled', false);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $(`#${field}`).addClass('unique-input-error');
                        $(`#error-${field}`).text(messages[0]);
                    });
                    showAlert('danger', 'Please fix the errors below.');
                } else {
                    showAlert('danger', xhr.responseJSON?.message || 'Registration failed.');
                }
            }
        });
    });

});
</script>
@endpush
