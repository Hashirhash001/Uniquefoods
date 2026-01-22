@forelse ($categories as $index => $category)
    <!-- Desktop Table Row -->
    <tr class="category-row desktop-only"
        data-parent="{{ $category->parent_id ?? '' }}"
        data-status="{{ $category->is_active ? '1' : '0' }}"
        data-name="{{ strtolower($category->name) }}">
        <td class="text-muted">{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</td>
        <td>
            <div class="d-flex align-items-center gap-2">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}"
                         alt="{{ $category->name }}"
                         class="category-thumbnail">
                @else
                    <div class="category-thumbnail-placeholder">
                        <i class="fas fa-folder"></i>
                    </div>
                @endif
                <div>
                    <span class="category-name">{{ $category->name }}</span>
                    @if($category->children_count > 0)
                        <span class="subcategory-badge">
                            {{ $category->children_count }} {{ Str::plural('sub', $category->children_count) }}
                        </span>
                    @endif
                </div>
            </div>
        </td>
        <td>
            @if($category->parent)
                <span class="parent-badge">{{ $category->parent->name }}</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            <span class="status-badge {{ $category->is_active ? 'active' : 'inactive' }}">
                {{ $category->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td class="text-center text-muted">
            {{ $category->products_count ?? 0 }}
        </td>
        <td class="text-center">
            <button class="action-btn btn-toggle"
                    onclick="toggleStatus({{ $category->id }}, {{ $category->is_active }})"
                    title="Toggle Status">
                <i class="fas fa-power-off"></i>
            </button>
            <button class="action-btn btn-edit"
                    onclick='editCategory(@json($category))'
                    title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button class="action-btn btn-delete"
                    onclick="deleteCategory({{ $category->id }})"
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>

    <!-- Mobile Card Layout -->
    <div class="category-mobile-card mobile-only">
        <div class="category-mobile-header">
            <div class="d-flex align-items-center gap-2 flex-1">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}"
                         alt="{{ $category->name }}"
                         class="category-thumbnail-mobile">
                @else
                    <div class="category-thumbnail-placeholder-mobile">
                        <i class="fas fa-folder"></i>
                    </div>
                @endif
                <div class="category-mobile-title">
                    <div class="category-mobile-name">{{ $category->name }}</div>
                    @if($category->children_count > 0)
                        <span class="subcategory-badge">
                            {{ $category->children_count }} {{ Str::plural('subcategory', $category->children_count) }}
                        </span>
                    @endif
                </div>
            </div>
            <span class="status-badge {{ $category->is_active ? 'active' : 'inactive' }}">
                {{ $category->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="category-mobile-info">
            <div class="category-mobile-info-item">
                <span class="category-mobile-label">Parent Category</span>
                <span class="category-mobile-value">
                    @if($category->parent)
                        <span class="parent-badge">{{ $category->parent->name }}</span>
                    @else
                        <span class="text-muted">Main Category</span>
                    @endif
                </span>
            </div>
            <div class="category-mobile-info-item">
                <span class="category-mobile-label">Products</span>
                <span class="category-mobile-value">
                    <strong>{{ $category->products_count ?? 0 }}</strong> products
                </span>
            </div>
        </div>

        <div class="category-mobile-actions">
            <button class="action-btn btn-toggle"
                    onclick="toggleStatus({{ $category->id }}, {{ $category->is_active }})"
                    title="Toggle Status">
                <i class="fas fa-power-off"></i>
            </button>
            <button class="action-btn btn-edit"
                    onclick='editCategory(@json($category))'
                    title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button class="action-btn btn-delete"
                    onclick="deleteCategory({{ $category->id }})"
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
@empty
    <tr class="desktop-only">
        <td colspan="6" class="text-center py-5">
            <div class="text-muted">
                <i class="fas fa-folder-open fa-3x mb-3" style="opacity: 0.25;"></i>
                <p class="mb-0">No categories found</p>
            </div>
        </td>
    </tr>
    <div class="category-mobile-card mobile-only">
        <div class="empty-state">
            <div class="empty-icon">📁</div>
            <h5 class="empty-title">No Categories Found</h5>
            <p class="empty-text">Try adjusting your filters</p>
        </div>
    </div>
@endforelse

<style>
/* Category Thumbnails */
.category-thumbnail {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.category-thumbnail-placeholder {
    width: 40px;
    height: 40px;
    background: #f3f4f6;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 16px;
    border: 1px solid #e5e7eb;
}

.category-thumbnail-mobile {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.category-thumbnail-placeholder-mobile {
    width: 50px;
    height: 50px;
    background: #f3f4f6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 20px;
    border: 1px solid #e5e7eb;
}

/* Show/hide based on screen size */
@media (min-width: 769px) {
    .desktop-only {
        display: table-row !important;
    }
    .mobile-only {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .desktop-only {
        display: none !important;
    }
    .mobile-only {
        display: flex !important;
        flex-direction: column;
    }
}
</style>
