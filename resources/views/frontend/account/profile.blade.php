@extends('frontend.layouts.app')

@section('title', 'My Profile')

@push('styles')
<style>
    button {
        width: unset;
    }
    .uf-profile-wrapper {
        padding: 60px 0 80px;
        background: linear-gradient(135deg, #f8fafc 0%, #e8f0fe 100%);
        min-height: calc(100vh - var(--header-height, 140px));
    }

    .uf-profile-container {
        max-width: 860px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .uf-profile-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #0f508d;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        margin-bottom: 24px;
        transition: gap 0.2s;
    }
    .uf-profile-back-btn:hover { gap: 12px; color: #08437b; }

    /* ── Hero ── */
    .uf-profile-hero {
        background: linear-gradient(135deg, #0f508d 0%, #08437b 100%);
        border-radius: 18px;
        padding: 32px;
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(15,80,141,0.25);
    }

    .uf-profile-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }

    .uf-profile-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -20px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }

    .uf-avatar-wrap {
        position: relative;
        flex-shrink: 0;
        z-index: 1;
    }

    .uf-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 800;
        color: white;
        border: 3px solid rgba(255,255,255,0.4);
        overflow: hidden;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .uf-avatar:hover { border-color: rgba(255,255,255,0.8); }
    .uf-avatar img   { width: 100%; height: 100%; object-fit: cover; }

    .uf-avatar-edit-btn {
        position: absolute;
        bottom: 2px; right: 2px;
        width: 26px; height: 26px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #0f508d;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: transform 0.2s;
        border: none;
    }

    .uf-avatar-edit-btn:hover { transform: scale(1.1); }

    .uf-avatar-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        border: 1px solid #e2e8f0;
        min-width: 160px;
        z-index: 100;
        overflow: hidden;
        display: none;
    }

    .uf-avatar-menu.open { display: block; }

    .uf-avatar-menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        transition: background 0.15s;
    }

    .uf-avatar-menu-item:hover { background: #f8fafc; }
    .uf-avatar-menu-item.is-danger { color: #dc2626; }
    .uf-avatar-menu-item.is-danger:hover { background: #fef2f2; }
    .uf-avatar-menu-item i { width: 16px; text-align: center; }

    .uf-hero-info { flex: 1; z-index: 1; }

    .uf-hero-info h2 {
        font-size: 22px;
        font-weight: 800;
        color: white;
        margin-bottom: 4px;
    }

    .uf-hero-info p {
        font-size: 14px;
        color: rgba(255,255,255,0.75);
        margin-bottom: 14px;
    }

    .uf-hero-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .uf-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        color: white;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 99px;
    }

    .uf-hero-badge.is-unverified {
        background: rgba(239,68,68,0.2);
        border-color: rgba(239,68,68,0.3);
    }

    .uf-profile-stats {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
        z-index: 1;
    }

    .uf-stat-box {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 12px;
        padding: 14px 18px;
        text-align: center;
        min-width: 80px;
    }

    .uf-stat-num {
        font-size: 22px;
        font-weight: 800;
        color: white;
        line-height: 1;
        margin-bottom: 4px;
    }

    .uf-stat-lbl {
        font-size: 11px;
        color: rgba(255,255,255,0.7);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ── Cards ── */
    .uf-profile-section {
        background: white;
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .uf-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .uf-section-header h3 {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .uf-section-header h3 i { color: #0f508d; }

    .uf-social-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fef3c7;
        border: 1px solid #fde68a;
        color: #92400e;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 99px;
    }

    /* ── Form ── */
    .uf-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .uf-form-group { margin-bottom: 18px; }

    .uf-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .uf-form-group label .uf-req { color: #ef4444; margin-left: 2px; }
    .uf-form-group label .uf-opt {
        color: #94a3b8;
        font-weight: 400;
        font-size: 12px;
        margin-left: 4px;
    }

    .uf-form-input {
        width: 100% !important;
        padding: 11px 14px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        font-size: 14px !important;
        color: #1e293b !important;
        background: #fff !important;
        outline: none !important;
        transition: border-color 0.2s, box-shadow 0.2s !important;
        box-sizing: border-box !important;
        font-family: inherit !important;
    }

    .uf-form-input:focus {
        border-color: #0f508d;
        box-shadow: 0 0 0 3px rgba(15,80,141,0.1);
    }

    .uf-form-input.has-error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
    }

    .uf-form-input:disabled {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .uf-field-error {
        font-size: 12px;
        color: #ef4444;
        margin-top: 4px;
        display: none;
    }
    .uf-field-error.show { display: block; }

    /* Password fields */
    .uf-pw-wrap {
        position: relative;
    }

    .uf-pw-wrap .uf-form-input { padding-right: 44px; }

    .uf-pw-eye {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 14px;
        padding: 4px;
        transition: color 0.2s;
        line-height: 1;
    }

    .uf-pw-eye:hover { color: #0f508d; }

    .uf-strength-bar {
        height: 4px;
        border-radius: 99px;
        background: #e2e8f0;
        margin-top: 8px;
        overflow: hidden;
    }

    .uf-strength-fill {
        height: 100%;
        border-radius: 99px;
        transition: width 0.3s, background 0.3s;
        width: 0;
    }

    .uf-strength-label {
        font-size: 12px;
        font-weight: 600;
        margin-top: 4px;
        color: #94a3b8;
    }

    /* Buttons */
    .uf-btn-primary {
        width: unset;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0f508d 0%, #08437b 100%);
        color: white !important;
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 6px;
        font-family: inherit;
    }

    .uf-btn-primary:hover {
        box-shadow: 0 6px 18px rgba(15,80,141,0.3);
        transform: translateY(-1px);
    }

    .uf-btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .uf-btn-danger-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fef2f2;
        color: #dc2626;
        border: 2px solid #fecaca;
        padding: 11px 22px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .uf-btn-danger-outline:hover {
        background: #fee2e2;
        border-color: #f87171;
    }

    /* Quick links */
    .uf-quick-links {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .uf-quick-link {
        flex: 1;
        min-width: 160px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 18px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        text-decoration: none;
        color: #1e293b;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }

    .uf-quick-link:hover {
        border-color: #0f508d;
        background: #eff6ff;
        color: #0f508d;
    }

    .uf-quick-link i {
        color: #0f508d;
        font-size: 18px;
        flex-shrink: 0;
    }

    /* Danger section */
    .uf-profile-section.is-danger {
        border-color: #fecaca;
        background: #fffbfb;
    }

    .uf-profile-section.is-danger .uf-section-header { border-bottom-color: #fecaca; }
    .uf-profile-section.is-danger .uf-section-header h3 { color: #dc2626; }
    .uf-profile-section.is-danger .uf-section-header h3 i { color: #dc2626; }

    .uf-danger-info {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 18px;
        line-height: 1.6;
    }

    /* Confirm overlay (delete account) */
    .uf-confirm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
    }

    .uf-confirm-overlay.active { opacity: 1; visibility: visible; }

    .uf-confirm-box {
        background: white;
        border-radius: 18px;
        padding: 32px 28px 26px;
        max-width: 380px;
        width: 100%;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.18);
        transform: scale(0.92) translateY(10px);
        transition: transform 0.22s ease;
    }

    .uf-confirm-overlay.active .uf-confirm-box { transform: scale(1) translateY(0); }

    .uf-confirm-icon {
        width: 60px;
        height: 60px;
        background: #fee2e2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        font-size: 26px;
        color: #dc2626;
    }

    .uf-confirm-box h3 {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .uf-confirm-box p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .uf-confirm-btns {
        display: flex;
        gap: 10px;
    }

    .uf-btn-keep {
        flex: 1;
        padding: 11px;
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .uf-btn-keep:hover { background: #e2e8f0; }

    .uf-btn-confirm-del {
        flex: 1;
        padding: 11px;
        background: #dc2626;
        border: none;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 700;
        color: white;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-family: inherit;
    }

    .uf-btn-confirm-del:hover { background: #b91c1c; }
    .uf-btn-confirm-del:disabled { opacity: 0.6; cursor: not-allowed; }

    /* Toast */
    .uf-profile-toast {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        padding: 13px 22px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: white;
        z-index: 10000;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        white-space: nowrap;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .uf-profile-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    .uf-profile-toast.is-success { background: #065f46; }
    .uf-profile-toast.is-error   { background: #991b1b; }
    .uf-profile-toast.is-info    { background: #1e40af; }

    /* Responsive */
    @media (max-width: 767px) {
        .uf-profile-wrapper  { padding: 24px 0 80px; }
        .uf-profile-hero     { flex-direction: column; align-items: flex-start; gap: 16px; padding: 22px; }
        .uf-profile-stats    { width: 100%; justify-content: space-between; }
        .uf-stat-box         { flex: 1; }
        .uf-form-row         { grid-template-columns: 1fr; gap: 0; }
        .uf-profile-section  { padding: 20px 18px; }
        .uf-section-header h3 { font-size: 16px; }
        .uf-confirm-btns     { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="uf-profile-wrapper">
    <div class="uf-profile-container">

        <a href="{{ route('home') }}" class="uf-profile-back-btn">
            <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>

        {{-- Hero --}}
        <div class="uf-profile-hero">
            <div class="uf-avatar-wrap" id="ufAvatarWrap">
                <div class="uf-avatar" id="ufAvatarDisplay" onclick="ufToggleAvatarMenu()">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" id="ufAvatarImg">
                    @else
                        <span id="ufAvatarInitial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <button class="uf-avatar-edit-btn" onclick="ufToggleAvatarMenu()" type="button">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <input type="file" id="ufAvatarInput" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none;">
                <div class="uf-avatar-menu" id="ufAvatarMenu">
                    <button class="uf-avatar-menu-item" type="button"
                            onclick="document.getElementById('ufAvatarInput').click(); ufCloseAvatarMenu();">
                        <i class="fa-regular fa-upload"></i> Upload Photo
                    </button>
                    @if($user->avatar)
                    <button class="uf-avatar-menu-item is-danger" type="button" onclick="ufRemoveAvatar()">
                        <i class="fa-regular fa-trash"></i> Remove Photo
                    </button>
                    @endif
                </div>
            </div>

            <div class="uf-hero-info">
                <h2 id="ufHeroName">{{ $user->name }}</h2>
                <p id="ufHeroEmail">{{ $user->email }}</p>
                <div class="uf-hero-badges">
                    @if($user->isSocialUser())
                        <span class="uf-hero-badge">
                            <i class="fa-brands fa-google"></i> Google Account
                        </span>
                    @endif
                    @if($user->email_verified_at)
                        <span class="uf-hero-badge">
                            <i class="fa-solid fa-circle-check"></i> Verified
                        </span>
                    @else
                        <span class="uf-hero-badge is-unverified">
                            <i class="fa-solid fa-circle-xmark"></i> Unverified
                        </span>
                    @endif
                    <span class="uf-hero-badge">
                        <i class="fa-regular fa-calendar"></i>
                        Joined {{ $user->created_at->format('M Y') }}
                    </span>
                </div>
            </div>

            <div class="uf-profile-stats">
                <div class="uf-stat-box">
                    <div class="uf-stat-num">{{ $orders->total ?? 0 }}</div>
                    <div class="uf-stat-lbl">Orders</div>
                </div>
                <div class="uf-stat-box">
                    <div class="uf-stat-num">{{ $orders->delivered ?? 0 }}</div>
                    <div class="uf-stat-lbl">Delivered</div>
                </div>
                <div class="uf-stat-box">
                    <div class="uf-stat-num">£{{ number_format($orders->spent ?? 0, 0) }}</div>
                    <div class="uf-stat-lbl">Spent</div>
                </div>
            </div>
        </div>

        {{-- Personal Info --}}
        <div class="uf-profile-section">
            <div class="uf-section-header">
                <h3><i class="fa-solid fa-user"></i> Personal Information</h3>
            </div>
            <div class="uf-form-row">
                <div class="uf-form-group">
                    <label>Full Name <span class="uf-req">*</span></label>
                    <input type="text" class="uf-form-input" id="ufName"
                           value="{{ $user->name }}" placeholder="Your full name">
                    <div class="uf-field-error" id="ufErr_name"></div>
                </div>
                <div class="uf-form-group">
                    <label>Mobile Number <span class="uf-opt">(Optional)</span></label>
                    <input type="tel" class="uf-form-input" id="ufMobile"
                           value="{{ $user->mobile }}" placeholder="+44 7XXX XXXXXX">
                    <div class="uf-field-error" id="ufErr_mobile"></div>
                </div>
            </div>
            {{-- Email field — read-only, changed via OTP modal --}}
            <div class="uf-form-group">
                <label>Email Address</label>
                <div style="display:flex;gap:10px;align-items:center;">
                    <div style="flex:1;">
                        <input type="email" class="uf-form-input" id="ufEmail"
                            value="{{ $user->email }}" readonly
                            style="background:#f8fafc;color:#64748b;cursor:not-allowed;">
                    </div>
                    <button type="button" class="uf-btn-primary"
                            style="margin-top:0;padding:11px 16px;white-space:nowrap;flex-shrink:0;"
                            onclick="ufOpenEmailChangeModal()">
                        <i class="fa-regular fa-pen-to-square"></i> Change
                    </button>
                </div>
                <div style="font-size:12px;color:#94a3b8;margin-top:5px;">
                    <i class="fa-regular fa-circle-info"></i>
                    Email changes require OTP verification to your new address.
                </div>
            </div>

            <button class="uf-btn-primary" id="ufBtnSaveProfile" onclick="ufSaveProfile()">
                <i class="fa-solid fa-floppy-disk"></i> Save Changes
            </button>
        </div>

        {{-- Password --}}
        <div class="uf-profile-section">
            <div class="uf-section-header">
                <h3><i class="fa-solid fa-lock"></i> Change Password</h3>
                @if($user->isSocialUser())
                    <span class="uf-social-badge">
                        <i class="fa-brands fa-google"></i> Managed by Google
                    </span>
                @endif
            </div>
            @if($user->isSocialUser())
                <p style="font-size:14px;color:#64748b;line-height:1.6;">
                    Your account is linked to Google. Password management is handled by Google.
                </p>
            @else
                <div class="uf-form-group">
                    <label>Current Password <span class="uf-req">*</span></label>
                    <div class="uf-pw-wrap">
                        <input type="password" class="uf-form-input" id="ufCurrentPw" placeholder="Enter current password">
                        <button type="button" class="uf-pw-eye" onclick="ufTogglePw('ufCurrentPw', this)">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="uf-field-error" id="ufErr_current_password"></div>
                </div>
                <div class="uf-form-row">
                    <div class="uf-form-group">
                        <label>New Password <span class="uf-req">*</span></label>
                        <div class="uf-pw-wrap">
                            <input type="password" class="uf-form-input" id="ufNewPw"
                                   placeholder="Min. 8 chars" oninput="ufCheckStrength(this.value)">
                            <button type="button" class="uf-pw-eye" onclick="ufTogglePw('ufNewPw', this)">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="uf-strength-bar">
                            <div class="uf-strength-fill" id="ufStrengthFill"></div>
                        </div>
                        <div class="uf-strength-label" id="ufStrengthLabel"></div>
                        <div class="uf-field-error" id="ufErr_password"></div>
                    </div>
                    <div class="uf-form-group">
                        <label>Confirm Password <span class="uf-req">*</span></label>
                        <div class="uf-pw-wrap">
                            <input type="password" class="uf-form-input" id="ufConfirmPw" placeholder="Repeat new password">
                            <button type="button" class="uf-pw-eye" onclick="ufTogglePw('ufConfirmPw', this)">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="uf-field-error" id="ufErr_password_confirmation"></div>
                    </div>
                </div>
                <button class="uf-btn-primary" id="ufBtnSavePw" onclick="ufSavePassword()">
                    <i class="fa-solid fa-shield-check"></i> Update Password
                </button>
            @endif
        </div>

        {{-- Quick Links --}}
        <div class="uf-profile-section">
            <div class="uf-section-header">
                <h3><i class="fa-solid fa-grid-2"></i> Quick Links</h3>
            </div>
            <div class="uf-quick-links">
                <a href="{{ route('orders.index') }}" class="uf-quick-link">
                    <i class="fa-regular fa-receipt"></i> My Orders
                </a>
                <a href="{{ route('account.addresses') }}" class="uf-quick-link">
                    <i class="fa-regular fa-location-dot"></i> My Addresses
                </a>
                <a href="{{ route('wishlist.index') }}" class="uf-quick-link">
                    <i class="fa-regular fa-heart"></i> Wishlist
                </a>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="uf-profile-section is-danger">
            <div class="uf-section-header">
                <h3><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</h3>
            </div>
            <p class="uf-danger-info">
                Once you delete your account, all your data including orders, addresses and wishlist will be permanently removed. This action cannot be undone.
            </p>
            <button class="uf-btn-danger-outline" onclick="ufConfirmDelete()">
                <i class="fa-regular fa-trash"></i> Delete My Account
            </button>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════
     EMAIL CHANGE MODAL
══════════════════════════════════ --}}
<div class="uf-confirm-overlay" id="ufEmailChangeOverlay" style="z-index:9999;">
    <div class="uf-confirm-box" style="max-width:440px;text-align:left;padding:32px;">

        {{-- Step 1: Enter new email --}}
        <div id="ufEmailStep1">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:2px solid #f1f5f9;">
                <div style="width:44px;height:44px;background:#dbeafe;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#1e40af;flex-shrink:0;">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
                <div>
                    <h3 style="font-size:17px;font-weight:800;color:#1e293b;margin:0 0 2px;">Change Email Address</h3>
                    <p style="font-size:13px;color:#64748b;margin:0;">We'll send a verification code to your new email.</p>
                </div>
                <button type="button" onclick="ufCloseEmailChangeModal()"
                        style="margin-left:auto;width:32px;height:32px;border:none;background:#f1f5f9;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:14px;flex-shrink:0;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start;">
                <i class="fa-solid fa-triangle-exclamation" style="color:#d97706;margin-top:2px;flex-shrink:0;"></i>
                <div style="font-size:13px;color:#92400e;line-height:1.5;">
                    Changing your email will require re-verification. You will be logged in with the new email going forward.
                </div>
            </div>

            <div class="uf-form-group">
                <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                    New Email Address <span style="color:#ef4444;">*</span>
                </label>
                <input type="email" class="uf-form-input" id="ufNewEmail" placeholder="Enter new email address">
                <div class="uf-field-error" id="ufErr_new_email"></div>
            </div>

            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" class="uf-btn-keep" style="flex:1;" onclick="ufCloseEmailChangeModal()">
                    Cancel
                </button>
                <button type="button" id="ufBtnSendOtp"
                        style="flex:2;padding:12px;background:linear-gradient(135deg,#0f508d,#08437b);color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:inherit;"
                        onclick="ufSendEmailOtp()">
                    <i class="fa-solid fa-paper-plane"></i> Send Code
                </button>
            </div>
        </div>

        {{-- Step 2: Enter OTP --}}
        <div id="ufEmailStep2" style="display:none;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:2px solid #f1f5f9;">
                <div style="width:44px;height:44px;background:#d1fae5;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#065f46;flex-shrink:0;">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div>
                    <h3 style="font-size:17px;font-weight:800;color:#1e293b;margin:0 0 2px;">Enter Verification Code</h3>
                    <p style="font-size:13px;color:#64748b;margin:0;">
                        Code sent to <strong id="ufOtpTargetEmail"></strong>
                    </p>
                </div>
                <button type="button" onclick="ufCloseEmailChangeModal()"
                        style="margin-left:auto;width:32px;height:32px;border:none;background:#f1f5f9;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:14px;flex-shrink:0;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="uf-form-group">
                <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                    6-Digit Code <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" class="uf-form-input" id="ufEmailOtp"
                       placeholder="Enter 6-digit code"
                       maxlength="6" inputmode="numeric"
                       style="letter-spacing:6px;font-size:20px;font-weight:700;text-align:center;"
                       onkeypress="return /[0-9]/.test(event.key)">
                <div class="uf-field-error" id="ufErr_otp"></div>
            </div>

            <div style="text-align:center;font-size:13px;color:#64748b;margin-bottom:20px;">
                <span id="ufOtpCountdown" style="display:none;color:#94a3b8;"></span>
                <button type="button" id="ufBtnResendOtp"
                        style="display:none;background:none;border:none;color:#0f508d;font-weight:600;cursor:pointer;font-size:13px;font-family:inherit;"
                        onclick="ufResendEmailOtp()">
                    <i class="fa-regular fa-rotate-right"></i> Resend Code
                </button>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" class="uf-btn-keep" style="flex:1;" onclick="ufResendEmailOtp()">
                    Back
                </button>
                <button type="button" id="ufBtnVerifyOtp"
                        style="flex:2;padding:12px;background:linear-gradient(135deg,#0f508d,#08437b);color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:inherit;"
                        onclick="ufVerifyEmailOtp()">
                    <i class="fa-solid fa-check"></i> Verify & Update
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════
     DELETE ACCOUNT MODAL (3 steps)
══════════════════════════════════ --}}
<div class="uf-confirm-overlay" id="ufDeleteOverlay" style="z-index:9999;">
    <div class="uf-confirm-box" style="max-width:440px;text-align:left;padding:32px;">

        {{-- Step 1: Warning --}}
        <div id="ufDeleteStep1">
            <div style="text-align:center;margin-bottom:24px;">
                <div style="width:68px;height:68px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;color:#dc2626;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 style="font-size:19px;font-weight:800;color:#1e293b;margin-bottom:8px;">Delete Your Account?</h3>
                <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">
                    This is a permanent action. Before you continue, please understand what will be lost.
                </p>
            </div>

            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:18px;margin-bottom:24px;">
                <div style="font-size:13px;font-weight:700;color:#dc2626;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-circle-xmark"></i> The following will be permanently deleted:
                </div>
                <ul style="margin:0;padding-left:18px;font-size:13px;color:#7f1d1d;line-height:2;">
                    <li>All your orders and order history</li>
                    <li>Saved delivery addresses</li>
                    <li>Wishlist items</li>
                    <li>Cart items</li>
                    <li>Your account profile and login access</li>
                </ul>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" class="uf-btn-keep" style="flex:1;" onclick="ufCloseDeleteModal()">
                    Keep My Account
                </button>
                <button type="button"
                        style="flex:1;padding:12px;background:#dc2626;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;"
                        onclick="ufDeleteNext()">
                    I Understand, Continue
                </button>
            </div>
        </div>

        {{-- Step 2: Final warning checkbox --}}
        <div id="ufDeleteStep2" style="display:none;">
            <div style="text-align:center;margin-bottom:24px;">
                <div style="width:68px;height:68px;background:#fef3c7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;color:#d97706;">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <h3 style="font-size:19px;font-weight:800;color:#1e293b;margin-bottom:8px;">Are You Sure?</h3>
                <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">
                    This action <strong>cannot be undone</strong>. There is no way to recover your account after deletion.
                </p>
            </div>

            <label style="display:flex;align-items:flex-start;gap:10px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:14px;cursor:pointer;margin-bottom:20px;">
                <input type="checkbox" id="ufDeleteConfirmCheck"
                       style="width:16px;height:16px;accent-color:#dc2626;flex-shrink:0;margin-top:2px;">
                <span style="font-size:13px;color:#374151;font-weight:600;line-height:1.5;">
                    I understand that deleting my account is permanent and all my data will be removed with no possibility of recovery.
                </span>
            </label>

            <div style="display:flex;gap:10px;">
                <button type="button" class="uf-btn-keep" style="flex:1;" onclick="ufCloseDeleteModal()">
                    Cancel
                </button>
                <button type="button"
                        style="flex:1;padding:12px;background:#dc2626;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;"
                        onclick="
                            if (!document.getElementById('ufDeleteConfirmCheck').checked) {
                                document.getElementById('ufDeleteConfirmCheck').closest('label').style.borderColor='#dc2626';
                                return;
                            }
                            ufDeleteConfirmNext();
                        ">
                    Yes, Delete My Account
                </button>
            </div>
        </div>

        {{-- Step 3: Password confirmation --}}
        <div id="ufDeleteStep3" style="display:none;">
            <div style="text-align:center;margin-bottom:24px;">
                <div style="width:68px;height:68px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;color:#dc2626;">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 style="font-size:19px;font-weight:800;color:#1e293b;margin-bottom:8px;">Confirm Your Identity</h3>
                <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">
                    Enter your password to permanently delete your account.
                </p>
            </div>

            {{-- ✅ Updated Step 3 — email for Google users, password for regular users --}}
            @if(Auth::user()->isSocialUser())
                <h3 style="font-size:19px;font-weight:800;color:#1e293b;margin-bottom:8px;">Confirm Your Identity</h3>
                <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">
                    Type your email address to confirm permanent deletion.
                </p>

                <div class="uf-form-group" style="margin-top:20px;">
                    <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                        Your Email <span style="color:#ef4444">*</span>
                    </label>
                    <input type="email" class="uf-form-input" id="ufDeleteConfirmEmail"
                        placeholder="{{ Auth::user()->email }}"
                        onkeypress="if(event.key==='Enter') ufExecuteDelete()">
                    <div class="uf-field-error" id="ufErrconfirmemail"></div>
                </div>
            @else
                <h3 style="font-size:19px;font-weight:800;color:#1e293b;margin-bottom:8px;">Confirm Your Identity</h3>
                <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">
                    Enter your password to permanently delete your account.
                </p>

                <div class="uf-form-group" style="margin-top:20px;">
                    <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                        Password <span style="color:#ef4444">*</span>
                    </label>
                    <div class="uf-pw-wrap">
                        <input type="password" class="uf-form-input" id="ufDeletePw"
                            placeholder="Enter your current password"
                            onkeypress="if(event.key==='Enter') ufExecuteDelete()">
                        <button type="button" class="uf-pw-eye" onclick="ufTogglePw('ufDeletePw', this)">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="uf-field-error" id="ufErrdeletepassword"></div>
                </div>
            @endif

            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" class="uf-btn-keep" style="flex:1;" onclick="ufCloseDeleteModal()">
                    Cancel
                </button>
                <button type="button" id="ufBtnConfirmDelete"
                        style="flex:1;padding:12px;background:#dc2626;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:inherit;"
                        onclick="ufExecuteDelete()">
                    <i class="fa-solid fa-trash"></i> Delete Forever
                </button>
            </div>
        </div>

    </div>
</div>

<div class="uf-profile-toast" id="ufProfileToast"></div>
@endsection

@push('scripts')
<script>
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    // ══════════════════════════════════════
    //  TOAST
    // ══════════════════════════════════════
    function ufShowToast(message, type = 'success') {
        const t = document.getElementById('ufProfileToast');
        if (!t) return;
        t.className = `uf-profile-toast is-${type}`;
        t.innerHTML = `<i class="fa-solid fa-${type === 'success' ? 'circle-check' : type === 'error' ? 'circle-xmark' : 'circle-info'}"></i> ${message}`;
        t.classList.add('show');
        clearTimeout(t._t);
        t._t = setTimeout(() => t.classList.remove('show'), 4000);
    }

    // ══════════════════════════════════════
    //  ERRORS
    // ══════════════════════════════════════
    function ufClearErrors() {
        document.querySelectorAll('[id^="ufErr_"]').forEach(el => {
            el.textContent = ''; el.classList.remove('show');
        });
        document.querySelectorAll('.uf-form-input').forEach(el => el.classList.remove('has-error'));
    }

    function ufShowErrors(errors) {
        const fieldMap = {
            name:                  'ufName',
            email:                 'ufEmail',
            mobile:                'ufMobile',
            current_password:      'ufCurrentPw',
            password:              'ufNewPw',
            password_confirmation: 'ufConfirmPw',
            new_email:             'ufNewEmail',
            otp:                   'ufEmailOtp',
            delete_password:       'ufDeletePw',
            confirm_email: 'ufDeleteConfirmEmail',
        };
        Object.entries(errors).forEach(([field, messages]) => {
            const inputId = fieldMap[field];
            const input   = inputId ? document.getElementById(inputId) : null;
            const errEl   = document.getElementById('ufErr_' + field);
            if (input) input.classList.add('has-error');
            if (errEl) { errEl.textContent = messages[0]; errEl.classList.add('show'); }
        });
    }

    document.querySelectorAll('.uf-form-input').forEach(el => {
        el.addEventListener('input', function () { this.classList.remove('has-error'); });
    });

    // ══════════════════════════════════════
    //  SAVE PROFILE (name + mobile only)
    // ══════════════════════════════════════
    window.ufSaveProfile = function () {
        ufClearErrors();
        const btn = document.getElementById('ufBtnSaveProfile');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        fetch('{{ route("account.profile.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({
                _method: 'PUT',
                name:    document.getElementById('ufName').value.trim(),
                mobile:  document.getElementById('ufMobile').value.trim(),
            }),
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Changes';
            if (data.success) {
                document.getElementById('ufHeroName').textContent = data.name;
                ufShowToast(data.message, 'success');
            } else if (data.errors) {
                ufShowErrors(data.errors);
            } else {
                ufShowToast(data.message || 'Failed to save.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Changes';
            ufShowToast('Request failed. Try again.', 'error');
        });
    };

    // ══════════════════════════════════════
    //  EMAIL CHANGE — STEP 1: open modal
    // ══════════════════════════════════════
    window.ufOpenEmailChangeModal = function () {
        document.getElementById('ufEmailChangeOverlay').classList.add('active');
        document.getElementById('ufEmailStep1').style.display = 'block';
        document.getElementById('ufEmailStep2').style.display = 'none';
        document.getElementById('ufNewEmail').value = '';
        document.getElementById('ufEmailOtp').value = '';
        ufClearErrors();
        setTimeout(() => document.getElementById('ufNewEmail').focus(), 200);
    };

    window.ufCloseEmailChangeModal = function () {
        document.getElementById('ufEmailChangeOverlay').classList.remove('active');
    };

    // Step 1: Send OTP
    window.ufSendEmailOtp = function () {
        ufClearErrors();
        const newEmail = document.getElementById('ufNewEmail').value.trim();
        const btn      = document.getElementById('ufBtnSendOtp');

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';

        fetch('{{ route("account.profile.email.otp") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ new_email: newEmail }),
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Code';
            if (data.success) {
                document.getElementById('ufOtpTargetEmail').textContent = newEmail;
                document.getElementById('ufEmailStep1').style.display = 'none';
                document.getElementById('ufEmailStep2').style.display = 'block';
                setTimeout(() => document.getElementById('ufEmailOtp').focus(), 100);
                ufShowToast(data.message, 'success');
                ufStartOtpCountdown();
            } else if (data.errors) {
                ufShowErrors(data.errors);
            } else {
                ufShowToast(data.message || 'Failed to send code.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Code';
            ufShowToast('Request failed.', 'error');
        });
    };

    // OTP countdown resend timer
    let ufOtpTimer = null;
    function ufStartOtpCountdown() {
        let seconds = 60;
        const resendBtn    = document.getElementById('ufBtnResendOtp');
        const countdownEl  = document.getElementById('ufOtpCountdown');
        resendBtn.style.display    = 'none';
        countdownEl.style.display  = 'inline';
        clearInterval(ufOtpTimer);
        ufOtpTimer = setInterval(() => {
            countdownEl.textContent = `Resend in ${seconds}s`;
            seconds--;
            if (seconds < 0) {
                clearInterval(ufOtpTimer);
                countdownEl.style.display = 'none';
                resendBtn.style.display   = 'inline-flex';
            }
        }, 1000);
    }

    window.ufResendEmailOtp = function () {
        document.getElementById('ufEmailStep1').style.display = 'block';
        document.getElementById('ufEmailStep2').style.display = 'none';
    };

    // Step 2: Verify OTP
    window.ufVerifyEmailOtp = function () {
        ufClearErrors();
        const btn = document.getElementById('ufBtnVerifyOtp');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';

        fetch('{{ route("account.profile.email.verify") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ otp: document.getElementById('ufEmailOtp').value.trim() }),
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Verify & Update';
            if (data.success) {
                ufCloseEmailChangeModal();
                document.getElementById('ufHeroEmail').textContent = data.email;
                document.getElementById('ufEmail').value = data.email;
                ufShowToast(data.message, 'success');
                clearInterval(ufOtpTimer);
            } else if (data.errors) {
                ufShowErrors(data.errors);
            } else {
                ufShowToast(data.message || 'Verification failed.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Verify & Update';
            ufShowToast('Request failed.', 'error');
        });
    };

    document.getElementById('ufEmailChangeOverlay').addEventListener('click', function (e) {
        if (e.target === this) ufCloseEmailChangeModal();
    });

    // ══════════════════════════════════════
    //  SAVE PASSWORD
    // ══════════════════════════════════════
    window.ufSavePassword = function () {
        ufClearErrors();
        const btn = document.getElementById('ufBtnSavePw');
        if (!btn) return;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';

        fetch('{{ route("account.profile.password") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({
                _method:               'PUT',
                current_password:      document.getElementById('ufCurrentPw').value,
                password:              document.getElementById('ufNewPw').value,
                password_confirmation: document.getElementById('ufConfirmPw').value,
            }),
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> Update Password';
            if (data.success) {
                ['ufCurrentPw','ufNewPw','ufConfirmPw'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });
                const fill  = document.getElementById('ufStrengthFill');
                const label = document.getElementById('ufStrengthLabel');
                if (fill)  fill.style.width  = '0';
                if (label) label.textContent = '';
                ufShowToast(data.message, 'success');
            } else if (data.errors) {
                ufShowErrors(data.errors);
            } else {
                ufShowToast(data.message || 'Failed to update.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> Update Password';
            ufShowToast('Request failed.', 'error');
        });
    };

    // ══════════════════════════════════════
    //  PASSWORD VISIBILITY
    // ══════════════════════════════════════
    window.ufTogglePw = function (id, btn) {
        const input = document.getElementById(id);
        if (!input) return;
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.querySelector('i').className = isText ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
    };

    // ══════════════════════════════════════
    //  PASSWORD STRENGTH
    // ══════════════════════════════════════
    window.ufCheckStrength = function (value) {
        const fill  = document.getElementById('ufStrengthFill');
        const label = document.getElementById('ufStrengthLabel');
        if (!fill || !label) return;
        let score = 0;
        if (value.length >= 8)           score++;
        if (/[A-Z]/.test(value))         score++;
        if (/[0-9]/.test(value))         score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;
        const levels = [
            { w:'0%',   bg:'transparent', txt:'' },
            { w:'25%',  bg:'#ef4444',     txt:'Weak' },
            { w:'50%',  bg:'#f97316',     txt:'Fair' },
            { w:'75%',  bg:'#eab308',     txt:'Good' },
            { w:'100%', bg:'#22c55e',     txt:'Strong' },
        ];
        const lvl = value.length === 0 ? levels[0] : (levels[score] || levels[1]);
        fill.style.width = lvl.w; fill.style.background = lvl.bg;
        label.textContent = lvl.txt; label.style.color = lvl.bg;
    };

    // ══════════════════════════════════════
    //  AVATAR
    // ══════════════════════════════════════
    window.ufToggleAvatarMenu = function () {
        document.getElementById('ufAvatarMenu')?.classList.toggle('open');
    };
    window.ufCloseAvatarMenu = function () {
        document.getElementById('ufAvatarMenu')?.classList.remove('open');
    };

    document.addEventListener('click', function (e) {
        const wrap = document.getElementById('ufAvatarWrap');
        if (wrap && !wrap.contains(e.target)) ufCloseAvatarMenu();
    });

    const ufAvatarInput = document.getElementById('ufAvatarInput');
    if (ufAvatarInput) {
        ufAvatarInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const fd = new FormData();
            fd.append('avatar', file);
            fd.append('_token', CSRF);
            fetch('{{ route("account.profile.avatar") }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('ufAvatarDisplay').innerHTML = `<img src="${data.avatar}" alt="Avatar">`;
                    ufShowToast(data.message, 'success');
                } else {
                    ufShowToast(data.message || 'Upload failed.', 'error');
                }
            })
            .catch(() => ufShowToast('Upload failed.', 'error'));
            this.value = '';
        });
    }

    window.ufRemoveAvatar = function () {
        ufCloseAvatarMenu();
        fetch('{{ route("account.profile.avatar.remove") }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const initial = '{{ strtoupper(substr($user->name, 0, 1)) }}';
                document.getElementById('ufAvatarDisplay').innerHTML = `<span>${initial}</span>`;
                ufShowToast(data.message, 'success');
            }
        })
        .catch(() => ufShowToast('Failed to remove photo.', 'error'));
    };

    // ══════════════════════════════════════
    //  DELETE ACCOUNT — multi-step
    // ══════════════════════════════════════
    window.ufConfirmDelete = function () {
        document.getElementById('ufDeleteOverlay').classList.add('active');
        document.getElementById('ufDeleteStep1').style.display = 'block';
        document.getElementById('ufDeleteStep2').style.display = 'none';
        document.getElementById('ufDeleteStep3').style.display = 'none';
    };

    // Step 1 → Step 2 (understand consequences)
    window.ufDeleteNext = function () {
        document.getElementById('ufDeleteStep1').style.display = 'none';
        document.getElementById('ufDeleteStep2').style.display = 'block';
    };

    // Step 2 → Step 3 (enter password)
    window.ufDeleteConfirmNext = function () {
        document.getElementById('ufDeleteStep2').style.display = 'none';
        document.getElementById('ufDeleteStep3').style.display = 'block';
        setTimeout(() => document.getElementById('ufDeletePw')?.focus(), 100);
    };

    window.ufCloseDeleteModal = function () {
        document.getElementById('ufDeleteOverlay').classList.remove('active');
        const pwInput = document.getElementById('ufDeletePw');
        if (pwInput) pwInput.value = '';
        const errEl = document.getElementById('ufErr_delete_password');
        if (errEl) { errEl.textContent = ''; errEl.classList.remove('show'); }
    };

    window.ufExecuteDelete = function () {
        ufClearErrors();
        const btn = document.getElementById('ufBtnConfirmDelete');
        const isSocial = {{ Auth::user()->isSocialUser() ? 'true' : 'false' }};

        let payload = {};

        if (isSocial) {
            const confirmEmail = document.getElementById('ufDeleteConfirmEmail')?.value?.trim();
            if (!confirmEmail) {
                const errEl = document.getElementById('ufErrconfirmemail');
                if (errEl) { errEl.textContent = 'Please enter your email address.'; errEl.classList.add('show'); }
                document.getElementById('ufDeleteConfirmEmail')?.classList.add('has-error');
                return;
            }
            payload = { confirm_email: confirmEmail };
        } else {
            const password = document.getElementById('ufDeletePw')?.value?.trim();
            if (!password) {
                const errEl = document.getElementById('ufErrdeletepassword');
                if (errEl) { errEl.textContent = 'Please enter your password.'; errEl.classList.add('show'); }
                document.getElementById('ufDeletePw')?.classList.add('has-error');
                return;
            }
            payload = { password };
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';

        fetch('{{ route("account.profile.delete") }}', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> Delete Forever';
            if (data.success) {
                ufShowToast('Account deleted. Redirecting...', 'info');
                setTimeout(() => window.location.href = data.redirect, 1500);
            } else if (data.errors) {
                ufShowErrors(data.errors);
            } else {
                ufShowToast(data.message || 'Failed.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> Delete Forever';
            ufShowToast('Request failed. Try again.', 'error');
        });
    };

    document.getElementById('ufDeleteOverlay').addEventListener('click', function (e) {
        if (e.target === this) ufCloseDeleteModal();
    });

    // Global Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            ufCloseAvatarMenu();
            ufCloseDeleteModal();
            ufCloseEmailChangeModal();
        }
    });

})();
</script>
@endpush
