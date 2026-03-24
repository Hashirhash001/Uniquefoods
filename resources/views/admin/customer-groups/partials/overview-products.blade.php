@if($products->count())
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:60px;">Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th style="text-align:center;width:80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                                 class="thumb" alt="{{ $product->name }}">
                        @else
                            <div class="thumb-placeholder">📦</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:500;color:#111827;">{{ $product->name }}</div>
                        <div style="font-size:12px;color:#9ca3af;">{{ $product->sku }}</div>
                    </td>
                    <td style="color:#374151;">{{ $product->category->name ?? '—' }}</td>
                    <td style="color:#374151;">{{ $product->brand->name ?? '—' }}</td>
                    <td>
                        <strong>£{{ number_format($product->price, 2) }}</strong>
                        @if($product->mrp && $product->mrp > $product->price)
                            <br><small style="text-decoration:line-through;color:#9ca3af;">
                                £{{ number_format($product->mrp, 2) }}
                            </small>
                        @endif
                    </td>
                    <td>
                        @if($product->stock > 10)
                            <span class="pill pill-green">{{ $product->stock }} in stock</span>
                        @elseif($product->stock > 0)
                            <span class="pill pill-yellow">{{ $product->stock }} low</span>
                        @else
                            <span class="pill pill-red">Out of Stock</span>
                        @endif
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="pill pill-green">Active</span>
                        @else
                            <span class="pill pill-red">Inactive</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="icon-btn" title="Edit Product">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #e5e7eb;">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
@else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h5>No products found</h5>
        <p>
            @if(request()->hasAny(['search','category_id','status','stock_status']))
                Try adjusting your filters above.
            @else
                No products are assigned to this group yet.
            @endif
        </p>
    </div>
@endif
