@forelse ($brands as $index => $brand)
    <tr data-status="{{ $brand->is_active ? '1' : '0' }}"
        data-name="{{ strtolower($brand->name) }}">
        <td class="text-muted">{{ $brands->firstItem() + $index }}</td>
        <td>
            <span class="brand-name">{{ $brand->name }}</span>
        </td>
        <td>
            <span class="status-badge {{ $brand->is_active ? 'active' : 'inactive' }}">
                {{ $brand->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td class="text-center text-muted">
            {{ $brand->products_count ?? 0 }}
        </td>
        <td class="text-center">
            <button class="action-btn btn-toggle"
                    onclick="toggleStatus({{ $brand->id }}, {{ $brand->is_active }})"
                    title="Toggle Status">
                <i class="fas fa-power-off"></i>
            </button>
            <button class="action-btn btn-edit"
                    onclick='editBrand(@json($brand))'
                    title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button class="action-btn btn-delete"
                    onclick="deleteBrand({{ $brand->id }})"
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="text-muted">
                <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.25;"></i>
                <p class="mb-0">No brands found</p>
            </div>
        </td>
    </tr>
@endforelse
