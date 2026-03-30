@extends('admin.layouts.app')

@section('title', 'Shipping Settings')

@push('styles')
<style>
    * { box-sizing: border-box; }

    .page-header { margin-bottom: 1.25rem; }
    .breadcrumb { background: transparent; padding: 0; margin: 0; font-size: 14px; }
    .breadcrumb-item { color: #6b7280; }
    .breadcrumb-item a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .breadcrumb-item a:hover { color: #08437b; }
    .breadcrumb-item.active { color: #111827; font-weight: 500; }
    .breadcrumb-item + .breadcrumb-item::before { color: #d1d5db; }

    /* ── Cards ── */
    .shipping-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }
    .shipping-card .card-header {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
        border-radius: 8px 8px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .shipping-card .card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #111827;
    }
    .shipping-card .card-body { padding: 1.5rem; }

    /* ── Mode Cards ── */
    .mode-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .mode-card {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        background: white;
        user-select: none;
    }
    .mode-card:hover { border-color: #08437b; background: #f0f9ff; }
    .mode-card.selected {
        border-color: #08437b;
        background: linear-gradient(135deg, #f0f9ff 0%, #e8f4fd 100%);
    }
    .mode-card input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }
    .mode-card .mode-icon { font-size: 26px; margin-bottom: 0.625rem; display: block; }
    .mode-card .mode-title { font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 0.3rem; }
    .mode-card .mode-desc { font-size: 12px; color: #6b7280; line-height: 1.5; }

    .mode-check {
        position: absolute;
        top: 10px; right: 10px;
        width: 18px; height: 18px;
        border-radius: 50%;
        border: 2px solid #d1d5db;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .mode-card.selected .mode-check { background: #08437b; border-color: #08437b; }
    .mode-card.selected .mode-check::after {
        content: '';
        width: 6px; height: 6px;
        background: white;
        border-radius: 50%;
        display: block;
    }

    /* ── Panels ── */
    .settings-panel { display: none; animation: fadeIn 0.2s ease; }
    .settings-panel.active { display: block; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Form ── */
    .form-label { font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 0.5rem; display: block; }
    .form-control, .form-select {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.625rem 0.875rem;
        font-size: 14px;
        color: #111827;
        transition: all 0.2s;
        height: 42px;
        width: 100%;
        background: white;
    }
    .form-control:focus, .form-select:focus {
        border-color: #08437b;
        box-shadow: 0 0 0 3px rgba(8,67,123,0.1);
        outline: none;
    }
    .input-group { display: flex; width: 100%; }
    .input-group-text {
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-right: none;
        color: #6b7280;
        font-weight: 500;
        font-size: 14px;
        padding: 0.625rem 0.875rem;
        display: flex;
        align-items: center;
        border-radius: 6px 0 0 6px;
        white-space: nowrap;
    }
    .input-group-text.suffix {
        border-left: none;
        border-right: 1px solid #d1d5db;
        border-radius: 0 6px 6px 0;
    }
    .input-group .form-control { border-left: none; border-radius: 0 6px 6px 0; }
    .input-group .form-control:focus { border-left: 1px solid #08437b; }
    .form-text { font-size: 12px; color: #6b7280; margin-top: 0.25rem; display: block; }

    /* ── Preview Box ── */
    .preview-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1.25rem;
        height: 100%;
    }
    .preview-box h6 {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .preview-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.4rem 0;
        border-bottom: 1px dashed #e5e7eb;
        font-size: 13px;
    }
    .preview-row:last-child { border-bottom: none; }
    .preview-row .label { color: #6b7280; }
    .preview-row .value { font-weight: 600; color: #111827; }
    .preview-row .value.free { color: #16a34a; }
    .preview-row .value.charged { color: #08437b; }
    .preview-row .value.rejected { color: #ef4444; }

    /* ── Section Divider ── */
    .section-divider {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 1.25rem 0 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-divider::after { content: ''; flex: 1; height: 1px; background: #e5e7eb; }

    /* ── Info Alert ── */
    .info-alert {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        display: flex;
        gap: 0.75rem;
        font-size: 13px;
        color: #1e40af;
        line-height: 1.6;
    }
    .info-alert i { flex-shrink: 0; margin-top: 2px; }
    .info-alert.success { background: #f0fdf4; border-color: #bbf7d0; color: #16a34a; }

    /* ── Badge ── */
    .mode-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        background: #dcfce7;
        color: #16a34a;
    }
    .mode-status-badge i { font-size: 8px; }

    /* ── Buttons ── */
    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary { background: #08437b; color: white; }
    .btn-primary:hover { background: #0f508d; box-shadow: 0 2px 8px rgba(8,67,123,0.25); }
    .btn-outline-secondary { background: white; border: 1px solid #d1d5db; color: #374151; }
    .btn-outline-secondary:hover { background: #f9fafb; border-color: #9ca3af; }

    @media (max-width: 992px) { .mode-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) {
        .mode-grid { grid-template-columns: 1fr; }
        .shipping-card .card-body { padding: 1rem; }
    }
</style>
@endpush

@section('content')

@php $currentMode = $settings->get('mode')?->value ?? 'free'; @endphp

{{-- Breadcrumb --}}
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Shipping Settings</li>
        </ol>
    </nav>
</div>

<form id="shippingForm">
@csrf

{{-- Hidden real fields — populated by JS before submit --}}
<input type="hidden" name="free_threshold"     id="h_free_threshold">
<input type="hidden" name="base_rate"          id="h_base_rate">
<input type="hidden" name="rate_per_mile"      id="h_rate_per_mile">
<input type="hidden" name="max_delivery_miles" id="h_max_delivery_miles">
<input type="hidden" name="store_postcode"     id="h_store_postcode">

{{-- ══════════════════════════════════════════
     CARD 1 — Delivery Mode Selector
══════════════════════════════════════════ --}}
<div class="shipping-card">
    <div class="card-header">
        <h5><i class="fas fa-truck me-2" style="color:#08437b"></i>Delivery Mode</h5>
        <span class="mode-status-badge">
            <i class="fas fa-circle"></i>
            <span id="currentModeLabel">{{ collect([
                'free'                   => 'Free Delivery',
                'free_above_threshold'   => 'Free Above Threshold',
                'distance_based'         => 'Distance Based',
                'threshold_and_distance' => 'Threshold + Distance',
            ])->get($currentMode, 'Free Delivery') }}</span>
        </span>
    </div>
    <div class="card-body">
        <div class="mode-grid">

            <label class="mode-card {{ $currentMode === 'free' ? 'selected' : '' }}"
                   id="modeCard_free" onclick="selectMode('free')">
                <input type="radio" name="mode" value="free" {{ $currentMode === 'free' ? 'checked' : '' }}>
                <div class="mode-check"></div>
                <span class="mode-icon">🚚</span>
                <div class="mode-title">Free Delivery</div>
                <div class="mode-desc">Everyone gets free delivery on every order, no conditions.</div>
            </label>

            <label class="mode-card {{ $currentMode === 'free_above_threshold' ? 'selected' : '' }}"
                   id="modeCard_free_above_threshold" onclick="selectMode('free_above_threshold')">
                <input type="radio" name="mode" value="free_above_threshold"
                    {{ $currentMode === 'free_above_threshold' ? 'checked' : '' }}>
                <div class="mode-check"></div>
                <span class="mode-icon">🛒</span>
                <div class="mode-title">Free Above Threshold</div>
                <div class="mode-desc">Free delivery above a set order amount. Flat rate below it.</div>
            </label>

            <label class="mode-card {{ $currentMode === 'distance_based' ? 'selected' : '' }}"
                   id="modeCard_distance_based" onclick="selectMode('distance_based')">
                <input type="radio" name="mode" value="distance_based"
                    {{ $currentMode === 'distance_based' ? 'checked' : '' }}>
                <div class="mode-check"></div>
                <span class="mode-icon">📍</span>
                <div class="mode-title">Distance Based</div>
                <div class="mode-desc">Charge by miles from store. Free above a threshold amount.</div>
            </label>

            <label class="mode-card {{ $currentMode === 'threshold_and_distance' ? 'selected' : '' }}"
                   id="modeCard_threshold_and_distance" onclick="selectMode('threshold_and_distance')">
                <input type="radio" name="mode" value="threshold_and_distance"
                    {{ $currentMode === 'threshold_and_distance' ? 'checked' : '' }}>
                <div class="mode-check"></div>
                <span class="mode-icon">📍🛒</span>
                <div class="mode-title">Threshold + Distance</div>
                <div class="mode-desc">Free above threshold. Below it, charge by distance from store.</div>
            </label>

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     CARD 2 — Rate Configuration
══════════════════════════════════════════ --}}
<div class="shipping-card">
    <div class="card-header">
        <h5><i class="fas fa-sliders-h me-2" style="color:#08437b"></i>Rate Configuration</h5>
    </div>
    <div class="card-body">

        {{-- ── Panel: Free ── --}}
        <div class="settings-panel {{ $currentMode === 'free' ? 'active' : '' }}" id="panel_free">
            <div class="info-alert success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Free delivery is active.</strong>
                    All customers receive free delivery on every order regardless of amount or location.
                    Switch the mode above to add delivery charges.
                </div>
            </div>
        </div>

        {{-- ── Panel: Free Above Threshold ── --}}
        <div class="settings-panel {{ $currentMode === 'free_above_threshold' ? 'active' : '' }}"
             id="panel_free_above_threshold">
            <div class="row g-4 align-items-stretch">

                <div class="col-md-4">
                    <label class="form-label">Free Delivery Above</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" id="fat_threshold"
                               step="0.01" min="0" class="form-control"
                               value="{{ $settings->get('free_threshold')?->value ?? '50' }}"
                               placeholder="50.00">
                    </div>
                    <small class="form-text">Orders at or above this amount get free delivery.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Flat Delivery Charge</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" id="fat_base"
                               step="0.01" min="0" class="form-control"
                               value="{{ $settings->get('base_rate')?->value ?? '2.99' }}"
                               placeholder="2.99">
                    </div>
                    <small class="form-text">Fixed charge for orders below the threshold.</small>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="preview-box w-100">
                        <h6>Preview</h6>
                        <div class="preview-row">
                            <span class="label">Order £30.00</span>
                            <span class="value charged" id="fat_prev_charged">£2.99</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">Order £<span id="fat_prev_threshold">50</span>+</span>
                            <span class="value free">FREE</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Panel: Distance Based ── --}}
        <div class="settings-panel {{ $currentMode === 'distance_based' ? 'active' : '' }}"
             id="panel_distance_based">
            <div class="row g-4 align-items-stretch">

                <div class="col-md-4">
                    <label class="form-label">Store Postcode</label>
                    <input type="text" id="db_store_postcode" class="form-control"
                           value="{{ $settings->get('store_postcode')?->value ?? '' }}"
                           placeholder="e.g. SW1A 1AA" style="text-transform:uppercase">
                    <small class="form-text">Your warehouse / shop origin postcode.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Free Delivery Above</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" id="db_threshold"
                               step="0.01" min="0" class="form-control"
                               value="{{ $settings->get('free_threshold')?->value ?? '50' }}"
                               placeholder="50.00">
                    </div>
                    <small class="form-text">Free delivery for large orders regardless of distance.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Max Delivery Radius</label>
                    <div class="input-group">
                        <input type="number" id="db_maxmiles"
                               step="1" min="1" class="form-control"
                               value="{{ $settings->get('max_delivery_miles')?->value ?? '10' }}"
                               placeholder="10">
                        <span class="input-group-text suffix">miles</span>
                    </div>
                    <small class="form-text">Orders beyond this radius are rejected.</small>
                </div>

                <div class="col-12"><div class="section-divider">Pricing</div></div>

                <div class="col-md-4">
                    <label class="form-label">Base Charge</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" id="db_base"
                               step="0.01" min="0" class="form-control"
                               value="{{ $settings->get('base_rate')?->value ?? '2.99' }}"
                               placeholder="2.99">
                    </div>
                    <small class="form-text">Fixed starting charge on every delivery.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rate Per Mile</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" id="db_ppm"
                               step="0.01" min="0" class="form-control"
                               value="{{ $settings->get('rate_per_mile')?->value ?? '0.50' }}"
                               placeholder="0.50">
                    </div>
                    <small class="form-text">Added per mile on top of base charge.</small>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="preview-box w-100">
                        <h6>Example Estimates</h6>
                        <div class="preview-row">
                            <span class="label">1 mile</span>
                            <span class="value charged" id="db_1mi">—</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">3 miles</span>
                            <span class="value charged" id="db_3mi">—</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">5 miles</span>
                            <span class="value charged" id="db_5mi">—</span>
                        </div>
                        <div class="preview-row">
                            <span class="label"><span id="db_maxLabel">10</span> miles (max)</span>
                            <span class="value charged" id="db_maxMi">—</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">Order £<span id="db_freeLabel">50</span>+</span>
                            <span class="value free">FREE</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">Beyond radius</span>
                            <span class="value rejected">Rejected</span>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="info-alert">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            Distance is calculated using the <strong>postcodes.io</strong> free UK API — no API key required.
                            If the API is unavailable, the base charge is used as a fallback.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Panel: Threshold + Distance ── --}}
        <div class="settings-panel {{ $currentMode === 'threshold_and_distance' ? 'active' : '' }}"
             id="panel_threshold_and_distance">
            <div class="row g-4 align-items-stretch">

                <div class="col-md-4">
                    <label class="form-label">Free Delivery Above</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" id="td_threshold"
                               step="0.01" min="0" class="form-control"
                               value="{{ $settings->get('free_threshold')?->value ?? '50' }}"
                               placeholder="50.00">
                    </div>
                    <small class="form-text">Orders at or above this get free delivery.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Store Postcode</label>
                    <input type="text" id="td_store_postcode" class="form-control"
                           value="{{ $settings->get('store_postcode')?->value ?? '' }}"
                           placeholder="e.g. SW1A 1AA" style="text-transform:uppercase">
                    <small class="form-text">Origin for distance calculations.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Max Delivery Radius</label>
                    <div class="input-group">
                        <input type="number" id="td_maxmiles"
                               step="1" min="1" class="form-control"
                               value="{{ $settings->get('max_delivery_miles')?->value ?? '10' }}"
                               placeholder="10">
                        <span class="input-group-text suffix">miles</span>
                    </div>
                    <small class="form-text">Orders beyond this radius are rejected.</small>
                </div>

                <div class="col-12"><div class="section-divider">Below Threshold Pricing</div></div>

                <div class="col-md-4">
                    <label class="form-label">Base Charge</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" id="td_base"
                               step="0.01" min="0" class="form-control"
                               value="{{ $settings->get('base_rate')?->value ?? '2.99' }}"
                               placeholder="2.99">
                    </div>
                    <small class="form-text">Fixed starting charge for orders below the threshold.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rate Per Mile</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" id="td_ppm"
                               step="0.01" min="0" class="form-control"
                               value="{{ $settings->get('rate_per_mile')?->value ?? '0.50' }}"
                               placeholder="0.50">
                    </div>
                    <small class="form-text">Added per mile on top of base charge.</small>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="preview-box w-100">
                        <h6>Example Estimates</h6>
                        <div class="preview-row">
                            <span class="label">Below threshold — 1 mi</span>
                            <span class="value charged" id="td_1mi">—</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">Below threshold — 3 mi</span>
                            <span class="value charged" id="td_3mi">—</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">Below threshold — 5 mi</span>
                            <span class="value charged" id="td_5mi">—</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">Order £<span id="td_freeLabel">50</span>+</span>
                            <span class="value free">FREE</span>
                        </div>
                        <div class="preview-row">
                            <span class="label">Beyond radius</span>
                            <span class="value rejected">Rejected</span>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="info-alert">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            Orders at or above <strong>£<span id="td_alertThreshold">50</span></strong>
                            get free delivery. Below that, customers are charged
                            <strong>£<span id="td_alertBase">2.99</span> base +
                            £<span id="td_alertPpm">0.50</span>/mile</strong>
                            based on their postcode distance from the store.
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- ── Footer ── --}}
<div class="d-flex justify-content-end gap-2">
    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
        <i class="fas fa-undo"></i> Reset
    </button>
    <button type="submit" class="btn btn-primary" id="saveBtn">
        <i class="fas fa-save"></i> Save Settings
    </button>
</div>

</form>
@endsection

@push('scripts')
<script>
const modeLabels = {
    free:                   'Free Delivery',
    free_above_threshold:   'Free Above Threshold',
    distance_based:         'Distance Based',
    threshold_and_distance: 'Threshold + Distance',
};

// ── Mode Selection ──────────────────────────────────────────────────
function selectMode(mode) {
    document.querySelectorAll('.mode-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('modeCard_' + mode).classList.add('selected');
    document.querySelector(`input[value="${mode}"]`).checked = true;

    document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel_' + mode).classList.add('active');

    document.getElementById('currentModeLabel').textContent = modeLabels[mode] ?? mode;
}

// ── Preview Helpers ─────────────────────────────────────────────────
function fmt(val) { return '£' + parseFloat(val || 0).toFixed(2); }
function calcRate(base, ppm, miles) { return fmt(parseFloat(base || 0) + parseFloat(ppm || 0) * miles); }

function updateFatPreview() {
    const threshold = document.getElementById('fat_threshold')?.value || 0;
    const base      = document.getElementById('fat_base')?.value || 0;
    const el = document.getElementById('fat_prev_charged');
    const lb = document.getElementById('fat_prev_threshold');
    if (el) el.textContent = fmt(base);
    if (lb) lb.textContent = parseFloat(threshold).toFixed(0);
}

function updateDbPreview() {
    const base   = document.getElementById('db_base')?.value    || 0;
    const ppm    = document.getElementById('db_ppm')?.value     || 0;
    const max    = document.getElementById('db_maxmiles')?.value || 10;
    const thresh = document.getElementById('db_threshold')?.value || 50;

    ['db_1mi','db_3mi','db_5mi'].forEach((id, i) => {
        const el = document.getElementById(id);
        if (el) el.textContent = calcRate(base, ppm, [1,3,5][i]);
    });
    const maxEl  = document.getElementById('db_maxMi');
    const maxLbl = document.getElementById('db_maxLabel');
    const freeLbl = document.getElementById('db_freeLabel');
    if (maxEl)  maxEl.textContent  = calcRate(base, ppm, max);
    if (maxLbl) maxLbl.textContent = max;
    if (freeLbl) freeLbl.textContent = parseFloat(thresh).toFixed(0);
}

function updateTdPreview() {
    const base   = document.getElementById('td_base')?.value     || 0;
    const ppm    = document.getElementById('td_ppm')?.value      || 0;
    const thresh = document.getElementById('td_threshold')?.value || 50;

    ['td_1mi','td_3mi','td_5mi'].forEach((id, i) => {
        const el = document.getElementById(id);
        if (el) el.textContent = calcRate(base, ppm, [1,3,5][i]);
    });

    const freeLbl    = document.getElementById('td_freeLabel');
    const alertThres = document.getElementById('td_alertThreshold');
    const alertBase  = document.getElementById('td_alertBase');
    const alertPpm   = document.getElementById('td_alertPpm');
    if (freeLbl)    freeLbl.textContent    = parseFloat(thresh).toFixed(0);
    if (alertThres) alertThres.textContent = parseFloat(thresh).toFixed(0);
    if (alertBase)  alertBase.textContent  = parseFloat(base).toFixed(2);
    if (alertPpm)   alertPpm.textContent   = parseFloat(ppm).toFixed(2);
}

// ── Attach Listeners & Init ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    ['fat_threshold','fat_base'].forEach(id =>
        document.getElementById(id)?.addEventListener('input', updateFatPreview));

    ['db_base','db_ppm','db_maxmiles','db_threshold'].forEach(id =>
        document.getElementById(id)?.addEventListener('input', updateDbPreview));

    ['td_base','td_ppm','td_threshold'].forEach(id =>
        document.getElementById(id)?.addEventListener('input', updateTdPreview));

    updateFatPreview();
    updateDbPreview();
    updateTdPreview();
});

// ── Populate hidden fields from active panel before submit ──────────
function populateHiddenFields() {
    const mode = document.querySelector('input[name="mode"]:checked')?.value;

    const map = {
        free: {
            free_threshold:     document.getElementById('fat_threshold')?.value || '0',
            base_rate:          '0',
            rate_per_mile:      '0',
            max_delivery_miles: '1',
            store_postcode:     '',
        },
        free_above_threshold: {
            free_threshold:     document.getElementById('fat_threshold')?.value || '50',
            base_rate:          document.getElementById('fat_base')?.value      || '2.99',
            rate_per_mile:      '0',
            max_delivery_miles: '1',
            store_postcode:     '',
        },
        distance_based: {
            free_threshold:     document.getElementById('db_threshold')?.value  || '50',
            base_rate:          document.getElementById('db_base')?.value       || '2.99',
            rate_per_mile:      document.getElementById('db_ppm')?.value        || '0.50',
            max_delivery_miles: document.getElementById('db_maxmiles')?.value   || '10',
            store_postcode:     document.getElementById('db_store_postcode')?.value.replace(/\s+/g,'').toUpperCase() || '',
        },
        threshold_and_distance: {
            free_threshold:     document.getElementById('td_threshold')?.value  || '50',
            base_rate:          document.getElementById('td_base')?.value       || '2.99',
            rate_per_mile:      document.getElementById('td_ppm')?.value        || '0.50',
            max_delivery_miles: document.getElementById('td_maxmiles')?.value   || '10',
            store_postcode:     document.getElementById('td_store_postcode')?.value.replace(/\s+/g,'').toUpperCase() || '',
        },
    };

    const values = map[mode] ?? map.free;
    document.getElementById('h_free_threshold').value     = values.free_threshold;
    document.getElementById('h_base_rate').value          = values.base_rate;
    document.getElementById('h_rate_per_mile').value      = values.rate_per_mile;
    document.getElementById('h_max_delivery_miles').value = values.max_delivery_miles;
    document.getElementById('h_store_postcode').value     = values.store_postcode;
}

// ── Form Submit ─────────────────────────────────────────────────────
document.getElementById('shippingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    populateHiddenFields(); // ✅ copy active panel values into hidden fields

    const saveBtn     = document.getElementById('saveBtn');
    saveBtn.disabled  = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    $.ajax({
        url: '{{ route("admin.shipping.update") }}',
        method: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: res => {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: res.message,
                confirmButtonColor: '#08437b',
                timer: 2500,
                showConfirmButton: false,
            });
        },
        error: xhr => {
            const errors = xhr.responseJSON?.errors;
            const msg    = errors
                ? Object.values(errors).flat().join('<br>')
                : (xhr.responseJSON?.message || 'Failed to save settings.');
            Swal.fire({ icon: 'error', title: 'Error', html: msg, confirmButtonColor: '#08437b' });
        },
        complete: () => {
            saveBtn.disabled  = false;
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Save Settings';
        },
    });
});

// ── Reset ────────────────────────────────────────────────────────────
function resetForm() {
    Swal.fire({
        icon: 'warning',
        title: 'Reset changes?',
        text: 'This will reload the page and discard unsaved changes.',
        showCancelButton: true,
        confirmButtonColor: '#08437b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reset',
    }).then(r => { if (r.isConfirmed) location.reload(); });
}
</script>
@endpush
