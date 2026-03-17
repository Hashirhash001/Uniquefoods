@extends('frontend.layouts.app')

@section('title', 'My Addresses')

@push('styles')
<style>
    /* ══════════════════════════════════════
       PAGE WRAPPER
    ══════════════════════════════════════ */

    button {
        width: unset;
    }

    .addr-page-wrapper {
        padding: 60px 0 80px;
        background: linear-gradient(135deg, #f8fafc 0%, #e8f0fe 100%);
        min-height: calc(100vh - var(--header-height, 140px));
    }

    .addr-page-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .addr-back-btn {
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

    .addr-back-btn:hover { gap: 12px; color: #08437b; }

    /* ── Page Header ── */
    .addr-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .addr-page-title {
        font-size: 26px;
        font-weight: 800;
        color: #0f508d;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .addr-count-badge {
        font-size: 12px;
        background: #dbeafe;
        color: #1e40af;
        padding: 3px 10px;
        border-radius: 99px;
        font-weight: 700;
    }

    .btn-add-address {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0f508d 0%, #08437b 100%);
        color: white !important;
        border: none;
        padding: 11px 22px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-add-address:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(15,80,141,0.3);
    }

    .btn-add-address:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ══════════════════════════════════════
       ADDRESS GRID
    ══════════════════════════════════════ */
    .addr-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 20px;
    }

    .addr-card {
        background: white;
        border-radius: 14px;
        border: 2px solid #e2e8f0;
        padding: 22px;
        position: relative;
        transition: all 0.25s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .addr-card.is-default {
        border-color: #0f508d;
        box-shadow: 0 0 0 3px rgba(15,80,141,0.1), 0 4px 12px rgba(15,80,141,0.08);
    }

    .addr-card:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,0.09);
        transform: translateY(-2px);
    }

    /* Card top row */
    .addr-card-top {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .addr-label-chip {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #0f508d;
        color: white;
        padding: 3px 10px;
        border-radius: 5px;
    }

    .addr-default-chip {
        font-size: 11px;
        font-weight: 700;
        color: #065f46;
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        padding: 3px 10px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .addr-card-actions {
        margin-left: auto;
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }

    .addr-action-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: all 0.2s;
    }

    .btn-edit-addr   { background: #eff6ff; color: #1e40af; }
    .btn-edit-addr:hover   { background: #dbeafe; }
    .btn-delete-addr { background: #fef2f2; color: #dc2626; }
    .btn-delete-addr:hover { background: #fee2e2; }

    /* Card body */
    .addr-recipient {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .addr-lines {
        font-size: 14px;
        color: #475569;
        line-height: 1.7;
        margin-bottom: 8px;
    }

    .addr-restaurant {
        font-size: 13px;
        color: #0f508d;
        font-weight: 600;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .addr-phone {
        font-size: 13px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-set-default {
        margin-top: 16px;
        width: 100%;
        padding: 9px;
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-set-default:hover {
        background: #eff6ff;
        border-color: #93c5fd;
        color: #0f508d;
    }

    /* Empty state */
    .addr-empty {
        text-align: center;
        padding: 70px 20px;
        background: white;
        border-radius: 16px;
        border: 2px dashed #e2e8f0;
    }

    .addr-empty-icon {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 30px;
        color: #94a3b8;
    }

    .addr-empty h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .addr-empty p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 24px;
    }

    /* Max limit notice */
    .addr-limit-notice {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 12px 18px;
        font-size: 13px;
        color: #92400e;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    /* ══════════════════════════════════════
       MODAL
    ══════════════════════════════════════ */
    .addr-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }

    .addr-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .addr-modal {
        background: white;
        border-radius: 18px;
        padding: 32px;
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 24px 60px rgba(0,0,0,0.18);
        transform: scale(0.93) translateY(12px);
        transition: transform 0.25s ease;
    }

    .addr-modal-overlay.active .addr-modal {
        transform: scale(1) translateY(0);
    }

    .addr-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .addr-modal-header h2 {
        font-size: 20px;
        font-weight: 800;
        color: #0f508d;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .addr-modal-close {
        width: 34px;
        height: 34px;
        border: none;
        background: #f1f5f9;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #64748b;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .addr-modal-close:hover { background: #fee2e2; color: #dc2626; }

    /* Form fields inside modal */
    .addr-form-group {
        margin-bottom: 16px;
    }

    .addr-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .addr-form-group label .req {
        color: #ef4444;
        margin-left: 2px;
    }

    .addr-form-group label .opt {
        color: #94a3b8;
        font-weight: 400;
        font-size: 12px;
        margin-left: 4px;
    }

    .addr-form-control {
        width: 100% !important;
        padding: 10px 14px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        color: #1e293b !important;
        transition: border-color 0.2s, box-shadow 0.2s !important;
        background: #fff !important;
        outline: none !important;
        box-sizing: border-box !important;
    }

    .addr-form-control:focus {
        border-color: #0f508d;
        box-shadow: 0 0 0 3px rgba(15,80,141,0.1);
    }

    .addr-form-control.has-error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
    }

    .addr-field-error {
        font-size: 12px;
        color: #ef4444;
        margin-top: 4px;
        display: none;
    }

    .addr-field-error.show { display: block; }

    .addr-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .addr-default-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .addr-default-toggle input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #0f508d;
        cursor: pointer;
        flex-shrink: 0;
    }

    .addr-modal-footer {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 2px solid #f1f5f9;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 12px;
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-cancel:hover { background: #e2e8f0; }

    .btn-modal-save {
        flex: 2;
        padding: 12px;
        background: linear-gradient(135deg, #0f508d 0%, #08437b 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-modal-save:hover { box-shadow: 0 4px 14px rgba(15,80,141,0.3); }
    .btn-modal-save:disabled { opacity: 0.6; cursor: not-allowed; box-shadow: none; }

    /* ══════════════════════════════════════
       DELETE CONFIRM MODAL
    ══════════════════════════════════════ */
    .addr-confirm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
    }

    .addr-confirm-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .addr-confirm-box {
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

    .addr-confirm-overlay.active .addr-confirm-box {
        transform: scale(1) translateY(0);
    }

    .addr-confirm-icon {
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

    .addr-confirm-box h3 {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .addr-confirm-box p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .addr-confirm-btns {
        display: flex;
        gap: 10px;
    }

    .btn-confirm-keep {
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
    }

    .btn-confirm-keep:hover { background: #e2e8f0; }

    .btn-confirm-delete {
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
    }

    .btn-confirm-delete:hover { background: #b91c1c; }
    .btn-confirm-delete:disabled { opacity: 0.6; cursor: not-allowed; }

    /* ══════════════════════════════════════
       TOAST
    ══════════════════════════════════════ */
    .addr-toast {
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

    .addr-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .addr-toast.toast-success { background: #065f46; }
    .addr-toast.toast-error   { background: #991b1b; }

    /* ══════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════ */
    @media (max-width: 767px) {
        .addr-page-wrapper  { padding: 24px 0 80px; }
        .addr-page-title    { font-size: 20px; }
        .addr-grid          { grid-template-columns: 1fr; }
        .addr-form-row      { grid-template-columns: 1fr; }
        .addr-modal         { padding: 22px 18px; }
        .addr-confirm-box   { padding: 24px 18px 20px; }
        .addr-modal-footer  { flex-direction: column; }
        .btn-modal-save, .btn-modal-cancel { flex: none; width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="addr-page-wrapper">
    <div class="addr-page-container">

        <a href="{{ route('orders.index') }}" class="addr-back-btn">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>

        <div class="addr-page-header">
            <h1 class="addr-page-title">
                <i class="fa-solid fa-address-book"></i>
                My Addresses
                <span class="addr-count-badge" id="addrCountBadge">
                    {{ $addresses->count() }} / 5
                </span>
            </h1>

            <button
                class="btn-add-address"
                id="btnOpenAddModal"
                {{ $addresses->count() >= 5 ? 'disabled' : '' }}
                onclick="openAddressModal()">
                <i class="fa-solid fa-plus"></i> Add New Address
            </button>
        </div>

        {{-- Max limit notice --}}
        @if($addresses->count() >= 5)
        <div class="addr-limit-notice">
            <i class="fa-solid fa-circle-info"></i>
            You've reached the 5-address limit. Remove an address to add a new one.
        </div>
        @endif

        {{-- Address Grid --}}
        <div class="addr-grid" id="addrGrid">

            @forelse($addresses as $addr)
            <div class="addr-card {{ $addr->is_default ? 'is-default' : '' }}"
                 id="addr-card-{{ $addr->id }}"
                 data-id="{{ $addr->id }}">

                <div class="addr-card-top">
                    @if($addr->label)
                        <span class="addr-label-chip">{{ $addr->label }}</span>
                    @endif
                    @if($addr->is_default)
                        <span class="addr-default-chip" id="defaultChip-{{ $addr->id }}">
                            <i class="fa-solid fa-circle-check"></i> Default
                        </span>
                    @endif
                    <div class="addr-card-actions">
                        <button class="addr-action-btn btn-edit-addr"
                                onclick="openEditModal({{ $addr->id }})"
                                title="Edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button class="addr-action-btn btn-delete-addr"
                                onclick="confirmDelete({{ $addr->id }})"
                                title="Delete">
                            <i class="fa-regular fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="addr-recipient">{{ $addr->recipient_name }}</div>

                <div class="addr-lines">
                    {{ $addr->address_line1 }}
                    @if($addr->address_line2)<br>{{ $addr->address_line2 }}@endif
                    <br>{{ $addr->city }}@if($addr->county), {{ $addr->county }}@endif
                    <br>{{ $addr->postcode }}, {{ $addr->country }}
                </div>

                @if($addr->restaurant_store)
                <div class="addr-restaurant">
                    <i class="fa-regular fa-store"></i> {{ $addr->restaurant_store }}
                </div>
                @endif

                <div class="addr-phone">
                    <i class="fa-regular fa-phone"></i> {{ $addr->phone }}
                </div>

                @if(!$addr->is_default)
                <button class="btn-set-default"
                        onclick="setDefault({{ $addr->id }}, this)">
                    <i class="fa-regular fa-circle-check"></i> Set as Default
                </button>
                @endif

            </div>
            @empty
            <div class="addr-empty" id="addrEmptyState" style="grid-column: 1 / -1;">
                <div class="addr-empty-icon">
                    <i class="fa-regular fa-location-dot"></i>
                </div>
                <h3>No saved addresses</h3>
            </div>
            @endforelse

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     ADD / EDIT MODAL
══════════════════════════════════════ --}}
<div class="addr-modal-overlay" id="addrModalOverlay">
    <div class="addr-modal" role="dialog" aria-modal="true" aria-labelledby="addrModalTitle">

        <div class="addr-modal-header">
            <h2 id="addrModalTitle">
                <i class="fa-solid fa-location-dot"></i>
                <span id="addrModalTitleText">Add New Address</span>
            </h2>
            <button class="addr-modal-close" onclick="closeAddressModal()" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="addrForm" autocomplete="off" onsubmit="return false;">
            <input type="hidden" id="addrFormId">

            <div class="addr-form-row">
                <div class="addr-form-group">
                    <label>Label <span class="opt">(e.g. Home, Work)</span></label>
                    <input type="text" class="addr-form-control" id="f_label" placeholder="e.g. Home">
                </div>
                <div class="addr-form-group">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" class="addr-form-control" id="f_recipient_name" placeholder="Recipient name">
                    <div class="addr-field-error" id="err_recipient_name"></div>
                </div>
            </div>

            <div class="addr-form-group">
                <label>Phone Number <span class="req">*</span></label>
                <input type="tel" class="addr-form-control" id="f_phone" placeholder="+44 7XXX XXXXXX">
                <div class="addr-field-error" id="err_phone"></div>
            </div>

            <div class="addr-form-group">
                <label>Address Line 1 <span class="req">*</span></label>
                <input type="text" class="addr-form-control" id="f_address_line1" placeholder="House number and street name">
                <div class="addr-field-error" id="err_address_line1"></div>
            </div>

            <div class="addr-form-group">
                <label>Address Line 2 <span class="opt">(Optional)</span></label>
                <input type="text" class="addr-form-control" id="f_address_line2" placeholder="Flat, floor, etc.">
            </div>

            <div class="addr-form-group">
                <label>Restaurant / Store <span class="opt">(Optional)</span></label>
                <input type="text" class="addr-form-control" id="f_restaurant_store" placeholder="e.g. The Spice Garden">
            </div>

            <div class="addr-form-row">
                <div class="addr-form-group">
                    <label>Town / City <span class="req">*</span></label>
                    <input type="text" class="addr-form-control" id="f_city" placeholder="e.g. London">
                    <div class="addr-field-error" id="err_city"></div>
                </div>
                <div class="addr-form-group">
                    <label>County <span class="opt">(Optional)</span></label>
                    <input type="text" class="addr-form-control" id="f_county" placeholder="e.g. Greater London">
                </div>
            </div>

            <div class="addr-form-group">
                <label>Postcode <span class="req">*</span></label>
                <input type="text" class="addr-form-control" id="f_postcode"
                       placeholder="e.g. SW1A 1AA"
                       style="text-transform:uppercase;" maxlength="8">
                <div class="addr-field-error" id="err_postcode"></div>
            </div>

            <div class="addr-form-group">
                <label class="addr-default-toggle">
                    <input type="checkbox" id="f_is_default">
                    Set as default delivery address
                </label>
            </div>

            <div class="addr-modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeAddressModal()">
                    Cancel
                </button>
                <button type="submit" class="btn-modal-save" id="addrSaveBtn" onclick="saveAddress()">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="addrSaveBtnText">Save Address</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════════════ --}}
<div class="addr-confirm-overlay" id="addrConfirmOverlay">
    <div class="addr-confirm-box">
        <div class="addr-confirm-icon">
            <i class="fa-solid fa-trash"></i>
        </div>
        <h3>Remove this address?</h3>
        <p>This address will be permanently removed from your account and cannot be recovered.</p>
        <div class="addr-confirm-btns">
            <button class="btn-confirm-keep" onclick="closeConfirm()">
                Keep it
            </button>
            <button class="btn-confirm-delete" id="confirmDeleteBtn" onclick="executeDelete()">
                <i class="fa-solid fa-trash"></i> Remove
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="addr-toast" id="addrToast"></div>

@endsection

@push('scripts')
<script>
(function () {

    const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
    const BASE   = '/account/addresses';

    // In-memory address store — avoids re-fetching for edit
    let addressStore = @json($addresses->keyBy('id'));
    let deleteTargetId = null;

    // ══════════════════════════════════════
    //  TOAST
    // ══════════════════════════════════════
    function showToast(message, type = 'success') {
        const toast = document.getElementById('addrToast');
        toast.className = `addr-toast toast-${type}`;
        toast.innerHTML = `<i class="fa-solid fa-${type === 'success' ? 'circle-check' : 'circle-xmark'}"></i> ${message}`;
        toast.classList.add('show');
        clearTimeout(toast._timer);
        toast._timer = setTimeout(() => toast.classList.remove('show'), 3500);
    }

    // ══════════════════════════════════════
    //  MODAL OPEN / CLOSE
    // ══════════════════════════════════════
    window.openAddressModal = function () {
        clearForm();
        document.getElementById('addrModalTitleText').textContent = 'Add New Address';
        document.getElementById('addrSaveBtnText').textContent     = 'Save Address';
        document.getElementById('addrFormId').value = '';
        document.getElementById('addrModalOverlay').classList.add('active');
        document.getElementById('f_recipient_name').focus();
    };

    window.openEditModal = function (id) {
        const addr = addressStore[id];
        if (!addr) return;

        clearForm();
        document.getElementById('addrModalTitleText').textContent = 'Edit Address';
        document.getElementById('addrSaveBtnText').textContent     = 'Update Address';
        document.getElementById('addrFormId').value = id;

        document.getElementById('f_label').value            = addr.label            || '';
        document.getElementById('f_recipient_name').value   = addr.recipient_name   || '';
        document.getElementById('f_phone').value            = addr.phone            || '';
        document.getElementById('f_address_line1').value    = addr.address_line1    || '';
        document.getElementById('f_address_line2').value    = addr.address_line2    || '';
        document.getElementById('f_restaurant_store').value = addr.restaurant_store || '';
        document.getElementById('f_city').value             = addr.city             || '';
        document.getElementById('f_county').value           = addr.county           || '';
        document.getElementById('f_postcode').value         = addr.postcode         || '';
        document.getElementById('f_is_default').checked     = !!addr.is_default;

        document.getElementById('addrModalOverlay').classList.add('active');
        document.getElementById('f_recipient_name').focus();
    };

    window.closeAddressModal = function () {
        document.getElementById('addrModalOverlay').classList.remove('active');
    };

    // Close on overlay click
    document.getElementById('addrModalOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeAddressModal();
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAddressModal();
            closeConfirm();
        }
    });

    function clearForm() {
        ['f_label','f_recipient_name','f_phone','f_address_line1',
         'f_address_line2','f_restaurant_store','f_city','f_county','f_postcode']
            .forEach(id => { document.getElementById(id).value = ''; });
        document.getElementById('f_is_default').checked = false;
        clearErrors();
    }

    function clearErrors() {
        document.querySelectorAll('.addr-field-error').forEach(el => {
            el.textContent = '';
            el.classList.remove('show');
        });
        document.querySelectorAll('.addr-form-control').forEach(el => {
            el.classList.remove('has-error');
        });
    }

    function showErrors(errors) {
        Object.entries(errors).forEach(([field, messages]) => {
            const input = document.getElementById('f_' + field);
            const errEl = document.getElementById('err_' + field);
            if (input) input.classList.add('has-error');
            if (errEl) {
                errEl.textContent = messages[0];
                errEl.classList.add('show');
            }
        });
    }

    // Clear error on input
    document.querySelectorAll('.addr-form-control').forEach(input => {
        input.addEventListener('input', function () {
            this.classList.remove('has-error');
            const errEl = document.getElementById('err_' + this.id.replace('f_', ''));
            if (errEl) { errEl.textContent = ''; errEl.classList.remove('show'); }
        });
    });

    // ══════════════════════════════════════
    //  SAVE / UPDATE
    // ══════════════════════════════════════
    window.saveAddress = function () {
        clearErrors();

        const id   = document.getElementById('addrFormId').value;
        const isEdit = !!id;

        const payload = {
            label:            document.getElementById('f_label').value.trim(),
            recipient_name:   document.getElementById('f_recipient_name').value.trim(),
            phone:            document.getElementById('f_phone').value.trim(),
            address_line1:    document.getElementById('f_address_line1').value.trim(),
            address_line2:    document.getElementById('f_address_line2').value.trim(),
            restaurant_store: document.getElementById('f_restaurant_store').value.trim(),
            city:             document.getElementById('f_city').value.trim(),
            county:           document.getElementById('f_county').value.trim(),
            postcode:         document.getElementById('f_postcode').value.trim().toUpperCase(),
            is_default:       document.getElementById('f_is_default').checked ? 1 : 0,
            _token:           CSRF,
        };

        if (isEdit) payload['_method'] = 'PUT';

        const url    = isEdit ? `${BASE}/${id}` : BASE;
        const saveBtn = document.getElementById('addrSaveBtn');
        saveBtn.disabled = true;
        document.getElementById('addrSaveBtnText').textContent = 'Saving...';
        saveBtn.querySelector('i').className = 'fa-solid fa-spinner fa-spin';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            saveBtn.disabled = false;
            document.getElementById('addrSaveBtnText').textContent = isEdit ? 'Update Address' : 'Save Address';
            saveBtn.querySelector('i').className = 'fa-solid fa-floppy-disk';

            if (data.success) {
                closeAddressModal();
                showToast(data.message, 'success');
                isEdit ? updateCardInDOM(data.address) : addCardToDOM(data.address);
                updateCountBadge();
            } else if (data.errors) {
                showErrors(data.errors);
            } else {
                showToast(data.message || 'Something went wrong.', 'error');
            }
        })
        .catch(() => {
            saveBtn.disabled = false;
            document.getElementById('addrSaveBtnText').textContent = isEdit ? 'Update Address' : 'Save Address';
            saveBtn.querySelector('i').className = 'fa-solid fa-floppy-disk';
            showToast('Request failed. Please try again.', 'error');
        });
    };

    // ══════════════════════════════════════
    //  SET DEFAULT
    // ══════════════════════════════════════
    window.setDefault = function (id, btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';

        fetch(`${BASE}/${id}/default`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Remove default styling from all cards
                document.querySelectorAll('.addr-card').forEach(card => {
                    card.classList.remove('is-default');
                    const chip = card.querySelector('.addr-default-chip');
                    if (chip) chip.remove();
                    const existingSetBtn = card.querySelector('.btn-set-default');
                    if (!existingSetBtn) {
                        const newBtn = document.createElement('button');
                        newBtn.className = 'btn-set-default';
                        newBtn.innerHTML = '<i class="fa-regular fa-circle-check"></i> Set as Default';
                        const cardId = card.dataset.id;
                        newBtn.setAttribute('onclick', `setDefault(${cardId}, this)`);
                        card.appendChild(newBtn);
                    }
                    // Update store
                    if (addressStore[card.dataset.id]) {
                        addressStore[card.dataset.id].is_default = false;
                    }
                });

                // Apply to the selected card
                const card = document.getElementById(`addr-card-${id}`);
                if (card) {
                    card.classList.add('is-default');
                    // Inject default chip after label chip or at top
                    const top = card.querySelector('.addr-card-top');
                    const chip = document.createElement('span');
                    chip.className = 'addr-default-chip';
                    chip.id = `defaultChip-${id}`;
                    chip.innerHTML = '<i class="fa-solid fa-circle-check"></i> Default';
                    top.insertBefore(chip, top.querySelector('.addr-card-actions'));
                    // Remove "Set as Default" button
                    btn.remove();
                    if (addressStore[id]) addressStore[id].is_default = true;
                }

                showToast(data.message, 'success');
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-regular fa-circle-check"></i> Set as Default';
                showToast(data.message || 'Failed to update default.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-regular fa-circle-check"></i> Set as Default';
            showToast('Request failed.', 'error');
        });
    };

    // ══════════════════════════════════════
    //  DELETE
    // ══════════════════════════════════════
    window.confirmDelete = function (id) {
        deleteTargetId = id;
        document.getElementById('addrConfirmOverlay').classList.add('active');
    };

    window.closeConfirm = function () {
        document.getElementById('addrConfirmOverlay').classList.remove('active');
        deleteTargetId = null;
    };

    document.getElementById('addrConfirmOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeConfirm();
    });

    window.executeDelete = function () {
        const id  = deleteTargetId;
        const btn = document.getElementById('confirmDeleteBtn');
        if (!id) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Removing...';

        fetch(`${BASE}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> Remove';
            closeConfirm();

            if (data.success) {
                const card = document.getElementById(`addr-card-${id}`);
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity    = '0';
                    card.style.transform  = 'scale(0.95)';
                    setTimeout(() => {
                        card.remove();
                        delete addressStore[id];
                        updateCountBadge();

                        // Show empty state if no addresses left
                        const remaining = document.querySelectorAll('.addr-card').length;
                        if (remaining === 0) {
                            document.getElementById('addrGrid').innerHTML = `
                                <div class="addr-empty" style="grid-column:1/-1">
                                    <div class="addr-empty-icon"><i class="fa-regular fa-location-dot"></i></div>
                                    <h3>No saved addresses</h3>
                                    <p>Add your delivery addresses to make checkout faster.</p>
                                </div>`;
                        }
                    }, 300);
                }
                showToast('Address removed.', 'success');
            } else {
                showToast(data.message || 'Failed to remove address.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> Remove';
            closeConfirm();
            showToast('Request failed.', 'error');
        });
    };

    // ══════════════════════════════════════
    //  DOM HELPERS
    // ══════════════════════════════════════
    function buildCardHTML(addr) {
        const labelChip    = addr.label
            ? `<span class="addr-label-chip">${addr.label}</span>` : '';
        const defaultChip  = addr.is_default
            ? `<span class="addr-default-chip" id="defaultChip-${addr.id}">
                <i class="fa-solid fa-circle-check"></i> Default
               </span>` : '';
        const line2        = addr.address_line2 ? `<br>${addr.address_line2}` : '';
        const county       = addr.county        ? `, ${addr.county}` : '';
        const restaurant   = addr.restaurant_store
            ? `<div class="addr-restaurant"><i class="fa-regular fa-store"></i> ${addr.restaurant_store}</div>` : '';
        const setDefaultBtn = !addr.is_default
            ? `<button class="btn-set-default" onclick="setDefault(${addr.id}, this)">
                <i class="fa-regular fa-circle-check"></i> Set as Default
               </button>` : '';

        return `
            <div class="addr-card ${addr.is_default ? 'is-default' : ''}"
                 id="addr-card-${addr.id}" data-id="${addr.id}">
                <div class="addr-card-top">
                    ${labelChip}
                    ${defaultChip}
                    <div class="addr-card-actions">
                        <button class="addr-action-btn btn-edit-addr"
                                onclick="openEditModal(${addr.id})" title="Edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button class="addr-action-btn btn-delete-addr"
                                onclick="confirmDelete(${addr.id})" title="Delete">
                            <i class="fa-regular fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="addr-recipient">${addr.recipient_name}</div>
                <div class="addr-lines">
                    ${addr.address_line1}${line2}<br>
                    ${addr.city}${county}<br>
                    ${addr.postcode}, ${addr.country}
                </div>
                ${restaurant}
                <div class="addr-phone">
                    <i class="fa-regular fa-phone"></i> ${addr.phone}
                </div>
                ${setDefaultBtn}
            </div>`;
    }

    function addCardToDOM(addr) {
        // Remove empty state if present
        const empty = document.querySelector('.addr-empty');
        if (empty) empty.remove();

        addressStore[addr.id] = addr;

        // If new address is default, strip default from others
        if (addr.is_default) stripDefaultFromAll();

        const div = document.createElement('div');
        div.innerHTML = buildCardHTML(addr).trim();
        const card = div.firstChild;
        card.style.opacity   = '0';
        card.style.transform = 'scale(0.95)';
        document.getElementById('addrGrid').appendChild(card);
        requestAnimationFrame(() => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity    = '1';
            card.style.transform  = 'scale(1)';
        });
    }

    function updateCardInDOM(addr) {
        addressStore[addr.id] = addr;
        if (addr.is_default) stripDefaultFromAll(addr.id);

        const existing = document.getElementById(`addr-card-${addr.id}`);
        if (!existing) return;

        const div = document.createElement('div');
        div.innerHTML = buildCardHTML(addr).trim();
        const newCard = div.firstChild;
        existing.replaceWith(newCard);
    }

    function stripDefaultFromAll(exceptId = null) {
        document.querySelectorAll('.addr-card').forEach(card => {
            const cid = card.dataset.id;
            if (String(cid) === String(exceptId)) return;
            card.classList.remove('is-default');
            const chip = card.querySelector('.addr-default-chip');
            if (chip) chip.remove();
            if (addressStore[cid]) addressStore[cid].is_default = false;
            // Add "Set as Default" btn if missing
            if (!card.querySelector('.btn-set-default')) {
                const btn = document.createElement('button');
                btn.className = 'btn-set-default';
                btn.innerHTML = '<i class="fa-regular fa-circle-check"></i> Set as Default';
                btn.setAttribute('onclick', `setDefault(${cid}, this)`);
                card.appendChild(btn);
            }
        });
    }

    function updateCountBadge() {
        const count   = document.querySelectorAll('.addr-card').length;
        const badge   = document.getElementById('addrCountBadge');
        const addBtn  = document.getElementById('btnOpenAddModal');
        if (badge)  badge.textContent = `${count} / 5`;
        if (addBtn) addBtn.disabled = count >= 5;

        // Show/hide limit notice
        let notice = document.querySelector('.addr-limit-notice');
        if (count >= 5 && !notice) {
            notice = document.createElement('div');
            notice.className = 'addr-limit-notice';
            notice.innerHTML = `<i class="fa-solid fa-circle-info"></i>
                You've reached the 5-address limit. Remove an address to add a new one.`;
            document.querySelector('.addr-page-header').after(notice);
        } else if (count < 5 && notice) {
            notice.remove();
        }
    }

})();
</script>
@endpush
