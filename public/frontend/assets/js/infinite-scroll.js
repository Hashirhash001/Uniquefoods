(function ($) {
    'use strict';

    // ── Helper: get correct column class based on sidebar state ──
    function getColClass() {
        const row = document.querySelector('#shopProductsContainer')?.closest('.row');
        const isSidebarHidden = row?.classList.contains('shop-sidebar-hidden');
        // sidebar hidden → 5 cols (col-lg-2-4 = ~20%), sidebar visible → 4 cols (col-lg-3)
        return isSidebarHidden
            ? 'col-lg-2 col-md-4 col-sm-6 col-6'
            : 'col-lg-3 col-md-4 col-sm-6 col-6';
    }

    // ── Helper: build one skeleton card col ──
    function buildSkeletonCard() {
        const col = getColClass();
        return `
            <div class="${col} skeleton-col load-more-skeleton">
                <div class="shop-product-card skeleton-card">
                    <div class="skeleton skeleton-image"></div>
                    <div class="product-info" style="padding:12px">
                        <div class="skeleton skeleton-text short"></div>
                        <div class="skeleton skeleton-text"></div>
                        <div class="skeleton skeleton-text medium"></div>
                        <div class="skeleton skeleton-price"></div>
                        <div class="skeleton skeleton-btn"></div>
                    </div>
                </div>
            </div>`;
    }

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
                scrollTimeout = setTimeout(() => this.checkScroll(), 100);
            });
        },

        checkScroll() {
            if (!this.hasMorePages || this.isLoading) return;

            const scrollPosition   = $(window).scrollTop() + $(window).height();
            const documentHeight   = $(document).height();
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

            const data = {
                page:       nextPage,
                min_price:  window.activeFilters?.minPrice  || 0,
                max_price:  window.activeFilters?.maxPrice  || 10000,
                categories: window.activeFilters?.categories || [],
                brands:     window.activeFilters?.brands    || [],
                sort:       window.currentSort || 'latest'
            };

            $.ajax({
                url:      '/shop/filter',
                method:   'GET',
                data:     data,
                dataType: 'json',
                success: (response) => {
                    this.hideLoader();

                    if (response.success && response.products && response.products.length > 0) {
                        this.appendProducts(response.products);
                        this.currentPage = nextPage;
                        this.lastPage    = response.last_page;
                        this.isLoading   = false;

                        if (typeof window.initializeWishlistStates === 'function') {
                            window.initializeWishlistStates();
                        }
                        if (typeof window.Cart !== 'undefined') {
                            window.Cart.syncAllProductCards();
                        }
                    } else {
                        this.hasMorePages = false;
                        this.isLoading    = false;
                        this.showEndMessage();
                    }
                },
                error: () => {
                    this.hideLoader();
                    this.isLoading = false;
                    if (typeof Toast !== 'undefined') Toast.error('Failed to load more products');
                }
            });
        },

        appendProducts(products) {
            const container = $('#shopProductsContainer');
            products.forEach(product => {
                container.append(this.renderProduct(product));
            });
        },

        // ── Mirrors displayProducts() in shop.blade.php exactly ──
        renderProduct(product) {
            const col          = getColClass();
            const finalPrice   = parseFloat(product.price) || 0;
            const basePrice    = parseFloat(product.base_price || product.mrp || product.price) || 0;
            const showDiscount = parseInt(product.discount_percentage) > 0;
            const showStrike   = basePrice > finalPrice;

            const discountBadge = showDiscount
                ? `<div class="product-badge-discount"><span>${product.discount_percentage}% OFF</span></div>`
                : '';

            const stockBadge = (product.stock <= 5 && product.stock > 0)
                ? `<div class="product-badge-stock"><span>Only ${product.stock} left</span></div>`
                : '';

            const strikePriceHtml = showStrike
                ? `<span class="price-original">£${basePrice.toFixed(2)}</span>
                   <span class="price-save">Save £${(basePrice - finalPrice).toFixed(2)}</span>`
                : '';

            const brandHtml = product.brand
                ? `<span class="meta-separator">•</span>
                   <span class="product-brand">${product.brand.name}</span>`
                : '';

            // Star rating
            let starsHtml = '';
            if (product.reviews_count > 0) {
                const rating = Math.round(parseFloat(product.average_rating) || 0);
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += `<i class="fa-${i <= rating ? 'solid' : 'regular'} fa-star"></i>`;
                }
                starsHtml = `
                    <div class="product-rating">
                        <div class="stars">${stars}</div>
                        <span class="rating-count">${parseFloat(product.average_rating || 0).toFixed(1)}</span>
                    </div>`;
            } else {
                starsHtml = `
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                        <span class="rating-count">0.0</span>
                    </div>`;
            }

            // Stock status row
            const stockStatus = product.stock > 0
                ? `<div class="product-stock in-stock">
                       <i class="fa-solid fa-circle-check"></i>
                       <span>In Stock</span>
                   </div>`
                : `<div class="product-stock out-of-stock">
                       <i class="fa-solid fa-circle-xmark"></i>
                       <span>Out of Stock</span>
                   </div>`;

            // Action button
            let actionBtn = '';
            if (product.stock > 0) {
                if (product.is_weight_based) {
                    actionBtn = `
                        <a href="/product/${product.slug}" class="btn-select-weight">
                            <i class="fa-regular fa-weight-scale"></i>
                            <span>Select Weight</span>
                        </a>`;
                } else {
                    actionBtn = `
                        <button class="product-add-to-cart add-to-cart-btn"
                                data-product-id="${product.id}">
                            <i class="fa-regular fa-cart-shopping"></i>
                            <span>Add to Cart</span>
                        </button>`;
                }
            } else {
                actionBtn = `
                    <button class="product-add-to-cart disabled" disabled>
                        <i class="fa-solid fa-circle-xmark"></i>
                        <span>Out of Stock</span>
                    </button>`;
            }

            // Wishlist state
            const wishlistActive = product.is_wishlisted ? 'active' : '';
            const heartIcon      = product.is_wishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart';

            return `
                <div class="${col}">
                    <div class="shop-product-card">
                        <div class="product-image-wrapper">
                            <a href="/product/${product.slug}" class="product-image-link">
                                ${discountBadge}
                                ${stockBadge}
                                <img src="${product.image_url}"
                                     alt="${product.name}"
                                     class="product-main-image"
                                     onerror="this.src='/frontend/assets/images/products/product-placeholder.svg';this.classList.add('product-placeholder-image')">
                            </a>
                            <div class="product-quick-actions">
                                <button class="quick-action-btn wishlist-toggle-btn ${wishlistActive}"
                                        title="Add to Wishlist"
                                        data-product-id="${product.id}">
                                    <i class="${heartIcon}"></i>
                                </button>
                                <a href="/product/${product.slug}" class="quick-action-btn" title="View Product">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-meta">
                                <span class="product-category">${product.category?.name || 'Uncategorized'}</span>
                                ${brandHtml}
                            </div>
                            <a href="/product/${product.slug}" class="product-name-link">
                                <h4 class="product-name">${product.name}</h4>
                            </a>
                            ${starsHtml}
                            <div class="product-price">
                                <span class="price-current">£${finalPrice.toFixed(2)}</span>
                                ${strikePriceHtml}
                            </div>
                            ${stockStatus}
                            ${actionBtn}
                        </div>
                    </div>
                </div>`;
        },

        // ── Skeleton loader (matches blade skeleton exactly) ──
        showLoader() {
            $('.load-more-skeleton').remove();
            for (let i = 0; i < 4; i++) {
                $('#shopProductsContainer').append(buildSkeletonCard());
            }
        },

        hideLoader() {
            $('.load-more-skeleton').remove();
        },

        showEndMessage() {
            if ($('#infiniteScrollEnd').length === 0) {
                $('#shopProductsContainer').after(`
                    <div class="col-12 text-center py-4" id="infiniteScrollEnd">
                        <p style="color:#aaa;font-size:13px;font-weight:600;letter-spacing:0.5px">
                            <i class="fa-solid fa-circle-check" style="color:#10b981;margin-right:6px"></i>
                            You've seen all products
                        </p>
                    </div>`);
            }
        },

        reset() {
            this.currentPage  = 1;
            this.hasMorePages = true;
            this.isLoading    = false;
            $('.load-more-skeleton').remove();
            $('#infiniteScrollEnd').remove();
        }
    };

    $(document).ready(function () {
        if ($('#shopProductsContainer').length > 0) {
            InfiniteScroll.init();

            $(document).on('shopFiltersChanged', function () {
                InfiniteScroll.reset();
            });
        }
    });

    window.InfiniteScroll = InfiniteScroll;

})(jQuery);
