@extends('admin.layouts.app')
@section('title', 'Customers')

@push('styles')
<style>
/* ── Reset & base ── */
.cw { padding: 24px; max-width: 1400px; margin: 0 auto; }

/* ── Stats grid ── */
.cw-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.cs { background: white; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px 22px; position: relative; overflow: hidden; transition: box-shadow 0.2s; }
.cs:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
.cs::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:14px 14px 0 0; }
.cs.cs-blue::before   { background: linear-gradient(90deg,#08437b,#3b82f6); }
.cs.cs-green::before  { background: linear-gradient(90deg,#10b981,#34d399); }
.cs.cs-purple::before { background: linear-gradient(90deg,#8b5cf6,#a78bfa); }
.cs.cs-amber::before  { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.cs-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 12px; }
.cs.cs-blue .cs-icon   { background: #eff6ff; color: #2563eb; }
.cs.cs-green .cs-icon  { background: #ecfdf5; color: #10b981; }
.cs.cs-purple .cs-icon { background: #f5f3ff; color: #7c3aed; }
.cs.cs-amber .cs-icon  { background: #fffbeb; color: #d97706; }
.cs-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.05em; }
.cs-val { font-size: 28px; font-weight: 800; color: #111827; margin: 4px 0 0; line-height: 1; }
.cs-sub { font-size: 12px; color: #9ca3af; margin-top: 4px; }

/* ── Toolbar ── */
.cw-toolbar { background: white; border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px 20px; margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.cw-search { flex: 1; min-width: 220px; position: relative; }
.cw-search input { width: 100%; padding: 9px 12px 9px 36px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; background: #f9fafb; transition: all 0.2s; }
.cw-search input:focus { outline: none; border-color: #08437b; background: white; box-shadow: 0 0 0 3px rgba(8,67,123,0.08); }
.cw-search i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px; }
.cw-select { padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; background: #f9fafb; min-width: 150px; transition: all 0.2s; }
.cw-select:focus { outline: none; border-color: #08437b; background: white; }

/* ── Buttons ── */
.cb { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; white-space: nowrap; }
.cb:hover { opacity: 0.88; transform: translateY(-1px); }
.cb-blue  { background: #08437b; color: white; }
.cb-ghost { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
.cb-ghost:hover { background: #e5e7eb; opacity: 1; transform: none; color: #374151; }
.cb-sm { padding: 7px 12px; font-size: 12px; }

/* ── Table card ── */
.cw-card { background: white; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; }
.cw-card-head { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; background: #fafafa; flex-wrap: wrap; gap: 10px; }
.cw-card-head h2 { font-size: 15px; font-weight: 700; color: #111827; margin: 0; display: flex; align-items: center; gap: 8px; }
.cw-card-head h2 i { color: #08437b; }
.cw-showing { font-size: 12px; color: #9ca3af; }

/* ════════════════════════════════════
   TABLE ROWS — MODERN REDESIGN
════════════════════════════════════ */

/* Row base */
.ctr { transition: background 0.15s, box-shadow 0.15s; }
.ctr td { padding: 16px 18px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.ctr:last-child td { border-bottom: none; }

/* Row hover */
.ctr:hover td { background: #f8fafc; }

/* ── Top customer row ── */
/* .ctr-top td {
    background: linear-gradient(90deg, #fffbeb 0%, #fefce8 30%, #ffffff 70%) !important;
    border-bottom: 1px solid #fde68a !important;
} */
/* .ctr-top:hover td {
    background: linear-gradient(90deg, #fef3c7 0%, #fffbeb 40%, #f9fafb 70%) !important;
} */
.ctr-top td:first-child {
    border-left: 3px solid #f59e0b;
}

/* ── Customer cell ── */
.td-customer { min-width: 240px; }
.ci-wrap { display: flex; align-items: center; gap: 13px; }

/* Avatar */
.ci-av {
    width: 46px; height: 46px; border-radius: 14px;
    font-size: 18px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; border: 2px solid transparent;
    position: relative; transition: transform 0.2s;
}
.ctr:hover .ci-av { transform: scale(1.05); }

/* Crown for top customer */
.ci-crown {
    position: absolute; top: -10px; right: -8px;
    font-size: 14px; line-height: 1;
    filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2));
}

/* Name row */
.ci-name-row { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 3px; }
.ci-name {
    font-size: 14px; font-weight: 700; color: #111827;
    text-decoration: none; transition: color 0.15s;
}
.ci-name:hover { color: #08437b; }

/* Badges */
.badge-top {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e; font-size: 10px; font-weight: 700;
    padding: 2px 8px; border-radius: 20px;
    border: 1px solid #fcd34d;
}
.badge-new {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46; font-size: 10px; font-weight: 700;
    padding: 2px 8px; border-radius: 20px;
    border: 1px solid #6ee7b7;
}

/* Sub info */
.ci-email, .ci-phone {
    font-size: 12px; color: #9ca3af;
    display: flex; align-items: center; gap: 5px;
    margin-top: 1px;
}
.ci-email i, .ci-phone i { font-size: 10px; opacity: 0.7; }

/* ── Group badges ── */
.td-groups { min-width: 130px; }
.gbadge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    margin: 2px 3px 2px 0; white-space: nowrap;
}
.gbadge-dot {
    width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0;
}
.g-hd { background: #d1fae5; color: #065f46; }
.g-hd .gbadge-dot { background: #10b981; }
.g-sh { background: #fef3c7; color: #92400e; }
.g-sh .gbadge-dot { background: #f59e0b; }
.g-rs { background: #ede9fe; color: #5b21b6; }
.g-rs .gbadge-dot { background: #8b5cf6; }
.g-df { background: #f1f5f9; color: #475569; }
.g-df .gbadge-dot { background: #94a3b8; }
.no-group { font-size: 12px; color: #d1d5db; font-style: italic; }

/* ── Orders cell ── */
.td-orders { min-width: 110px; }
.ord-pill {
    display: inline-flex; align-items: baseline; gap: 4px;
}
.ord-num {
    font-size: 20px; font-weight: 800; color: #1d4ed8; line-height: 1;
}
.ord-lbl { font-size: 11px; color: #93c5fd; font-weight: 600; }
.ord-last { font-size: 11px; color: #9ca3af; margin-top: 3px; }
.ord-zero { font-size: 12px; color: #d1d5db; font-style: italic; }

/* ── Spent cell ── */
.td-spent { min-width: 150px; }
.spent-amt {
    font-size: 16px; font-weight: 800; line-height: 1; margin-bottom: 6px;
}
.spent-top  { color: #d97706; }
.spent-has  { color: #111827; }
.spent-none { color: #d1d5db; font-size: 13px; }

.spent-bar-track {
    height: 5px; background: #f1f5f9;
    border-radius: 10px; overflow: hidden; margin-bottom: 3px;
}
.spent-bar-fill {
    height: 5px; border-radius: 10px;
    transition: width 0.8s cubic-bezier(0.4,0,0.2,1);
}
.bar-blue { background: linear-gradient(90deg, #3b82f6, #08437b); }
.bar-gold { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
.spent-pct { font-size: 10px; color: #9ca3af; font-weight: 500; }

/* ── Joined cell ── */
.td-joined { min-width: 110px; white-space: nowrap; }
.joined-date { font-size: 13px; font-weight: 600; color: #374151; }
.joined-rel  { font-size: 11px; color: #9ca3af; margin-top: 3px; }

/* ── Actions cell ── */
.td-actions { white-space: nowrap; }
.act-wrap { display: flex; gap: 6px; align-items: center; }

.act-btn {
    width: 34px; height: 34px; border-radius: 9px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; border: none; cursor: pointer;
    transition: all 0.18s; text-decoration: none;
}
.act-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }

.act-view { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.act-view:hover { background: #dbeafe; color: #1d4ed8; }

.act-edit { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.act-edit:hover { background: #dcfce7; color: #15803d; }

.act-del { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }
.act-del:hover { background: #ffe4e6; color: #be123c; }

/* ── Header cells ── */
.ct thead th {
    background: #f8fafc; padding: 12px 18px;
    font-size: 11px; font-weight: 700; color: #64748b;
    text-transform: uppercase; letter-spacing: 0.06em;
    border-bottom: 2px solid #e2e8f0;
    text-align: left; white-space: nowrap;
}
.ct thead th.sortable { cursor: pointer; user-select: none; transition: all 0.15s; }
.ct thead th.sortable:hover { background: #eef2f7; color: #08437b; }
.ct thead th.sort-active { color: #08437b; background: #eef5ff; }
.sort-icon { margin-left: 5px; font-size: 10px; }

/* ── Empty state ── */
.empty-state {
    padding: 70px 20px; text-align: center;
}
.empty-icon {
    width: 64px; height: 64px; background: #f1f5f9;
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
    font-size: 24px; color: #cbd5e1;
}
.empty-title { font-size: 16px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.empty-sub   { font-size: 13px; color: #9ca3af; }

/* ── Avatar ── */
.cav { width: 40px; height: 40px; border-radius: 50%; font-weight: 700; font-size: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; }

/* ── Group badges ── */
.gb { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 700; margin: 2px 2px 2px 0; white-space: nowrap; }
.gb-hd { background: #d1fae5; color: #065f46; }
.gb-sh { background: #fef3c7; color: #92400e; }
.gb-rs { background: #ede9fe; color: #5b21b6; }
.gb-df { background: #f1f5f9; color: #475569; }

/* ── Spent bar ── */
.spent-bar-wrap { height: 4px; background: #f1f5f9; border-radius: 4px; margin-top: 5px; overflow: hidden; }
.spent-bar { height: 4px; border-radius: 4px; background: linear-gradient(90deg, #08437b, #3b82f6); transition: width 0.6s ease; }

/* ── Modal ── */
.cm-overlay { display: none; position: fixed; inset: 0; background: rgba(15,20,30,0.55); z-index: 9000; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
.cm-overlay.open { display: flex; }
.cm { background: white; border-radius: 18px; width: 100%; max-width: 540px; max-height: 92vh; overflow-y: auto; box-shadow: 0 30px 80px rgba(0,0,0,0.25); margin: 16px; }
.cm-head { padding: 22px 24px 0; display: flex; align-items: center; justify-content: space-between; }
.cm-head h3 { font-size: 18px; font-weight: 800; color: #111827; margin: 0; }
.cm-close { background: #f3f4f6; border: none; width: 34px; height: 34px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 16px; transition: all 0.2s; }
.cm-close:hover { background: #e5e7eb; color: #111827; }
.cm-body { padding: 20px 24px 26px; }
.cm-divider { height: 1px; background: #f1f5f9; margin: 18px 0; }
.cf-label { font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 5px; display: block; }
.cf-input { width: 100%; border: 1px solid #d1d5db; border-radius: 9px; padding: 10px 13px; font-size: 13px; background: white; transition: all 0.2s; }
.cf-input:focus { border-color: #08437b; box-shadow: 0 0 0 3px rgba(8,67,123,0.08); outline: none; }
.cf-group { margin-bottom: 16px; }
.cf-err { font-size: 11px; color: #ef4444; margin-top: 4px; display: none; }
.cf-hint { font-size: 11px; color: #9ca3af; margin-top: 4px; }
.cf-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

/* ── Group checkboxes ── */
.group-checks { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px; }
.gc-item { display: flex; align-items: center; gap: 7px; padding: 7px 13px; border: 1.5px solid #e5e7eb; border-radius: 9px; cursor: pointer; transition: all 0.2s; user-select: none; font-size: 13px; color: #374151; font-weight: 500; }
.gc-item:hover { border-color: #08437b; background: #f0f7ff; }
.gc-item input { accent-color: #08437b; }
.gc-item.checked { border-color: #08437b; background: #eff6ff; color: #08437b; font-weight: 600; }

/* ── Spinner ── */
.ld-spin { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.35); border-top-color: white; border-radius: 50%; animation: spn 0.65s linear infinite; display: inline-block; vertical-align: middle; }
@keyframes spn { to { transform: rotate(360deg); } }

/* ── Pagination ── */
.pag-wrap { padding: 16px 20px; border-top: 1px solid #f1f5f9; }

/* ── Responsive ── */
@media(max-width:900px)  { .cw-stats { grid-template-columns: repeat(2,1fr); } }
@media(max-width:600px)  { .cw-stats { grid-template-columns: 1fr; } .cw-toolbar { flex-direction:column; } .cf-row2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="cw">

    {{-- ── Stats ── --}}
    <div class="cw-stats">
        <div class="cs cs-blue">
            <div class="cs-icon"><i class="fas fa-users"></i></div>
            <div class="cs-lbl">Total Customers</div>
            <div class="cs-val">{{ number_format($totalCustomers) }}</div>
            <div class="cs-sub">All registered accounts</div>
        </div>
        <div class="cs cs-green">
            <div class="cs-icon"><i class="fas fa-pound-sign"></i></div>
            <div class="cs-lbl">Total Revenue</div>
            <div class="cs-val">£{{ number_format($totalRevenue, 0) }}</div>
            <div class="cs-sub">Excl. cancelled orders</div>
        </div>
        <div class="cs cs-purple">
            <div class="cs-icon"><i class="fas fa-chart-line"></i></div>
            <div class="cs-lbl">Avg Order Value</div>
            <div class="cs-val">£{{ number_format($avgOrderValue, 2) }}</div>
            <div class="cs-sub">Across all orders</div>
        </div>
        <div class="cs cs-amber">
            <div class="cs-icon"><i class="fas fa-user-check"></i></div>
            <div class="cs-lbl">Active Groups</div>
            <div class="cs-val">{{ $groups->count() }}</div>
            <div class="cs-sub">Customer segments</div>
        </div>
    </div>

    {{-- ── Toolbar ── --}}
    <div class="cw-toolbar">
        <div class="cw-search">
            <i class="fas fa-search"></i>
            <input type="text" id="custSearch" placeholder="Search name, email, phone…" value="{{ request('search') }}">
        </div>
        <select class="cw-select" id="custGroup">
            <option value="">All Groups</option>
            @foreach($groups as $g)
                <option value="{{ $g->id }}" {{ request('group') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
            @endforeach
        </select>
        <button class="cb cb-blue" id="openCreate">
            <i class="fas fa-user-plus"></i> New Customer
        </button>
    </div>

    {{-- ── Table Card ── --}}
    <div class="cw-card">
        <div class="cw-card-head">
            <h2><i class="fas fa-users"></i> All Customers</h2>
            <span class="cw-showing" id="custShowing">
                Showing {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }}
            </span>
        </div>
        <div style="overflow-x:auto;">
            <table class="ct w-100" id="custTable">
                <thead>
                    <tr>
                        <th class="sortable {{ $sort === 'name' ? 'sort-active' : '' }}" data-sort="name">
                            Customer
                            <span class="sort-icon">
                                @if($sort === 'name')
                                    <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>
                        <th>Groups</th>
                        <th class="sortable {{ $sort === 'most_orders' ? 'sort-active' : '' }}" data-sort="most_orders" style="text-align:center;">
                            Orders
                            <span class="sort-icon">
                                @if($sort === 'most_orders')
                                    <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>
                        <th class="sortable {{ $sort === 'most_spent' ? 'sort-active' : '' }}" data-sort="most_spent">
                            Total Spent
                            <span class="sort-icon">
                                @if($sort === 'most_spent')
                                    <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>
                        <th class="sortable {{ $sort === 'latest' ? 'sort-active' : '' }}" data-sort="latest">
                            Joined
                            <span class="sort-icon">
                                @if($sort === 'latest')
                                    <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="custBody">
                    @include('admin.customers._table_rows', compact('customers','sort','direction','topSpenderId'))
                </tbody>
            </table>
        </div>
        <div class="pag-wrap" id="custPag">
            {{ $customers->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>

{{-- ══════════ CREATE MODAL ══════════ --}}
<div class="cm-overlay" id="createModal">
    <div class="cm">
        <div class="cm-head">
            <h3>
                <i class="fas fa-user-plus" style="color:#08437b;font-size:16px;margin-right:8px;"></i>
                New Customer
            </h3>
            <button class="cm-close" data-close="createModal">✕</button>
        </div>
        <div class="cm-body">
            <form id="createForm" autocomplete="off">
                @csrf
                <div class="cf-row2">
                    <div class="cf-group" style="grid-column:1/-1;">
                        <label class="cf-label">Full Name *</label>
                        <input type="text" name="name" class="cf-input" placeholder="e.g. Jane Smith">
                        <div class="cf-err" id="err-c-name"></div>
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Email Address *</label>
                        <input type="email" name="email" class="cf-input" placeholder="jane@example.com">
                        <div class="cf-err" id="err-c-email"></div>
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Phone</label>
                        <input type="text" name="mobile" class="cf-input" placeholder="+44 7700 000000">
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Password *</label>
                        <input type="password" name="password" class="cf-input" placeholder="Min 6 characters">
                        <div class="cf-err" id="err-c-password"></div>
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="cf-input" placeholder="Repeat password">
                    </div>
                </div>
                <div class="cm-divider"></div>
                <div class="cf-group">
                    <label class="cf-label">Assign to Customer Groups</label>
                    <div class="group-checks" id="createGroups">
                        @foreach($groups as $g)
                            <label class="gc-item {{ $g->slug === 'home-delivery' ? 'checked' : '' }}">
                                <input type="checkbox" name="groups[]" value="{{ $g->id }}"
                                    {{ $g->slug === 'home-delivery' ? 'checked' : '' }}>
                                {{ $g->name }}
                            </label>
                        @endforeach
                    </div>
                    <div class="cf-hint">Defaults to <strong>Home Delivery</strong> if none selected.</div>
                </div>
                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="cb cb-blue" id="createBtn" style="flex:1;justify-content:center;">
                        <i class="fas fa-user-plus"></i> Create Customer
                    </button>
                    <button type="button" class="cb cb-ghost" data-close="createModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════ EDIT MODAL ══════════ --}}
<div class="cm-overlay" id="editModal">
    <div class="cm">
        <div class="cm-head">
            <h3>
                <i class="fas fa-user-edit" style="color:#08437b;font-size:16px;margin-right:8px;"></i>
                Edit Customer
            </h3>
            <button class="cm-close" data-close="editModal">✕</button>
        </div>
        <div class="cm-body">
            <form id="editForm" autocomplete="off">
                @csrf
                <input type="hidden" id="editId">
                <div class="cf-row2">
                    <div class="cf-group" style="grid-column:1/-1;">
                        <label class="cf-label">Full Name *</label>
                        <input type="text" name="name" id="eName" class="cf-input">
                        <div class="cf-err" id="err-e-name"></div>
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Email Address *</label>
                        <input type="email" name="email" id="eEmail" class="cf-input">
                        <div class="cf-err" id="err-e-email"></div>
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Phone</label>
                        <input type="text" name="mobile" id="eMobile" class="cf-input">
                    </div>
                    <div class="cf-group" style="grid-column:1/-1;">
                        <label class="cf-label">
                            New Password
                            <span style="font-weight:400;color:#9ca3af;text-transform:none;">(leave blank to keep current)</span>
                        </label>
                        <input type="password" name="password" class="cf-input" placeholder="Leave blank to keep current">
                    </div>
                </div>
                <div class="cm-divider"></div>
                <div class="cf-group">
                    <label class="cf-label">Customer Groups</label>
                    <div class="group-checks" id="editGroups">
                        {{-- Rendered by JS --}}
                    </div>
                    <div class="cf-hint">Direct group assignments for this customer.</div>
                </div>

                <div class="cm-divider"></div>
                <div class="cf-group">
                    <label class="cf-label">Company Account
                        <span style="font-weight:400;color:#9ca3af;text-transform:none;">(inherits company's groups & pricing)</span>
                    </label>
                    <select name="company_id" id="eCompany" class="cf-input">
                        <option value="">— No company —</option>
                        {{-- Populated by JS --}}
                    </select>
                    <div class="cf-hint" id="eCompanyHint" style="display:none;color:#f59e0b;">
                        <i class="fas fa-info-circle"></i> This customer will inherit all groups assigned to the selected company.
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="cb cb-blue" id="editBtn" style="flex:1;justify-content:center;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <button type="button" class="cb cb-ghost" data-close="editModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const CSRF = $('meta[name="csrf-token"]').attr('content');
    let currentSort = '{{ $sort }}';
    let currentDir  = '{{ $direction }}';

    // ── Modal helpers ─────────────────────────────────────────────────────
    const openModal  = id => { $('#' + id).addClass('open'); $('body').css('overflow','hidden'); };
    const closeModal = id => { $('#' + id).removeClass('open'); $('body').css('overflow',''); };

    $('[data-close]').on('click', function () { closeModal($(this).data('close')); });
    $('.cm-overlay').on('click', function (e) {
        if ($(e.target).hasClass('cm-overlay')) closeModal($(this).attr('id'));
    });
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') $('.cm-overlay.open').each(function () { closeModal($(this).attr('id')); });
    });

    const clearErrors = prefix => {
        $('[id^="err-' + prefix + '"]').text('').hide();
    };
    const showErrors = (prefix, errors) => {
        $.each(errors, (field, msgs) => $('#err-' + prefix + '-' + field).text(msgs[0]).show());
    };

    $(document).on('change', '.gc-item input', function () {
        $(this).closest('.gc-item').toggleClass('checked', this.checked);
    });

    // ── Load table via AJAX ───────────────────────────────────────────────
    let searchTimer;

    function loadCustomers(page) {
        const params = {
            search:    $('#custSearch').val(),
            sort:      currentSort,
            direction: currentDir,
            group:     $('#custGroup').val(),
            page:      page || 1,
        };

        $('#custBody').css('opacity', '0.5');

        $.get('{{ route("admin.customers.index") }}', params, function (r) {
            $('#custBody').html(r.html).css('opacity', '1');
            $('#custPag').html(r.pagination);
            $('#custShowing').text('Showing ' + r.from + '–' + r.to + ' of ' + r.total);
            updateSortUI();
            bindPagination();
        });
    }

    function bindPagination() {
        $('#custPag').off('click', 'a.page-link').on('click', 'a.page-link', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (!href || href === '#') return;
            const page = new URL(href, location.href).searchParams.get('page');
            if (page) loadCustomers(page);
        });
    }

    bindPagination();

    $('#custSearch').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => loadCustomers(1), 350);
    });

    $('#custGroup').on('change', () => loadCustomers(1));

    // ── Column header sorting ─────────────────────────────────────────────
    function updateSortUI() {
        $('#custTable thead th.sortable').each(function () {
            const col = $(this).data('sort');
            $(this).removeClass('sort-active');
            $(this).find('.sort-icon i').attr('class', 'fas fa-sort');

            if (col === currentSort) {
                $(this).addClass('sort-active');
                $(this).find('.sort-icon i').attr('class',
                    'fas fa-sort-' + (currentDir === 'asc' ? 'up' : 'down'));
            }
        });
    }

    $('#custTable').on('click', 'th.sortable', function () {
        const col = $(this).data('sort');
        if (col === currentSort) {
            currentDir = currentDir === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = col;
            currentDir  = col === 'name' ? 'asc' : 'desc';
        }
        updateSortUI();
        loadCustomers(1);
    });

    // ── Create customer ───────────────────────────────────────────────────
    $('#openCreate').on('click', function () {
        $('#createForm')[0].reset();
        clearErrors('c');
        $('#createGroups .gc-item').each(function () {
            const isHD = $(this).find('input').data('slug') === 'home-delivery' ||
                         $(this).find('input').closest('label').text().trim().toLowerCase().includes('home');
            // restore default checked state from the page load
            const cb = $(this).find('input');
            $(this).toggleClass('checked', cb.prop('defaultChecked') || false);
            cb.prop('checked', cb.prop('defaultChecked') || false);
        });
        openModal('createModal');
    });

    $('#createForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors('c');
        const $btn = $('#createBtn').prop('disabled', true).html('<span class="ld-spin"></span> Creating…');

        $.ajax({
            url:  '{{ route("admin.customers.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: r => {
                closeModal('createModal');
                Swal.fire({ icon:'success', title:'Created!', text:r.message, timer:1500, showConfirmButton:false });
                loadCustomers(1);
            },
            error: xhr => {
                if (xhr.status === 422) showErrors('c', xhr.responseJSON.errors);
                else Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message ?? 'Failed', confirmButtonColor:'#08437b' });
            },
            complete: () => $btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Create Customer'),
        });
    });

    // ── Edit customer ─────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        clearErrors('e');

        $.get('{{ url("admin/customers") }}/' + id + '/edit', function (r) {
            if (!r.success) return;
            $('#editId').val(r.user.id);
            $('#eName').val(r.user.name);
            $('#eEmail').val(r.user.email);
            $('#eMobile').val(r.user.mobile ?? '');
            $('#editForm input[name="password"]').val('');

            // Groups
            let html = '';
            $.each(r.groups, function (i, g) {
                const chk = r.user_group_ids.includes(g.id) ? 'checked' : '';
                const cls = chk ? 'gc-item checked' : 'gc-item';
                html += `<label class="${cls}">
                    <input type="checkbox" name="groups[]" value="${g.id}" ${chk}>
                    ${g.name}
                </label>`;
            });
            $('#editGroups').html(html);

            // Companies dropdown
            let coHtml = '<option value="">— No company —</option>';
            $.each(r.companies, function (i, c) {
                const sel = r.user_company && r.user_company.id === c.id ? 'selected' : '';
                coHtml += `<option value="${c.id}" ${sel}>${c.name}</option>`;
            });
            $('#eCompany').html(coHtml);

            // Show hint if company selected
            if (r.user_company) {
                $('#eCompanyHint').show();
            } else {
                $('#eCompanyHint').hide();
            }

            openModal('editModal');
        });
    });

    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors('e');
        const id   = $('#editId').val();
        const $btn = $('#editBtn').prop('disabled', true).html('<span class="ld-spin"></span> Saving…');

        $.ajax({
            url:  '{{ url("admin/customers") }}/' + id,
            type: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            success: r => {
                closeModal('editModal');
                Swal.fire({ icon:'success', title:'Updated!', text:r.message, timer:1500, showConfirmButton:false });
                loadCustomers();
            },
            error: xhr => {
                if (xhr.status === 422) showErrors('e', xhr.responseJSON.errors);
                else Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message ?? 'Failed', confirmButtonColor:'#08437b' });
            },
            complete: () => $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Changes'),
        });
    });

    // ── Delete customer ───────────────────────────────────────────────────
    $(document).on('click', '.btn-del', function () {
        const id   = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: `Delete ${name}?`,
            html:  `<p style="color:#6b7280;font-size:14px;">Their orders will be kept. This action soft-deletes the account.</p>`,
            icon:  'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor:  '#6b7280',
            confirmButtonText: 'Yes, Delete',
        }).then(res => {
            if (!res.isConfirmed) return;
            $.ajax({
                url:  '{{ url("admin/customers") }}/' + id,
                type: 'DELETE',
                data: { _token: CSRF },
                success: r => {
                    Swal.fire({ icon:'success', title:'Deleted', text:r.message, timer:1400, showConfirmButton:false });
                    loadCustomers();
                },
                error: xhr => Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message ?? 'Failed', confirmButtonColor:'#08437b' }),
            });
        });
    });

    // Show hint when company selected
    $(document).on('change', '#eCompany', function () {
        if ($(this).val()) {
            $('#eCompanyHint').show();
        } else {
            $('#eCompanyHint').hide();
        }
    });
});
</script>
@endpush
