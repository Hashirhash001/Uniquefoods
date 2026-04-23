@extends('admin.layouts.app')
@section('title', 'Edit Order · ' . $order->order_number)

@push('styles')
<style>
    .oe-wrap { padding: 20px; max-width: 1100px; margin: 0 auto; }
    .od-back { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #6b7280; text-decoration: none; margin-bottom: 16px; transition: color 0.2s; }
    .od-back:hover { color: #08437b; }
    .ob { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; white-space: nowrap; }
    .ob:hover { transform: translateY(-1px); opacity: 0.88; }
    .ob-ghost { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
    .ob-ghost:hover { background: #e5e7eb; opacity: 1; transform: none; color: #374151; }
    .ob-blue { background: #08437b; color: white; }
    .ob-blue:hover { background: #0f508d; opacity: 1; }
    .oe-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .oe-card:last-child { margin-bottom: 0; }
    .oe-card-head { padding: 14px 20px; border-bottom: 1px solid #e5e7eb; background: #fafafa; display: flex; align-items: center; justify-content: space-between; }
    .oe-card-head h2 { font-size: 14px; font-weight: 700; color: #111827; margin: 0; display: flex; align-items: center; gap: 7px; }
    .oe-card-head h2 i { color: #08437b; font-size: 13px; }
    .oe-card-body { padding: 20px; }
    .oe-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .oe-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
    .oe-label { font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 5px; display: block; }
    .oe-input { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 9px 12px; font-size: 13px; background: white; transition: border-color 0.2s, box-shadow 0.2s; font-family: inherit; color: #111827; }
    .oe-input:focus { border-color: #08437b; box-shadow: 0 0 0 3px rgba(8,67,123,0.08); outline: none; }
    textarea.oe-input { resize: vertical; min-height: 80px; }
    .oe-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 620px; }
    .oe-table thead th { background: #f9fafb; padding: 10px 14px; font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid #e5e7eb; text-align: left; white-space: nowrap; }
    .oe-table tbody td { padding: 11px 14px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    .oe-table tbody tr:last-child td { border-bottom: none; }
    .oe-table tbody tr:hover td { background: #fafafa; }
    .qty-input   { width: 80px;  border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 8px; font-size: 13px; text-align: center; font-family: inherit; }
    .price-input { width: 96px;  border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 8px; font-size: 13px; font-family: inherit; }
    .vat-input   { width: 68px;  border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 8px; font-size: 13px; text-align: center; font-family: inherit; }
    .qty-input:focus, .price-input:focus, .vat-input:focus { border-color: #08437b; outline: none; box-shadow: 0 0 0 2px rgba(8,67,123,0.08); }
    .vat-wrap { display: inline-flex; align-items: center; gap: 3px; }
    .vat-wrap span { font-size: 11px; color: #9ca3af; }
    .prod-cell { display: flex; align-items: center; gap: 10px; }
    .prod-img { width: 42px; height: 42px; border-radius: 7px; object-fit: cover; border: 1px solid #e5e7eb; flex-shrink: 0; }
    .prod-img-ph { width: 42px; height: 42px; border-radius: 7px; background: #f3f4f6; border: 1px solid #e5e7eb; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    .prod-name { font-weight: 600; color: #111827; font-size: 13px; }
    .prod-sku  { font-size: 11px; color: #9ca3af; margin-top: 2px; }
    .rm-btn { background: #fee2e2; color: #991b1b; border: none; border-radius: 6px; padding: 5px 9px; cursor: pointer; font-size: 12px; transition: background 0.15s; line-height: 1; }
    .rm-btn:hover { background: #fecaca; }
    .sum-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; font-size: 13px; border-bottom: 1px solid #f3f4f6; }
    .sum-row:last-child { border-bottom: none; font-size: 15px; font-weight: 800; color: #111827; border-top: 2px solid #e5e7eb; padding-top: 12px; margin-top: 4px; }
    .sum-label { color: #6b7280; }
    .search-wrap { position: relative; }
    .search-results { display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-height: 260px; overflow-y: auto; z-index: 999; }
    .sr-item { padding: 10px 14px; cursor: pointer; font-size: 13px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; transition: background 0.15s; }
    .sr-item:last-child { border-bottom: none; }
    .sr-item:hover { background: #f0f9ff; }
    .sr-item-left { display: flex; flex-direction: column; }
    .sr-item-name { font-weight: 600; color: #111827; }
    .sr-item-sku  { font-size: 11px; color: #9ca3af; margin-top: 2px; }
    .sr-item-price { font-weight: 700; color: #08437b; white-space: nowrap; margin-left: 12px; }
    .sr-no-results { padding: 14px; text-align: center; font-size: 13px; color: #9ca3af; }
    .oe-footer { display: flex; gap: 10px; justify-content: flex-end; padding-top: 6px; }
    .ld-spin { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spn 0.6s linear infinite; display: inline-block; vertical-align: middle; }
    @keyframes spn { to { transform: rotate(360deg); } }
    @media(max-width: 767px) {
        .oe-wrap { padding: 12px; }
        .oe-grid, .oe-grid-3 { grid-template-columns: 1fr; }
        .oe-footer { flex-direction: column-reverse; }
        .oe-footer .ob { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="oe-wrap">

    <a href="{{ route('admin.orders.show', $order) }}" class="od-back">
        <i class="fas fa-arrow-left"></i> Back to Order
    </a>

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
        <div>
            <h1 style="font-size:20px;font-weight:800;color:#111827;margin:0 0 4px;">
                Edit Order {{ $order->order_number }}
            </h1>
            <p style="font-size:13px;color:#6b7280;margin:0;">
                <i class="fas fa-calendar-alt" style="margin-right:4px;"></i>{{ $order->created_at->format('d M Y, h:i A') }}
                &nbsp;·&nbsp;
                <i class="fas fa-user" style="margin-right:4px;"></i>{{ $order->customer_name }}
            </p>
        </div>
    </div>

    <form id="editOrderForm">
        @csrf

        {{-- ORDER ITEMS --}}
        <div class="oe-card">
            <div class="oe-card-head">
                <h2><i class="fas fa-shopping-bag"></i> Order Items</h2>
                <span id="itemCount" style="font-size:12px;color:#9ca3af;">{{ $order->items->count() }} items</span>
            </div>
            <div class="oe-card-body">

                {{-- Product search --}}
                <div style="position:relative;margin-bottom:16px;">
                    <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px;pointer-events:none;"></i>
                    <input type="text" id="productSearchInput" class="oe-input"
                           placeholder="Search product by name or SKU to add..."
                           style="padding-left:34px;">
                    <div class="search-results" id="searchResults"></div>
                </div>

                <div style="overflow-x:auto;">
                    <table class="oe-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit Price (£)</th>
                                <th>VAT %</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            @foreach($order->items as $item)
                            <tr data-product-id="{{ $item->product_id }}">
                                <td>
                                    <div class="prod-cell">
                                        @if($item->product && $item->product->primaryImage)
                                            <img src="{{ $item->product->image_url }}" alt="" class="prod-img">
                                        @else
                                            <div class="prod-img-ph"><i class="fas fa-image" style="color:#d1d5db;font-size:14px;"></i></div>
                                        @endif
                                        <div>
                                            <div class="prod-name">{{ $item->product->name ?? $item->product_name ?? 'Product' }}</div>
                                            @if($item->product->sku ?? null)
                                                <div class="prod-sku">{{ $item->product->sku }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                           class="price-input item-price"
                                           value="{{ $item->price }}">
                                </td>
                                <td>
                                    <div class="vat-wrap">
                                        <input type="number" step="0.01" min="0" max="100"
                                               class="vat-input item-vat-rate"
                                               value="{{ $item->vat_rate ?? 0 }}">
                                        <span>%</span>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" step="0.001" min="0.001"
                                           class="qty-input item-qty"
                                           value="{{ $item->quantity }}">
                                </td>
                                <td class="item-subtotal" style="font-weight:700;white-space:nowrap;">
                                    £{{ number_format($item->quantity * $item->price, 2) }}
                                </td>
                                <td>
                                    <button type="button" class="rm-btn" title="Remove item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary --}}
                <div style="padding:16px 20px;border-top:1px solid #f3f4f6;max-width:340px;margin-left:auto;">
                    <div class="sum-row">
                        <span class="sum-label">Subtotal</span>
                        <span id="sumSubtotal" style="font-weight:600;">£{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="sum-row">
                        <span class="sum-label">Shipping (£)</span>
                        <input type="number" step="0.01" min="0" id="shippingInput"
                               class="oe-input" style="width:96px;text-align:right;padding:5px 8px;"
                               value="{{ $order->shipping_cost }}">
                    </div>
                    <div class="sum-row">
                        <span class="sum-label">Tax (£)</span>
                        <input type="number" step="0.01" min="0" id="taxInput"
                               class="oe-input" style="width:96px;text-align:right;padding:5px 8px;"
                               value="{{ $order->tax }}">
                    </div>
                    <div class="sum-row">
                        <span class="sum-label">Discount (£)</span>
                        <input type="number" step="0.01" min="0" id="discountInput"
                               class="oe-input" style="width:96px;text-align:right;padding:5px 8px;"
                               value="{{ $order->discount }}">
                    </div>
                    <div class="sum-row">
                        <span>Total</span>
                        <span id="sumTotal" style="color:#08437b;">£{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- CUSTOMER INFO --}}
        <div class="oe-card">
            <div class="oe-card-head">
                <h2><i class="fas fa-user"></i> Customer Information</h2>
            </div>
            <div class="oe-card-body">
                <div class="oe-grid" style="margin-bottom:16px;">
                    <div>
                        <label class="oe-label">Full Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="customer_name" class="oe-input" value="{{ $order->customer_name }}" required>
                    </div>
                    <div>
                        <label class="oe-label">Email <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="customer_email" class="oe-input" value="{{ $order->customer_email }}" required>
                    </div>
                </div>
                <div>
                    <label class="oe-label">Phone</label>
                    <input type="text" name="customer_phone" class="oe-input" style="max-width:260px;" value="{{ $order->customer_phone }}">
                </div>
            </div>
        </div>

        {{-- SHIPPING ADDRESS --}}
        <div class="oe-card">
            <div class="oe-card-head">
                <h2><i class="fas fa-truck"></i> Shipping Address</h2>
            </div>
            <div class="oe-card-body">
                <div style="margin-bottom:16px;">
                    <label class="oe-label">Address Line <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="shipping_address" class="oe-input" value="{{ $order->shipping_address }}" required>
                </div>
                <div class="oe-grid-3">
                    <div>
                        <label class="oe-label">City</label>
                        <input type="text" name="shipping_city" class="oe-input" value="{{ $order->shipping_city }}">
                    </div>
                    <div>
                        <label class="oe-label">Postcode</label>
                        <input type="text" name="shipping_postcode" class="oe-input" value="{{ $order->shipping_postcode }}">
                    </div>
                    <div>
                        <label class="oe-label">Country</label>
                        <input type="text" name="shipping_country" class="oe-input" value="{{ $order->shipping_country }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- CUSTOMER NOTES --}}
        <div class="oe-card">
            <div class="oe-card-head">
                <h2><i class="fas fa-sticky-note"></i> Customer Notes</h2>
            </div>
            <div class="oe-card-body">
                <textarea name="customer_notes" class="oe-input"
                          placeholder="Visible to the customer on their order confirmation...">{{ $order->customer_notes }}</textarea>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="oe-footer">
            <a href="{{ route('admin.orders.show', $order) }}" class="ob ob-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="ob ob-blue" id="saveBtn">
                <i class="fas fa-save"></i> Save &amp; Notify Customer
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
const UPDATE_URL = "{{ route('admin.orders.update', $order) }}";
const SHOW_URL   = "{{ route('admin.orders.show', $order) }}";
const SEARCH_URL = "{{ route('admin.products.search') }}";

// ── LIVE RECALC ──────────────────────────────────────────────────────────────
function recalc() {
    let subtotal = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value)       || 0;
        const prc = parseFloat(row.querySelector('.item-price').value)     || 0;
        const sub = qty * prc;
        subtotal += sub;
        row.querySelector('.item-subtotal').textContent = '£' + sub.toFixed(2);
    });
    const shipping = parseFloat(document.getElementById('shippingInput').value) || 0;
    const tax      = parseFloat(document.getElementById('taxInput').value)      || 0;
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total    = subtotal + shipping + tax - discount;
    document.getElementById('sumSubtotal').textContent = '£' + subtotal.toFixed(2);
    document.getElementById('sumTotal').textContent    = '£' + total.toFixed(2);
    document.getElementById('itemCount').textContent   = document.querySelectorAll('#itemsBody tr').length + ' items';
}

document.getElementById('itemsBody').addEventListener('input', e => {
    if (e.target.matches('.item-qty, .item-price, .item-vat-rate')) recalc();
});
document.getElementById('shippingInput').addEventListener('input', recalc);
document.getElementById('taxInput').addEventListener('input', recalc);
document.getElementById('discountInput').addEventListener('input', recalc);

// ── REMOVE ITEM ──────────────────────────────────────────────────────────────
document.getElementById('itemsBody').addEventListener('click', e => {
    if (!e.target.closest('.rm-btn')) return;
    if (document.querySelectorAll('#itemsBody tr').length === 1) {
        Swal.fire({ icon: 'warning', title: 'Cannot remove', text: 'An order must have at least one item.', confirmButtonColor: '#08437b' });
        return;
    }
    e.target.closest('tr').remove();
    recalc();
});

// ── PRODUCT SEARCH ───────────────────────────────────────────────────────────
let searchTimer;
const searchInput   = document.getElementById('productSearchInput');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function () {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    if (q.length < 2) { searchResults.style.display = 'none'; return; }
    searchTimer = setTimeout(() => {
        fetch(`${SEARCH_URL}?q=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(products => {
            if (!products.length) {
                searchResults.innerHTML = '<div class="sr-no-results"><i class="fas fa-search" style="margin-right:6px;"></i>No products found</div>';
                searchResults.style.display = 'block';
                return;
            }
            searchResults.innerHTML = products.map(p => `
                <div class="sr-item"
                     data-id="${p.id}"
                     data-name="${p.name}"
                     data-price="${p.price}"
                     data-sku="${p.sku ?? ''}"
                     data-vat="${p.tax_rate ?? 0}">
                    <div class="sr-item-left">
                        <span class="sr-item-name">${p.name}</span>
                        ${p.sku ? `<span class="sr-item-sku">SKU: ${p.sku}</span>` : ''}
                    </div>
                    <span class="sr-item-price">£${parseFloat(p.price).toFixed(2)}</span>
                </div>
            `).join('');
            searchResults.style.display = 'block';
        })
        .catch(() => { searchResults.style.display = 'none'; });
    }, 280);
});

searchResults.addEventListener('click', e => {
    const item = e.target.closest('.sr-item');
    if (!item) return;
    addRow(item.dataset.id, item.dataset.name, item.dataset.sku, item.dataset.price, item.dataset.vat || 0);
    searchInput.value = '';
    searchResults.style.display = 'none';
});

document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// ── ADD ROW ──────────────────────────────────────────────────────────────────
function addRow(productId, name, sku, price, vatRate = 0) {
    if (document.querySelector(`#itemsBody tr[data-product-id="${productId}"]`)) {
        Swal.fire({ icon: 'info', title: 'Already in order', text: `"${name}" is already added. Adjust the quantity instead.`, confirmButtonColor: '#08437b' });
        return;
    }
    const tr = document.createElement('tr');
    tr.dataset.productId = productId;
    tr.innerHTML = `
        <td>
            <div class="prod-cell">
                <div class="prod-img-ph"><i class="fas fa-image" style="color:#d1d5db;font-size:14px;"></i></div>
                <div>
                    <div class="prod-name">${name}</div>
                    ${sku ? `<div class="prod-sku">SKU: ${sku}</div>` : ''}
                </div>
            </div>
        </td>
        <td><input type="number" step="0.01" min="0" class="price-input item-price" value="${parseFloat(price).toFixed(2)}"></td>
        <td>
            <div class="vat-wrap">
                <input type="number" step="0.01" min="0" max="100" class="vat-input item-vat-rate" value="${parseFloat(vatRate).toFixed(2)}">
                <span>%</span>
            </div>
        </td>
        <td><input type="number" step="0.001" min="0.001" class="qty-input item-qty" value="1"></td>
        <td class="item-subtotal" style="font-weight:700;white-space:nowrap;">£${parseFloat(price).toFixed(2)}</td>
        <td><button type="button" class="rm-btn" title="Remove item"><i class="fas fa-times"></i></button></td>
    `;
    document.getElementById('itemsBody').appendChild(tr);
    recalc();
    tr.style.background = '#f0fdf4';
    setTimeout(() => { tr.style.background = ''; }, 1200);
}

// ── FORM SUBMIT ──────────────────────────────────────────────────────────────
document.getElementById('editOrderForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const rows = document.querySelectorAll('#itemsBody tr');
    if (!rows.length) {
        Swal.fire({ icon: 'warning', title: 'No items', text: 'Add at least one product to the order.', confirmButtonColor: '#08437b' });
        return;
    }

    const items = [];
    rows.forEach(row => {
        items.push({
            product_id: row.dataset.productId,
            quantity:   parseFloat(row.querySelector('.item-qty').value),
            price:      parseFloat(row.querySelector('.item-price').value),
            vat_rate:   parseFloat(row.querySelector('.item-vat-rate').value) || 0,
        });
    });

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="ld-spin"></span> Saving...';

    fetch(UPDATE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept':       'application/json',
        },
        body: JSON.stringify({
            _method:           'PUT',
            items,
            customer_name:     document.querySelector('[name=customer_name]').value,
            customer_email:    document.querySelector('[name=customer_email]').value,
            customer_phone:    document.querySelector('[name=customer_phone]').value,
            shipping_address:  document.querySelector('[name=shipping_address]').value,
            shipping_city:     document.querySelector('[name=shipping_city]').value,
            shipping_postcode: document.querySelector('[name=shipping_postcode]').value,
            shipping_country:  document.querySelector('[name=shipping_country]').value,
            shipping_cost:     parseFloat(document.getElementById('shippingInput').value)  || 0,
            tax:               parseFloat(document.getElementById('taxInput').value)       || 0,
            discount:          parseFloat(document.getElementById('discountInput').value)  || 0,
            customer_notes:    document.querySelector('[name=customer_notes]').value,
        }),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Save &amp; Notify Customer';
        if (data.success) {
            Swal.fire({
                icon: 'success', title: 'Order Updated!',
                html: `${data.message}<br><small style="color:#6b7280;">Customer has been notified by email.</small>`,
                confirmButtonColor: '#08437b', timer: 2500, showConfirmButton: false,
            }).then(() => window.location.href = SHOW_URL);
        } else if (data.errors) {
            const msgs = Object.values(data.errors).flat();
            Swal.fire({
                icon: 'error', title: 'Validation Error',
                html: `<ul style="text-align:left;font-size:13px;line-height:1.8;padding-left:18px;">${msgs.map(m => `<li>${m}</li>`).join('')}</ul>`,
                confirmButtonColor: '#08437b',
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong.', confirmButtonColor: '#08437b' });
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Save &amp; Notify Customer';
        Swal.fire({ icon: 'error', title: 'Network Error', text: 'Request failed. Please try again.', confirmButtonColor: '#08437b' });
    });
});
</script>
@endpush
