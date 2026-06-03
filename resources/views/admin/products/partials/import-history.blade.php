@forelse($history as $import)
<div class="ih-row {{ $import->status === 'rolled_back' ? 'ih-row--rolled' : '' }}"
     data-import-id="{{ $import->id }}">

    <div class="ih-meta">
        <div class="ih-filename">
            <i class="fas fa-file-csv" style="color:#059669;margin-right:6px;"></i>
            {{ $import->original_filename }}
        </div>
        <div class="ih-sub">
            By {{ $import->importedBy->name ?? '—' }} &nbsp;·&nbsp;
            {{ $import->created_at->format('d M Y, H:i') }}
        </div>
    </div>

    <div class="ih-stats">
        <span class="ih-pill ih-pill--green">✓ {{ $import->imported_rows }} imported</span>
        @if($import->skipped_rows > 0)
            <span class="ih-pill ih-pill--yellow">⤼ {{ $import->skipped_rows }} skipped</span>
        @endif
        @if($import->failed_rows > 0)
            <span class="ih-pill ih-pill--red">✗ {{ $import->failed_rows }} failed</span>
        @endif
    </div>

    <div class="ih-actions">
        @if($import->status === 'completed' && $import->imported_rows > 0)
            <button class="ih-rollback-btn" data-id="{{ $import->id }}"
                    data-count="{{ $import->imported_rows }}">
                <i class="fas fa-undo"></i> Rollback
            </button>
        @elseif($import->status === 'rolled_back')
            <span class="ih-rolled-badge">
                <i class="fas fa-ban"></i> Rolled back
                {{ $import->rolled_back_at?->format('d M, H:i') }}
            </span>
        @endif

        @if($import->errors && count($import->errors))
            <button class="ih-errors-btn" data-id="{{ $import->id }}"
                data-errors='{{ json_encode($import->errors, JSON_HEX_APOS | JSON_HEX_TAG) }}'>
                <i class="fas fa-exclamation-triangle"></i>
                View {{ count($import->errors) }} issue(s)
            </button>
        @endif
    </div>
</div>
@empty
<p style="text-align:center;color:#9ca3af;padding:24px;font-size:13px;">No imports yet.</p>
@endforelse

@if($history->hasPages())
<div style="padding:12px 20px;border-top:1px solid #f1f5f9;">
    {{ $history->links('pagination::bootstrap-5') }}
</div>
@endif
