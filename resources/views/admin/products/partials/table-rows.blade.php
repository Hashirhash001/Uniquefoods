<div class="table-responsive">
    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 80px;">Image</th>
                <th class="sortable" data-sort="name">
                    Product
                    <span class="sort-icon">
                        <i class="fas fa-caret-up"></i>
                        <i class="fas fa-caret-down"></i>
                    </span>
                </th>
                <th class="sortable" data-sort="sku">
                    SKU
                    <span class="sort-icon">
                        <i class="fas fa-caret-up"></i>
                        <i class="fas fa-caret-down"></i>
                    </span>
                </th>
                <th>Category</th>
                <th>Brand</th>
                <th class="sortable" data-sort="price">
                    Price
                    <span class="sort-icon">
                        <i class="fas fa-caret-up"></i>
                        <i class="fas fa-caret-down"></i>
                    </span>
                </th>
                <th class="sortable" data-sort="stock">
                    Stock
                    <span class="sort-icon">
                        <i class="fas fa-caret-up"></i>
                        <i class="fas fa-caret-down"></i>
                    </span>
                </th>
                <th>Status</th>
                <th style="width: 100px; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td data-label="Image">
                    @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                             alt="{{ $product->name }}"
                             class="product-image">
                    @else
                        <div class="no-image">📦</div>
                    @endif
                </td>
                <td data-label="Product">
                    <div class="product-info">
                        <div class="product-details">
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-sku">{{ $product->sku }}</div>
                        </div>
                    </div>
                </td>
                <td data-label="SKU">{{ $product->sku }}</td>
                <td data-label="Category">{{ $product->category->name ?? '-' }}</td>
                <td data-label="Brand">{{ $product->brand->name ?? '-' }}</td>
                <td data-label="Price">
                    <strong>₹{{ number_format($product->price, 2) }}</strong>
                    @if($product->mrp && $product->mrp > $product->price)
                        <br><small style="text-decoration: line-through; color: #9ca3af;">₹{{ number_format($product->mrp, 2) }}</small>
                    @endif
                </td>
                <td data-label="Stock">
                    @if($product->stock > 10)
                        <span class="badge badge-success">{{ $product->stock }} in stock</span>
                    @elseif($product->stock > 0)
                        <span class="badge badge-warning">{{ $product->stock }} low</span>
                    @else
                        <span class="badge badge-danger">Out of Stock</span>
                    @endif
                </td>
                <td data-label="Status">
                    @if($product->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </td>
                <td data-label="Actions">
                    <div class="action-btns">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="btn-icon"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn-icon btn-danger delete-product"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h4>No Products Found</h4>
                        <p>Try adjusting your filters or create a new product</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
