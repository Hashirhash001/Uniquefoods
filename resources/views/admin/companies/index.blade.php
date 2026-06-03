@extends('admin.layouts.app')
@section('title', 'Companies')

@push('styles')
<style>
.cw { padding: 24px; max-width: 1400px; margin: 0 auto; }
.cw-toolbar { background: white; border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px 20px; margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.cw-search { flex: 1; min-width: 220px; position: relative; }
.cw-search input { width: 100%; padding: 9px 12px 9px 36px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; background: #f9fafb; transition: all 0.2s; }
.cw-search input:focus { outline: none; border-color: #08437b; background: white; box-shadow: 0 0 0 3px rgba(8,67,123,0.08); }
.cw-search i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px; }
.cw-card { background: white; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; }
.cw-card-head { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; background: #fafafa; flex-wrap: wrap; gap: 10px; }
.cw-card-head h2 { font-size: 15px; font-weight: 700; color: #111827; margin: 0; display: flex; align-items: center; gap: 8px; }
.cw-card-head h2 i { color: #08437b; }
.pag-wrap { padding: 16px 20px; border-top: 1px solid #f1f5f9; }
.cb { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; white-space: nowrap; }
.cb:hover { opacity: 0.88; transform: translateY(-1px); }
.cb-blue { background: #08437b; color: white; }
.cb-ghost { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
.cb-ghost:hover { background: #e5e7eb; opacity: 1; transform: none; color: #374151; }
.co-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.co-table thead th { background: #f8fafc; padding: 12px 18px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 2px solid #e2e8f0; text-align: left; white-space: nowrap; }
.co-table tbody td { padding: 14px 18px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.co-table tbody tr:last-child td { border-bottom: none; }
.co-table tbody tr:hover td { background: #f8fafc; }
.act-btn { width: 34px; height: 34px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; border: none; cursor: pointer; transition: all 0.18s; text-decoration: none; }
.act-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
.act-edit { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.act-del  { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }
.gbadge { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; margin: 2px 2px 2px 0; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.member-av { width: 26px; height: 26px; border-radius: 50%; background: #08437b; color: white; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; margin-right: -6px; border: 2px solid white; }
/* Modal */
.cm-overlay { display: none; position: fixed; inset: 0; background: rgba(15,20,30,0.55); z-index: 9000; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
.cm-overlay.open { display: flex; }
.cm { background: white; border-radius: 18px; width: 100%; max-width: 600px; max-height: 92vh; overflow-y: auto; box-shadow: 0 30px 80px rgba(0,0,0,0.25); margin: 16px; }
.cm-head { padding: 22px 24px 0; display: flex; align-items: center; justify-content: space-between; }
.cm-head h3 { font-size: 18px; font-weight: 800; color: #111827; margin: 0; }
.cm-close { background: #f3f4f6; border: none; width: 34px; height: 34px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 16px; transition: all 0.2s; }
.cm-close:hover { background: #e5e7eb; color: #111827; }
.cm-body { padding: 20px 24px 26px; }
.cm-divider { height: 1px; background: #f1f5f9; margin: 18px 0; }
.cf-label { font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 5px; display: block; }
.cf-input { width: 100%; border: 1px solid #d1d5db; border-radius: 9px; padding: 10px 13px; font-size: 13px; background: white; transition: all 0.2s; font-family: inherit; }
.cf-input:focus { border-color: #08437b; box-shadow: 0 0 0 3px rgba(8,67,123,0.08); outline: none; }
.cf-group { margin-bottom: 16px; }
.cf-err { font-size: 11px; color: #ef4444; margin-top: 4px; display: none; }
.cf-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.gc-item { display: flex; align-items: center; gap: 7px; padding: 7px 13px; border: 1.5px solid #e5e7eb; border-radius: 9px; cursor: pointer; transition: all 0.2s; user-select: none; font-size: 13px; color: #374151; font-weight: 500; }
.gc-item:hover { border-color: #08437b; background: #f0f7ff; }
.gc-item input { accent-color: #08437b; }
.gc-item.checked { border-color: #08437b; background: #eff6ff; color: #08437b; font-weight: 600; }
.group-checks { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px; }
/* Member list inside modal */
.member-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
.member-row:last-child { border-bottom: none; }
.member-info { display: flex; align-items: center; gap: 10px; }
.member-av-lg { width: 34px; height: 34px; border-radius: 10px; background: #08437b; color: white; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.member-name { font-weight: 600; color: #111827; }
.member-email { font-size: 11px; color: #9ca3af; }
.role-badge { padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700; }
.role-owner  { background: #fef3c7; color: #92400e; }
.role-member { background: #f1f5f9; color: #475569; }
.rm-member-btn { background: #fee2e2; color: #991b1b; border: none; border-radius: 6px; padding: 4px 8px; cursor: pointer; font-size: 11px; transition: background 0.15s; }
.rm-member-btn:hover { background: #fecaca; }
/* User search dropdown */
.user-search-wrap { position: relative; }
.user-search-results { display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-height: 200px; overflow-y: auto; z-index: 999; }
.usr-item { padding: 9px 13px; cursor: pointer; font-size: 13px; border-bottom: 1px solid #f3f4f6; transition: background 0.15s; }
.usr-item:last-child { border-bottom: none; }
.usr-item:hover { background: #f0f9ff; }
.usr-name { font-weight: 600; color: #111827; }
.usr-email { font-size: 11px; color: #9ca3af; }
.ld-spin { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.35); border-top-color: white; border-radius: 50%; animation: spn 0.65s linear infinite; display: inline-block; vertical-align: middle; }
@keyframes spn { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="cw">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
        <div>
            <h1 style="font-size:20px;font-weight:800;color:#111827;margin:0 0 4px;">Companies</h1>
            <p style="font-size:13px;color:#6b7280;margin:0;">Manage company accounts and their linked users</p>
        </div>
        <button class="cb cb-blue" id="openCreate">
            <i class="fas fa-building"></i> New Company
        </button>
    </div>

    <div class="cw-toolbar">
        <div class="cw-search">
            <i class="fas fa-search"></i>
            <input type="text" id="coSearch" placeholder="Search company name or email…">
        </div>
    </div>

    <div class="cw-card">
        <div class="cw-card-head">
            <h2><i class="fas fa-building"></i> All Companies</h2>
            <span style="font-size:12px;color:#9ca3af;" id="coShowing"></span>
        </div>
        <div style="overflow-x:auto;">
            <table class="co-table">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Groups</th>
                        <th>Members</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="coBody">
                    @forelse($companies as $company)
                        @include('admin.companies._row', compact('company'))
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:40px;color:#9ca3af;">No companies yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pag-wrap" id="coPag">
            {{ $companies->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- ══════ CREATE / EDIT MODAL ══════ --}}
<div class="cm-overlay" id="companyModal">
    <div class="cm">
        <div class="cm-head">
            <h3 id="modalTitle"><i class="fas fa-building" style="color:#08437b;font-size:15px;margin-right:8px;"></i> New Company</h3>
            <button class="cm-close" data-close="companyModal">✕</button>
        </div>
        <div class="cm-body">
            <form id="companyForm" autocomplete="off">
                @csrf
                <input type="hidden" id="companyId">

                <div class="cf-row2">
                    <div class="cf-group" style="grid-column:1/-1;">
                        <label class="cf-label">Company Name *</label>
                        <input type="text" name="name" id="coName" class="cf-input" placeholder="e.g. Acme Ltd">
                        <div class="cf-err" id="err-name"></div>
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Primary Email *</label>
                        <input type="email" name="primary_email" id="coPrimaryEmail" class="cf-input" placeholder="contact@company.com">
                        <div class="cf-err" id="err-primary_email"></div>
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Phone</label>
                        <input type="text" name="phone" id="coPhone" class="cf-input" placeholder="+44 ...">
                    </div>
                    <div class="cf-group" style="grid-column:1/-1;">
                        <label class="cf-label">Address</label>
                        <input type="text" name="address" id="coAddress" class="cf-input" placeholder="Street, City, Postcode">
                    </div>
                </div>

                <div class="cm-divider"></div>

                {{-- Customer Groups --}}
                <div class="cf-group">
                    <label class="cf-label">Customer Groups <span style="font-weight:400;color:#9ca3af;text-transform:none;">(all members inherit these)</span></label>
                    <div class="group-checks" id="coGroups">
                        @foreach($groups as $g)
                            <label class="gc-item">
                                <input type="checkbox" name="group_ids[]" value="{{ $g->id }}">
                                {{ $g->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="cm-divider"></div>

                {{-- Members --}}
                <div class="cf-group">
                    <label class="cf-label">Members</label>

                    {{-- Search to add --}}
                    <div class="user-search-wrap" style="margin-bottom:12px;">
                        <input type="text" id="userSearchInput" class="cf-input"
                               placeholder="Search customer by name or email to add…"
                               autocomplete="off">
                        <div class="user-search-results" id="userSearchResults"></div>
                    </div>

                    {{-- Member list --}}
                    <div id="memberList" style="min-height:40px;">
                        <p style="color:#9ca3af;font-size:13px;font-style:italic;" id="noMembersMsg">No members added yet.</p>
                    </div>
                </div>

                <div class="cm-divider"></div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="cb cb-blue" id="companySubmitBtn" style="flex:1;justify-content:center;">
                        <i class="fas fa-save"></i> Save Company
                    </button>
                    <button type="button" class="cb cb-ghost" data-close="companyModal">Cancel</button>
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

    // ── Modal helpers ──
    const openModal  = id => { $('#'+id).addClass('open'); $('body').css('overflow','hidden'); };
    const closeModal = id => { $('#'+id).removeClass('open'); $('body').css('overflow',''); };
    $('[data-close]').on('click', function () { closeModal($(this).data('close')); });
    $('.cm-overlay').on('click', function(e) {
        if ($(e.target).hasClass('cm-overlay')) closeModal($(this).attr('id'));
    });

    $(document).on('change', '.gc-item input', function () {
        $(this).closest('.gc-item').toggleClass('checked', this.checked);
    });

    // Pending members array for the form
    let pendingMembers = []; // [{id, name, email, role}]

    function renderMemberList() {
        if (!pendingMembers.length) {
            $('#memberList').html('<p style="color:#9ca3af;font-size:13px;font-style:italic;" id="noMembersMsg">No members added yet.</p>');
            return;
        }
        let html = pendingMembers.map(m => `
            <div class="member-row" data-uid="${m.id}">
                <div class="member-info">
                    <div class="member-av-lg">${m.name.charAt(0).toUpperCase()}</div>
                    <div>
                        <div class="member-name">${m.name}</div>
                        <div class="member-email">${m.email}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <select class="cf-input member-role-select" data-uid="${m.id}"
                            style="width:100px;padding:5px 8px;font-size:12px;">
                        <option value="member" ${m.role==='member'?'selected':''}>Member</option>
                        <option value="owner"  ${m.role==='owner' ?'selected':''}>Owner</option>
                    </select>
                    <button type="button" class="rm-member-btn" data-uid="${m.id}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `).join('');
        $('#memberList').html(html);
    }

    // Role change
    $(document).on('change', '.member-role-select', function () {
        const uid = $(this).data('uid');
        const m = pendingMembers.find(x => x.id == uid);
        if (m) m.role = $(this).val();
    });

    // Remove member
    $(document).on('click', '.rm-member-btn', function () {
        const uid = $(this).data('uid');
        pendingMembers = pendingMembers.filter(x => x.id != uid);
        renderMemberList();
    });

    // ── User search ──
    let userSearchTimer;
    $('#userSearchInput').on('input', function () {
        clearTimeout(userSearchTimer);
        const q = $.trim($(this).val());
        if (q.length < 2) { $('#userSearchResults').hide(); return; }

        userSearchTimer = setTimeout(() => {
            $.get('{{ route("admin.customers.index") }}', { search: q, _ajax_user_search: 1 }, function(r) {
                // We'll use a dedicated endpoint instead
            });

            // Use the existing customer search via index with ajax
            $.ajax({
                url: '{{ url("admin/customers") }}',
                data: { search: q },
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(r) {
                    // Parse user list from HTML response isn't ideal,
                    // we handle this with a dedicated search below
                }
            });
        }, 250);
    });

    // ── Open Create ──
    $('#openCreate').on('click', function () {
        $('#companyForm')[0].reset();
        $('#companyId').val('');
        $('#modalTitle').html('<i class="fas fa-building" style="color:#08437b;font-size:15px;margin-right:8px;"></i> New Company');
        $('#companySubmitBtn').html('<i class="fas fa-save"></i> Save Company');
        $('#coGroups .gc-item').removeClass('checked').find('input').prop('checked', false);
        pendingMembers = [];
        renderMemberList();
        clearErrors();
        openModal('companyModal');
    });

    // ── User search (dedicated) ──
    $('#userSearchInput').off('input').on('input', function () {
        clearTimeout(userSearchTimer);
        const q = $.trim($(this).val());
        if (q.length < 2) { $('#userSearchResults').hide(); return; }

        userSearchTimer = setTimeout(() => {
            $.get('{{ route("admin.companies.user-search") }}', { q }, function(users) {
                if (!users.length) {
                    $('#userSearchResults').html('<div style="padding:12px;text-align:center;color:#9ca3af;font-size:13px;">No customers found</div>').show();
                    return;
                }
                let html = users.map(u => {
                    const already = pendingMembers.find(m => m.id == u.id);
                    return `<div class="usr-item ${already ? 'opacity-50' : ''}" data-id="${u.id}" data-name="${u.name}" data-email="${u.email}">
                        <div class="usr-name">${u.name} ${already ? '<span style="color:#9ca3af;">(already added)</span>' : ''}</div>
                        <div class="usr-email">${u.email}</div>
                    </div>`;
                }).join('');
                $('#userSearchResults').html(html).show();
            });
        }, 250);
    });

    $(document).on('click', '.usr-item', function () {
        const id    = $(this).data('id');
        const name  = $(this).data('name');
        const email = $(this).data('email');

        if (pendingMembers.find(m => m.id == id)) {
            $('#userSearchInput').val('');
            $('#userSearchResults').hide();
            return;
        }

        pendingMembers.push({ id, name, email, role: 'member' });
        renderMemberList();
        $('#userSearchInput').val('');
        $('#userSearchResults').hide();
    });

    $(document).on('click', function(e) {
        if (!$('#userSearchInput').is(e.target) && !$('#userSearchResults').is(e.target)) {
            $('#userSearchResults').hide();
        }
    });

    // ── Submit ──
    function clearErrors() {
        $('[id^="err-"]').text('').hide();
    }

    $('#companyForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        const id  = $('#companyId').val();
        const url = id
            ? '{{ url("admin/companies") }}/' + id
            : '{{ url("admin/companies") }}';
        const method = id ? 'PUT' : 'POST';

        const groupIds = [];
        $('#coGroups input:checked').each(function () { groupIds.push($(this).val()); });

        const $btn = $('#companySubmitBtn').prop('disabled', true)
                        .html('<span class="ld-spin"></span> Saving…');

        $.ajax({
            url,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                _token:        CSRF,
                _method:       method,
                name:          $('#coName').val(),
                primary_email: $('#coPrimaryEmail').val(),
                phone:         $('#coPhone').val(),
                address:       $('#coAddress').val(),
                group_ids:     groupIds,
                user_ids:      pendingMembers.map(m => m.id),
                owner_id:      pendingMembers.find(m => m.role === 'owner')?.id ?? null,
            }),
            success: r => {
                closeModal('companyModal');
                Swal.fire({ icon:'success', title: id ? 'Updated!' : 'Created!', text: r.message, timer:1500, showConfirmButton:false });
                loadCompanies(1);
            },
            error: xhr => {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, (field, msgs) => {
                        $('#err-' + field).text(msgs[0]).show();
                    });
                } else {
                    Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message ?? 'Failed', confirmButtonColor:'#08437b' });
                }
            },
            complete: () => $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Company'),
        });
    });

    // ── Edit ──
    $(document).on('click', '.btn-edit-company', function () {
        const id = $(this).data('id');
        clearErrors();

        $.get('{{ url("admin/companies") }}/' + id, function (r) {
            if (!r.success) return;
            const c = r.company;

            $('#companyId').val(c.id);
            $('#coName').val(c.name);
            $('#coPrimaryEmail').val(c.primary_email);
            $('#coPhone').val(c.phone ?? '');
            $('#coAddress').val(c.address ?? '');
            $('#modalTitle').html('<i class="fas fa-building" style="color:#08437b;font-size:15px;margin-right:8px;"></i> Edit Company');
            $('#companySubmitBtn').html('<i class="fas fa-save"></i> Update Company');

            // Groups
            const groupIds = r.groups.map(g => g.id);
            $('#coGroups .gc-item').each(function () {
                const val = parseInt($(this).find('input').val());
                const checked = groupIds.includes(val);
                $(this).toggleClass('checked', checked).find('input').prop('checked', checked);
            });

            // Members
            pendingMembers = r.users.map(u => ({
                id:    u.id,
                name:  u.name,
                email: u.email,
                role:  u.pivot?.role ?? 'member',
            }));
            renderMemberList();

            openModal('companyModal');
        });
    });

    // ── Delete ──
    $(document).on('click', '.btn-del-company', function () {
        const id   = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: `Delete ${name}?`,
            text:  'Members will be unlinked but their accounts remain.',
            icon:  'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Delete',
        }).then(res => {
            if (!res.isConfirmed) return;
            $.ajax({
                url:  '{{ url("admin/companies") }}/' + id,
                type: 'DELETE',
                data: { _token: CSRF },
                success: r => {
                    Swal.fire({ icon:'success', title:'Deleted', text:r.message, timer:1400, showConfirmButton:false });
                    loadCompanies(1);
                },
            });
        });
    });

    // ── Load table ──
    let searchTimer;
    function loadCompanies(page) {
        $.get('{{ route("admin.companies.index") }}', { search: $('#coSearch').val(), page: page || 1 }, function (r) {
            $('#coBody').html(r.html);
            $('#coPag').html(r.pagination);
            $('#coShowing').text('Showing ' + r.from + '–' + r.to + ' of ' + r.total);
        });
    }

    $('#coSearch').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => loadCompanies(1), 350);
    });

    $('#coPag').on('click', 'a.page-link', function (e) {
        e.preventDefault();
        const page = new URL($(this).attr('href'), location.href).searchParams.get('page');
        if (page) loadCompanies(page);
    });

    // Init showing count
    $('#coShowing').text('{{ $companies->firstItem() ?? 0 }}–{{ $companies->lastItem() ?? 0 }} of {{ $companies->total() }}');
});
</script>
@endpush
