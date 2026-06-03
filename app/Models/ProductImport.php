<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
    protected $fillable = [
        'filename', 'original_filename', 'total_rows',
        'imported_rows', 'skipped_rows', 'failed_rows',
        'errors', 'imported_product_ids', 'status',
        'imported_by', 'rolled_back_at', 'rolled_back_by',
    ];

    protected $casts = [
        'errors'               => 'array',
        'imported_product_ids' => 'array',
        'rolled_back_at'       => 'datetime',
    ];

    public function importedBy()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function rolledBackBy()
    {
        return $this->belongsTo(User::class, 'rolled_back_by');
    }
}
