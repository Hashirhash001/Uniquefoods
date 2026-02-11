<table class="groups-table">
    <thead>
        <tr>
            <th style="width: 8%;">#</th>
            <th style="width: 20%;">Group Name</th>
            <th style="width: 12%;">Members</th>
            <th style="width: 12%;">Status</th>
            <th style="width: 15%;">Created</th>
            <th style="width: 33%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($groups as $index => $group)
        <tr>
            <td>{{ $groups->firstItem() + $index }}</td>
            <td>
                <strong style="color: #111827;">{{ $group->name }}</strong>
                <br>
                <small style="color: #9ca3af;">{{ $group->slug }}</small>
            </td>
            <td>
                <span class="badge badge-info">
                    <i class="fas fa-users"></i> {{ $group->users_count }}
                </span>
            </td>
            <td>
                <span class="badge status-badge {{ $group->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $group->is_active ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td>
                <small style="color: #6b7280;">
                    {{ $group->created_at->format('M d, Y') }}
                </small>
            </td>
            <td>
                <div class="action-btns">
                    <a href="{{ route('admin.customer-groups.edit', $group) }}"
                       class="btn-icon"
                       title="Edit Group">
                        <i class="fas fa-edit"></i>
                    </a>

                    <a href="{{ route('admin.customer-groups.product-prices', $group) }}"
                       class="btn-icon btn-success"
                       title="Product Prices">
                        <i class="fas fa-tags"></i>
                    </a>

                    <a href="{{ route('admin.customer-groups.discounts', $group) }}"
                       class="btn-icon"
                       title="Group Discounts">
                        <i class="fas fa-percent"></i>
                    </a>

                    <a href="{{ route('admin.customer-groups.product-offers', $group) }}"
                       class="btn-icon"
                       title="Product Offers">
                        <i class="fas fa-gift"></i>
                    </a>

                    <button class="btn-icon toggle-status"
                            data-id="{{ $group->id }}"
                            data-type="customer-groups"
                            title="Toggle Status">
                        <i class="fas fa-toggle-{{ $group->is_active ? 'on' : 'off' }}"></i>
                    </button>

                    <button class="btn-icon btn-danger delete-group"
                            data-id="{{ $group->id }}"
                            title="Delete Group">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h5 style="color: #374151; font-size: 18px; margin-top: 1rem;">No Customer Groups Found</h5>
                    <p style="color: #9ca3af; margin-top: 0.5rem;">Create your first customer group to get started</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
