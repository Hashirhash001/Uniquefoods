@extends('admin.layouts.app')

@section('title', 'Categories Management')

@push('styles')
    <style>
        .page-header-section {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }

        .btn-create {
            background: #22c55e;
            border: none;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-create:hover {
            background: #16a34a;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
        }

        .product-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .category-table {
            margin: 0;
            width: 100%;
        }

        .category-table thead {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .category-table thead th {
            padding: 0.875rem 1rem;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .category-table tbody td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #111827;
        }

        .category-table tbody tr {
            transition: background 0.15s;
        }

        .category-table tbody tr:hover {
            background: #fafafa;
        }

        .category-name {
            font-weight: 500;
            color: #111827;
            font-size: 14px;
        }

        .subcategory-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            background: #f3f4f6;
            color: #6b7280;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 0.5rem;
            text-transform: uppercase;
        }

        .parent-badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.inactive {
            background: #f3f4f6;
            color: #6b7280;
        }

        .action-btn {
            padding: 0;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.25rem;
            transition: all 0.2s;
            cursor: pointer;
            color: #6b7280;
            font-size: 14px;
        }

        .action-btn:hover {
            border-color: #d1d5db;
            background: #f9fafb;
        }

        .action-btn.btn-toggle {
            color: #d97706;
        }

        .action-btn.btn-toggle:hover {
            background: #fef3c7;
            border-color: #fbbf24;
        }

        .action-btn.btn-edit {
            color: #2563eb;
        }

        .action-btn.btn-edit:hover {
            background: #dbeafe;
            border-color: #3b82f6;
        }

        .action-btn.btn-delete {
            color: #dc2626;
        }

        .action-btn.btn-delete:hover {
            background: #fee2e2;
            border-color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
        }

        .empty-icon {
            font-size: 4rem;
            opacity: 0.2;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: #6b7280;
            font-size: 14px;
        }

        /* Filters Section */
        .filters-section {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        /* View Toggle Buttons */
        .view-toggle-group {
            display: inline-flex;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #d1d5db;
            width: 100%;
        }

        .view-radio {
            display: none;
        }

        .view-label {
            flex: 1;
            padding: 0.625rem 1rem;
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-align: center;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: unset !important;
        }

        .view-label:first-of-type {
            border-right: 1px solid #d1d5db;
        }

        .view-label:hover {
            background: #f9fafb;
            color: #374151;
        }

        .view-radio:checked+.view-label {
            background: #4b5563;
            color: white;
            font-weight: 600;
        }

        .view-label i {
            font-size: 14px;
        }

        /* Tree View Styles */
        .tree-container {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .tree-node {
            margin-bottom: 0.5rem;
        }

        .tree-node-content {
            padding: 0.875rem 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .tree-node-content:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .main-category>.tree-node-content {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .tree-toggle {
            background: transparent;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            color: #6b7280;
            transition: transform 0.2s;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tree-toggle.collapsed i {
            transform: rotate(-90deg);
        }

        .tree-spacer {
            width: 24px;
            display: inline-block;
        }

        .tree-children {
            margin-left: 2rem;
            margin-top: 0.5rem;
            border-left: 2px solid #e5e7eb;
            padding-left: 1rem;
        }

        .tree-line {
            color: #9ca3af;
            font-family: monospace;
            margin-right: 0.5rem;
            font-size: 14px;
        }

        .category-icon {
            color: #3b82f6;
            font-size: 16px;
        }

        .category-icon-sub {
            color: #8b5cf6;
            font-size: 14px;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e5e7eb;
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0.625rem 0.875rem;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
            outline: none;
        }

        .form-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .text-danger {
            color: #ef4444;
            font-size: 13px;
        }

        .form-check-input:checked {
            background-color: #22c55e;
            border-color: #22c55e;
        }

        .btn-secondary {
            background: white;
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-primary-modal {
            background: #22c55e;
            border: none;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
        }

        .btn-primary-modal:hover {
            background: #16a34a;
        }

        /* Green Pagination Styling */
        .pagination .page-link {
            color: #374151;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            border-radius: 6px;
            font-size: 14px;
        }

        .pagination .page-link:hover {
            background: #f9fafb;
            color: #111827;
            border-color: #d1d5db;
        }

        .pagination .page-item.active .page-link {
            background: #22c55e;
            border-color: #22c55e;
            color: white;
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            color: #9ca3af;
            background: #f9fafb;
            border-color: #e5e7eb;
        }

        /* Loading State */
        .table-loading {
            position: relative;
            opacity: 0.6;
            pointer-events: none;
        }

        .table-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 40px;
            height: 40px;
            margin: -20px 0 0 -20px;
            border: 3px solid #f3f4f6;
            border-top-color: #22c55e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Form fixes */
        input[type=checkbox]~label,
        input[type=radio]~label {
            padding-left: 0 !important;
        }

        .form-check-input::before,
        .form-check-input::after {
            display: none !important;
        }

        .form-check-label::before,
        .form-check-label::after {
            display: none !important;
        }

        .form-check-switch .form-check-input {
            width: 3rem !important;
            height: 1.5rem !important;
            background-image: none !important;
        }

        .form-check {
            padding-left: 0 !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
        }

        .form-check-input {
            margin: 0 !important;
            flex-shrink: 0 !important;
        }

        .form-check-label {
            margin: 0 !important;
            cursor: pointer !important;
            font-size: 14px;
        }


        /* ============================================
        MOBILE RESPONSIVE STYLES
        ============================================ */

        /* Tablet & Below (992px) */
        @media (max-width: 992px) {
            .page-title {
                font-size: 20px;
            }

            .category-table thead th,
            .category-table tbody td {
                padding: 0.75rem 0.75rem;
                font-size: 13px;
            }

            .action-btn {
                width: 28px;
                height: 28px;
                font-size: 13px;
            }
        }

        /* Tablet Portrait & Mobile (768px) */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }

            /* Header */
            .page-header-section {
                margin-bottom: 1.25rem;
            }

            .page-header-section .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 1rem;
            }

            .page-title {
                font-size: 18px;
            }

            .page-subtitle {
                font-size: 13px;
            }

            .btn-create {
                width: 100%;
                justify-content: center;
                padding: 0.75rem 1rem;
            }

            /* Filters */
            .filters-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .filters-section .row {
                gap: 0.75rem;
            }

            .filters-section .col-md-4,
            .filters-section .col-md-3,
            .filters-section .col-md-2 {
                width: 100%;
                padding: 0;
            }

            /* View Toggle Full Width on Mobile */
            .view-toggle-group {
                width: 100%;
            }

            /* Card */
            .product-card {
                border-radius: 6px;
            }

            .card-header {
                padding: 1rem;
                font-size: 14px;
            }

            .card-header h4 {
                font-size: 14px;
            }

            .category-table {
                display: none;
            }

            /* Show mobile cards */
            #categoryTableBody {
                display: flex !important;
                flex-direction: column;
                gap: 0.75rem;
                padding: 1rem;
            }

            .category-mobile-card {
                display: flex !important;
                flex-direction: column;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 1rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .category-mobile-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 0.75rem;
                padding-bottom: 0.75rem;
                border-bottom: 1px solid #f3f4f6;
            }

            .category-mobile-title {
                flex: 1;
            }

            .category-mobile-name {
                font-size: 15px;
                font-weight: 600;
                color: #111827;
                margin-bottom: 0.25rem;
            }

            .category-mobile-info {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
                margin-bottom: 0.75rem;
            }

            .category-mobile-info-item {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .category-mobile-label {
                font-size: 11px;
                font-weight: 600;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .category-mobile-value {
                font-size: 13px;
                color: #111827;
            }

            .category-mobile-actions {
                display: flex;
                gap: 0.5rem;
                padding-top: 0.75rem;
                border-top: 1px solid #f3f4f6;
                justify-content: flex-end;
            }

            /* Tree View Mobile */
            .tree-container {
                padding: 1rem;
            }

            .tree-node-content {
                padding: 0.75rem;
                font-size: 13px;
            }

            .tree-children {
                margin-left: 1rem;
                padding-left: 0.75rem;
            }

            .action-btn {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            /* Modal Mobile */
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }

            .modal-header {
                padding: 1rem;
            }

            .modal-title {
                font-size: 16px;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-footer {
                padding: 1rem;
                flex-direction: column-reverse;
                gap: 0.5rem;
            }

            .modal-footer .btn {
                width: 100%;
                margin: 0;
            }

            /* Pagination Mobile */
            .card-footer {
                padding: 1rem;
            }

            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }

            .pagination .page-link {
                padding: 0.5rem 0.625rem;
                font-size: 13px;
                min-width: 36px;
            }

            .view-toggle-group {
                width: 100%;
            }
        }

        /* Desktop - Hide mobile cards */
        @media (min-width: 769px) {
            .category-mobile-card {
                display: none !important;
            }
        }

        /* Small Mobile (576px) */
        @media (max-width: 576px) {
            .container-fluid {
                padding: 0.75rem;
            }

            .page-title {
                font-size: 16px;
            }

            .page-subtitle {
                font-size: 12px;
            }

            .btn-create {
                font-size: 13px;
                padding: 0.625rem 1rem;
            }

            .filters-section {
                padding: 0.75rem;
            }

            .form-control,
            .form-select {
                height: 40px;
                font-size: 13px;
                padding: 0.5rem 0.75rem;
            }

            .card-header h4 {
                font-size: 13px;
            }

            #categoryCount {
                font-size: 13px;
            }

            .category-mobile-card {
                padding: 0.875rem;
            }

            .category-mobile-name {
                font-size: 14px;
            }

            .category-mobile-info {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .status-badge {
                font-size: 10px;
                padding: 0.25rem 0.5rem;
            }

            .subcategory-badge {
                font-size: 10px;
                padding: 0.25rem 0.5rem;
            }

            .parent-badge {
                font-size: 12px;
                padding: 0.25rem 0.5rem;
            }

            .action-btn {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }

            .tree-node-content {
                padding: 0.625rem;
                font-size: 13px;
            }

            .tree-children {
                margin-left: 0.75rem;
                padding-left: 0.5rem;
            }

            .pagination .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 12px;
                min-width: 32px;
            }

            .modal-title {
                font-size: 15px;
            }

            .form-label {
                font-size: 13px;
            }

            .form-text {
                font-size: 12px;
            }

            .btn-secondary,
            .btn-primary-modal {
                padding: 0.625rem 1rem;
                font-size: 13px;
            }

            /* View Toggle Mobile */
            .view-label {
                font-size: 13px;
                padding: 0.5rem 0.75rem;
            }

            .view-label i {
                font-size: 13px;
            }
        }

        /* Landscape Mobile (max height 500px) */
        @media (max-height: 500px) and (orientation: landscape) {
            .modal-dialog {
                margin: 0.25rem auto;
                max-height: 95vh;
            }

            .modal-content {
                max-height: 95vh;
                overflow-y: auto;
            }

            .modal-header,
            .modal-footer {
                padding: 0.75rem 1rem;
            }

            .modal-body {
                padding: 0.75rem 1rem;
            }
        }

        /* Image Upload Styles */
        .image-upload-box {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.2s;
            background: #fafafa;
        }

        .image-upload-box:hover {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        .image-upload-label {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            margin: 0;
        }

        .image-upload-label i {
            font-size: 2rem;
            color: #22c55e;
        }

        .image-upload-label span {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }

        .image-upload-label small {
            font-size: 12px;
            color: #9ca3af;
        }

        .image-preview-wrapper {
            position: relative;
            display: inline-block;
        }

        .category-image-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }

        .btn-remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ef4444;
            color: white;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-remove-image:hover {
            background: #dc2626;
            transform: scale(1.1);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Category Management</h4>
                    <p class="page-subtitle">Manage product categories and subcategories</p>
                </div>
                <button class="btn btn-create" data-bs-toggle="modal" data-bs-target="#categoryModal"
                    onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> Add Category
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section mb-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" id="searchCategory" class="form-control" placeholder="Search categories...">
                </div>
                <div class="col-md-3">
                    <select id="filterParent" class="form-select">
                        <option value="">All Categories</option>
                        <option value="main">Main Categories Only</option>
                        <option value="sub">Subcategories Only</option>
                        @foreach ($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="filterStatus" class="form-select">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="view-toggle-group">
                        <input type="radio" class="view-radio" name="viewMode" id="viewList" value="list" checked>
                        <label class="view-label" for="viewList">
                            <i class="fas fa-th-list"></i> List
                        </label>
                        <input type="radio" class="view-radio" name="viewMode" id="viewTree" value="tree">
                        <label class="view-label" for="viewTree">
                            <i class="fas fa-sitemap"></i> Tree
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- List View (Default) -->
        <div id="listView" class="view-container">
            <div class="row">
                <div class="col-12">
                    <div class="card product-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                Categories List (<span id="categoryCount">{{ $categories->total() }}</span> total)
                            </h4>
                        </div>

                        <div class="card-body pt-0 p-0">
                            <div class="table-responsive">
                                <table class="category-table table mb-0" id="categoryTable">
                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Category Name</th>
                                            <th width="180">Parent Category</th>
                                            <th width="120">Status</th>
                                            <th width="100" class="text-center">Products</th>
                                            <th width="180" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoryTableBody">
                                        @include('admin.categories.partials.table-rows')
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            <div id="paginationContainer">
                                {{ $categories->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tree View -->
        <div id="treeView" class="view-container card product-card" style="display: none;">
            <div class="card-body">
                <div class="tree-container">
                    @php
                        $mainCategories = $allCategories->where('parent_id', null);
                    @endphp
                    @if ($mainCategories->count() > 0)
                        @foreach ($mainCategories as $mainCategory)
                            <div class="tree-node main-category" data-parent=""
                                data-status="{{ $mainCategory->is_active ? '1' : '0' }}"
                                data-name="{{ strtolower($mainCategory->name) }}">
                                <div class="tree-node-content">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($mainCategory->children->count() > 0)
                                                <button class="tree-toggle" onclick="toggleNode(this)">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            @else
                                                <span class="tree-spacer"></span>
                                            @endif
                                            <i class="fas fa-folder category-icon"></i>
                                            <span class="category-name">{{ $mainCategory->name }}</span>
                                            <span
                                                class="status-badge {{ $mainCategory->is_active ? 'active' : 'inactive' }}">
                                                {{ $mainCategory->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="text-muted small">({{ $mainCategory->products_count }}
                                                products)</span>
                                        </div>
                                        <div>
                                            <button class="action-btn btn-toggle btn-sm"
                                                onclick="toggleStatus({{ $mainCategory->id }}, {{ $mainCategory->is_active }})"
                                                title="Toggle Status">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                            <button class="action-btn btn-edit btn-sm"
                                                onclick='editCategory(@json($mainCategory))' title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn btn-delete btn-sm"
                                                onclick="deleteCategory({{ $mainCategory->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @if ($mainCategory->children->count() > 0)
                                    <div class="tree-children">
                                        @foreach ($mainCategory->children as $subCategory)
                                            <div class="tree-node sub-category" data-parent="{{ $mainCategory->id }}"
                                                data-status="{{ $subCategory->is_active ? '1' : '0' }}"
                                                data-name="{{ strtolower($subCategory->name) }}">
                                                <div class="tree-node-content">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="tree-line">└─</span>
                                                            <i class="fas fa-folder-open category-icon-sub"></i>
                                                            <span class="category-name">{{ $subCategory->name }}</span>
                                                            <span
                                                                class="status-badge {{ $subCategory->is_active ? 'active' : 'inactive' }}">
                                                                {{ $subCategory->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                            <span
                                                                class="text-muted small">({{ $subCategory->products_count }}
                                                                products)</span>
                                                        </div>
                                                        <div>
                                                            <button class="action-btn btn-toggle btn-sm"
                                                                onclick="toggleStatus({{ $subCategory->id }}, {{ $subCategory->is_active }})"
                                                                title="Toggle Status">
                                                                <i class="fas fa-power-off"></i>
                                                            </button>
                                                            <button class="action-btn btn-edit btn-sm"
                                                                onclick='editCategory(@json($subCategory))'
                                                                title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="action-btn btn-delete btn-sm"
                                                                onclick="deleteCategory({{ $subCategory->id }})"
                                                                title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">📁</div>
                            <h5 class="empty-title">No Categories Yet</h5>
                            <p class="empty-text">Create your first category to get started</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- CREATE/EDIT MODAL -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="categoryForm" class="modal-content" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="category_id" id="categoryId">
                <input type="hidden" name="remove_image" id="removeImage" value="0">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-folder-plus me-2" id="modalIcon"></i>
                        <span id="modalTitleText">Create New Category</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Category Name -->
                    <div class="mb-3">
                        <label class="form-label">
                            Category Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="categoryName" class="form-control"
                            placeholder="Enter category name" required>
                        <small class="text-danger error-name"></small>
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select name="parent_id" id="parentCategory" class="form-select">
                            <option value="">None (Main Category)</option>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text">Leave empty to create a main category</small>
                    </div>

                    <!-- Category Image -->
                    <div class="mb-3">
                        <label class="form-label">Category Image (Optional)</label>

                        <!-- Image Preview -->
                        <div id="imagePreviewContainer" class="mb-2" style="display: none;">
                            <div class="image-preview-wrapper">
                                <img id="imagePreview" src="" alt="Category Image"
                                    class="category-image-preview">
                                <button type="button" class="btn-remove-image" onclick="removeCategoryImage()"
                                    title="Remove Image">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Upload Button -->
                        <div class="image-upload-box" id="imageUploadBox">
                            <input type="file" name="image" id="categoryImage" class="d-none"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                onchange="previewImage(event)">
                            <label for="categoryImage" class="image-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload image</span>
                                <small>JPG, PNG, GIF, WEBP (Max 2MB)</small>
                            </label>
                        </div>
                        <small class="text-danger error-image"></small>
                    </div>

                    <!-- Active Status -->
                    <div class="mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="categoryStatus"
                                value="1" checked>
                            <label class="form-check-label" for="categoryStatus">
                                Active Status
                            </label>
                        </div>
                        <small class="form-text">Enable to make this category visible</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary-modal">
                        <i class="fas fa-save me-1"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ============================================
        // GLOBAL FUNCTIONS - SAME AS PRODUCT PAGE
        // ============================================

        function removeCategoryImage() {
            Swal.fire({
                title: 'Remove Image?',
                text: 'This will delete the image when you save',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                jQuery('#categoryImage').val('');
                jQuery('#imagePreview').attr('src', '');
                jQuery('#imagePreviewContainer').hide();
                jQuery('#imageUploadBox').show();
                jQuery('#removeImage').val('1'); // keep this if you keep the input id
                }
            });
        }


        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const MAX_FILE_SIZE = 100 * 1024; // 100 KB = 102400 bytes

            if (file.size > MAX_FILE_SIZE) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Image must be less than 100KB',
                confirmButtonColor: '#22c55e'
            });
            event.target.value = '';
            return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                jQuery('#imagePreview').attr('src', e.target.result);
                jQuery('#imagePreviewContainer').show();
                jQuery('#imageUploadBox').hide();
                jQuery('#removeImage').val('0');
            };
            reader.readAsDataURL(file);
        }

        function openCreateModal() {
            jQuery('#categoryForm')[0].reset();
            jQuery('#formMethod').val('POST');
            jQuery('#categoryId').val('');
            jQuery('#modalTitleText').text('Create New Category');
            jQuery('#categoryStatus').prop('checked', true);
            jQuery('#imagePreview').attr('src', '');
            jQuery('#imagePreviewContainer').hide();
            jQuery('#imageUploadBox').show();
            jQuery('#removeImage').val('0');
            jQuery('#modalIcon').removeClass('fa-edit').addClass('fa-folder-plus');
        }

        function editCategory(category) {
            jQuery('#formMethod').val('PUT');
            jQuery('#categoryId').val(category.id);
            jQuery('#categoryName').val(category.name);
            jQuery('#categoryStatus').prop('checked', category.is_active == 1);
            jQuery('#parentCategory').val(category.parent_id || '');
            jQuery('#modalTitleText').text('Edit Category');
            jQuery('#modalIcon').removeClass('fa-folder-plus').addClass('fa-edit');

            if (category.image_url) {
                jQuery('#imagePreview').attr('src', category.image_url);
                jQuery('#imagePreviewContainer').show();
                jQuery('#imageUploadBox').hide();
            } else {
                jQuery('#imagePreview').attr('src', '');
                jQuery('#imagePreviewContainer').hide();
                jQuery('#imageUploadBox').show();
            }
            jQuery('#removeImage').val('0');
            jQuery('#categoryModal').modal('show');
        }

        function toggleStatus(id, currentStatus) {
            const newStatus = currentStatus ? 0 : 1;
            Swal.fire({
                title: 'Toggle Status?',
                text: `Change to ${newStatus ? 'Active' : 'Inactive'}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e'
            }).then((result) => {
                if (result.isConfirmed) {
                    jQuery.ajax({
                        url: `/admin/categories/${id}/toggle-status`,
                        method: 'POST',
                        data: {
                            _token: jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        success: () => {
                            Swal.fire('Updated!', 'Status changed', 'success').then(() => location
                                .reload());
                        }
                    });
                }
            });
        }

        function deleteCategory(id) {
            Swal.fire({
                title: 'Delete Category?',
                text: 'This will delete all subcategories too!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    jQuery.ajax({
                        url: `/admin/categories/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        success: () => {
                            Swal.fire('Deleted!', 'Category deleted', 'success').then(() => location
                                .reload());
                        }
                    });
                }
            });
        }

        // ============================================
        // jQuery READY - Event Handlers Only
        // ============================================

        jQuery(document).ready(function($) {
            console.log('✅ Category page ready');

            // Form submit
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                // Fix checkbox
                formData.delete('is_active');
                const isChecked = $('#categoryStatus').is(':checked');
                formData.append('is_active', isChecked ? '1' : '0');

                const url = $('#formMethod').val() === 'PUT' ?
                    `/admin/categories/${$('#categoryId').val()}` :
                    "{{ route('admin.categories.store') }}";

                Swal.fire({
                    title: 'Saving...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000
                        }).then(() => {
                            $('#categoryModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.close();
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $('.error-name').text(errors.name ? errors.name[0] : '');
                            $('.error-image').text(errors.image ? errors.image[0] : '');
                        }
                        Swal.fire('Error', 'Please check the form', 'error');
                    }
                });
            });

            // Filters
            let searchTimeout;
            $('#searchCategory').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => loadCategories(1), 500);
            });

            $('#filterParent, #filterStatus').change(() => loadCategories(1));

            // Pagination
            $(document).on('click', '#paginationContainer .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const page = new URL(url, window.location.origin).searchParams.get('page');
                if (page) loadCategories(page);
            });

            // Load categories
            function loadCategories(page = 1) {
                const $tbody = $('#categoryTableBody');
                $tbody.addClass('table-loading');

                $.ajax({
                    url: '{{ route('admin.categories.index') }}',
                    data: {
                        page: page,
                        search: $('#searchCategory').val(),
                        parent_filter: $('#filterParent').val(),
                        status: $('#filterStatus').val()
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        $tbody.removeClass('table-loading').html(response.html);
                        $('#paginationContainer').html(response.pagination);
                        $('#categoryCount').text(response.total);
                    }
                });
            }

            // View toggle
            $('input[name="viewMode"]').change(function() {
                if ($(this).val() === 'tree') {
                    $('#listView').hide();
                    $('#treeView').show();
                } else {
                    $('#treeView').hide();
                    $('#listView').show();
                }
            });
        });
    </script>
@endpush
