@extends('admin.layouts.app')
@section('title', 'Bulk Import Products')

@push('styles')
<style>
.ip { padding: 24px; max-width: 1100px; margin: 0 auto; }

/* ── Header ── */
.ip-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:24px; }
.ip-head h1 { font-size:20px; font-weight:800; color:#111827; margin:0; }
.ip-head p  { font-size:13px; color:#6b7280; margin:4px 0 0; }
.ip-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; color:#6b7280; text-decoration:none; padding:8px 14px; background:#f3f4f6; border-radius:8px; font-weight:600; transition:all 0.2s; border:1px solid #e5e7eb; }
.ip-back:hover { background:#e5e7eb; color:#111827; }

/* ── Cards ── */
.ip-card { background:white; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; margin-bottom:20px; }
.ip-card-head { padding:16px 20px; border-bottom:1px solid #f1f5f9; background:#fafafa; display:flex; align-items:center; gap:10px; }
.ip-card-head h2 { font-size:14px; font-weight:700; color:#111827; margin:0; }
.ip-card-body { padding:20px; }

/* ── Steps ── */
.ip-steps { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:20px; }
.ip-step { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px; display:flex; gap:12px; align-items:flex-start; }
.ip-step-num { width:28px; height:28px; border-radius:50%; background:#08437b; color:white; font-size:12px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.ip-step-title { font-size:13px; font-weight:700; color:#111827; margin:0 0 3px; }
.ip-step-desc { font-size:12px; color:#6b7280; margin:0; line-height:1.4; }

/* ── Template download ── */
.ip-template-btn { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:#f0fdf4; border:1.5px solid #86efac; border-radius:10px; color:#15803d; font-size:13px; font-weight:700; text-decoration:none; transition:all 0.2s; }
.ip-template-btn:hover { background:#dcfce7; color:#15803d; transform:translateY(-1px); box-shadow:0 4px 12px rgba(21,128,61,0.15); }

/* ── Upload zone ── */
.ip-dropzone { border:2.5px dashed #d1d5db; border-radius:12px; padding:40px 24px; text-align:center; cursor:pointer; transition:all 0.2s; background:#fafafa; position:relative; }
.ip-dropzone:hover, .ip-dropzone.drag-over { border-color:#08437b; background:#f0f7ff; }
.ip-dropzone input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.ip-dropzone-icon { font-size:36px; color:#d1d5db; margin-bottom:12px; transition:color 0.2s; }
.ip-dropzone:hover .ip-dropzone-icon, .ip-dropzone.drag-over .ip-dropzone-icon { color:#08437b; }
.ip-dropzone-title { font-size:15px; font-weight:700; color:#374151; margin:0 0 4px; }
.ip-dropzone-sub { font-size:13px; color:#9ca3af; }
.ip-file-chosen { font-size:13px; color:#08437b; font-weight:600; margin-top:8px; display:none; }

/* ── Options ── */
.ip-options { display:flex; gap:10px; flex-wrap:wrap; margin-top:16px; }
.ip-option { flex:1; min-width:180px; }
.ip-option label { display:block; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px; }
.ip-option select { width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:9px; font-size:13px; background:white; }
.ip-option select:focus { outline:none; border-color:#08437b; box-shadow:0 0 0 3px rgba(8,67,123,0.08); }

/* ── Submit btn ── */
.ip-submit { margin-top:20px; width:100%; padding:13px; background:#08437b; color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:8px; transition:all 0.2s; }
.ip-submit:hover { background:#063260; }
.ip-submit:disabled { opacity:0.6; cursor:not-allowed; }

/* ── Progress ── */
.ip-progress-wrap { display:none; margin-top:20px; }
.ip-progress-bar-track { background:#f3f4f6; border-radius:99px; height:10px; overflow:hidden; }
.ip-progress-bar-fill  { height:100%; border-radius:99px; background:linear-gradient(90deg,#08437b,#0f6cba); width:0; transition:width 0.4s ease; }
.ip-progress-label { font-size:12px; color:#6b7280; margin-top:6px; font-weight:500; }

/* ── Result panel ── */
.ip-result { display:none; margin-top:20px; border-radius:12px; padding:16px 20px; }
.ip-result.success { background:#f0fdf4; border:1.5px solid #86efac; }
.ip-result.partial { background:#fffbeb; border:1.5px solid #fcd34d; }
.ip-result.error   { background:#fff1f2; border:1.5px solid #fca5a5; }
.ip-result-title { font-size:14px; font-weight:800; margin:0 0 10px; display:flex; align-items:center; gap:8px; }
.ip-stat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:12px; }
.ip-stat { background:white; border-radius:9px; padding:10px 14px; border:1px solid rgba(0,0,0,0.06); }
.ip-stat-val { font-size:20px; font-weight:800; color:#111827; }
.ip-stat-lbl { font-size:11px; color:#6b7280; font-weight:600; }
.ip-rollback-btn-main { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; transition:all 0.2s; margin-top:10px; }
.ip-rollback-btn-main:hover { background:#fecaca; }

/* ── Error table ── */
.ip-error-table { width:100%; border-collapse:collapse; font-size:12px; margin-top:10px; }
.ip-error-table th { background:#f8fafc; padding:7px 12px; font-weight:700; color:#374151; text-align:left; border-bottom:2px solid #e2e8f0; }
.ip-error-table td { padding:7px 12px; border-bottom:1px solid #f1f5f9; vertical-align:top; color:#374151; }
.ip-error-table tr:hover td { background:#f9fafb; }
.err-msg { color:#dc2626; font-size:11px; }

/* ── History ── */
.ih-row { padding:14px 20px; display:flex; align-items:center; gap:14px; flex-wrap:wrap; border-bottom:1px solid #f1f5f9; transition:background 0.15s; }
.ih-row:last-child { border-bottom:none; }
.ih-row:hover { background:#f8fafc; }
.ih-row--rolled { opacity:0.6; }
.ih-meta { flex:1; min-width:200px; }
.ih-filename { font-size:13px; font-weight:700; color:#111827; }
.ih-sub { font-size:11px; color:#9ca3af; margin-top:3px; }
.ih-stats { display:flex; gap:6px; flex-wrap:wrap; }
.ih-pill { padding:3px 9px; border-radius:20px; font-size:11px; font-weight:700; }
.ih-pill--green  { background:#dcfce7; color:#15803d; }
.ih-pill--yellow { background:#fef9c3; color:#854d0e; }
.ih-pill--red    { background:#fee2e2; color:#991b1b; }
.ih-actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
.ih-rollback-btn { padding:6px 12px; background:#fff1f2; color:#991b1b; border:1px solid #fca5a5; border-radius:7px; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.18s; }
.ih-rollback-btn:hover { background:#fee2e2; }
.ih-errors-btn { padding:6px 12px; background:#fffbeb; color:#92400e; border:1px solid #fcd34d; border-radius:7px; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.18s; }
.ih-errors-btn:hover { background:#fef3c7; }
.ih-rolled-badge { font-size:11px; color:#9ca3af; font-style:italic; }

/* ── Columns reference ── */
.col-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:8px; }
.col-item { padding:8px 12px; border-radius:8px; font-size:12px; background:#f8fafc; border:1px solid #e2e8f0; }
.col-name { font-weight:700; color:#111827; font-family:monospace; }
.col-req { font-size:10px; color:#dc2626; font-weight:700; text-transform:uppercase; }
.col-opt { font-size:10px; color:#9ca3af; font-weight:600; text-transform:uppercase; }
.col-desc { font-size:11px; color:#6b7280; margin-top:2px; }

@media(max-width:768px) {
    .ip-steps { grid-template-columns:1fr; }
    .ip-stat-grid { grid-template-columns:1fr 1fr; }
    .ih-row { flex-direction:column; align-items:flex-start; }
}
</style>
@endpush

@section('content')
<div class="ip">

    {{-- Header --}}
    <div class="ip-head">
        <div>
            <h1><i class="fas fa-file-import" style="color:#08437b;margin-right:8px;"></i>Bulk Import Products</h1>
            <p>Upload a CSV file to import multiple products at once</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="ip-back">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    {{-- Steps --}}
    <div class="ip-steps">
        <div class="ip-step">
            <div class="ip-step-num">1</div>
            <div>
                <p class="ip-step-title">Download Template</p>
                <p class="ip-step-desc">Get the CSV template with all required columns and an example row</p>
            </div>
        </div>
        <div class="ip-step">
            <div class="ip-step-num">2</div>
            <div>
                <p class="ip-step-title">Fill in Products</p>
                <p class="ip-step-desc">Add your products to the spreadsheet. Save as CSV when done</p>
            </div>
        </div>
        <div class="ip-step">
            <div class="ip-step-num">3</div>
            <div>
                <p class="ip-step-num" style="width:auto;height:auto;background:none;color:inherit;font-size:inherit;"></p>
                <p class="ip-step-title">Upload & Review</p>
                <p class="ip-step-desc">Upload your file, review results, and rollback if needed</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">

            {{-- Upload Card --}}
            <div class="ip-card">
                <div class="ip-card-head">
                    <i class="fas fa-upload" style="color:#08437b;"></i>
                    <h2>Upload CSV File</h2>
                    <a href="{{ route('admin.products.import.template') }}"
                       class="ip-template-btn" style="margin-left:auto;">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>
                <div class="ip-card-body">
                    <form id="importForm">
                        @csrf
                        <div class="ip-dropzone" id="dropzone">
                            <input type="file" name="csv_file" id="csvFile" accept=".csv,.txt">
                            <div class="ip-dropzone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <p class="ip-dropzone-title">Drop your CSV here or click to browse</p>
                            <p class="ip-dropzone-sub">Supports .csv files up to 5MB &nbsp;·&nbsp; Max 500 rows per import</p>
                            <p class="ip-file-chosen" id="fileChosen"></p>
                        </div>

                        <div class="ip-options">
                            <div class="ip-option">
                                <label>On Duplicate Product</label>
                                <select name="on_duplicate" id="onDuplicate">
                                    <option value="skip">Skip (keep existing)</option>
                                    <option value="update">Update existing</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="ip-submit" id="importSubmit" disabled>
                            <i class="fas fa-file-import"></i>
                            <span id="importBtnText">Select a file first</span>
                        </button>
                    </form>

                    {{-- Progress --}}
                    <div class="ip-progress-wrap" id="progressWrap">
                        <div class="ip-progress-bar-track">
                            <div class="ip-progress-bar-fill" id="progressFill"></div>
                        </div>
                        <p class="ip-progress-label" id="progressLabel">Processing…</p>
                    </div>

                    {{-- Result --}}
                    <div class="ip-result" id="importResult">
                        <p class="ip-result-title" id="resultTitle"></p>
                        <div class="ip-stat-grid" id="resultStats"></div>
                        <button class="ip-rollback-btn-main" id="rollbackBtn" style="display:none;">
                            <i class="fas fa-undo"></i> Undo this import
                        </button>
                        <div id="errorTableWrap"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-5">

            {{-- Columns reference --}}
            <div class="ip-card" style="margin-bottom:20px;">
                <div class="ip-card-head">
                    <i class="fas fa-table" style="color:#08437b;"></i>
                    <h2>Column Reference</h2>
                    <span style="margin-left:auto;font-size:11px;background:#fef3c7;color:#92400e;padding:3px 9px;border-radius:20px;font-weight:700;">
                        Max 500 rows
                    </span>
                </div>
                <div class="ip-card-body" style="padding:16px;">
                    <div class="col-grid">
                        @foreach([
                            ['name','Required','Product name'],
                            ['category','Required','Must match exactly'],
                            ['price','Required','Selling price'],
                            ['stock','Required','Integer quantity'],
                            ['unit','Required','pcs,kg,g,l,ml…'],
                            ['brand','Optional','Must match exactly'],
                            ['mrp','Optional','Original price'],
                            ['cost','Optional','Cost price'],
                            ['tax_rate','Optional','e.g. 20'],
                            ['barcode','Optional','EAN/UPC'],
                            ['description','Optional','Product description'],
                            ['is_active','Optional','1 or 0'],
                            ['is_featured','Optional','1 or 0'],
                            ['is_popular','Optional','1 or 0'],
                            ['is_weight_based','Optional','1 or 0'],
                            ['price_per_kg','Optional','If weight-based'],
                            ['min_weight','Optional','If weight-based'],
                            ['max_weight','Optional','If weight-based'],
                            ['customer_groups','Optional','Comma-separated names'],
                        ] as [$col, $req, $desc])
                        <div class="col-item">
                            <div class="col-name">{{ $col }}</div>
                            <div class="{{ $req === 'Required' ? 'col-req' : 'col-opt' }}">{{ $req }}</div>
                            <div class="col-desc">{{ $desc }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- History --}}
    <div class="ip-card">
        <div class="ip-card-head">
            <i class="fas fa-history" style="color:#08437b;"></i>
            <h2>Import History</h2>
        </div>
        <div id="historyContainer">
            @include('admin.products.partials.import-history', compact('history'))
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(function () {
    const CSRF = $('meta[name="csrf-token"]').attr('content');
    let currentImportId = null;

    // ── File input ──
    $('#csvFile').on('change', function () {
        const file = this.files[0];
        if (file) {
            $('#fileChosen').text('✓ ' + file.name).show();
            $('#importSubmit').prop('disabled', false);
            $('#importBtnText').text('Import Products');
            $('#dropzone').css({ borderColor: '#08437b', background: '#f0f7ff' });
        }
    });

    // Drag & drop
    const dz = document.getElementById('dropzone');
    dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
    dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
    dz.addEventListener('drop', e => {
        e.preventDefault();
        dz.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && (file.name.endsWith('.csv') || file.name.endsWith('.txt'))) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('csvFile').files = dt.files;
            $('#csvFile').trigger('change');
        }
    });

    // ── Submit ──
    $('#importForm').on('submit', function (e) {
        e.preventDefault();
        const file = document.getElementById('csvFile').files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('csv_file', file);
        formData.append('on_duplicate', $('#onDuplicate').val());
        formData.append('_token', CSRF);

        $('#importSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing…');
        $('#importResult').hide();
        $('#progressWrap').show();
        animateProgress();

        $.ajax({
            url:         '{{ route("admin.products.import.process") }}',
            type:        'POST',
            data:        formData,
            processData: false,
            contentType: false,
            success: function (r) {
                stopProgress(100);
                currentImportId = r.import_id;
                showResult(r);
                refreshHistory();
            },
            error: function (xhr) {
                stopProgress(0);
                const msg = xhr.responseJSON?.message ?? 'Upload failed. Please try again.';
                showErrorResult(msg);
            },
            complete: function () {
                $('#importSubmit').prop('disabled', false)
                    .html('<i class="fas fa-file-import"></i> <span id="importBtnText">Import Again</span>');
            }
        });
    });

    let progressTimer, progressVal = 0;
    function animateProgress() {
        progressVal = 0;
        clearInterval(progressTimer);
        progressTimer = setInterval(() => {
            progressVal = Math.min(progressVal + Math.random() * 8, 85);
            $('#progressFill').css('width', progressVal + '%');
            $('#progressLabel').text('Processing… ' + Math.round(progressVal) + '%');
        }, 200);
    }

    function stopProgress(final) {
        clearInterval(progressTimer);
        $('#progressFill').css('width', final + '%');
        $('#progressLabel').text(final === 100 ? 'Complete!' : 'Done');
        setTimeout(() => $('#progressWrap').hide(), 800);
    }

    function showResult(r) {
        const hasFailed  = r.failed_count  > 0;
        const hasSkipped = r.skipped_count > 0;
        const cls = r.imported_count === 0 ? 'error' : (hasFailed || hasSkipped ? 'partial' : 'success');

        const icon = cls === 'success' ? '✅' : cls === 'partial' ? '⚠️' : '❌';
        $('#resultTitle').html(icon + ' ' + r.message);

        $('#resultStats').html(`
            <div class="ip-stat">
                <div class="ip-stat-val" style="color:#15803d;">${r.imported_count}</div>
                <div class="ip-stat-lbl">Imported</div>
            </div>
            <div class="ip-stat">
                <div class="ip-stat-val" style="color:#92400e;">${r.skipped_count}</div>
                <div class="ip-stat-lbl">Skipped</div>
            </div>
            <div class="ip-stat">
                <div class="ip-stat-val" style="color:#991b1b;">${r.failed_count}</div>
                <div class="ip-stat-lbl">Failed</div>
            </div>
        `);

        if (r.can_rollback) {
            $('#rollbackBtn').show().off('click').on('click', () => rollback(currentImportId, r.imported_count));
        } else {
            $('#rollbackBtn').hide();
        }

        if (r.errors && r.errors.length) {
            let rows = r.errors.map(e =>
                `<tr><td>${e.row}</td><td>${e.name}</td><td class="err-msg">${Array.isArray(e.errors) ? e.errors.join(', ') : e.errors}</td></tr>`
            ).join('');
            $('#errorTableWrap').html(`
                <div style="margin-top:12px;">
                    <p style="font-size:12px;font-weight:700;color:#374151;margin:0 0 6px;">Row issues:</p>
                    <div style="max-height:220px;overflow-y:auto;border:1px solid #e5e7eb;border-radius:8px;">
                        <table class="ip-error-table">
                            <thead><tr><th>Row</th><th>Name</th><th>Issue</th></tr></thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                </div>
            `);
        } else {
            $('#errorTableWrap').html('');
        }

        $('#importResult').attr('class', 'ip-result ' + cls).show();
    }

    function showErrorResult(msg) {
        $('#resultTitle').html('❌ ' + msg);
        $('#resultStats, #errorTableWrap').html('');
        $('#rollbackBtn').hide();
        $('#importResult').attr('class', 'ip-result error').show();
    }

    // ── Rollback ──
    function rollback(importId, count) {
        Swal.fire({
            title: 'Undo Import?',
            html:  `This will soft-delete <strong>${count} products</strong> that were just imported. This cannot be undone from here — you would need to restore from the database.<br><br>Are you sure?`,
            icon:  'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, Roll Back',
        }).then(res => {
            if (!res.isConfirmed) return;
            $.ajax({
                url:  '{{ url("admin/products/import") }}/' + importId + '/rollback',
                type: 'POST',
                data: { _token: CSRF },
                success: r => {
                    Swal.fire({ icon:'success', title:'Rolled Back', text: r.message, timer:2000, showConfirmButton:false });
                    $('#rollbackBtn').hide();
                    $('#importResult').attr('class', 'ip-result').hide();
                    refreshHistory();
                },
                error: xhr => {
                    Swal.fire({ icon:'error', title:'Failed', text: xhr.responseJSON?.message ?? 'Rollback failed', confirmButtonColor:'#08437b' });
                }
            });
        });
    }

    // ── History rollback (from history rows) ──
    $(document).on('click', '.ih-rollback-btn', function () {
        const id    = $(this).data('id');
        const count = $(this).data('count');
        rollback(id, count);
    });

    // ── History errors ──
    $(document).on('click', '.ih-errors-btn', function () {
        const raw    = $(this).attr('data-errors');
        const errors = JSON.parse(raw);
        let rows = errors.map(e =>
            `<tr><td style="padding:6px 10px;">${e.row}</td><td style="padding:6px 10px;">${e.name}</td><td style="padding:6px 10px;color:#dc2626;font-size:11px;">${Array.isArray(e.errors) ? e.errors.join(', ') : e.errors}</td></tr>`
        ).join('');

        Swal.fire({
            title: 'Import Issues',
            html: `<div style="max-height:350px;overflow-y:auto;text-align:left;">
                <table style="width:100%;border-collapse:collapse;font-size:12px;">
                    <thead><tr style="background:#f8fafc;">
                        <th style="padding:6px 10px;font-weight:700;border-bottom:2px solid #e2e8f0;">Row</th>
                        <th style="padding:6px 10px;font-weight:700;border-bottom:2px solid #e2e8f0;">Product</th>
                        <th style="padding:6px 10px;font-weight:700;border-bottom:2px solid #e2e8f0;">Issue</th>
                    </tr></thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`,
            width: '600px',
            confirmButtonColor: '#08437b',
        });
    });

    // ── History pagination ──
    $(document).on('click', '#historyContainer .pagination a', function (e) {
        e.preventDefault();
        const page = new URL($(this).attr('href'), location.href).searchParams.get('page');
        if (page) refreshHistory(page);
    });

    function refreshHistory(page) {
        $.get('{{ route("admin.products.import.history") }}', { page: page || 1 }, function (r) {
            $('#historyContainer').html(r.html);
        });
    }
});
</script>
@endpush
