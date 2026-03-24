@extends('admin.layouts.app')

@section('title', 'Group Overview — ' . $customerGroup->name)

@push('styles')
<style>
    * { box-sizing: border-box; }

    .page-header { margin-bottom: 1.5rem; }
    .breadcrumb { background: transparent; padding: 0; margin: 0; font-size: 14px; }
    .breadcrumb-item { color: #6b7280; }
    .breadcrumb-item a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .breadcrumb-item a:hover { color: #08437b; }
    .breadcrumb-item.active { color: #111827; font-weight: 500; }
    .breadcrumb-item + .breadcrumb-item::before { color: #d1d5db; content: "/"; }

    .group-hero {
        background: white; border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 1.5rem 2rem; margin-bottom: 1.75rem;
        display: flex; align-items: center; justify-content: space-between;
        gap: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .group-hero-left { display: flex; align-items: center; gap: 1.25rem; }
    .group-avatar {
        width: 56px; height: 56px;
        background: linear-gradient(135deg, #08437b, #1a6abf);
        border-radius: 12px; display: flex; align-items: center;
        justify-content: center; font-size: 24px; color: white; flex-shrink: 0;
    }
    .group-meta h2 { margin: 0; font-size: 20px; font-weight: 700; color: #111827; }
    .group-meta p  { margin: 0.25rem 0 0; font-size: 14px; color: #6b7280; }
    .group-badges { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem; }
    .gbadge {
        display: inline-flex; align-items: center; gap: 0.25rem;
        padding: 0.2rem 0.625rem; border-radius: 20px;
        font-size: 11px; font-weight: 600; text-transform: uppercase;
    }
    .gbadge-blue   { background: #dbeafe; color: #1e40af; }
    .gbadge-green  { background: #d1fae5; color: #065f46; }
    .gbadge-red    { background: #fee2e2; color: #991b1b; }
    .gbadge-purple { background: #ede9fe; color: #5b21b6; }
    .group-hero-actions { display: flex; gap: 0.75rem; align-items: center; flex-shrink: 0; }

    .btn {
        padding: 0.5rem 1rem; border-radius: 6px; font-weight: 500;
        font-size: 13px; transition: all 0.2s; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        text-decoration: none; gap: 0.5rem;
    }
    .btn-primary { background: #08437b; color: white; }
    .btn-primary:hover { background: #0f508d; color: white; box-shadow: 0 2px 8px rgba(8,67,123,0.25); }
    .btn-outline { background: white; border: 1px solid #d1d5db; color: #374151; }
    .btn-outline:hover { background: #f9fafb; border-color: #9ca3af; color: #111827; }

    /* ── Stats Row ── */
    .stats-row {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 1rem; margin-bottom: 1.75rem;
    }
    .stat-card {
        background: white; border: 1px solid #e5e7eb; border-radius: 8px;
        padding: 1.25rem 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        position: relative; overflow: hidden;
    }
    .stat-card .stat-icon {
        position: absolute; top: 1rem; right: 1rem;
        font-size: 2rem; opacity: 0.08; line-height: 1;
        pointer-events: none;
    }
    .stat-card .stat-label {
        font-size: 11px; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;
    }
    .stat-card .stat-value {
        font-size: 30px; font-weight: 700; color: #111827; line-height: 1;
    }
    .stat-card .stat-sub { font-size: 12px; color: #9ca3af; margin-top: 0.375rem; }

    /* ── Section Card ── */
    .section-card {
        background: white; border: 1px solid #e5e7eb; border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 2rem; overflow: hidden;
    }
    .section-card-header {
        padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between; background: white;
    }
    .section-card-header h5 {
        margin: 0; font-size: 16px; font-weight: 600; color: #111827;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .section-card-header h5 i { color: #08437b; }

    /* ── Filter Row ── */
    .filter-row {
        background: #f9fafb; border-bottom: 1px solid #e5e7eb;
        padding: 0.875rem 1.5rem; display: flex; gap: 0.75rem;
        flex-wrap: wrap; align-items: center;
    }
    .filter-row .form-control,
    .filter-row .form-select {
        border: 1px solid #d1d5db; border-radius: 6px; padding: 0.5rem 0.75rem;
        font-size: 13px; height: 38px; background: white; min-width: 140px; max-width: 200px;
    }
    .filter-row .form-control:focus,
    .filter-row .form-select:focus {
        border-color: #08437b; box-shadow: 0 0 0 2px rgba(8,67,123,0.1); outline: none;
    }
    .filter-row .input-icon { position: relative; }
    .filter-row .input-icon i {
        position: absolute; left: 0.625rem; top: 50%;
        transform: translateY(-50%); color: #9ca3af; font-size: 12px; z-index: 1;
    }
    .filter-row .input-icon .form-control { padding-left: 2rem; }

    /* ── Data Table ── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead { background: #f9fafb; }
    .data-table th {
        padding: 0.75rem 1rem; font-size: 11px; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb; white-space: nowrap;
    }
    .data-table tbody tr { transition: background 0.15s; }
    .data-table tbody tr:hover { background: #fafafa; }
    .data-table td {
        padding: 0.875rem 1rem; font-size: 14px; color: #111827;
        border-bottom: 1px solid #f3f4f6; vertical-align: middle;
    }

    .pill {
        display: inline-block; padding: 0.2rem 0.6rem; border-radius: 4px;
        font-size: 11px; font-weight: 600; text-transform: uppercase;
    }
    .pill-green  { background: #d1fae5; color: #065f46; }
    .pill-red    { background: #fee2e2; color: #991b1b; }
    .pill-yellow { background: #fef3c7; color: #92400e; }
    .pill-blue   { background: #dbeafe; color: #1e40af; }
    .pill-gray   { background: #f3f4f6; color: #374151; }

    .thumb {
        width: 44px; height: 44px; object-fit: cover;
        border-radius: 6px; border: 1px solid #e5e7eb;
    }
    .thumb-placeholder {
        width: 44px; height: 44px; background: #f3f4f6; border-radius: 6px;
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }

    .icon-btn {
        width: 30px; height: 30px; display: inline-flex;
        align-items: center; justify-content: center;
        border-radius: 6px; border: 1px solid #e5e7eb;
        background: white; color: #6b7280; font-size: 12px;
        text-decoration: none; transition: all 0.2s; cursor: pointer;
    }
    .icon-btn:hover { background: #dbeafe; color: #2563eb; border-color: #93c5fd; }

    .count-badge {
        background: #f3f4f6; border-radius: 12px; padding: 0.15rem 0.6rem;
        font-size: 12px; font-weight: 600; color: #374151;
    }

    .empty-state { text-align: center; padding: 3.5rem 1.5rem; }
    .empty-state i { font-size: 3rem; opacity: 0.15; display: block; margin-bottom: 1rem; }
    .empty-state h5 { font-size: 16px; font-weight: 600; color: #374151; margin-bottom: 0.5rem; }
    .empty-state p  { font-size: 13px; color: #9ca3af; margin: 0; }

    /* Loading spinner */
    .table-loading {
        display: none; text-align: center; padding: 2.5rem;
        font-size: 13px; color: #6b7280;
    }
    .table-loading i { font-size: 1.5rem; color: #08437b; margin-bottom: 0.5rem; display: block; }

    .pagination .page-link { color: #374151; border: 1px solid #e5e7eb; padding: 0.4rem 0.7rem; margin: 0 0.1rem; border-radius: 5px; font-size: 13px; }
    .pagination .page-link:hover { background: #f9fafb; }
    .pagination .page-item.active .page-link { background: #08437b; border-color: #08437b; color: white; }
    .pagination .page-item.disabled .page-link { color: #9ca3af; background: #f9fafb; }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .group-hero { flex-direction: column; align-items: flex-start; }
        .group-hero-actions { width: 100%; }
        .group-hero-actions .btn { flex: 1; }
        .filter-row { flex-direction: column; }
        .filter-row .form-control,
        .filter-row .form-select { min-width: 100%; max-width: 100%; }
    }
    @media (max-width: 480px) {
        .stats-row { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.customer-groups.index') }}">Customer Groups</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.customer-groups.edit', ['customer_group' => $customerGroup->id]) }}">
                        {{ $customerGroup->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active">Overview</li>
            </ol>
        </nav>
    </div>

    {{-- Group Hero --}}
    <div class="group-hero">
        <div class="group-hero-left">
            <div class="group-avatar">👥</div>
            <div class="group-meta">
                <h2>{{ $customerGroup->name }}</h2>
                @if($customerGroup->description)
                    <p>{{ $customerGroup->description }}</p>
                @endif
                <div class="group-badges">
                    @if($customerGroup->is_active)
                        <span class="gbadge gbadge-green"><i class="fas fa-circle" style="font-size:7px;"></i> Active</span>
                    @else
                        <span class="gbadge gbadge-red"><i class="fas fa-circle" style="font-size:7px;"></i> Inactive</span>
                    @endif
                    @if($customerGroup->slug === 'home-delivery')
                        <span class="gbadge gbadge-blue"><i class="fas fa-star"></i> Default</span>
                    @endif
                    @if($customerGroup->tax_exempt ?? false)
                        <span class="gbadge gbadge-purple"><i class="fas fa-percent"></i> Tax Exempt</span>
                    @endif
                    <span class="gbadge gbadge-blue">
                        <i class="fas fa-tag"></i>
                        {{ $customerGroup->discount_rate ?? 0 }}% group discount
                    </span>
                </div>
            </div>
        </div>
        <div class="group-hero-actions">
            <a href="{{ route('admin.customer-groups.edit', ['customer_group' => $customerGroup->id]) }}" class="btn btn-outline">
                <i class="fas fa-edit"></i> Edit Group
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    @php
        $totalProducts  = $customerGroup->products()->count();
        $activeProducts = $customerGroup->products()->where('is_active', 1)->count();
        $outOfStock     = $customerGroup->products()->where('stock', '<=', 0)->count();
        $now            = now();
        $totalOffers    = $customerGroup->groupProductOffers()->count();
        $activeOffers   = $customerGroup->groupProductOffers()
                            ->where('starts_at', '<=', $now)
                            ->where('ends_at', '>=', $now)
                            ->count();
    @endphp

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-boxes"></i></div>
            <div class="stat-label">Total Products</div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-sub">Assigned to this group</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-label">Active Products</div>
            <div class="stat-value" style="color:#065f46;">{{ $activeProducts }}</div>
            <div class="stat-sub">Visible to customers</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-label">Out of Stock</div>
            <div class="stat-value" style="color:#991b1b;">{{ $outOfStock }}</div>
            <div class="stat-sub">Need restocking</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-percentage"></i></div>
            <div class="stat-label">Active Offers</div>
            <div class="stat-value" style="color:#5b21b6;">{{ $activeOffers }}</div>
            <div class="stat-sub">{{ $totalOffers }} total offers</div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         PRODUCTS SECTION
    ══════════════════════════════════════ --}}
    <div class="section-card">
        <div class="section-card-header">
            <h5>
                <i class="fas fa-boxes"></i>
                Products in "{{ $customerGroup->name }}"
                <span class="count-badge ms-1" id="products-count-badge">{{ $products->total() }}</span>
            </h5>
            <a href="{{ route('admin.products.index') }}?group_id={{ $customerGroup->id }}"
               class="btn btn-outline" style="font-size:12px;padding:0.375rem 0.75rem;">
                <i class="fas fa-external-link-alt"></i> Full Product List
            </a>
        </div>

        {{-- Product Filters --}}
        <form id="products-filter-form" method="GET"
              action="{{ route('admin.customer-groups.overview', ['customerGroup' => $customerGroup->id]) }}">
            <div class="filter-row">
                <div class="input-icon">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" id="product-search" class="form-control"
                           placeholder="Search products..."
                           value="{{ request('search') }}">
                </div>
                <select name="category_id" id="product-category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" id="product-status" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" @selected(request('status') === '1')>Active</option>
                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                </select>
                <select name="stock_status" id="product-stock" class="form-select">
                    <option value="">All Stock</option>
                    <option value="in_stock"     @selected(request('stock_status') === 'in_stock')>In Stock</option>
                    <option value="low_stock"    @selected(request('stock_status') === 'low_stock')>Low Stock</option>
                    <option value="out_of_stock" @selected(request('stock_status') === 'out_of_stock')>Out of Stock</option>
                </select>
                <button type="submit" class="btn btn-primary" style="height:38px;padding:0 1rem;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search','category_id','status','stock_status']))
                    <a href="{{ route('admin.customer-groups.overview', ['customerGroup' => $customerGroup->id]) }}"
                       class="btn btn-outline" style="height:38px;padding:0 0.875rem;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Products Table (AJAX target) --}}
        <div id="products-table-wrapper">
            <div class="table-loading" id="products-loading">
                <i class="fas fa-spinner fa-spin"></i> Loading products…
            </div>

            @include('admin.customer-groups.partials.overview-products', [
                'products' => $products,
                'customerGroup' => $customerGroup,
            ])
        </div>
    </div>

    {{-- ══════════════════════════════════════
         OFFERS / DISCOUNTS SECTION
    ══════════════════════════════════════ --}}
    <div class="section-card">
        <div class="section-card-header">
            <h5>
                <i class="fas fa-percent"></i>
                Offers & Discounts for "{{ $customerGroup->name }}"
                <span class="count-badge ms-1" id="offers-count-badge">{{ $offers->total() }}</span>
            </h5>
        </div>

        {{-- Offer Filters --}}
        <form id="offers-filter-form" method="GET"
              action="{{ route('admin.customer-groups.overview', ['customerGroup' => $customerGroup->id]) }}">
            @foreach(request()->except(['offer_search','offer_status','offers_page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <div class="filter-row">
                <div class="input-icon">
                    <i class="fas fa-search"></i>
                    <input type="text" name="offer_search" id="offer-search" class="form-control"
                           placeholder="Search offers..."
                           value="{{ request('offer_search') }}">
                </div>
                <select name="offer_status" id="offer-status" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" @selected(request('offer_status') === '1')>Active</option>
                    <option value="0" @selected(request('offer_status') === '0')>Inactive</option>
                </select>
                <button type="submit" class="btn btn-primary" style="height:38px;padding:0 1rem;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['offer_search','offer_status']))
                    <a href="{{ route('admin.customer-groups.overview', ['customerGroup' => $customerGroup->id]) }}"
                       class="btn btn-outline" style="height:38px;padding:0 0.875rem;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Offers Table (AJAX target) --}}
        <div id="offers-table-wrapper">
            <div class="table-loading" id="offers-loading">
                <i class="fas fa-spinner fa-spin"></i> Loading offers…
            </div>

            @include('admin.customer-groups.partials.overview-offers', [
                'offers' => $offers,
                'customerGroup' => $customerGroup,
            ])
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const groupId   = {{ $customerGroup->id }};
    const baseUrl   = '{{ route('admin.customer-groups.overview', ['customerGroup' => $customerGroup->id]) }}';

    /* ── helpers ── */
    function getProductParams() {
        return {
            search:       document.getElementById('product-search').value,
            category_id:  document.getElementById('product-category').value,
            status:       document.getElementById('product-status').value,
            stock_status: document.getElementById('product-stock').value,
        };
    }

    function getOfferParams() {
        return {
            offer_search: document.getElementById('offer-search').value,
            offer_status: document.getElementById('offer-status').value,
        };
    }

    /* ── AJAX loader ── */
    function loadProducts(params, page) {
        const wrapper = document.getElementById('products-table-wrapper');
        const loader  = document.getElementById('products-loading');

        loader.style.display  = 'block';
        wrapper.style.opacity = '0.4';

        const query = new URLSearchParams({ ...params, products_page: page || 1 });

        fetch(`${baseUrl}?${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            wrapper.innerHTML         = data.products_html;
            document.getElementById('products-count-badge').textContent = data.products_total;
            wrapper.style.opacity     = '1';
            bindProductPagination();
        })
        .catch(() => { wrapper.style.opacity = '1'; })
        .finally(() => { loader.style.display = 'none'; });
    }

    function loadOffers(params, page) {
        const wrapper = document.getElementById('offers-table-wrapper');
        const loader  = document.getElementById('offers-loading');

        loader.style.display  = 'block';
        wrapper.style.opacity = '0.4';

        const query = new URLSearchParams({ ...params, offers_page: page || 1 });

        fetch(`${baseUrl}?${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            wrapper.innerHTML       = data.offers_html;
            document.getElementById('offers-count-badge').textContent = data.offers_total;
            wrapper.style.opacity   = '1';
            bindOfferPagination();
        })
        .catch(() => { wrapper.style.opacity = '1'; })
        .finally(() => { loader.style.display = 'none'; });
    }

    /* ── Pagination delegation ── */
    function bindProductPagination() {
        document.querySelectorAll('#products-table-wrapper .pagination .page-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url    = new URL(this.href);
                const page   = url.searchParams.get('products_page') || 1;
                loadProducts(getProductParams(), page);
            });
        });
    }

    function bindOfferPagination() {
        document.querySelectorAll('#offers-table-wrapper .pagination .page-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url  = new URL(this.href);
                const page = url.searchParams.get('offers_page') || 1;
                loadOffers(getOfferParams(), page);
            });
        });
    }

    /* ── Form submit intercept ── */
    document.getElementById('products-filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        loadProducts(getProductParams(), 1);
    });

    document.getElementById('offers-filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        loadOffers(getOfferParams(), 1);
    });

    /* ── Initial pagination binding ── */
    bindProductPagination();
    bindOfferPagination();
})();
</script>
@endpush
