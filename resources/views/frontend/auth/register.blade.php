@extends('frontend.layouts.app')

@section('title', 'Register - Unique Foods')

@section('content')

<div class="unique-auth-wrapper">
    <div class="unique-auth-container">

        {{-- Left Side - Image/Branding --}}
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

        {{-- Right Side - Register Form --}}
        <div class="unique-auth-right">
            <div class="unique-auth-form-wrapper">

                <div class="unique-form-header">
                    <h3>Create Account</h3>
                    <p>Fill in your details to get started</p>
                </div>

                {{-- Alert Container --}}
                <div id="alertContainer"></div>

                {{-- Google Login Button --}}
                <a href="{{ route('auth.google') }}" class="unique-btn-google">
                    <svg width="18" height="18" viewBox="0 0 18 18">
                        <path fill="#4285F4" d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 0 0 2.38-5.88c0-.57-.05-.66-.15-1.18z"></path>
                        <path fill="#34A853" d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2a4.8 4.8 0 0 1-7.18-2.54H1.83v2.07A8 8 0 0 0 8.98 17z"></path>
                        <path fill="#FBBC05" d="M4.5 10.52a4.8 4.8 0 0 1 0-3.04V5.41H1.83a8 8 0 0 0 0 7.18l2.67-2.07z"></path>
                        <path fill="#EA4335" d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 0 0 1.83 5.4L4.5 7.49a4.77 4.77 0 0 1 4.48-3.3z"></path>
                    </svg>
                    <span>Continue with Google</span>
                </a>

                <div class="unique-divider-text">
                    <span>or register with email</span>
                </div>

                {{-- Register Form --}}
                <form id="registerForm" class="unique-auth-form">
                    @csrf

                    {{-- Name Field --}}
                    <div class="unique-form-group">
                        <label for="name" class="unique-label">
                            <i class="fa-regular fa-user"></i>
                            <span>Full Name</span>
                        </label>
                        <input type="text" id="name" name="name"
                               class="unique-input"
                               placeholder="Enter your full name" required autofocus>
                        <span class="unique-error-text" id="error-name"></span>
                    </div>

                    {{-- Email Field --}}
                    <div class="unique-form-group">
                        <label for="email" class="unique-label">
                            <i class="fa-regular fa-envelope"></i>
                            <span>Email Address</span>
                        </label>
                        <input type="email" id="email" name="email"
                               class="unique-input"
                               placeholder="Enter your email" required>
                        <span class="unique-error-text" id="error-email"></span>
                    </div>

                    {{-- Mobile Field --}}
                    <div class="unique-form-group">
                        <label for="mobile" class="unique-label">
                            <i class="fa-regular fa-phone"></i>
                            <span>Mobile Number (Optional)</span>
                        </label>
                        <input type="tel" id="mobile" name="mobile"
                               class="unique-input"
                               placeholder="Enter your mobile number">
                        <span class="unique-error-text" id="error-mobile"></span>
                    </div>

                    {{-- Password Field --}}
                    <div class="unique-form-group">
                        <label for="password" class="unique-label">
                            <i class="fa-regular fa-lock"></i>
                            <span>Password</span>
                        </label>
                        <div class="unique-password-wrapper">
                            <input type="password" id="password" name="password"
                                   class="unique-input"
                                   placeholder="Create a password" required>
                            <button type="button" class="unique-password-toggle" data-target="password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <span class="unique-error-text" id="error-password"></span>
                    </div>

                    {{-- Confirm Password Field --}}
                    <div class="unique-form-group">
                        <label for="password_confirmation" class="unique-label">
                            <i class="fa-regular fa-lock"></i>
                            <span>Confirm Password</span>
                        </label>
                        <div class="unique-password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="unique-input"
                                   placeholder="Confirm your password" required>
                            <button type="button" class="unique-password-toggle" data-target="password_confirmation">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <span class="unique-error-text" id="error-password_confirmation"></span>
                    </div>

                    {{-- Terms Checkbox --}}
                    <div class="unique-form-group">
                        <label class="unique-checkbox">
                            <input type="checkbox" name="terms" id="terms" required>
                            <span class="checkmark"></span>
                            <span class="label-text">I agree to the <a href="#" class="unique-link-green">Terms & Conditions</a></span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="unique-btn-submit" id="registerBtn">
                        <span>Create Account</span>
                        <i class="fa-regular fa-arrow-right"></i>
                    </button>

                </form>

                {{-- Login Link --}}
                <div class="unique-form-footer">
                    <p>Already have an account? <a href="{{ route('login') }}" class="unique-link-green">Sign In</a></p>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    :root {
        --unique-green: #629D23;
        --unique-green-dark: #518219;
        --unique-black: #1a1a1a;
        --unique-gray: #666666;
        --unique-light-gray: #f8f9fa;
        --unique-border: #e5e7eb;
        --unique-danger: #ef4444;
        --unique-success: #10b981;
    }

    /* Loading spinner */
    .unique-btn-submit.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .unique-btn-submit.loading span {
        opacity: 0;
    }

    .unique-btn-submit.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Auth Wrapper */
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
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        display: grid;
        grid-template-columns: 45% 55%;
    }

    /* Left Side */
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
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .unique-auth-branding {
        position: relative;
        z-index: 2;
    }

    .unique-auth-logo {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
    }

    .unique-auth-logo i {
        font-size: 48px;
        color: white;
    }

    .unique-auth-logo h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    .unique-auth-branding h2 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 16px;
        color: white;
    }

    .unique-auth-branding > p {
        font-size: 16px;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 40px;
        color: #fff;
    }

    .unique-auth-features {
        display: grid;
        gap: 16px;
    }

    .unique-feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 15px;
    }

    .unique-feature-item i {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.9);
    }

    /* Right Side */
    .unique-auth-right {
        padding: 60px 50px;
        display: flex;
        align-items: center;
    }

    .unique-auth-form-wrapper {
        width: 100%;
    }

    .unique-form-header {
        margin-bottom: 32px;
    }

    .unique-form-header h3 {
        font-size: 28px;
        font-weight: 700;
        color: var(--unique-black);
        margin-bottom: 8px;
    }

    .unique-form-header p {
        font-size: 15px;
        color: var(--unique-gray);
        margin: 0;
    }

    /* Alerts */
    .unique-alert {
        display: flex;
        gap: 12px;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 14px;
    }

    .unique-alert i {
        font-size: 20px;
        flex-shrink: 0;
    }

    .unique-alert p {
        margin: 0;
        line-height: 1.5;
    }

    .unique-alert-danger {
        background: #fef2f2;
        color: var(--unique-danger);
        border: 1px solid #fecaca;
    }

    .unique-alert-success {
        background: #f0fdf4;
        color: var(--unique-success);
        border: 1px solid #bbf7d0;
    }

    /* Google Button */
    .unique-btn-google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        padding: 14px 24px;
        background: white;
        border: 2px solid var(--unique-border);
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        color: var(--unique-black);
        text-decoration: none;
        transition: all 0.3s;
        margin-bottom: 24px;
    }

    .unique-btn-google:hover {
        background: var(--unique-light-gray);
        border-color: #d1d5db;
    }

    /* Divider */
    .unique-divider-text {
        position: relative;
        text-align: center;
        margin: 24px 0;
    }

    .unique-divider-text::before,
    .unique-divider-text::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 40%;
        height: 1px;
        background: var(--unique-border);
    }

    .unique-divider-text::before {
        left: 0;
    }

    .unique-divider-text::after {
        right: 0;
    }

    .unique-divider-text span {
        background: white;
        padding: 0 16px;
        font-size: 14px;
        color: var(--unique-gray);
    }

    /* Form */
    .unique-auth-form {
        margin-bottom: 24px;
    }

    .unique-form-group {
        margin-bottom: 24px;
    }

    .unique-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: var(--unique-black);
        margin-bottom: 8px;
    }

    .unique-label i {
        color: var(--unique-green);
    }

    .unique-input {
        width: 100% !important;
        padding: 14px 16px !important;
        border: 2px solid var(--unique-border) !important;
        border-radius: 12px !important;
        font-size: 15px !important;
        transition: all 0.3s !important;
        outline: none !important;
    }

    .unique-input:focus {
        border-color: var(--unique-green) !important;
        box-shadow: 0 0 0 4px rgba(98, 157, 35, 0.1) !important;
    }

    .unique-input-error {
        border-color: var(--unique-danger);
    }

    .unique-input-error:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .unique-error-text {
        display: block;
        font-size: 13px;
        color: var(--unique-danger);
        margin-top: 6px;
    }

    /* Password Toggle */
    .unique-password-wrapper {
        position: relative;
    }

    .unique-password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--unique-gray);
        cursor: pointer;
        padding: 8px;
        font-size: 16px;
        width: unset;
    }

    /* Form Options */
    .unique-form-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .unique-checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        position: relative;
    }

    .unique-checkbox input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid var(--unique-border);
        border-radius: 6px;
        transition: all 0.3s;
    }

    .unique-checkbox input[type="checkbox"]:checked ~ .checkmark {
        background: var(--unique-green);
        border-color: var(--unique-green);
    }

    .unique-checkbox input[type="checkbox"]:checked ~ .checkmark::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Pro';
        font-weight: 900;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
    }

    .label-text {
        font-size: 14px;
        color: var(--unique-gray);
    }

    .unique-forgot-link {
        font-size: 14px;
        color: var(--unique-green);
        text-decoration: none;
        font-weight: 600;
    }

    .unique-forgot-link:hover {
        text-decoration: underline;
    }

    /* Submit Button */
    .unique-btn-submit {
        width: 100%;
        padding: 16px 24px;
        background: var(--unique-green);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: all 0.3s;
    }

    .unique-btn-submit:hover {
        background: var(--unique-green-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(98, 157, 35, 0.3);
    }

    .unique-btn-submit i {
        transition: transform 0.3s;
    }

    .unique-btn-submit:hover i {
        transform: translateX(4px);
    }

    /* Footer */
    .unique-form-footer {
        text-align: center;
    }

    .unique-form-footer p {
        font-size: 14px;
        color: var(--unique-gray);
        margin: 0;
    }

    .unique-link-green {
        color: var(--unique-green);
        font-weight: 600;
        text-decoration: none;
    }

    .unique-link-green:hover {
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .unique-auth-container {
            grid-template-columns: 1fr;
        }

        .unique-auth-left {
            display: none;
        }

        .unique-auth-right {
            padding: 40px 30px;
        }
    }

    @media (max-width: 767px) {
        .unique-auth-wrapper {
            padding: 20px;
        }

        .unique-auth-right {
            padding: 30px 20px;
        }

        .unique-form-header h3 {
            font-size: 24px;
        }

        .unique-form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Password toggle functionality
    $('.unique-password-toggle').on('click', function() {
        const targetId = $(this).data('target');
        const $input = $(`#${targetId}`);
        const $icon = $(this).find('i');

        const type = $input.attr('type') === 'password' ? 'text' : 'password';
        $input.attr('type', type);

        $icon.toggleClass('fa-eye fa-eye-slash');
    });

    // AJAX Register Form
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('.unique-error-text').text('');
        $('.unique-input').removeClass('unique-input-error');
        $('#alertContainer').empty();

        const $btn = $('#registerBtn');
        const $btnText = $btn.find('span');
        const originalText = $btnText.text();

        // Loading state
        $btn.addClass('loading').prop('disabled', true);
        $btnText.text('Creating Account...');

        $.ajax({
            url: '{{ route("register") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Show success message
                showAlert('success', response.message || 'Registration successful! Redirecting...');

                // Redirect after 1.5 seconds
                setTimeout(function() {
                    window.location.href = response.redirect || '{{ route("home") }}';
                }, 1500);
            },
            error: function(xhr) {
                $btn.removeClass('loading').prop('disabled', false);
                $btnText.text(originalText);

                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;

                    $.each(errors, function(field, messages) {
                        const $input = $(`#${field}`);
                        const $error = $(`#error-${field}`);

                        $input.addClass('unique-input-error');
                        $error.text(messages[0]);
                    });

                    showAlert('danger', 'Please fix the errors below.');
                } else {
                    // General error
                    showAlert('danger', xhr.responseJSON?.message || 'Registration failed. Please try again.');
                }
            }
        });
    });

    // Helper function to show alerts
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'unique-alert-success' : 'unique-alert-danger';
        const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';

        const html = `
            <div class="unique-alert ${alertClass}">
                <i class="fa-regular ${icon}"></i>
                <p>${message}</p>
            </div>
        `;

        $('#alertContainer').html(html);

        // Scroll to alert
        $('html, body').animate({
            scrollTop: $('#alertContainer').offset().top - 100
        }, 300);
    }
});
</script>
@endpush
