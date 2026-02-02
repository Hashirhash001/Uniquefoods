/**
 * ================================================
 * INFINITE SCROLL FOR SHOP PAGE
 * ================================================
 */

(function($) {
    'use strict';

    // Infinite Scroll Manager
    const InfiniteScroll = {
        currentPage: 1,
        lastPage: 1,
        isLoading: false,
        hasMorePages: true,
        scrollThreshold: 1000,

        init() {
            this.bindScrollEvent();
        },

        bindScrollEvent() {
            let scrollTimeout;

            $(window).on('scroll', () => {
                clearTimeout(scrollTimeout);

                scrollTimeout = setTimeout(() => {
                    this.checkScroll();
                }, 100); // Debounce scroll
            });
        },

        checkScroll() {
            if (!this.hasMorePages || this.isLoading) {
                return;
            }

            const scrollPosition = $(window).scrollTop() + $(window).height();
            const documentHeight = $(document).height();
            const distanceFromBottom = documentHeight - scrollPosition;

            if (distanceFromBottom <= this.scrollThreshold) {
                this.loadNextPage();
            }
        },

        loadNextPage() {
            if (this.currentPage >= this.lastPage) {
                this.hasMorePages = false;
                this.showEndMessage();
                return;
            }

            this.isLoading = true;
            this.showLoader();

            const nextPage = this.currentPage + 1;

            // Use your existing filter function but with pagination
            const data = {
                page: nextPage,
                min_price: window.activeFilters?.minPrice || 0,
                max_price: window.activeFilters?.maxPrice || 10000,
                categories: window.activeFilters?.categories || [],
                brands: window.activeFilters?.brands || [],
                sort: $('#shopSortBy').val() || 'latest'
            };

            $.ajax({
                url: '/shop/filter',
                method: 'GET',
                data: data,
                success: (response) => {
                    if (response.success && response.products.length > 0) {
                        this.appendProducts(response.products);
                        this.currentPage = nextPage;
                        this.lastPage = response.last_page;
                        this.isLoading = false;
                        this.hideLoader();

                        // Initialize wishlist states for new products
                        if (typeof window.initializeWishlistStates === 'function') {
                            window.initializeWishlistStates();
                        }

                        // Sync cart quantity controls
                        if (typeof window.Cart !== 'undefined') {
                            window.Cart.syncAllProductCards();
                        }
                    } else {
                        this.hasMorePages = false;
                        this.hideLoader();
                        this.showEndMessage();
                    }
                },
                error: () => {
                    this.isLoading = false;
                    this.hideLoader();
                    Toast.error('Failed to load more products');
                }
            });
        },

        appendProducts(products) {
            const container = $('#shopProductsContainer');

            products.forEach(product => {
                const productHtml = this.renderProduct(product);
                container.append(productHtml);
            });
        },

        renderProduct(product) {
            return `
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="shop-product-card">
                        <div class="product-image-wrapper">
                            <a href="/product/${product.slug}" class="product-image-link">
                                ${product.discount_percentage > 0 ? `
                                    <div class="product-badge-discount">
                                        <span>${product.discount_percentage}% OFF</span>
                                    </div>
                                ` : ''}
                                ${product.stock <= 5 && product.stock > 0 ? `
                                    <div class="product-badge-stock">
                                        <span>Only ${product.stock} left</span>
                                    </div>
                                ` : ''}
                                <img src="${product.image_url}" alt="${product.name}" class="product-main-image"
                                     onerror="this.src='/frontend/assets/images/grocery/01.jpg'">
                            </a>
                            <div class="product-quick-actions">
                                <button class="quick-action-btn wishlist-toggle-btn"
                                        title="Add to Wishlist"
                                        data-product-id="${product.id}">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <button class="quick-action-btn shop-quick-view-btn" title="Quick View" data-id="${product.id}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-meta">
                                <span class="product-category">${product.category?.name || 'Uncategorized'}</span>
                                ${product.brand ? `
                                    <span class="meta-separator">•</span>
                                    <span class="product-brand">${product.brand.name}</span>
                                ` : ''}
                            </div>
                            <a href="/product/${product.slug}" class="product-name-link">
                                <h4 class="product-name">${product.name}</h4>
                            </a>
                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <span class="rating-count">(4.0)</span>
                            </div>
                            <div class="product-price">
                                <span class="price-current">₹${parseFloat(product.price).toFixed(2)}</span>
                                ${product.mrp && product.mrp > product.price ? `
                                    <span class="price-original">₹${parseFloat(product.mrp).toFixed(2)}</span>
                                    <span class="price-save">Save ₹${(product.mrp - product.price).toFixed(2)}</span>
                                ` : ''}
                            </div>
                            ${product.stock > 0 ? `
                                <div class="product-stock in-stock">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>In Stock</span>
                                </div>
                            ` : `
                                <div class="product-stock out-of-stock">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <span>Out of Stock</span>
                                </div>
                            `}
                            <button class="product-add-to-cart add-to-cart-btn ${product.stock === 0 ? 'disabled' : ''}"
                                    ${product.stock === 0 ? 'disabled' : ''}
                                    data-product-id="${product.id}">
                                <i class="fa-regular fa-cart-shopping"></i>
                                <span>${product.stock > 0 ? 'Add to Cart' : 'Out of Stock'}</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        },

        showLoader() {
            if ($('#infiniteScrollLoader').length === 0) {
                const loader = $(`
                    <div class="col-12 text-center py-4" id="infiniteScrollLoader">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3">Loading more products...</p>
                    </div>
                `);
                $('#shopProductsContainer').after(loader);
            }
        },

        hideLoader() {
            $('#infiniteScrollLoader').remove();
        },

        showEndMessage() {
            if ($('#infiniteScrollEnd').length === 0) {
                const message = $(`
                    <div class="col-12 text-center py-4" id="infiniteScrollEnd">
                        <p style="color: #666; font-size: 14px;">
                            <i class="fa-solid fa-check-circle"></i>
                            You've reached the end of the products
                        </p>
                    </div>
                `);
                $('#shopProductsContainer').after(message);
            }
        },

        reset() {
            this.currentPage = 1;
            this.hasMorePages = true;
            this.isLoading = false;
            $('#infiniteScrollLoader').remove();
            $('#infiniteScrollEnd').remove();
        }
    };

    // Initialize on page load
    $(document).ready(function() {
        // Only initialize on shop page
        if ($('#shopProductsContainer').length > 0) {
            InfiniteScroll.init();

            // Reset when filters change
            $(document).on('shopFiltersChanged', function() {
                InfiniteScroll.reset();
            });
        }
    });

    // Make available globally
    window.InfiniteScroll = InfiniteScroll;

})(jQuery);
