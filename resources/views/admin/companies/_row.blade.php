<tr>
    <td>
        <div style="font-weight:700;color:#111827;font-size:13px;">{{ $company->name }}</div>
        <div style="font-size:11px;color:#9ca3af;">{{ $company->primary_email }}</div>
        @if($company->phone)
            <div style="font-size:11px;color:#9ca3af;">{{ $company->phone }}</div>
        @endif
    </td>
    <td>
        @forelse($company->groups as $g)
            <span class="gbadge">{{ $g->name }}</span>
        @empty
            <span style="color:#d1d5db;font-size:12px;font-style:italic;">No groups</span>
        @endforelse
    </td>
    <td>
        <div style="display:flex;align-items:center;">
            @foreach($company->activeUsers->take(4) as $u)
                <div class="member-av" title="{{ $u->name }}">{{ strtoupper(substr($u->name,0,1)) }}</div>
            @endforeach
            @if($company->users_count > 4)
                <div class="member-av" style="background:#6b7280;">+{{ $company->users_count - 4 }}</div>
            @endif
            @if($company->users_count === 0)
                <span style="color:#d1d5db;font-size:12px;font-style:italic;">No members</span>
            @endif
        </div>
        <div style="font-size:11px;color:#9ca3af;margin-top:3px;">{{ $company->users_count }} {{ Str::plural('member', $company->users_count) }}</div>
    </td>
    <td>
        @if($company->is_active)
            <span style="background:#d1fae5;color:#065f46;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700;">Active</span>
        @else
            <span style="background:#fee2e2;color:#991b1b;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700;">Inactive</span>
        @endif
    </td>
    <td style="color:#9ca3af;font-size:12px;white-space:nowrap;">{{ $company->created_at->format('d M Y') }}</td>
    <td>
        <div style="display:flex;gap:6px;">
            <button class="act-btn act-edit btn-edit-company" data-id="{{ $company->id }}" title="Edit">
                <i class="fas fa-pen"></i>
            </button>
            <button class="act-btn act-del btn-del-company" data-id="{{ $company->id }}" data-name="{{ $company->name }}" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
