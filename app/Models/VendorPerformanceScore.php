<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPerformanceScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'period_start',
        'period_end',
        'delivery_score',
        'quality_score',
        'price_score',
        'responsiveness_score',
        'overall_score',
        'notes',
        'scored_by',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'delivery_score' => 'decimal:2',
            'quality_score' => 'decimal:2',
            'price_score' => 'decimal:2',
            'responsiveness_score' => 'decimal:2',
            'overall_score' => 'decimal:2',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scored_by');
    }
}
