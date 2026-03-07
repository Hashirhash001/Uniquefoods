@extends('frontend.layouts.app')

@section('title', 'Forgot Password - Unique Foods')

@section('content')

<div class="unique-auth-wrapper" style="padding-top: 120px; padding-bottom: 120px;">
    <div class="unique-auth-container">

        {{-- Left Side --}}
        <div class="unique-auth-left">
            <div class="unique-auth-branding">
                <div class="unique-auth-logo">
                    <i class="fa-solid fa-leaf"></i>
                    <h1>Unique Foods</h1>
                </div>
                <h2>Reset Password</h2>
                <p>No worries! Enter your email and we'll send you a code to reset your password.</p>
                <div class="unique-auth-features">
                    <div class="unique-feature-item">
                        <i class="fa-regular fa-circle-check"></i>
                        <span>Quick & Secure Reset</span>
                    </div>
                    <div class="unique-feature-item">
                        <i class="fa-regular fa-circle-check"></i>
                        <span>OTP Verified Process</span>
                    </div>
                    <div class="unique-feature-item">
                        <i class="fa-regular fa-circle-check"></i>
                        <span>Instant Access Restored</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="unique-auth-right">
            <div class="unique-auth-form-wrapper">

                <div class="unique-form-header">
                    <h3>Forgot Password?</h3>
                    <p id="stepSubtitle">Enter your registered email address</p>
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

                {{-- ── STEP 1: Email ── --}}
                <div id="step1">
                    <div class="unique-form-group">
                        <label class="unique-label">
                            <i class="fa-regular fa-envelope"></i>
                            <span>Email Address</span>
                        </label>
                        <input type="email" id="emailInput" class="unique-input"
                               placeholder="you@example.com" required autofocus>
                        <span class="unique-error-text" id="error-email"></span>
                    </div>

                    <button type="button" class="unique-btn-submit" id="sendOtpBtn">
                        <span>Send Reset Code</span>
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>

                    <div class="unique-form-footer" style="margin-top:20px;">
                        <p>Remember your password? <a href="{{ route('login') }}" class="unique-link-green">Sign In</a></p>
                    </div>
                </div>

                {{-- ── STEP 2: OTP ── --}}
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
                        We sent a 6-digit reset code to your email.
                    </p>

                    <div class="otp-input-group">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric">
                        <input type="text" class="otp-box" maxlength="1" inputmode="numeric">
                    </div>
                    <span class="unique-error-text" id="error-otp" style="text-align:center;display:block;margin-bottom:12px;"></span>

                    <div class="resend-wrapper">
                        <span id="timerText">Resend code in <strong id="countdown">60</strong>s</span>
                        <button type="button" id="resendBtn" style="display:none;">
                            <i class="fa-regular fa-rotate-right"></i> Resend Code
                        </button>
                    </div>

                    <button type="button" class="unique-btn-submit" id="verifyOtpBtn">
                        <span>Verify Code</span>
                        <i class="fa-regular fa-shield-check"></i>
                    </button>
                </div>

                {{-- ── STEP 3: New Password ── --}}
                <div id="step3" style="display:none;">

                    <div class="email-pill">
                        <i class="fa-regular fa-envelope"></i>
                        <span id="emailDisplay3"></span>
                        <span class="verified-badge">
                            <i class="fa-solid fa-circle-check"></i> Verified
                        </span>
                    </div>

                    <form id="resetForm">
                        @csrf
                        <input type="hidden" id="emailHidden" name="email">

                        <div class="unique-form-group">
                            <label class="unique-label">
                                <i class="fa-regular fa-lock"></i>
                                <span>New Password</span>
                            </label>
                            <div class="unique-password-wrapper">
                                <input type="password" id="password" name="password"
                                       class="unique-input" placeholder="Create new password" required>
                                <button type="button" class="unique-password-toggle" data-target="password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <div class="strength-bar-wrapper">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                            <span class="strength-label" id="strengthLabel"></span>
                            <span class="unique-error-text" id="error-password"></span>
                        </div>

                        <div class="unique-form-group">
                            <label class="unique-label">
                                <i class="fa-regular fa-lock"></i>
                                <span>Confirm New Password</span>
                            </label>
                            <div class="unique-password-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="unique-input" placeholder="Repeat new password" required>
                                <button type="button" class="unique-password-toggle" data-target="password_confirmation">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <span class="unique-error-text" id="error-password_confirmation"></span>
                        </div>

                        <button type="submit" class="unique-btn-submit" id="resetBtn">
                            <span>Reset Password</span>
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
        min-height: 100vh; display: flex; align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 40px 20px;
    }
    .unique-auth-container {
        max-width: 1100px; width: 100%; background: white;
        border-radius: 24px; overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        display: grid; grid-template-columns: 45% 55%;
    }
    .unique-auth-left {
        background: linear-gradient(135deg, var(--unique-green) 0%, var(--unique-green-dark) 100%);
        padding: 60px 50px; color: white; display: flex;
        align-items: center; position: relative; overflow: hidden;
    }
    .unique-auth-left::before {
        content: ''; position: absolute; top: -50%; right: -50%;
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
    .step.active  { background: var(--unique-green);   border-color: var(--unique-green);   color: white; }
    .step.completed { background: var(--unique-success); border-color: var(--unique-success); color: white; }
    .step-line { flex: 1; height: 2px; background: var(--unique-border); margin: 0 8px; transition: background 0.4s; }
    .step-line.active { background: var(--unique-success); }

    .unique-alert { display: flex; gap: 12px; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; }
    .unique-alert i { font-size: 20px; flex-shrink: 0; }
    .unique-alert p { margin: 0; line-height: 1.5; }
    .unique-alert-danger  { background: #fef2f2; color: var(--unique-danger);  border: 1px solid #fecaca; }
    .unique-alert-success { background: #f0fdf4; color: var(--unique-success); border: 1px solid #bbf7d0; }

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

    .unique-password-wrapper { position: relative; }
    .unique-password-toggle {
        position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: var(--unique-gray);
        cursor: pointer; padding: 8px; font-size: 16px; width: unset;
    }

    .unique-btn-submit {
        width: 100%; padding: 16px 24px; background: var(--unique-green);
        color: white; border: none; border-radius: 12px; font-size: 16px;
        font-weight: 600; cursor: pointer; display: flex; align-items: center;
        justify-content: center; gap: 12px; transition: background 0.3s, box-shadow 0.3s;
        position: relative; overflow: hidden; min-height: 54px;
    }
    .unique-btn-submit:hover { background: var(--unique-green-dark); box-shadow: 0 8px 20px rgba(8,67,123,0.3); }
    .unique-btn-submit.loading { pointer-events: none; opacity: 0.8; }
    .unique-btn-submit.loading > * { visibility: hidden; }
    .unique-btn-submit.loading::after {
        content: ''; position: absolute;
        top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 22px; height: 22px;
        border: 3px solid rgba(255,255,255,0.3);
        border-top-color: white; border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: translate(-50%, -50%) rotate(360deg); } }

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

    .otp-hint { font-size: 14px; color: var(--unique-gray); margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
    .otp-hint i { color: var(--unique-green); }

    .otp-input-group { display: flex; gap: 10px; justify-content: center; margin-bottom: 12px; }
    .otp-box {
        width: 52px !important; height: 56px; text-align: center;
        font-size: 22px; font-weight: 700; border: 2px solid var(--unique-border) !important;
        border-radius: 12px !important; outline: none !important;
        transition: all 0.2s !important; padding: 0 !important; color: var(--unique-black);
    }
    .otp-box:focus   { border-color: var(--unique-green)   !important; box-shadow: 0 0 0 4px rgba(8,67,123,0.1) !important; }
    .otp-box.filled  { border-color: var(--unique-green)   !important; background: rgba(8,67,123,0.04); }
    .otp-box.error   { border-color: var(--unique-danger)  !important; background: #fef2f2; }

    .resend-wrapper { text-align: center; margin-bottom: 20px; font-size: 14px; color: var(--unique-gray); }
    #resendBtn { background: none; border: none; color: var(--unique-green); font-size: 14px; font-weight: 600; cursor: pointer; padding: 0; width: unset; }
    #resendBtn:hover { text-decoration: underline; }

    .strength-bar-wrapper { height: 4px; background: var(--unique-border); border-radius: 4px; margin-top: 8px; overflow: hidden; }
    .strength-bar { height: 100%; width: 0%; border-radius: 4px; transition: all 0.3s; }
    .strength-label { font-size: 12px; margin-top: 4px; font-weight: 600; display: block; }

    .unique-form-footer { text-align: center; }
    .unique-form-footer p { font-size: 14px; color: var(--unique-gray); margin: 0; }
    .unique-link-green { color: var(--unique-green); font-weight: 600; text-decoration: none; }
    .unique-link-green:hover { text-decoration: underline; }

    @media (max-width: 991px) {
        .unique-auth-container { grid-template-columns: 1fr; }
        .unique-auth-left { display: none; }
        .unique-auth-right { padding: 40px 30px; }
    }
    @media (max-width: 767px) {
        .unique-auth-wrapper { padding: 20px; }
        .unique-auth-right { padding: 30px 20px; }
        .unique-form-header h3 { font-size: 24px; }
        .otp-box { width: 42px !important; height: 48px; font-size: 18px; }
        .otp-input-group { gap: 7px; }
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
        $(`#step${from}`).fadeOut(200, function () { $(`#step${to}`).fadeIn(200); });
    }

    function markCompleted(dotId, lineId) {
        $(`#${dotId}`).removeClass('active').addClass('completed')
            .html('<i class="fa-solid fa-check" style="font-size:12px"></i>');
        if (lineId) $(`#${lineId}`).addClass('active');
    }

    function startCountdown() {
        let s = 60;
        $('#timerText').show(); $('#resendBtn').hide(); $('#countdown').text(s);
        clearInterval(countdownTimer);
        countdownTimer = setInterval(function () {
            s--;
            $('#countdown').text(s);
            if (s <= 0) {
                clearInterval(countdownTimer);
                $('#timerText').hide();
                $('#resendBtn').show();
            }
        }, 1000);
    }

    // ── OTP Boxes ─────────────────────────────────────────────
    $(document).on('input', '.otp-box', function () {
        const val = $(this).val().replace(/\D/g, '');
        $(this).val(val).toggleClass('filled', val !== '');
        if (val && $(this).next('.otp-box').length) $(this).next('.otp-box').focus();
    });
    $(document).on('keydown', '.otp-box', function (e) {
        if (e.key === 'Backspace' && !$(this).val() && $(this).prev('.otp-box').length) {
            $(this).prev('.otp-box').focus().val('').removeClass('filled');
        }
    });
    $(document).on('paste', '.otp-box', function (e) {
        e.preventDefault();
        const pasted = (e.originalEvent.clipboardData || window.clipboardData)
            .getData('text').replace(/\D/g, '').slice(0, 6);
        $('.otp-box').each(function (i) {
            $(this).val(pasted[i] || '').toggleClass('filled', !!pasted[i]);
        });
        $('.otp-box').last().focus();
    });
    function getOtp() {
        return $('.otp-box').map(function () { return $(this).val(); }).get().join('');
    }

    // ── Password Toggle ───────────────────────────────────────
    $(document).on('click', '.unique-password-toggle', function () {
        const id = $(this).data('target');
        const $i = $(`#${id}`);
        $i.attr('type', $i.attr('type') === 'password' ? 'text' : 'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // ── Password Strength ─────────────────────────────────────
    $('#password').on('input', function () {
        const v = $(this).val();
        let s = 0;
        if (v.length >= 6)  s++;
        if (v.length >= 10) s++;
        if (/[A-Z]/.test(v)) s++;
        if (/[0-9]/.test(v)) s++;
        if (/[^A-Za-z0-9]/.test(v)) s++;
        const levels = [
            {},
            { w: '25%',  c: '#ef4444', l: 'Weak' },
            { w: '50%',  c: '#f97316', l: 'Fair' },
            { w: '75%',  c: '#eab308', l: 'Good' },
            { w: '90%',  c: '#10b981', l: 'Strong' },
            { w: '100%', c: '#059669', l: 'Very Strong' },
        ];
        const lvl = levels[Math.min(s, 5)];
        if (lvl.w) {
            $('#strengthBar').css({ width: lvl.w, background: lvl.c });
            $('#strengthLabel').text(lvl.l).css('color', lvl.c);
        }
    });

    // ── STEP 1: Send OTP ──────────────────────────────────────
    $('#sendOtpBtn').on('click', function () {
        const email = $('#emailInput').val().trim();
        $('#error-email').text('');
        $('#emailInput').removeClass('unique-input-error');
        $('#alertContainer').empty();

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#emailInput').addClass('unique-input-error');
            $('#error-email').text('Please enter a valid email address.');
            return;
        }

        const $btn = $(this);
        $btn.addClass('loading').prop('disabled', true);

        $.ajax({
            url: '{{ route("password.send-otp") }}',
            method: 'POST',
            data: { email: email, _token: '{{ csrf_token() }}' },
            success: function () {
                currentEmail = email;
                $('#emailDisplay').text(email);
                markCompleted('dot1', 'line1');
                $('#dot2').addClass('active');
                $('#stepSubtitle').text('Enter the code sent to your email');
                goToStep(1, 2);
                startCountdown();
                $('.otp-box').first().focus();
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.errors?.email?.[0]
                    || xhr.responseJSON?.message
                    || 'Failed to send OTP.';
                $('#emailInput').addClass('unique-input-error');
                $('#error-email').text(msg);
            }
        }).always(function () {
            $btn.removeClass('loading').prop('disabled', false);
        });
    });

    // ── Edit Email ────────────────────────────────────────────
    $('#editEmail').on('click', function () {
        clearInterval(countdownTimer);
        $('.otp-box').val('').removeClass('filled error');
        $('#error-otp').text('');
        $('#alertContainer').empty();
        goToStep(2, 1);
        $('#dot1').removeClass('completed').addClass('active').html('<span>1</span>');
        $('#dot2').removeClass('active');
        $('#line1').removeClass('active');
        $('#stepSubtitle').text('Enter your registered email address');
    });

    // ── Resend OTP ────────────────────────────────────────────
    $('#resendBtn').on('click', function () {
        $('.otp-box').val('').removeClass('filled error');
        $('#error-otp').text('');
        $.post('{{ route("password.send-otp") }}', {
            email: currentEmail, _token: '{{ csrf_token() }}'
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
        const otp = getOtp();
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
            url: '{{ route("password.verify-otp") }}',
            method: 'POST',
            data: { email: currentEmail, otp: otp, _token: '{{ csrf_token() }}' },
            success: function () {
                clearInterval(countdownTimer);
                $('#emailHidden').val(currentEmail);
                $('#emailDisplay3').text(currentEmail);
                markCompleted('dot2', 'line2');
                $('#dot3').addClass('active');
                $('#stepSubtitle').text('Set your new password');
                goToStep(2, 3);
                $('#password').focus();
            },
            error: function (xhr) {
                $('.otp-box').addClass('error');
                $('#error-otp').text(xhr.responseJSON?.message || 'Invalid or expired code.');
            }
        }).always(function () {
            $btn.removeClass('loading').prop('disabled', false);
        });
    });

    // ── STEP 3: Reset Password ────────────────────────────────
    $('#resetForm').on('submit', function (e) {
        e.preventDefault();
        $('.unique-error-text').text('');
        $('.unique-input').removeClass('unique-input-error');
        $('#alertContainer').empty();

        const $btn = $('#resetBtn');
        $btn.addClass('loading').prop('disabled', true);

        $.ajax({
            url: '{{ route("password.reset") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                showAlert('success', res.message || 'Password reset! Redirecting...');
                setTimeout(() => { window.location.href = res.redirect || '{{ route("login") }}'; }, 1500);
            },
            error: function (xhr) {
                $btn.removeClass('loading').prop('disabled', false);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    $.each(errors, function (field, messages) {
                        $(`#${field}`).addClass('unique-input-error');
                        $(`#error-${field}`).text(messages[0]);
                    });
                    showAlert('danger', xhr.responseJSON?.message || 'Please fix the errors.');
                } else {
                    showAlert('danger', xhr.responseJSON?.message || 'Reset failed. Please try again.');
                }
            }
        });
    });

});
</script>
@endpush
